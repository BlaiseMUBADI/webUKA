<?php 

//include('connexion.php');
include("../../Connexion_BDD/Connexion_1.php");


//recuperation pour changement des option
$selectedElement1 = $_GET['id_mention1'];
$systeme = $_GET['systeme'];
 //echo "je suis dans donnée 1".$systeme;
//echo $selectedElement1;
//$sql = "SELECT  mentions.Libelle_mention as nom_mention, mentions.idMentions as id_mention from  mentions WHERE mentions.IdFiliere=:idFiliere  ORDER BY mentions.Libelle_mention ASC";
$sql = "SELECT DISTINCT idMentions,Libelle_mention from  mentions WHERE mentions.IdFiliere='3'  ORDER BY mentions.Libelle_mention ASC";


$sql="";
if ($systeme=="Ancien systeme") {
    $sql = "SELECT DISTINCT idMentions,Libelle_mention from  mentions WHERE mentions.IdFiliere=:idFiliere AND NOT mentions.Cycle_mention LIKE '%LMD%' ORDER BY mentions.Libelle_mention ASC";
} else
{
    $sql = "SELECT DISTINCT idMentions,Libelle_mention from  mentions WHERE mentions.IdFiliere=:idFiliere AND mentions.Cycle_mention LIKE '%LMD%' ORDER BY mentions.Libelle_mention ASC";
}
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