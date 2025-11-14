<?php
    session_start();
    
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $code_ec=$_POST['code_ec'];
    echo "code EC est ".$code_ec;


    // Ici récuperaion de JSON envoyé depuis javascript
    $con->beginTransaction();

try
{
    $sql_sup ="DELETE FROM element_constutifs WHERE id_ec=:code_ec" ;
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':code_ec', $code_ec);
    if($stmt->execute()) echo "Ok";
    else echo "faux";
    $con->commit();
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de la suppresion de l'EC' " . $e->getMessage();
}

?>

