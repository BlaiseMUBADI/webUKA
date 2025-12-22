# 🎓 Système de Compensation des Notes - Guide d'Implémentation

## 📋 Vue d'Ensemble

Le système de compensation permet aux jurys de compenser automatiquement les notes entre 8 et 9.99/20 en utilisant les points excédentaires (> 10/20) d'autres ECs de la même UE.

---

## 🎯 Fonctionnalités Implémentées

### 1. **Menu Contextuel sur les Côtes < 10**
- **Déclenchement**: Clic droit sur une cellule de note
- **Condition**: La note doit être < 10/20 pour afficher le menu
- **Action**: Propose l'option "Compenser cette note"

### 2. **Recherche Automatique des ECs Compensables**
L'API recherche les ECs qui peuvent compenser selon ces critères:
- ✅ Même UE que l'EC à compenser
- ✅ **MÊME NOMBRE DE CRÉDITS** (règle UKA)
- ✅ Note > 10/20
- ✅ Points disponibles pour céder
- ✅ Même semestre

### 3. **Modal de Sélection**
Affiche une liste interactive des ECs compensables avec:
- 📊 Note actuelle de l'EC cédant
- ➕ Surplus de points disponibles
- 🔄 Points qui seront transférés
- 📉 Note après cession

### 4. **Application de la Compensation**
- Met à jour `cote_compensee` pour l'EC bénéficiaire
- Met à jour `cote_cedee` pour l'EC cédant
- Enregistre la référence dans `Ligne_touchee_Matricule_id_ec_aligne`
- Transaction sécurisée (rollback en cas d'erreur)

---

## 📁 Fichiers Créés/Modifiés

### Nouveaux Fichiers API PHP

#### 1. `API_PHP/Recup_EC_Compensables.php`
```php
Entrée:
{
  "matricule": "MAT001",
  "ec_beneficiaire": 42,
  "id_semestre": 1
}

Sortie:
{
  "success": true,
  "ue": {
    "code": "UE01",
    "intitule": "Mathématiques I",
    "categorie": "Fondamentale"
  },
  "ec_beneficiaire": {
    "cote_actuelle": 8.5,
    "deficit": 1.5
  },
  "ecs_compensables": [
    {
      "id_ec_aligne": 43,
      "intitule": "Algèbre",
      "credit": 6,
      "cote_actuelle": 14,
      "surplus": 4,
      "points_disponibles": 1.5,
      "cote_apres_cession": 12.5
    }
  ],
  "count": 1
}
```

#### 2. `API_PHP/Compenser_Cote.php`
```php
Entrée:
{
  "matricule": "MAT001",
  "ec_beneficiaire": 42,
  "ec_cedant": 43
}

Sortie:
{
  "success": true,
  "message": "Compensation effectuée avec succès",
  "beneficiaire": {
    "id_ec_aligne": 42,
    "cote_avant": 8.5,
    "cote_apres": 10,
    "points_recus": 1.5
  },
  "cedant": {
    "id_ec_aligne": 43,
    "cote_avant": 14,
    "cote_apres": 12.5,
    "points_cedes": 1.5
  }
}
```

### Modifications JavaScript (`Manip_Deliberation.js`)

#### Variables Ajoutées
```javascript
let selectedCoteMatricule = null;
let selectedCoteEcAligne = null;
let selectedCoteValeur = null;
let selectedCoteCell = null;
```

#### Fonctions Ajoutées
1. **`initializeContextMenuCote()`** - Initialise le menu contextuel pour les côtes
2. **`proposerCompensation()`** - Lance la recherche des ECs compensables
3. **`afficherModalCompensation(data)`** - Affiche le modal avec les options
4. **`appliquerCompensation(ec_cedant_id)`** - Applique la compensation
5. **`fermerModalCompensation()`** - Ferme le modal

### Modifications HTML (`Entree_Par_Deliberation.php`)

#### Menu Contextuel Côte
```html
<div id="contextMenuCote">
  <!-- Menu moderne avec option de compensation -->
</div>
```

#### Modal Compensation
```html
<dialog id="modal_Compensation">
  <!-- Modal avec liste interactive des ECs -->
</dialog>
```

---

## 🎮 Utilisation

### Étape 1: Identifier une note à compenser
1. Ouvrir la page de délibération
2. Sélectionner un semestre
3. Repérer une note < 10 dans le tableau

### Étape 2: Ouvrir le menu contextuel
1. **Clic droit** sur la cellule de note
2. Le menu s'affiche avec la note actuelle
3. Vérifier que la note est entre 8 et 9.99

### Étape 3: Proposer la compensation
1. Cliquer sur "Compenser cette note"
2. Le système recherche les ECs compensables
3. Si aucun EC disponible, un message explicatif s'affiche

### Étape 4: Sélectionner l'EC cédant
1. Le modal affiche tous les ECs disponibles
2. Chaque carte montre:
   - 📚 Nom de l'EC
   - 🎯 Note actuelle
   - ➕ Points disponibles
   - 📉 Note après cession
3. **Cliquer** sur une carte pour appliquer la compensation

### Étape 5: Confirmation
1. Une confirmation est demandée
2. La compensation est appliquée
3. Le tableau se recharge automatiquement
4. Les nouvelles valeurs apparaissent:
   - `cote_compensee` pour l'EC bénéficiaire
   - `cote_cedee` pour l'EC cédant

---

## 🔍 Logique de Calcul

### Exemple Concret

**Situation initiale:**
- EC1 (Algèbre): 8.5/20, **6 crédits** ❌ (Échec)
- EC2 (Analyse): 14/20, **6 crédits** ✅ (Surplus de 4 points)
- EC3 (Géométrie): 16/20, **4 crédits** ✅ (Ne peut PAS compenser EC1, crédits différents)
- Les ECs sont dans la même UE "Mathématiques I"

**Calculs:**
```
Déficit EC1 = 10 - 8.5 = 1.5 points
Surplus EC2 = 14 - 10 = 4 points (6 crédits = OK ✅)
Surplus EC3 = 16 - 10 = 6 points (4 crédits ≠ 6 = NON ❌)
Points transférables = min(1.5, 4) = 1.5 points
```

**Résultat après compensation:**
- EC1: `cote_compensee` = 8.5 + 1.5 = **10/20** ✅
- EC2: `cote_cedee` = 14 - 1.5 = **12.5/20** ✅
- EC1: `Ligne_touchee_Matricule_id_ec_aligne` = "MAT001_43"

---

## ✅ Règles de Validation

### Pour l'EC Bénéficiaire:
- ✅ Note entre **8.00 et 9.99**/20
- ❌ Note < 8/20 (trop faible)
- ❌ Note >= 10/20 (déjà validé)

### Pour l'EC Cédant:
- ✅ Note > **10/20**
- ✅ Dans la **même UE**
- ✅ **MÊME NOMBRE DE CRÉDITS** (règle UKA)
- ✅ **Même semestre**
- ✅ A encore des points à céder (cote_cedee > 10)

### Conditions Générales:
- ✅ **ECs avec le même nombre de crédits** (règle UKA)
- ✅ Moyenne de l'UE >= 10/20
- ✅ Transaction atomique (tout ou rien)
- ✅ Traçabilité complète

---

## 🎨 Interface Utilisateur

### Menu Contextuel
```
┌─────────────────────────────────┐
│ 🧮 Note: 8.5/20 (< 10)         │
├─────────────────────────────────┤
│ 🔄 Compenser cette note         │
│    Utiliser un autre EC de l'UE │
├─────────────────────────────────┤
│ ℹ️  Rèême UE et même crédit     │
│    • Note entre 8 et 9.99       │
│    • Moyenne UE ≥ 10/20         │
│    • EC cédant > 10/20          │
└─────────────────────────────────┘
```

### Modal de Compensation
```
┌────────────────────────────────────────────┐
│ 🔄 Compensation de Note                   │
├────────────────────────────────────────────┤
│                                            │
│ 📚 Unité d'Enseignement                   │
│ ┌────────────────────────────────────────┐│
│ │ Mathématiques I           [UE01]       ││
│ │ 🏷️  Catégorie: Fondamentale            ││
│ └────────────────────────────────────────┘│
│                                            │
│ ⬆️  Note à Compenser                       │
│ ┌────────────────────────────────────────┐│
│ │ Note actuelle: 8.5/20                  ││
│ │ Déficit: -1.5 pts                      ││
│ └────────────────────────────────────────┘│
│                                            │
│ ✅ ECs Disponibles pour Compenser         │
│ ┌────────────────────────────────────────┐│
│ │ 📖 Algèbre           [14/20] (+4)      ││
│ │ 🔄 Points disponibles: 1.5             ││
│ │ 📉 Note après cession: 12.5/20         ││
│ └────────────────────────────────────────┘│
│                                            │
│ 💡 Cliquez sur un EC pour compenser       │
│                                            │
│ [ ❌ Annuler ]                             │
└────────────────────────────────────────────┘
```

---

## 🔐 Sécurité & Traçabilité

### Base de Données
```sql
-- Table evaluer (structure existante)
cote_compensee         -- Note finale après compensation
cote_cedee            -- Note après avoir cédé des points
Ligne_touchee_...     -- Référence de l'EC qui a donné les points
```

### Exemple de Traçabilité
```sql
-- EC Bénéficiaire (Matricule: MAT001, EC: 42)
Cote: 8.5
cote_compensee: 10.0
Ligne_touchee_Matricule_id_ec_aligne: 'MAT001_43'

-- EC Cédant (Matricule: MAT001, EC: 43)
Cote: 14.0
cote_cedee: 12.5
```

### Transaction Sécurisée
```php
$con->beginTransaction();
try {
    // Update EC bénéficiaire
    // Update EC cédant
    $con->commit();
} catch (Exception $e) {
    $con->rollBack();
    throw $e;
}
```

---

## 📊 Cas d'Usage

### Cas 1: Compensation Simple
```
EC1: 9.0/20 → 10.0/20 (reçoit 1.0 pt)
EC2: 13.0/20 → 12.0/20 (cède 1.0 pt)
```

### Cas 2: Compensation Partielle
```
EC1: 8.0/20 → 9.5/20 (reçoit 1.5 pt, mais reste < 10)
EC2: 11.5/20 → 10.0/20 (cède tout son surplus)
```ECs avec Crédits Différents (Règle UKA)
```
EC1: 9.5/20, 6 crédits ❌ (besoin 0.5 pt)
EC2: 8.5/20, 4 crédits ❌ (besoin 1.5 pt)
EC3: 15.0/20, 6 crédits ✅ (peut compenser EC1 seulement)
EC4: 13.0/20, 4 crédits ✅ (peut compenser EC2 seulement)

Résultat:
EC1: 9.5 → 10.0 (reçoit 0.5 de EC3, même crédit ✅)
EC2: 8.5 → 10.0 (reçoit 1.5 de EC4, même crédit ✅5 de EC4)
EC3: 13.0/20 → 12.5/20 (cède 0.5)
EC4: 15.0/20 → 13.5/20 (cède 1.5)
```

---

## 🐛 Gestion des Erreurs

### Messages d'Erreur Possibles

| Erreur | Message | Solution |(vérifier crédits et UE)" | Vérifier crédits et notes de l'UE |
| Crédits différents | "L'EC doit avoir le même nombre de crédits" | Chercher EC avec même crédit
|--------|---------|----------|
| Note >= 10 | "Cette note est déjà >= 10, pas besoin de compensation" | Sélectionner une note < 10 |
| Note < 8 | "Note trop faible (< 8/20) pour être compensée" | Rattrapage nécessaire |
| Aucun EC | "Aucun EC disponible pour compenser" | Vérifier les notes de l'UE |
| Paramètres manquants | "Paramètres manquants" | Vérifier la requête |
| Erreur BD | "Erreur base de données: ..." | Contacter l'administrateur |

---

## 🚀 Prochaines Évolutions Possibles

1. **Compensation Automatique**: Appliquer automatiquement les compensations optimales
2. **Historique**: Afficher l'historique des compensations d'un étudiant
3. **Annulation**: Permettre d'annuler une compensation
4. **Statistiques**: Dashboard des compensations par UE/promotion
5. **Export**: Générer un rapport PDF des compensations appliquées
6. **Compensation Inter-UE**: Compensation entre UEs du même semestre
7. **Simulation**: Mode simulation avant application réelle
8. **Validation Jury**: Nécessiter une validation du jury avant application

---Règle des Crédits**: **OBLIGATOIRE** - Les ECs doivent avoir le même nombre de crédits (règle UKA)
3. **Irréversibilité**: Une fois appliquée, la compensation nécessite une intervention manuelle pour être annulée
4. **Règlement**: Cette implémentation suit strictement les règles académiques UKA
5# 📝 Notes Importantes

1. **Ordre de Priorité**: La note finale utilisée est `COALESCE(cote_compensee, Cote_rattrapage, Cote)`
2. **Irréversibilité**: Une fois appliquée, la compensation nécessite une intervention manuelle pour être annulée
3. **Règlement**: Cette implémentation suit strictement les règles académiques UKA
4. **Performance**: Les requêtes sont optimisées avec des indexes sur les clés étrangères

---

## ✅ Checklist de Test

- [ ] Menu contextuel s'affiche sur clic droit
- [ ] Menu ne s'affiche que pour notes < 10
- [ ] Recherche correcte des ECs compensables
- [ ] Modal s'affiche avec les bonnes données
- [ ] Calculs corrects des points transférables
- [ ] Transaction BD réussit
- [ ] Rollback en cas d'erreur
- [ ] Tableau se recharge après compensation
- [ ] Nouvelles valeurs visibles dans evaluer
- [ ] Traçabilité complète enregistrée

---

**Développé selon les règlements académiques UKA - Décembre 2025**
