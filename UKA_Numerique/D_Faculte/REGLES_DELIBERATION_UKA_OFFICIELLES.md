# 🎓 RÈGLES DE DÉLIBÉRATION UKA (OFFICIELLES)

## 📋 Source : Critères de Délibération en LMD - UKA 2023-2024
**Par Bernard KANDAWU MUKUMA, Chef de Travaux**

---

## ⚖️ RÈGLES DE COMPENSATION (Point 06)

### **Principe de base**

> "La compensation est la possibilité que des notes supérieures à la moyenne puissent en compenser de moins bonnes."

### **Conditions d'application**

1. ✅ **Compensation intra-UE** : À l'intérieur d'une UE
2. ✅ **Compensation inter-UE** : Entre les UE d'un même semestre
3. ✅ **Compensation annuelle** : Entre les deux semestres (sur décision du jury)

### **Règles strictes**

```
⚠️ SEUIL MINIMUM : Note ≥ 8/20

❌ Note < 8/20 → PAS de compensation possible
✅ Note ≥ 8/20 ET Moyenne ≥ 10 → Compensation possible
```

**Texte officiel** :
> "La compensation n'est appliquée que si la plus faible note obtenue dans les UE est supérieure ou égale à 8/20."

**Validation** :
> "Il est possible de valider une entité (UE ou semestre) en obtenant une moyenne pondérée égale ou supérieure à 10/20, sans avoir obligatoirement obtenu une note supérieure ou égale à 10/20 à chacun des éléments qui la composent."

---

## 🎯 DÉCISIONS DU JURY (Point 07)

### **A. À la fin de chaque SEMESTRE**

| Décision | Description | Code |
|----------|-------------|------|
| **Semestre validé avec capitalisation** | Tous les crédits acquis directement (notes ≥ 10) | **CAP** |
| **Semestre validé avec compensation** | Crédits acquis par compensation (notes 8-10) | **COMP** |
| **DEF** | Manque de notes pour absence justifiée ou non | **DEF** |
| **Semestre non validé** | Échec | **NV** |

### **B. À la fin de l'ANNÉE ACADÉMIQUE**

| Décision | Description | Critères |
|----------|-------------|----------|
| **ADM** | Admis avec capitalisation définitive | 60 crédits validés directement |
| **COMP** | Admis avec compensation | 60 crédits par compensation (notes ≥ 8) |
| **DEF** | Défaillant | Manque de notes pour absence |
| **AJ** | Ajourné ou non admis | < 45 crédits (L1→L2) ou échec |
| **ABS** | Absent | N'a présenté aucun examen |

---

## 📊 SYSTÈME DE MENTIONS (Point 10)

### **Échelle officielle UKA**

```
≥ 18  →  (A) EXCELLENT
≥ 16  →  (B) TRES BIEN
≥ 14  →  (C) BIEN
≥ 12  →  (D) ASSZ BIEN
≥ 10  →  (E) PASSABLE
≥ 8   →  (F) INSUFFISANT
≤ 7   →  (G) INSATISFAISANT
```

**Note** : Votre code actuel utilise déjà ces mentions ! ✅

---

## 🎓 PROGRESSION DANS LE PARCOURS (Point 08)

### **Passage de L1 à L2**

**Cas 1 : Passage automatique**
```
✅ 60 crédits capitalisés
✅ Deux semestres validés (avec ou sans compensation)

→ Passage en L2
```

**Cas 2 : Passage conditionnel**
```
✅ Au moins 45 crédits (dont 1/3 minimum dans un semestre)
⚠️ Dettes de L1 à régulariser en L2

→ Autorisé à suivre L2 + inscription aux matières non acquises
```

### **Passage de L2 à L3**

**Cas 1 : Capitalisation complète**
```
✅ 120 crédits sur 120 (4 semestres validés)
OU
✅ 105 crédits (L2 validé par compensation + aucune dette L1)

→ Admission en L3
```

**Cas 2 : Progression conditionnelle**
```
✅ Au moins 90 crédits sur 120
✅ Toutes les UE fondamentales validées
⚠️ Dettes L1 et L2 à régulariser

→ Autorisé à suivre L3 + inscription aux matières non acquises
```

---

## 🔧 ALGORITHME DE CALCUL DES DÉCISIONS

### **Étape 1 : Vérifier la présence**

