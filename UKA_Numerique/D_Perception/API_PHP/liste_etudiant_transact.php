<?php
include("../../../Connexion_BDD/Connexion_1.php");

//$mat_etudaint=$_GET['idFiliere'];
$id_annee_acad=$_GET['Id_annee_acad'];
$code_promo=$_GET['code_promo'];

$verifi_date=$_GET['verifi_date'];

$sql_select_acces="";

if(@$_GET['Mot_recherche'])
{
    $txt_mot_recherche="%".$_GET['Mot_recherche']."%";
    
    $sql_select_acces="SELECT etudiant.Matricule,
      etudiant.Nom,
      etudiant.Postnom,
      etudiant.Prenom,
      etudiant.Sexe

      FROM etudiant,passer_par,promotion,mentions,filiere,annee_academique
      WHERE 
      etudiant.Matricule=passer_par.Etudiant_Matricule 
      AND passer_par.Code_Promotion=promotion.Code_Promotion 
      AND promotion.idMentions=mentions.idMentions 
      AND mentions.IdFiliere=filiere.IdFiliere 
      AND passer_par.idAnnee_academique=annee_academique.idAnnee_Acad
      AND annee_academique.idAnnee_Acad=:idannee 
      AND promotion.Code_Promotion=:code_prom
      AND (etudiant.Matricule LIKE :mot_recherche OR etudiant.Nom LIKE :mot_recherche)
      order by etudiant.Nom ASC";
      $stmt=$con->prepare($sql_select_acces);
      $stmt->bindParam(':idannee',$id_annee_acad);
      $stmt->bindParam(':code_prom',$code_promo);      
      $stmt->bindParam(':mot_recherche',$txt_mot_recherche);
      //echo(" je suis dans IF et txt est $txt_mot_recherche");

}
else
{
    $sql_select_acces="
    SELECT etudiant.Matricule,
      etudiant.Nom,
      etudiant.Postnom,
      etudiant.Prenom,
      etudiant.Sexe
    from etudiant,payer_frais,frais,promotion,annee_academique
    where etudiant.Matricule=payer_frais.Matricule
    and payer_frais.idFrais=frais.idFrais
    and frais.idAnnee_Acad=annee_academique.idAnnee_Acad
    and frais.Code_Promotion=promotion.Code_Promotion
    and annee_academique.idAnnee_Acad=:idannee 
    and promotion.Code_Promotion=:code_prom 
    GROUP by etudiant.Matricule order by etudiant.Nom ASC";

      $stmt=$con->prepare($sql_select_acces);
      $stmt->bindParam(':idannee',$id_annee_acad);
      $stmt->bindParam(':code_prom',$code_promo);
      /*
      SELECT payer_frais.Date_paie as date_paie,
payer_frais.Montant_paie as montant,
payer_frais.Motif_paie as motif_paie,
payer_frais.FROM etudiant,payer_frais,frais
      //echo(" je suis dans ELSE IF");*/

}
//echo " VM ";



    /*$stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':idFiliere',$mat_etudaint);
    $stmt->execute();*/

    
    $stmt->execute();
    
    
    $etud=array();
    while($ligne = $stmt->fetch())
    {
        $etud[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
    echo json_encode($etud);
    //echo $etudiant;
        

?>

