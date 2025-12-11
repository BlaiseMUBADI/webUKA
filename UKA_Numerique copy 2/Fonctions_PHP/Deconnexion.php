<?php
/**
 * ============================================================================
 * Script de déconnexion sécurisée avec nettoyage des sessions
 * ============================================================================
 * 
 * Gère:
 * - Suppression de la session en base de données
 * - Archivage dans l'historique
 * - Nettoyage des sessions PHP
 * - Redirection vers la page de connexion
 */

session_start();

// Inclure les fichiers nécessaires
require_once('../../Connexion_BDD/Connexion_1.php');
require_once('../D_Generale/SessionManager.php');

try {
    // Initialiser le gestionnaire de sessions
    $sessionManager = new SessionManager($con);
    
    // Déterminer la raison de déconnexion (peut être passée en paramètre)
    $raison = isset($_GET['raison']) ? $_GET['raison'] : 'manuelle';
    
    // Si une session sécurisée existe, la terminer
    if (isset($_SESSION['secure_session_id'])) {
        $sessionManager->terminateSession($_SESSION['secure_session_id'], $raison);
    }
    
    // Détruire toutes les variables de session
    $_SESSION = array();
    
    // Détruire le cookie de session si il existe
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    
    // Détruire la session PHP
    session_destroy();
    
} catch (Exception $e) {
    // En cas d'erreur, au moins détruire la session PHP
    error_log("Erreur lors de la déconnexion: " . $e->getMessage());
    session_destroy();
}

// Rediriger vers la page de connexion
header('location:../index.php');
exit;
?>
