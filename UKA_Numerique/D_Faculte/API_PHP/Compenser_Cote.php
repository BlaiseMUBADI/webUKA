<?php
// API pour récupérer les ECs qui peuvent compenser un EC donné
//header('Content-Type: application/json; charset=UTF-8');
session_start();
require_once("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion à la base de données'
    ]);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $matricule = $data['matricule'] ?? null;
    $ec_beneficiaire_id = $data['ec_beneficiaire'] ?? null;
    $ec_cedant_id = $data['ec_cedant'] ?? null;
    
    if (!$matricule || !$ec_beneficiaire_id || !$ec_cedant_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    // Vérifier que les deux ECs existent pour cet étudiant
    $stmt = $con->prepare("
        SELECT 
            Cote,
            Cote_rattrapage,
            cote_compensee,
            cote_reste_apres_cedee,
            id_ec_aligne
        FROM evaluer 
        WHERE Matricule = ? AND id_ec_aligne IN (?, ?)
    ");
    $stmt->execute([$matricule, $ec_beneficiaire_id, $ec_cedant_id]);
    $cotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($cotes) !== 2) {
        echo json_encode([
            'success' => false,
            'message' => 'Les deux ECs doivent avoir des notes'
        ]);
        exit;
    }
    
    // Séparer les cotes
    $cote_beneficiaire = null;
    $cote_cedant = null;
    
    foreach ($cotes as $cote) {
        if ($cote['id_ec_aligne'] == $ec_beneficiaire_id) {
            $cote_beneficiaire = $cote;
        } else {
            $cote_cedant = $cote;
        }
    }
    
    // Calculer les valeurs actuelles
    $valeur_beneficiaire = $cote_beneficiaire['cote_compensee'] 
        ?? $cote_beneficiaire['Cote_rattrapage'] 
        ?? $cote_beneficiaire['Cote'];
    
    $valeur_cedant = $cote_cedant['cote_reste_apres_cedee'] 
        ?? $cote_cedant['Cote_rattrapage'] 
        ?? $cote_cedant['Cote'];
    
    // Vérifications
    if ($valeur_beneficiaire >= 10) {
        echo json_encode([
            'success' => false,
            'message' => 'L\'EC bénéficiaire a déjà 10/20 ou plus'
        ]);
        exit;
    }
    
    if ($valeur_beneficiaire < 8) {
        echo json_encode([
            'success' => false,
            'message' => 'L\'EC bénéficiaire doit avoir au moins 8/20 pour être compensé'
        ]);
        exit;
    }
    
    if ($valeur_cedant <= 10) {
        echo json_encode([
            'success' => false,
            'message' => 'L\'EC cédant doit avoir plus de 10/20'
        ]);
        exit;
    }
    
    // Calculer les points à transférer
    $deficit = 10 - $valeur_beneficiaire;
    $surplus = $valeur_cedant - 10;
    $points_a_transferer = min($deficit, $surplus);
    
    // Nouvelles valeurs
    $nouvelle_cote_beneficiaire = $valeur_beneficiaire + $points_a_transferer;
    $nouvelle_cote_cedant = $valeur_cedant - $points_a_transferer;
    
    // Commencer la transaction
    $con->beginTransaction();
    
    // Mettre à jour l'EC bénéficiaire
    $stmt = $con->prepare("
        UPDATE evaluer 
        SET cote_compensee = ?,
            Ligne_touchee_Matricule_id_ec_aligne = ?
        WHERE Matricule = ? AND id_ec_aligne = ?
    ");
    $reference = $matricule . '_' . $ec_cedant_id;
    $stmt->execute([
        $nouvelle_cote_beneficiaire,
        $reference,
        $matricule,
        $ec_beneficiaire_id
    ]);
    
    // Mettre à jour l'EC cédant
    $stmt = $con->prepare("
        UPDATE evaluer 
        SET cote_reste_apres_cedee = ?,
            Ligne_touchee_Matricule_id_ec_aligne = ?
        WHERE Matricule = ? AND id_ec_aligne = ?
    ");
    $reference_benef = $matricule . '_' . $ec_beneficiaire_id;
    $stmt->execute([
        $nouvelle_cote_cedant,
        $reference_benef,
        $matricule,
        $ec_cedant_id
    ]);
    
    $con->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Compensation effectuée avec succès',
        'beneficiaire' => [
            'id_ec_aligne' => $ec_beneficiaire_id,
            'cote_avant' => floatval($valeur_beneficiaire),
            'cote_apres' => floatval($nouvelle_cote_beneficiaire),
            'points_recus' => floatval($points_a_transferer)
        ],
        'cedant' => [
            'id_ec_aligne' => $ec_cedant_id,
            'cote_avant' => floatval($valeur_cedant),
            'cote_apres' => floatval($nouvelle_cote_cedant),
            'points_cedes' => floatval($points_a_transferer)
        ]
    ]);
    
} catch (PDOException $e) {
    if (isset($con) && $con->inTransaction()) {
        $con->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => 'Erreur base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    if (isset($con) && $con->inTransaction()) {
        $con->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>
