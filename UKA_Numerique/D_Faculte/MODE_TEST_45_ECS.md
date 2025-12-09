# 🧪 MODE TEST - 45 ECs Fictives

## 📋 Ce qui a été modifié

### ✅ Fichiers créés :
1. **`API_PHP/Liste_EC_aligne_delibe_TEST.php`** 
   - Génère 45 ECs fictives (9 réelles + 36 supplémentaires)
   - Matières variées : Mathématiques, Physique, IA, Blockchain, etc.

2. **`API_PHP/Liste_Cotes_TEST.php`**
   - Génère des notes aléatoires pour ~30% des étudiants
   - Notes entre 5 et 20

### 🔄 Fichiers modifiés temporairement :
1. **`JavaScript/Manip_Encodage.js`** (lignes 55-65 et 68-78)
   ```javascript
   // Mode TEST activé
   'API_PHP/Liste_EC_aligne_delibe_TEST.php'
   'API_PHP/Liste_Cotes_TEST.php'
   ```

2. **`Entree_Par_Encodage.php`** (ligne 56)
   - Badge orange "🧪 MODE TEST (45 ECs)" avec animation pulse

3. **`Styles_CSS/Encodage_Modern.css`** (ligne 233)
   - Animation @keyframes pulse

---

## 🎯 Comment tester

1. **Ouvrir la page d'encodage** dans votre navigateur
2. **Sélectionner un semestre** dans le dropdown
3. **Observer** :
   - ✅ Badge orange "MODE TEST (45 ECs)" qui pulse
   - ✅ Statistiques : "45 ECs" (devient orange car ≥40)
   - ✅ Défilement horizontal automatique
   - ✅ Indicateur en bas : "← Faites défiler horizontalement →"
   - ✅ Colonnes N° (50px) et NOM (280px) restent fixes (sticky)
   - ✅ Les 45 colonnes d'ECs s'affichent correctement

---

## 🔙 Comment revenir aux données réelles

### Option 1 : Modifier le JavaScript
Dans `Manip_Encodage.js` :

```javascript
// Ligne 55-65 : Remplacer
const response = await fetch('API_PHP/Liste_EC_aligne_delibe_TEST.php', {
// Par
const response = await fetch('API_PHP/Liste_EC_aligne_delibe.php', {

// Ligne 68-78 : Remplacer
const response = await fetch('API_PHP/Liste_Cotes_TEST.php', {
// Par
const response = await fetch('API_PHP/Liste_Cotes.php', {
```

### Option 2 : Retirer le badge MODE TEST
Dans `Entree_Par_Encodage.php`, supprimer les lignes 56-58 :
```php
<!-- 🧪 Badge MODE TEST -->
<div class="stat-badge" style="...">
  <i class="fas fa-flask"></i> MODE TEST (45 ECs)
</div>
```

---

## 📊 Statistiques du mode TEST

- **ECs totales** : 45
- **Largeur théorique** : 50 + 280 + 80 + (45 × 45) = **2435px**
- **Support défilement** : ✅ Oui (horizontal)
- **Colonnes sticky** : ✅ N° et NOM restent visibles
- **Performance** : ✅ Optimisé avec DocumentFragment

---

## 🎨 Fonctionnalités visibles avec 45 ECs

1. **Défilement horizontal fluide** avec scrollbar personnalisée
2. **Badge orange** dans les stats (≥40 ECs)
3. **Indicateur de défilement** en bas du tableau
4. **Navigation clavier** fonctionne parfaitement (flèches, Tab, Enter)
5. **Surbrillance ligne/colonne** lors de l'édition
6. **Tooltip** sur les en-têtes EC pour voir le nom complet

---

## 🚀 Testez maintenant !

Rechargez la page d'encodage et profitez du mode TEST avec 45 ECs ! 🎉
