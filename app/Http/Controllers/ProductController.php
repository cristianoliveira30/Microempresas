<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
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

        return Inertia::render('Produto/Produto', [
            'produtos' => $products,
        ]);
    }

    // Cria um novo produto
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->store($request->validated());

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product,
        ], 201);
    }

    // Atualiza um produto existente
    public function update(UpdateProductRequest $request, Product $product)
    {
        $updatedProduct = $this->productService->update($product, $request->validated());

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $updatedProduct,
        ]);
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
