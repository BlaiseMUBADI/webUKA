<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$promotion = $_SESSION['code_prom'];
$annee_acad = $_SESSION['id_annee_acad'];

if (isset($con)) {
    $con->beginTransaction();

    try {
        $sql = "CALL Liste_etudiant_deliberation(:promo_code, :annee_acad)";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(':promo_code', $promotion);
            $stmt->bindParam(':annee_acad', $annee_acad);
            
            if ($stmt->execute()) {
                $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                $con->commit();
                echo json_encode($etudiants);
            } else {
                throw new Exception("Échec de la récupération des étudiants.");
            }
        } else {
            throw new Exception("Impossible de préparer la requête.");
        }
    } catch (PDOException $e) {
        $con->rollback();
        echo json_encode(["message" => "Erreur lors de la récupération: " . $e->getMessage()]);
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(["message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Erreur de connexion à la base de données."]);
}
?>