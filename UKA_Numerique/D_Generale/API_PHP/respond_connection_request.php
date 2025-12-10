<?php
/**
 * ============================================================================
 * API: Répondre à une demande de connexion (utilisateur actuel)
 * ============================================================================
 * L'utilisateur actuellement connecté accepte ou refuse une nouvelle tentative
 */

session_start();
header('Content-Type: application/json');

require_once('../../Connexion_BDD/Connexion_1.php');
require_once('../SessionManager.php');

try {
    // Vérifier l'authentification
    if (!isset($_SESSION['Login_user']) || !isset($_SESSION['secure_session_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Non authentifié'
        ]);
        exit;
    }
    
    // Récupérer les données POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['demande_id']) || !isset($data['action'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    $demande_id = intval($data['demande_id']);
    $action = $data['action']; // 'accepte' ou 'refuse'
    
    $sessionManager = new SessionManager($con);
    
    if ($action === 'accepte') {
        $result = $sessionManager->currentUserAcceptsRequest($demande_id);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Demande acceptée. En attente de confirmation du demandeur.',
                'action' => 'accepted'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de l\'acceptation'
            ]);
        }
    } elseif ($action === 'refuse') {
        $result = $sessionManager->currentUserRefusesRequest($demande_id);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Demande refusée. Votre session reste active.',
                'action' => 'refused'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du refus'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Action invalide'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
