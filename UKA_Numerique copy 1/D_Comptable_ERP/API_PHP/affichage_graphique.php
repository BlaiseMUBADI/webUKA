<?php
include("../../../Connexion_BDD/Connexion_1.php"); // Connexion à la base

// Récupérer la dernière année académique
$anneeQuery = $con->query("SELECT MAX(idAnnee_Acad) AS last_year FROM annee_academique");
$anneeRow = $anneeQuery->fetch(PDO::FETCH_ASSOC);
$lastAnnee = $anneeRow['last_year'];

// Vérifier si une année académique est définie par l'utilisateur
$anneeAcademique = isset($_GET['annee_academique']) ? intval($_GET['annee_academique']) : $lastAnnee;

$sql = "SELECT 
            ru.Id_rubrique,
            ru.Libelle,
            COALESCE(rg.Montant, 0) AS Montant_recette_generale,
            ROUND(SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)), 2) AS Montant_paye_etudiant
        FROM 
            rubrique ru
        LEFT JOIN recette_generale rg ON rg.Id_rubrique = ru.Id_rubrique
        LEFT JOIN t_budget tb ON rg.Ref_budget = tb.Ref_budget
        LEFT JOIN repartition r ON ru.Id_rubrique = r.Id_rubrique
        LEFT JOIN passer_par pp ON pp.Code_Promotion = r.Code_Promotion AND pp.idAnnee_academique = tb.Annee_debut
        LEFT JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
        LEFT JOIN frais fr ON fr.idFrais = pf.idFrais 
                            AND fr.Code_Promotion = pp.Code_Promotion 
                            AND fr.idAnnee_Acad = pp.idAnnee_academique
        LEFT JOIN (
            SELECT 
                Code_Promotion,
                idAnnee_Acad,
                SUM(Montant) AS TotalMontant
            FROM 
                repartition
            WHERE 
                idAnnee_Acad = $anneeAcademique
            GROUP BY 
                Code_Promotion, idAnnee_Acad
        ) AS repartition_total 
            ON repartition_total.Code_Promotion = r.Code_Promotion 
            AND repartition_total.idAnnee_Acad = r.idAnnee_Acad
        WHERE tb.Annee_debut = $anneeAcademique
        GROUP BY ru.Id_rubrique, ru.Libelle
        ORDER BY ru.Libelle";

$stmt = $con->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($rows);
?>
