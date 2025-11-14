<?php 
include("../../../Connexion_BDD/Connexion_1.php");

$idfiliere = $_GET['id_filiere'];
$text_rech = $_GET['text_rech'];
$rech_par = $_GET['rech_par'];



// Requete sql non préparée
$sql_rqt="";
if ($rech_par=="matricule") {
    $sql_rqt = $con->prepare("SELECT DISTINCT etudiant.Matricule,
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
      AND (etudiant.Matricule LIKE '%$text_rech%' or etudiant.Nom LIKE '$text_rech%') ORDER BY etudiant.Nom, etudiant.Postnom, etudiant.Prenom ASC");

}
//préparation de la reque sql et execution$stat1=$con->prepare($sql);
// $stat->bindParam(":idFiliere",$idfiliere);

 
 $sql_rqt->bindParam(':idfiliere',$idfiliere);
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