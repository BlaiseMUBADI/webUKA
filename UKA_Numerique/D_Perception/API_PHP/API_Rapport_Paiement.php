<?php
session_start(); 

include("../../../Connexion_BDD/Connexion_1.php");




$Mat_agent=$_SESSION['MatriculeAgent'];
$Id_lieu_paiement=$_GET['Id_lieu_paiement'];
$Id_filiere=$_GET['Id_filiere'];
$Date_debut=$_GET['Date_debut'];
$Date_fin=$_GET['Date_fin'];
$Id_annee_academique=$_GET['Id_annee_acad'];

$Nom_agent=$_SESSION['Nom_user']." ".$_SESSION['Postnom_user'];
//echo "la date debut est ".$Date_debut;
$sql_rapport="SELECT  
etudiant.Matricule AS mat_etudiant, 
etudiant.Nom, etudiant.Postnom, etudiant.Prenom,
ROUND(SUM(CASE WHEN frais.Libelle_Frais = 'Enrôlement à la Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol,
ROUND(SUM(CASE WHEN frais.Libelle_Frais = 'Frais Académiques' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS FA,
promotion.Abréviation AS promo,
GROUP_CONCAT(DISTINCT frais.Libelle_Frais SEPARATOR ', ') AS Lib_frais
FROM 
agent,
    payer_frais,
    passer_par,
    annee_academique,
    promotion,
    mentions,
    filiere,
    frais,
    lieu_paiement,etudiant


WHERE 
    etudiant.Matricule=payer_frais.Matricule
and payer_frais.Matricule=passer_par.Etudiant_Matricule 
and passer_par.idAnnee_academique=annee_academique.idAnnee_Acad 
and passer_par.Code_Promotion=promotion.Code_Promotion
and promotion.idMentions=mentions.idMentions
and mentions.IdFiliere=filiere.IdFiliere
and payer_frais.Mat_agent=agent.Mat_agent 
and payer_frais.idFrais=frais.idFrais
and frais.idAnnee_Acad=annee_academique.idAnnee_Acad
and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement
and agent.Mat_agent = :mat_agent
AND frais.idAnnee_Acad = :id_annee 
AND DATE(payer_frais.Date_paie) BETWEEN :date_debut AND :date_fin
AND filiere.IdFiliere = :Id_filiere
AND lieu_paiement.idLieu_paiement = :id_lieu_paie
AND payer_frais.Fc IS NULL
GROUP BY 
etudiant.Matricule, etudiant.Nom, etudiant.Postnom, etudiant.Prenom, 
promotion.Abréviation
ORDER BY 
etudiant.Nom, etudiant.Postnom, etudiant.Prenom";



$stmt=$con->prepare($sql_rapport);    
$stmt->bindParam(':mat_agent',$Mat_agent);
$stmt->bindParam(':id_annee',$Id_annee_academique);
$stmt->bindParam(':date_debut',$Date_debut);
$stmt->bindParam(':date_fin',$Date_fin);
$stmt->bindParam(':Id_filiere',$Id_filiere);
$stmt->bindParam(':id_lieu_paie',$Id_lieu_paiement);
$stmt->execute();

$tab_rapport_FA=array();


while($ligne = $stmt->fetch()) 
{
//$devise=$ligne['Devise'];
$tab_rapport_FA[]=$ligne;
}
echo json_encode($tab_rapport_FA);

?>