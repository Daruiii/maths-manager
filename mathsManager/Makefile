# Cible principale de déploiement
deploy: public/build/manifest.json
	rsync -avz public/build davidinfomaniak:~/sites/mathsmanager.fr/mathsManager/public
	rsync -avz storage/app/public/images/ davidinfomaniak:~/sites/mathsmanager.fr/mathsManager/public/storage/images/
	ssh davidinfomaniak 'cd ~/sites/mathsmanager.fr && git pull origin main && make install && php artisan storage:link'

# Cible pour installer les dépendances et effectuer la migration
install: vendor/autoload.php .env public/storage
	php artisan cache:clear
	php artisan migrate

# Cible pour générer le fichier .env à partir de l'exemple et générer la clé d'application
.env:
	cp .env.example .env
	php artisan key:generate

# Cible pour créer le lien symbolique du dossier storage vers public
public/storage:
	php artisan storage:link

# Installation des dépendances PHP avec Composer
vendor/autoload.php: composer.lock
	composer install
	touch vendor/autoload.php

# Compilation des assets avec npm
public/build/manifest.json: package.json
	npm install
	npm run build

# Cible pour synchroniser seulement les images de storage (à exécuter manuellement si nécessaire)
sync-images:
	rsync -avz storage/app/public/images/ davidinfomaniak:~/sites/mathsmanager.fr/mathsManager/public/storage/images/
