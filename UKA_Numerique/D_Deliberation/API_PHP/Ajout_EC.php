<?php
    session_start();
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $code_ue=$_POST['code_ue'];
    $code_prom=$_POST['code_prom'];
    $nom_ec=$_POST['nom_ec'];
    $credit=$_POST['credit'];
    $hr_td=$_POST['hr_td'];
    $hr_tp=$_POST['hr_tp'];
    $cmi=$_POST['CMI'];
    $tpe=$_POST['TPE'];
    $vht=$_POST['VHT'];
    
    $con->beginTransaction();
    
    
 /*
           "code_ue=" + code_ue_ec
                  + "&code_prom=" + cmb_promotion_FAC.value
                  + "&nom_ec=" + txt_nom_ec.value
                  + "&credit=" + txt_nb_credit.value
                  + "&hr_td=" + txt_hr_td.value
                  + "&hr_tp=" + txt_hr_tp.value
                   id_ec	Code_ue	Code_Promotion	Intutile_ec	Hr_TD	Hr_TP	Credit	
          */
    
try
{
   $sql_insert = "INSERT INTO element_constutifs
   (Code_ue
   ,Code_Promotion
   ,Intutile_ec
   ,CMI
   ,Hr_TD
   ,Hr_TP
   ,TPE
   ,VHT
   ,Credit
   )
   VALUES (:code_ue
   ,:code_promo
   ,:nom_ec
   ,:cmi
   ,:hr_td
   ,:hr_tp
   ,:tpe
   ,:vht
   ,:credit)";
    
    $stmt = $con->prepare($sql_insert);
    
    $stmt->bindParam(':code_ue', $code_ue);
    $stmt->bindParam(':code_promo', $code_prom);
    $stmt->bindParam(':nom_ec', $nom_ec);
    $stmt->bindParam(':cmi', $cmi);
    $stmt->bindParam(':hr_td',$hr_td);
    $stmt->bindParam(':hr_tp', $hr_tp);
    $stmt->bindParam(':tpe', $tpe);
    $stmt->bindParam(':vht', $vht);
    $stmt->bindParam(':credit', $credit);
    
    
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


