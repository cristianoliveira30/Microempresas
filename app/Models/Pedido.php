<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'alcunha',
        'total',
        'status',
        'payment_method',
    ];
    public function produtos()
    {
        return $this->belongsToMany(Product::class, 'pedido_produto')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}
