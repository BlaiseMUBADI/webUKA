<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$lib_ue = $_POST['libelle_ue'];
$code_ue = $_POST['code_ue'];
$id_filiere = $_SESSION['id_fac'];
$categorie_ue = $_POST['categorie_ue'];

$con->beginTransaction();

try {
    $sql_insert = "CALL Nouvelle_UE(:code_ue, :id_filiere, :lib_ue, :cat_ue)";
    $stmt = $con->prepare($sql_insert);

    $stmt->bindParam(':code_ue', $code_ue);
    $stmt->bindParam(':lib_ue', $lib_ue);
    $stmt->bindParam(':id_filiere', $id_filiere);
    $stmt->bindParam(':cat_ue', $categorie_ue);
    
    if ($stmt->execute()) {
        $con->commit();
        echo json_encode(["message" => "UE ajoutée avec succès !"]);
    } else {
        throw new Exception("Échec de l'enregistrement.");
    }
} catch (PDOException $e) {
    $con->rollback();
    echo json_encode(["message" => "Erreur lors de l'insertion: " . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["message" => $e->getMessage()]);
}
?>


