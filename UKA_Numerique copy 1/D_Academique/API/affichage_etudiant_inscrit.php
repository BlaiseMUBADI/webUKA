<?php 
include("../../../Connexion_BDD/Connexion_1.php");

$idfiliere = $_GET['id_filiere'];
$text_rech = $_GET['text_rech'];
$code_promo = $_GET['promtion'];
$annee = $_GET['annee_acad'];



// Requete sql non préparée
$sql_rqt="";

    $sql_rqt = $con1->prepare("SELECT DISTINCT etudiant.Matricule,
      etudiant.Nom,
      etudiant.Postnom,
      etudiant.Prenom,
      etudiant.Sexe,
      etudiant.LieuNaissance,
      etudiant.DateNaissance 

      FROM etudiant,passer_par,promotion,mentions,filiere,annee_academique
      WHERE 
      etudiant.Matricule=passer_par.Etudiant_Matricule 
      AND passer_par.Code_Promotion=promotion.Code_Promotion 
      AND promotion.idMentions=mentions.idMentions 
      AND passer_par.idAnnee_academique=annee_academique.idAnnee_Acad
      AND mentions.IdFiliere=:idfiliere
      AND annee_academique.idAnnee_Acad =:idannee
      AND promotion.Code_Promotion =:codepromo
      AND (etudiant.Matricule LIKE '%$text_rech%' or etudiant.Nom LIKE '$text_rech%') ORDER BY etudiant.Nom, etudiant.Postnom, etudiant.Prenom ASC");

//préparation de la reque sql et execution$stat1=$con->prepare($sql);
// $stat->bindParam(":idFiliere",$idfiliere);

 
 $sql_rqt->bindParam(':idfiliere',$idfiliere);
 $sql_rqt->bindParam(':idannee',$annee);
 $sql_rqt->bindParam(':codepromo',$code_promo);
 //$SQ->bindParam(':text_rech',$text_rech);

      $sql_rqt->execute();
     
    $etud=array();
    while($ligne = $sql_rqt->fetch())
    {
        $etud[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
    echo json_encode($etud);
// echo json_encode($data);

?>