<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Nome obrigatório, texto, no máximo 255 caracteres
            'name' => 'required|string|max:255',

            // Preço obrigatório, valor numérico, no mínimo 0
            'price' => 'required|numeric|min:0',

            // Estoque obrigatório, inteiro, no mínimo 0
            'stock' => 'required|integer|min:0',
        ];
    }
}
