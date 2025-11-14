<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données nécessaires sont présentes
if (!isset($data['matricule']) || !isset($data['id_ec_aligne']) || !isset($data['cote'])) {
    echo json_encode(["status" => "error", "message" => "Données manquantes."]);
    exit;
}

$matricule = $data['matricule'];
$id_ec_aligne = $data['id_ec_aligne'];
$cote = $data['cote'];

try {
    $con->beginTransaction();

    $sql_insert = "CALL Modifier_cote(:matricule, :id_ec_aligne, :cote)";
    $stmt = $con->prepare($sql_insert);
    $stmt->bindParam(':matricule', $matricule);
    $stmt->bindParam(':id_ec_aligne', $id_ec_aligne);
    $stmt->bindParam(':cote', $cote);

    if ($stmt->execute()) {
        if ($con->inTransaction()) {
            $con->commit();
        }
        echo json_encode(["status" => "success", "message" => "Côte modifiée avec succès !"]);
    } else {
        throw new Exception("Échec de l'insertion.");
    }
} catch (PDOException $e) {
    if ($con->inTransaction()) {
        $con->rollback();
    }
    echo json_encode(["status" => "error", "message" => "Erreur lors de la modification : " . $e->getMessage()]);
} catch (Exception $e) {
    if ($con->inTransaction()) {
        $con->rollback();
    }
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>