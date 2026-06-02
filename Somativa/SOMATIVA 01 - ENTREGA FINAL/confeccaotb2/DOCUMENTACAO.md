# Projeto Confecção

**Sistema de Gestão de Confecção — TB2**  
Versão: 1.0  
Data: Junho de 2026

---

## Sumário

1. [Visão Geral](#1-visão-geral)
2. [Stack Tecnológica](#2-stack-tecnológica)
3. [Requisitos do Sistema](#3-requisitos-do-sistema)
4. [Instalação e Configuração](#4-instalação-e-configuração)
5. [Arquitetura do Projeto](#5-arquitetura-do-projeto)
6. [Banco de Dados](#6-banco-de-dados)
7. [Módulos do Sistema](#7-módulos-do-sistema)
8. [Dashboard e Widgets](#8-dashboard-e-widgets)
9. [Estilização Customizada](#9-estilização-customizada)
10. [Comandos Úteis](#10-comandos-úteis)

---

## 1. Visão Geral

O **TB2 Confecção** é um sistema de gestão administrativa desenvolvido para gerenciar o fluxo completo de uma empresa de confecção de roupas. O sistema cobre desde o cadastro de clientes e fornecedores até o controle de pedidos, estoque e insumos.

**Funcionalidades principais:**
- Cadastro e gestão de Clientes e Fornecedores
- Catálogo de Produtos com vínculo a fornecedores
- Controle de Insumos (matéria-prima) com unidades de medida
- Registro e acompanhamento de Pedidos com cálculo automático de total
- Controle de Estoque com alertas de quantidade mínima
- Dashboard com estatísticas em tempo real e notificações de estoque crítico

---

## 2. Stack Tecnológica

| Camada | Tecnologia | Versão |
|---|---|---|
| Linguagem | PHP | ^8.3 |
| Framework Backend | Laravel | ^13.0 |
| Painel Administrativo | Filament | ^5.0 |
| Componentes Reativos | Livewire | ^3.0 (via Filament) |
| Estilização | Tailwind CSS | ^4.0 |
| Build Frontend | Vite | ^8.0 |
| Banco de Dados | MySQL | 5.7+ |
| Gerenciador PHP | Composer | 2.x |
| Gerenciador JS | NPM | 10+ |

---

## 3. Requisitos do Sistema

- **PHP** 8.3 ou superior com extensões: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- **MySQL** 5.7 ou superior (ou MariaDB 10.3+)
- **Composer** 2.x
- **Node.js** 18+ com NPM 10+
- Servidor web: **Apache** ou **Nginx** (ou `php artisan serve` para desenvolvimento)

---

## 4. Instalação e Configuração

### 4.1 Clonar e instalar dependências

```bash
# Instalar dependências PHP
composer install

# Instalar dependências JavaScript
npm install
```

### 4.2 Configurar o ambiente

```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 4.3 Configurar o banco de dados

Edite o arquivo `.env` com as credenciais do seu banco:

```env
APP_NAME="TB2 Confecção"
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=confeccaotb2
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 4.4 Executar migrations e criar usuário admin

```bash
# Rodar todas as migrations
php artisan migrate

# Criar o usuário administrador via Tinker
php artisan tinker
```

```php
App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@tb2.com',
    'password' => 'sua_senha',
]);
```

### 4.5 Compilar assets e iniciar

```bash
# Compilar para produção
npm run build

# OU iniciar em modo de desenvolvimento (todos os serviços juntos)
composer run dev
```

O comando `composer run dev` sobe simultaneamente:
- Servidor Laravel (`php artisan serve`)
- Queue listener
- Log viewer (Laravel Pail)
- Vite dev server

### 4.6 Acessar o sistema

| URL | Descrição |
|---|---|
| `http://127.0.0.1:8000` | Redireciona para o painel |
| `http://127.0.0.1:8000/admin` | Painel administrativo |
| `http://127.0.0.1:8000/admin/login` | Tela de login |

---

## 5. Arquitetura do Projeto

### 5.1 Estrutura de diretórios

```
confeccaotb2/
├── app/
│   ├── Filament/
│   │   ├── Resources/              # Recursos do painel administrativo
│   │   │   ├── Clientes/
│   │   │   │   ├── Pages/          # List, Create, View, Edit
│   │   │   │   ├── Schemas/        # ClienteForm.php, ClienteInfolist.php
│   │   │   │   ├── Tables/         # ClientesTable.php
│   │   │   │   └── ClienteResource.php
│   │   │   ├── Fornecedors/
│   │   │   ├── Produtos/
│   │   │   ├── Insumos/
│   │   │   ├── Pedidos/
│   │   │   ├── ItemPedidos/        # Oculto da nav — gerenciado via Pedidos
│   │   │   └── Estoques/
│   │   └── Widgets/
│   │       ├── StatusOverview.php       # Cards de estatísticas
│   │       ├── UltimosPedidosWidget.php # Tabela de últimos pedidos
│   │       └── AlertasEstoqueWidget.php # Alertas de estoque crítico
│   ├── Models/                     # Modelos Eloquent
│   ├── Providers/
│   │   ├── AppServiceProvider.php  # Registro de render hooks (CSS custom)
│   │   └── Filament/
│   │       └── AdminPanelProvider.php  # Configuração do painel Filament
│   └── Http/Controllers/
├── database/
│   ├── migrations/                 # 13 migrations
│   ├── factories/
│   └── seeders/
├── public/
│   └── css/
│       └── filament-custom.css     # Estilização customizada do painel
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/
├── routes/
│   └── web.php                     # Única rota: / → redirect /admin
└── lang/
    └── vendor/filament/pt_BR/      # Traduções do Filament em português
```

### 5.2 Padrão dos Resources Filament

Cada entidade segue o padrão de subpastas:

```
{Entidade}Resource.php      → Classe principal (ícone, grupo, badge, páginas)
Pages/
  List{Entidade}.php        → Listagem com tabela
  Create{Entidade}.php      → Formulário de criação
  View{Entidade}.php        → Visualização de detalhes
  Edit{Entidade}.php        → Formulário de edição
Schemas/
  {Entidade}Form.php        → Definição dos campos do formulário
  {Entidade}Infolist.php    → Definição da exibição de detalhes
Tables/
  {Entidade}sTable.php      → Definição de colunas e filtros da tabela
```

### 5.3 Navegação por grupos

O menu lateral está organizado em três grupos:

| Grupo | Itens |
|---|---|
| **Cadastros** | Clientes, Fornecedores |
| **Produtos** | Produtos, Insumos |
| **Operações** | Pedidos, Estoques |

---

## 6. Banco de Dados

### 6.1 Diagrama de entidades

```
users
└── (acesso ao sistema)

clientes ──────────────────┐
                           ├──→ pedidos ──→ item_pedidos
produtos ──→ fornecedores  │                    │
    │                      └───────────────────┘
    └──→ estoques
```

### 6.2 Tabelas

#### `users`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| name | varchar(255) | Nome do usuário |
| email | varchar(255) UNIQUE | E-mail de login |
| password | varchar(255) | Senha criptografada (bcrypt) |
| email_verified_at | timestamp NULL | |
| remember_token | varchar(100) NULL | |
| timestamps | | created_at, updated_at |

#### `clientes`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| nome | varchar(255) | Nome completo |
| email | varchar(255) UNIQUE | E-mail de contato |
| telefone | varchar(255) NULL | Armazenado apenas dígitos |
| documento | varchar(255) NULL | CPF (11 dígitos) ou CNPJ (14 dígitos) — apenas dígitos |
| timestamps | | |

> Os campos `telefone` e `documento` são salvos apenas com dígitos e formatados na exibição.

#### `fornecedors`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| nome | varchar(255) | Razão social ou nome |
| email | varchar(255) | E-mail de contato |
| telefone | varchar(255) NULL | Apenas dígitos |
| CNPJ | varchar(255) NULL | Apenas dígitos (14 dígitos) |
| endereco | varchar(255) NULL | Endereço completo |
| timestamps | | |

#### `produtos`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| nome | varchar(255) | Nome do produto |
| descricao | text NULL | Descrição |
| preco | decimal(10,2) | Preço unitário em R$ |
| fornecedor_id | bigint FK | Referência a `fornecedors` |
| timestamps | | |

#### `insumos`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| nome | varchar(255) | Nome do insumo |
| descricao | text NULL | Descrição |
| preco | decimal(10,2) | Preço unitário |
| unidade_medida | varchar(255) | Ex: `kg`, `m`, `un`, `rl` |
| medida | decimal NULL | Valor da medida |
| quantidade | decimal NULL | Quantidade disponível |
| timestamps | | |

**Unidades de medida disponíveis:** `un` (Unidade), `kg` (Quilograma), `g` (Grama), `l` (Litro), `ml` (Mililitro), `m` (Metro), `cm` (Centímetro), `cx` (Caixa), `pct` (Pacote), `rl` (Rolo)

#### `pedidos`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| cliente_id | bigint FK | Referência a `clientes` (cascade delete) |
| produto_id | bigint FK | Referência a `produtos` (cascade delete) |
| quantidade | integer | Quantidade de itens |
| status | varchar(255) | `pendente`, `em_andamento`, `concluido`, `cancelado` |
| total | decimal(10,2) | Calculado automaticamente (quantidade × preço) |
| timestamps | | |

**Status e cores:**
| Status | Cor |
|---|---|
| `pendente` | Amarelo (warning) — pisca suavemente |
| `em_andamento` | Azul (info) |
| `concluido` | Verde (success) |
| `cancelado` | Vermelho (danger) |

#### `item_pedidos`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| pedido_id | bigint FK | Referência a `pedidos` |
| produto_id | bigint FK | Referência a `produtos` |
| quantidade | integer | |
| preco_unitario | decimal(10,2) | |
| timestamps | | |

#### `estoques`
| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | |
| produto_id | bigint FK UNIQUE | Referência a `produtos` (1 estoque por produto) |
| quantidade | integer | Quantidade atual em estoque |
| quantidade_minima | integer | Quantidade mínima antes de gerar alerta (padrão: 5) |
| timestamps | | |

---

## 7. Módulos do Sistema

### 7.1 Clientes

**Arquivo principal:** `app/Filament/Resources/Clientes/ClienteResource.php`

- Cadastro com Nome, E-mail, Telefone e CPF/CNPJ
- Máscaras dinâmicas no formulário (CPF: `000.000.000-00` / CNPJ: `00.000.000/0000-00`)
- Dados salvos sem formatação (apenas dígitos) — formatação aplicada apenas na exibição
- Busca por nome, e-mail, telefone e documento

**Modelo:** `app/Models/Cliente.php`

Métodos estáticos de formatação:
```php
Cliente::formatTelefone($value)   // (11) 99999-9999 ou (11) 9999-9999
Cliente::formatDocumento($value)  // 000.000.000-00 (CPF) ou 00.000.000/0000-00 (CNPJ)
```

### 7.2 Fornecedores

**Arquivo principal:** `app/Filament/Resources/Fornecedors/FornecedorResource.php`

- Cadastro com Nome, E-mail, Telefone, CNPJ e Endereço
- Mesma lógica de formatação que Clientes
- Label explícito: "Fornecedores" (evita pluralização automática em inglês)

**Modelo:** `app/Models/Fornecedor.php`

Métodos estáticos:
```php
Fornecedor::formatTelefone($value)
Fornecedor::formatDocumento($value)  // Apenas CNPJ (14 dígitos)
```

### 7.3 Produtos

**Arquivo principal:** `app/Filament/Resources/Produtos/ProdutoResource.php`

- Vinculado a um Fornecedor
- Campos: Nome, Descrição, Preço (R$)
- Filtro por Fornecedor na tabela
- Preço exibido no formato `R$ X.XXX,XX`

### 7.4 Insumos

**Arquivo principal:** `app/Filament/Resources/Insumos/InsumoResource.php`

- Matéria-prima da confecção
- Campos: Nome, Descrição, Preço, Unidade de Medida, Medida, Quantidade
- Filtro por Unidade de Medida na tabela
- Select com 10 unidades de medida do setor têxtil

### 7.5 Pedidos

**Arquivo principal:** `app/Filament/Resources/Pedidos/PedidoResource.php`

- Vínculo com Cliente e Produto
- **Cálculo automático do total** via Livewire (quantidade × preço do produto)
- 4 status com cores e badge pulsante para "Pendente"
- Filtro por Status na tabela
- Exibição do nome do cliente e produto (não IDs)

**Lógica de cálculo (`PedidoForm.php`):**
```php
// Ao selecionar Produto ou alterar Quantidade:
$set('total', $produto->preco * $quantidade);
```

### 7.6 Estoques

**Arquivo principal:** `app/Filament/Resources/Estoques/EstoqueResource.php`

- Um registro de estoque por produto (campo `produto_id` único)
- Campos: Produto, Quantidade em Estoque, Quantidade Mínima
- **Alerta visual:** quantidade atual fica vermelha e linha recebe borda vermelha quando abaixo do mínimo
- **Badge na navegação:** número vermelho ao lado de "Estoques" com a quantidade de produtos em estado crítico
- Filtro rápido: "Apenas críticos" / "Apenas normais"

**Badge dinâmico (`EstoqueResource.php`):**
```php
public static function getNavigationBadge(): ?string
{
    $count = Estoque::whereColumn('quantidade', '<', 'quantidade_minima')->count();
    return $count > 0 ? (string) $count : null;
}
```

---

## 8. Dashboard e Widgets

O dashboard está disponível em `/admin` e exibe três widgets em sequência.

### 8.1 AlertasEstoqueWidget *(sort: 1)*

**Arquivo:** `app/Filament/Widgets/AlertasEstoqueWidget.php`

Tabela com todos os produtos cujo estoque está abaixo do mínimo, ordenados pelo maior déficit primeiro.

Colunas: Produto · Qtd. Atual (vermelho) · Qtd. Mínima · Déficit (badge) · Última Atualização

Quando não há alertas, exibe: *"Nenhum alerta! Todos os produtos estão com estoque acima do mínimo."*

### 8.2 StatusOverview *(sort: padrão)*

**Arquivo:** `app/Filament/Widgets/StatusOverview.php`

Seis cards com estatísticas em tempo real:

| Card | Dado |
|---|---|
| Pedidos Pendentes | `Pedido::where('status', 'pendente')->count()` |
| Em Andamento | `Pedido::where('status', 'em_andamento')->count()` |
| Concluídos | `Pedido::where('status', 'concluido')->count()` |
| Total Faturado | `Pedido::where('status', 'concluido')->sum('total')` |
| Clientes | `Cliente::count()` |
| Produtos em Estoque | `Estoque::sum('quantidade')` |

### 8.3 UltimosPedidosWidget *(sort: 2)*

**Arquivo:** `app/Filament/Widgets/UltimosPedidosWidget.php`

Tabela com os 8 pedidos mais recentes.

Colunas: Cliente · Produto · Qtd. · Status (badge colorido) · Valor · Data

---

## 9. Estilização Customizada

**Arquivo CSS:** `public/css/filament-custom.css`  
**Registro:** `app/Providers/AppServiceProvider.php` via `FilamentView::registerRenderHook`

### Mudanças visuais aplicadas

| Elemento | Estilo |
|---|---|
| Fundo geral | Gradiente azul-lilás suave `#f0f4ff → #e8eeff → #f5f0ff` |
| Sidebar | Gradiente branco → lilás, sombra lateral suave, borda índigo |
| Topbar | `backdrop-filter: blur(12px)` — efeito de vidro |
| Cards de stats | Branco com sombra índigo, levitam 5px ao hover |
| Tabelas | Bordas arredondadas (16px), sombra suave |
| Botões | Sobem 2px ao hover, afundam ao clicar |
| Badge "Pendente" | Pulsa suavemente (animação infinita) |
| Sidebar items | Deslizam 4px para a direita ao hover |
| Scrollbar | Fina (5px), cor índigo semitransparente |

### Animações

| Animação | Aplicada em |
|---|---|
| `fadeInUp` | Conteúdo principal ao carregar a página |
| `slideInLeft` | Itens da sidebar em cascata (delay por item) |
| `slideInRight` | Notificações do sistema |
| `pulseWarn` | Badge de status "Pendente" |

### Configurações do Painel

```php
// AdminPanelProvider.php
->brandName('TB2 Confecção')
->sidebarCollapsibleOnDesktop()
->colors(['primary' => Color::Indigo])
```

---

## 10. Comandos Úteis

```bash
# Iniciar em desenvolvimento (todos os serviços)
composer run dev

# Rodar migrations
php artisan migrate

# Limpar todos os caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Criar usuário via Tinker
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'admin@tb2.com', 'password' => '1234'])

# Listar todas as rotas do painel
php artisan route:list --path=admin

# Compilar assets para produção
npm run build

# Rodar testes
composer run test

# Setup completo do zero
composer run setup
```

---

*Documentação gerada em 02/06/2026 — TB2 Confecção v1.0*
