<?php
include("../../../Connexion_BDD/Connexion_1.php");
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

$Num_pce = $input['Num_pce'] ?? null;
$niveau = intval($input['niveau'] ?? 0);
$action = $input['action'] ?? '';
$agent = $input['agent'] ?? null;

if (!$Num_pce || !$niveau || !$action || !$agent) {
    echo json_encode(['success'=>false, 'error'=>'Paramètres manquants']);
    exit;
}

$date = date('Y-m-d H:i:s');

try {
    if ($niveau === 2) {
        // Vérifier si Niveau 2 a déjà été effectué
        $verif = $con->prepare("SELECT Niveau_2 FROM autoriser_depense WHERE Num_pce = ?");
        $verif->execute([$Num_pce]);
        $ligne = $verif->fetch(PDO::FETCH_ASSOC);

        if ($ligne && !empty($ligne['Niveau_2'])) {
            echo json_encode(['success'=>false, 'error'=>'Désole. Une action a déjà été effecué sur cette autorisation par vous']);
            exit;
        }

        // Niveau 2 : INSERT
        $stmt = $con->prepare("INSERT INTO autoriser_depense 
            (Num_pce, Agent_auriz2, Date_autoriz2, Niveau_2, Agent_auriz1, Date_autoriz1, Niveau_1)
            VALUES (?, ?, ?, ?, NULL, NULL, NULL)");
        $stmt->execute([$Num_pce, $agent, $date, $action]);

    } elseif ($niveau === 1) {
        // Vérifier si Niveau 1 a déjà été effectué
        $verif = $con->prepare("SELECT Niveau_1, Niveau_2 FROM autoriser_depense WHERE Num_pce = ?");
        $verif->execute([$Num_pce]);
        $ligne = $verif->fetch(PDO::FETCH_ASSOC);

        if (!$ligne || empty($ligne['Niveau_2'])) {
            echo json_encode(['success'=>false, 'error'=>'Le Niveau 2 doit autoriser avant Niveau 1']);
            exit;
        }

        if (!empty($ligne['Niveau_1'])) {
            echo json_encode(['success'=>false, 'error'=>'Action déjà effectuée au Niveau 1']);
            exit;
        }

        // Niveau 1 : UPDATE
        $stmt = $con->prepare("UPDATE autoriser_depense 
            SET Agent_auriz1 = ?, Date_autoriz1 = ?, Niveau_1 = ? 
            WHERE Num_pce = ?");
        $stmt->execute([$agent, $date, $action, $Num_pce]);
    }

    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}
?>
