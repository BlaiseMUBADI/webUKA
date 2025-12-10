<?php
 include("../../../Connexion_BDD/Connexion_1.php");


 // 2. Récupérer la dernière année académique
$anneeQuery = $con->query("SELECT MAX(idAnnee_Acad) AS last_year FROM annee_academique");
$anneeRow = $anneeQuery->fetch(PDO::FETCH_ASSOC);
$lastAnnee = $anneeRow['last_year'];

// 3. Exécution de la requête principale pour récupérer les rubriques et les montants
$sql = "
    SELECT 
        ru.Libelle AS Libelle_rubrique,
        ROUND(SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)), 2) AS Montant_rubrique_global
    FROM 
        repartition r
    JOIN rubrique ru ON ru.Id_rubrique = r.Id_rubrique
    JOIN promotion p ON p.Code_Promotion = r.Code_Promotion
    JOIN passer_par pp ON pp.Code_Promotion = p.Code_Promotion AND pp.idAnnee_academique = r.idAnnee_Acad
    JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
    JOIN frais fr ON fr.idFrais = pf.idFrais 
                  AND fr.Code_Promotion = pp.Code_Promotion 
                  AND fr.idAnnee_Acad = pp.idAnnee_academique
    JOIN (
        SELECT 
            Code_Promotion,
            idAnnee_Acad,
            SUM(Montant) AS TotalMontant
        FROM 
            repartition
        WHERE 
            idAnnee_Acad = $lastAnnee
        GROUP BY 
            Code_Promotion, idAnnee_Acad
    ) AS repartition_total 
        ON repartition_total.Code_Promotion = r.Code_Promotion 
        AND repartition_total.idAnnee_Acad = r.idAnnee_Acad
    WHERE 
        r.idAnnee_Acad = $lastAnnee
        AND pf.Motif_paie = 'Frais Académiques'
    GROUP BY 
        ru.Id_rubrique, ru.Libelle
    ORDER BY 
        ru.Libelle
";

// 4. Exécution de la requête
$stmt = $con->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Créer dynamiquement les tableaux PHP
$labels = []; // Tableau des libellés
$data = []; // Tableau des montants

foreach ($rows as $row) {
    $labels[] = htmlspecialchars($row['Libelle_rubrique']); // Libellé de la rubrique
    $data[] = $row['Montant_rubrique_global']; // Montant de la rubrique
}

// 6. Encodage des tableaux en JSON pour utilisation en JavaScript
//$labels_json = json_encode($labels);
//$data_json = json_encode($data);

// Crée un tableau associatif contenant les deux tableaux
$response = array(
    'labels' => $labels,
    'data' => $data
);

// Encode le tableau en JSON et l'affiche
echo json_encode($response);
?>
    


