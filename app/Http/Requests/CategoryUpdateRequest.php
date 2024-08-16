<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:categories,name'],
            'slug' => ['required', 'string', 'unique:categories,slug'],
            'is_active' => ['required'],
            'parent_category_id' => ['nullable', 'integer', 'exists:parent_categories,id'],
            'size_category_id' => ['nullable', 'integer', 'exists:size_categories,id'],
        ];
    }
}
