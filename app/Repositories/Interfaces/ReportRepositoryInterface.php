<?php

namespace App\Repositories\Interfaces;

use App\Models\Report;

interface ReportRepositoryInterface
{
    public function all();
    public function find(int $id): ?Report;
    public function create(array $data): Report;
    public function update($id, array $data): bool;
    public function delete($id): bool;
}