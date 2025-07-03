<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaixaRequest;
use App\Http\Requests\UpdateCaixaRequest;
use App\Models\Caixa;
use App\Models\Pedido;
use App\Services\CaixaService;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpParser\Node\Stmt\TryCatch;

class CaixaController extends Controller
{
    // Injeta a classe de serviço que lida com a lógica de negócio
    public function __construct(
        protected CaixaService $caixaService,
        protected ProductService $productService
    ) {}

    // Lista todos os produtos
    public function index()
    {
        $pedidos = Pedido::with('produtos')
            ->where('status', 'pendente')
            ->get()
            ->map(function ($pedido) {
                // Soma todas as quantidades da tabela pivô
                $pedido->quantity = $pedido->produtos->sum('pivot.quantity');
                return $pedido;
            });

        $produtos = $this->productService->listAll();

        return Inertia::render('Caixa/Caixa', [
            'pedidos' => $pedidos,
            'produtos' => $produtos
        ]);
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

    public function confirmPayment(Pedido $pedido)
    {
        try {
            // Atualiza o status do pedido para 'pago'
            $pedido->update(['status' => 'pago']);

            // Retorna a resposta de sucesso
            return response()->json([
                'message' => 'Pedido pago com sucesso.',
                'pedido' => $pedido,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao confirmar pagamento.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Pedido $pedido)
    {
        try {
            $pedido->delete();

            return response()->json([
                'message' => 'Pedido excluído com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir o pedido.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Remove um produto
    public function destroyProdutoPedido(Pedido $pedido, $produtoId)
    {
        try {
            $pedido->produtos()->detach($produtoId);
            return response()->json([
                'message' => 'Produto removed from pedido successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error removing produto from pedido.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPedidoData()
    {
        try {
            $pedidos = Pedido::with('produtos')
            ->where('status', 'pendente')
            ->get()
            ->map(function ($pedido) {
                // Soma todas as quantidades da tabela pivô
                $pedido->quantity = $pedido->produtos->sum('pivot.quantity');
                return $pedido;
            });

            return response()->json($pedidos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter dados dos pedidos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pagarPedido(Request $request, $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
    
            $pedido->status = 'concluido';
            $pedido->payment_method = $request->input('method'); // 'pix' ou 'cash'
            $pedido->save();
    
            // Dá baixa no estoque usando o service
            $produtosVendidos = $pedido->produtos->map(function($produto) {
                return [
                    'id' => $produto->id,
                    'quantity' => $produto->pivot->quantity,
                ];
            })->toArray();
    
            $baixarEstoque = $this->productService->baixarEstoque($produtosVendidos);
            $baixarEstoque['success'] !== true ?? throw new Exception($baixarEstoque['message']);
    
            return response()->json([
                'success' => true,
                'pedido' => $pedido,
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao pagar o pedido.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
