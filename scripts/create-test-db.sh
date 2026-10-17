#!/usr/bin/env bash
set -euo pipefail

# Cria o banco de testes e garante permissões ao usuário da aplicação
docker compose exec -T db mysql -uroot -proot_password -e "
  CREATE DATABASE IF NOT EXISTS tjjud_db_test
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  GRANT ALL PRIVILEGES ON tjjud_db_test.* TO 'tjjud_user'@'%';
  FLUSH PRIVILEGES;
"

# Aplica as migrations no banco de teste
docker compose exec -T app php bin/console --env=test doctrine:migrations:migrate -n
