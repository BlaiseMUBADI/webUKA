<?php

include("../../../Connexion_BDD/Connexion_1.php");


$mat = $_GET['mat'];

//echo "le matricule est ".$matricule;
        $sql = "SELECT * from enfant WHERE Mat_agent=:matricul ";

        $tos=$con->prepare($sql);
        $tos->bindParam(':matricul', $mat, PDO::PARAM_STR);

        $tos->execute();

        $data = array();
        while ($row = $tos->fetch()) 
        {
                $data[] = $row;
        }

        // renvoie de la reponse JSON
        echo json_encode($data);

?>