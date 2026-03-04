# Explicação das alterações realizadas

Este documento descreve de forma didática as mudanças que fizemos no projeto para adicionar as entidades `Clientes`, `Fornecedores`, `Produtos`, `Estoque` e `Pedidos`.
Cada seção contém o que foi feito, como foi feito e para que serve.

---

## 1. Models (Modelos Eloquent)

### O que é
Um "Model" em Laravel representa uma tabela do banco de dados. Ele facilita operações como inserir, atualizar, buscar e excluir registros.

### O que fizemos
- Criei os arquivos `app/Models/Fornecedores.php`, `Produto.php`, `Estoque.php` e `Pedido.php`.
- Cada model usa o trait `HasFactory` para permitir a geração de dados de teste (factories) e define o atributo `$fillable` para preenchimento em massa.
- No `Estoque` e `Pedido` adicionei relacionamentos (`belongsTo`) para outras tabelas.

### Como fazer por conta
1. `php artisan make:model Nome` cria o modelo.
2. Defina `$fillable` com os campos que poderão ser atribuídos em massa.
3. Para relacionamentos, adicione métodos como `public function cliente() { return $this->belongsTo(Clientes::class); }`.

### Para que serve
Os modelos encapsulam a lógica de acesso aos dados e facilitam o uso de Eloquent ORM em controle, views e factories.

---

## 2. Migrations (Migrações de banco)

### O que é
Migrações são scripts que criam ou modificam tabelas no banco. Elas mantêm o esquema versionado.

### O que fizemos
- Adicionei três migrations novas para `fornecedores`, `produtos`, `estoques` e uma para `pedidos`.
- Cada arquivo define os campos que a tabela terá e regras como chaves estrangeiras.

### Como fazer
1. `php artisan make:migration create_nome_table --create=nome` gera o arquivo.
2. No método `up()` use `Schema::create('nome', function (Blueprint $table) { ... });` para definir colunas.
3. No método `down()` faça `Schema::dropIfExists('nome');` para desfazer.
4. Execute `php artisan migrate` para aplicar.

### Para que serve
Permite estruturar e versionar o esquema do banco de dados, garantindo que a versão atual seja reprodutível em qualquer ambiente.

---

## 3. Factories e Seeders

### O que são
Factories definem como gerar dados fictícios para testes. Seeders populam tabelas inicializando dados.

### O que fizemos
- Criamos factories para cada modelo (`FornecedoresFactory`, `ProdutoFactory`, `EstoqueFactory`, `PedidoFactory`).
- Adicionamos a geração desses registros em `DatabaseSeeder.php` para criar dados automaticamente ao rodar `php artisan db:seed`.

### Como fazer
1. `php artisan make:factory NomeFactory --model=Nome` gera a factory.
2. No método `definition()` retorne um array com valores gerados (usando `fake()` para dados randômicos).
3. No `DatabaseSeeder`, chame `Model::factory(count)->create();`.

### Para que serve
Facilita testes automatizados e populações iniciais de base sem precisar inserir manualmente cada registro.

---

## 4. Controllers e Rotas

### O que são
Controllers recebem requisições HTTP, interagem com modelos e retornam respostas (normalmente views). Rotas definem quais URLs acionam quais controles.

### O que fizemos
- Criamos controllers simples (`FornecedorController`, `ProdutoController`, `EstoqueController`, `PedidoController`) com método `index()` que traz todos os registros e retorna uma view.
- Atualizamos `routes/web.php` para adicionar rotas `get('/fornecedores',...)`, etc., e importamos os controllers.

### Como fazer
1. `php artisan make:controller NomeController` gera o controller.
2. Adicione métodos que buscam dados via modelo e retornam views: `return view('caminho', compact('variavel'));`.
3. Em `routes/web.php`, use `Route::get('/url', [NomeController::class, 'metodo'])->name('nome');`.

### Para que serve
Organiza a lógica de tratamento de requisições e permite separar responsabilidades. As rotas ligam URLs ao código.

---

## 5. Views (Blade Templates)

### O que são
Views representam o HTML que será enviado ao navegador. O Blade é o motor de templates do Laravel.

### O que fizemos
- Criamos arquivos `resources/views/{fornecedores,produtos,estoques,pedidos}/index.blade.php` contendo tabelas para exibir os registros.
- Utilize layouts (`<x-app-layout>`) para reaproveitar cabeçalho e navegação.
- As views exibem cada coluna usando expressions como `{{ $fornecedor->nome }}`.

### Como fazer
1. Crie pastas e arquivos dentro de `resources/views` correspondendo às páginas.
2. Use sintaxe Blade (`@foreach`, `{{ }}`, componentes `<x-...>`) para gerar HTML dinâmico.
3. Chamadas `compact('variavel')` no controller passam dados para a view.

### Para que serve
Permitem apresentar informações dinâmicas ao usuário e são separadas da lógica PHP/Controllers.

---

## 6. Testes

### O que são
Testes automatizados verificam se a aplicação se comporta conforme esperado.

### O que fizemos
- Adicionamos testes de feature para cada lista (`FornecedoresTest`, `ProdutosTest`, `EstoqueTest`, `PedidosTest`).
- Em cada teste usamos `RefreshDatabase` para base limpa e criamos alguns registros via factory. Verificamos resposta HTTP 200 e texto da tabela.

### Como fazer
1. `php artisan make:test NomeTest --unit` ou `--feature`.
2. Use métodos como `$this->get('/url')->assertStatus(200)->assertSee('Texto');`.
3. Rode `php artisan test` ou `vendor/bin/phpunit`.

### Para que serve
Garante que mudanças no código não quebrem funcionalidades e serve de documentação executável.

---

## 7. Uso básico e execução
1. Rode `composer install` se necessário.
2. Configure o `.env` com suas credenciais de banco.
3. Execute migrações: `php artisan migrate`.
4. Opcionalmente popule com `php artisan db:seed`.
5. Inicie servidor: `php artisan serve` ou via Laragon.
6. Navegue para `/clientes`, `/fornecedores`, `/produtos`, `/estoques`, `/pedidos`.

---

## 8. Como aplicar essas etapas por conta própria
1. Planeje a entidade e seus campos.
2. Gere model + migration: `artisan make:model -m` (criando também migration).
3. Edite a migration, rode `migrate`.
4. Adicione factory se precisar de testes/seeding.
5. Crie controller e rota para exibir ou manipular.
6. Crie view correspondente.
7. Teste manualmente e com testes automatizados.

---

## Conclusão
Seguindo esse fluxo você consegue estender o sistema com novas tabelas e funcionalidades. Cada parte do Laravel trabalha em conjunto: migrations criam o schema, models representam os dados, controllers lidam com lógica, rotas conectam URLs, views mostram os resultados e testes garantem estabilidade.

Fique à vontade para experimentar criando outras entidades ou implementando CRUD completo com formulários e validação!