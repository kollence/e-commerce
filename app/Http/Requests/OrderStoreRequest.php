<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
            'total_price' => ['required', 'integer'],
            'shipping_price' => ['required', 'integer'],
            'shipping_method' => ['required', 'string'],
            'payment_method' => ['required', 'string'],
            'payment_status' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'shipping_address' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
