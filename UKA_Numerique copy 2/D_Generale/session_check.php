<?php
/**
 * ============================================================================
 * Session Check - Vérification de session (PHP pur - pas de sortie HTML)
 * ============================================================================
 * 
 * Ce fichier doit être inclus AU DÉBUT de toutes les pages nécessitant
 * une authentification, AVANT toute sortie HTML.
 * 
 * Il démarre la session et peut effectuer des vérifications côté serveur
 * sans envoyer d'en-têtes HTML.
 * 
 * Usage: <?php include('D_Generale/session_check.php'); ?>
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Optionnel: Vérifications de sécurité supplémentaires
// Exemple: vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['Login']) || !isset($_SESSION['secure_session_id'])) {
//     header('location: ../index.php');
//     exit;
// }
