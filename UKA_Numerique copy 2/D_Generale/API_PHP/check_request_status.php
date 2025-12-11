<?php
/**
 * ============================================================================
 * API: Vérifier le statut d'une demande de connexion
 * ============================================================================
 * Utilisé par le nouveau demandeur pour vérifier si sa demande a été acceptée
 */
include('../session_check.php');
require_once("../../../Connexion_BDD/Connexion_1.php");

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['demande_id']) || !isset($data['token'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    $demande_id = intval($data['demande_id']);
    $token = $data['token'];
    
    $sql = "SELECT id_demande, statut_demande, reponse_user_actuel, 
                   reponse_user_demandeur, expiration
            FROM session_demandes_connexion 
            WHERE id_demande = :demande_id
            AND token_demande = :token";
    
    $stmt = $con->prepare($sql);
    $stmt->execute([
        ':demande_id' => $demande_id,
        ':token' => $token
    ]);
    
    $demande = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$demande) {
        echo json_encode([
            'success' => false,
            'message' => 'Demande introuvable'
        ]);
        exit;
    }
    
    // Vérifier expiration
    if (strtotime($demande['expiration']) < time()) {
        echo json_encode([
            'success' => true,  // Changé en true pour que le JS puisse traiter
            'expired' => true,
            'message' => 'La demande a expiré'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'statut_demande' => $demande['statut_demande'],
        'reponse_user_actuel' => $demande['reponse_user_actuel'],
        'reponse_user_demandeur' => $demande['reponse_user_demandeur'],
        'can_confirm' => ($demande['reponse_user_actuel'] === 'accepte' && 
                         $demande['statut_demande'] === 'en_attente')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
