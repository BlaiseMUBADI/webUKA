<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

$idAnnee_Acad = $data['idAnnee_Acad'];
$id_ec = $data['id_ec'];
$Id_Semestre = $data['Id_Semestre'];
$Code_Promotion = $data['Code_Promotion'];
$Mat_agent = $data['Mat_agent'];

$con->beginTransaction();

try {
    $sql_sup = "CALL Supprimer_EC_Aligne(:idAnnee_Acad, :id_ec, :Id_Semestre, :Code_Promotion, :Mat_agent)";
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':idAnnee_Acad', $idAnnee_Acad);
    $stmt->bindParam(':id_ec', $id_ec);
    $stmt->bindParam(':Id_Semestre', $Id_Semestre);
    $stmt->bindParam(':Code_Promotion', $Code_Promotion);
    $stmt->bindParam(':Mat_agent', $Mat_agent);

    if ($stmt->execute()) {
        $con->commit();
        echo json_encode(["status" => "success", "message" => "Élément constitutif aligné supprimé avec succès !"]);
    } else {
        throw new Exception("Échec de la suppression.");
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'élément constitutif aligné : " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>