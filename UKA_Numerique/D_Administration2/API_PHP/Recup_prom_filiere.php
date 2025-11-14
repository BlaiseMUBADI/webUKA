<?php
include("../../../Connexion_BDD/Connexion_1.php");

$mat_etudaint=$_GET['idFiliere'];
//echo " je suis dans min fichier";

$sql_select_acces="
SELECT promotion.Code_Promotion as cd_prom, 
promotion.Abréviation as abv,
mentions.Libelle_mention as lib_mention 

from promotion,mentions,filiere

 where promotion.IdMentions=mentions.IdMentions
 and mentions.IdFiliere=filiere.IdFiliere
 and filiere.IdFiliere=:idFiliere order by promotion.Abréviation asc";

    $stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':idFiliere',$mat_etudaint);
    $stmt->execute();
    
    
    $filiere=array();
    while($ligne = $stmt->fetch())
    {
        $filiere[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
    echo json_encode($filiere);
    //echo $etudiant;
        

?>

