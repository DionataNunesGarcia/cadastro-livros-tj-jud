# Cadastro de Livros TJ JUD e Relatório

Ambiente de desenvolvimento para um projeto de Teste Técnico onde vai ter um cadastro de livros, utilizando o framework Symfony, banco de dados MySql e templates utilizando Twig.

Precisa apenas de Docker e Docker Compose instalados — não é necessário PHP, Composer ou Node na máquina.

## Tecnologias e Arquitetura

- **Linguagem / Framework**: PHP 8.4 & Symfony 7.4 (LTS)
- **Engine de Templates**: Twig
- **ORM & Banco de Dados**: Doctrine ORM, DBAL & MySQL 8.0
- **Servidor Web**: Nginx (Reverse Proxy) + PHP-FPM
- **Geração de PDF**: Dompdf
- **Containerização**: Docker & Docker Compose (sem dependência de PHP/Node local)

Os controllers recebem a request, validam o formulário e delegam. Persistência e tratamento de erro de banco ficam no `CrudService`; a consulta e a geração do relatório ficam no `RelatorioService`.

## Como subir

1. Crie o `.env` a partir do template:

```bash
cp .env.default .env
```

2. Preencha no `.env`:

- `APP_SECRET` (ex.: `openssl rand -hex 16`)
- `MYSQL_ROOT_PASSWORD=root_password`
- `MYSQL_PASSWORD=tjjud_pass`

Esses valores de banco são os mesmos usados pelo `scripts/create-test-db.sh` e pelo `.env.test`. Se trocar, ajuste os dois também.

O `DATABASE_URL` é montado com essas variáveis:

```env
DATABASE_URL="mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@db:3306/${MYSQL_DATABASE}?serverVersion=8.0&charset=utf8mb4"
```

3. Suba os containers e prepare a aplicação:

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php bin/console doctrine:migrations:migrate -n
docker compose exec app php bin/console doctrine:fixtures:load -n
docker compose port web 8085
```

A última linha mostra a porta aleatória do host (ex.: `0.0.0.0:32776`). Acesse `http://localhost:<porta>`.

## Comandos úteis

Entrar no container PHP:

```bash
docker compose exec app bash
```

Rodar o Composer sem entrar no container (o diretório de trabalho já é `/var/www/html`):

```bash
docker compose exec app composer install
docker compose exec app composer require <pacote>
```

Ver status e portas dos containers:

```bash
docker compose ps
```

Derrubar o ambiente (mantendo os dados do banco):

```bash
docker compose down
```

Derrubar e apagar também os dados do MySQL:

```bash
docker compose down -v
```

## Banco de dados

As credenciais ficam no arquivo `.env` (lido automaticamente pelo Symfony e pelo docker compose) e o `DATABASE_URL` do container `app` é montado a partir delas:

```env
MYSQL_ROOT_PASSWORD=root_password
MYSQL_DATABASE=tjjud_db
MYSQL_USER=tjjud_user
MYSQL_PASSWORD=tjjud_pass
```

O `.env.default` serve de template (estrutura + valores padrão) e deve ser versionado no Git; o `.env` contém seus valores locais e não deve ser commitado.

Dentro da rede Docker o host é `db` na porta `3306`. Do host, use a porta aleatória retornada por `docker compose port db 3306`.

## Ambiente de teste

O banco de teste é o `tjjud_db_test` (o Doctrine adiciona o sufixo `_test` ao nome do banco). Na primeira vez:

```bash
./scripts/create-test-db.sh
```

O script cria o banco, garante permissão ao usuário e roda as migrations no ambiente de teste. Se você trocar as credenciais no `.env`, ajuste o script também.

Depois é só rodar a suíte:

```bash
docker compose exec app php vendor/bin/phpunit
```

São 20 testes cobrindo os CRUDs das três entidades, a validação do ano (422), o bloqueio de exclusão de autor/assunto com livros vinculados e a tela/PDF/filtro do relatório. Cada teste roda em uma transação e sofre rollback via DAMA, então um teste não enxerga os dados do outro.

## Telas

- `/` — home com os contadores de livros, autores e assuntos
- `/livros` — lista em cards, com busca por título (`?q=`)
- `/autores` e `/assuntos` — listas em tabela
- `/relatorio/livros-por-autor` — relatório em tela, com filtro por autor
- `/relatorio/livros-por-autor/pdf` — PDF (o botão da tela repassa o filtro atual)

As exclusões usam `POST` com token CSRF.

## Relatório

A consulta vem da view `vw_livros_por_autor`, criada por migration. O `GROUP_CONCAT` junta os assuntos de cada livro e o agrupamento por autor é feito no `RelatorioService`. A tela mostra a contagem e o total por autor; o PDF usa um template e um CSS separados porque o Dompdf não entende Bootstrap.

Para ver a view direto no banco:

```bash
docker compose exec app php bin/console dbal:run-sql "SELECT * FROM vw_livros_por_autor"
```

## O que eu decidi (e por quê)

- Deixei os controllers finos e concentrei persistência e erros no `CrudService`, para não repetir try/catch e manter as mensagens iguais.
- Criei a interface `EntidadeComVinculos` para o service não precisar conhecer Autor e Assunto.
- O Doctrine remove os vínculos do ManyToMany sozinho, então a regra de "não excluir autor/assunto com livro" é validada na aplicação, antes do banco.
- Usei `MoneyType` com locale `pt_BR` e máscara no JavaScript para aceitar `1.234,56`.
- Formulário inválido retorna 422 para o Turbo não recarregar a página.
- Não implementei paginação: o volume do teste é pequeno. Se crescer, o primeiro candidato é o index de livros.
- Usei DAMA para os testes rodarem em transação com rollback, sem truncar tabelas entre eles.

## Estrutura

```
assets/        JS das máscaras e CSS (aplicação e PDF)
config/        configuração do Symfony
docker/nginx/  virtual host
migrations/    schema + view do relatório
scripts/       criação do banco de teste
src/           Contract, Controller, DataFixtures, Entity, Exception, Form, Repository e Service
templates/     layout, home, CRUDs e relatório
tests/         WebTestCase + DAMA
```

## Problemas comuns

- **Variáveis vazias no compose**: falta o `.env` (copie do `.env.default` e preencha).
- **Porta**: as portas do host são aleatórias; use `docker compose port web 8085`.
- **Erro de conexão com o banco**: aguarde o healthcheck (`docker compose ps`) e confira o `DATABASE_URL`.
- **Testes falhando por banco**: rode `./scripts/create-test-db.sh` novamente.
- **Permissão em `var/`**: `docker compose exec app chmod -R 777 var` (somente em desenvolvimento).
