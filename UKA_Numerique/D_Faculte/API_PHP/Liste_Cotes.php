<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que toutes les données nécessaires sont présentes
if (!isset($data['id_semestre'])) {
    echo json_encode(["status" => "error", "message" => "Données manquantes."]);
    exit;
}

$promotion = $_SESSION['code_prom'];
$id_semestre = $data['id_semestre'];

$con->beginTransaction();

try {
    $sql = "CALL Liste_cote(:promotion, :id_semestre)";
    $stmt = $con->prepare($sql);

    $stmt->bindParam(':promotion', $promotion);
    $stmt->bindParam(':id_semestre', $id_semestre);
    
    if ($stmt->execute()) {
        $cotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Fermer le curseur pour libérer les ressources
        $con->commit();
        echo json_encode($cotes);
    } else {
        throw new Exception("Échec de la récupération des cotes.");
    }
} catch (PDOException $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => "Erreur lors de la récupération: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>