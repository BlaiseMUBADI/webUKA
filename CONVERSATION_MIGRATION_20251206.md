# Conversation GitHub Copilot - Migration Base de Données
**Date :** 6 décembre 2025  
**Sujet :** Migration de structure de bdd_uka vers bdd_uka_original

---

## 🎯 Objectif Principal
Mettre à jour la structure de `bdd_uka_original` pour intégrer les nouvelles fonctionnalités de `bdd_uka` (tables et procédures stockées pour la gestion des jurys de délibération) **SANS perdre les données existantes**.

---

## 📊 Analyse des Bases de Données

### Dumps Analysés
- **Source :** `dump-bdd_uka-202512061049.sql` (51 tables, 22 procédures)
- **Destination :** `dump-bdd_uka_original-202512061049.sql` (51 tables, 21 procédures)

### Différences Détectées

#### Tables (51 identiques dans les deux bases)
✅ Toutes les tables existent déjà, y compris :
- `t_jury_deliberation` - Gestion des jurys de délibération
- `t_membre_jury` - Membres des jurys avec système d'authentification

#### Procédures Stockées

**Procédures manquantes dans `bdd_uka_original` :**
1. ✅ `Ajouter_Membre_Jury` - Ajout de membres au jury avec validation
2. ✅ `Ajout_Nouvel_Jury` - Création d'un nouveau jury

**Procédure manquante dans `bdd_uka` :**
- `Liste_Agent_Aligner` (présente uniquement dans original)

---

## 🔐 Système d'Authentification Dual

### Architecture Découverte (analyse de `index.php`)

Un agent peut avoir **deux types de comptes différents** :

#### 1. Compte Administratif (`compte_agent`)
```php
// Table: compte_agent
// Login: login_agent
// Password: password_agent (SHA1 legacy ou bcrypt)
// Catégories: Guichetier, Admin, Doyen, Academique, etc.
```

#### 2. Compte Jury (`t_membre_jury`)
```php
// Table: t_membre_jury
// Login: login_jury (différent du login_agent)
// Password: password_jury (bcrypt uniquement)
// Rôles: Président, Secrétaire, Membre
```

### Règles de Gestion

**✅ Autorisé :**
- Agent matricule `001` → compte_agent (login: `admin001`) + jury A (login: `jury001a`)
- Agent matricule `001` → jury A (login: `jury001a`) + jury B (login: `jury001b`)

**❌ Interdit :**
- Agent matricule `001` → jury A (login: `jury001a`) + jury A (login: `jury001b2`)
- **Un agent = un seul compte par jury** (validation par `Mat_agent`)

### Code de Vérification (`index.php` lignes 100-400)

```php
// BLOC 1: Gestion du choix de compte
if(isset($_POST['choix_type_compte'])) {
    $type_compte = $_POST['choix_type_compte'];
    
    if($type_compte == 'agent') {
        // Connexion avec compte_agent
    } else if($type_compte == 'jury') {
        // Connexion avec t_membre_jury
    }
}

// BLOC 2: Authentification duale
$sql_agent = "SELECT ... FROM compte_agent WHERE Login = ?";
$sql_jury = "SELECT ... FROM t_membre_jury WHERE Login = ? AND Statut = 'Actif'";

// Validation: SHA1 (legacy) OU bcrypt (moderne) pour compte_agent
$password_valid = (sha1($motdepasse) === $ligne['password_agent']) || 
                 (password_verify($motdepasse, $ligne['password_agent']));

// Validation: UNIQUEMENT bcrypt pour t_membre_jury
if(password_verify($motdepasse, $ligne['password_jury'])) { ... }
```

---

## 🛠️ Solution Mise en Place

### Fichier Créé : `MIGRATION_STRUCTURE_20251206.sql`

**Contenu :**
1. ✅ Analyse automatique des différences
2. ✅ Ajout de 2 procédures stockées
3. ✅ Commentaires détaillés sur la logique métier
4. ✅ Validation par matricule (anti-doublon)
5. ✅ Aucune modification de données

### Procédure 1 : `Ajouter_Membre_Jury`

