start:
	docker-compose up -d --build

stop:
	docker-compose down

logs:
	docker-compose logs -f

migrate:
	docker-compose exec app php update-schema.php

test:
	docker-compose exec app vendor/bin/phpunit

shell:
	docker-compose exec app bash

	

