# 🚨 BACKLOG - CLEAN CODE AUDIT

## 🔴 **PRIORITÉ 1 - CRITIQUE**

### 2. **Sécuriser les uploads de fichiers**
**Problème** : Path Traversal et uploads non sécurisés
**Fichiers concernés** :
- `CorrectionRequestController.php` ligne 121-126
- `RegisteredUserController.php` ligne 38-44
- `UserController.php` ligne 124-139

**Actions** :
- [ ] Créer `app/Services/FileUploadService.php`
- [ ] Valider les types MIME réels (pas juste l'extension)
- [ ] Sanitizer les noms de fichiers
- [ ] Vérifier les permissions de dossiers

```php
// Problème actuel
$image->move($destinationPath, $imageName); // Pas de validation
```

## 🟡 **PRIORITÉ 2 - ÉLEVÉE**

### 3. **GOD OBJECTS - Diviser les gros contrôleurs**

#### QuizzController (560 lignes, 20 méthodes)
**Actions** :
- [ ] Créer `QuizGameController` (startQuizz, showQuestion, checkAnswer, showResult)
- [ ] Créer `QuizQuestionController` (CRUD questions)
- [ ] Créer `QuizAnswerController` (CRUD réponses)
- [ ] Créer `QuizSessionService` pour gérer les sessions

#### DSController (465 lignes)
**Actions** :
- [ ] Extraire `DSGenerationService`
- [ ] Extraire `DSTimerService` 
- [ ] Créer `DSAssignmentController`

### 4. **Problèmes de Performance - N+1 Queries**

**Fichier** : `DSController.php` lignes 111-115
```php
// PROBLÈME
foreach ($dsList as $ds) {
    foreach ($ds->exercisesDS as $exerciseDS) {
        $exerciseDS->multipleChapter = MultipleChapter::find($exerciseDS->multiple_chapter_id);
    }
}
```

**Actions** :
- [ ] Utiliser eager loading : `->with(['exercisesDS.multipleChapter'])`
- [ ] Revoir toutes les requêtes dans les boucles
- [ ] Optimiser les collections

### 5. **Failles de Sécurité**

#### Mass Assignment
**Fichier** : `MultipleChapterController.php` ligne 103
```php
$multipleChapter->update($request->all()); // DANGEREUX
```

**Actions** :
- [ ] Définir `$fillable` dans tous les modèles
- [ ] Remplacer par `$request->only(['champ1', 'champ2'])`

#### Autorisations manquantes
**Actions** :
- [ ] Créer des Policies pour chaque modèle
- [ ] Ajouter middleware d'autorisation
- [ ] Vérifier l'ownership des ressources

## 🟢 **PRIORITÉ 3 - MOYENNE**

### 6. **Architecture et Clean Code**

#### Logique métier dans les contrôleurs
**Actions** :
- [ ] Créer `app/Services/TimerService.php` (DSController formatTimer)
- [ ] Extraire la logique de génération de DS
- [ ] Créer des Form Requests pour la validation

#### Code mort et hardcoding
**Actions** :
- [ ] Supprimer le code commenté
- [ ] Externaliser les emails en config
- [ ] Nettoyer les méthodes `changeAnalyse2Color()` etc.

#### Nommage incohérent
**Actions** :
- [ ] `Quizze` → `Quiz` dans toute l'app
- [ ] Standardiser `ds` vs `DS`
- [ ] Revoir les noms de variables

### 7. **Validation et Form Requests**

**Actions** :
- [ ] Créer `app/Http/Requests/StoreExerciseRequest.php`
- [ ] Créer `app/Http/Requests/StoreDSRequest.php`
- [ ] Centraliser les règles de validation dupliquées

## 📊 **MÉTRIQUES ACTUELLES**
- **560 lignes** dans QuizzController
- **240+ lignes** de code LaTeX dupliqué
- **26 contrôleurs** au total
- **~3500 lignes** de contrôleurs au total

## 🎯 **OBJECTIFS POST-CLEANUP**
- [ ] Aucun contrôleur > 200 lignes
- [ ] 0% de duplication de code critique
- [ ] Couverture de sécurité à 100%
- [ ] Performance optimisée (pas de N+1)

---
**Dernière mise à jour** : 2025-09-02
**Status** : En attente après factorisation convertCustomLatexToHtml()
