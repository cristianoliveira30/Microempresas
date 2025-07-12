<?php

namespace App\Services;

use App\Repositories\Interfaces\ReportRepositoryInterface;

class ReportService
{
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {}

    public function listAll(): array 
    {
        return 
        [
            'vendaPedido'           =>  $this->reportRepository->getVendaPedido(),
            'vendaPeriodo'          =>  $this->reportRepository->getVendaPeriodo(),
            'vendaProduto'          =>  $this->reportRepository->getVendaProduto(),
            'gestaoLucro'           =>  $this->reportRepository->getGestaoLucro(),
            'entrada'               =>  $this->reportRepository->getEntrada(),
            'vendaparada'           =>  $this->reportRepository->getVendaParada(),
            'custoestoque'          =>  $this->reportRepository->getCustoEstoque(),
            'historicoalteracao'    =>  $this->reportRepository->getHistoricoProduto(),
            'saida'                 =>  $this->reportRepository->getSaida(),
        ];
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
