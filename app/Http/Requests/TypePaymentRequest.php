<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypePaymentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The payment type name is required.',
            'name.string' => 'The payment type name must be a string.',
            'name.max' => 'The payment type name may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'is_active.boolean' => 'The is active field must be true or false.',
        ];
    }
} 