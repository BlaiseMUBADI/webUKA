<?php
    session_start();
    
    include("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données"]);
    exit;
}

    $id_compte_agent=$_POST['id_compte_agent'];

    // Ici récuperaion de JSON envoyé depuis javascript
    $con->beginTransaction();

try
{
    $sql_sup_paiement ="DELETE FROM compte_agent WHERE Id_Compte_agent=:id_compte_agent" ;
    $stmt = $con->prepare($sql_sup_paiement);
    $stmt->bindParam(':id_compte_agent', $id_compte_agent);
    
    if($stmt->execute()) echo "Ok";
    else echo "faux";
    $con->commit();
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de la suppresion de la transaction: " . $e->getMessage();
}

?>

