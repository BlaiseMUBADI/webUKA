<?php
    session_start();
    //include("../../D_Generale/Connexion.php");
    include("../../../Connexion_BDD/Connexion_1.php");

    $con->beginTransaction();
    
try
{   
    
    $Id_an_acad=$_POST['Id_an_acad'];
    $code_promo=$_POST['code_promo'];
    
    $motif_frais=$_POST['motif_frais'];
    $montant_fixer=$_POST['montant_fixer'];    
    $tranche_fixer=$_POST['tranche'];
    $devise=$_POST['devise'];

        //echo " ===> montant à inserer = $montant_inserer et le montantrestant = $montant_payer";
        $sql_insert_paiement = "INSERT INTO 
        frais(
        idAnnee_Acad,
        Code_Promotion,
        Libelle_Frais,
        Montant,
        Tranche,
        Devise) 
        VALUES (:id_anne,:code_prom,:lib_frais,:montant,:tranche,:devise)";

        $stmt = $con->prepare($sql_insert_paiement);
        $stmt->bindParam(':id_anne', $Id_an_acad);
        $stmt->bindParam(':code_prom', $code_promo);
        $stmt->bindParam(':lib_frais', $motif_frais);
        $stmt->bindParam(':montant',$montant_fixer);
        $stmt->bindParam(':tranche', $tranche_fixer);
        $stmt->bindParam(':devise', $devise);        
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

