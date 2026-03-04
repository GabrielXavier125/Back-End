<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdutosTest extends TestCase
{
    use RefreshDatabase;

    public function test_produtos_index_displays()
    {
        \App\Models\Produto::factory()->count(3)->create();

        $response = $this->get('/produtos');
        $response->assertStatus(200);
        $response->assertSee('Lista de Produtos');
    }
}
