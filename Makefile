#!/usr/bin/make

SHELL = /bin/sh

new: build run init-app info # create new

fresh: build run re-init-app info # refresh

build: # build db, nginx and app container
	docker-compose build

run: # run existing containers
	docker-compose up -d

init-app: # init laravel-app in app container
	docker exec -it app composer install
	docker exec -it app php artisan key:generate
	docker exec -it app php artisan jwt:secret
	docker exec -it app php artisan l5-swagger:generate
	docker exec -it app php artisan storage:link
	docker exec -it app php artisan db:wipe
	docker exec -it app php artisan migrate:refresh
	docker exec -it app php artisan db:seed

re-init-app: # init laravel-app in app container
	docker exec -it app composer install
	docker exec -it app php artisan l5-swagger:generate
	docker exec -it app php artisan db:wipe
	docker exec -it app php artisan migrate:refresh
	docker exec -it app php artisan db:seed

info: # information about containers
	docker-compose ps

down: # stop all containers
	docker-compose down

app-connect: # enter in laravel container
	docker exec -it app bash

test: # run tests
	php artisan test
