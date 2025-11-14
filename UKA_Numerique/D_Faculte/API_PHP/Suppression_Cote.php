<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données nécessaires sont présentes
if (!isset($data['matricule']) || !isset($data['id_ec_aligne'])) {
    echo json_encode(["status" => "error", "message" => "Données manquantes."]);
    exit;
}

$matricule = $data['matricule'];
$id_ec_aligne = $data['id_ec_aligne'];

$con->beginTransaction();

try {
    $sql_sup = "CALL Supprimer_cote(:mat_etudiant,:id_ec_aligne)";
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':mat_etudiant', $matricule);
    $stmt->bindParam(':id_ec_aligne', $id_ec_aligne);

    if ($stmt->execute()) {
        $con->commit();
        echo json_encode(["status" => "success", "message" => "Côte supprimée avec succès"]);
    } else {
        throw new Exception("Échec de la suppression.");
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de la côte: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>