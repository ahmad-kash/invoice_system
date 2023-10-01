<?php

namespace App\Services\Invoice;

use App\Enums\InvoiceState;
use App\Exceptions\Custom\ArgumentOutOfRangeException;
use App\Models\Invoice;
use App\Models\PaymentDetail;
use App\Notifications\Database\Invoice\InvoicePaid;
use App\Notifications\Database\Invoice\InvoicePaidPartially;
use App\Services\Notification\AdminNotifyService;

class InvoicePaymentService
{
    private float $allPayments;

    public function __construct(private AdminNotifyService $adminNotifyService)
    {
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
            throw new ArgumentOutOfRangeException("القيمة يجب ان تكون اكبر من 0");

        if (!$this->amountsIsSmallerThanTotal($invoice, $amount))
            throw new ArgumentOutOfRangeException("القيمة المدفوعة سابقا + القيمة الحالية ={$this->allPayments}, يجب ان تكون اقل او تساوي القيمة الكلية للفاتورة {$invoice->total}");

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

    private function makeAPayment(Invoice $invoice, float $amount, ?string $note): bool
    {
        $invoice->paymentDetails()->create([
            'user_id' => auth()->id(),
            'state' => $this->getNewState($invoice),
            'amount' => $amount,
            'note' => $note ?? '',
        ]);

        $this->changeInvoiceState($invoice);

        $this->sendNotification($invoice, $amount);

        return true;
    }

    private function sendNotification($invoice, $amount): void
    {
        if ($this->getNewState($invoice) === InvoiceState::paid)
            $this->adminNotifyService->notifyAdmins(new InvoicePaid($invoice, auth()->user()));
        else
            $this->adminNotifyService->notifyAdmins(new InvoicePaidPartially($invoice, auth()->user(), $amount));
    }
    private function amountsIsSmallerThanTotal(Invoice $invoice): bool
    {
        return round($invoice->total, 2) > round($this->allPayments, 2);
    }

    private function changeInvoiceState(Invoice $invoice): bool
    {
        return $invoice->update(['state' => $this->getNewState($invoice)]);
    }
}
