# 📋 PLAN D'IMPLÉMENTATION - DÉCISIONS DE DÉLIBÉRATION

## 📅 Date : 13 Décembre 2024
## 🎯 Objectif : Intégrer les décisions automatiques ADM/COMP/DEF/AJ/ABS

---

## 📊 ANALYSE DE L'EXISTANT

### ✅ **CE QUI EST DÉJÀ FAIT**

1. **Colonnes calculées fonctionnelles** :
   - ✅ Crédits validés (notes ≥ 10)
   - ✅ Total notes pondérées
   - ✅ Moyenne du semestre (sur /20)
   - ✅ Mention (A, B, C, D, E, F, G)

2. **Système de mentions CECT** :
   ```javascript
   A = [18-20]  // Excellence
   B = [16-18[  // Très Grande Distinction
   C = [14-16[  // Grande Distinction
   D = [12-14[  // Distinction
   E = [10-12[  // Satisfaction
   F = [8-10[   // Faible
   G = [0-8[    // Échec
   ```

3. **Colonne Décision créée** :
   - ✅ Présente dans le tableau
   - ❌ Affiche uniquement "-" (non implémentée)

4. **Structure BDD** :
   - ✅ Champ `Decision_jury` existe dans la table `passer_par`

---

## 🎓 CRITÈRES DE DÉLIBÉRATION (UKA)

D'après l'analyse du système et des documents :

### **DÉCISIONS POSSIBLES**

| Code | Signification | Critères |
|------|--------------|----------|
| **ADM** | Admis | Crédits validés ≥ 30 ET Moyenne ≥ 10 |
| **COMP** | Complément | 20 ≤ Crédits < 30 ET Moyenne ≥ 10 |
| **DEF** | Définitivement Éliminé | Crédits < 20 OU Moyenne < 8 |
| **AJ** | Ajourné | Échec temporaire - peut repasser |
| **ABS** | Absent | Aucune note encodée |

### **RÈGLES DE CALCUL DÉTAILLÉES**

```
SI étudiant n'a AUCUNE note encodée:
    → ABS (Absent)

SINON SI crédits validés ≥ 30 ET moyenne ≥ 10:
    → ADM (Admis)
    
SINON SI crédits validés ENTRE [20-29] ET moyenne ≥ 10:
    → COMP (Complément)
    
SINON SI crédits validés < 20 OU moyenne < 8:
    → DEF (Définitivement Éliminé)
    
SINON:
    → AJ (Ajourné)
```

---

## 🔧 ÉTAPES D'IMPLÉMENTATION

### **ÉTAPE 1 : Créer la fonction de calcul de décision** ⏱️ 10 min

**Fichier** : `Manip_Deliberation.js`  
**Ligne d'insertion** : Après `calculerMention()` (~ligne 1085)

```javascript
/**
 * Calculer la décision de délibération selon les critères UKA
 * @param {number} creditsValides - Crédits validés (notes ≥ 10)
 * @param {number} moyenne - Moyenne du semestre (/20)
 * @param {boolean} hasNotes - Si l'étudiant a au moins une note
 * @returns {string} Code décision : ADM, COMP, DEF, AJ, ABS
 */
function calculerDecision(creditsValides, moyenne, hasNotes) {
    // Cas 1 : Étudiant absent (aucune note)
    if (!hasNotes) {
        return 'ABS';
    }
    
    // Cas 2 : Admis (≥30 crédits ET moyenne ≥10)
    if (creditsValides >= 30 && moyenne >= 10) {
        return 'ADM';
    }
    
    // Cas 3 : Complément (20-29 crédits ET moyenne ≥10)
    if (creditsValides >= 20 && creditsValides < 30 && moyenne >= 10) {
        return 'COMP';
    }
    
    // Cas 4 : Définitivement éliminé (<20 crédits OU moyenne <8)
    if (creditsValides < 20 || moyenne < 8) {
        return 'DEF';
    }
    
    // Cas 5 : Ajourné (autres cas)
    return 'AJ';
}

/**
 * Obtenir le libellé complet de la décision
 */
function getDecisionLabel(code) {
    const labels = {
        'ADM': 'Admis',
        'COMP': 'Complément',
        'DEF': 'Définitivement Éliminé',
        'AJ': 'Ajourné',
        'ABS': 'Absent'
    };
    return labels[code] || '-';
}

/**
 * Obtenir la couleur associée à la décision
 */
function getDecisionColor(code) {
    const colors = {
        'ADM': '#2ecc71',   // Vert
        'COMP': '#3498db',  // Bleu
        'DEF': '#e74c3c',   // Rouge
        'AJ': '#f39c12',    // Orange
        'ABS': '#95a5a6'    // Gris
    };
    return colors[code] || '#000000';
}
```

