<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$code_ue = $_POST['code_ue'];
$nom_ec = $_POST['nom_ec'];
$credit = $_POST['credit'];
$hr_td = $_POST['hr_td'];
$hr_tp = $_POST['hr_tp'];
$cmi = $_POST['CMI'];
$tpe = $_POST['TPE'];
$vht = $_POST['VHT'];

$con->beginTransaction();

try {
    $sql_insert = "CALL Nouvel_EC(:code_ue, :nom_ec, :credit, :cmi, :hr_td, :hr_tp, :tpe, :vht)";
    $stmt = $con->prepare($sql_insert);

    $stmt->bindParam(':code_ue', $code_ue);
    $stmt->bindParam(':nom_ec', $nom_ec);
    $stmt->bindParam(':credit', $credit);
    $stmt->bindParam(':cmi', $cmi);
    $stmt->bindParam(':hr_td', $hr_td);
    $stmt->bindParam(':hr_tp', $hr_tp);
    $stmt->bindParam(':tpe', $tpe);
    $stmt->bindParam(':vht', $vht);

    if ($stmt->execute()) {
        $con->commit();
        echo json_encode(["message" => "Élément constitutif ajouté avec succès !"]);
    } else {
        throw new Exception("Échec de l'enregistrement.");
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo json_encode(["message" => "Erreur lors de l'insertion: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["message" => $e->getMessage()]);
}
?>