<?php
    session_start();
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $code_ec=$_POST['code_ec'];
    $code_prom=$_POST['code_prom'];
    $mat_agent=$_POST['mat_enseignant'];
    $id_annee_acad=$_POST['annee_acad'];
    $mat_ass=NULL;
    
    $con->beginTransaction();
    
    
try
{
    $sql_sup = "DELETE FROM element_constitufs_aligne 
    WHERE element_constitufs_aligne.idAnnee_Acad=:idannee_acad
    and element_constitufs_aligne.id_ec=:id_ec
    and element_constitufs_aligne.Mat_agent=:mat_agent";
        
    $stmt = $con->prepare($sql_sup);
    $stmt->bindParam(':idannee_acad', $id_annee_acad);
    //$stmt->bindParam(':mat_ass',$mat_ass);
    $stmt->bindParam(':id_ec', $code_ec);
    $stmt->bindParam(':mat_agent',$mat_agent);
            
            
    if($stmt->execute()) echo "\n\nOk Supprimer avec succès \n\n";
    else echo "\n\nimpossible de faire cet enregistrment \n\n";   
    $con->commit();
    
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de l'insertion: " . $e->getMessage();
    
}




?>


