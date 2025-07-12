<?php

namespace App\Repositories;

use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Product;
use App\Models\Report;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    /**
     * Métodos para obter pedidos concluídos dos últimos 30 dias
     */
    public function getVendaPedido(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        try {
            return Pedido::where('status', 'concluido')
                ->whereBetween('updated_at', [$inicio, $fim])
                ->select('id', 'alcunha', 'total', 'payment_method')
                ->selectRaw('DATE(updated_at) as date')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray();
        } catch (\Exception $th) {
            return [
                'error' => 'Erro ao obter vendas do período: ' . $th->getMessage()
            ];
        }
    }
    /**
     * Métodos para obter venda dos últimos 30 dias
     */
    public function getVendaPeriodo(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        try {
            return DB::table('pedido_produto')
                ->join('pedidos', 'pedidos.id', '=', 'pedido_produto.pedido_id')
                ->join('products', 'products.id', '=', 'pedido_produto.product_id')
                ->whereBetween('pedidos.updated_at', [$inicio, $fim])
                ->where('pedidos.status', 'concluido')
                ->selectRaw('
                    DATE(pedidos.updated_at) as date,
                    products.name as produto,
                    SUM(pedido_produto.quantity) as quantidade,
                    SUM(pedido_produto.unit_price * pedido_produto.quantity) as valor_total
                ')
                ->groupBy('date', 'products.name')
                ->orderBy('date')
                ->orderBy('products.name')
                ->get()
                ->toArray();
        } catch (\Exception $th) {
            return [
                'error' => 'Erro ao obter vendas do período: ' . $th->getMessage()
            ];
        }
    }
    /**
     * Veja aqui os 10 produtos mais vendidos.
     */
    public function getVendaProduto(): array
    {
        try {
            $inicio = now()->subDays(30)->startOfDay();
            $fim = now()->endOfDay();

            return Product::select(
                'products.id',
                'products.name',
                'products.stock',
                DB::raw('AVG(pedido_produto.unit_price) as price'),
                DB::raw('SUM(pedido_produto.quantity) as quantity')
            )
                ->join('pedido_produto', 'products.id', '=', 'pedido_produto.product_id')
                ->join('pedidos', 'pedidos.id', '=', 'pedido_produto.pedido_id')
                ->whereBetween('pedidos.updated_at', [$inicio, $fim])
                ->where('pedidos.status', 'concluido')
                ->groupBy('products.id', 'products.name', 'products.stock')
                ->orderByDesc('quantity')
                ->get()
                ->toArray();
        } catch (\Exception $th) {
            return ['error' => 'Erro ao obter produtos mais vendidos: ' . $th->getMessage()];
        }
    }
    /**
     * Veja aqui os produtos que mais geraram lucro nos últimos 30 dias.
     */
    public function getGestaoLucro(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        return Product::select(
                'products.id',
                'products.name',
                'products.stock',
                DB::raw('AVG(pedido_produto.unit_price) as price'),
                DB::raw('products.cost_price'),
                DB::raw('SUM((pedido_produto.unit_price - products.cost_price) * pedido_produto.quantity) as lucro')
            )
            ->join('pedido_produto', 'products.id', '=', 'pedido_produto.product_id')
            ->join('pedidos', 'pedidos.id', '=', 'pedido_produto.pedido_id')
            ->whereBetween('pedidos.updated_at', [$inicio, $fim])
            ->where('pedidos.status', 'concluido')
            ->groupBy('products.id', 'products.name', 'products.stock', 'products.cost_price')
            ->having('lucro', '>', 0)
            ->orderByDesc('lucro')
            ->get()
            ->toArray();
    }
    /**
     * Veja aqui a entrada de caixa dos últimos 30 dias.
     */
    public function getEntrada(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        return Pedido::where('status', 'concluido')
            ->whereBetween('updated_at', [$inicio, $fim])
            ->selectRaw('DATE(updated_at) as dia, SUM(total) as total_entrada')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
    }
    /**
     * Veja aqui os 10 produtos mais parados.
     */
    public function getVendaParada(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        // Subquery: soma a quantidade vendida de cada produto no período
        $sub = DB::table('pedido_produto')
            ->join('pedidos', 'pedidos.id', '=', 'pedido_produto.pedido_id')
            ->whereBetween('pedidos.created_at', [$inicio, $fim])
            ->where('pedidos.status', 'concluido')
            ->select('pedido_produto.product_id', DB::raw('SUM(pedido_produto.quantity) as total_vendido'))
            ->groupBy('pedido_produto.product_id');

        // Junta com products, pega os que menos venderam, prioriza maior estoque em empate
        return Product::leftJoinSub($sub, 'vendas', function($join) {
                $join->on('products.id', '=', 'vendas.product_id');
            })
            ->select(
                'products.id',
                'products.name',
                'products.stock',
                DB::raw('COALESCE(vendas.total_vendido, 0) as total_vendido')
            )
            ->orderBy('total_vendido', 'asc')
            ->orderBy('products.stock', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
    /**
     * Veja aqui as 10 maiores despesas do estoque.
     */
    public function getCustoEstoque(): array
    {
        return Product::select(
                'id',
                'name',
                'price',
                'stock',
                'cost_price',
                DB::raw('(stock * cost_price) as custo_total')
            )
            ->orderByDesc('custo_total')
            ->limit(10)
            ->get()
            ->toArray();
    }
    public function getHistoricoProduto(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        return Product::whereBetween('updated_at', [$inicio, $fim])
            ->select('id', 'name', 'description', 'price', 'cost_price', 'stock')
            ->selectRaw('DATE(updated_at) as modificacao')
            ->orderByDesc('updated_at')
            ->get()
            ->toArray();
    }
    /**
     * Veja aqui a saída de caixa dos últimos 30 dias.
     */
    public function getSaida(): array
    {
        $inicio = now()->subDays(30)->startOfDay();
        $fim = now()->endOfDay();

        return DB::table('pedido_produto')
            ->join('pedidos', 'pedidos.id', '=', 'pedido_produto.pedido_id')
            ->join('products', 'products.id', '=', 'pedido_produto.product_id')
            ->whereBetween('pedidos.updated_at', [$inicio, $fim])
            ->where('pedidos.status', 'concluido')
            ->selectRaw('DATE(pedidos.updated_at) as dia, SUM(products.cost_price * pedido_produto.quantity) as total_saida')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->toArray();
    }
}
