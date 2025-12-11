# 🔐 Système d'Authentification Duale - Guide Rapide

## 🎯 Objectif

Permettre aux utilisateurs de se connecter avec deux types de comptes:
- **Compte Agent** (administratif)
- **Compte Membre de Jury** (délibération)

## ⚡ Fonctionnement en 3 Étapes

```
Connexion → Validation → Choix (si nécessaire) → Redirection
```

## 📦 Composants Clés

### 1. Fonction `rediriger_selon_categorie($categorie)`

**Rôle**: Centraliser toutes les redirections
**Avantage**: 240 lignes → 35 lignes (85% de réduction)

```php
// Utilisation simple
if (!rediriger_selon_categorie($ligne['categorie'])) {
    // Catégorie inconnue, gérer l'erreur
    header('location:Page_Principale.php?page=Dashboard');
    exit;
}
```

### 2. Système d'Authentification Duale

**Tables consultées**:
- `compte_agent` (avec SHA1 OU bcrypt)
- `t_membre_jury` (uniquement bcrypt)

**4 Cas possibles**:
1. ❌ Aucun compte valide → Message d'erreur
2. 🔀 Deux comptes valides → Boîte de dialogue
3. ⚖️ Seulement jury → Connexion directe jury
4. 👤 Seulement agent → Connexion directe agent

## 🔧 Maintenance Rapide

### Ajouter une catégorie

**Un seul endroit à modifier**:
```php
$redirections = [
    'Nouvelle_Categorie' => 'Chemin/Vers/Page.php',
];
```

### Changer une redirection

**Modifier la valeur dans le tableau**:
```php
$redirections = [
    'Admin' => 'Nouveau/Chemin.php', // ✅ C'est tout !
];
```

## 📊 Variables de Session Principales

### Compte Agent
- `$_SESSION['MatriculeAgent']`
- `$_SESSION['Categorie']`
- `$_SESSION['Nom_user']`
- `$_SESSION['id_fac']`

### Compte Jury
- `$_SESSION['MatriculeAgent']`
- `$_SESSION['Categorie']` = 'Membre_Jury'
- `$_SESSION['Role_Jury']` (Président/Secrétaire/Membre)
- `$_SESSION['ID_jury']`

## 🎨 Interface Utilisateur

### Boîte de Dialogue
- Design moderne avec animations CSS
- Deux cartes cliquables (Agent/Jury)
- Fond flouté avec backdrop-filter
- Effets hover élégants

## 🔐 Sécurité

- ✅ Requêtes préparées PDO (anti SQL injection)
- ✅ Hachage bcrypt pour nouveaux comptes
- ✅ Support SHA1 legacy pour compatibilité
- ✅ htmlspecialchars() contre XSS
- ✅ Validation du statut 'Actif'

## 🐛 Bugs Corrigés

1. ✅ Callback confirmation ne s'exécutait pas
2. ✅ Session variables incompatibles (Mat_agent vs MatriculeAgent)
3. ✅ Choix ne s'affichait pas au premier clic
4. ✅ Code dupliqué 240 lignes éliminé

## 📖 Documentation Complète

Voir **INDEX_DOCUMENTATION.md** pour:
- Diagrammes de flux détaillés
- Explications SQL complètes
- Guide de maintenance approfondi
- Architecture système

## 🚀 Déploiement

**Fichiers nécessaires**:
- `index.php` (authentification)
- `../Connexion_BDD/Connexion_1.php` (PDO)
- `Styles_CSS/Style_connexion.css`
- `fontawesome-6.5.1/` (icônes)

**Tables requises**:
- `compte_agent`
- `t_membre_jury`
- `agent`
- `t_jury_deliberation`
- `filiere`, `promotion`, `mentions`

---

**Version**: 2.0 | **Dernière mise à jour**: Décembre 2024 | **U.KA.**