---

### **ÉTAPE 2 : Intégrer le calcul dans l'affichage initial** ⏱️ 15 min

**Fichier** : `Manip_Deliberation.js`  
**Ligne à modifier** : Ligne 1004-1009 (colonne Décision)

**Remplacer** :
```javascript
// Décision
const td_decision = document.createElement('td');
td_decision.classList.add("text-center", "cell-calculated");
td_decision.textContent = "-";
tr.appendChild(td_decision);
```

**Par** :
```javascript
// Décision
const td_decision = document.createElement('td');
td_decision.classList.add("text-center", "cell-calculated");

// Vérifier si l'étudiant a au moins une note
let hasNotes = false;
tab_ECs_aligne.forEach((ec_s_aligne) => {
    let cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec_s_aligne.id_ec_aligne);
    if (cote && cote.Cote !== "" && cote.Cote !== null) {
        hasNotes = true;
    }
});

// Calculer la décision
const decision = calculerDecision(totalCreditsValides, moyenne, hasNotes);
td_decision.textContent = decision;
td_decision.title = getDecisionLabel(decision); // Tooltip avec le libellé complet
td_decision.style.fontWeight = "700";
td_decision.style.fontSize = "16px";
td_decision.style.color = getDecisionColor(decision);

tr.appendChild(td_decision);
```

---

### **ÉTAPE 3 : Mettre à jour lors du recalcul (edition de notes)** ⏱️ 15 min

**Fichier** : `Manip_Deliberation.js`  
**Fonction** : `recalculerCreditsValides()` (ligne ~1106)  
**Ligne à ajouter** : Après le calcul de la mention (~ligne 1175)

**Ajouter après le calcul de la mention** :
```javascript
    // Cinquième cellule = Décision
    if (cellsCalculated[4]) {
        // Vérifier si l'étudiant a des notes
        let hasNotes = false;
        cellsNotes.forEach(cell => {
            if (cell.textContent.trim() !== "") {
                hasNotes = true;
            }
        });
        
        const decision = calculerDecision(totalCreditsValides, moyenne, hasNotes);
        cellsCalculated[4].textContent = decision;
        cellsCalculated[4].title = getDecisionLabel(decision);
        cellsCalculated[4].style.fontWeight = "700";
        cellsCalculated[4].style.fontSize = "16px";
        cellsCalculated[4].style.color = getDecisionColor(decision);
    }
```

---

### **ÉTAPE 4 : Créer l'API pour sauvegarder la décision** ⏱️ 20 min

**Nouveau fichier** : `c:\wamp64\www\webUKA\UKA_Numerique\D_Faculte\API_PHP\save_decision.php`

```php
<?php
header('Content-Type: application/json; charset=utf-8');
include("../../../Connexion_BDD/Connexion_1.php");

try {
    // Récupérer les données JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    $matricule = $data['matricule'] ?? null;
    $id_semestre = $data['id_semestre'] ?? null;
    $decision = $data['decision'] ?? null;
    $moyenne = $data['moyenne'] ?? null;
    $credits_valides = $data['credits_valides'] ?? null;
    
    if (!$matricule || !$id_semestre || !$decision) {
        throw new Exception("Paramètres manquants");
    }
    
    // Vérifier si l'enregistrement existe dans passer_par
    $check_sql = "SELECT COUNT(*) as count 
                  FROM passer_par 
                  WHERE Etudiant_Matricule = :matricule 
                  AND idPromotion = (
                      SELECT idPromotion 
                      FROM semestre 
                      WHERE id_sem = :id_semestre
                  )";
    
    $check_stmt = $con->prepare($check_sql);
    $check_stmt->bindParam(':matricule', $matricule);
    $check_stmt->bindParam(':id_semestre', $id_semestre);
    $check_stmt->execute();
    $exists = $check_stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if ($exists) {
        // Mettre à jour la décision
        $update_sql = "UPDATE passer_par 
                       SET Decision_jury = :decision
                       WHERE Etudiant_Matricule = :matricule 
                       AND idPromotion = (
                           SELECT idPromotion 
                           FROM semestre 
                           WHERE id_sem = :id_semestre
                       )";
        
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bindParam(':decision', $decision);
        $update_stmt->bindParam(':matricule', $matricule);
        $update_stmt->bindParam(':id_semestre', $id_semestre);
        $update_stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Décision mise à jour',
            'decision' => $decision,
            'matricule' => $matricule
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Enregistrement non trouvé dans passer_par',
            'matricule' => $matricule
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
```

---

