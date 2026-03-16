<?php
//include("../../D_Generale/Connexion.php");
include("../../../Connexion_BDD/Connexion_1.php");

//echo " je suis dans min fichier";

$sql_select_acces="SELECT taux_du_jours.Id_Taux_du_jours as id_taux,
taux_du_jours.Montant_du_jour as montant,
taux_du_jours.Date_modification  as date_mod
FROM taux_du_jours order by taux_du_jours.Date_modification DESC limit 1";

    $stmt=$con->prepare($sql_select_acces);
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

