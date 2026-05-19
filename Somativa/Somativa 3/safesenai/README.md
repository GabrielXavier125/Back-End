# SAFE — Sistema de Autorização e Fluxo Escolar

Sistema web para gerenciamento de **saídas antecipadas** e **entradas atrasadas** de alunos em ambiente escolar, com fluxo de autorização multi-etapas por papel (coordenação → professor → portaria).

---

## Sumário

- [Tecnologias](#tecnologias)
- [Instalação](#instalação)
- [Usuários de Teste](#usuários-de-teste)
- [Papéis e Permissões](#papéis-e-permissões)
- [Fluxos do Sistema](#fluxos-do-sistema)
- [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
- [Rotas da Aplicação](#rotas-da-aplicação)
- [Módulos e Funcionalidades](#módulos-e-funcionalidades)

---

## Tecnologias

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.3 + Laravel 13 |
| Autenticação | Laravel Breeze |
| Frontend | Tailwind CSS 3 + Alpine.js 3 |
| Build | Vite 8 |
| Banco de Dados | MySQL 8 |
| Fuso Horário | America/Sao_Paulo (UTC-3) |
| Idioma | Português Brasileiro (pt_BR) |

---

## Instalação

```bash
# 1. Clonar o repositório
git clone <repositorio> safesenai
cd safesenai

# 2. Instalar dependências PHP
composer install

# 3. Instalar dependências JS
npm install

# 4. Configurar o ambiente
cp .env.example .env
php artisan key:generate

# 5. Configurar banco de dados no .env
# DB_DATABASE=safesenai
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Executar migrações e popular com dados de teste
php artisan migrate:fresh --seed

# 7. Compilar assets
npm run build

# 8. Iniciar servidor de desenvolvimento
php artisan serve
```

URL local padrão: `http://safesenai.test` (Laragon) ou `http://localhost:8000` (artisan serve)

---

## Usuários de Teste

> Todos os usuários abaixo são criados pelo seeder (`php artisan migrate:fresh --seed`).
> **Senha universal de teste: `1234`**

### Coordenação

| Campo | Valor |
|---|---|
| Nome | Coordenação SENAI |
| E-mail | `coordenacao@safe.test` |
| Senha | `1234` |
| Papel | Coordenador |

### Professores

| Nome | E-mail | Senha | Turma Vinculada |
|---|---|---|---|
| Prof. Carlos Souza | `carlos.souza@safe.test` | `1234` | Informática (Manhã) |
| Profa. Ana Lima | `ana.lima@safe.test` | `1234` | Administração (Tarde) |

### Portaria

| Campo | Valor |
|---|---|
| Nome | José Porteiro |
| E-mail | `portaria@safe.test` |
| Senha | `1234` |
| Papel | Porteiro |

### Turmas e Alunos

**Informática — Técnico em Informática (Manhã)**

| Matrícula | Nome |
|---|---|
| 2026001 | Lucas Ferreira |
| 2026002 | Beatriz Santos |
| 2026003 | Gabriel Oliveira |
| 2026004 | Mariana Costa |
| 2026005 | Rafael Almeida |
| 2026006 | Júlia Rodrigues |
| 2026007 | Thiago Pereira |
| 2026008 | Fernanda Lima |
| 2026009 | Diego Nascimento |
| 2026010 | Amanda Carvalho |

**Administração — Técnico em Administração (Tarde)**

| Matrícula | Nome |
|---|---|
| 2026011 | Pedro Mendes |
| 2026012 | Camila Barbosa |
| 2026013 | Rodrigo Martins |
| 2026014 | Larissa Cardoso |
| 2026015 | Felipe Araújo |
| 2026016 | Isabela Rocha |
| 2026017 | Vinícius Gomes |
| 2026018 | Natália Ribeiro |
| 2026019 | Eduardo Sousa |
| 2026020 | Carolina Silva |

---

## Papéis e Permissões

O sistema possui três papéis de usuário com acesso e responsabilidades distintas.

### Coordenador (`coordinator`)

Controle total do sistema.

- Cadastrar, editar e desativar alunos
- Cadastrar e editar turmas
- Cadastrar e editar usuários (professores e porteiros), vinculando professores às turmas
- **Registrar saídas antecipadas** de alunos (Etapa 1 do fluxo de saída)
- **Registrar entradas atrasadas** de alunos (Etapa 1 do fluxo de entrada)
- Cancelar qualquer autorização pendente
- Visualizar relatórios e logs de auditoria
- Dashboard com visão geral de todos os registros do dia

### Professor (`teacher`)

Acesso restrito à **sua turma vinculada**.

- Visualizar apenas alunos e registros da sua turma
- **Confirmar saída do aluno em sala** (Etapa 2 do fluxo de saída)
- **Confirmar presença atrasada em sala** (Etapa 2 do fluxo de entrada)
- Dashboard com pendências da sua turma
- Badge na sidebar indica a quantidade de pendências da sua turma

### Porteiro (`gatekeeper`)

- Visualizar autorizações de saída aguardando liberação
- **Confirmar saída física do aluno** na portaria (Etapa 3 do fluxo de saída)
- Dashboard com saídas pendentes e confirmadas no dia

---

## Fluxos do Sistema

### Fluxo de Saída Antecipada

```
Aluno solicita saída
       ↓
[ETAPA 1] COORDENAÇÃO
  Registra a saída antecipada
  Define motivo e aulas perdidas (1ª a 5ª)
  Status: waiting_teacher
       ↓
  Aluno retorna à sala de aula
       ↓
[ETAPA 2] PROFESSOR (da turma do aluno)
  Confirma que o aluno saiu da sala
  Status: waiting_gate
       ↓
  Aluno se dirige à portaria
       ↓
[ETAPA 3] PORTARIA
  Confirma a saída física do aluno
  Status: released ✓
```

**Status possíveis:** `waiting_teacher` → `waiting_gate` → `released` | `cancelled`

---

### Fluxo de Entrada Atrasada

```
Aluno chega atrasado
       ↓
[ETAPA 1] COORDENAÇÃO
  Registra a entrada atrasada
  Define motivo e aulas perdidas (1ª a 5ª)
  Registra horário de chegada
  Status: waiting_teacher
       ↓
  Aluno vai para a sala de aula
       ↓
[ETAPA 2] PROFESSOR (da turma do aluno)
  Confirma presença do aluno em sala
  Status: confirmed ✓
```

**Status possíveis:** `waiting_teacher` → `confirmed` | `cancelled`

---

### Aulas Perdidas

Em ambos os fluxos, é possível registrar quais aulas o aluno perdeu (seleção múltipla):

| Seleção | Significado |
|---|---|
| 1ª | 1ª hora/aula |
| 2ª | 2ª hora/aula |
| 3ª | 3ª hora/aula |
| 4ª | 4ª hora/aula |
| 5ª | 5ª hora/aula |

---

## Estrutura do Banco de Dados

### `users` — Usuários do sistema

| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| name | string | Nome completo |
| email | string unique | E-mail de acesso |
| password | string | Senha (bcrypt) |
| role | enum | `coordinator`, `teacher`, `gatekeeper` |
| classroom_id | bigint FK nullable | Turma vinculada (apenas professores) |
| active | boolean | Ativo/Inativo |

### `classrooms` — Turmas

| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| name | string | Nome da turma/curso |
| grade | string | Nível/série |
| shift | string | `morning`, `afternoon`, `evening` |
| year | year | Ano letivo |
| active | boolean | Ativo/Inativo |

### `students` — Alunos

| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| classroom_id | bigint FK | Turma do aluno |
| name | string | Nome completo |
| registration | string unique | Número de matrícula |
| birth_date | date nullable | Data de nascimento |
| guardian_name | string nullable | Nome do responsável |
| guardian_phone | string nullable | Telefone do responsável |
| guardian_email | string nullable | E-mail do responsável |
| active | boolean | Ativo/Inativo |

### `early_releases` — Saídas Antecipadas

| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| student_id | bigint FK | Aluno |
| coordinator_id | bigint FK nullable | Coordenador que registrou |
| teacher_id | bigint FK nullable | Professor que confirmou |
| gatekeeper_id | bigint FK nullable | Porteiro que liberou |
| reason | string | Motivo da saída |
| observation | text nullable | Observações adicionais |
| status | enum | `waiting_teacher`, `waiting_gate`, `released`, `cancelled` |
| missed_periods | json nullable | Array com aulas perdidas (ex: `[1, 2]`) |
| teacher_confirmed_at | timestamp nullable | Data/hora da confirmação do professor |
| released_at | timestamp nullable | Data/hora da liberação na portaria |

### `late_entries` — Entradas Atrasadas

| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| student_id | bigint FK | Aluno |
| coordinator_id | bigint FK | Coordenador que registrou |
| teacher_id | bigint FK nullable | Professor que confirmou |
| reason | string | Motivo do atraso |
| observation | text nullable | Observações adicionais |
| status | enum | `waiting_teacher`, `confirmed`, `cancelled` |
| missed_periods | json nullable | Array com aulas perdidas (ex: `[1]`) |
| arrived_at | timestamp nullable | Horário de chegada registrado |
| confirmed_at | timestamp nullable | Data/hora da confirmação do professor |

### `audit_logs` — Logs de Auditoria

| Coluna | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| user_id | bigint FK nullable | Usuário que realizou a ação |
| action | string | Nome da ação (ex: `early_release.created`) |
| model_type | string nullable | Classe do modelo afetado |
| model_id | bigint nullable | ID do registro afetado |
| old_values | json nullable | Valores antes da alteração |
| new_values | json nullable | Valores após a alteração |
| ip_address | string nullable | IP do usuário |
| user_agent | string nullable | Navegador/dispositivo |

---

## Rotas da Aplicação

Todas as rotas requerem autenticação (`auth` middleware).

### Dashboard
| Método | URL | Acesso |
|---|---|---|
| GET | `/dashboard` | Todos os papéis |

### Alunos
| Método | URL | Acesso |
|---|---|---|
| GET | `/students` | Todos |
| GET | `/students/create` | Coordenação |
| POST | `/students` | Coordenação |
| GET | `/students/{id}` | Todos |
| GET | `/students/{id}/edit` | Coordenação |
| PATCH | `/students/{id}` | Coordenação |
| DELETE | `/students/{id}` | Coordenação |

### Turmas
| Método | URL | Acesso |
|---|---|---|
| GET | `/classrooms` | Coordenação |
| GET | `/classrooms/create` | Coordenação |
| POST | `/classrooms` | Coordenação |
| GET | `/classrooms/{id}/edit` | Coordenação |
| PATCH | `/classrooms/{id}` | Coordenação |
| DELETE | `/classrooms/{id}` | Coordenação |

### Usuários
| Método | URL | Acesso |
|---|---|---|
| GET | `/users` | Coordenação |
| GET | `/users/create` | Coordenação |
| POST | `/users` | Coordenação |
| GET | `/users/{id}/edit` | Coordenação |
| PATCH | `/users/{id}` | Coordenação |
| DELETE | `/users/{id}` | Coordenação |

### Saídas Antecipadas
| Método | URL | Etapa | Acesso |
|---|---|---|---|
| GET | `/early-releases` | — | Coordenação, Professor, Portaria |
| GET | `/early-releases/create` | — | Coordenação |
| POST | `/early-releases` | Etapa 1 | Coordenação |
| GET | `/early-releases/{id}` | — | Coordenação, Professor, Portaria |
| POST | `/early-releases/{id}/confirm-teacher` | Etapa 2 | Professor |
| POST | `/early-releases/{id}/confirm` | Etapa 3 | Portaria |
| POST | `/early-releases/{id}/cancel` | — | Coordenação |

### Entradas Atrasadas
| Método | URL | Etapa | Acesso |
|---|---|---|---|
| GET | `/late-entries` | — | Coordenação, Professor |
| GET | `/late-entries/create` | — | Coordenação |
| POST | `/late-entries` | Etapa 1 | Coordenação |
| GET | `/late-entries/{id}` | — | Coordenação, Professor |
| POST | `/late-entries/{id}/confirm` | Etapa 2 | Professor |
| POST | `/late-entries/{id}/cancel` | — | Coordenação |

### Relatórios e Auditoria
| Método | URL | Acesso |
|---|---|---|
| GET | `/reports` | Coordenação |
| GET | `/audit-logs` | Coordenação |

---

## Módulos e Funcionalidades

### Vínculo Professor ↔ Turma

Cada professor é vinculado a uma única turma no momento do cadastro (ou edição). A partir disso:

- O professor **só visualiza** registros de alunos da sua turma
- O professor **só pode confirmar** registros de alunos da sua turma
- O badge de pendências na sidebar mostra apenas o total da sua turma
- Um professor sem turma vinculada não verá nenhum registro

### Auditoria Automática

Todas as ações relevantes são registradas automaticamente:

| Evento | Ação Auditada |
|---|---|
| Cadastro de usuário | `user.created` |
| Edição de usuário | `user.updated` |
| Desativação de usuário | `user.deactivated` |
| Cadastro de aluno | `student.created` |
| Edição de aluno | `student.updated` |
| Desativação de aluno | `student.deactivated` |
| Registro de saída | `early_release.created` |
| Confirmação pelo professor | `early_release.teacher_confirmed` |
| Confirmação pela portaria | `early_release.confirmed` |
| Cancelamento de saída | `early_release.cancelled` |
| Registro de atraso | `late_entry.created` |
| Confirmação de presença | `late_entry.confirmed` |
| Cancelamento de atraso | `late_entry.cancelled` |

### Busca Dinâmica de Alunos

Nos formulários de registro, o campo de aluno usa busca dinâmica via API JSON (`/api/students/search`), exibindo nome, matrícula, turma e indicação se o aluno já possui um registro pendente.

### Relatórios

A coordenação tem acesso a relatórios de saídas antecipadas filtráveis por:
- Período (data início / data fim)
- Status
- Aluno específico
- Professor específico

---

## Estrutura de Diretórios Relevante

```
app/
├── Events/
│   └── StudentReleased.php        # Evento disparado na liberação final
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── EarlyReleaseController.php
│   │   ├── LateEntryController.php
│   │   ├── StudentController.php
│   │   ├── ClassroomController.php
│   │   ├── UserController.php
│   │   ├── ReportController.php
│   │   └── AuditLogController.php
│   ├── Middleware/
│   │   └── RoleMiddleware.php      # Controle de acesso por papel
│   └── Requests/
│       ├── StoreEarlyReleaseRequest.php
│       └── StoreLateEntryRequest.php
├── Models/
│   ├── User.php
│   ├── Student.php
│   ├── Classroom.php
│   ├── EarlyRelease.php
│   ├── LateEntry.php
│   └── AuditLog.php
└── Policies/
    ├── EarlyReleasePolicy.php
    ├── LateEntryPolicy.php
    └── StudentPolicy.php

database/
├── migrations/           # Todas as migrações em ordem
└── seeders/
    └── DatabaseSeeder.php  # Dados de teste (migrate:fresh --seed)

resources/views/
├── layouts/
│   └── app.blade.php     # Layout principal com sidebar e navbar
├── dashboard/
│   ├── coordinator.blade.php
│   ├── teacher.blade.php
│   └── gatekeeper.blade.php
├── early-releases/       # Saídas antecipadas (index, create, show)
├── late-entries/         # Entradas atrasadas (index, create, show)
├── students/             # Alunos (index, create, edit, show)
├── classrooms/           # Turmas (index, create, edit)
├── users/                # Usuários (index, create, edit)
└── components/
    ├── status-badge.blade.php       # Badge de status de saída
    └── late-status-badge.blade.php  # Badge de status de entrada
```
