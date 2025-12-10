<?php

include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$code_ue=$_GET['code_ue'];


$sql_FA="SELECT COUNT(unite_enseignement.Code_ue) as nb_ue
from unite_enseignement 
WHERE unite_enseignement.Code_ue=:code_ue";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':code_ue',$code_ue);
$stmt->execute();

$tab_json[]=array();
while($ligne = $stmt->fetch()) $tab_json[]=$ligne;
//Renvoyer les resultats sous forme de JSOn
//header('Content-Type: application/json');
echo json_encode($tab_json);
//echo $etudiant;
        

    


?>

