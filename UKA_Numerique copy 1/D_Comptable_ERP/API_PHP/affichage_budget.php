<?php 
include("../../../Connexion_BDD/Connexion_1.php");
$action=$_GET['action'] ?? 'rien';
if ($action=='affichage_recette_general') {

    $id=$_GET['id'];
    $sql_affiche_recette="SELECT
    r.Id_rubrique,
    r.Libelle,
    COALESCE(rg.Montant, 0) AS Montant,
    CASE 
        WHEN total.TotalMontant > 0 THEN 
            ROUND(COALESCE(rg.Montant, 0) * 100.0 / total.TotalMontant, 2)
        ELSE NULL
    END AS Pourcentage
FROM 
    rubrique r
LEFT JOIN 
    recette_generale rg
    ON r.Id_rubrique = rg.Id_rubrique AND rg.Ref_budget = :ref_budget
LEFT JOIN (
    SELECT 
        Ref_budget,
        SUM(Montant) AS TotalMontant
    FROM 
        recette_generale
    WHERE 
        Ref_budget = :ref_budget
) total ON total.Ref_budget = :ref_budget
ORDER BY 
    CASE WHEN rg.Id_rubrique IS NOT NULL THEN 0 ELSE 1 END,  -- Priorité aux rubriques liées à recette
    r.Id_rubrique;  -- Puis tri par Id_rubrique
";
$stmt=$con->prepare($sql_affiche_recette);
$stmt->bindParam(':ref_budget', $id);

}else if ($action=='affichage_depense_general') {
      $id=$_GET['id'];
    $sql_affiche_recette="SELECT
    i.Num_imputation,
    i.Intitul_compte,
    COALESCE(d.Montant, 0) AS Montant,
    CASE 
        WHEN total.TotalMontant > 0 THEN 
            ROUND(COALESCE(d.Montant, 0) * 100.0 / total.TotalMontant, 2)
        ELSE NULL
    END AS Pourcentage
FROM 
    t_imputation i
LEFT JOIN 
    t_depense_generale d
    ON i.Num_imputation = d.Num_imputation AND d.Ref_budget = :ref_budget
LEFT JOIN (
    SELECT 
        Ref_budget,
        SUM(Montant) AS TotalMontant
    FROM 
        t_depense_generale
    WHERE 
        Ref_budget = :ref_budget
) total ON total.Ref_budget = :ref_budget
ORDER BY 
    CASE WHEN d.Num_imputation IS NOT NULL THEN 0 ELSE 1 END,  -- Priorité aux imputations ayant des dépenses
    i.Num_imputation;  -- Puis tri par Num_imputation
;  -- Puis tri par Id_rubrique
";
$stmt=$con->prepare($sql_affiche_recette);
$stmt->bindParam(':ref_budget', $id);

 }else if ($action=='enregistrement_recette_fonctionnement') {
    
    $Ref_budget=$_GET['Ref_budget'];
    $montant=$_GET['montant'];
    $num_rubrique=$_GET['num_rubrique'];

    $rqt_enregistrement_recette_generale="INSERT INTO recette_generale (Id_rubrique, Ref_budget, Montant)
VALUES (:id_rubrique, :ref_budget, :montant)
ON DUPLICATE KEY UPDATE Montant = :montant;
";

    $stmt=$con->prepare($rqt_enregistrement_recette_generale);
    $stmt->bindParam(':ref_budget', $Ref_budget);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':id_rubrique', $num_rubrique);
}
else if ($action=='enregistrement_depense_fonctionnement') {
    
    $Ref_budget=$_GET['Ref_budget'];
    $montant=$_GET['montant'];
    $num_imputation=$_GET['num_imputation'];

    $rqt_enregistrement_depense_generale="INSERT INTO t_depense_generale (Num_imputation, Ref_budget, Montant)
VALUES (:num_imputation, :ref_budget, :montant)
ON DUPLICATE KEY UPDATE Montant = :montant;
";

    $stmt=$con->prepare($rqt_enregistrement_depense_generale);
    $stmt->bindParam(':ref_budget', $Ref_budget);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':num_imputation', $num_imputation);
}
else{
$sql_select_acces="SELECT 
    t_budget.Ref_budget,
    t_budget.Libelle AS libelle_budget,
    t_budget.Description,
    t_budget.Periodicite,
    t_budget.Annee_debut,
    t_budget.Annee_fin,

    -- Affiche fac si filière, sinon libellé service
    CASE 
        WHEN t_budget.id_filiere IS NOT NULL THEN CONCAT('Fac. ', filiere.Libelle_Filiere)
        ELSE CONCAT('Ser. ', service.Libelle)
    END AS Service_Concerne,

    -- Identifiant de la source (service ou filière)
    CASE 
        WHEN t_budget.id_filiere IS NOT NULL THEN filiere.IdFiliere
        ELSE service.IdService
    END AS Identifiant_Source,

    -- Type : Fac. ou Ser.
    CASE 
        WHEN t_budget.id_filiere IS NOT NULL THEN 'Fac.'
        ELSE 'Ser.'
    END AS Type

FROM 
    t_budget

LEFT JOIN service ON t_budget.Idservice = service.IdService
LEFT JOIN filiere ON t_budget.id_filiere = filiere.IdFiliere

ORDER BY 
    -- Priorité : d'abord les filières
    CASE 
        WHEN t_budget.id_filiere IS NOT NULL THEN 1 
        ELSE 2 
    END,
    -- Puis ordre alphabétique de l'affichage
    Ref_budget DESC;";
    $stmt=$con->prepare($sql_select_acces);
}

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