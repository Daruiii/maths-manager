#!/bin/bash

# Script pour exporter la base de données
# Usage: ./scripts/export-db.sh [nom-du-fichier]

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Charger les variables d'environnement
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
else
    echo -e "${RED}Erreur: Fichier .env non trouvé${NC}"
    exit 1
fi

# Nom du fichier de sortie
FILENAME=${1:-"mathsmanager-$(date +%Y%m%d-%H%M%S).sql"}

echo -e "${YELLOW}Export de la base de données...${NC}"
echo "Base de données: $DB_DATABASE"
echo "Host: $DB_HOST:$DB_PORT"
echo "Fichier de sortie: $FILENAME"

# Vérifier si Docker est utilisé (port 3307)
if [ "$DB_PORT" = "3307" ]; then
    echo -e "${YELLOW}Utilisation de Docker détectée${NC}"
    
    # Vérifier que le conteneur fonctionne
    if ! docker ps | grep -q mathsmanager-db; then
        echo -e "${RED}Erreur: Le conteneur mathsmanager-db n'est pas en cours d'exécution${NC}"
        echo "Lancez-le avec: docker start mathsmanager-db"
        exit 1
    fi
    
    # Export via Docker
    docker exec mathsmanager-db mysqldump -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$FILENAME"
else
    # Export direct
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$FILENAME"
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Export réussi !${NC}"
    echo "Fichier créé: $FILENAME"
    echo "Taille: $(du -h "$FILENAME" | cut -f1)"
else
    echo -e "${RED}❌ Erreur lors de l'export${NC}"
    exit 1
fi

