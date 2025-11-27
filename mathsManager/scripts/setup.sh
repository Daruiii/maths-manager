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
echo "║      Installation automatique            ║"
echo "╚══════════════════════════════════════════╝"
echo -e "${NC}"
echo ""
echo -e "${YELLOW}📋 Important :${NC}"
echo "  • Ce script installe l'application Laravel sur votre machine"
echo "  • L'option Docker sert uniquement pour la base de données"
echo "  • PHP, Composer et Node.js doivent être installés localement"
echo ""
sleep 2

# Fonction pour vérifier si une commande existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Vérifier les prérequis
echo -e "${YELLOW}🔍 Vérification des prérequis...${NC}"
sleep 1

if ! command_exists php; then
    echo -e "${RED}❌ PHP n'est pas installé${NC}"
    echo ""
    echo -e "${YELLOW}💡 Comment installer PHP :${NC}"
    if [[ "$(uname)" == "Darwin" ]]; then
        echo "  macOS : brew install php@8.2"
    else
        echo "  Ubuntu/Debian : sudo apt-get install php8.2 php8.2-cli php8.2-mysql php8.2-zip"
        echo "  Fedora : sudo dnf install php php-cli php-mysqlnd php-zip"
    fi
    exit 1
fi

# Vérifier la version minimale de PHP (>= 8.1)
if ! php -r "exit(version_compare(PHP_VERSION,'8.1.0','<') ? 1 : 0);"; then
    CURRENT_PHP_VERSION=$(php -r 'echo PHP_VERSION;')
    echo -e "${RED}❌ PHP >= 8.1 requis (version actuelle: ${CURRENT_PHP_VERSION})${NC}"
    echo "Veuillez mettre à jour PHP ou utiliser une version compatible (par ex. via Homebrew, phpenv, Docker, ...)."
    exit 1
fi

# Vérifier extensions PHP utiles
for ext in pdo_mysql zip; do
    if ! php -m | grep -q "${ext}"; then
        echo -e "${RED}❌ L'extension PHP '${ext}' n'est pas installée${NC}"
        echo ""
        echo -e "${YELLOW}💡 Comment installer l'extension PHP ${ext} :${NC}"
        if [[ "$(uname)" == "Darwin" ]]; then
            echo "  macOS : L'extension devrait être incluse avec Homebrew PHP"
            echo "  Vérifiez votre php.ini ou réinstallez : brew reinstall php@8.2"
        else
            echo "  Ubuntu/Debian : sudo apt-get install php8.2-mysql php8.2-zip"
            echo "  Fedora : sudo dnf install php-mysqlnd php-zip"
        fi
        exit 1
    fi
done

if ! command_exists composer; then
    echo -e "${RED}❌ Composer n'est pas installé${NC}"
    echo ""
    echo -e "${YELLOW}💡 Comment installer Composer :${NC}"
    if [[ "$(uname)" == "Darwin" ]]; then
        echo "  macOS (installation globale) :"
        echo "  php -r \"copy('https://getcomposer.org/installer', 'composer-setup.php');\""
        echo "  php composer-setup.php --install-dir=/opt/homebrew/bin --filename=composer"
        echo "  php -r \"unlink('composer-setup.php');\""
    else
        echo "  Linux : https://getcomposer.org/download/"
    fi
    exit 1
fi

if ! command_exists node; then
    echo -e "${RED}❌ Node.js n'est pas installé${NC}"
    echo ""
    echo -e "${YELLOW}💡 Comment installer Node.js :${NC}"
    if [[ "$(uname)" == "Darwin" ]]; then
        echo "  macOS : brew install node"
    else
        echo "  Ubuntu/Debian : curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash - && sudo apt-get install -y nodejs"
    fi
    echo "  Ou téléchargez depuis : https://nodejs.org/"
    exit 1
fi

if ! command_exists npm; then
    echo -e "${RED}❌ NPM n'est pas installé${NC}"
    echo -e "${YELLOW}💡 NPM est normalement inclus avec Node.js${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Tous les prérequis sont installés${NC}"
sleep 1

if [[ "$(uname)" == "Darwin" ]]; then
    SED_INPLACE=("-i" "")
else
    SED_INPLACE=("-i")
fi

# 1. Installation des dépendances
echo -e "${YELLOW}📦 Installation des dépendances PHP...${NC}"
sleep 1
composer install

echo -e "${YELLOW}📦 Installation des dépendances Node.js...${NC}"
sleep 1
npm install

# 2. Configuration de l'environnement
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚙️  Création du fichier .env...${NC}"
    sleep 1
    cp .env.example .env
    echo -e "${GREEN}✅ Fichier .env créé${NC}"
else
    echo -e "${YELLOW}⚠️  Le fichier .env existe déjà${NC}"
fi

# 3. Génération de la clé d'application
echo -e "${YELLOW}🔑 Génération de la clé d'application...${NC}"
sleep 1
php artisan key:generate --force

