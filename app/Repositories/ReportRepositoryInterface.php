<?php

namespace App\Repositories;

use App\Models\Report;
use App\Repositories\Interfaces\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    public function all()
    {
        return Report::all();
    }

    public function find(int $id): ?Report
    {
        return Report::find($id);
    }

    public function create(array $data): Report
    {
        return Report::create($data);
    }
    public function update($id, array $data): bool
    {
        $report = $this->find($id);
        if ($report) {
            return $report->update($data);
        }
        return false;
    }
    public function delete($id): bool
    {
        $report = $this->find($id);
        if ($report) {
            return $report->delete();
        }
        return false;
    }
}