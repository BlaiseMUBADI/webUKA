# 📋 DOCUMENTATION - CORRECTION ALIGNEMENT DES ECs

## 🎯 Problème Identifié

**Situation actuelle (BUG):**
- Un EC pouvait être aligné plusieurs fois dans une même promotion sur différents semestres
- Exemple: EC "Algorithmique" aligné en L1-S1, puis à nouveau en L1-S2 pour la même promotion
- Les checkboxes restaient actives lors du changement de semestre
- Aucune indication visuelle pour les ECs déjà alignés dans un autre semestre

**Règle métier:**
> **Un EC ne peut être aligné qu'UNE SEULE FOIS par promotion, peu importe le semestre**

---

## ✅ Solution Implémentée

### 1. **Nouvelle Procédure Stockée: `Liste_EC_Aligne_V2`**

**Fichier:** `Structure_Procedure_Liste_EC_Aligne_V2.sql`

**Modifications apportées:**

#### Nouvelle jointure ajoutée:
```sql
LEFT JOIN 
    element_constitutifs_aligne eca_promotion ON ec.id_ec = eca_promotion.id_ec 
    AND eca_promotion.Code_Promotion = p_Code_Promotion
    AND eca_promotion.idAnnee_Acad = p_idAnnee_Acad
```

#### Nouvelles colonnes retournées:
- `etat_ec_pris_dans_promotion` (BOOLEAN): Indique si l'EC est déjà aligné dans cette promotion
- `semestre_alignement_promotion` (INT): Le semestre où l'EC est aligné
- `nom_semestre_alignement_promotion` (VARCHAR): Nom du semestre (ex: "Semestre 1")
- `mat_agent_alignement_promotion` (VARCHAR): Matricule de l'agent qui a aligné l'EC
- `nom_agent_alignement_promotion` (VARCHAR): Nom complet de l'agent

---

### 2. **Nouveau Fichier PHP: `Liste_EC_Aligne_V2.php`**

**Emplacement:** `UKA_Numerique\D_Faculte copy 3\API_PHP\Liste_EC_Aligne_V2.php`

**Changement:**
- Appelle la nouvelle procédure `Liste_EC_Aligne_V2`
- Retourne les nouvelles colonnes au client JavaScript

---

### 3. **Nouveau Fichier JavaScript: `Manip_EC_Aligner_V2.js`**

**Emplacement:** `UKA_Numerique\D_Faculte copy 3\JavaScript\Manip_EC_Aligner_V2.js`

#### Modifications principales:

##### A. Détection des ECs déjà alignés dans un autre semestre
```javascript
const estDejaAligneDansPromotion = ec.etat_ec_pris_dans_promotion === 1;
const semestreAlignementPromotion = ec.semestre_alignement_promotion;
const semestreActuel = parseInt(cmb_semestre_alignre.value);

const estAligneDansAutreSemestre = estDejaAligneDansPromotion && 
                                   semestreAlignementPromotion !== null && 
                                   semestreAlignementPromotion !== semestreActuel;
```

##### B. Indication visuelle (fond rouge moderne)
```javascript
if (estAligneDansAutreSemestre) {
  tr.style.backgroundColor = '#fee2e2'; // Rouge moderne clair
  tr.style.borderLeft = '4px solid #ef4444'; // Bordure rouge
  tr.title = `⚠️ Cet EC est déjà aligné dans le ${nomSemestreAlignementPromotion} 
              de cette promotion par ${nomAgentAlignementPromotion}`;
}
```

##### C. Désactivation de la checkbox
```javascript
if (estAligneDansAutreSemestre) {
  case_cocher.disabled = true;
  case_cocher.checked = false;
  case_cocher.style.cursor = 'not-allowed';
}
```

##### D. Message d'alerte explicatif
```javascript
if (estAligneDansAutreSemestre) {
  e.preventDefault();
  case_cocher.checked = false;
  
  textAlert.innerHTML = `
    ⚠️ <strong>EC DÉJÀ ALIGNÉ DANS CETTE PROMOTION!</strong><br><br>
    Cet EC "${ec.Intutile_ec}" est déjà aligné dans le ${nomSemestreAlignementPromotion} 
    de cette promotion par ${nomAgentAlignementPromotion}.<br><br>
    <strong>Règle:</strong> Un EC ne peut être aligné qu'une seule fois par promotion.
  `;
  dialog.showModal();
}
```