# 4. Configuration de la base de données
echo ""
echo -e "${YELLOW}🗄️  Configuration de la base de données...${NC}"
sleep 1
echo ""
echo "Choisissez votre méthode d'installation de la base de données :"
echo "1) Docker (Recommandé) - Lance MariaDB dans un conteneur"
echo "2) XAMPP - Utilise MySQL/MariaDB de XAMPP"
echo "3) MySQL/MariaDB local - Base de données déjà installée sur votre machine"
echo ""
sleep 1

read -p "Votre choix (1-3): " -n 1 -r
echo

case $REPLY in
    1)
        echo -e "${YELLOW}🐳 Configuration avec Docker...${NC}"
        sleep 1
        
        if ! command_exists docker; then
            echo -e "${RED}❌ Docker n'est pas installé${NC}"
            exit 1
        fi
        
        # Arrêter le conteneur s'il existe
        docker stop mathsmanager-db 2>/dev/null || true
        docker rm mathsmanager-db 2>/dev/null || true
        
        # Lancer le conteneur MariaDB
        echo -e "${YELLOW}🚀 Lancement du conteneur MariaDB...${NC}"
        sleep 1
        if ! docker run -d \
            --name mathsmanager-db \
            -e MYSQL_ROOT_PASSWORD=root \
            -e MYSQL_DATABASE=mathsManager \
            -p 3307:3306 \
            mariadb:10.6; then
            echo -e "${RED}❌ Échec du lancement du conteneur Docker${NC}"
            echo -e "${YELLOW}💡 Vérifiez :${NC}"
            echo "• Le port 3307 n'est pas déjà utilisé : netstat -tlnp | grep 3307"
            echo "• Docker fonctionne : docker --version"
            echo "• Permissions Docker : docker ps"
            exit 1
        fi
        
        # Attendre que le conteneur soit prêt
        echo -e "${YELLOW}⏳ Attente du démarrage de la base de données...${NC}"
        
        # Attendre que MariaDB soit réellement prêt
        DB_READY=false
        for i in {1..30}; do
            if docker exec mathsmanager-db mysql -uroot -proot -e "SELECT 1" >/dev/null 2>&1; then
                echo -e "${GREEN}✅ Base de données prête !${NC}"
                DB_READY=true
                break
            fi
            echo "Tentative $i/30..."
            sleep 2
        done
        
        if [ "$DB_READY" = "false" ]; then
            echo -e "${RED}❌ La base de données n'est pas prête après 60 secondes${NC}"
            echo -e "${YELLOW}Vérifiez les logs : docker logs mathsmanager-db${NC}"
            exit 1
        fi
        
    # Mettre à jour le .env avec des expressions plus robustes
    sed "${SED_INPLACE[@]}" 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_PORT=.*/DB_PORT=3307/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_DATABASE=.*/DB_DATABASE=mathsManager/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_USERNAME=.*/DB_USERNAME=root/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_PASSWORD=.*/DB_PASSWORD=root/' .env
    # Forcer l'utilisation du port TCP au lieu du socket Unix (important pour macOS)
    if grep -q "^DB_SOCKET=" .env; then
        sed "${SED_INPLACE[@]}" 's/^DB_SOCKET=.*/DB_SOCKET=/' .env
    else
        echo "DB_SOCKET=" >> .env
    fi
        ;;
        
    2)
        echo -e "${YELLOW}📊 Configuration avec XAMPP...${NC}"
        echo "Assurez-vous que XAMPP est démarré (Apache + MySQL)"
        sleep 1
        
    # Mettre à jour le .env
    sed "${SED_INPLACE[@]}" 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_PORT=.*/DB_PORT=3306/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_DATABASE=.*/DB_DATABASE=mathsManager/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_USERNAME=.*/DB_USERNAME=root/' .env
    sed "${SED_INPLACE[@]}" 's/^DB_PASSWORD=.*/DB_PASSWORD=/' .env
        
        read -p "Appuyez sur Entrée quand XAMPP est prêt..."
        ;;
        
    3)
        echo -e "${YELLOW}🗄️  Configuration MySQL/MariaDB local...${NC}"
        sleep 1
        read -p "Host (127.0.0.1): " db_host
        read -p "Port (3306): " db_port
        read -p "Database (mathsManager): " db_name
        read -p "Username (root): " db_user
        read -s -p "Password: " db_pass
        echo
        
        # Validation des inputs
        if [ -n "$db_port" ] && ! [[ "$db_port" =~ ^[0-9]+$ ]]; then
            echo -e "${RED}❌ Le port doit être un nombre${NC}"
            exit 1
        fi
        
    # Mettre à jour le .env
    sed "${SED_INPLACE[@]}" "s/^DB_HOST=.*/DB_HOST=${db_host:-127.0.0.1}/" .env
    sed "${SED_INPLACE[@]}" "s/^DB_PORT=.*/DB_PORT=${db_port:-3306}/" .env
    sed "${SED_INPLACE[@]}" "s/^DB_DATABASE=.*/DB_DATABASE=${db_name:-mathsManager}/" .env
    sed "${SED_INPLACE[@]}" "s/^DB_USERNAME=.*/DB_USERNAME=${db_user:-root}/" .env
    sed "${SED_INPLACE[@]}" "s/^DB_PASSWORD=.*/DB_PASSWORD=${db_pass}/" .env
        ;;
        
    *)
        echo -e "${RED}❌ Choix invalide${NC}"
        exit 1
        ;;
