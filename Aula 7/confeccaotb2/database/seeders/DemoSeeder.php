<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Estoque;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Permission;
use App\Models\Produto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // PERMISSÕES
        // -------------------------------------------------------
        $permVendas = Permission::firstOrCreate(
            ['name' => 'acesso-vendas', 'guard_name' => 'web'],
            ['pages' => ['clientes', 'pedidos']]
        );

        $permEstoque = Permission::firstOrCreate(
            ['name' => 'acesso-estoque', 'guard_name' => 'web'],
            ['pages' => ['produtos', 'insumos', 'estoques']]
        );

        $permCompras = Permission::firstOrCreate(
            ['name' => 'acesso-compras', 'guard_name' => 'web'],
            ['pages' => ['fornecedores', 'insumos']]
        );

        $permTotal = Permission::firstOrCreate(
            ['name' => 'acesso-total', 'guard_name' => 'web'],
            ['pages' => ['clientes', 'pedidos', 'produtos', 'insumos', 'estoques', 'fornecedores', 'cargos', 'permissoes', 'usuarios']]
        );

        // -------------------------------------------------------
        // CARGOS (ROLES)
        // -------------------------------------------------------
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions([$permTotal]);

        $roleGerente = Role::firstOrCreate(['name' => 'Gerente de Vendas', 'guard_name' => 'web']);
        $roleGerente->syncPermissions([$permVendas]);

        $roleEstoque = Role::firstOrCreate(['name' => 'Operador de Estoque', 'guard_name' => 'web']);
        $roleEstoque->syncPermissions([$permEstoque]);

        $roleComprador = Role::firstOrCreate(['name' => 'Comprador', 'guard_name' => 'web']);
        $roleComprador->syncPermissions([$permCompras]);

        // -------------------------------------------------------
        // USUÁRIOS (ignora se o e-mail já existir)
        // -------------------------------------------------------
        $maria = User::firstOrCreate(
            ['email' => 'maria.silva@confeccaotb2.com'],
            ['name' => 'Maria Silva', 'password' => Hash::make('password')]
        );
        $maria->syncRoles([$roleGerente]);

        $joao = User::firstOrCreate(
            ['email' => 'joao.santos@confeccaotb2.com'],
            ['name' => 'João Santos', 'password' => Hash::make('password')]
        );
        $joao->syncRoles([$roleEstoque]);

        $ana = User::firstOrCreate(
            ['email' => 'ana.costa@confeccaotb2.com'],
            ['name' => 'Ana Costa', 'password' => Hash::make('password')]
        );
        $ana->syncRoles([$roleComprador]);

        // -------------------------------------------------------
        // FORNECEDORES
        // -------------------------------------------------------
        $forn1 = Fornecedor::create(['nome' => 'Tecidos São Paulo Ltda',       'email' => 'contato@tecidossp.com.br',          'telefone' => '11987654321', 'CNPJ' => '12345678000190', 'endereco' => 'Rua das Fibras, 100 - Brás, São Paulo/SP']);
        $forn2 = Fornecedor::create(['nome' => 'Aviamentos Brasil Eireli',      'email' => 'vendas@aviamentosbrasil.com.br',    'telefone' => '21912345678', 'CNPJ' => '98765432000155', 'endereco' => 'Av. Marechal Floriano, 55 - Centro, Rio de Janeiro/RJ']);
        $forn3 = Fornecedor::create(['nome' => 'Moda Têxtil do Nordeste ME',   'email' => 'comercial@modatextil.com.br',       'telefone' => '81933221100', 'CNPJ' => '11223344000177', 'endereco' => 'Rua do Algodão, 320 - Boa Vista, Recife/PE']);

        // -------------------------------------------------------
        // INSUMOS
        // -------------------------------------------------------
        Insumo::create(['nome' => 'Tecido Malha PV',         'descricao' => 'Tecido de alta qualidade para confecção', 'preco' => 18.50, 'unidade_medida' => 'metro',   'medida' => '1.50m', 'quantidade' => '200']);
        Insumo::create(['nome' => 'Linha de Costura 40/2',   'descricao' => 'Linha resistente para costura reta',      'preco' => 3.90,  'unidade_medida' => 'unidade', 'medida' => '500m',  'quantidade' => '150']);
        Insumo::create(['nome' => 'Botão de Camisa',         'descricao' => 'Botão 4 furos tamanho 18mm',              'preco' => 0.15,  'unidade_medida' => 'unidade', 'medida' => '18mm',  'quantidade' => '5000']);
        Insumo::create(['nome' => 'Zíper Invisível',         'descricao' => 'Zíper para vestidos e saias',             'preco' => 2.20,  'unidade_medida' => 'unidade', 'medida' => '25cm',  'quantidade' => '300']);
        Insumo::create(['nome' => 'Elástico Chato',          'descricao' => 'Elástico para cintura 3cm',               'preco' => 1.80,  'unidade_medida' => 'metro',   'medida' => '3cm',   'quantidade' => '400']);
        Insumo::create(['nome' => 'Entretela Termocolante',  'descricao' => 'Entretela para gola e punhos',            'preco' => 5.40,  'unidade_medida' => 'metro',   'medida' => '0.90m', 'quantidade' => '100']);

        // -------------------------------------------------------
        // PRODUTOS
        // -------------------------------------------------------
        $p1 = Produto::create(['nome' => 'Vestido Floral Verão',    'descricao' => 'Vestido leve estampado, ideal para o verão',  'preco' => 89.90,  'fornecedor_id' => $forn1->id]);
        $p2 = Produto::create(['nome' => 'Calça Social Feminina',   'descricao' => 'Calça slim fit tecido plano',                 'preco' => 119.90, 'fornecedor_id' => $forn1->id]);
        $p3 = Produto::create(['nome' => 'Camisa Polo Masculina',   'descricao' => 'Camisa polo em malha piquet',                 'preco' => 74.90,  'fornecedor_id' => $forn2->id]);
        $p4 = Produto::create(['nome' => 'Saia Midi Plissada',      'descricao' => 'Saia midi com pregas, tecido viscose',        'preco' => 94.90,  'fornecedor_id' => $forn2->id]);
        $p5 = Produto::create(['nome' => 'Blusa Cropped Listrada',  'descricao' => 'Blusa cropped malha canelada listrada',       'preco' => 49.90,  'fornecedor_id' => $forn3->id]);

        // -------------------------------------------------------
        // ESTOQUES
        // -------------------------------------------------------
        Estoque::create(['produto_id' => $p1->id, 'quantidade' => 42]);
        Estoque::create(['produto_id' => $p2->id, 'quantidade' => 28]);
        Estoque::create(['produto_id' => $p3->id, 'quantidade' => 65]);
        Estoque::create(['produto_id' => $p4->id, 'quantidade' => 19]);
        Estoque::create(['produto_id' => $p5->id, 'quantidade' => 83]);

        // -------------------------------------------------------
        // CLIENTES
        // -------------------------------------------------------
        $c1 = Cliente::create(['nome' => 'Fernanda Oliveira', 'email' => 'fernanda.oliveira@email.com', 'telefone' => '11991234567', 'documento' => '12345678901']);
        $c2 = Cliente::create(['nome' => 'Roberto Almeida',   'email' => 'roberto.almeida@email.com',  'telefone' => '21987654321', 'documento' => '98765432100']);
        $c3 = Cliente::create(['nome' => 'Juliana Martins',   'email' => 'juliana.martins@email.com',  'telefone' => '31912345678', 'documento' => '45678912300']);
        $c4 = Cliente::create(['nome' => 'Carlos Eduardo',    'email' => 'carlos.eduardo@email.com',   'telefone' => '41998765432', 'documento' => '78912345600']);
        $c5 = Cliente::create(['nome' => 'Patrícia Souza',    'email' => 'patricia.souza@email.com',   'telefone' => '51933221100', 'documento' => '32165498700']);

        // -------------------------------------------------------
        // PEDIDOS + ITENS
        // -------------------------------------------------------
        $ped1 = Pedido::create(['cliente_id' => $c1->id, 'produto_id' => $p1->id, 'quantidade' => 2, 'status' => 'concluido',    'total' => 179.80]);
        ItemPedido::create(['pedido_id' => $ped1->id, 'produto_id' => $p1->id, 'quantidade' => 2, 'preco_unitario' => 89.90]);

        $ped2 = Pedido::create(['cliente_id' => $c2->id, 'produto_id' => $p3->id, 'quantidade' => 3, 'status' => 'em_andamento', 'total' => 224.70]);
        ItemPedido::create(['pedido_id' => $ped2->id, 'produto_id' => $p3->id, 'quantidade' => 2, 'preco_unitario' => 74.90]);
        ItemPedido::create(['pedido_id' => $ped2->id, 'produto_id' => $p5->id, 'quantidade' => 1, 'preco_unitario' => 49.90]);

        $ped3 = Pedido::create(['cliente_id' => $c3->id, 'produto_id' => $p2->id, 'quantidade' => 1, 'status' => 'pendente',     'total' => 119.90]);
        ItemPedido::create(['pedido_id' => $ped3->id, 'produto_id' => $p2->id, 'quantidade' => 1, 'preco_unitario' => 119.90]);

        $ped4 = Pedido::create(['cliente_id' => $c4->id, 'produto_id' => $p4->id, 'quantidade' => 2, 'status' => 'concluido',    'total' => 334.70]);
        ItemPedido::create(['pedido_id' => $ped4->id, 'produto_id' => $p4->id, 'quantidade' => 2, 'preco_unitario' => 94.90]);
        ItemPedido::create(['pedido_id' => $ped4->id, 'produto_id' => $p5->id, 'quantidade' => 3, 'preco_unitario' => 49.90]);

        $ped5 = Pedido::create(['cliente_id' => $c5->id, 'produto_id' => $p5->id, 'quantidade' => 4, 'status' => 'cancelado',    'total' => 199.60]);
        ItemPedido::create(['pedido_id' => $ped5->id, 'produto_id' => $p5->id, 'quantidade' => 4, 'preco_unitario' => 49.90]);
    }
}
