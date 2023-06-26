
# Projeto Easy Manager - Avaliação

Projeto feito para Avaliação de habilidades para a vaga de DEV PHP
O foco aqui foi utilizar o PHP puro com o auxilio da micro-framework Slim.
Com o Slim, é possivel implementar uma API em um sistema que utiliza PHP sem o uso de grandes frameworks com muitos recursos prontos, como Laravel ou Symfony

##

Tecnologias Utilizadas:

- PHP 8.1
- Slim 4 (para desenvolver as rotas e middleware de validação para a API)
- MySQL 5.7
- JWT (para autenticação)

Técnicas Utilizadas:

- Conceitos SOLID
- Código Limpo
- Injeção de dependências
- PSR7

## Deploy

Para testar o projeto, baixe os arquivos em um servidor ou em um docker com PHP. Na raiz do projeto, rode os comandos:

**Para baixar as dependências:**

```bash
  composer install
```
ou
```bash
  composer.phar install
```

**Copie o arquivo env-example:**

```bash
  copy ext-example.php ext.php
```

ou no Linux:

```bash
  cp ext-example.php ext.php
```

Nesse arquivo, defina os dados da conexão com o banco de dados MySQL (Já deixei com alguns dados, afim de facilitar, mesmo não sendo uma boa prática).

**Crie a base MySQL:**

Rodar o conteúdo do arquivo script.sql da pasta raiz no ambiente MySQL

**Iniciar a aplicação:**

Rodar o comando:

```bash
   php -S localhost:8000 -t .
```

## Fluxo da aplicação

- Aplicação é iniciada pelo index.php da raiz, que instancia o arquivo index.php da pasta bootstrap
- Ao fazer uma requisição, a rota é buscada no arquivo routes.php. O grupo /api/v1 possui validação de token jwt e só vai completar a requisição se o mesmo existir e for válido
- A rota redireciona para a função do controller definido
- Cada função chama um serviço com a finalidade unica para que foi definido (Single Responsibility Principle)
- Esse serviço recebe uma ou mais interfaces do repositório que representa a uma tabela no banco de dados e seus métodos (Dependency Inversion Principle).
- Nos serviços que trabalham com criação/alteração de dados da base, um objeto model da tabela em questão é criado e no seu método construct, já existe a chamada de validação para os dados que serão inseridos
- Feita as devidas validações no model e no service, o método do repositório e chamado e alteração na base é feita.
- O usuário recebe a resposta da API.


## Documentação da API

#### Chave JWT

Utilizar o usuário criado no script no corpo da requisição a seguir para obter a chave JWT para acessar o restante dos endpoints:

```http
  POST /api/v1/auth
```

```json
{
    "email": "admin@gmail.com",
    "cpf": "443.237.098-84"
}
```

Com a chave retornada, utilize como bearer token nas próximas requisições.

**Users**

#### Adicionar usuário

```http
  POST /api/v1/user
```

```json
{
    "username": "string|not null",
    "cpf": "string|not null",
    "email": "test2@gmail.com",
    "manager" : 0-1|Default 0 //Define se o usuário é um gerente (1) ou executor (0)
}
```

#### Listar usuários

```http
  GET /api/v1/user
```

**Project**

#### Listar projetos

```http
  GET /api/v1/project
```

#### Adicionar projeto

```http
  POST /api/v1/project
```

```json
{
    "title": "string|not null",
    "end_date": "date|yyyy-mm-dd|yyyy-mm-dd h:i:s|not null"
}
```

#### Atualizar projeto

```http
  PUT /api/v1/project/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `integer` | **Obrigatório** |

```json
{
    "title": "string|optional",
    "end_date": "date|yyyy-mm-dd|yyyy-mm-dd h:i:s|optional"
}
```

#### Excluir projeto

```http
  DELETE /api/v1/project/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `integer` | **Obrigatório** |


#### "Fechar" projeto (atualizar coluna status)

```http
  PATCH /api/v1/project/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `integer` | **Obrigatório** |

**Task**

#### Listar tarefas

```http
  GET /api/v1/task
```

#### Adicionar tarefa

```http
  POST /api/v1/task
```

```json
{
    "title": "string|not null",
    "description" : "string|optional",
    "end_date": "date|yyyy-mm-dd|yyyy-mm-dd h:i:s"
    "project_id": int|not null|fk,
    "user_id": int|not null|fk
}
```

#### Atualizar tarefas

```http
  PUT /api/v1/task/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `integer` | **Obrigatório** |

```json
{
    "title": "string|optional",
    "description" : "string|optional",
    "end_date": "date|yyyy-mm-dd|yyyy-mm-dd h:i:s|optional"
    "project_id": int|not null|fk|optional,
    "user_id": int|not null|optional
}
```

#### Excluir tarefa

```http
  DELETE /api/v1/task/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `integer` | **Obrigatório** |


#### "Fechar" tarefa (atualizar coluna status)

```http
  PATCH /api/v1/task/{id}
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `id` | `integer` | **Obrigatório** |
