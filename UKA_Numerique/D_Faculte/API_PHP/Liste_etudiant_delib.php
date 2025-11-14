<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$promotion = $_SESSION['code_prom'];
$annee_acad = $_SESSION['id_annee_acad'];

$con->beginTransaction();

try {
    $sql = "CALL Liste_etudiant_deliberation(:promo_code, :annee_acad)";
    $stmt = $con->prepare($sql);

    $stmt->bindParam(':promo_code', $promotion);
    $stmt->bindParam(':annee_acad', $annee_acad);
    
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