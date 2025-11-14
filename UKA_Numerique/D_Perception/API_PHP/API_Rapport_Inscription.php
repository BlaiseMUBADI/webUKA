<?php
session_start(); 
include("../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$IdAnne = $_GET['annee_acad'];
$CodePromo = $_GET['promo'];
$Datepaie = $_GET['datepaie'];
$mat_agent=$_SESSION['MatriculeAgent'] ;


//echo"le code promo------------$CodePromo";
//echo"le code anné------------$IdAnne";
//echo"la date est------------$Datepaie";

$sql = "SELECT etudiant.Matricule as Matricule
,etudiant.Nom, etudiant.Postnom,etudiant.Prenom,etudiant.Sexe
,CONCAT(promotion.Abréviation,' ',mentions.Libelle_mention) as Prom
,payer_frais.Montant_paie as Montant
,payer_frais.Motif_paie as libelle


from etudiant,payer_frais,mentions,promotion,passer_par,annee_academique,frais,filiere,agent

where etudiant.Matricule=passer_par.Etudiant_Matricule
and passer_par.Code_Promotion=promotion.Code_Promotion
and promotion.idMentions=mentions.idMentions
and mentions.IdFiliere=filiere.IdFiliere
and etudiant.Matricule=payer_frais.Matricule
and payer_frais.idFrais=frais.idFrais
and frais.idAnnee_Acad=annee_academique.idAnnee_Acad
and frais.Code_Promotion=promotion.Code_Promotion
and payer_frais.Mat_agent=agent.Mat_agent 
and agent.Mat_agent = :mat_agent
and annee_academique.idAnnee_Acad=:annee
and promotion.Code_Promotion=:codepromo
AND DATE(payer_frais.Date_paie)=:Datepaie


GROUP BY etudiant.Matricule,etudiant.Nom, etudiant.Postnom,etudiant.Prenom,
payer_frais.Montant_paie,payer_frais.Motif_paie
order by etudiant.Nom,etudiant.Postnom,etudiant.Prenom";

$tos=$con1->prepare($sql);
$tos->bindParam(":annee",$IdAnne);
$tos->bindParam(":codepromo",$CodePromo);
$tos->bindParam(":Datepaie",$Datepaie);
$tos->bindParam(":mat_agent",$mat_agent);
$tos->execute();

$data = array();
while ($row = $tos->fetch()) {
    $data[] = $row;


}

// renvoie de la reponse JSON
echo json_encode($data);

?>