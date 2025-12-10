<?php 
include("../../../Connexion_BDD/Connexion_1.php");

$action = $_GET['action'] ?? 'rien';

if ($action=='depense_generale') {
	$annee_debut = $_GET['Annee_debut'];

$sql_select="SELECT 
    i.Num_imputation,
    i.Intitul_compte,
    SUM(dp.Montant) AS Montant_Total
FROM 
    t_depense_prevues dp
JOIN 
    t_imputation i ON dp.Num_imputation = i.Num_imputation
JOIN 
    t_budget b ON dp.Ref_budget = b.Ref_budget
WHERE 
    b.Annee_debut = :annee
GROUP BY 
    i.Num_imputation, i.Intitul_compte
ORDER BY 
    i.Num_imputation ASC;";
    $stmt=$con->prepare($sql_select);
    $stmt->bindParam(':annee', $annee_debut);

}else{
$id_budget = $_GET['id'];

$sql_select_acces="SELECT t_depense_prevues.Id_depense,
				t_depense_prevues.Montant,
				t_imputation.Num_imputation,
				t_imputation.Intitul_compte,
				t_budget.Libelle,
				SUM(t_depense_prevues.Montant) AS Total_Montant
				FROM
				t_budget, t_imputation, t_depense_prevues
				where t_depense_prevues.Ref_budget=t_budget.Ref_budget AND t_depense_prevues.Num_imputation=t_imputation.Num_imputation AND  t_budget.Ref_budget=:id

				GROUP BY 
			    t_depense_prevues.Id_depense,
			    t_depense_prevues.Montant,
			    t_imputation.Num_imputation,
			    t_imputation.Intitul_compte,
			    t_budget.Libelle
    
				  order by Id_depense desc";
    $stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':id', $id_budget);

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