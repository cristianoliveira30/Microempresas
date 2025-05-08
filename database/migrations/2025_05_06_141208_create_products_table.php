<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do produto
            $table->text('description')->nullable(); // Descrição do produto
            $table->decimal('price', 10, 2); // Preço de venda
            $table->decimal('cost_price', 10, 2)->nullable(); // Preço de custo
            $table->integer('stock')->default(0); // Estoque disponível
            $table->string('barcode')->nullable()->unique(); // Código de barras
            $table->boolean('is_active')->default(true); // Produto ativo/inativo
            $table->unsignedBigInteger('category_id')->nullable(); // Chave estrangeira para categoria
            $table->string('unit')->default('un'); // Unidade de medida (ex: un, kg, l)
            $table->string('brand')->nullable(); // Marca do produto
            $table->string('image')->nullable(); // Caminho da imagem principal

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
