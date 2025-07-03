<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendaRequest;
use App\Http\Requests\UpdateVendaRequest;
use App\Models\Pedido;
use App\Models\Venda;
use App\Services\ProductService;
use App\Services\VendaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendaController extends Controller
{
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

    // Cria um novo pedido
    public function store(StoreVendaRequest $request)
    {
        $pedido = Pedido::create([
            'alcunha' => $request->alcunha,
            'total' => $request->total,
        ]);

        foreach ($request->products as $produto) {
            $pedido->produtos()->attach($produto['id'], [
                'quantity' => $produto['quantity'],
                'unit_price' => $produto['price'],
            ]);
        }

        return response()->json(['message' => 'Pedido salvo com sucesso.']);
    }

    // Atualiza um produto existente
    public function update(UpdateVendaRequest $request, Pedido $pedido)
    {
        try {
            $updatedVenda = $this->vendaService->update($pedido->id, $request->validated());
    
            return response()->json([
                'message' => 'Venda updated successfully.',
                'venda' => $updatedVenda,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao atualizar a venda: ' . $e->getMessage() . ' - ' . $e->getLine());
        }
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
