<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$id_filiere = $_SESSION['id_fac'];
$data = json_decode(file_get_contents('php://input'), true);
$mat_agent = $data['mat_agent'];

// Traitez $mat_agent comme nécessaire
try {
    $sql_select = "CALL Liste_Memebre_jury(:mat_agent)";
    $stmt = $con->prepare($sql_select);
    $stmt->bindParam(':mat_agent', $mat_agent);
    $stmt->execute();

    $ecs = array();
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ecs[] = $ligne;
    }
    echo json_encode($ecs);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de la récupération des ECs: " . $e->getMessage()]);
}
?>