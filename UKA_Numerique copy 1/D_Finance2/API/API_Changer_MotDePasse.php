<?php
session_start();
ob_start(); // capture tout ce qui pourrait être envoyé accidentellement
header('Content-Type: application/json');

include("../../../Connexion_BDD/Connexion_1.php");

if (!isset($_SESSION['MatriculeAgent'])) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Utilisateur non authentifié."]);
    exit;
}

$Mat_agent = $_SESSION['MatriculeAgent'];
$ancien = isset($_POST['ancienMotDePasse']) ? sha1($_POST['ancienMotDePasse']) : '';
$nouveau = isset($_POST['nouveauMotDePasse']) ? sha1($_POST['nouveauMotDePasse']) : '';

try {
    $stmt = $con->prepare("SELECT Mot_passe FROM compte_agent WHERE Mat_agent = ?");
    $stmt->execute([$Mat_agent]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || $result['Mot_passe'] !== $ancien) {
        ob_end_clean();
        echo json_encode(["success" => false, "message" => "L'Ancien mot de passe saisi est incorrect."]);
        exit;
    }

    $stmt = $con->prepare("UPDATE compte_agent SET Mot_passe = ? WHERE Mat_agent = ?");
    $stmt->execute([$nouveau, $Mat_agent]);

    ob_end_clean();
    echo json_encode(["success" => true, "message" => "Mot de passe mis à jour avec succès."]);
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Erreur : " . $e->getMessage()]);
}