---

## 🗂️ Structure des Fichiers Créés

```
webUKA/
├── Structure_Procedure_Liste_EC_Aligne_V2.sql    # Nouvelle procédure SQL
├── DOCUMENTATION_CORRECTION_ALIGNEMENT_EC.md     # Ce fichier
└── UKA_Numerique/
    └── D_Faculte copy 3/
        ├── API_PHP/
        │   └── Liste_EC_Aligne_V2.php            # Nouveau fichier PHP
        └── JavaScript/
            └── Manip_EC_Aligner_V2.js            # Nouveau fichier JavaScript
```

---

## 🚀 Comment Utiliser les Nouvelles Versions

### Étape 1: Créer la procédure stockée dans la base de données

```sql
-- Exécuter le contenu du fichier: Structure_Procedure_Liste_EC_Aligne_V2.sql
-- dans votre gestionnaire MySQL (phpMyAdmin, MySQL Workbench, etc.)
```

### Étape 2: Mettre à jour le fichier HTML principal

Dans votre page d'alignement des ECs, **remplacer** la référence à l'ancien JavaScript:

**AVANT:**
```html
<script src="JavaScript/Manip_EC_Aligner.js"></script>
```

**APRÈS:**
```html
<script src="JavaScript/Manip_EC_Aligner_V2.js"></script>
```

### Étape 3: Tester la correction

1. **Test 1: Aligner un EC dans un semestre**
   - Sélectionner une promotion (ex: L1)
   - Sélectionner un semestre (ex: Semestre 1)
   - Aligner un EC (ex: "Algorithmique")
   - ✅ L'EC doit être coché

