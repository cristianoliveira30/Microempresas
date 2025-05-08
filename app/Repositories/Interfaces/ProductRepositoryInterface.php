<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id): ?Product;
    public function create(array $data): Product;
    public function update($id, array $data): bool;
    public function delete($id): bool;
}