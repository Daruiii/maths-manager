# Maths Manager

Une application Laravel pour la gestion d'exercices de mathématiques, avec génération de DS, correction automatique et système de récapitulatifs.

## 🚀 Installation et Configuration

### 💫 Installation rapide (Recommandée)

**⚠️ Prérequis obligatoires** (à installer avant de lancer le script) :
- PHP 8.1 ou supérieur ([Installer PHP](https://www.php.net/downloads))
- Composer ([Installer Composer](https://getcomposer.org/download/))
- Node.js et NPM ([Installer Node.js](https://nodejs.org/))
- Docker (optionnel, uniquement pour la base de données) ([Installer Docker](https://docs.docker.com/get-docker/))

**📝 Note importante** : L'option Docker du script sert uniquement à créer une base de données MariaDB dans un conteneur. PHP, Composer et Node.js doivent être installés localement sur votre machine car le script les utilise pour installer les dépendances et lancer l'application.

Pour une installation automatique, utilisez le script d'installation :

```bash
git clone https://github.com/Daruiii/maths-manager
cd mathsManager
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Le script vous guidera à travers toutes les étapes d'installation et vérifiera automatiquement que tous les prérequis sont installés.

### 🔨 Installation manuelle

Si vous préférez installer manuellement (ou si le script automatique ne fonctionne pas), suivez ces étapes.

#### Prérequis

- PHP 8.1 ou supérieur avec les extensions `pdo_mysql` et `zip`
- Composer
- Node.js et NPM
- Une base de données MariaDB/MySQL (ou Docker pour la lancer en conteneur)
- Docker (optionnel, uniquement pour la base de données)

### 1. Cloner le projet

```bash
git clone https://github.com/Daruiii/maths-manager
cd mathsManager
```

### 2. Installation des dépendances

```bash
# Dépendances PHP
composer install

# Dépendances Node.js
npm install
```

### 3. Configuration de l'environnement

#### Option A : Configuration avec Docker (Recommandée)

1. **Créer le fichier `.env`** :
```bash
cp .env.example .env
```

2. **Lancer la base de données avec Docker** :
```bash
docker run -d \
  --name mathsmanager-db \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=mathsManager \
  -p 3307:3306 \
  mariadb:10.6
```

3. **Vérifier que le conteneur fonctionne** :
```bash
docker ps
```

4. **Configurer le `.env`** :
```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=mathsManager
DB_USERNAME=root
DB_PASSWORD=root
```

#### Option B : Configuration avec XAMPP

1. **Installer XAMPP** et démarrer Apache + MySQL
2. **Créer le fichier `.env`** :
```bash
cp .env.example .env
```

3. **Configurer le `.env`** :
```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mathsManager
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Finaliser la configuration

1. **Générer la clé d'application** :
```bash
php artisan key:generate
```

2. **Créer la base de données** (si elle n'existe pas) :
```bash
# Pour Docker
docker exec -it mathsmanager-db mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS mathsManager;"

# Pour XAMPP
mysql -u root -e "CREATE DATABASE IF NOT EXISTS mathsManager;"
```

3. **Exécuter les migrations** :
```bash
php artisan migrate
```

**OU** utiliser la base de données sample avec des données d'exemple :
```bash
# Au lieu des migrations, importer la base sample compressée
gunzip -c mathsmanager-sample.sql.gz | mysql -u root -p mathsManager
# OU avec Docker :
gunzip -c mathsmanager-sample.sql.gz | docker exec -i mathsmanager-db mysql -u root -proot mathsManager
```

4. **Créer les liens symboliques** :
```bash
php artisan storage:link
```

5. **Compiler les assets** :
```bash
npm run dev
```

### 5. Lancer l'application

```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 🔧 Problèmes courants et solutions

### OAuth Google ne fonctionne pas

**Problème** : Erreur lors de la connexion avec Google OAuth.

**Solutions** :

1. **Vider le cache de configuration Laravel** :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Vérifier que les clés sont bien dans le `.env`** :
   ```env
   GOOGLE_CLIENT_ID=votre-client-id
   GOOGLE_CLIENT_SECRET=votre-client-secret
   ```

3. **Vérifier l'URL de callback dans Google Cloud Console** :
   - Aller sur [Google Cloud Console](https://console.cloud.google.com/)
   - APIs & Services → Credentials → OAuth 2.0 Client IDs
   - Ajouter dans "Authorized redirect URIs" : `http://localhost:8000/auth/google/callback`
   - ⚠️ **Important** : Si votre `APP_URL` dans `.env` est `http://127.0.0.1:8000`, ajoutez aussi `http://127.0.0.1:8000/auth/google/callback`
   - Pour la preprod/production, ajouter aussi : `https://votre-domaine.com/auth/google/callback`

4. **Tester l'URL directement** :
   ```
   http://localhost:8000/auth/google/redirect
   ```

### Mode Preprod / Staging

**Comment activer le mode preprod** :

1. **Modifier le `.env`** :
   ```env
   APP_ENV=staging  # au lieu de 'local' ou 'production'
   APP_DEBUG=false
   APP_PREPROD_PASSWORD=votre-mot-de-passe-sécurisé
   ```

2. **Comportement en mode preprod** :
   - L'application demande un mot de passe avant d'accéder au site
   - Le mot de passe est défini par `APP_PREPROD_PASSWORD` dans le `.env`
   - Un cookie est créé pour 7 jours après authentification
   - Les robots de crawl sont bloqués (fichier `robots-preprod.txt`)

3. **Accéder à la preprod** :
   - Visiter : `http://localhost:8000`
   - Entrer le mot de passe défini dans `APP_PREPROD_PASSWORD`
   - Le cookie permet de rester connecté

4. **Désactiver le mode preprod** :
   ```env
   APP_ENV=local  # ou 'production'
   ```

### Composer n'est pas installé

**Problème** : La commande `composer` n'est pas trouvée.

**Solution macOS** :
```bash
# Télécharger et installer Composer globalement
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/opt/homebrew/bin --filename=composer
php -r "unlink('composer-setup.php');"

# Vérifier l'installation
composer --version
```

**Solution Linux/Windows** : [Suivre les instructions officielles](https://getcomposer.org/download/)

### Erreur "Connection refused" ou "SQLSTATE[HY000] [2002]"

**Problème** : L'application ne peut pas se connecter à la base de données.

**Cause** : Le service de base de données n'est pas démarré (très courant au redémarrage de l'ordinateur).

**Solutions** :

1. **Avec Docker** :
   ```bash
   # Vérifier si Docker est lancé
   docker ps
   
   # Si erreur "Cannot connect to the Docker daemon"
   open -a Docker  # macOS
   # Attendre quelques secondes que Docker démarre
   
   # Démarrer le conteneur de la base de données
   docker start mathsmanager-db
   
   # Vérifier que le conteneur fonctionne
   docker ps | grep mathsmanager-db
   ```

2. **Avec XAMPP** :
   ```bash
   # Démarrer XAMPP
   sudo /opt/lampp/lampp start
   
   # Ou via l'interface graphique XAMPP
   # Démarrer Apache + MySQL
   ```

3. **Avec MySQL/MariaDB local** :
   ```bash
   # macOS (Homebrew)
   brew services start mariadb
   # ou
   brew services start mysql
   
   # Linux (systemd)
   sudo systemctl start mariadb
   # ou
   sudo systemctl start mysql
   
   # Pour démarrer automatiquement au boot
   brew services enable mariadb  # macOS
   sudo systemctl enable mariadb # Linux
   ```

4. **Vérifier les paramètres de connexion dans `.env`** :
   ```env
   DB_CONNECTION=mariadb
   DB_HOST=127.0.0.1
   DB_PORT=3307  # 3307 pour Docker, 3306 pour XAMPP/local
   DB_DATABASE=mathsManager
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

5. **Tester la connexion** :
   ```bash
   # Avec Laravel
   php artisan db:show
   
   # Ou manuellement
   mysql -h 127.0.0.1 -P 3307 -u root -proot  # Docker
   mysql -h 127.0.0.1 -P 3306 -u root -p      # XAMPP/local
   
   # Avec Docker exec (si client mysql non installé)
   docker exec -it mathsmanager-db mysql -uroot -proot
   ```

**💡 Astuce** : Au redémarrage de votre ordinateur, pensez à relancer Docker ou votre service MySQL/MariaDB !

### Erreur "Port already in use"

**Problème** : Le port 3306 est déjà utilisé.

**Solutions** :
1. Utiliser Docker avec le port 3307 (recommandé)
2. Arrêter le service qui utilise le port 3306 :
   ```bash
   sudo systemctl stop mysql
   sudo systemctl stop mariadb
   ```

### Erreur "No application encryption key"

**Solution** :
```bash
php artisan key:generate
```

### Erreur "Class 'ZipArchive' not found"

**Solution** :
```bash
sudo apt-get install php-zip
```

### Erreur "proc_open(): fork failed"

**Solution** :
```bash
sudo sysctl -w vm.max_map_count=262144
```

### Erreur de permissions sur les fichiers

**Solution** :
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📦 Fonctionnalités

### Principales fonctionnalités

- **Gestion des classes** : Création et gestion des classes d'élèves
- **Chapitres et sous-chapitres** : Organisation hiérarchique du contenu
- **Exercices** : Création et gestion d'exercices avec différents niveaux de difficulté
- **Génération de DS** : Création automatique de devoirs surveillés
- **Correction automatique** : Système de correction avec upload de fichiers
- **Récapitulatifs** : Génération de fiches de révision
- **Quizz** : Système de quiz interactifs
- **Authentification** : Système d'authentification avec rôles (admin, professeur, élève)

### Fonctionnalités avancées

- **OAuth** : Connexion via GitHub et Google (optionnel - voir configuration ci-dessous)
- **Emails** : Système d'envoi d'emails (Mailtrap pour le développement)
- **Export PDF** : Génération de PDF pour les DS et corrections
- **Cache** : Système de cache pour optimiser les performances

### Configuration OAuth (Optionnel)

L'application supporte l'authentification via Google et GitHub. Pour l'activer :

1. **Créer une application OAuth** :
   - Google : [Google Cloud Console](https://console.cloud.google.com/)
   - GitHub : [GitHub Developer Settings](https://github.com/settings/developers)

2. **Configurer les redirections** :
   - Google : `http://localhost:8000/auth/google/callback`
   - GitHub : `http://localhost:8000/auth/github/callback`

3. **Ajouter les clés dans `.env`** :
   ```env
   GOOGLE_CLIENT_ID=votre-google-client-id
   GOOGLE_CLIENT_SECRET=votre-google-client-secret
   GITHUB_CLIENT_ID=votre-github-client-id
   GITHUB_CLIENT_SECRET=votre-github-client-secret
   ```

⚠️ **Important** : Ces clés sont personnelles et ne doivent pas être partagées publiquement.

## 🗄️ Structure de la base de données

### Tables principales

- `users` : Utilisateurs (élèves, professeurs, admins)
- `classes` : Classes d'élèves
- `chapters` : Chapitres de cours
- `subchapters` : Sous-chapitres
- `exercises` : Exercices
- `ds` : Devoirs surveillés
- `correction_requests` : Demandes de correction
- `quizz_questions` : Questions de quiz
- `recaps` : Récapitulatifs de cours

## 🔧 Maintenance

### Commandes utiles

```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Voir le statut des migrations
php artisan migrate:status

# Rollback des migrations
php artisan migrate:rollback

# Seed de la base de données
php artisan db:seed
```

### Logs

Les logs sont stockés dans `storage/logs/laravel.log`

### Backup de la base de données

```bash
# Docker
docker exec mathsmanager-db mysqldump -u root -proot mathsManager > backup.sql

# XAMPP
mysqldump -u root -p mathsManager > backup.sql
```

## 🚀 Déploiement

### Prérequis pour la production

1. Configurer un serveur web (Apache/Nginx)
2. Configurer PHP-FPM
3. Installer une base de données MariaDB/MySQL
4. Configurer les variables d'environnement
5. Optimiser les performances

### Variables d'environnement importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
DB_CONNECTION=mariadb
DB_HOST=votre-host
DB_DATABASE=votre-database
DB_USERNAME=votre-username
DB_PASSWORD=votre-password
```

### Déploiement avec le Makefile

Pour déployer en production, configurez vos variables de serveur :

```bash
# Définir vos paramètres de serveur
SERVER_USER=votre-utilisateur SERVER_HOST=votre.serveur.com:/path/vers/app make deploy
```

Ou créez un fichier `.env.deploy` :
```env
SERVER_USER=votre-utilisateur
SERVER_HOST=votre.serveur.com:/path/vers/app
```

## 📝 Contribuer

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 🐛 Signaler un bug

Si vous rencontrez un problème :
1. Vérifiez les logs dans `storage/logs/laravel.log`
2. Consultez la section "Problèmes courants" ci-dessus
3. Ouvrez une issue avec les détails du problème

## 📄 Licence

Ce projet est sous licence MIT.

## 👥 Auteurs

- **David** - Développeur principal
- **Maxime** - Contributeur

---

Pour toute question ou problème, n'hésitez pas à consulter les logs ou à ouvrir une issue sur le dépôt GitHub.