### **ÉTAPE 5 : Ajouter un bouton "Enregistrer toutes les décisions"** ⏱️ 20 min

**Fichier** : `Page_Principale.php` (ou le fichier HTML principal)  
**Emplacement** : Dans la section des badges stats

**Ajouter ce bouton** :
```html
<button id="btn-save-decisions" class="btn btn-success btn-lg" style="margin-left: 20px;">
    <i class="fas fa-save"></i> Enregistrer Décisions
</button>
```

**Fichier** : `Manip_Deliberation.js`  
**Ajouter à la fin du fichier** :

```javascript
/**
 * Enregistrer toutes les décisions dans la base de données
 */
async function enregistrerToutesLesDecisions() {
    const btnSave = document.getElementById('btn-save-decisions');
    if (!btnSave) return;
    
    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    
    try {
        const tbody = document.querySelector('#table_deliberation tbody');
        const rows = tbody.querySelectorAll('tr');
        const id_semestre = cmb_semestre_encodage_delib.value;
        
        let successCount = 0;
        let errorCount = 0;
        
        for (const row of rows) {
            const matricule = row.dataset.matricule;
            const cellsCalculated = row.querySelectorAll('.cell-calculated');
            
            if (cellsCalculated.length >= 5) {
                const creditsValides = parseInt(cellsCalculated[0].textContent) || 0;
                const moyenne = parseFloat(cellsCalculated[2].textContent) || 0;
                const decision = cellsCalculated[4].textContent;
                
                if (decision && decision !== '-') {
                    try {
                        const response = await fetch('../API_PHP/save_decision.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                matricule: matricule,
                                id_semestre: id_semestre,
                                decision: decision,
                                moyenne: moyenne,
                                credits_valides: creditsValides
                            })
                        });
                        
                        const result = await response.json();
                        if (result.success) {
                            successCount++;
                        } else {
                            errorCount++;
                            console.error(`Erreur pour ${matricule}:`, result.message);
                        }
                    } catch (err) {
                        errorCount++;
                        console.error(`Erreur réseau pour ${matricule}:`, err);
                    }
                }
            }
        }
        
        // Afficher le résultat
        if (errorCount === 0) {
            alert(`✅ Toutes les décisions ont été enregistrées avec succès (${successCount} étudiants)`);
        } else {
            alert(`⚠️ ${successCount} décisions enregistrées, ${errorCount} erreurs. Voir la console pour les détails.`);
        }
        
    } catch (error) {
        alert('❌ Erreur lors de l\'enregistrement : ' + error.message);
        console.error(error);
    } finally {
        btnSave.disabled = false;
        btnSave.innerHTML = '<i class="fas fa-save"></i> Enregistrer Décisions';
    }
}

// Attacher l'événement au bouton
document.addEventListener('DOMContentLoaded', () => {
    const btnSave = document.getElementById('btn-save-decisions');
    if (btnSave) {
        btnSave.addEventListener('click', enregistrerToutesLesDecisions);
    }
});
```

---

### **ÉTAPE 6 : Améliorer l'affichage visuel (BONUS)** ⏱️ 10 min

**Fichier** : `Encodage_Modern.css`  
**Ajouter à la fin** :

```css
/* ==================== Styles pour les décisions ==================== */
.decision-ADM {
    background-color: #d4edda !important;
    color: #155724 !important;
    font-weight: 800 !important;
}

.decision-COMP {
    background-color: #d1ecf1 !important;
    color: #0c5460 !important;
    font-weight: 800 !important;
}

.decision-DEF {
    background-color: #f8d7da !important;
    color: #721c24 !important;
    font-weight: 800 !important;
}

.decision-AJ {
    background-color: #fff3cd !important;
    color: #856404 !important;
    font-weight: 800 !important;
}

.decision-ABS {
    background-color: #e2e3e5 !important;
    color: #383d41 !important;
    font-weight: 800 !important;
}

/* Badge pour le bouton d'enregistrement */
#btn-save-decisions {
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

#btn-save-decisions:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

#btn-save-decisions:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
```

**Modifier les étapes 2 et 3 pour ajouter les classes CSS** :

Dans l'affichage initial (Étape 2), ajouter :
```javascript
td_decision.classList.add(`decision-${decision}`);
```

Dans le recalcul (Étape 3), ajouter :
```javascript
// Supprimer les anciennes classes
cellsCalculated[4].classList.remove('decision-ADM', 'decision-COMP', 'decision-DEF', 'decision-AJ', 'decision-ABS');
// Ajouter la nouvelle classe
cellsCalculated[4].classList.add(`decision-${decision}`);
```

---

## 📊 RÉCAPITULATIF DES MODIFICATIONS

