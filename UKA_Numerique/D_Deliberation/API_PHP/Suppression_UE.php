<?php
    session_start();
    
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $code_ue=$_POST['code_ue'];

    // Ici récuperaion de JSON envoyé depuis javascript
    $con->beginTransaction();

try
{
    $sql_sup ="DELETE FROM unite_enseignement WHERE Code_ue=:code_ue" ;
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':code_ue', $code_ue);
    
    if($stmt->execute()) echo "Ok";
    else echo "faux";
    $con->commit();
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de la suppresion de l'UE' " . $e->getMessage();
}

?>

