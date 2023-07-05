build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

php:
	docker-compose exec app bash

install:
	composer install

clean:
	docker-compose down --volumes --remove-orphans