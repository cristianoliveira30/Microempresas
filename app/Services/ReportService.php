<?php

namespace App\Services;

use App\Repositories\Interfaces\ReportRepositoryInterface;

class ReportService
{
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {}

    public function listAll()
    {
        return $this->reportRepository->all();
    }

    public function findById($id)
    {
        return $this->reportRepository->find($id);
    }
    public function store(array $data)
    {
        return $this->reportRepository->create($data);
    }
    public function update($id, array $data)
    {
        return $this->reportRepository->update($id, $data);
    }
    public function destroy($id)
    {
        return $this->reportRepository->delete($id);
    }
}
