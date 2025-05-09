<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(3, true); // Ex: "Sabonete Líquido Hidratante"

        return [
            'name' => $name,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 500), // Entre R$10,00 e R$500,00
            'cost_price' => $this->faker->randomFloat(2, 5, 300), // Entre R$5,00 e R$300,00
            'stock' => $this->faker->numberBetween(0, 200),
            'barcode' => $this->faker->unique()->ean13(), // Gera código de barras
            'is_active' => $this->faker->boolean(90), // 90% de chance de ser true
            'category_id' => null, // Pode ser preenchido depois com seeders relacionados
            'unit' => $this->faker->randomElement(['un', 'kg', 'l']),
            'brand' => $this->faker->company,
            'image' => null, // Pode gerar um fake depois, se quiser
        ];
    }
}
