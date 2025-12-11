<?php
/**
 * ============================================================================
 * API: Mettre à jour l'activité de la session
 * ============================================================================
 * Appelé périodiquement par JavaScript pour maintenir la session active
 */

include('../session_check.php');
require_once('../../../Connexion_BDD/Connexion_1.php');
require_once('../SessionManager.php');

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['secure_session_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Aucune session active'
        ]);
        exit;
    }
    
    $sessionManager = new SessionManager($con);
    $result = $sessionManager->updateActivity($_SESSION['secure_session_id']);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'timestamp' => time()
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Session introuvable'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
