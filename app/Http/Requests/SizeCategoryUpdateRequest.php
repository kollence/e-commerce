<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeCategoryUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:size_categories,name'],
            'slug' => ['required', 'string', 'unique:size_categories,slug'],
        ];
    }
}
