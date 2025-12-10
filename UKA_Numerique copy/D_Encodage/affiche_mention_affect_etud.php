<?php  //include('connexion.php') ;

include("../../Connexion_BDD/Connexion_1.php");

//recuperation pour changement des option
$selectedElement1 = $_GET['element1'];

$systeme = $_GET['systeme'];
 //echo "je suis dans donnée 1".$systeme;
$sql="";
if ($systeme=="Ancien systeme") {
    $sql = "SELECT promotion.Code_Promotion, mentions.Libelle_mention as nom_mention,mentions.idMentions as id_mention, concat(promotion.Abréviation,' - ',mentions.Libelle_mention) as Promtion from promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE filiere.IdFiliere=:idFiliere AND NOT promotion.Abréviation LIKE '%LMD%' ORDER BY promotion.Abréviation ASC";
} else
{
   $sql = "SELECT promotion.Code_Promotion, mentions.Libelle_mention as nom_mention,mentions.idMentions as id_mention, concat(promotion.Abréviation,' - ',mentions.Libelle_mention) as Promtion from promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE filiere.IdFiliere=:idFiliere AND promotion.Abréviation LIKE '%LMD%' ORDER BY promotion.Abréviation ASC"; 
}
// Query database for selected element
//$sql = "SELECT * FROM t_promotion WHERE id_fac = '$selectedElement'";

$stat=$con->prepare($sql);

$stat->bindParam(":idFiliere",$selectedElement1);

$stat->execute();

// Fetch data and store in JSON array
$data1 = array();
while ($row = $stat->fetch()) {
    $data1[] = $row;

}

// Send JSON response
echo json_encode($data1);

?>