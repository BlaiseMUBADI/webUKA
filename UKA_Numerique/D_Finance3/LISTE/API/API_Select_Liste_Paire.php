<?php
include("../../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$IdAnne = $_GET['Id_annee_acad'];
$CodePromo = $_GET['code_promo'];
//echo"le code promo------------$CodePromo";

$sql = "SELECT etudiant.Matricule as Matricule
,etudiant.Nom, etudiant.Postnom,etudiant.Prenom
,CONCAT(promotion.Abréviation,' ',mentions.Libelle_mention) as Prom
,ROUND(SUM(payer_frais.Montant_paie),2) as Montant
,payer_frais.motif_paie as libelle


from etudiant,payer_frais,mentions,promotion,passer_par,annee_academique,frais,filiere

where etudiant.Matricule=passer_par.Etudiant_Matricule
and passer_par.Code_Promotion=promotion.Code_Promotion
and promotion.idMentions=mentions.idMentions
and mentions.IdFiliere=filiere.IdFiliere
and etudiant.Matricule=payer_frais.Matricule
and payer_frais.idFrais=frais.idFrais
and frais.idAnnee_Acad=annee_academique.idAnnee_Acad
and frais.Code_Promotion=promotion.Code_Promotion
and annee_academique.idAnnee_Acad=:annee
and promotion.Code_Promotion=:codepromo


GROUP BY etudiant.Matricule,promotion.Abréviation,mentions.Libelle_mention
,payer_frais.Montant,payer_frais.motif_paie
order by etudiant.Nom,etudiant.Postnom,etudiant.Prenom";

$tos=$con->prepare($sql);
$tos->bindParam(":annee",$IdAnne);
$tos->bindParam(":codepromo",$CodePromo);
$tos->execute();

$data = array();
while ($row = $tos->fetch()) {
    $data[] = $row;


}

// renvoie de la reponse JSON
echo json_encode($data);


?>