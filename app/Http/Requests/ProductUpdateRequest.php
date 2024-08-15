<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:products,name'],
            'slug' => ['required', 'string', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'care_instructions' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'is_active' => ['required'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
        ];
    }
}
