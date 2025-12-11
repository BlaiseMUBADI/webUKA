<?php
include("../../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$IdAnne = $_GET['Id_annee_acad'];
$CodePromo = $_GET['code_promo'];
//echo"le code promo------------$CodePromo";

$sql = "SELECT 
    etudiant.Matricule AS Matricule,
    etudiant.Nom,
    etudiant.Postnom,
    etudiant.Prenom,
    CONCAT(promotion.Abréviation, ' ', mentions.Libelle_mention) AS Prom,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Frais Académiques' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS FA,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Mi-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_1,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Grande-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_2,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Deuxiè-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_2
FROM 
    etudiant
JOIN 
    passer_par ON etudiant.Matricule = passer_par.Etudiant_Matricule
JOIN 
    promotion ON passer_par.Code_Promotion = promotion.Code_Promotion
JOIN 
    mentions ON promotion.idMentions = mentions.idMentions
JOIN 
    filiere ON mentions.IdFiliere = filiere.IdFiliere
JOIN 
    payer_frais ON etudiant.Matricule = payer_frais.Matricule
JOIN 
    frais ON payer_frais.idFrais = frais.idFrais
JOIN 
    annee_academique ON frais.idAnnee_Acad = annee_academique.idAnnee_Acad
WHERE 
    annee_academique.idAnnee_Acad =:anne
    AND promotion.Code_Promotion =:codepromo 
GROUP BY 
    etudiant.Matricule, etudiant.Nom, etudiant.Postnom, etudiant.Prenom,
    promotion.Abréviation, mentions.Libelle_mention
ORDER BY 
    etudiant.Nom, etudiant.Postnom";

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