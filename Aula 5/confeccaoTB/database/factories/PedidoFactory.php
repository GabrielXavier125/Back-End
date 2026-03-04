<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produto = \App\Models\Produto::factory()->create();
        $quantidade = fake()->numberBetween(1, 10);
        $total = $produto->preco * $quantidade;

        return [
            'cliente_id' => \App\Models\Clientes::factory(),
            'produto_id' => $produto->id,
            'quantidade' => $quantidade,
            'total' => $total,
            'status' => fake()->randomElement(['pendente', 'concluido', 'cancelado']),
        ];
    }
}
