<?php 

//include('../connexion.php');
include("../../../Connexion_BDD/Connexion_1.php");


//recuperation pour changement des option
$selectedElement1 = $_GET['id_mention1'];
$systeme = $_GET['systeme'];
 //echo "je suis dans donnée 1".$systeme;

$sql = "SELECT  disctict mentions.Libelle_mention as nom_mention, mentions.idMentions as id_mention from promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE filiere.IdFiliere=:idFiliere  ORDER BY mentions.Libelle_mention ASC";

// Query database for selected element
//$sql = "SELECT * FROM t_promotion WHERE id_fac = '$selectedElement'";

$stat=$con->prepare($sql);

$stat->bindParam(":idFiliere",$selectedElement1);

$stat->execute();

// Fetch data and store in JSON array
$data2 = array();
while ($row = $stat->fetch()) {
    $data2[] = $row;

}

// Send JSON response
echo json_encode($data2);

?>