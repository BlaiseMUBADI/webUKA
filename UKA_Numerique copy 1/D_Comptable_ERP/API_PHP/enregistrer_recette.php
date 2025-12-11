<?php  
 //include('connexion.php') ;
include("../../../Connexion_BDD/Connexion_1.php");



$id_budget = $_GET['id_budget'];
$promotion = $_GET['promotion'];
    

//echo $action." ".$id_annee_acad." ".$code_promo;



$sql_insert_t_recette= "INSERT INTO `t_recettes_prevues`(`Id_recette`, `Designation`, `IdPromotion`, `Id_type_Recette`, `Ref_budget`) VALUES ('','','$promotion','1','$id_budget')"; 

 $stmt1=$con->prepare($sql_insert_t_recette);
 
 $stmt1->execute();


    $stmt->execute();


   //  $etud1=array();
   // while($ligne = $stmt->fetch())
   // {
    //    $etud1[]=$ligne;

    //}

    //Renvoyer les resultats sous forme de json
   //echo json_encode($etud1);

?>