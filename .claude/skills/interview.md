# Interview Skill - Planification Architecturale Approfondie

## Description
Skill pour mener une interview approfondie avant de valider un plan d'implémentation. Active automatiquement le mode plan et pose des questions ciblées pour comprendre le contexte complet.

## Déclenchement
`/interview` ou `/interview <description de la tâche>`

## Comportement

### 1. Activation Mode Plan
- Active automatiquement EnterPlanMode
- Ne commence JAMAIS à coder avant validation du plan

### 2. Phase de Découverte
Pour chaque demande, investigate :
- **Existant** : Chercher des patterns/services/solutions déjà présents dans le codebase
- **Contexte** : Lire les fichiers pertinents pour comprendre l'architecture actuelle
- **Dépendances** : Identifier les fichiers/classes/services impactés
- **Alternatives** : Proposer 2-3 approches possibles avec trade-offs

### 3. Questions Structurées
Poser des questions ciblées sur :

#### Architecture
- Où placer le code ? (Service/Controller/Helper/Middleware)
- Pattern existant à suivre ?
- Impact sur l'architecture actuelle ?

#### Implémentation
- Approche technique (eager loading, caching, jobs, events) ?
- Gestion des erreurs (Exception, ValidationException, Log) ?
- Besoins en tests ?

#### Intégration
- Impact sur les autres fonctionnalités ?
- Migration de données nécessaire ?
- Besoin de documentation ?

#### Découpage
- La feature peut être découpée en sous-tâches ?
- Ordre d'implémentation optimal ?
- Quick wins identifiés ?

### 4. Reformulation & Validation
Après chaque réponse :
- Reformuler pour confirmer la compréhension
- Faire des analogies avec le code existant
- Montrer un exemple concret si pertinent

### 5. Plan Final
Une fois toutes les questions posées :
- Résumer les décisions architecturales
- Proposer un découpage en features/tickets si nécessaire
- Lister les fichiers à créer/modifier
- Identifier les risques potentiels
- Proposer une stratégie de tests

### 6. Utilisation de AskUserQuestion
- Utiliser AskUserQuestion pour choix techniques importants
- Proposer 2-4 options avec descriptions claires
- Recommander une option quand pertinent

## Contexte Projet
Ce projet est **Maths Manager** :
- Laravel 11.46.2 backend (actuellement Blade, futur API pour React)
- Objectif : Clean code pour migration React + système multi-professeurs
- Principes : DRY, SOLID, Services pour logique métier
- Documentation : `exclude/SPRINT3_CLEAN_CODE_PLAN.md` + `exclude/CLAUDE.md`

## Exemples d'Usage

### Bon Usage
```
/interview diviser le QuizzController en plusieurs contrôleurs
/interview ajouter un système de cache pour les quiz
/interview implémenter une API REST pour le frontend React
```

### Usage Déconseillé
```
/interview corriger un typo
/interview ajouter un commentaire
```

## Notes
- Skill adapté pour features complexes nécessitant réflexion architecturale
- Pas nécessaire pour tâches simples (FormRequests, extractions services simples)
- Toujours consulter SPRINT3_CLEAN_CODE_PLAN.md pour contexte du sprint actuel