```javascript
function verifierPresence(etudiant, ecs) {
    let nbExamensPresentes = 0;
    
    ecs.forEach(ec => {
        let note = getNoteEC(etudiant, ec);
        if (note !== null && note !== "") {
            nbExamensPresentes++;
        }
    });
    
    if (nbExamensPresentes === 0) {
        return 'ABS'; // Aucun examen présenté
    }
    
    if (nbExamensPresentes < ecs.length) {
        return 'DEF'; // Manque des notes
    }
    
    return 'PRESENT'; // Tous les examens présentés
}
```

### **Étape 2 : Calculer les crédits validés**

```javascript
function calculerCreditsValides(etudiant, ecs, moyenneGenerale) {
    let creditsDirects = 0;        // Notes ≥ 10
    let creditsCompensation = 0;   // Notes 8-10 avec moyenne ≥ 10
    let creditsEchoues = 0;        // Notes < 8
    
    ecs.forEach(ec => {
        let note = getNoteEC(etudiant, ec);
        let credit = ec.credit;
        
        if (note >= 10) {
            // Validation directe
            creditsDirects += credit;
        } else if (note >= 8 && moyenneGenerale >= 10) {
            // Compensation possible (seuil ≥ 8)
            creditsCompensation += credit;
        } else {
            // Note < 8 → Non compensable
            creditsEchoues += credit;
        }
    });
    
    return {
        directs: creditsDirects,
        compensation: creditsCompensation,
        echoues: creditsEchoues,
        total: creditsDirects + creditsCompensation
    };
}
```

### **Étape 3 : Déterminer la décision finale**

```javascript
function calculerDecisionFinale(presenceStatus, credits, moyenneGenerale) {
    // Cas 1 : Absence ou défaillance
    if (presenceStatus === 'ABS') return 'ABS';
    if (presenceStatus === 'DEF') return 'DEF';
    
    // Cas 2 : Échec (notes < 8 non compensables)
    if (credits.echoues > 0) {
        return 'AJ'; // Ajourné
    }
    
    // Cas 3 : Moyenne insuffisante pour compensation
    if (moyenneGenerale < 10) {
        return 'AJ'; // Ajourné
    }
    
    // Cas 4 : Validation avec compensation
    if (credits.compensation > 0 && credits.total >= 30) {
        return 'COMP'; // Admis avec compensation
    }
    
    // Cas 5 : Validation directe
    if (credits.directs >= 30) {
        return 'ADM'; // Admis
    }
    
    // Cas 6 : Crédits insuffisants
    return 'AJ'; // Ajourné
}
```

---

## 📝 EXEMPLES CONCRETS

### **Exemple 1 : Admission directe (ADM)**

| EC | Crédit | Note | Statut |
|----|--------|------|--------|
| EC1 | 5 | 12/20 | ✅ Direct |
| EC2 | 5 | 15/20 | ✅ Direct |
| EC3 | 8 | 11/20 | ✅ Direct |
| EC4 | 7 | 13/20 | ✅ Direct |
| EC5 | 5 | 10/20 | ✅ Direct |
| **Total** | **30** | **12,23** | |

```
✅ Moyenne = 12,23/20
✅ Toutes les notes ≥ 10
✅ Crédits directs = 30
✅ Crédits par compensation = 0

→ DÉCISION : ADM (Admis)
```

---

### **Exemple 2 : Admission avec compensation (COMP)**

| EC | Crédit | Note | Statut |
|----|--------|------|--------|
| EC1 | 5 | **8,5/20** | ⚠️ Compensation |
| EC2 | 5 | 12/20 | ✅ Direct |
| EC3 | 8 | 11/20 | ✅ Direct |
| EC4 | 7 | **9/20** | ⚠️ Compensation |
| EC5 | 5 | 13/20 | ✅ Direct |
| **Total** | **30** | **10,58** | |

```
✅ Moyenne = 10,58/20 (≥ 10)
⚠️ EC1 = 8,5 (≥ 8) → Compensation possible
⚠️ EC4 = 9 (≥ 8) → Compensation possible
✅ Crédits directs = 18 (5+8+5)
✅ Crédits par compensation = 12 (5+7)
✅ Total = 30 crédits

→ DÉCISION : COMP (Admis avec compensation)
```

---

### **Exemple 3 : Ajourné (AJ) - Note < 8**

| EC | Crédit | Note | Statut |
|----|--------|------|--------|
| EC1 | 5 | **7/20** | ❌ Éliminatoire |
| EC2 | 5 | 12/20 | ✅ Direct |
| EC3 | 8 | 11/20 | ✅ Direct |
| EC4 | 7 | 9/20 | ⚠️ Compensation |
| EC5 | 5 | 13/20 | ✅ Direct |
| **Total** | **30** | **10,10** | |

