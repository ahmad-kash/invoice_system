<?php

namespace App\Http\Requests;

use App\Enums\InvoiceState;
use App\Rules\UnsignedNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;

class StoreInvoiceRequest extends FormRequest
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
            'number' => ['required', 'string', 'alpha_num'],
            'due_date' => ['required', 'date'],
            'create_date' => ['required', 'date'],
            'payment_date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'collection_amount' => ['required', 'numeric', 'gt:0', 'gt:commission_amount'],
            'commission_amount' => ['required', 'numeric', 'gt:0'],
            'discount' => ['required', 'numeric', 'gt:0'],
            'VAT_rate' => ['required', 'string', 'regex:/%\d+/'],
            'note' => ['required', 'string'],
            'files' => ['sometimes'],
            'files.*' => [
                'sometimes', 'file', File::types(['png', 'jpg', 'pdf', 'jpeg'])
                    ->min('1mb')
                    ->max('2mb')
            ]

        ];
    }
}
