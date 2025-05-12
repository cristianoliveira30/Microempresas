<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Inertia\Inertia;

class ReportController extends Controller
{
    // Injeta a classe de serviço que lida com a lógica de negócio
    public function __construct(
        protected ProductService $productService
    ) {}

    // Lista todos os produtos
    public function index()
    {
        return Inertia::render('Relatorio/Relatorio');
    }
}
