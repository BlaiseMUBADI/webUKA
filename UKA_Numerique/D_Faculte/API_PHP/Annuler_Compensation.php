
<?php
session_start();
require_once("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

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
    $ec_beneficiaire = $data['ec_beneficiaire'] ?? null;
    $ec_cedant = $data['ec_cedant'] ?? null;

    if (!$matricule || !$ec_beneficiaire || !$ec_cedant) {
        echo json_encode([
            'success' => false,
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }

    $con->beginTransaction();

    // 1. Vérifier que les deux ECs existent pour cet étudiant
    $stmt = $con->prepare("
        SELECT id_ec_aligne, cote_compensee, cote_reste_apres_cedee, Cote, Cote_rattrapage
        FROM evaluer
        WHERE Matricule = ? AND id_ec_aligne IN (?, ?)
    ");
    $stmt->execute([$matricule, $ec_beneficiaire, $ec_cedant]);
    $cotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($cotes) !== 2) {
        throw new Exception('Les deux ECs doivent exister pour cet étudiant.');
    }

    // 2. Annuler la compensation :
    //    - Pour l'EC bénéficiaire : remettre cote_compensee à NULL, Ligne_touchee_Matricule_id_ec_aligne à NULL
    //    - Pour l'EC cédant : remettre cote_reste_apres_cedee à NULL, Ligne_touchee_Matricule_id_ec_aligne à NULL

    // Toujours remettre à NULL toutes les colonnes de compensation sur les deux lignes
    $stmt1 = $con->prepare("
        UPDATE evaluer
        SET cote_compensee = NULL,
            cote_reste_apres_cedee = NULL,
            Ligne_touchee_Matricule_id_ec_aligne = NULL
        WHERE Matricule = ? AND (id_ec_aligne = ? OR id_ec_aligne = ?)
    ");
    $stmt1->execute([$matricule, $ec_beneficiaire, $ec_cedant]);

    $con->commit();
    echo json_encode([
        'success' => true,
        'message' => 'Compensation annulée et cotes restaurées.'
    ]);
} catch (Exception $e) {
    if (isset($con) && $con->inTransaction()) $con->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
