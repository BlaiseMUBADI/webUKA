<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données nécessaires sont présentes
if (!isset($data['idAnnee_Acad']) || !isset($data['id_ec']) || !isset($data['Id_Semestre']) || !isset($data['Code_Promotion']) || !isset($data['Mat_agent'])) {
    echo json_encode(["status" => "error", "message" => "Données manquantes."]);
    exit;
}

$idAnnee_Acad = $data['idAnnee_Acad'];
$id_ec = $data['id_ec'];
$Id_Semestre = $data['Id_Semestre'];
$Code_Promotion = $data['Code_Promotion'];
$Mat_agent = $data['Mat_agent'];
$Mat_assistant = isset($data['Mat_assistant']) ? $data['Mat_assistant'] : null;

try {
    $con->beginTransaction();

    $sql_ajout = "CALL Nouvel_EC_Aligne(:idAnnee_Acad, :id_ec, :Id_Semestre, :Code_Promotion, :Mat_agent, :Mat_assistant)";
    $stmt = $con->prepare($sql_ajout);
    $stmt->bindParam(':idAnnee_Acad', $idAnnee_Acad);
    $stmt->bindParam(':id_ec', $id_ec);
    $stmt->bindParam(':Id_Semestre', $Id_Semestre);
    $stmt->bindParam(':Code_Promotion', $Code_Promotion);
    $stmt->bindParam(':Mat_agent', $Mat_agent);
    $stmt->bindParam(':Mat_assistant', $Mat_assistant);

    if ($stmt->execute()) {
        if ($con->inTransaction()) {
            $con->commit();
        }
        echo json_encode(["status" => "success", "message" => "Élément constitutif aligné ajouté avec succès !"]);
    } else {
        throw new Exception("Échec de l'ajout.");
    }
} catch (PDOException $e) {
    if ($con->inTransaction()) {
        $con->rollback();
    }
    echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout de l'élément constitutif aligné : " . $e->getMessage()]);
} catch (Exception $e) {
    if ($con->inTransaction()) {
        $con->rollback();
    }
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>