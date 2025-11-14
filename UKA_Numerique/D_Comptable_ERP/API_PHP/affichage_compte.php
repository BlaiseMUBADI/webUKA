 <?php

 include("../../../Connexion_BDD/Connexion_1.php");


$sql_select_compte="SELECT * FROM t_imputation order by Num_imputation ASC";
    $stmt=$con->prepare($sql_select_compte);


      $stmt->execute();
    
    
    $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
?>