<?php

namespace App\Filters;

use App\Enums\InvoiceState;
use Carbon\Carbon;

class InvoiceFilter extends Filter
{

    protected array $allowedFilter = ['number', 'section', 'product', 'state', 'from', 'to'];

    public function number($filterValue)
    {
        return $this->where('number', 'like', '%' . $filterValue . '%');
    }
    public function to($filterValue)
    {
        return $this->where('payment_date', '<=', Carbon::parse($filterValue));
    }
    public function from($filterValue)
    {
        return $this->where('payment_date', '>=', Carbon::parse($filterValue));
    }
    public function section($filterValue)
    {
        return $this->whereRelation('section', 'name', 'like', '%' . $filterValue . '%');
    }
    public function product($filterValue)
    {
        return $this->whereRelation('product', 'name', 'like', '%' . $filterValue . '%');
    }
    public function state($filterValue)
    {
        return $this->where('state', InvoiceState::fromString($filterValue));
    }
}
