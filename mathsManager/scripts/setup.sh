#!/bin/bash

# Script d'installation complète pour Maths Manager
# Usage: ./scripts/setup.sh

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Se placer dans le répertoire racine du projet
cd "$(dirname "$0")/.."

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════╗"
echo "║         MATHS MANAGER SETUP              ║"
echo "║      Installation automatique           ║"
echo "╚══════════════════════════════════════════╝"
echo -e "${NC}"

# Fonction pour vérifier si une commande existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Vérifier les prérequis
echo -e "${YELLOW}🔍 Vérification des prérequis...${NC}"

if ! command_exists php; then
    echo -e "${RED}❌ PHP n'est pas installé${NC}"
    exit 1
fi

if ! command_exists composer; then
    echo -e "${RED}❌ Composer n'est pas installé${NC}"
    exit 1
fi

if ! command_exists node; then
    echo -e "${RED}❌ Node.js n'est pas installé${NC}"
    exit 1
fi

if ! command_exists npm; then
    echo -e "${RED}❌ NPM n'est pas installé${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Tous les prérequis sont installés${NC}"

# 1. Installation des dépendances
echo -e "${YELLOW}📦 Installation des dépendances PHP...${NC}"
composer install

echo -e "${YELLOW}📦 Installation des dépendances Node.js...${NC}"
npm install

# 2. Configuration de l'environnement
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚙️  Création du fichier .env...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✅ Fichier .env créé${NC}"
else
    echo -e "${YELLOW}⚠️  Le fichier .env existe déjà${NC}"
fi

# 3. Génération de la clé d'application
echo -e "${YELLOW}🔑 Génération de la clé d'application...${NC}"
php artisan key:generate --force

# 4. Configuration de la base de données
echo -e "${YELLOW}🗄️  Configuration de la base de données...${NC}"
echo "Choisissez votre méthode d'installation :"
echo "1) Docker (Recommandé)"
echo "2) XAMPP"
echo "3) MySQL/MariaDB local"

read -p "Votre choix (1-3): " -n 1 -r
echo

case $REPLY in
    1)
        echo -e "${YELLOW}🐳 Configuration avec Docker...${NC}"
        
        if ! command_exists docker; then
            echo -e "${RED}❌ Docker n'est pas installé${NC}"
            exit 1
        fi
        
        # Arrêter le conteneur s'il existe
        docker stop mathsmanager-db 2>/dev/null || true
        docker rm mathsmanager-db 2>/dev/null || true
        
        # Lancer le conteneur MariaDB
        echo -e "${YELLOW}🚀 Lancement du conteneur MariaDB...${NC}"
        docker run -d \
            --name mathsmanager-db \
            -e MYSQL_ROOT_PASSWORD=root \
            -e MYSQL_DATABASE=mathsManager \
            -p 3307:3306 \
            mariadb:10.6
        
        # Attendre que le conteneur soit prêt
        echo -e "${YELLOW}⏳ Attente du démarrage de la base de données...${NC}"
        
        # Attendre que MariaDB soit réellement prêt
        for i in {1..30}; do
            if docker exec mathsmanager-db mysql -uroot -proot -e "SELECT 1" >/dev/null 2>&1; then
                echo -e "${GREEN}✅ Base de données prête !${NC}"
                break
            fi
            echo "Tentative $i/30..."
            sleep 2
        done
        
        # Mettre à jour le .env avec des expressions plus robustes
        sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
        sed -i 's/^DB_PORT=.*/DB_PORT=3307/' .env
        sed -i 's/^DB_DATABASE=.*/DB_DATABASE=mathsManager/' .env
        sed -i 's/^DB_USERNAME=.*/DB_USERNAME=root/' .env
        sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=root/' .env
        ;;
        
    2)
        echo -e "${YELLOW}📊 Configuration avec XAMPP...${NC}"
        echo "Assurez-vous que XAMPP est démarré (Apache + MySQL)"
        
        # Mettre à jour le .env
        sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
        sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env
        sed -i 's/^DB_DATABASE=.*/DB_DATABASE=mathsManager/' .env
        sed -i 's/^DB_USERNAME=.*/DB_USERNAME=root/' .env
        sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=/' .env
        
        read -p "Appuyez sur Entrée quand XAMPP est prêt..."
        ;;
        
    3)
        echo -e "${YELLOW}🗄️  Configuration MySQL/MariaDB local...${NC}"
        read -p "Host (127.0.0.1): " db_host
        read -p "Port (3306): " db_port
        read -p "Database (mathsManager): " db_name
        read -p "Username (root): " db_user
        read -s -p "Password: " db_pass
        echo
        
        # Mettre à jour le .env
        sed -i "s/^DB_HOST=.*/DB_HOST=${db_host:-127.0.0.1}/" .env
        sed -i "s/^DB_PORT=.*/DB_PORT=${db_port:-3306}/" .env
        sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${db_name:-mathsManager}/" .env
        sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${db_user:-root}/" .env
        sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${db_pass}/" .env
        ;;
        
    *)
        echo -e "${RED}❌ Choix invalide${NC}"
        exit 1
        ;;
