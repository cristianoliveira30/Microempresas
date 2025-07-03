<?php

namespace App\Services;

use App\Repositories\Interfaces\VendaRepositoryInterface;

class VendaService
{
    public function __construct(
        protected VendaRepositoryInterface $vendaRepository
    ) {}

    public function listAll()
    {
        return $this->vendaRepository->all();
    }

    public function findById($id)
    {
        return $this->vendaRepository->find($id);
    }
    public function store(array $data)
    {
        return $this->vendaRepository->create($data);
    }
    public function update($id, array $data)
    {
        try {
            $produtos = $data['products'] ?? [];
            unset($data['products']);
    
            $venda = $this->vendaRepository->update($id, $data);
    
            if ($venda && !empty($produtos)) {
                $this->vendaRepository->syncProdutos($venda, $produtos);
                // Atualize o modelo para trazer os produtos atualizados
                $venda = $this->vendaRepository->find($id);
                $venda->load('produtos');
            }
    
            return $venda;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao atualizar a venda: ' . $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine());
        }
    }
    public function destroy($id)
    {
        return $this->vendaRepository->delete($id);
    }
}
