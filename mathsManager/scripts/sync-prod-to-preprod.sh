#!/bin/bash

# Script pour synchroniser la base de données de production vers preprod
# Usage: ./scripts/sync-prod-to-preprod.sh

set -e

# Couleurs pour les messages
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🔄 Synchronisation BDD Prod → Preprod${NC}"
echo -e "${YELLOW}⚠️  Attention: Cette opération va écraser toutes les données de la preprod !${NC}"
echo ""

# Demander confirmation
read -p "Êtes-vous sûr de vouloir continuer ? (oui/non): " confirmation
if [ "$confirmation" != "oui" ]; then
    echo -e "${RED}❌ Synchronisation annulée${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}📦 Export de la base de données de production...${NC}"

# Variables de configuration (à adapter selon ton environnement)
PROD_DB="dm2blc_mathsManager"
PREPROD_DB="dm2blc_mathsManager_preprod"
BACKUP_FILE="/tmp/mathsmanager-prod-sync-$(date +%Y%m%d_%H%M%S).sql"

# Export de la BDD de production
echo "Exportation de la base $PROD_DB..."
mysqldump -h dm2blc.myd.infomaniak.com -u dm2blc_david -p $PROD_DB > $BACKUP_FILE

if [ ! -f "$BACKUP_FILE" ]; then
    echo -e "${RED}❌ Erreur lors de l'export${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Export terminé: $BACKUP_FILE${NC}"
echo ""

echo -e "${GREEN}📥 Import vers la preprod...${NC}"

# Note: On ne peut pas DROP/CREATE sur Infomaniak, on vide juste les tables
echo "Import des données dans $PREPROD_DB..."
mysql -h dm2blc.myd.infomaniak.com -u dm2blc_david -p $PREPROD_DB < $BACKUP_FILE

echo -e "${GREEN}✅ Import terminé${NC}"
echo ""

echo -e "${GREEN}🧹 Nettoyage...${NC}"
rm $BACKUP_FILE

echo ""
echo -e "${GREEN}🎉 Synchronisation terminée avec succès !${NC}"
echo -e "${YELLOW}💡 La preprod contient maintenant les données de production${NC}"
