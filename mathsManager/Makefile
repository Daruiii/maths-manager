.PHONY: serve vite deploy install prod-optimize sync-images clean status help

# Variables (peuvent être overridées via Makefile.local ou variables d'environnement)
SERVER_USER ?= your-server-user
SERVER_HOST ?= your-server-path
PREPROD_HOST ?= your-preprod-path
PHP_BINARY ?= php
NPM_BINARY ?= npm

# Inclure les variables locales si le fichier existe (non versionné)
-include Makefile.local

# Exemple d'utilisation :
# 1. Créer un fichier Makefile.local avec tes vraies variables (ajouté au .gitignore)
# 2. Ou utiliser: SERVER_USER=monuser SERVER_HOST=mon.serveur.com:/path make deploy

# Aide
help:
	@echo "Commandes disponibles :"
	@echo "  serve        - Lancer le serveur de développement PHP"
	@echo "  vite         - Lancer Vite en mode développement"
	@echo "  deploy       - Déployer en production (branche main)"
	@echo "  deploy-preprod - Déployer en preprod (branche develop)"
	@echo "  prod-optimize - Optimiser pour la production"
	@echo "  install      - Installer les dépendances"
	@echo "  sync-images  - Synchroniser les images vers la production"
	@echo "  fetch-images - Récupérer les images depuis la production"
	@echo "  export-prod-to-sample - Exporter la base prod → sample local"
	@echo "  import-sample-local - Importer le sample → base locale"
	@echo "  deploy-sample-to-preprod - Déployer le sample → preprod"
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
	$(PHP_BINARY) artisan view:clear
	$(NPM_BINARY) run build
	@echo "✅ Optimisations terminées"

# Déploiement en production (branche main)
# Étapes :
# 1. Optimise le code (cache config/routes/views + build Vite)
# 2. Sync le code vers le serveur (EXCLUT public/storage pour éviter bug symlink)
# 3. Sync les images séparément vers leur destination finale (public/storage/images/)
# 4. Sur le serveur : installe dépendances, migrations, recrée symlink storage, fixe permissions
deploy: prod-optimize
	@echo "📦 Déploiement en cours..."
	@mkdir -p storage/app/public/images
	@rm -f public/hot  # Supprime le fichier Vite hot pour forcer l'usage des assets compilés
	# Rsync 1/2 : Sync tout le code SAUF public/storage (symlink) et .env (spécifique serveur)
	rsync -avz --exclude='node_modules' --exclude='.git' --exclude='.env' --exclude='storage/logs/*' --exclude='bootstrap/cache/*' --exclude='public/storage' --exclude='resources/js/ziggy.js' . $(SERVER_USER):$(SERVER_HOST)/
	@if [ -d "storage/app/public/images" ]; then \
		rsync -avz storage/app/public/images/ $(SERVER_USER):$(SERVER_HOST)/public/storage/images/; \
	else \
		echo "⚠️  Dossier images introuvable, ignoré"; \
	fi
	# Commandes sur le serveur : fixe permissions racine + cleanup + install prod + symlink
	ssh $(SERVER_USER) 'cd $(SERVER_HOST) && chmod 755 . && rm -f public/hot && rm -rf public/storage && make install-prod && php-8.3 artisan storage:link'
	@echo "🎉 Déploiement terminé !"

# Déploiement en preprod (branche develop) - Même logique que deploy prod
deploy-preprod: prod-optimize
	@echo "📦 Déploiement preprod en cours..."
	@mkdir -p storage/app/public/images
	@rm -f public/hot
	# Rsync 1/2 : Sync tout le code SAUF public/storage (symlink) et .env (spécifique serveur)
	rsync -avz --exclude='node_modules' --exclude='.git' --exclude='.env' --exclude='storage/logs/*' --exclude='bootstrap/cache/*' --exclude='public/storage' --exclude='resources/js/ziggy.js' . $(SERVER_USER):$(PREPROD_HOST)/
	@if [ -d "storage/app/public/images" ]; then \
		rsync -avz storage/app/public/images/ $(SERVER_USER):$(PREPROD_HOST)/public/storage/images/; \
	else \
		echo "⚠️  Dossier images introuvable, ignoré"; \
	fi
	# Commandes sur le serveur : fixe permissions racine + cleanup + install preprod + symlink
	ssh $(SERVER_USER) 'cd $(PREPROD_HOST) && chmod 755 . && rm -f public/hot && rm -rf public/storage && make install-preprod && php-8.3 artisan storage:link'
	@echo "🎉 Déploiement preprod terminé !"

# Installation pour la production (sur le serveur)
# Exécuté automatiquement après deploy via SSH
# Configure l'environnement en mode production et optimise Laravel
install-prod: vendor/autoload.php .env public/storage
	@echo "🔧 Installation en mode production..."
	@echo "🔧 Fixing permissions..."
	chmod -R 775 storage bootstrap/cache
	find public -type f -exec chmod 644 {} \; || true
	find public -type d -exec chmod 755 {} \; || true
	@echo "🔧 Setting production environment..."
	# Force APP_ENV=production (remplace n'importe quelle valeur existante)
	sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env
	# Force APP_DEBUG=false (remplace n'importe quelle valeur existante)
	sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env
	@echo "🔧 Clearing cached config..."
	rm -rf bootstrap/cache/config.php bootstrap/cache/routes*.php bootstrap/cache/packages.php bootstrap/cache/services.php || true
	php-8.3 artisan config:clear || true
	php-8.3 artisan cache:clear || true
	php-8.3 artisan migrate --force
	@echo "✅ Installation production terminée"

