<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255', // novo campo
            'price' => 'required|numeric|min:1',
            'cost_price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|integer|min:0',
        ];
    }
}
