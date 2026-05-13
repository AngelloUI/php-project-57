## steps:
- git clone https://github.com/AngelloUI/php-project-57.git
- cp .env.example .env
- docker compose up
- docker compose exec app make setup
- docker compose exec app php artisan key:generate
- docker compose exec app make migrate
- docker compose exec app php artisan db:seed

### open http://localhost:8081
