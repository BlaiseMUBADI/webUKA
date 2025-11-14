 <?php

 include("../../../Connexion_BDD/Connexion_1.php");

$id_budget = $_GET['Id_recette'];
$sql_supprimer_recette="DELETE FROM `t_recettes_prevues` WHERE `Id_recette`='$id_budget'";
    $stmt=$con->prepare($sql_supprimer_recette);

      $stmt->execute();
    
?>