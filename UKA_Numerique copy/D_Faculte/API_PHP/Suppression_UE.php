<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$code_ue = $_POST['code_ue'];

$con->beginTransaction();

try {
    $sql_sup = "CALL Supprimer_UE(:code_ue)";
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':code_ue', $code_ue);

    if ($stmt->execute()) {
        $con->commit();
        echo json_encode(["status" => "success", "message" => "UE supprimée avec succès !"]);
    } else {
        throw new Exception("Échec de la suppression.");
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'UE: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>

