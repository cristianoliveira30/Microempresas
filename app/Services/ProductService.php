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
    public function baixarEstoque($produtosVendidos) {
        try {
            foreach ($produtosVendidos as $produto) {
                $produtoModel = $this->productRepository->find($produto['id']);
                if ($produtoModel) {
                    $novaQuantidade = max(0, $produtoModel->estoque - $produto['quantity']);
                    $produtoModel->estoque = $novaQuantidade;
                    $this->productRepository->update($produtoModel->id, ['stock' => $novaQuantidade]);
                }
            }
            return [
                'success' => true,
                'message' => 'Estoque baixado com sucesso.'
            ];
        } catch (\Exception $th) {
            return [
                'success' => false,
                'message' => 'Erro ao baixar o estoque.',
                'error' => $th->getMessage()
            ];
        }
    }
}
