<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendaRequest;
use App\Http\Requests\UpdateVendaRequest;
use App\Models\Venda;
use App\Services\ProductService;
use App\Services\VendaService;
use Inertia\Inertia;

class VendaController extends Controller{
    // Injeta a classe de serviço que lida com a lógica de negócio
        public function __construct(
        protected VendaService $vendaService,
        protected ProductService $productService
    ) {}

    // Lista todos os produtos
    public function index()
    {
        $products = $this->productService->listVenda();
        return Inertia::render('Venda/Venda', [
            'produtos' => $products
        ]);
    }

    // Cria um novo produto
    public function store(StoreVendaRequest $request)
    {
        $venda = $this->vendaService->store($request->validated());

        return response()->json([
            'message' => 'Venda created successfully.',
            'venda' => $venda,
        ], 201);
    }

    // Atualiza um produto existente
    public function update(UpdateVendaRequest $request, Venda $venda)
    {
        $updatedVenda = $this->vendaService->update($venda, $request->validated());

        return response()->json([
            'message' => 'Venda updated successfully.',
            'venda' => $updatedVenda,
        ]);
    }

    // Remove um produto
    public function destroy(Venda $venda)
    {
        $this->vendaService->destroy($venda);

        return response()->json([
            'message' => 'Venda deleted successfully.',
        ]);
    }
}
