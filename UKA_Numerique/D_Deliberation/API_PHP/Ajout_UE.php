<?php
    session_start();
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $id_semestre=$_POST['id_semestre'];
    $lib_ue=$_POST['libelle_ue'];
    $code_ue=$_POST['code_ue'];
    $id_filiere=$_SESSION['id_fac'];
    $categorie_ue=$_POST['categorie_ue'];
    
    $con->beginTransaction();
    
    

    
try
{
   $sql_insert = "INSERT INTO unite_enseignement
    (
    Code_ue
    ,IdFiliere
    ,Id_Semestre
    ,Intitule_ue
    ,Catégorie
    ) 
    VALUES (:code_ue,:id_fac,:id_semestre,:lib_ue,:cat_ue)";
    
    $stmt = $con->prepare($sql_insert);
    
    $stmt->bindParam(':code_ue', $code_ue);
    $stmt->bindParam(':id_semestre', $id_semestre);
    $stmt->bindParam(':lib_ue', $lib_ue);
    $stmt->bindParam(':id_fac', $id_filiere);
    $stmt->bindParam(':cat_ue', $categorie_ue);
    
    
    if($stmt->execute()) echo "\n\nOk\n\n";
    else echo "\n\nimpossible de faire cet enregistrment \n\n";
    $con->commit();
    
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de l'insertion: " . $e->getMessage();
    
}




?>


