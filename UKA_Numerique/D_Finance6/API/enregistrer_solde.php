<?php
// enregistrer_solde.php
header('Content-Type: application/json');

// 🔒 Sécurité de base : n'autoriser que POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
    exit;
}

include("../../../Connexion_BDD/Connexion_1.php");
// Lire le corps JSON envoyé par fetch
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier les données envoyées
$date_solde = $data['date_solde'] ?? null;
$devise = $data['devise'] ?? '';
$montant = $data['montant'] ?? '';
$observations = $data['observations'] ?? '';

// Validation de la devise
$devise_valides = ['CDF', 'USD'];  // Liste des devises autorisées
if (!in_array($devise, $devise_valides)) {
    echo json_encode([
        "success" => false,
        "message" => "Devise invalide. Seules les devises CDF et USD sont autorisées."
    ]);
    exit;
}

// Formater la date pour vérifier uniquement le jour sans l'heure
$date_solde_day = substr($date_solde, 0, 10);  // Format YYYY-MM-DD

try {
    // Vérifie si une clôture a déjà été enregistrée pour cette date et devise
    $checkStmt = $con->prepare("SELECT COUNT(*) FROM solde WHERE DATE(date_solde) = ? AND devise = ?");
    $checkStmt->execute([$date_solde_day, $devise]);
    $existeDeja = $checkStmt->fetchColumn();

    if ($existeDeja > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Une clôture existe déjà pour cette date et cette devise."
        ]);
        exit;
    }

    // Préparation de l'insertion
    $stmt = $con->prepare("INSERT INTO solde (date_solde, devise, montant, observation) VALUES (?, ?, ?, ?)");
    $ok = $stmt->execute([$date_solde, $devise, $montant, $observations]);

    if ($ok) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Erreur lors de l'insertion en base."
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}

?>