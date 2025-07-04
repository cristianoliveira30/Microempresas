<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    // Injeta a classe de serviço que lida com a lógica de negócio
    public function __construct(
        protected ProductService $productService
    ) {}

    // Lista todos os produtos
    public function index()
    {
        $products = $this->productService->listAll();

        foreach ($products as $product) {
            $product->is_active = $product->is_active == 1 ? 'Ativo' : 'Inativo';
        }

        return Inertia::render('Produto/Produto', [
            'produtos' => $products,
        ]);
    }

    // Cria um novo produto
    public function store(StoreProductRequest $request)
    { 
        $data = $request->validated();
        $product = $this->productService->store($data);

        return response()->json([
            'message' => 'Produto criado com sucesso.',
            'product' => $product,
        ], 201);
    }

    // Atualiza um produto existente
    public function batchUpdate(Request $request)
    {
        $edits = $request->input('edits', []);
        foreach ($edits as $edit) {
            $product = Product::find($edit['id']);
            if ($product && in_array($edit['field'], ['name', 'price', 'cost_price', 'description', 'stock', 'is_active'])) {
                $product->{$edit['field']} = $edit['newValue'];
                $product->save();
            }
        }
        return response()->json(['message' => 'Produtos atualizados com sucesso.']);
    }

    // Remove um produto
    public function destroy(Product $product)
    {
        $this->productService->destroy($product);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
