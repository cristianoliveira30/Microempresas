<?php

namespace App\Repositories\Interfaces;

use App\Models\Venda;

interface VendaRepositoryInterface
{
    public function all();
    public function find(int $id): ?Venda;
    public function create(array $data): Venda;
    public function update($id, array $data): ?Venda;
    public function delete($id): bool;
    public function syncProdutos(Venda $venda, array $produtos): void;
}