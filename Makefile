up:
	docker compose up -d
build:
	docker compose build
init:
	cp .env.example .env
	docker compose up -d --build
	docker compose exec php composer install
	docker composer exec php chown laravel:laravel .env
	docker compose exec php php artisan key:generate
	docker compose exec php php artisan storage:link
	docker compose exec php chmod -R 777 storage bootstrap/cache
	docker compose exec php npm install
	@make fresh
remake:
	@make destroy
	@make init
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
down-v:
	docker compose down --remove-orphans --volumes
restart:
	@make down
	@make up
destroy:
	docker compose down --rmi all --volumes --remove-orphans
ps:
	docker compose ps
logs:
	docker compose logs
migrate:
	docker compose exec php php artisan migrate
fresh:
	docker compose exec php php artisan migrate:fresh --seed
seed:
	docker compose exec php php artisan db:seed
rollback-test:
	docker compose exec php php artisan migrate:fresh
	docker compose exec php php artisan migrate:refresh
tinker:
	docker compose exec php php artisan tinker
test:
	docker compose exec php php artisan test
optimize:
	docker compose exec php php artisan optimize
optimize-clear:
	docker compose exec php php artisan optimize:clear
cache:
	docker compose exec php composer dump-autoload -o
	@make optimize
	docker compose exec php php artisan event:cache
	docker compose exec php php artisan view:cache
cache-clear:
	docker compose exec php composer clear-cache
	@make optimize-clear
	docker compose exec php php artisan event:clear
dump-autoload:
	docker compose exec php composer dump-autoload
db:
	docker compose exec mysql bash
sql:
	docker compose exec mysql bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
redis:
	docker compose exec redis redis-cli
ide-helper:
	docker compose exec php php artisan clear-compiled
	docker compose exec php php artisan ide-helper:generate
	docker compose exec php php artisan ide-helper:meta
	docker compose exec php php artisan ide-helper:models --nowrite
