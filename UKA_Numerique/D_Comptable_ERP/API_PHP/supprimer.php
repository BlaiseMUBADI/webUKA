 <?php

 include("../../../Connexion_BDD/Connexion_1.php");

 $action = $_GET['action'] ?? 'rien';
if ($action=="supprimer_rubrique") {

	$id_rubrique = $_GET['id'];
	$sql_supprimer_rubrique="DELETE FROM `repartition` WHERE `Id_repartition`='$id_rubrique'";
    $stmt=$con->prepare($sql_supprimer_rubrique);
}else if ($action=="depense") {
      $Id_depense = $_GET['Id_depense'];
      $sql_supprimer_depense="DELETE FROM `t_depense_prevues` WHERE `Id_depense`='$Id_depense'";
      $stmt=$con->prepare($sql_supprimer_depense);
}


      $stmt->execute();
    
?>