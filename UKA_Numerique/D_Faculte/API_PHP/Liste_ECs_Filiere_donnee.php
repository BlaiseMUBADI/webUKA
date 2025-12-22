<?php
session_start();
require_once("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données"]);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$code_ue = $_GET['code_ue'];

$sql_select = "CALL Liste_ECs_UE_donnee(:code_ue)";

$stmt = $con->prepare($sql_select);
$stmt->bindParam(':code_ue', $code_ue);
$stmt->execute();

$etud = array();
while ($ligne = $stmt->fetch()) {
    $etud[] = $ligne;
}
echo json_encode($etud);


?>

