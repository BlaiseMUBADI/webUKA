<?php
// verifier_cloture.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
    exit;
}

include("../../../Connexion_BDD/Connexion_1.php");

$data = json_decode(file_get_contents("php://input"), true);
$devise = $data['devise'] ?? null;
$date = $data['date'] ?? null;

if (!$devise || !$date) {
    echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
    exit;
}

try {
    $stmt = $con->prepare("SELECT COUNT(*) FROM solde WHERE DATE(date_solde) = ? AND devise = ?");
    $stmt->execute([$date, $devise]);
    $count = $stmt->fetchColumn();

    echo json_encode([
        "success" => true,
        "clotureExiste" => $count > 0
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
