<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendaRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado a fazer essa requisição.
     */
    public function authorize(): bool
    {
        // Aqui você pode aplicar lógicas de permissão se desejar.
        return true;
    }

    /**
     * Regras de validação para a criação de uma venda.
     */
    public function rules(): array
    {
        return [
            'alcunha' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.name' => 'required|string|exists:products,name',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0.01',

            'total' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'products.required' => 'É necessário selecionar pelo menos um produto.',
            'products.*.id.required' => 'O ID de cada produto é obrigatório.',
            'products.*.id.exists' => 'Um ou mais produtos não existem.',
            'products.*.name.exists' => 'O nome do produto é inválido.',
            'products.*.quantity.min' => 'A quantidade deve ser no mínimo 1.',
            'total.required' => 'O valor total da venda é obrigatório.',
        ];
    }
}
 