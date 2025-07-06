#!/bin/bash

# Script pour importer la base de données
# Usage: ./scripts/import-db.sh fichier.sql

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vérifier qu'un fichier est fourni
if [ $# -eq 0 ]; then
    echo -e "${RED}Erreur: Veuillez spécifier un fichier SQL${NC}"
    echo "Usage: ./scripts/import-db.sh fichier.sql"
    exit 1
fi

FILENAME=$1

# Vérifier que le fichier existe
if [ ! -f "$FILENAME" ]; then
    echo -e "${RED}Erreur: Le fichier $FILENAME n'existe pas${NC}"
    exit 1
fi

# Charger les variables d'environnement
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
else
    echo -e "${RED}Erreur: Fichier .env non trouvé${NC}"
    exit 1
fi

echo -e "${YELLOW}Import de la base de données...${NC}"
echo "Base de données: $DB_DATABASE"
echo "Host: $DB_HOST:$DB_PORT"
echo "Fichier source: $FILENAME"
echo "Taille: $(du -h "$FILENAME" | cut -f1)"

# Demander confirmation
echo -e "${YELLOW}⚠️  ATTENTION: Cette opération va écraser les données existantes !${NC}"
read -p "Êtes-vous sûr de vouloir continuer ? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Annulé."
    exit 0
fi

# Vérifier si Docker est utilisé (port 3307)
if [ "$DB_PORT" = "3307" ]; then
    echo -e "${YELLOW}Utilisation de Docker détectée${NC}"
    
    # Vérifier que le conteneur fonctionne
    if ! docker ps | grep -q mathsmanager-db; then
        echo -e "${RED}Erreur: Le conteneur mathsmanager-db n'est pas en cours d'exécution${NC}"
        echo "Lancez-le avec: docker start mathsmanager-db"
        exit 1
    fi
    
    # Import via Docker
    docker exec -i mathsmanager-db mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < "$FILENAME"
else
    # Import direct
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < "$FILENAME"
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Import réussi !${NC}"
    echo "Base de données restaurée depuis: $FILENAME"
else
    echo -e "${RED}❌ Erreur lors de l'import${NC}"
    exit 1
fi

