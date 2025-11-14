<?php
include("../../../Connexion_BDD/Connexion_1.php");

// Récupération des variables
$Idfiliere = $_GET['idfiliere'];
$id_Annee = $_GET['Id_annee_acad'];
$id_lieu = $_GET['Id_lieu'];
$code_promo = $_GET['Code_promo'];

$data = array();

if ($code_promo == "Toutes") {
    // Requête pour les montants en USD
    $sql = "SELECT payer_frais.Motif_paie AS Lib_frais,
                   ROUND(SUM(payer_frais.Montant_paie), 2) AS Montant
            FROM payer_frais
            JOIN passer_par ON payer_frais.Matricule = passer_par.Etudiant_Matricule
            JOIN annee_academique ON passer_par.idAnnee_academique = annee_academique.idAnnee_Acad
            JOIN promotion ON passer_par.Code_Promotion = promotion.Code_Promotion
            JOIN mentions ON promotion.idMentions = mentions.idMentions
            JOIN filiere ON mentions.IdFiliere = filiere.IdFiliere
            JOIN frais ON payer_frais.idFrais = frais.idFrais AND frais.idAnnee_Acad = annee_academique.idAnnee_Acad
            JOIN lieu_paiement ON payer_frais.idLieu_paiement = lieu_paiement.idLieu_paiement
            JOIN etudiant ON etudiant.Matricule = payer_frais.Matricule
            WHERE payer_frais.Fc IS NULL 
              AND frais.idAnnee_Acad = :idanne
              AND filiere.IdFiliere = :idfiliere
              AND payer_frais.idLieu_paiement = :idlieu
            GROUP BY payer_frais.Motif_paie";

    $tos = $con->prepare($sql);
    $tos->bindParam(":idfiliere", $Idfiliere);
    $tos->bindParam(":idanne", $id_Annee);
    $tos->bindParam(":idlieu", $id_lieu);
    $tos->execute();

    while ($row = $tos->fetch()) {
        $data[] = $row;
    }

    // Requête pour les montants en Francs Congolais (CDF)
    $sql = "SELECT payer_frais.Motif_paie AS Lib_frais_FC,
                   ROUND(SUM(payer_frais.Fc), 2) AS Montant_FC
            FROM payer_frais
            JOIN passer_par ON payer_frais.Matricule = passer_par.Etudiant_Matricule
            JOIN annee_academique ON passer_par.idAnnee_academique = annee_academique.idAnnee_Acad
            JOIN promotion ON passer_par.Code_Promotion = promotion.Code_Promotion
            JOIN mentions ON promotion.idMentions = mentions.idMentions
            JOIN filiere ON mentions.IdFiliere = filiere.IdFiliere
            JOIN frais ON payer_frais.idFrais = frais.idFrais AND frais.idAnnee_Acad = annee_academique.idAnnee_Acad
            JOIN lieu_paiement ON payer_frais.idLieu_paiement = lieu_paiement.idLieu_paiement
            JOIN etudiant ON etudiant.Matricule = payer_frais.Matricule
            WHERE payer_frais.Fc IS NOT NULL 
              AND frais.idAnnee_Acad = :idanne
              AND filiere.IdFiliere = :idfiliere
              AND payer_frais.idLieu_paiement = :idlieu
            GROUP BY payer_frais.Motif_paie";

    $tos = $con->prepare($sql);
    $tos->bindParam(":idfiliere", $Idfiliere);
    $tos->bindParam(":idanne", $id_Annee);
    $tos->bindParam(":idlieu", $id_lieu);
    $tos->execute();

    while ($row = $tos->fetch()) {
        $data[] = $row;
    }

} else {
    // Requête avec filtre de promotion
    $sql = "SELECT payer_frais.Motif_paie AS Lib_frais,
                   ROUND(SUM(payer_frais.Montant_paie), 2) AS Montant
            FROM payer_frais
            JOIN passer_par ON payer_frais.Matricule = passer_par.Etudiant_Matricule
            JOIN annee_academique ON passer_par.idAnnee_academique = annee_academique.idAnnee_Acad
            JOIN promotion ON passer_par.Code_Promotion = promotion.Code_Promotion
            JOIN mentions ON promotion.idMentions = mentions.idMentions
            JOIN filiere ON mentions.IdFiliere = filiere.IdFiliere
            JOIN frais ON payer_frais.idFrais = frais.idFrais AND frais.idAnnee_Acad = annee_academique.idAnnee_Acad
            JOIN lieu_paiement ON payer_frais.idLieu_paiement = lieu_paiement.idLieu_paiement
            JOIN etudiant ON etudiant.Matricule = payer_frais.Matricule
            WHERE frais.idAnnee_Acad = :idanne
              AND filiere.IdFiliere = :idfiliere
              AND frais.Code_Promotion = :idpromo
              AND lieu_paiement.idLieu_paiement = :idlieu
            GROUP BY payer_frais.Motif_paie";

    $tos = $con->prepare($sql);
    $tos->bindParam(":idfiliere", $Idfiliere);
    $tos->bindParam(":idanne", $id_Annee);
    $tos->bindParam(":idlieu", $id_lieu);
    $tos->bindParam(":idpromo", $code_promo);
    $tos->execute();

    while ($row = $tos->fetch()) {
        $data[] = $row;
    }
}

// Retourne la réponse JSON une seule fois après toutes les requêtes
echo json_encode($data);
?>
