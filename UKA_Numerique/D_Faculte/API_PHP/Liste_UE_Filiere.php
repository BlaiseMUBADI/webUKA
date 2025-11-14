<?php

include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$id_filiere = $_SESSION['id_fac'];

try {
    $sql_call = "CALL Liste_UE_Filiere(:id_filiere)";
    $stmt = $con->prepare($sql_call);
    $stmt->bindParam(':id_filiere', $id_filiere);
    $stmt->execute();

    $etud = array();
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $etud[] = $ligne;
    }
    echo json_encode($etud);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

