<?php

namespace App\Repositories;

use App\Models\Caixa;
use App\Repositories\Interfaces\CaixaRepositoryInterface;

class CaixaRepository implements CaixaRepositoryInterface
{
    public function all()
    {
        return Caixa::all();
    }

    public function find(int $id): ?Caixa
    {
        return Caixa::find($id);
    }

    public function create(array $data): Caixa
    {
        return Caixa::create($data);
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