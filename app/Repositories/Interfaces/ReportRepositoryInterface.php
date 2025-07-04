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
    // metodos personalizados
    public function getVendaPeriodo(): array;
    public function getVendaProduto(): array;
    public function getEntrada(): array;
    public function getGestaoLucro(): array;
    public function getHistoricoProduto(): array;
    public function getVendaParada(): array;
    public function getCustoEstoque(): array;
    public function getSaida(): array;
}