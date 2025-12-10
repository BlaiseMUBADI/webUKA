<?php
include("../../../Connexion_BDD/Connexion_1.php");

//$mat_etudaint=$_GET['idFiliere'];
$id_annee_acad=$_GET['Id_annee_acad'];
$code_promo=$_GET['code_promo'];
//echo " VM ";

$sql_modalite="select frais.Montant,frais.Tranche,frais.Libelle_Frais,frais.Devise
                from frais,annee_academique,promotion
                WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
                and frais.Code_Promotion=promotion.Code_Promotion
                and annee_academique.idAnnee_Acad=:id_annee_acad
                and promotion.Code_Promotion=:code_prom";

    /*$stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':idFiliere',$mat_etudaint);
    $stmt->execute();*/

    $stmt=$con->prepare($sql_modalite);
    $stmt->bindParam(':id_annee_acad',$id_annee_acad);
    $stmt->bindParam(':code_prom',$code_promo);
    $stmt->execute();
    
    
    $modalite=array();
    while($ligne = $stmt->fetch())
    {
        $modalite[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
    echo json_encode($modalite);
    //echo $etudiant;
        

?>

