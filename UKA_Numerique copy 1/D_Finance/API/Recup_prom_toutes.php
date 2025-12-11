<?php
include("../../../Connexion_BDD/Connexion_1.php");

$id_Filiere=$_GET['IdFiliere'];
$id_Annee=$_GET['idannee'];
//echo " je suis dans min fichier";

$sql_select_acces=" SELECT distinct promotion.Code_Promotion as cd_prom, 
promotion.Abréviation as abv,
mentions.Libelle_mention as lib_mention 

from promotion,mentions,filiere,frais

 where promotion.IdMentions=mentions.IdMentions
 and mentions.IdFiliere=filiere.IdFiliere
 and promotion.Code_Promotion=frais.Code_Promotion
 and frais.idAnnee_Acad=:idAnnee
 and filiere.IdFiliere=:idFiliere order by promotion.Abréviation asc";

    $stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':idFiliere',$id_Filiere);
    $stmt->bindParam(':idAnnee',$id_Annee);
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