```
✅ Moyenne = 10,10/20
❌ EC1 = 7/20 (< 8) → PAS de compensation
✅ Crédits directs = 18
❌ Crédits par compensation = 7 (EC4 seulement)
❌ Crédits échoués = 5 (EC1)
❌ Total validé = 25 < 30

→ DÉCISION : AJ (Ajourné)
   Doit repasser EC1 en rattrapage
```

---

### **Exemple 4 : Ajourné (AJ) - Moyenne < 10**

| EC | Crédit | Note | Statut |
|----|--------|------|--------|
| EC1 | 5 | 8/20 | ⚠️ Impossible |
| EC2 | 5 | 9/20 | ⚠️ Impossible |
| EC3 | 8 | 9,5/20 | ⚠️ Impossible |
| EC4 | 7 | 10/20 | ✅ Direct |
| EC5 | 5 | 10/20 | ✅ Direct |
| **Total** | **30** | **9,33** | |

```
❌ Moyenne = 9,33/20 (< 10)
⚠️ Notes entre 8-10 MAIS moyenne < 10
❌ Compensation IMPOSSIBLE
✅ Crédits directs = 12 (7+5)
❌ Crédits par compensation = 0 (moyenne < 10)
❌ Total = 12 < 30

→ DÉCISION : AJ (Ajourné)
   Doit repasser EC1, EC2, EC3 en rattrapage
```

---

### **Exemple 5 : Défaillant (DEF)**

| EC | Crédit | Note | Statut |
|----|--------|------|--------|
| EC1 | 5 | 12/20 | ✅ Présenté |
| EC2 | 5 | - | ❌ Absent |
| EC3 | 8 | 11/20 | ✅ Présenté |
| EC4 | 7 | - | ❌ Absent |
| EC5 | 5 | 13/20 | ✅ Présenté |
| **Total** | **30** | - | |

```
❌ 2 examens non présentés
✅ 3 examens présentés

→ DÉCISION : DEF (Défaillant)
   Absence justifiée ou non
```

---

### **Exemple 6 : Absent (ABS)**

| EC | Crédit | Note | Statut |
|----|--------|------|--------|
| EC1 | 5 | - | ❌ Absent |
| EC2 | 5 | - | ❌ Absent |
| EC3 | 8 | - | ❌ Absent |
| EC4 | 7 | - | ❌ Absent |
| EC5 | 5 | - | ❌ Absent |
| **Total** | **30** | - | |

```
❌ Aucun examen présenté

→ DÉCISION : ABS (Absent)
```

---

## 🎯 RÉSUMÉ DES CRITÈRES

### **Pour ADM (Admis)**
```
✅ Tous les examens présentés
✅ Toutes les notes ≥ 10
✅ 30 crédits validés directement
✅ Moyenne ≥ 10
```

### **Pour COMP (Admis avec compensation)**
```
✅ Tous les examens présentés
✅ Moyenne ≥ 10
⚠️ Certaines notes entre 8-10 (seuil min = 8)
✅ 30 crédits validés (directs + compensation)
❌ Aucune note < 8
```

### **Pour AJ (Ajourné)**
```
✅ Examens présentés
❌ Moyenne < 10
OU
❌ Notes < 8 (non compensables)
OU
❌ Crédits < 45 (pour passage conditionnel)
```

### **Pour DEF (Défaillant)**
```
❌ Un ou plusieurs examens non présentés
⚠️ Absence justifiée ou non
```

### **Pour ABS (Absent)**
```
❌ Aucun examen présenté
```

---

## 📌 POINTS IMPORTANTS

1. **Seuil de compensation** : 8/20 (officiel UKA)
2. **Compensation inter-UE** : Possible dans le même semestre
3. **Compensation annuelle** : Sur décision du jury uniquement
4. **Rachat** : Prérogative exclusive du jury (note ramenée à 10/20)
5. **Mentions** : Système de A à G déjà implémenté ✅

---

## 🚀 IMPLÉMENTATION DANS VOTRE SYSTÈME

### **Modifications nécessaires**

1. ✅ **Ajouter colonne "Crédits directs"** (notes ≥ 10)
2. ✅ **Ajouter colonne "Crédits par compensation"** (notes 8-10)
3. ✅ **Modifier calcul de décision** (ADM vs COMP)
4. ✅ **Vérifier seuil 8/20** pour compensation
5. ✅ **Gérer DEF et ABS** (présence aux examens)

---

**Prêt à implémenter ces règles officielles UKA ? 🎓**
