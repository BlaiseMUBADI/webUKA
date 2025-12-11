<?php
include("../../../Connexion_BDD/Connexion_1.php");

$annee = $_GET['annee'];

if ( isset($_GET['action']) AND $_GET['action']=='bedget_general') {
    $rqt = "
        SELECT 
    fi.Libelle_Filiere,
    fr.Libelle_Frais,
    SUM(fr.Montant * etudiant_count.Nombre_Etudiants) AS Montant_Total
FROM 
    t_recettes_prevues r
JOIN 
    promotion p ON r.IdPromotion = p.Code_Promotion
JOIN 
    mentions m ON p.idMentions = m.idMentions
JOIN 
    filiere fi ON m.IdFiliere = fi.IdFiliere
JOIN 
    t_budget b ON r.Ref_budget = b.Ref_budget
JOIN 
    annee_academique aa ON b.Annee_debut = aa.Annee_debut
JOIN 
    frais fr ON fr.Code_Promotion = p.Code_Promotion
             AND fr.idAnnee_Acad = aa.idAnnee_Acad
JOIN 
    (
        SELECT 
            Code_Promotion, 
            idAnnee_academique, 
            COUNT(Etudiant_Matricule) AS Nombre_Etudiants
        FROM 
            passer_par
        GROUP BY 
            Code_Promotion, idAnnee_academique
    ) AS etudiant_count ON etudiant_count.Code_Promotion = p.Code_Promotion
                         AND etudiant_count.idAnnee_academique = aa.idAnnee_Acad
WHERE 
    b.Annee_debut = :annee
GROUP BY 
    fi.Libelle_Filiere, fr.Libelle_Frais
ORDER BY 
    fi.Libelle_Filiere ASC, fr.Libelle_Frais ASC;

    ";
}else{
if (isset($_GET['annee'])) {
    
    $rqt = "
        SELECT 
            Ref_budget, 
            Libelle AS libelle1, 
            Periodicite
        FROM t_budget
        WHERE Annee_debut = :annee
        ORDER BY Ref_budget DESC
    ";
    }
}
    $stmt=$con->prepare($rqt);
    $stmt->bindParam(':annee', $annee);
    $stmt->execute();

    //$rows = $stmt->fetchAll();

    
     $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
    

