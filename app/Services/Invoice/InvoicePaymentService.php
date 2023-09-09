<?php

namespace App\Services\Invoice;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Models\PaymentDetail;
use Illuminate\Testing\Exceptions\InvalidArgumentException;
use Nette\ArgumentOutOfRangeException;

class InvoicePaymentService
{
    private float $allPayments;

    private function amountsIsSmallerThanTotal(Invoice $invoice): bool
    {
        return $invoice->total >= $this->allPayments;
    }

    private function changeInvoiceState(Invoice $invoice): bool
    {
        return $invoice->update(['state' => $this->getNewState($invoice)]);
    }

    private function makeAPayment(Invoice $invoice, float $amount, ?string $note): bool
    {
        $invoice->paymentDetails()->create([
            'user_id' => auth()->id(),
            'state' => $this->getNewState($invoice),
            'amount' => $amount,
            'note' => $note ?? '',
        ]);

        $this->changeInvoiceState($invoice);
        return true;
    }

    public function setAllPayments(Invoice $invoice, float $amount): void
    {
        $previousPaymentAmount = PaymentDetail::where('invoice_id', $invoice->id)->sum('amount');
        $this->allPayments = $previousPaymentAmount + $amount;
    }

    public function pay(Invoice $invoice, float $amount, ?string $note = ""): bool
    {
        $this->setAllPayments($invoice, $amount);
        if ($amount ==  0)
            throw new InvalidArgumentException("the amount must be bigger then 0");

        if (!$this->amountsIsSmallerThanTotal($invoice, $amount))
            throw new ArgumentOutOfRangeException("the previous payment + amount ={$this->allPayments} must be less than invoice total {$invoice->total}");

        return $this->makeAPayment($invoice, $amount, $note);
    }

    public function getNewState(Invoice $invoice): InvoiceState
    {
        //the unPaid state can't be set cause there is a new payment and it will always be bigger then 0
        if ($this->allPayments == $invoice->total)
            return InvoiceState::paid;

        return InvoiceState::partiallyPaid;
    }

    public function getSumOfAllPreviousPayments(Invoice $invoice)
    {
        $this->setAllPayments($invoice, 0);
        return $this->allPayments;
    }
}