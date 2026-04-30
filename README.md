# Instaclone

Instaclone é uma API exemplo estilo Instagram construída com Laravel 13 e Sanctum (token-based). Este repositório inclui documentação interativa OpenAPI/Swagger e foi preparado para execução via Docker Compose.

**Principais recursos**
- Endpoints para autenticação, usuários, posts, feed, likes, comentários e follows
- Documentação OpenAPI 3.0.3 e Swagger UI customizado
- Testes PHPUnit incluídos

**Requisitos**
- Docker e Docker Compose
- Git

## Quick Start (Docker)

1. Copie/edite o ambiente:

```bash
cp .env.example .env
# editar .env se necessário (DB_HOST/DB_PORT etc.)
```

2. Construir e subir os serviços (recomendado após mudanças no código):

```bash
docker compose up -d --build
```

3. Verificar status e logs:

```bash
docker compose ps
docker compose logs -f app
```

## Acessando o Swagger UI

- Interface: http://localhost:8000/docs
- Spec JSON (OpenAPI): http://localhost:8000/docs/openapi.json

Use o botão "Authorize" para inserir um token Bearer (Sanctum) e testar endpoints autenticados.

## Comandos úteis

```bash
# entrar no container de app (se necessário)
docker exec -it instaclone-app-1 bash

# limpar/reconstruir caches
php artisan route:clear
php artisan config:clear
php artisan route:cache

# rodar testes
./vendor/bin/phpunit
```

## Reconstrução completa
Se o container não está refletindo alterações (controllers, views, classes), reconstrua a imagem:

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

Isto removerá volumes locais e garante que a imagem seja recriada com as últimas mudanças.

## Solução de problemas
- "Could not find driver": geralmente falta `pdo_mysql` no PHP local; execute via Docker (a imagem contém as extensões) ou instale a extensão localmente.
- `App\\Http\\Controllers\\SwaggerController does not exist`: pode significar que o container está com código antigo. Soluções:
	- Copiar arquivos para o container (temporário):
		```bash
		docker cp app/Http/Controllers/SwaggerController.php instaclone-app-1:/app/app/Http/Controllers/SwaggerController.php
		docker cp app/Support/Swagger/OpenApiSpec.php instaclone-app-1:/app/app/Support/Swagger/OpenApiSpec.php
		docker cp -r resources/views/swagger instaclone-app-1:/app/resources/views/swagger
		```
	- Recomenda-se rebuild da imagem (veja seção Reconstrução completa).
	- `composer dump-autoload` dentro do container só funciona se o binário `composer` estiver disponível — o build da imagem já executa Composer quando apropriado.

## Testes

- Rodar suíte de testes dentro do container:

```bash
docker compose exec app ./vendor/bin/phpunit
```

## Arquivos relevantes
- Rotas web (docs): [routes/web.php](routes/web.php)
- Controller Swagger: [app/Http/Controllers/SwaggerController.php](app/Http/Controllers/SwaggerController.php)
- Gerador OpenAPI: [app/Support/Swagger/OpenApiSpec.php](app/Support/Swagger/OpenApiSpec.php)
- View do Swagger UI: [resources/views/swagger/index.blade.php](resources/views/swagger/index.blade.php)

## Contribuindo

PRs são bem-vindos. Para mudanças relevantes, adicione testes e descreva o comportamento esperado.

---
README atualizado pelo assistente — se quiser, eu adiciono exemplos curl ou um arquivo Postman/Insomnia a partir do OpenAPI.