esac

# 5. Test de la connexion à la base de données
echo ""
echo -e "${YELLOW}🔌 Test de la connexion à la base de données...${NC}"
sleep 2

# Tentatives multiples de connexion via Laravel Artisan qui respecte le DB_SOCKET
for i in {1..5}; do
    # Test de connexion via artisan qui utilise la config Laravel complète
    if php artisan db:show >/dev/null 2>&1; then
        echo -e "${GREEN}✅ Connexion à la base de données réussie${NC}"
        break
    else
        if [ $i -eq 5 ]; then
            echo -e "${RED}❌ Impossible de se connecter à la base de données après 5 tentatives${NC}"
            echo "Vérifiez vos paramètres dans le fichier .env"
            echo ""
            echo "Contenu actuel du .env (section DB):"
            grep "^DB_" .env
            echo ""
            
            # Récupérer les valeurs pour les conseils
            DB_PORT=$(grep "^DB_PORT=" .env | cut -d'=' -f2)
            
            echo -e "${YELLOW}💡 Conseils de dépannage :${NC}"
            if [ "$DB_PORT" = "3307" ]; then
                echo "• Docker est configuré, vérifiez que le conteneur fonctionne : docker ps"
                echo "• Vérifiez les logs du conteneur : docker logs mathsmanager-db"
                echo "• Redémarrez le conteneur : docker restart mathsmanager-db"
                echo "• Testez manuellement : docker exec -it mathsmanager-db mysql -uroot -proot"
            elif [ "$DB_PORT" = "3306" ]; then
                echo "• XAMPP/MySQL local configuré, vérifiez que le service est démarré"
                echo "• Testez la connexion : mysql -h127.0.0.1 -P3306 -uroot -p"
            fi
            exit 1
        fi
        echo "Tentative $i/5 échouée, nouvelle tentative dans 3 secondes..."
        sleep 3
    fi
done

# 6. Exécution des migrations
echo ""
echo -e "${YELLOW}🔄 Exécution des migrations...${NC}"
sleep 1
php artisan migrate

# 7. Création des liens symboliques
echo ""
echo -e "${YELLOW}🔗 Création des liens symboliques...${NC}"
sleep 1
php artisan storage:link

# 8. Compilation des assets
echo ""
echo -e "${YELLOW}🎨 Compilation des assets...${NC}"
sleep 1
npm run build

# 9. Proposer les actions optionnelles
echo ""
echo -e "${GREEN}"
echo "╔══════════════════════════════════════════╗"
echo "║            INSTALLATION TERMINÉE        ║"
echo "╚══════════════════════════════════════════╝"
echo -e "${NC}"
sleep 1

echo -e "${GREEN}🎉 Installation terminée avec succès !${NC}"
echo ""

# Proposer l'import des données si pas encore fait
if [ -f "mathsmanager-sample.sql.gz" ]; then
    echo -e "${YELLOW}📊 Voulez-vous importer les données de démonstration ? (y/N):${NC}"
    read -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "Décompression et import de mathsmanager-sample.sql.gz..."
        
        # Récupérer le port depuis .env (trim whitespace)
        DB_PORT=$(grep "^DB_PORT=" .env | cut -d'=' -f2 | tr -d ' \r\n')
        DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d'=' -f2 | tr -d ' \r\n')
        DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d'=' -f2 | tr -d ' \r\n')
        DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d'=' -f2 | tr -d ' \r\n')
        DB_HOST=$(grep "^DB_HOST=" .env | cut -d'=' -f2 | tr -d ' \r\n')
        
        if [ "$DB_PORT" = "3307" ]; then
            # Docker : utiliser docker exec avec mysql (pas besoin de client mysql local)
            echo -e "${YELLOW}Utilisation de Docker pour l'import...${NC}"
            if gunzip -c "mathsmanager-sample.sql.gz" | docker exec -i mathsmanager-db mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" 2>/dev/null; then
                echo -e "${GREEN}✅ Données de démonstration importées${NC}"
            else
                echo -e "${RED}❌ Erreur lors de l'import des données${NC}"
                echo "Vous pouvez importer manuellement plus tard avec :"
                echo "gunzip -c mathsmanager-sample.sql.gz | docker exec -i mathsmanager-db mysql -uroot -proot mathsManager"
            fi
        else
            # XAMPP/MySQL local : nécessite le client mysql
            if command_exists mysql; then
                echo -e "${YELLOW}Utilisation du client MySQL local...${NC}"
                if gunzip -c "mathsmanager-sample.sql.gz" | mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" 2>/dev/null; then
                    echo -e "${GREEN}✅ Données de démonstration importées${NC}"
                else
                    echo -e "${RED}❌ Erreur lors de l'import des données${NC}"
                fi
            else
                echo -e "${RED}❌ Le client mysql n'est pas installé${NC}"
                echo "Installez-le avec : brew install mysql-client (macOS) ou apt-get install mysql-client (Linux)"
            fi
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