# Installation pour la preprod (sur le serveur)
# Exécuté automatiquement après deploy-preprod via SSH
# Configure l'environnement en mode staging (preprod) avec debug activé
install-preprod: vendor/autoload.php .env public/storage
	@echo "🔧 Installation en mode preprod..."
	@echo "🔧 Fixing permissions..."
	chmod -R 775 storage bootstrap/cache
	find public -type f -exec chmod 644 {} \; || true
	find public -type d -exec chmod 755 {} \; || true
	@echo "🔧 Setting preprod environment..."
	# Force APP_ENV=staging (remplace n'importe quelle valeur existante)
	sed -i 's/^APP_ENV=.*/APP_ENV=staging/' .env
	# Force APP_DEBUG=true pour preprod (remplace n'importe quelle valeur existante)
	sed -i 's/^APP_DEBUG=.*/APP_DEBUG=true/' .env
	@echo "🔧 Clearing cached config..."
	rm -rf bootstrap/cache/config.php bootstrap/cache/routes*.php bootstrap/cache/packages.php bootstrap/cache/services.php || true
	php-8.3 artisan config:clear || true
	php-8.3 artisan cache:clear || true
	php-8.3 artisan migrate --force
	@echo "✅ Installation preprod terminée"

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

# Synchroniser manuellement les images locales vers la production
# Utilité : Si tu ajoutes/modifies des images en local et veux les pousser sans redéployer tout le code
# Comportement : Ajoute/met à jour les images locales sur le serveur (ne supprime PAS les images serveur)
# Attention : Si tu supprimes une image en local, elle restera présente sur le serveur
sync-images:
	@echo "📸 Synchronisation des images vers la production..."
	rsync -avz storage/app/public/images/ $(SERVER_USER):$(SERVER_HOST)/public/storage/images/
	@echo "✅ Images synchronisées vers la production"

# Récupérer les images depuis la production en local
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

# Rollback non implémenté (déploiement via rsync, pas git)
# Pour rollback : redéployer manuellement une version antérieure depuis git local

# Logs de production
logs:
	ssh $(SERVER_USER) 'tail -f $(SERVER_HOST)/storage/logs/laravel.log'

# Test des variables (pour debug)
test-vars:
	@echo "SERVER_USER: $(SERVER_USER)"
	@echo "SERVER_HOST: $(SERVER_HOST)"
	@echo "PREPROD_HOST: $(PREPROD_HOST)"


# Exporter la base de données de prod vers le fichier sample local
# Utilité : Mettre à jour mathsmanager-sample.sql.gz avec les données réelles de prod
export-prod-to-sample:
	@echo "📥 Export de la base de données prod..."
	@echo "🗄️  Dump de la base prod sur le serveur (saisissez le mot de passe MySQL)..."
	ssh -t $(SERVER_USER) 'cd $(SERVER_HOST) && mysqldump -h dm2blc.myd.infomaniak.com -u dm2blc_david -p dm2blc_mathsManager > temp-dump.sql && gzip -f temp-dump.sql'
	@echo "📥 Téléchargement du dump..."
	rsync -avz $(SERVER_USER):$(SERVER_HOST)/temp-dump.sql.gz mathsmanager-sample.sql.gz
	@echo "🧹 Nettoyage du serveur..."
	ssh $(SERVER_USER) 'cd $(SERVER_HOST) && rm -f temp-dump.sql.gz'
	@echo "✅ Base de données exportée dans mathsmanager-sample.sql.gz"

# Importer les données sample en local
# Utilité : Remplir la base de données locale avec les données sample (données de prod)
# Supporte Docker (mathsmanager-db) et MySQL natif
import-sample-local:
	@echo "📥 Import des données sample en local..."
	@echo "⚠️  Cette opération va écraser toutes les données locales !"
	@if docker ps 2>/dev/null | grep -q mathsmanager-db; then \
		echo "🐳 Détecté : Docker container (mathsmanager-db)"; \
		echo "🗄️  Import via Docker..."; \
		gunzip -c mathsmanager-sample.sql.gz | docker exec -i mathsmanager-db mysql -u root -proot mathsManager; \
	elif command -v mysql >/dev/null 2>&1; then \
		echo "💻 Détecté : MySQL natif"; \
		echo "🗄️  Import en base locale (saisissez votre mot de passe)..."; \
		gunzip -c mathsmanager-sample.sql.gz | mysql -u root -p mathsmanager; \
	else \
		echo "❌ Erreur : ni Docker ni MySQL trouvé"; \
		exit 1; \
	fi
	@echo "🔄 Migrations et caches..."
	$(PHP_BINARY) artisan migrate
	$(PHP_BINARY) artisan config:clear
	$(PHP_BINARY) artisan cache:clear
	@echo "✅ Données sample importées en local"

# Déployer les données sample vers la preprod
deploy-sample-to-preprod:
	@echo "🎯 Déploiement des données sample → preprod..."
	@echo "⚠️  Cette opération va écraser toutes les données de la preprod !"
	@echo "📦 Upload du fichier sample..."
	rsync -avz mathsmanager-sample.sql.gz $(SERVER_USER):$(PREPROD_HOST)/
	@echo "🗄️  Import en base preprod (saisissez le mot de passe MySQL)..."
	ssh -t $(SERVER_USER) 'cd $(PREPROD_HOST) && echo "Import des données sample..." && gunzip -c mathsmanager-sample.sql.gz | mysql -h dm2blc.myd.infomaniak.com -u dm2blc_david -p dm2blc_mathsManager_preprod'
	@echo "🎉 Données sample déployées en preprod !"