**Paramètres :**
```sql
IN  p_id_jury       INT
IN  p_mat_agent     VARCHAR(50)
IN  p_role          ENUM('Président','Secrétaire','Membre')
IN  p_login         VARCHAR(50)
IN  p_mot_passe     VARCHAR(255)
IN  p_statut        ENUM('Actif', 'Inactif')
OUT p_success       BOOLEAN
OUT p_message       VARCHAR(255)
OUT p_id_membre     INT
```

**Logique de validation :**
1. ✅ Vérification données obligatoires (id_jury, mat_agent, role)
2. ✅ Anti-doublon : un Mat_agent par jury uniquement
3. ✅ Login/Password obligatoires pour Président et Secrétaire
4. ✅ Vérification unicité du login globale
5. ✅ Insertion avec ou sans credentials selon le rôle

### Procédure 2 : `Ajout_Nouvel_Jury`

**Paramètres :**
```sql
IN p_Libelle_jury       TEXT
IN p_Date_deliberation  DATE
IN p_Code_Promotion     VARCHAR(10)
IN p_idAnnee_Acad       INT
```

**Action :**
Insertion simple dans `t_jury_deliberation` avec clés étrangères vers :
- `annee_academique` (idAnnee_Acad)
- `promotion` (Code_Promotion)

---

## 📝 Instructions d'Exécution

### Via phpMyAdmin (Recommandé)

```bash
1. Ouvrir phpMyAdmin
2. Se connecter avec l'utilisateur 'root' (pas 'blaise')
3. Sélectionner la base 'bdd_uka_original'
4. Onglet SQL
5. Copier-coller le contenu de MIGRATION_STRUCTURE_20251206.sql
6. Cliquer sur "Exécuter"
```

### Via MySQL CLI

```powershell
# PowerShell
cd C:\wamp64\bin\mysql\mysql8.2.0\bin
.\mysql.exe -u root -p bdd_uka_original < "C:\wamp64\www\webUKA\MIGRATION_STRUCTURE_20251206.sql"
```

---

## ⚠️ Erreurs Rencontrées et Solutions

### Erreur #1109 - Table inconnue dans information_schema
**Cause :** Les tables n'étaient pas encore créées lors des vérifications  
**Solution :** Séparation création tables + ajout contraintes

### Erreur #1044 - Accès refusé pour 'blaise'
**Cause :** Utilisateur 'blaise' sans privilèges sur information_schema  
**Solution :** Utiliser 'root' OU supprimer les requêtes information_schema

---

## 🔍 Vérifications Post-Migration

### 1. Vérifier les procédures créées

```sql
SELECT ROUTINE_NAME, ROUTINE_TYPE, CREATED
FROM information_schema.ROUTINES 
WHERE ROUTINE_SCHEMA = 'bdd_uka_original' 
AND ROUTINE_TYPE = 'PROCEDURE'
ORDER BY ROUTINE_NAME;
```

**Résultat attendu :** 23 procédures (21 + 2 nouvelles)

### 2. Tester Ajout_Nouvel_Jury

```sql
CALL Ajout_Nouvel_Jury(
    'Jury de Délibération L1 Informatique',
    '2025-12-15',
    'L1INFO',
    1
);
```

### 3. Tester Ajouter_Membre_Jury

```sql
SET @success = FALSE;
SET @message = '';
SET @id_membre = NULL;

CALL Ajouter_Membre_Jury(
    1,                    -- ID_jury
    'AG001',             -- Mat_agent
    'Président',         -- role
    'president_jury1',   -- login
    '$2y$10$...',        -- mot_passe (bcrypt)
    'Actif',             -- statut
    @success,
    @message,
    @id_membre
);

SELECT @success, @message, @id_membre;
```

### 4. Vérifier l'intégration avec index.php

```php
// Test de connexion avec compte jury
// Login: president_jury1
// Password: (le mot de passe en clair utilisé pour bcrypt)

// Vérifier la redirection selon le rôle :
// - Président → D_Faculte/Principale_fac.php?page=gestion_deliberation
// - Secrétaire → D_Faculte/Principale_fac.php?page=gestion_encodage
// - Membre → D_Faculte/Principale_fac.php?page=consultation_jury
```

