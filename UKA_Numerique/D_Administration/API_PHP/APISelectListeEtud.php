<?php
include("../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$IdAnne = $_GET['Id_annee'];
$CodePromo = $_GET['code_pro'];


$sql = "SELECT etudiant.Matricule, etudiant.Nom,etudiant.Postnom,etudiant.Prenom,etudiant.Sexe
,etudiant.LieuNaissance,etudiant.DateNaissance, autreinfo_etudiant.EtatCiv, autreinfo_etudiant.NomPere, 
autreinfo_etudiant.NomMere,autreinfo_etudiant.Nationalite, autreinfo_etudiant.ProvinceOrigine, 
autreinfo_etudiant.Territoire, autreinfo_etudiant.AdresseActuelle,autreinfo_etudiant.TelResponsable
,autreinfo_etudiant.NumDiplom, autreinfo_etudiant.PourceDiplome, autreinfo_etudiant.OptionEtude
,autreinfo_etudiant.SetionEtude,autreinfo_etudiant.Lieudelivrance ,autreinfo_etudiant.Datedelivrance
,autreinfo_etudiant.Ecole ,autreinfo_etudiant.Province
FROM etudiant, passer_par, annee_academique, promotion, autreinfo_etudiant
WHERE
etudiant.Matricule=passer_par.Etudiant_Matricule
AND passer_par.Code_Promotion=:codepromo
AND passer_par.idAnnee_academique=:annee
AND passer_par.Code_Promotion=promotion.Code_Promotion
AND annee_academique.idAnnee_Acad=passer_par.idAnnee_academique
AND etudiant.Matricule=autreinfo_etudiant.Matricule
ORDER BY etudiant.Nom,etudiant.Postnom,etudiant.Prenom";

$stmt=$con->prepare($sql);
$stmt->bindParam(":annee",$IdAnne);
$stmt->bindParam(":codepromo",$CodePromo);
$stmt->execute();

$data = array();
while ($row = $stmt->fetch()) {
    $data[] = $row;

}

// renvoie de la reponse JSON
echo json_encode($data);


?>