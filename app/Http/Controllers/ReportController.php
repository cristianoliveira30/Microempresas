<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\ReportService;
use Inertia\Inertia;

class ReportController extends Controller
{
    // Injeta a classe de serviço que lida com a lógica de negócio
    public function __construct(
        protected ProductService $productService,
        protected ReportService $reportService
) {}

    // Lista todos os produtos
    public function index()
    {
        $relatorios = $this->reportService->listAll();
        return Inertia::render('Relatorio/Relatorio', [
            'positivos' => [
                'vendaperiodo' => $relatorios['vendaPeriodo'],
                'vendaproduto' => $relatorios['vendaProduto'],
                'gestaolucro' => $relatorios['gestaoLucro'],
                'entrada' => $relatorios['entrada'],
            ],
            'negativos' => [
                'vendaparada' => $relatorios['vendaparada'],
                'custoestoque' => $relatorios['custoestoque'],
                'historicoalteracao' => $relatorios['historicoalteracao'],
                'saida' => $relatorios['saida'],
            ],
        ]);
    }
}
