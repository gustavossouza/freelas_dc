<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstallmentRequest extends FormRequest
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
            'sell_id' => 'required|exists:sells,id',
            'installment_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,paid,overdue',
            'paid_date' => 'nullable|date',
            'notes' => 'nullable|string',
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
            'sell_id.required' => 'The sell is required.',
            'sell_id.exists' => 'The selected sell does not exist.',
            'installment_number.required' => 'The installment number is required.',
            'installment_number.integer' => 'The installment number must be an integer.',
            'installment_number.min' => 'The installment number must be at least 1.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'status.in' => 'The status must be pending, paid, or overdue.',
            'paid_date.date' => 'The paid date must be a valid date.',
            'notes.string' => 'The notes must be a string.',
        ];
    }
} 