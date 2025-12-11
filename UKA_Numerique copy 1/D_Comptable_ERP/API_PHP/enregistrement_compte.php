<?php  
 //include('connexion.php') ;
include("../../../Connexion_BDD/Connexion_1.php");
echo $_GET['input_compte'];
if ($_GET['input_compte']=="") {
        # code...


$numero_compte = $_GET['numero_compte'];
$intitule_compte = addslashes($_GET['intitule_compte']);       
$Pourcentage = $_GET['Pourcentage'];     

    



$sql_insert_t_compte= 
  "INSERT INTO 
  t_imputation(
    Num_imputation,
    Intitul_compte, 
    Pourcent_budget)
    VALUES ( '$numero_compte','$intitule_compte','$Pourcentage')"; 

 $stmt1=$con->prepare($sql_insert_t_compte);
 
 $stmt1->execute();
}else {



$numero_compte = $_GET['numero_compte'];
$intitule_compte = addslashes($_GET['intitule_compte']);       
$Pourcentage = $_GET['Pourcentage'];       
//echo $action." ".$id_annee_acad." ".$code_promo;



$sql_update="UPDATE `t_compte` SET `Intitul_compte`=:Intitul_compte,`Pourcent_budget`=:Pourcent_budget WHERE `Num_compte`=:Num_compte";



$stmt=$con->prepare($sql_update);


    $stmt->bindParam(':Intitul_compte', $intitule_compte);
    $stmt->bindParam(':Pourcent_budget', $Pourcentage);
    $stmt->bindParam(':Num_compte', $numero_compte);
    
   // echo "la veulleur de sql :".$sql_update;


    $stmt->execute();


     $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
}

?>