2. **Test 2: Changer de semestre dans la même promotion**
   - Garder la même promotion (L1)
   - Changer pour Semestre 2
   - ✅ L'EC "Algorithmique" doit apparaître avec:
     - Fond rouge clair (#fee2e2)
     - Bordure rouge à gauche
     - Checkbox désactivée
     - Message au survol

3. **Test 3: Essayer de cocher l'EC déjà aligné**
   - Cliquer sur la checkbox désactivée
   - ✅ Une alerte doit s'afficher avec le message détaillé

4. **Test 4: Changer de promotion**
   - Sélectionner une autre promotion (ex: L2)
   - ✅ L'EC "Algorithmique" doit être disponible (checkbox active)

---

## 📊 Diagramme de Flux de la Logique

```
Chargement de la page
    ↓
Sélection: Promotion + Semestre + Enseignant
    ↓
Appel API: Liste_EC_Aligne_V2.php
    ↓
Procédure SQL: Liste_EC_Aligne_V2
    ↓
Retour des ECs avec nouvelles colonnes
    ↓
Pour chaque EC:
    │
    ├─→ EC déjà aligné dans un AUTRE semestre de cette promotion?
    │   │
    │   ├─→ OUI:
    │   │   ├─ Fond rouge (#fee2e2)
    │   │   ├─ Bordure rouge (#ef4444)
    │   │   ├─ Checkbox désactivée
    │   │   └─ Message informatif
    │   │
    │   └─→ NON:
    │       ├─ Fond normal
    │       └─ Checkbox selon état normal
    │
    └─→ Affichage de la ligne
```

---

## 🎨 Codes Couleur Utilisés

| Élément | Couleur | Code Hex | Utilisation |
|---------|---------|----------|-------------|
| Fond ligne | Rouge clair | `#fee2e2` | EC déjà aligné |
| Bordure | Rouge | `#ef4444` | Indication visuelle forte |
| Icône warning | Rouge | `#ef4444` | Symbole ⚠️ |

---

## 🔍 Comparaison Avant/Après

### AVANT (Bug)
```
Promotion: L1, Semestre 1
├─ [✓] Algorithmique (aligné)

Changement vers Semestre 2 (même promotion L1)
├─ [ ] Algorithmique (checkbox active - BUG!)
    ↳ L'utilisateur peut réaligner → ERREUR
```

### APRÈS (Corrigé)
```
Promotion: L1, Semestre 1
├─ [✓] Algorithmique (aligné)

Changement vers Semestre 2 (même promotion L1)
├─ [⊗] Algorithmique (checkbox DÉSACTIVÉE, fond rouge)
    ↳ Message: "Déjà aligné en Semestre 1 par Prof. DUPONT"
    ↳ L'utilisateur ne peut PAS réaligner → CORRECT ✓
```

---

## ⚠️ Points d'Attention

1. **Ne pas supprimer les anciens fichiers immédiatement**
   - Gardez-les comme backup pendant la phase de test
   - Préfixez-les avec `_OLD_` si nécessaire

2. **Ordre de déploiement important:**
   - D'abord: Créer la procédure SQL
   - Ensuite: Déployer le fichier PHP
   - Enfin: Mettre à jour la référence JavaScript dans le HTML

3. **Compatibilité base de données:**
   - La procédure utilise `LEFT JOIN` standard MySQL
   - Compatible avec MySQL 5.7+ et MariaDB 10.2+

4. **Performance:**
   - Les nouvelles jointures n'impactent pas significativement les performances
   - Index existants sur `id_ec`, `Code_Promotion`, `idAnnee_Acad` sont utilisés

---

## 🧪 Tests Recommandés

### Test 1: Alignement basique
- [ ] Aligner un EC dans S1 d'une promotion
- [ ] Vérifier que l'EC est coché
- [ ] Recharger la page et vérifier la persistance

### Test 2: Protection inter-semestre
- [ ] Garder la promotion, changer pour S2
- [ ] Vérifier le fond rouge sur l'EC déjà aligné
- [ ] Vérifier que la checkbox est désactivée
- [ ] Survol: vérifier le message tooltip
- [ ] Clic: vérifier l'alerte explicative

### Test 3: Liberté inter-promotion
- [ ] Changer de promotion (ex: L1 → L2)
- [ ] Vérifier que le même EC est disponible (actif)
- [ ] Aligner l'EC dans la nouvelle promotion
- [ ] Vérifier que ça fonctionne

### Test 4: Informations enseignant
- [ ] Vérifier que le nom de l'enseignant apparaît dans le message
- [ ] Vérifier que le nom du semestre est correct

---

## 📞 Support et Questions

Si vous rencontrez des problèmes:

1. **Vérifier les logs d'erreurs:**
   - Console navigateur (F12)
   - Logs PHP (error_log)
   - Logs MySQL (query log)

2. **Points de vérification:**
   - La procédure `Liste_EC_Aligne_V2` existe-t-elle dans la BDD?
   - Le fichier `Liste_EC_Aligne_V2.php` est-il accessible?
   - La référence JavaScript est-elle mise à jour dans le HTML?

3. **Debug JavaScript:**
   ```javascript
   console.log("EC:", ec);
   console.log("Déjà aligné dans promo?", ec.etat_ec_pris_dans_promotion);
   console.log("Semestre alignement:", ec.semestre_alignement_promotion);
   ```

---

## 📝 Notes de Version

**Version:** 2.0
**Date:** Décembre 2025
**Auteur:** Correction automatique du bug d'alignement multiple

**Changements majeurs:**
- ✅ Empêche l'alignement multiple d'un EC dans une promotion
- ✅ Indication visuelle moderne (rouge clair)
- ✅ Messages d'alerte explicatifs
- ✅ Informations contextuelles (semestre, enseignant)

---

## 🎓 Règles Métier Implémentées

1. **Un EC = Une seule fois par promotion**
   - Peu importe le semestre
   - Peu importe l'enseignant

2. **Liberté inter-promotions**
   - Le même EC peut être aligné dans différentes promotions
   - Ex: "Algorithmique" en L1, L2, L3

3. **Traçabilité**
   - On sait quel enseignant a aligné l'EC
   - On sait dans quel semestre il a été aligné

---

**FIN DE LA DOCUMENTATION**

Pour toute question ou amélioration, consultez les fichiers sources avec commentaires détaillés.