| Fichier | Action | Lignes |
|---------|--------|--------|
| `Manip_Deliberation.js` | Ajouter 3 fonctions de calcul | ~80 lignes |
| `Manip_Deliberation.js` | Modifier affichage initial | Ligne ~1004 |
| `Manip_Deliberation.js` | Modifier recalcul | Ligne ~1175 |
| `Manip_Deliberation.js` | Ajouter fonction enregistrement | ~80 lignes |
| `API_PHP/save_decision.php` | **Créer nouveau fichier** | ~80 lignes |
| `Page_Principale.php` | Ajouter bouton | 3 lignes |
| `Encodage_Modern.css` | Ajouter styles décisions | ~45 lignes |

**Total : ~290 lignes de code**

---

## ✅ TESTS À EFFECTUER

### **Test 1 : Calcul automatique**
- [ ] Ouvrir Délibération, sélectionner un semestre
- [ ] Vérifier que la colonne Décision affiche des codes (ADM/COMP/DEF/AJ/ABS)
- [ ] Vérifier la cohérence avec les crédits et moyennes

### **Test 2 : Mise à jour temps réel**
- [ ] Modifier une note d'un étudiant
- [ ] Vérifier que la décision se met à jour automatiquement
- [ ] Tester un étudiant qui passe de DEF à ADM

### **Test 3 : Enregistrement BDD**
- [ ] Cliquer sur "Enregistrer Décisions"
- [ ] Vérifier le message de confirmation
- [ ] Vérifier dans la BDD (table `passer_par`, colonne `Decision_jury`)

### **Test 4 : Cas limites**
- [ ] Étudiant avec 0 notes → ABS
- [ ] Étudiant avec exactement 30 crédits → ADM
- [ ] Étudiant avec 29 crédits et moyenne 10 → COMP
- [ ] Étudiant avec 19 crédits → DEF

---

## 🎯 RÉSULTAT ATTENDU

**AVANT** :
```
| Crédits | Total | Moyenne | Mention | Décision |
|---------|-------|---------|---------|----------|
| 30      | 480   | 16.00   | B       | -        |
```

**APRÈS** :
```
| Crédits | Total | Moyenne | Mention | Décision |
|---------|-------|---------|---------|----------|
| 30      | 480   | 16.00   | B       | ADM      |  (vert)
| 25      | 350   | 11.67   | D       | COMP     |  (bleu)
| 15      | 200   | 6.67    | G       | DEF      |  (rouge)
| 28      | 380   | 12.67   | D       | AJ       |  (orange)
| 0       | 0     | 0.00    | -       | ABS      |  (gris)
```

---

## 🚀 ORDRE D'EXÉCUTION RECOMMANDÉ

1. ✅ **ÉTAPE 1** → Créer les 3 fonctions de calcul (10 min)
2. ✅ **ÉTAPE 2** → Intégrer dans l'affichage initial (15 min)
3. ✅ **ÉTAPE 3** → Mettre à jour lors du recalcul (15 min)
4. 🧪 **TEST** → Vérifier que ça fonctionne visuellement (5 min)
5. ✅ **ÉTAPE 4** → Créer l'API de sauvegarde (20 min)
6. ✅ **ÉTAPE 5** → Ajouter le bouton d'enregistrement (20 min)
7. 🧪 **TEST** → Vérifier l'enregistrement en BDD (5 min)
8. ✅ **ÉTAPE 6** → Améliorer le design (10 min)

**⏱️ Temps total estimé : ~1h40**

---

## 📝 NOTES IMPORTANTES

⚠️ **ATTENTION** :
- Le champ `Decision_jury` dans `passer_par` doit correspondre à une promotion
- Vérifier que la relation `etudiant → passer_par → promotion → semestre` est correcte
- Les décisions sont liées à une ANNÉE ACADÉMIQUE, pas juste au semestre

💡 **AMÉLIORATIONS FUTURES** :
- Export PDF des décisions
- Historique des décisions par année
- Rapport statistique (% ADM, COMP, DEF, etc.)
- Validation par le Doyen avant enregistrement définitif
- Système de commentaires pour les cas particuliers

---

## 🎉 AVANTAGES DE CETTE IMPLÉMENTATION

✅ **Automatisation complète** - Plus besoin de calculer manuellement  
✅ **Temps réel** - Mise à jour instantanée lors de l'édition  
✅ **Visuel clair** - Couleurs pour identifier rapidement les cas  
✅ **Traçabilité** - Sauvegarde en base de données  
✅ **Performance** - Calculs côté client, enregistrement par batch  
✅ **Maintenable** - Code propre et documenté  

---

**Prêt à commencer ? Je peux implémenter chaque étape une par une ! 🚀**
