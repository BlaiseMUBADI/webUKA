<?php
include("../../../Connexion_BDD/Connexion_1.php");
//$mat_etudaint=$_GET['idFiliere'];
$id_annee_acad=$_GET['Id_annee_acad'];
$code_promo=$_GET['code_promo'];

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
      AND promotion.Code_Promotion=:code_prom order by etudiant.Nom ASC";
      $stmt=$con->prepare($sql_select_acces);
      $stmt->bindParam(':idannee',$id_annee_acad);
      $stmt->bindParam(':code_prom',$code_promo);
      
      //echo(" je suis dans ELSE IF");

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

