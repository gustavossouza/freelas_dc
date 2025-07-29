<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PdfRequest extends FormRequest
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
            'template' => 'required|string|in:invoice,receipt,contract,report',
            'data' => 'required|array',
            'filename' => 'nullable|string|max:255',
            'format' => 'nullable|string|in:A4,A3,letter,legal',
            'orientation' => 'nullable|string|in:portrait,landscape',
            'include_header' => 'nullable|boolean',
            'include_footer' => 'nullable|boolean',
            'watermark' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:4|max:50',
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
            'template.required' => 'The template is required.',
            'template.string' => 'The template must be a string.',
            'template.in' => 'The template must be invoice, receipt, contract, or report.',
            'data.required' => 'The data is required.',
            'data.array' => 'The data must be an array.',
            'filename.string' => 'The filename must be a string.',
            'filename.max' => 'The filename may not be greater than 255 characters.',
            'format.string' => 'The format must be a string.',
            'format.in' => 'The format must be A4, A3, letter, or legal.',
            'orientation.string' => 'The orientation must be a string.',
            'orientation.in' => 'The orientation must be portrait or landscape.',
            'include_header.boolean' => 'The include header field must be true or false.',
            'include_footer.boolean' => 'The include footer field must be true or false.',
            'watermark.string' => 'The watermark must be a string.',
            'watermark.max' => 'The watermark may not be greater than 100 characters.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 4 characters.',
            'password.max' => 'The password may not be greater than 50 characters.',
        ];
    }
} 