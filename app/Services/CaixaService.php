<?php

namespace App\Services;

use App\Repositories\Interfaces\CaixaRepositoryInterface;

class CaixaService
{
    public function __construct(
        protected CaixaRepositoryInterface $caixaRepository
    ) {}

    public function listAll()
    {
        return $this->caixaRepository->all();
    }

    public function findById($id)
    {
        return $this->caixaRepository->find($id);
    }
    public function store(array $data)
    {
        return $this->caixaRepository->create($data);
    }
    public function update($id, array $data)
    {
        return $this->caixaRepository->update($id, $data);
    }
    public function destroy($id)
    {
        return $this->caixaRepository->delete($id);
    }
}
