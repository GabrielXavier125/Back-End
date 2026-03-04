<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstoqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_estoque_index_displays()
    {
        $produto = \App\Models\Produto::factory()->create();
        \App\Models\Estoque::factory()->for($produto)->create();

        $response = $this->get('/estoques');
        $response->assertStatus(200);
        $response->assertSee('Controle de Estoque');
    }
}
