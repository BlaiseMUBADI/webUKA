<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$promotion = $_SESSION['code_prom'];
$annee_acad = $_SESSION['id_annee_acad'];
$data = json_decode(file_get_contents('php://input'), true);
$id_semestre = $data['id_semestre'];

$con->beginTransaction();

try {
    $sql = "CALL Liste_EC_Aligne_Delibe(:promo_code, :annee_acad,:id_semestre)";
    $stmt = $con->prepare($sql);

    $stmt->bindParam(':promo_code', $promotion);
    $stmt->bindParam(':annee_acad', $annee_acad);
    $stmt->bindParam(':id_semestre', $id_semestre);
    
    if ($stmt->execute()) {
        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Fermer le curseur pour libérer les ressources
        $con->commit();
        echo json_encode($etudiants);
    } else {
        throw new Exception("Échec de la récupération des étudiants.");
    }
} catch (PDOException $e) {
    $con->rollback();
    echo json_encode(["message" => "Erreur lors de la récupération: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["message" => $e->getMessage()]);
}
?>