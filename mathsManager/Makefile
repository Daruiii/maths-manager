# Ce fichier Makefile permet de déployer l'application sur le serveur de production et de synchroniser les images du dossier storage

.PHONY: serve vite deploy install prod-optimize sync-images clean status help

# Variables
SERVER_USER = davidinfomaniak
SERVER_HOST = ~/sites/mathsmanager.fr/mathsManager
PHP_BINARY = php
NPM_BINARY = npm

# Aide
help:
	@echo "Commandes disponibles :"
	@echo "  serve        - Lancer le serveur de développement PHP"
	@echo "  vite         - Lancer Vite en mode développement"
	@echo "  deploy       - Déployer en production"
	@echo "  prod-optimize - Optimiser pour la production"
	@echo "  install      - Installer les dépendances"
	@echo "  sync-images  - Synchroniser les images vers la production"
	@echo "  fetch-images - Récupérer les images depuis la production"
	@echo "  clean        - Nettoyer les caches"
	@echo "  status       - Afficher le statut de l'application"

# Cible pour lancer le serveur de développement de PHP
serve:
	$(PHP_BINARY) artisan serve

# Cible pour lancer le serveur de développement de Vite
vite:
	$(NPM_BINARY) run dev

# Optimisations pour la production
prod-optimize:
	@echo "🚀 Optimisation pour la production..."
	$(PHP_BINARY) artisan config:cache
	$(PHP_BINARY) artisan route:cache
	$(PHP_BINARY) artisan view:cache
	$(NPM_BINARY) run build
	@echo "✅ Optimisations terminées"

# Cible principale de déploiement
deploy: prod-optimize
	@echo "📦 Déploiement en cours..."
	@mkdir -p storage/app/public/images
	rsync -avz --exclude='node_modules' --exclude='.git' --exclude='.env' --exclude='storage/logs/*' --exclude='bootstrap/cache/*' . $(SERVER_USER):$(SERVER_HOST)/
	@if [ -d "storage/app/public/images" ]; then \
		rsync -avz storage/app/public/images/ $(SERVER_USER):$(SERVER_HOST)/public/storage/images/; \
	else \
		echo "⚠️  Dossier images introuvable, ignoré"; \
	fi
	ssh $(SERVER_USER) 'cd $(SERVER_HOST) && make install-prod && (php-8.3 artisan storage:link || echo "Storage link already exists")'
	@echo "🎉 Déploiement terminé !"

# Installation pour la production (sur le serveur)
install-prod: vendor/autoload.php .env public/storage
	@echo "🔧 Installation en mode production..."
	@echo "🔧 Fixing storage permissions..."
	chmod -R 775 storage bootstrap/cache
	@echo "🔧 Setting production environment..."
	sed -i 's/APP_ENV=local/APP_ENV=production/' .env
	sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
	@echo "🔧 Clearing cached config..."
	rm -rf bootstrap/cache/config.php bootstrap/cache/routes*.php bootstrap/cache/packages.php bootstrap/cache/services.php || true
	php-8.3 artisan config:clear || true
	php-8.3 artisan cache:clear || true
	php-8.3 artisan migrate --force
	php-8.3 artisan config:cache
	php-8.3 artisan route:cache
	php-8.3 artisan view:cache
	@echo "✅ Installation production terminée"

# Cible pour installer les dépendances et effectuer la migration (développement)
install: vendor/autoload.php .env public/storage
	$(PHP_BINARY) artisan migrate

# Cible pour générer le fichier .env à partir de l'exemple et générer la clé d'application
.env:
	cp .env.example .env
	php artisan key:generate

# Cible pour créer le lien symbolique du dossier storage vers public
public/storage:
	php artisan storage:link

# Installation des dépendances PHP avec Composer
vendor/autoload.php: composer.lock
	php-8.3 /usr/bin/composer install
	touch vendor/autoload.php

# Compilation des assets avec npm
public/build/manifest.json: package.json
	npm install
	npm run build

# Cible pour synchroniser seulement les images de storage (à exécuter manuellement si nécessaire)
sync-images:
	@echo "📸 Synchronisation des images vers la production..."
	rsync -avz storage/app/public/images/ $(SERVER_USER):$(SERVER_HOST)/public/storage/images/
	@echo "✅ Images synchronisées vers la production"

# Récupérer les images depuis la production
fetch-images:
	@echo "📥 Récupération des images depuis la production..."
	@mkdir -p storage/app/public/images
	rsync -avz $(SERVER_USER):$(SERVER_HOST)/public/storage/images/ storage/app/public/images/
	@echo "✅ Images récupérées depuis la production"

# Nettoyer les caches
clean:
	@echo "🧹 Nettoyage des caches..."
	$(PHP_BINARY) artisan cache:clear
	$(PHP_BINARY) artisan config:clear
	$(PHP_BINARY) artisan route:clear
	$(PHP_BINARY) artisan view:clear
	@echo "✅ Caches nettoyés"

# Statut de l'application
status:
	@echo "📊 Statut de l'application :"
	$(PHP_BINARY) artisan about

# Déploiement rapide (sans rebuild des assets)
quick-deploy:
	@echo "⚡ Déploiement rapide..."
	rsync -avz --exclude='node_modules' --exclude='.git' --exclude='.env' --exclude='storage/logs/*' . $(SERVER_USER):$(SERVER_HOST)/
	ssh $(SERVER_USER) 'cd $(SERVER_HOST) && php-8.3 artisan migrate --force && php-8.3 artisan config:cache'
	@echo "✅ Déploiement rapide terminé"

# Rollback (revenir à la version précédente)
rollback:
	@echo "⏪ Rollback en cours..."
	ssh $(SERVER_USER) 'cd $(SERVER_HOST) && git reset --hard HEAD~1 && php-8.3 artisan migrate:rollback && make install-prod'
	@echo "✅ Rollback terminé"

# Logs de production
logs:
	ssh $(SERVER_USER) 'tail -f $(SERVER_HOST)/storage/logs/laravel.log'