esac

# 5. Test de la connexion à la base de données
echo -e "${YELLOW}🔌 Test de la connexion à la base de données...${NC}"
sleep 2

# Tentatives multiples de connexion
for i in {1..5}; do
    # Test de connexion plus simple avec une requête SQL basique
    if php -r "try { 
        \$pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=mathsManager', 'root', 'root');
        echo 'OK';
    } catch(Exception \$e) {
        echo 'Erreur: ' . \$e->getMessage() . PHP_EOL;
        exit(1);
    }"; then
        echo -e "${GREEN}✅ Connexion à la base de données réussie${NC}"
        break
    else
        if [ $i -eq 5 ]; then
            echo -e "${RED}❌ Impossible de se connecter à la base de données après 5 tentatives${NC}"
            echo "Vérifiez vos paramètres dans le fichier .env"
            echo "Contenu actuel du .env (section DB):"
            grep "^DB_" .env
            echo "Test direct avec les paramètres :"
            php -r "try { 
                \$pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=mathsManager', 'root', 'root');
                echo 'Connexion PDO OK' . PHP_EOL;
            } catch(Exception \$e) {
                echo 'Erreur: ' . \$e->getMessage() . PHP_EOL;
            }"
            exit 1
        fi
        echo "Tentative $i/5 échouée, nouvelle tentative dans 3 secondes..."
        sleep 3
    fi
done

# 6. Exécution des migrations
echo -e "${YELLOW}🔄 Exécution des migrations...${NC}"
php artisan migrate

# 7. Création des liens symboliques
echo -e "${YELLOW}🔗 Création des liens symboliques...${NC}"
php artisan storage:link

# 8. Compilation des assets
echo -e "${YELLOW}🎨 Compilation des assets...${NC}"
npm run build

# 9. Proposer les actions optionnelles
echo -e "${GREEN}"
echo "╔══════════════════════════════════════════╗"
echo "║            INSTALLATION TERMINÉE        ║"
echo "╚══════════════════════════════════════════╝"
echo -e "${NC}"

echo -e "${GREEN}🎉 Installation terminée avec succès !${NC}"
echo ""

# Proposer l'import des données si pas encore fait
if [ -f "mathsmanager-sample.sql" ]; then
    echo -e "${YELLOW}📊 Voulez-vous importer les données de démonstration ? (y/N):${NC}"
    read -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "Import de mathsmanager-sample.sql..."
        source <(grep -E '^(DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=' .env | sed 's/^/export /')
        
        if [ "$DB_PORT" = "3307" ]; then
            docker exec -i mathsmanager-db mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < "mathsmanager-sample.sql"
        else
            mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < "mathsmanager-sample.sql"
        fi
        
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✅ Données de démonstration importées${NC}"
        else
            echo -e "${RED}❌ Erreur lors de l'import des données${NC}"
        fi
    fi
fi

# Proposer de lancer les serveurs
echo ""
echo -e "${YELLOW}🚀 Voulez-vous lancer les serveurs maintenant ? (y/N):${NC}"
read -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Lancement du serveur Vite (dev assets)...${NC}"
    npm run dev &
    
    echo -e "${YELLOW}Lancement du serveur Laravel...${NC}"
    php artisan serve
else
    echo ""
    echo -e "${YELLOW}📋 Prochaines étapes :${NC}"
    echo "1. Lancez le serveur de développement :"
    echo -e "   ${BLUE}php artisan serve${NC}"
    echo ""
    echo "2. Pour le développement avec hot reload :"
    echo -e "   ${BLUE}npm run dev${NC} (dans un autre terminal)"
    echo ""
    echo "3. Accédez à l'application :"
    echo -e "   ${BLUE}http://localhost:8000${NC}"
    echo ""
    echo -e "${YELLOW}🛠️  Commandes utiles :${NC}"
    echo -e "   Export BDD : ${BLUE}./scripts/export-db.sh${NC}"
    echo -e "   Import BDD : ${BLUE}./scripts/import-db.sh fichier.sql${NC}"
    echo -e "   Logs       : ${BLUE}tail -f storage/logs/laravel.log${NC}"
    echo ""
    echo -e "${YELLOW}📚 Consultez le README.md pour plus d'informations${NC}"
fi

