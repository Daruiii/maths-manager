# Changelog

Toutes les modifications importantes de ce projet seront documentées dans ce fichier.

## [Unreleased]

### Ajouté
- 🚀 **Script d'installation automatique** (`scripts/setup.sh`)
  - Installation guidée avec choix Docker/XAMPP/MySQL local
  - Vérification automatique des prérequis
  - Configuration automatique du fichier `.env`
  - Test de connexion à la base de données
  
- 🗄️ **Scripts de gestion de base de données**
  - `scripts/export-db.sh` : Export automatique de la base de données
  - `scripts/import-db.sh` : Import automatique avec confirmation
  - Support Docker et installations locales
  
- 📚 **Documentation complète**
  - README détaillé avec instructions d'installation
  - Section de résolution des problèmes courants
  - Guide de configuration pour différents environnements
  
- 📦 **Base de données d'exemple**
  - `mathsmanager-sample.sql` : Base avec données d'exemple
  - Facilite l'installation pour les nouveaux développeurs
  
- ⚙️ **Configuration améliorée**
  - `.env.example` mis à jour avec commentaires
  - Support OAuth GitHub/Google
  - Configuration email Mailtrap
  - Variables d'environnement documentées

### Amélioré
- 🔧 **Résolution des problèmes de configuration**
  - Gestion des conflits de ports (3306 vs 3307)
  - Support Docker et XAMPP en parallèle
  - Messages d'erreur plus clairs
  
- 📋 **Documentation des erreurs courantes**
  - "Connection refused"
  - "Port already in use" 
  - "No application encryption key"
  - "Class 'ZipArchive' not found"
  - Erreurs de permissions

### Technique
- 🐳 **Conteneur Docker optimisé**
  - MariaDB 10.6 sur port 3307
  - Variables d'environnement configurées
  - Persistance des données
  
- 🔒 **Sécurité**
  - Exclusion des exports de BDD du versioning (sauf sample)
  - Configuration des variables sensibles
  
- 🎨 **Scripts colorés et interactifs**
  - Interface utilisateur améliorée
  - Messages d'état clairs
  - Validation des entrées

## [v1.0.0] - 2024-XX-XX

### Fonctionnalités principales
- ✅ Système de gestion des classes et élèves
- ✅ Création et organisation des chapitres/sous-chapitres
- ✅ Gestion des exercices avec niveaux de difficulté
- ✅ Génération automatique de DS
- ✅ Système de correction avec upload de fichiers
- ✅ Récapitulatifs et fiches de révision
- ✅ Quiz interactifs
- ✅ Authentification avec rôles (admin, professeur, élève)
- ✅ Export PDF pour DS et corrections
- ✅ Cache et optimisations

---

## Guide de mise à jour

### Pour les nouveaux développeurs
1. Cloner le projet
2. Exécuter `./scripts/setup.sh`
3. Suivre les instructions à l'écran

### Pour les développeurs existants
1. Tirer les dernières modifications
2. Mettre à jour `.env` si nécessaire
3. Exécuter `composer install` et `npm install`
4. Optionnel : importer la base sample avec `./scripts/import-db.sh mathsmanager-sample.sql`

### Migration vers Docker
Si vous utilisiez XAMPP et voulez passer à Docker :
1. Exporter votre base actuelle : `./scripts/export-db.sh`
2. Arrêter XAMPP
3. Lancer Docker : `./scripts/setup.sh` (choix 1)
4. Importer vos données : `./scripts/import-db.sh votre-export.sql`

