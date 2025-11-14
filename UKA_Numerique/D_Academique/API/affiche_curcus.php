<?php 
include("../../../Connexion_BDD/Connexion_1.php");

$matricule = $_GET['matricule'];
$vale = $_GET['vale'];
//echo "nous la";


 $rqt_sql = $con->prepare("SELECT etudiant.Matricule, annee_academique.idAnnee_Acad,
   annee_academique.Annee_debut, annee_academique.Annee_fin,promotion.Abréviation,
   promotion.Code_Promotion,mentions.idMentions,mentions.idMentions,mentions.Libelle_mention,
   passer_par.Decision_jury,passer_par.Session1,passer_par.Mention1, passer_par.Session2,
   passer_par.Mention2
    FROM etudiant, passer_par, annee_academique, promotion, filiere, mentions 
    WHERE etudiant.Matricule=passer_par.Etudiant_Matricule 
    and annee_academique.idAnnee_Acad=passer_par.idAnnee_academique 
    and passer_par.Code_Promotion=promotion.Code_Promotion
    and promotion.idMentions=mentions.idMentions
    and mentions.IdFiliere=filiere.IdFiliere
    and etudiant.Matricule=:matricule ORDER BY annee_academique.Annee_debut ASC");


  
    $rqt_sql->bindParam(':matricule',$matricule);
    $rqt_sql->execute();
   

$data = array();
while ($row = $rqt_sql->fetch()) {
    $data[] = $row;
}

// renvoie de la reponse JSON
echo json_encode($data);
    
?>