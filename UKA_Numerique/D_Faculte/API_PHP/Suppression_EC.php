<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$code_ec = $_POST['code_ec'];

$con->beginTransaction();

try {
    $sql_sup = "CALL Supprimer_EC(:code_ec)";
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':code_ec', $code_ec);

    if ($stmt->execute()) {
        $con->commit();
        echo json_encode(["status" => "success", "message" => "EC supprimée avec succès !"]);
    } else {
        throw new Exception("Échec de la suppression.");
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'EC: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>