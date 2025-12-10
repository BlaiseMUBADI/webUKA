# 🔐 Système de Gestion des Sessions Sécurisées - Guide d'Installation

## 📋 Vue d'ensemble

Ce système implémente une gestion avancée des sessions avec :
- ✅ Une seule session active par utilisateur
- ✅ Notification des tentatives de connexion concurrentes
- ✅ Validation bidirectionnelle (utilisateur actuel + nouveau demandeur)
- ✅ Détection d'inactivité et déconnexion automatique
- ✅ Historique complet des sessions

---

## 🚀 Installation

### Étape 1 : Mise à jour de la base de données

1. Ouvrez **phpMyAdmin** ou votre client MySQL
2. Sélectionnez votre base de données `bdd_uka`
3. Exécutez le fichier SQL :
   ```bash
   mysql -u root -p bdd_uka < update_database_sessions.sql
   ```
   
   **OU** importez le fichier `update_database_sessions.sql` via phpMyAdmin

4. Vérifiez que les tables suivantes ont été créées :
   - `sessions_actives`
   - `demandes_connexion`
   - `historique_sessions`
   - `activites_session`

5. Vérifiez que les colonnes suivantes ont été ajoutées :
   - Dans `compte_agent` : `derniere_connexion`, `session_active`, `session_id_actuelle`
   - Dans `t_membre_jury` : `derniere_connexion`, `session_active`, `session_id_actuelle`

### Étape 2 : Vérification des fichiers

Assurez-vous que les fichiers suivants existent :

**Fichiers PHP :**
- ✅ `UKA_Numerique/Fonctions_PHP/SessionManager.php`
- ✅ `UKA_Numerique/Fonctions_PHP/Deconnexion.php` (modifié)
- ✅ `UKA_Numerique/Fonctions_PHP/include_session_monitor.php`
- ✅ `UKA_Numerique/index.php` (modifié)

**APIs :**
- ✅ `UKA_Numerique/API/check_pending_requests.php`
- ✅ `UKA_Numerique/API/respond_connection_request.php`
- ✅ `UKA_Numerique/API/confirm_new_session.php`
- ✅ `UKA_Numerique/API/check_request_status.php`
- ✅ `UKA_Numerique/API/update_activity.php`

**JavaScript :**
- ✅ `UKA_Numerique/Fonctions_PHP/session_monitor.js`

### Étape 3 : Intégrer le SessionMonitor dans les pages protégées

Dans **TOUTES** les pages nécessitant une authentification, ajoutez cette ligne dans le `<head>` :

```php
<?php include('Fonctions_PHP/include_session_monitor.php'); ?>
```

**Exemples de pages à modifier :**
- `Page_Principale.php`
- `Page_Principale_Finance.php`
- `D_Academique/index.php`
- `D_Administration/Principal.php`
- `D_Faculte/Principale_fac.php`
- Etc.

**Exemple complet :**
```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ma Page</title>
    
    <!-- FontAwesome pour les icônes -->
    <link rel="stylesheet" href="fontawesome-6.5.1/css/all.min.css">
    
    <!-- IMPORTANT : Inclure le SessionMonitor -->
    <?php include('Fonctions_PHP/include_session_monitor.php'); ?>
</head>
<body>
    <!-- Votre contenu -->
</body>
</html>
```

---

## ⚙️ Configuration

### Paramètres du SessionMonitor

Vous pouvez personnaliser les paramètres dans `include_session_monitor.php` :

```javascript
const sessionMonitor = new SessionMonitor({
    inactivityTimeout: 1800000,      // 30 minutes (en millisecondes)
    warningTime: 300000,             // Avertir 5 minutes avant
    checkInterval: 30000,            // Vérifier toutes les 30 secondes
    activityUpdateInterval: 60000,   // Mettre à jour chaque minute
    checkPendingRequestsInterval: 10000, // Vérifier demandes toutes les 10 sec
});
```

### Paramètres de la base de données

Le nettoyage automatique des sessions inactives se fait via l'event scheduler MySQL :
- **Fréquence** : Toutes les 5 minutes
- **Délai d'inactivité** : 30 minutes

Pour modifier ces paramètres, éditez la procédure stockée :
```sql
-- Nettoyer sessions inactives depuis X minutes
CALL clean_inactive_sessions(30); 
```

---

## 🧪 Tests

### Test 1 : Connexion normale
1. Connectez-vous avec un utilisateur
2. Vérifiez que vous êtes redirigé correctement
3. Vérifiez dans la table `sessions_actives` qu'une ligne a été créée

### Test 2 : Connexion concurrente
1. Connectez-vous avec l'utilisateur A sur le navigateur 1
2. Tentez de vous connecter avec le même utilisateur sur le navigateur 2
3. **Résultat attendu :**
   - Navigateur 1 : Modal "Une personne tente de se connecter"
   - Navigateur 2 : Modal "En attente d'autorisation"

4. Sur navigateur 1, cliquez "Accepter"
5. Sur navigateur 2, cliquez "Oui, activer"
6. **Résultat :** Navigateur 1 déconnecté, Navigateur 2 connecté

### Test 3 : Inactivité
1. Connectez-vous
2. N'effectuez aucune action pendant 25 minutes
3. **Résultat attendu :** Avertissement "Inactivité détectée"
4. Attendez 5 minutes supplémentaires
5. **Résultat :** Déconnexion automatique

