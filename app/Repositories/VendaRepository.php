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
    public function update($id, array $data): bool
    {
        $venda = $this->find($id);
        if ($venda) {
            return $venda->update($data);
        }
        return false;
    }
    public function delete($id): bool
    {
        $venda = $this->find($id);
        if ($venda) {
            return $venda->delete();
        }
        return false;
    }
}