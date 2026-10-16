# Cadastro de Livros TJ JUD e Relatório

Ambiente de desenvolvimento para um projeto de Teste Técnico onde vai ter cadastro de livros, utilizando o framework Symfony, banco de dados MySql e templates utilizando Twig.

## Tecnologias e Arquitetura

- **Linguagem / Framework**: PHP 8.4 & Symfony 7.4 (LTS)
- **Engine de Templates**: Twig
- **ORM & Banco de Dados**: Doctrine ORM, DBAL & MySQL 8.0
- **Servidor Web**: Nginx (Reverse Proxy) + PHP-FPM
- **Geração de PDF**: Dompdf
- **Containerização**: Docker & Docker Compose (sem dependência de PHP/Node local)


## Como subir

```bash
docker compose up -d --build
```

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

As credenciais ficam no arquivo `.env` (lido automaticamente pelo docker compose) e o `DATABASE_URL` do container `app` é montado a partir delas:

```env
MYSQL_ROOT_PASSWORD=root_password
MYSQL_DATABASE=db
MYSQL_USER=user
MYSQL_PASSWORD=pass
```

```
DATABASE_URL="mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@db:3306/${MYSQL_DATABASE}?serverVersion=8.0&charset=utf8mb4"
```

O `.env-default` serve de template (estrutura + valores padrão) e deve ser versionado no Git; o `.env` contém seus valores locais e não deve ser commitado. Se você clonar o projeto, faça:

```bash
cp .env-default .env
```

Se o `.env` não existir, o compose usa os valores padrão (`:-`) definidos no `docker-compose.yml`.

Dentro da rede Docker o host é `db` na porta `3306`. Do host, use a porta aleatória retornada por `docker compose port db 3306`.