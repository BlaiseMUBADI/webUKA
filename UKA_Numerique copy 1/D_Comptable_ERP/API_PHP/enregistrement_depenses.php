<?php  
 //include('connexion.php') ;
include("../../../Connexion_BDD/Connexion_1.php");


$budget = addslashes($_GET['budget']);
$id_compte = addslashes($_GET['id_compte']);       
$montant = $_GET['montant']; 
    

//echo $action." ".$id_annee_acad." ".$code_promo;


$sql_insert_t_budget= 
  "INSERT INTO 
  t_depense_prevues(
    Id_depense,
    Ref_budget, 
    Num_imputation, 
    Montant)
    VALUES ( '','$budget','$id_compte','$montant')"; 

 $stmt1=$con->prepare($sql_insert_t_budget);
 
 $stmt1->execute();


?>