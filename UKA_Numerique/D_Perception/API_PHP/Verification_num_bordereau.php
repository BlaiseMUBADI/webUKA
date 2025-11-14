<?php

include("../../../Connexion_BDD/Connexion_1.php");

$num_bordereau=$_GET['num_bordereau'];


$sql_FA="
select COUNT(payer_frais.Id_payer_frais) as nb_num_bordereau 
from payer_frais 
WHERE payer_frais.Numero_bordereau=:num_bord";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':num_bord',$num_bordereau);
$stmt->execute();

$tab_json[]=array();
while($ligne = $stmt->fetch()) $tab_json[]=$ligne;
//Renvoyer les resultats sous forme de JSOn
header('Content-Type: application/json');
echo json_encode($tab_json);
//echo $etudiant;
        

    


?>

