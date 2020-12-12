<?php

declare(strict_types=1);

namespace Stock\Controller\Request;

use Hyperf\Validation\Request\FormRequest;

class PurchaseProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|gt:0',
            'unit_price' => 'required|numeric|gt:0',
            'date' => 'string|date_format:d/m/Y',
        ];
    }
}
