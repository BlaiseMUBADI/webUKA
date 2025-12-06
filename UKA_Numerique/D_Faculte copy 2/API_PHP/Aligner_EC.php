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
    $sql_select = "SELECT COUNT(*) as nb_ec 
    FROM element_constitufs_aligne
    WHERE idAnnee_Acad = :idannee_acad 
      AND id_ec = :id_ec
      AND Mat_agent = :mat_agent";

    $res = $con->prepare($sql_select);
    $res->execute([
    ":idannee_acad" => $id_annee_acad,
    ":id_ec" => $code_ec,
    ":mat_agent" => $mat_agent
    ]);

    $nb_ec = $res->fetchColumn();
    echo " Regarde nombre ligne ".$nb_ec;

    if($nb_ec== 0)
    {
        $sql_insert = "INSERT INTO 
        element_constitufs_aligne
        (idAnnee_Acad,
        Mat_assistant,
        id_ec,
        Mat_agent) 
        VALUES 
        (
        :idannee_acad,
        :mat_ass,
        :id_ec,
        :mat_agent)";
        
        $stmt = $con->prepare($sql_insert);
            
        $stmt->bindParam(':idannee_acad', $id_annee_acad);
        $stmt->bindParam(':mat_ass',$mat_ass);
        $stmt->bindParam(':id_ec', $code_ec);
        $stmt->bindParam(':mat_agent',$mat_agent);
            
            
        if($stmt->execute()) echo "\n\nOk\n\n";
        else echo "\n\nimpossible de faire cet enregistrment \n\n";

    }
    else echo "Il y'a une correspondance ";
    



   
    $con->commit();
    
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de l'insertion: " . $e->getMessage();
    
}




?>


