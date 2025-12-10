<?php
include("../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$Matricule = $_GET['matEtud'];
$Idfiliere = $_GET['IdFil'];
//echo"le code promo------------$Matricule";

$sql = "SELECT promotion.Libelle_promotion,
        annee_academique.Annee_debut, 
        annee_academique.Annee_fin,
        annee_academique.idAnnee_Acad,
        promotion.Code_Promotion,
        passer_par.Session1, 
        passer_par.Mention1, 
        passer_par.Session2, 
        passer_par.Mention2,
        passer_par.Decision_jury,
        promotion.Abréviation,
        filiere.Libelle_Filiere

FROM passer_par, 
    annee_academique, 
    promotion,
    filiere
WHERE promotion.Code_Promotion=passer_par.Code_Promotion 
AND passer_par.idAnnee_academique=annee_academique.idAnnee_Acad 
AND passer_par.Etudiant_Matricule=:matricule
AND filiere.IdFiliere=filiere.IdFiliere
AND filiere.IdFiliere=:idfiliere

ORDER BY filiere.Libelle_Filiere,annee_academique.Annee_debut,annee_academique.Annee_fin ASC";

$tos=$con->prepare($sql);
$tos->bindParam(":matricule",$Matricule);
$tos->bindParam(":idfiliere",$Idfiliere);
$tos->execute();

$data = array();
while ($row = $tos->fetch()) {
    $data[] = $row;


}

// renvoie de la reponse JSON
echo json_encode($data);


?>