PORT ?= 8000

start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

install:
	composer install

setup:
	cp -n .env.example .env
	composer install
	php artisan key:generate --ansi
	npm install
	npm ci
	npm run build

migrate:
	php artisan migrate --force

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app routes tests

test:
	php artisan test

test-coverage:
	composer exec --verbose phpunit -- --coverage-clover build/logs/clover.xml

build:
	npm ci && npm run build

auto-fix-lint:
	composer exec phpcbf -- --standard=PSR12 app routes tests

