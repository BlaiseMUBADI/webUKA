<?php
// appel de la page de connexion
//include("Connexion.php");

include("../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$selectedElement = $_GET['element'];
$systeme = $_GET['systeme'];

// Requete sql non préparée
$sql="";
if ($systeme== "Ancien systeme") {
    $sql = "SELECT promotion.Code_Promotion, concat(promotion.Abréviation,' - ',mentions.Libelle_mention) as Promtion from promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE filiere.IdFiliere=:idFiliere AND NOT promotion.Abréviation LIKE '%LMD%' ORDER BY promotion.Abréviation ASC";
}
else if ($systeme=="LMD") 
{
   $sql = "SELECT promotion.Code_Promotion, concat(promotion.Abréviation,' - ',mentions.Libelle_mention) as Promtion from promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE filiere.IdFiliere=:idFiliere AND promotion.Abréviation LIKE '%LMD%' ORDER BY promotion.Abréviation ASC"; 
}

//préparation de la reque sql et execution
$stat1=$con->prepare($sql);
$stat1->bindParam(":idFiliere",$selectedElement);
$stat1->execute();

$data = array();
while ($row = $stat1->fetch()) {
    $data[] = $row;

}

// renvoie de la reponse JSON
echo json_encode($data);

?>