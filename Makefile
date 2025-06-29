# Переменные для файлов docker-compose и имени сервиса PHP CLI
COMPOSE_FILE_DEV := docker-compose.yaml
COMPOSE_FILE_TEST := docker-compose-test.yaml
PHP_CLI_SERVICE := php-cli

# ==============================================================================
# ЦЕЛИ ДЛЯ ЛОКАЛЬНОЙ РАЗРАБОТКИ (DEV ENVIRONMENT)
# ==============================================================================

# Алиасы для команд разработки
build: docker-dev-build
up: docker-dev-up
stop: docker-dev-stop
restart: stop up
logs: docker-dev-logs
artisan: docker-dev-artisan
command: docker-dev-cli
test-coverage: docker-xdebug-coverage
ide-helper: docker-ide-helper
swagger: docker-swagger-generate
down:
	docker compose -f $(COMPOSE_FILE_DEV) down --remove-orphans --volumes

# Первоначальная настройка проекта (запуск контейнеров, установка зависимостей, миграции)
setup: up
	$(MAKE) command c="composer install"
	$(MAKE) command c="cp .env.example .env"
	$(MAKE) artisan c="key:generate"
	$(MAKE) artisan c="migrate --seed"
	$(MAKE) artisan c="optimize:clear"
	$(MAKE) command c="composer dump-autoload -o"
	$(MAKE) artisan c="cache:clear"

# Вход в оболочку PHP CLI контейнера
shell:
	docker compose -f $(COMPOSE_FILE_DEV) exec $(PHP_CLI_SERVICE) bash

# Выполнение миграций базы данных
migrate:
	$(MAKE) artisan c="migrate"

# Выполнение миграций с очисткой базы данных
migrate-fresh:
	$(MAKE) artisan c="migrate:fresh --seed"

# Детальные команды для разработки
docker-dev-build:
	docker compose -f $(COMPOSE_FILE_DEV) build

docker-dev-up:
	docker compose -f $(COMPOSE_FILE_DEV) up -d

docker-dev-stop:
	docker compose -f $(COMPOSE_FILE_DEV) stop

docker-dev-logs:
	docker compose -f $(COMPOSE_FILE_DEV) logs -f

# Выполнение команды Artisan в контейнере php-cli
docker-dev-artisan:
	docker compose -f $(COMPOSE_FILE_DEV) exec $(PHP_CLI_SERVICE) php artisan $(c)

# Выполнение произвольной CLI команды в контейнере php-cli
docker-dev-cli:
	docker compose -f $(COMPOSE_FILE_DEV) exec $(PHP_CLI_SERVICE) $(c)

# Запуск тестов с покрытием кода через XDebug
docker-xdebug-coverage:
	docker compose -f $(COMPOSE_FILE_DEV) exec $(PHP_CLI_SERVICE) env XDEBUG_MODE=coverage php artisan test --coverage

# Генерация IDE Helper файлов
docker-ide-helper:
	docker compose -f $(COMPOSE_FILE_DEV) exec $(PHP_CLI_SERVICE) composer ide-helper

# Генерация документации Swagger
docker-swagger-generate:
	docker compose -f $(COMPOSE_FILE_DEV) exec $(PHP_CLI_SERVICE) php artisan l5-swagger:generate --all

# ==============================================================================
# ЦЕЛИ ДЛЯ ТЕСТОВОГО СЕРВЕРА (TEST ENVIRONMENT)
# ==============================================================================

# Алиасы для команд тестового окружения
build-test: docker-test-build
up-test: docker-test-up
stop-test: docker-test-stop
restart-test: stop-test up-test
logs-test: docker-test-logs
down-test:
	docker compose -f $(COMPOSE_FILE_TEST) down --remove-orphans --volumes

# Детальные команды для тестового окружения
docker-test-build:
	docker compose -f $(COMPOSE_FILE_TEST) build

docker-test-up:
	docker compose -f $(COMPOSE_FILE_TEST) up -d

docker-test-stop:
	docker compose -f $(COMPOSE_FILE_TEST) stop

docker-test-logs:
	docker compose -f $(COMPOSE_FILE_TEST) logs -f