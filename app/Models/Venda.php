<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;
    protected $table = 'pedidos';
    protected $fillable = [
        'alcunha',
    ];
    protected $primaryKey = 'id'; // apenas se o nome for diferente de "id"
    public $timestamps = true;    // ou false, se a tabela não tiver created_at/updated_at

    public function produtos()
    {
        return $this->belongsToMany(
            Product::class,      // Model relacionado
            'pedido_produto',    // Nome da tabela pivô (ajuste se for diferente)
            'pedido_id',         // Chave estrangeira para este model na tabela pivô
            'product_id'         // Chave estrangeira para o model relacionado na tabela pivô
        )->withPivot('quantity'); // Adicione os campos extras da tabela pivô, se existirem
    }
}
