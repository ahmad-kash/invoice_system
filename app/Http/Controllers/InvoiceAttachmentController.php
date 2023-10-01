<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Services\Invoice\InvoiceAttachmentService;
use Illuminate\Http\Request;

class InvoiceAttachmentController extends Controller
{

    protected function resourceAbilityMap()
    {
        return [
            'show' => 'view',
            'download' => 'view',
            'store' => 'create',
            'destroy' => 'delete',
        ];
    }

    public function __construct(private InvoiceAttachmentService $attachmentService)
    {
        $this->authorizeResource(InvoiceAttachment::class, 'attachment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Invoice $invoice)
    {
        $this->attachmentService->store($invoice, $request->file('file'));
        return redirect()->route('invoices.show', ['invoice' => $invoice->id])->with('successMessage', 'تم اضافة الملف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceAttachment $attachment)
    {
        return $this->attachmentService->show($attachment->path);
    }

    /**
     * download the specified resource.
     */
    public function download(InvoiceAttachment $attachment)
    {
        return $this->attachmentService->download($attachment->path);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceAttachment $attachment)
    {
        if ($this->attachmentService->delete($attachment))
            return redirect()->route('invoices.show', ['invoice' => $attachment->invoice->id])->with('successMessage', 'تم حذف الملف بنجاح');
        return back()->with('errorMessage', 'حصل مشكلة و لم يتم حذف الملف');
    }
}