---

## 📂 Fichiers Créés/Modifiés

### Fichiers de Migration
- ✅ `MIGRATION_STRUCTURE_20251206.sql` - Script principal
- ✅ `MIGRATION_COMPLETE_VERS_ORIGINAL.sql` - Version précédente (5 décembre)

### Dumps SQL
- 📄 `dump-bdd_uka-202512061049.sql` - Base source (51 tables, 22 procs)
- 📄 `dump-bdd_uka_original-202512061049.sql` - Base destination (51 tables, 21 procs)

### Documentation
- 📄 `GUIDE_MIGRATION_STRUCTURE.md` - Guide complet
- 📄 `GUIDE_UTILISATION_MIGRATION.md` - Instructions utilisateur
- 📄 `README_MIGRATION.md` - README principal
- 📄 `RESOLUTION_ERREUR_1109.md` - Résolution erreur table inconnue
- 📄 `RESOLUTION_ERREUR_1044_PERMISSIONS.md` - Résolution erreur permissions

### Scripts Automation
- 📄 `Execute-Migration.ps1` - PowerShell avec backup
- 📄 `Verify-Differences.ps1` - Comparaison bases
- 📄 `LANCER_MIGRATION.bat` - Batch pour user 'blaise'
- 📄 `LANCER_MIGRATION_ROOT.bat` - Batch pour user 'root'

---

## 🎓 Leçons Apprises

### 1. Architecture d'Authentification
- Un agent peut avoir plusieurs comptes (admin + jury)
- Chaque compte a son propre login/password
- Le matricule (`Mat_agent`) est l'identifiant unique

### 2. Gestion des Migrations
- Toujours séparer structure et données
- Utiliser `ALTER TABLE` au lieu de `DROP/CREATE`
- Désactiver `FOREIGN_KEY_CHECKS` pendant migration
- Vérifier les permissions utilisateur avant DDL

### 3. Bonnes Pratiques MySQL
- `information_schema` requiert privilèges spéciaux
- Préférer requêtes directes pour utilisateurs limités
- Utiliser `DELIMITER` correctement pour procédures
- Tester avec `START TRANSACTION` / `ROLLBACK`

### 4. Sécurité Mots de Passe
- Migration progressive SHA1 → bcrypt
- `password_verify()` pour validation bcrypt
- `sha1()` maintenu pour compatibilité legacy
- Tous nouveaux comptes jury en bcrypt uniquement

---

## 🔄 Prochaines Étapes

### Court Terme
1. ✅ Exécuter la migration sur `bdd_uka_original`
2. ⏳ Tester les procédures avec données réelles
3. ⏳ Vérifier l'interface web (formulaires jury)
4. ⏳ Mettre à jour la documentation utilisateur

### Moyen Terme
1. Migrer tous les mots de passe SHA1 vers bcrypt
2. Ajouter logs d'audit pour actions jury
3. Créer interface de gestion des jurys
4. Implémenter notifications email

### Long Terme
1. Refactoriser système d'authentification unifié
2. Implémenter 2FA pour comptes sensibles
3. Créer API REST pour gestion jurys
4. Ajouter tests automatisés

---

## 📞 Support

**Problèmes connus :**
- Erreur #1109 → Utiliser script simplifié sans information_schema
- Erreur #1044 → Connecter avec 'root' au lieu de 'blaise'
- Doublon matricule → Vérifier Mat_agent avant insertion

**Contacts :**
- DBA : Blaise MUBADI
- Repository : webUKA (GitHub)
- Branch : master

---

## 📊 Statistiques de Migration

- **Tables analysées :** 51
- **Procédures comparées :** 22 vs 21
- **Nouveaux objets :** 2 procédures
- **Lignes de code SQL :** ~300
- **Durée d'exécution :** < 1 seconde
- **Impact sur données :** 0 (structure uniquement)

---

**Fin de la conversation - 6 décembre 2025**
