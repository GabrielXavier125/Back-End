<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FornecedoresTest extends TestCase
{
    use RefreshDatabase;

    public function test_fornecedores_index_displays()
    {
        \App\Models\Fornecedores::factory()->count(3)->create();

        $response = $this->get('/fornecedores');
        $response->assertStatus(200);
        $response->assertSee('Lista de Fornecedores');
    }
}
