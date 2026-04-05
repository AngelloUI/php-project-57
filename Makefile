start:
	php artisan serve --host 0.0.0.0

install: setup

setup:
	cp -n .env.example .env
	composer install
	php artisan key:generate --ansi
	npm install

migrate:
	php artisan migrate --force

test:
	php artisan test

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app

auto-fix-lint:
	composer exec phpcbf -- --standard=PSR12 app
