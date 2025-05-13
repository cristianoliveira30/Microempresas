<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaixaRequest;
use App\Http\Requests\UpdateCaixaRequest;
use App\Models\Caixa;
use App\Services\CaixaService;
use Inertia\Inertia;

class CaixaController extends Controller {
    // Injeta a classe de serviço que lida com a lógica de negócio
        public function __construct(
        protected CaixaService $caixaService
    ) {}

    // Lista todos os produtos
    public function index()
    {
        return Inertia::render('Caixa/Caixa');
    }

    // Cria um novo produto
    public function store(StoreCaixaRequest $request)
    {
        $caixa = $this->caixaService->store($request->validated());

        return response()->json([
            'message' => 'Caixa created successfully.',
            'caixa' => $caixa,
        ], 201);
    }

    // Atualiza um produto existente
    public function update(UpdateCaixaRequest $request, Caixa $caixa)
    {
        $updatedCaixa = $this->caixaService->update($caixa, $request->validated());

        return response()->json([
            'message' => 'Caixa updated successfully.',
            'caixa' => $updatedCaixa,
        ]);
    }

    // Remove um produto
    public function destroy(Caixa $caixa)
    {
        $this->caixaService->destroy($caixa);

        return response()->json([
            'message' => 'Caixa deleted successfully.',
        ]);
    }
}
