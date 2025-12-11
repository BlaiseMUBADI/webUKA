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
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Frais Académiques' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Montant,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Mi-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_1,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Grande-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_2,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Deuxième-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_3,
    COUNT(payer_frais.Motif_paie) AS Nombre_Paiements
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
    frais ON frais.Code_Promotion = promotion.Code_Promotion AND frais.idAnnee_Acad=:annee -- Filtre sur l'année académique
LEFT JOIN 
    payer_frais ON payer_frais.idFrais = frais.idFrais AND payer_frais.Matricule = etudiant.Matricule
JOIN 
    annee_academique ON annee_academique.idAnnee_Acad = passer_par.idAnnee_academique AND passer_par.idAnnee_academique=:annee -- Assurez-vous que cette jointure est bien présente
WHERE 
    promotion.Code_Promotion =:codepromo
GROUP BY 
    etudiant.Matricule, etudiant.Nom, etudiant.Postnom, etudiant.Prenom, promotion.Abréviation, mentions.Libelle_mention
ORDER BY 
    etudiant.Nom, etudiant.Postnom, etudiant.Prenom";

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




/*SELECT 
    etudiant.Matricule AS Matricule,
    etudiant.Nom,
    etudiant.Postnom,
    etudiant.Prenom,
    CONCAT(promotion.Abréviation, ' ', mentions.Libelle_mention) AS Prom,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Frais Académiques' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Montant,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Mi-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_1,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Grande-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_2,
    ROUND(SUM(CASE WHEN payer_frais.Motif_paie = 'Enrôlement à la Deuxième-Session' THEN payer_frais.Montant_paie ELSE 0 END), 2) AS Enrol_S_3
FROM etudiant,payer_frais,mentions,promotion,passer_par,annee_academique,frais,filiere
where
   etudiant.Matricule=passer_par.Etudiant_Matricule
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

order by etudiant.Nom,etudiant.Postnom,etudiant.Prenom";*/
?>
