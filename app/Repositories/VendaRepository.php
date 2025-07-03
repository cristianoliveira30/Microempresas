<?php

namespace App\Repositories;

use App\Models\Venda;
use App\Repositories\Interfaces\VendaRepositoryInterface;

class VendaRepository implements VendaRepositoryInterface
{
    public function all()
    {
        return Venda::all();
    }

    public function find(int $id): ?Venda
    {
        return Venda::find($id);
    }

    public function create(array $data): Venda
    {
        return Venda::create($data);
    }
    
    public function update($id, array $data): ?Venda
    {
        try {
            $venda = $this->find($id);
            if ($venda) {
                $venda->update($data);
                return $venda->fresh();
            }
            return null;
        } catch (\Exception $e) {
            // Mostra a mensagem original do erro
            throw new \RuntimeException("Erro ao atualizar a venda: " . $e->getMessage() . " - " . $e->getFile() . ":" . $e->getLine(), 0, $e);
        }
    }

    public function delete($id): bool
    {
        $venda = $this->find($id);
        if ($venda) {
            return $venda->delete();
        }
        return false;
    }

    public function syncProdutos(Venda $venda, array $produtos): void
    {
        $syncData = [];
        $novoTotal = 0;
        foreach ($produtos as $produto) {
            $syncData[$produto['id']] = [
                'quantity' => $produto['quantity'],
                'unit_price' => $produto['price'],
            ];
            $novoTotal += $produto['quantity'] * $produto['price'];
        }
        $venda->produtos()->sync($syncData);

        // Atualiza o total no banco
        $venda->total = $novoTotal;
        $venda->save();
    }
}