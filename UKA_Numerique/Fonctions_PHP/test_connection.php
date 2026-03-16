<?php
include("../../Connexion_BDD/Connexion_1.php");

header('Content-Type: application/json');

if ($con === null) {
    echo json_encode([
        "status" => "error",
        "message" => "Connexion au serveur perdue"
    ]);
    exit;
}

try {
    $con->query("SELECT 1");
    echo json_encode([
        "status" => "ok"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Serveur indisponible"
    ]);
}
