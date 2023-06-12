up:
	docker-compose up -d

down:
	docker-compose down

reup:
	docker-compose down
	docker-compose up -d

build:
	docker-compose build

rebuild:
	docker-compose build --no-cache

back:
	docker-compose exec php bash

composer-install:
	docker-compose run --rm php composer install

composer-update:
	docker-compose run --rm php composer update

test:
	docker-compose run --rm php vendor/bin/phpunit

inspect:
	docker-compose run --rm php vendor/bin/grumphp run

dbcreate:
	docker-compose run --rm php php artisan migrate:fresh --seed

dbcreate-test:
	docker-compose run --rm php php artisan migrate:fresh --seed --env=testing

npm-install:
	docker-compose run --rm npm install

npm-dev:
	docker-compose run --rm npm run dev

npm-watch:
	docker-compose run --rm npm run watch
