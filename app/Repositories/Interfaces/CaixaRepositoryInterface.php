<?php

namespace App\Repositories\Interfaces;

use App\Models\Caixa;

interface CaixaRepositoryInterface
{
    public function all();
    public function find(int $id): ?Caixa;
    public function create(array $data): Caixa;
    public function update($id, array $data): bool;
    public function delete($id): bool;
}