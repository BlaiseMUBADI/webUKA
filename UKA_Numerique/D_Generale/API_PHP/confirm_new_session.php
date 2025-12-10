<?php
/**
 * ============================================================================
 * API: Confirmer l'activation de session (nouveau demandeur)
 * ============================================================================
 * Le nouveau demandeur confirme qu'il veut activer sa session
 * après que l'utilisateur actuel ait accepté
 */

session_start();
header('Content-Type: application/json');

require_once('../../Connexion_BDD/Connexion_1.php');
require_once('../SessionManager.php');

try {
    // Récupérer les données POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['demande_id']) || !isset($data['token']) || !isset($data['action'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    $demande_id = intval($data['demande_id']);
    $token = $data['token'];
    $action = $data['action']; // 'accepte' ou 'refuse'
    
    $sessionManager = new SessionManager($con);
    
    if ($action === 'accepte') {
        $result = $sessionManager->newUserConfirmsActivation($demande_id, $token);
        
        if ($result['success']) {
            // Stocker les informations de session
            $_SESSION['secure_session_id'] = $result['session_data']['session_id'];
            $_SESSION['session_token'] = $result['session_data']['token'];
            $_SESSION['session_start_time'] = time();
            
            echo json_encode([
                'success' => true,
                'message' => 'Session activée avec succès',
                'redirect' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    } elseif ($action === 'refuse') {
        $result = $sessionManager->newUserRefusesActivation($demande_id, $token);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Vous avez annulé la connexion',
                'cancelled' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation'
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
