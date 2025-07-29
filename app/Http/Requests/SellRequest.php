<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'payment_method' => 'nullable|in:pix,cartao_debito,cartao_credito,boleto',
            'installments' => 'nullable|integer|min:1|max:12',
            'discount' => 'nullable|numeric|min:0',
            'sale_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:sale_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
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
            'client_id.required' => 'O cliente é obrigatório.',
            'client_id.exists' => 'O cliente selecionado não existe.',
            'payment_method.in' => 'A forma de pagamento selecionada é inválida.',
            'installments.integer' => 'O número de parcelas deve ser um número inteiro.',
            'installments.min' => 'O número de parcelas deve ser pelo menos 1.',
            'installments.max' => 'O número de parcelas não pode ser maior que 12.',
            'discount.numeric' => 'O desconto deve ser um número.',
            'discount.min' => 'O desconto deve ser pelo menos 0.',
            'sale_date.required' => 'A data da venda é obrigatória.',
            'sale_date.date' => 'A data da venda deve ser uma data válida.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'due_date.after_or_equal' => 'A data de vencimento deve ser igual ou posterior à data da venda.',
            'notes.string' => 'As observações devem ser um texto.',
            'items.required' => 'Pelo menos um item é obrigatório.',
            'items.array' => 'Os itens devem ser uma lista.',
            'items.min' => 'Pelo menos um item é obrigatório.',
            'items.*.product_id.required' => 'O ID do produto é obrigatório.',
            'items.*.product_id.exists' => 'O produto selecionado não existe.',
            'items.*.product_name.required' => 'O nome do produto é obrigatório.',
            'items.*.product_name.string' => 'O nome do produto deve ser um texto.',
            'items.*.product_name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'items.*.description.string' => 'A descrição deve ser um texto.',
            'items.*.quantity.required' => 'A quantidade é obrigatória.',
            'items.*.quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'items.*.quantity.min' => 'A quantidade deve ser pelo menos 1.',
            'items.*.unit_price.required' => 'O preço unitário é obrigatório.',
            'items.*.unit_price.numeric' => 'O preço unitário deve ser um número.',
            'items.*.unit_price.min' => 'O preço unitário deve ser pelo menos 0.',
            'items.*.total_price.required' => 'O preço total é obrigatório.',
            'items.*.total_price.numeric' => 'O preço total deve ser um número.',
            'items.*.total_price.min' => 'O preço total deve ser pelo menos 0.',
        ];
    }
} 