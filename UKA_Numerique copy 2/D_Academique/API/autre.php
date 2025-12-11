<?php 

include("../../../Connexion_BDD/Connexion_1.php");

$matricule = $_GET['matricule'];
//echo "nous la";


 $rqt_sql = "SELECT annee_academique.Annee_debut, annee_academique.Annee_fin,
    promotion.Abréviation,,passer_par.Mention1,
    passer_par.Mention2
    FROM etudiant, passer_par, annee_academique, promotion, filiere, mentions 
    WHERE etudiant.Matricule=passer_par.Etudiant_Matricule 
    and annee_academique.idAnnee_Acad=passer_par.idAnnee_academique 
    and passer_par.Code_Promotion=promotion.Code_Promotion
    and promotion.idMentions=mentions.idMentions
    and mentions.IdFiliere=filiere.IdFiliere
    and etudiant.Matricule='02058 I/15/KAN'";
  //echo json_encode($etud);

    $stmt1=$con->prepare($rqt_sql);
    //$stmt->bindParam(':mat',$matricule);
    $stmt1->execute();
   

   $data = array();
while ($row = $stat1->fetch()) {
    $data[] = $row;

}

// renvoie de la reponse JSON
echo json_encode($data);

    
?>