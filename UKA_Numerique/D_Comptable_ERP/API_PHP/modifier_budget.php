<?php  
 //include('connexion.php') ;
include("../../../Connexion_BDD/Connexion_1.php");

$Ref_budget = $_GET['id'];
$libelle = $_GET['libelle'];
$description = $_GET['description'];       
$Periodicite = $_GET['Periodicite'];       
$Annee_debut = $_GET['Annee_debut'];       
$Annee_fin = $_GET['Annee_fin'];       
$service = $_GET['service'];       
//echo $action." ".$id_annee_acad." ".$code_promo;

$sql_update= 
  "UPDATE t_budget 
         SET
         Libelle=:lebelle
         Description=:description
         Periodicite=:Periodicite
         Annee_debut=:Annee_debut
         Annee_fin=:Annee_fin
         Idservice=:Idservice
         WHERE t_budget.Ref_budget=:Ref_budget"; 

$stmt = $con->prepare($sql_update);

    $stmt->bindParam(':lebelle', $libelle);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':Periodicite', $Periodicite);
    $stmt->bindParam(':Annee_debut', $Annee_debut);
    $stmt->bindParam(':Annee_fin', $Annee_fin);
    $stmt->bindParam(':service', $service);
    $stmt->bindParam(':Ref_budget', $Ref_budget);
    
   // echo "la veulleur de sql :".$sql_update;


    $stmt->execute();


     $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
?>