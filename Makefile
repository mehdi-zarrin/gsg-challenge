include .env
export APP_IMAGE=mehdizarrin/$(SERVICE_NAME)
EXEC=docker exec $(SERVICE_NAME)_php
CONSOLE=$(EXEC) bin/console
all: build up db-init db-migrate

build:
	docker build --tag ${APP_IMAGE} .
	docker run --rm \
		--volume "`pwd`:/app" \
		composer install --no-progress --prefer-dist --no-interaction

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

test-api:
	sh ./run-behat.sh api

test: test-api

wait-for-db:
	$(EXEC) php -r "set_time_limit(60);for(;;){if(@fsockopen('$(SERVICE_NAME)_mysql',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"

db-init: wait-for-db
	$(CONSOLE) doctrine:database:create --if-not-exists --no-debug

db-migrate: wait-for-db
	$(CONSOLE) doctrine:migration:migrate -n --no-debug

analyse-code:
	docker build --tag ${APP_IMAGE} .
	docker run --tty --rm \
		--volume "`pwd`:/app" \
		${APP_IMAGE} \
		bash -c \
		'composer install --no-progress --prefer-dist --no-interaction \
		&& vendor/bin/phpcs \
		&& vendor/bin/phpstan analyse src \
		'

prepare-local-test:
	$(CONSOLE) doctrine:database:create --if-not-exists --no-debug --env=test
	$(CONSOLE) doctrine:migration:migrate --env=test

local-test:
	$(EXEC) ./bin/phpunit --colors=always

local-behat:
	$(EXEC) ./vendor/bin/behat --format pretty --format progress --colors