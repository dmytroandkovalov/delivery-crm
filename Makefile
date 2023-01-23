COMPOSE_FILE?=laradock/docker-compose.yml
build:
	- bash build.sh

run:
	- docker-compose -f ${COMPOSE_FILE} up -d nginx mariadb

stop:
	- docker-compose -f ${COMPOSE_FILE} stop -t0

rm:
	- docker-compose -f ${COMPOSE_FILE} rm -fv

bash:
	- docker-compose -f ${COMPOSE_FILE} exec --user=laradock workspace bash

test:
	- docker exec -i delivery_crm_php-fpm_1 /var/www/vendor/bin/phpunit -vvv

create_delivery:
	- docker exec -i delivery_crm_php-fpm_1 php artisan db:seed --class=CarrierSeeder
	- docker exec -i delivery_crm_php-fpm_1 php artisan delivery-crm:delivery:create-rand
