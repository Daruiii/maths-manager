---
description: Planification architecturale approfondie pour features complexes
---

# Workflow `/interview` - Planification Architecturale

## Objectif
Poser des questions ciblées pour comprendre en profondeur une feature complexe avant de créer un plan d'implémentation détaillé.

## Quand utiliser
- Features complexes nécessitant des décisions architecturales
- Refactoring de contrôleurs volumineux (ex: diviser QuizzController)
- Migration vers React / Système multi-professeurs
- Choix entre plusieurs approches techniques

## Quand NE PAS utiliser
- Tâches simples (FormRequests, typos, ajout commentaires)
- Extractions de services bien définies
- Corrections de bugs évidents

## Étapes du workflow

### 1. Activer le mode PLANNING
- Utiliser `task_boundary` avec `Mode: PLANNING`
- Créer `implementation_plan.md` pour documenter les décisions

### 2. Poser des questions ciblées

**Questions sur l'architecture :**
- Quelle est la responsabilité principale de chaque composant ?
- Comment les composants communiquent entre eux ?
- Y a-t-il des dépendances circulaires à éviter ?

**Questions sur l'implémentation :**
- Quels sont les cas d'usage principaux ?
- Quelles sont les contraintes techniques (performance, compatibilité) ?
- Y a-t-il des patterns existants dans le codebase à suivre ?

**Questions sur l'intégration :**
- Comment cette feature s'intègre avec l'existant ?
- Quels fichiers/composants seront impactés ?
- Y a-t-il des migrations de données nécessaires ?

### 3. Rechercher des patterns existants
- Utiliser `grep_search` pour trouver des implémentations similaires
- Analyser les contrôleurs/services existants
- Identifier les conventions du projet

### 4. Proposer 2-3 alternatives avec trade-offs

**Pour chaque alternative, documenter :**
- ✅ Avantages
- ❌ Inconvénients
- 📊 Impact (lignes de code, complexité, maintenabilité)
- 🔧 Effort d'implémentation

### 5. Reformuler pour valider la compréhension
- Résumer les décisions prises
- Confirmer avec l'utilisateur avant de procéder

### 6. Produire un plan détaillé
- Créer `implementation_plan.md` avec :
  - Contexte et objectif
  - Décisions architecturales
  - Découpage en sous-tâches
  - Plan de vérification/tests

### 7. Demander validation via `notify_user`
- `BlockedOnUser: true`
- `PathsToReview: [implementation_plan.md]`
- Attendre feedback avant de passer en EXECUTION

## Exemple d'utilisation

```
User: /interview diviser le QuizzController en plusieurs contrôleurs
```

**Réponse attendue :**
1. Questions sur la séparation des responsabilités
2. Analyse du QuizzController actuel
3. Proposition de 2-3 découpages possibles
4. Plan détaillé avec routes, contrôleurs, services
5. Validation utilisateur avant implémentation

## Notes importantes
- Ne pas se précipiter en EXECUTION
- Prendre le temps de bien comprendre le contexte
- Documenter toutes les décisions dans `implementation_plan.md`
- Valider avec l'utilisateur avant de coder
