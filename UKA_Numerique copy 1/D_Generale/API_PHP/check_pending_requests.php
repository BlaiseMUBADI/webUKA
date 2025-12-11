<?php
/**
 * ============================================================================
 * API: Vérifier les demandes de connexion en attente
 * ============================================================================
 * Vérifie si l'utilisateur actuel a des demandes de connexion en attente
 * Retourne les informations de la demande pour afficher la notification
 */

include('../session_check.php');
require_once("../../../Connexion_BDD/Connexion_1.php");
require_once('../SessionManager.php');

//header('Content-Type: application/json');

try {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['Login_user']) || !isset($_SESSION['secure_session_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Non authentifié'
        ]);
        exit;
    }
    
    $sessionManager = new SessionManager($con);
    $login = $_SESSION['Login_user'];
    $type_compte = isset($_SESSION['Role_Jury']) ? 'jury' : 'agent';
    
    // Vérifier si la session actuelle est toujours valide
    if (!$sessionManager->validateSession($_SESSION['secure_session_id'])) {
        echo json_encode([
            'success' => false,
            'session_expired' => true,
            'message' => 'Session expirée'
        ]);
        exit;
    }
    
    // Vérifier s'il y a des demandes en attente
    $demande = $sessionManager->getPendingConnectionRequest($login, $type_compte);
    
    // DEBUG: Log pour voir les paramètres et le résultat
    error_log("Check pending - Login: $login, Type: $type_compte, Demande trouvée: " . ($demande ? 'OUI' : 'NON'));
    if ($demande) {
        error_log("Demande details: " . json_encode($demande));
    }
    
    if ($demande) {
        echo json_encode([
            'success' => true,
            'has_pending_request' => true,
            'demande' => [
                'id' => $demande['id_demande'],
                'ip_address' => $demande['ip_address'],
                'date_demande' => $demande['date_demande'],
                'user_agent' => $demande['user_agent'],
                'expiration' => $demande['expiration']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'has_pending_request' => false
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
