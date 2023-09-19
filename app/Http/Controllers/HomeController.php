<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'totalInvoicesSum' => Invoice::sum('total'),
            'unPaidInvoicesSum' => Invoice::where('state', InvoiceState::unPaid->value)->sum('total'),
            'paidInvoicesSum' => Invoice::where('state', InvoiceState::paid->value)->sum('total'),
            'partiallyPaidInvoicesSum' => Invoice::where('state', InvoiceState::partiallyPaid->value)->sum('total'),

            'totalInvoicesCount' => Invoice::count(),
            'unPaidInvoicesCount' => Invoice::where('state', InvoiceState::unPaid->value)->count(),
            'paidInvoicesCount' => Invoice::where('state', InvoiceState::paid->value)->count(),
            'partiallyPaidInvoicesCount' => Invoice::where('state', InvoiceState::partiallyPaid->value)->count(),
        ]);
    }
}
