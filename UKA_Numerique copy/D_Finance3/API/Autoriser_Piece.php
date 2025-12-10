<?php
include("../../../Connexion_BDD/Connexion_1.php");
header("Content-Type: application/json");

// Récupération JSON
$input = json_decode(file_get_contents("php://input"), true);

if (empty($input['Num_pce'])) {
    echo json_encode(['success' => false, 'error' => 'Numéro de pièce manquant']);
    exit;
}

$num_pce = $input['Num_pce'];

try {
    // Exemple : ajouter un champ "Autorisé" si nécessaire
    // Ici je vais juste mettre un flag pour l'exemple
    $update = $con->prepare("UPDATE autorisation_depense SET Autorise = 1 WHERE Num_pce = ?");
    $update->execute([$num_pce]);

    echo json_encode(['success' => true]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
