<?php 

include("../../../Connexion_BDD/Connexion_1.php");

$rqt = "WITH 
-- Total des paiements effectués dans la dernière année académique
TotalGlobal AS (
    SELECT SUM(pf.Montant_paie) AS Total_Paye_Annee
    FROM payer_frais pf
    JOIN frais fr ON pf.idFrais = fr.idFrais
    WHERE fr.idAnnee_Acad = (SELECT MAX(idAnnee_Acad) FROM annee_academique)
),

-- Total recettes prévues globales (seulement pour budgets avec id_filiere)
TotalGlobalRecettes AS (
    SELECT SUM(f.Montant * ep.Nombre_Etudiants) AS Total_Global_Recettes
    FROM t_recettes_prevues r
    JOIN t_budget b ON r.Ref_budget = b.Ref_budget
    JOIN promotion p ON r.IdPromotion = p.Code_Promotion
    JOIN annee_academique aa ON b.Annee_debut = aa.Annee_debut
    JOIN frais f ON f.Code_Promotion = p.Code_Promotion AND f.idAnnee_Acad = aa.idAnnee_Acad
    JOIN (
        SELECT Code_Promotion, idAnnee_academique, COUNT(Etudiant_Matricule) AS Nombre_Etudiants
        FROM passer_par
        GROUP BY Code_Promotion, idAnnee_academique
    ) AS ep ON ep.Code_Promotion = p.Code_Promotion AND ep.idAnnee_academique = aa.idAnnee_Acad
    WHERE aa.idAnnee_Acad = (SELECT MAX(idAnnee_Acad) FROM annee_academique)
      AND b.id_filiere IS NOT NULL
),

-- Total dépenses prévues globales (seulement pour budgets avec id_filiere)
TotalGlobalDepenses AS (
    SELECT SUM(dp.Montant) AS Total_Global_Depenses
    FROM t_depense_prevues dp
    JOIN t_budget b ON dp.Ref_budget = b.Ref_budget
    JOIN annee_academique aa ON b.Annee_debut = aa.Annee_debut
    WHERE aa.idAnnee_Acad = (SELECT MAX(idAnnee_Acad) FROM annee_academique)
      AND b.id_filiere IS NOT NULL
),

-- Total recettes prévues par filière
RecettesParFiliere AS (
    SELECT 
        b.id_filiere,
        SUM(f.Montant * ep.Nombre_Etudiants) AS Total_Recettes
    FROM t_recettes_prevues r
    JOIN t_budget b ON r.Ref_budget = b.Ref_budget
    JOIN promotion p ON r.IdPromotion = p.Code_Promotion
    JOIN annee_academique aa ON b.Annee_debut = aa.Annee_debut
    JOIN frais f ON f.Code_Promotion = p.Code_Promotion AND f.idAnnee_Acad = aa.idAnnee_Acad
    JOIN (
        SELECT Code_Promotion, idAnnee_academique, COUNT(Etudiant_Matricule) AS Nombre_Etudiants
        FROM passer_par
        GROUP BY Code_Promotion, idAnnee_academique
    ) AS ep ON ep.Code_Promotion = p.Code_Promotion AND ep.idAnnee_academique = aa.idAnnee_Acad
    WHERE aa.idAnnee_Acad = (SELECT MAX(idAnnee_Acad) FROM annee_academique)
    GROUP BY b.id_filiere
),

-- Total dépenses prévues par filière
DepensesParFiliere AS (
    SELECT 
        b.id_filiere,
        SUM(dp.Montant) AS Total_Depenses
    FROM t_depense_prevues dp
    JOIN t_budget b ON dp.Ref_budget = b.Ref_budget
    JOIN annee_academique aa ON b.Annee_debut = aa.Annee_debut
    WHERE aa.idAnnee_Acad = (SELECT MAX(idAnnee_Acad) FROM annee_academique)
    GROUP BY b.id_filiere
)

-- Requête finale : tout combiner
SELECT 
    f.Libelle_Filiere,
    fr.Devise,
    SUM(pf.Montant_paie) AS Total_Paye,
    ROUND((SUM(pf.Montant_paie) / tg.Total_Paye_Annee) * 100, 2) AS Pourcentage_Paye,
    COALESCE(rf.Total_Recettes, 0) AS Total_Recettes_Prevues,
    COALESCE(ROUND((rf.Total_Recettes / tgr.Total_Global_Recettes) * 100, 2), 0) AS Pourcentage_Recettes,
    COALESCE(dp.Total_Depenses, 0) AS Total_Depenses_Prevues,
    COALESCE(ROUND((dp.Total_Depenses / tgd.Total_Global_Depenses) * 100, 2), 0) AS Pourcentage_Depenses
FROM 
    payer_frais pf
JOIN etudiant e ON pf.Matricule = e.Matricule
JOIN frais fr ON pf.idFrais = fr.idFrais
JOIN annee_academique aa ON fr.idAnnee_Acad = aa.idAnnee_Acad
JOIN promotion p ON fr.Code_Promotion = p.Code_Promotion
JOIN mentions m ON p.idMentions = m.idMentions
JOIN filiere f ON m.IdFiliere = f.IdFiliere
JOIN TotalGlobal tg
LEFT JOIN RecettesParFiliere rf ON rf.id_filiere = f.IdFiliere
LEFT JOIN DepensesParFiliere dp ON dp.id_filiere = f.IdFiliere
JOIN TotalGlobalRecettes tgr
JOIN TotalGlobalDepenses tgd
WHERE 
    fr.idAnnee_Acad = (SELECT MAX(idAnnee_Acad) FROM annee_academique)
GROUP BY 
    f.Libelle_Filiere,
    fr.Devise,
    rf.Total_Recettes,
    dp.Total_Depenses,
    tg.Total_Paye_Annee,
    tgr.Total_Global_Recettes,
    tgd.Total_Global_Depenses
ORDER BY 
    f.Libelle_Filiere;";


    $stmt=$con->prepare($rqt);


      $stmt->execute();
    
    
    $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
//$bdd->close();

 ?>