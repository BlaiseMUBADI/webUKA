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
    $id_semestre = $data['id_semestre'] ?? null;
    
    if (!$matricule || !$ec_beneficiaire_id || !$id_semestre) {
        echo json_encode([
            'success' => false,
            'message' => 'Paramètres manquants'
        ]);
        exit;
    }
    
    // Récupérer l'UE et le crédit de l'EC bénéficiaire
    $stmt = $con->prepare("
        SELECT ue.Code_ue, ue.Intitule_ue, ue.Catégorie, ec.Credit, ec.Intutile_ec
        FROM element_constitutifs_aligne eca
        JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
        JOIN unite_enseignement ue ON ec.Code_ue = ue.Code_ue
        WHERE eca.id_ec_aligne = ?
    ");
    $stmt->execute([$ec_beneficiaire_id]);
    $ue_beneficiaire = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ue_beneficiaire) {
        echo json_encode([
            'success' => false,
            'message' => 'EC non trouvé'
        ]);
        exit;
    }
    
    // Récupérer la cote du bénéficiaire
    $stmt = $con->prepare("
        SELECT 
            COALESCE(cote_compensee, Cote_rattrapage, Cote) as cote_actuelle
        FROM evaluer
        WHERE Matricule = ? AND id_ec_aligne = ?
    ");
    $stmt->execute([$matricule, $ec_beneficiaire_id]);
    $cote_ben = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cote_ben || $cote_ben['cote_actuelle'] === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Aucune note trouvée pour cet EC'
        ]);
        exit;
    }
    
    $cote_beneficiaire = floatval($cote_ben['cote_actuelle']);
    
    // Vérifier les conditions de compensation
    if ($cote_beneficiaire >= 10) {
        echo json_encode([
            'success' => false,
            'message' => 'Cette note est déjà >= 10, pas besoin de compensation'
        ]);
        exit;
    }
    
    if ($cote_beneficiaire < 8) {
        echo json_encode([
            'success' => false,
            'message' => 'Note trop faible (< 8/20) pour être compensée'
        ]);
        exit;
    }
    
    // Recherche d'abord dans la même UE
    $stmt = $con->prepare("
        SELECT 
            eca.id_ec_aligne,
            ec.Intutile_ec,
            ec.Credit,
            ev.Cote,
            ev.Cote_rattrapage,
            ev.cote_reste_apres_cedee,
            COALESCE(ev.cote_reste_apres_cedee, ev.Cote_rattrapage, ev.Cote) as cote_actuelle,
            ue.Intitule_ue
        FROM evaluer ev
        JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
        JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
        JOIN unite_enseignement ue ON ec.Code_ue = ue.Code_ue
        WHERE ev.Matricule = ?
          AND eca.Id_Semestre = ?
          AND ue.Code_ue = ?
          AND ec.Credit = ?
          AND eca.id_ec_aligne != ?
          AND COALESCE(ev.cote_reste_apres_cedee, ev.Cote_rattrapage, ev.Cote) > 10
        ORDER BY COALESCE(ev.cote_reste_apres_cedee, ev.Cote_rattrapage, ev.Cote) DESC
    ");
    $stmt->execute([
        $matricule,
        $id_semestre,
        $ue_beneficiaire['Code_ue'],
        $ue_beneficiaire['Credit'],
        $ec_beneficiaire_id
    ]);
    $ecs_compensables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si aucun EC dans la même UE, élargir à tout le semestre (même crédit, hors UE)
    if (count($ecs_compensables) === 0) {
        $stmt = $con->prepare("
            SELECT 
                eca.id_ec_aligne,
                ec.Intutile_ec,
                ec.Credit,
                ev.Cote,
                ev.Cote_rattrapage,
                ev.cote_reste_apres_cedee,
                COALESCE(ev.cote_reste_apres_cedee, ev.Cote_rattrapage, ev.Cote) as cote_actuelle,
                ue.Intitule_ue
            FROM evaluer ev
            JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
            JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
            JOIN unite_enseignement ue ON ec.Code_ue = ue.Code_ue
            WHERE ev.Matricule = ?
              AND eca.Id_Semestre = ?
              AND ec.Credit = ?
              AND eca.id_ec_aligne != ?
              AND COALESCE(ev.cote_reste_apres_cedee, ev.Cote_rattrapage, ev.Cote) > 10
            ORDER BY COALESCE(ev.cote_reste_apres_cedee, ev.Cote_rattrapage, ev.Cote) DESC
        ");
        $stmt->execute([
            $matricule,
            $id_semestre,
            $ue_beneficiaire['Credit'],
            $ec_beneficiaire_id
        ]);
        $ecs_compensables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calculer le déficit et le surplus pour chaque EC
    $deficit = 10 - $cote_beneficiaire;

    $ecs_formates = [];
    foreach ($ecs_compensables as $ec) {
        $cote_actuelle = floatval($ec['cote_actuelle']);
        $surplus = $cote_actuelle - 10;
        $points_disponibles = min($deficit, $surplus);
        $ecs_formates[] = [
            'id_ec_aligne' => $ec['id_ec_aligne'],
            'intitule' => $ec['Intutile_ec'],
            'credit' => floatval($ec['Credit']),
            'cote_actuelle' => $cote_actuelle,
            'surplus' => round($surplus, 2),
            'points_disponibles' => round($points_disponibles, 2),
            'cote_apres_cession' => round($cote_actuelle - $points_disponibles, 2),
            'ue' => $ec['Intitule_ue']
        ];
    }

    echo json_encode([
        'success' => true,
        'ue' => [
            'code' => $ue_beneficiaire['Code_ue'],
            'intitule' => $ue_beneficiaire['Intitule_ue'],
            'categorie' => $ue_beneficiaire['Catégorie']
        ],
        'ec_beneficiaire' => [
            'intitule' => $ue_beneficiaire['Intutile_ec'],
            'cote_actuelle' => $cote_beneficiaire,
            'deficit' => round($deficit, 2),
            'credit' => floatval($ue_beneficiaire['Credit'])
        ],
        'ecs_compensables' => $ecs_formates,
        'count' => count($ecs_formates)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>
