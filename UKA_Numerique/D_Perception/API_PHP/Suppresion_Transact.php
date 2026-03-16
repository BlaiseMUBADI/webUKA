<?php
    session_start();
    
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $mat_etudiant=$_POST['mat_etudiant'];
    $Id_an_acad=$_POST['Id_annee_acad'];    
    $date_paie=$_POST['date_paie'];
    $code_promo=$_POST['code_promo'];

    // Ici récuperaion de JSON envoyé depuis javascript
    $con->beginTransaction();

try
{


    /***********************************************************************************************************
     ******** CE ELESE S'EXECUTE QUE LORQUE NOUS VOULONS INSERER QUE LE FRAIS ACADEMIQUE SEUL OU ENROLEMENT ****
     ***********************************************************************************************************
     */
    
    $sql_sup_paiement = 
    " DELETE FROM 
    payer_frais
    WHERE Matricule=:mat_etudiant
    AND Date_paie=:Date_paie
    AND idFrais
    IN(
        SELECT frais.idFrais 
        FROM frais, promotion, annee_academique 
        WHERE payer_frais.idFrais=frais.idFrais 
        AND frais.Code_Promotion=promotion.Code_Promotion 
        AND frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
        AND promotion.Code_Promotion=:code_promo
        AND annee_academique.idAnnee_Acad=:id_annee_acde
        AND payer_frais.Date_paie=:Date_paie)";
    
    $stmt = $con->prepare($sql_sup_paiement);
    $stmt->bindParam(':mat_etudiant', $mat_etudiant);
    $stmt->bindParam(':Date_paie', $date_paie);
    $stmt->bindParam(':code_promo', $code_promo);
    $stmt->bindParam(':id_annee_acde', $Id_an_acad);
    
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

