<?php

include("../../../Connexion_BDD/Connexion_1.php");

        $sql = "SELECT agent.Mat_agent,agent.Nom_agent,agent.Post_agent,
                agent.Prenom, agent.Sexe,
                agent.Grade
                FROM agent
                ORDER BY agent.Nom_agent,agent.Post_agent, agent.Prenom";

        $tos=$con->prepare($sql);
        $tos->execute();

        $data = array();
        while ($row = $tos->fetch()) 
        {
                $data[] = $row;
        }

        // renvoie de la reponse JSON
        echo json_encode($data);

?>