<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::all();
    }

    public function find($id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update($id, array $data): bool
    {
        $product = $this->find($id);
        if ($product) {
            // Apenas os campos permitidos
            $allowed = ['name', 'price', 'cost_price', 'description', 'stock', 'is_active'];
            $updateData = array_intersect_key($data, array_flip($allowed));
            return $product->update($updateData);
        }
        return false;
    }

    public function delete($id): bool
    {
        $product = $this->find($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    public function allVenda()
    {
        return Product::select('id', 'name', 'price')->get();
    }
}
