<?php
    session_start();
    //include("../../D_Generale/Connexion.php");
    include("../../../Connexion_BDD/Connexion_1.php");

    $con->beginTransaction();
    
try
{   
    
    
    $taux=$_POST['taux'];
    $date_modif=$_POST['date_modif'];

        //echo " ===> montant à inserer = $montant_inserer et le montantrestant = $montant_payer";
        
    $sql_insert_paiement = "INSERT INTO taux_du_jours(Montant_du_jour,Date_modification) 
        VALUES ($taux,'$date_modif')";

    $stmt = $con->prepare($sql_insert_paiement);     
    if($stmt->execute()) echo "Ok";
    else echo "faux";
        

    $con->commit();
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de l'insertion: " . $e->getMessage();
}




?>

