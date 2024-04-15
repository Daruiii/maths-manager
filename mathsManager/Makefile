deploy: public/build/manifest.json
	rsync -avz public/build davidinfomaniak:~/sites/mathsmanager.fr/mathsManager/public
	ssh davidinfomaniak 'cd ~/sites/mathsmanager.fr && git pull origin main && make install'

install: vendor/autoload.php .env public/storage
	php artisan cache:clear
	php artisan migrate

.env:
	cp .env.example
	php artisan key:generate

public/storage:
	php artisan storage:link

vendor/autoload.php: composer.lock
	composer install
	touch vendor/autoload.php

public/build/manifest.json: package.json
	npm install
	npm run build