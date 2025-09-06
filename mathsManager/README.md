# Maths Manager

Une application Laravel pour la gestion d'exercices de mathématiques, avec génération de DS, correction automatique et système de récapitulatifs.

## 🚀 Installation et Configuration

### 💫 Installation rapide (Recommandée)

Pour une installation automatique, utilisez le script d'installation :

```bash
git clone https://github.com/Daruiii/maths-manager
cd mathsManager
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Le script vous guidera à travers toutes les étapes d'installation.

### 🔨 Installation manuelle

#### Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js et NPM
- Une base de données MariaDB/MySQL
- Docker (optionnel)

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

### Erreur "Connection refused"

**Problème** : L'application ne peut pas se connecter à la base de données.

**Solutions** :
1. Vérifier que la base de données est démarrée :
   ```bash
   # Pour Docker
   docker ps
   docker start mathsmanager-db
   
   # Pour XAMPP
   sudo /opt/lampp/lampp start
   ```

2. Vérifier les paramètres de connexion dans `.env`

3. Tester la connexion manuellement :
   ```bash
   # Pour Docker (port 3307)
   mysql -h 127.0.0.1 -P 3307 -u root -proot
   
   # Pour XAMPP (port 3306)
   mysql -h 127.0.0.1 -P 3306 -u root -p
   ```

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
