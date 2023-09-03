<?php

namespace App\Http\Requests;

use App\Rules\UnsignedNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'number' => ['sometimes', 'string', 'alpha_num'],
            'due_date' => ['sometimes', 'date'],
            'create_date' => ['sometimes', 'date'],
            'payment_date' => ['sometimes', 'date'],
            'product_id' => ['sometimes', 'exists:products,id'],
            'section_id' => ['sometimes', 'exists:sections,id'],
            'collection_amount' => ['sometimes', 'numeric', 'gt:0', 'gt:commission_amount'],
            'commission_amount' => ['sometimes', 'numeric', 'gt:0'],
            'discount' => ['sometimes', 'numeric', 'gt:0'],
            'VAT_rate' => ['sometimes', 'string', 'regex:/%\d+/'],
            'state' => ['sometimes', new Enum(InvoiceState::class)],
            'note' => ['sometimes', 'string'],
        ];
    }
}