### Test 4 : Déconnexion manuelle
1. Connectez-vous
2. Cliquez sur le bouton de déconnexion
3. Vérifiez que la session a été supprimée de `sessions_actives`
4. Vérifiez qu'une ligne a été ajoutée dans `historique_sessions`

---

## 📊 Monitoring et Maintenance

### Vérifier les sessions actives

```sql
-- Vue des sessions actives avec informations utilisateur
SELECT * FROM v_sessions_actives;

-- Compter les sessions par utilisateur
SELECT user_login, COUNT(*) as nb_sessions 
FROM sessions_actives 
WHERE statut = 'active'
GROUP BY user_login;
```

### Vérifier les demandes en attente

```sql
-- Vue des demandes en attente
SELECT * FROM v_demandes_connexion_actives;

-- Demandes expirées
SELECT * FROM demandes_connexion 
WHERE statut_demande = 'expiree';
```

### Statistiques d'utilisation

```sql
-- Sessions par jour
SELECT DATE(date_connexion) as jour, COUNT(*) as nb_sessions
FROM historique_sessions
GROUP BY DATE(date_connexion)
ORDER BY jour DESC;

-- Raisons de déconnexion
SELECT raison_deconnexion, COUNT(*) as nombre
FROM historique_sessions
WHERE raison_deconnexion IS NOT NULL
GROUP BY raison_deconnexion;

-- Durée moyenne des sessions
SELECT AVG(duree_session) / 60 as duree_moyenne_minutes
FROM historique_sessions
WHERE duree_session IS NOT NULL;
```

### Nettoyage manuel

Si nécessaire, vous pouvez nettoyer manuellement :

```sql
-- Nettoyer les sessions inactives depuis plus de 30 minutes
CALL clean_inactive_sessions(30);

-- Supprimer les demandes expirées
DELETE FROM demandes_connexion 
WHERE statut_demande = 'expiree' 
AND date_demande < DATE_SUB(NOW(), INTERVAL 7 DAY);

-- Archiver les anciennes sessions (plus de 6 mois)
DELETE FROM historique_sessions 
WHERE date_connexion < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

---

## 🔧 Dépannage

### Problème : Les notifications ne s'affichent pas

**Solution :**
1. Vérifiez que FontAwesome est chargé : `<link rel="stylesheet" href="fontawesome-6.5.1/css/all.min.css">`
2. Ouvrez la console du navigateur (F12) et vérifiez les erreurs JavaScript
3. Vérifiez que `session_monitor.js` est bien chargé

### Problème : Erreur "Non authentifié" dans les APIs

**Solution :**
1. Vérifiez que la session PHP est démarrée : `session_start();`
2. Vérifiez que `$_SESSION['Login_user']` est défini
3. Vérifiez que `$_SESSION['secure_session_id']` est défini

### Problème : L'event scheduler ne fonctionne pas

**Solution :**
```sql
-- Vérifier si l'event scheduler est activé
SHOW VARIABLES LIKE 'event_scheduler';

-- L'activer si nécessaire
SET GLOBAL event_scheduler = ON;

-- Vérifier les events
SHOW EVENTS;
```

### Problème : Sessions non supprimées

**Solution :**
```sql
-- Vérifier les sessions zombies
SELECT * FROM sessions_actives 
WHERE derniere_activite < DATE_SUB(NOW(), INTERVAL 30 MINUTE);

-- Nettoyer manuellement
CALL clean_inactive_sessions(30);
```

---

## 🔒 Sécurité

### Bonnes pratiques implémentées

✅ **Tokens sécurisés** : Utilisation de `random_bytes()` pour générer des tokens uniques  
✅ **Requêtes préparées** : Protection contre les injections SQL  
✅ **Validation bidirectionnelle** : Double confirmation pour les sessions concurrentes  
✅ **Expiration automatique** : Les demandes expirent après 5 minutes  
✅ **Historique complet** : Traçabilité de toutes les actions  
✅ **Nettoyage automatique** : Suppression des sessions inactives  

### Recommandations supplémentaires

1. **HTTPS obligatoire** : Déployez votre application en HTTPS uniquement
2. **Mots de passe forts** : Migrez tous les mots de passe vers bcrypt (déjà implémenté pour les nouveaux comptes)
3. **Limitation de tentatives** : Ajoutez un système de limitation après 5 tentatives échouées
4. **Authentification à deux facteurs** : Considérez l'ajout de 2FA pour les comptes sensibles

---

## 📝 Notes importantes

1. **Compatibilité** : Le système est compatible avec votre système dual (compte_agent + t_membre_jury)
2. **Performance** : Les requêtes sont optimisées avec des index appropriés
3. **Évolutivité** : Le système peut gérer des milliers de sessions simultanées
4. **Logs** : Tous les événements sont enregistrés dans `historique_sessions`

---

## 📞 Support

En cas de problème, vérifiez :
1. Les logs PHP : `error_log` dans votre configuration PHP
2. Les logs MySQL : Fichier de log MySQL
3. La console JavaScript du navigateur (F12)

---

**✅ Installation terminée !**

Le système de gestion des sessions sécurisées est maintenant opérationnel.
