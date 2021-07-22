up:
	docker-compose up -d

down:
	docker-compose down

re:
	make down
	make up

bash:
	docker-compose exec --user=application php bash

cc:
	rm -rf app/var/cache/
	docker-compose exec -T --user=application php bin/console ca:cl

validate-schema:
	docker-compose exec --user=application php bin/console doctrine:schema:validate

drop-db:
	docker-compose exec --user=application php bin/console doctrine:database:drop --force

create-db:
	docker-compose exec --user=application php bin/console doctrine:database:create --if-not-exists

update-schema:
	docker-compose exec -T --user=application php bin/console doctrine:schema:update --force

import-db:
	cat test-cinemahd-database.sql | docker-compose exec -T database /usr/bin/mysql -u user --password=password database

import-db-data:
	cat test-cinemahd-datas.sql | docker-compose exec -T database /usr/bin/mysql -u user --password=password database

imdb-synchronize:
	docker-compose exec --user=application php bin/console app:generate-movie-posters

migration:
	docker-compose exec -T --user=application php bin/console doctrine:migrations:migrate
