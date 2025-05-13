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
        return $this->vendaRepository->update($id, $data);
    }
    public function destroy($id)
    {
        return $this->vendaRepository->delete($id);
    }
}
