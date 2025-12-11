<?php

include("../../../Connexion_BDD/Connexion_1.php");

$matricule =$_GET['mat'];
//echo "le matricule est ".$matricule;
        $sql = "SELECT * from parent WHERE MatriculeAgent=:matricule ";

        $tos=$con->prepare($sql);
        $tos->bindParam(':matricule', $matricule, PDO::PARAM_STR);

        $tos->execute();

        $data = array();
        while ($row = $tos->fetch()) 
        {
                $data[] = $row;
        }

        // renvoie de la reponse JSON
        echo json_encode($data);

?>