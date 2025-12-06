<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id_filiere = $_SESSION['id_fac'];



try {
    $sql_select = "CALL Liste_Enseignants_Aligner(:idfiliere)";
    $stmt = $con->prepare($sql_select);
    $stmt->bindParam(':idfiliere', $id_filiere);
    $stmt->execute();

    $agents = array();
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $agents[] = $ligne;
        
    }
    
    // Retourner les données avec l'ID de la filière de l'utilisateur
    $response = array(
        'id_filiere_user' => $id_filiere,
        'enseignants' => $agents
    );
    
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de la récupération des agents: " . $e->getMessage()]);
}
?>