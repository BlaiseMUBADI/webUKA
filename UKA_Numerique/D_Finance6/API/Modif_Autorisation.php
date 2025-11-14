<?php
include("../../../Connexion_BDD/Connexion_1.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id']) || !isset($data['field'])) {
    echo json_encode(["success" => false, "message" => "Données incomplètes"]);
    exit;
}

$id = intval($data['id']);
$field = trim($data['field']);
$value = trim($data['value'] ?? '');

$allowed_fields = ['Motif', 'Montant', 'Imputation'];
if (!in_array($field, $allowed_fields)) {
    echo json_encode(["success" => false, "message" => "Champ non autorisé"]);
    exit;
}

try {
    // 🔹 Récupérer le Num_pce de la ligne qu’on veut modifier
    $stmt = $con->prepare("SELECT Num_pce FROM autorisation_depense WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $num_pce = $stmt->fetchColumn();

    if (!$num_pce) {
        echo json_encode(["success" => false, "message" => "Pièce introuvable"]);
        exit;
    }

    // 🔹 Vérifier si le Niveau_1 est déjà autorisé
    $check = $con->prepare("SELECT Niveau_1 FROM autoriser_depense WHERE Num_pce = :num");
    $check->execute([':num' => $num_pce]);
    $niveau1 = $check->fetchColumn();

    if ($niveau1 && trim($niveau1) !== '') {
        echo json_encode([
            "success" => false,
            "message" => "Modification impossible : Cette pièce a été transmise à la caisse par le Recteur."
        ]);
        exit;
    }

    // 🔹 Si on arrive ici, la modification est autorisée
    if ($field === 'Montant') {
        if (!is_numeric($value)) {
            echo json_encode(["success" => false, "message" => "Montant invalide"]);
            exit;
        }
        $stmt = $con->prepare("UPDATE autorisation_depense SET Montant = :val WHERE id = :id");
        $stmt->execute([':val' => floatval($value), ':id' => $id]);
    } else {
        $stmt = $con->prepare("UPDATE autorisation_depense SET $field = :val WHERE id = :id");
        $stmt->execute([':val' => $value, ':id' => $id]);
    }

    echo json_encode(["success" => true]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
