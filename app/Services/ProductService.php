<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function listAll()
    {
        return $this->productRepository->all();
    }

    public function findById($id)
    {
        return $this->productRepository->find($id);
    }
    public function store(array $data)
    {
        return $this->productRepository->create($data);
    }
    public function update($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }
    public function destroy($id)
    {
        return $this->productRepository->delete($id);
    }
    public function listVenda()
    {
        return $this->productRepository->allVenda();
    }
}
