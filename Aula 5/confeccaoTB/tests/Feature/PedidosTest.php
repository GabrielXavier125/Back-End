<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidosTest extends TestCase
{
    use RefreshDatabase;

    public function test_pedidos_index_displays()
    {
        \App\Models\Pedido::factory()->count(3)->create();

        $response = $this->get('/pedidos');
        $response->assertStatus(200);
        $response->assertSee('Lista de Pedidos');
    }
}
