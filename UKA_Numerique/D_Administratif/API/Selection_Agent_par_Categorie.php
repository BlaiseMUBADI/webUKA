<?php

include("../../../Connexion_BDD/Connexion_1.php");
$IdCategorie = $_GET['codeCat'];
$critere = $_GET['critere'];

if($critere==="-")
{
        $sql = "SELECT agent.Mat_agent,agent.Nom_agent,agent.Post_agent,
                agent.Prenom, agent.Sexe,
                agent.Grade,agent.Lieu,agent.DateNaissance,
                agent.IdCategorie,agent.AdressePhysique
                FROM agent,categorie
                WHERE agent.IdCategorie=:idcat and agent.Grade IS NOT NULL and agent.type_agent=1
                GROUP BY 
                agent.Grade,agent.Nom_agent,agent.Post_agent,
                agent.Prenom,agent.Mat_agent";

        $tos=$con->prepare($sql);
        $tos->bindParam(":idcat",$IdCategorie);
        $tos->execute();

        $data = array();
        while ($row = $tos->fetch()) 
        {
                $data[] = $row;
        }

        // renvoie de la reponse JSON
        echo json_encode($data);
}
else if($critere==="NU")
{
        $sql = "SELECT agent.Mat_agent,agent.Nom_agent,agent.Post_agent,
                agent.Prenom, agent.Sexe,
                agent.Grade,agent.Lieu,agent.DateNaissance,
                agent.IdCategorie,agent.AdressePhysique
                FROM agent,categorie
                WHERE agent.IdCategorie=:idcat AND agent.Mat_agent LIKE '%NU%'and agent.Grade IS NOT NULL and agent.type_agent=1
                GROUP BY 
                agent.Grade,agent.Nom_agent,agent.Post_agent,
                agent.Prenom,agent.Mat_agent";

        $tos=$con->prepare($sql);
        $tos->bindParam(":idcat",$IdCategorie);
        $tos->execute();

        $data = array();
        while ($row = $tos->fetch()) 
        {
                $data[] = $row;
        }

        // renvoie de la reponse JSON
        echo json_encode($data);
}
else if($critere==="Matriculé")
{
        $sql = "SELECT agent.Mat_agent,agent.Nom_agent,agent.Post_agent,
                agent.Prenom, agent.Sexe,
                agent.Grade,agent.Lieu,agent.DateNaissance,
                agent.IdCategorie,agent.AdressePhysique
                FROM agent,categorie
                WHERE agent.IdCategorie=:idcat AND agent.Mat_agent NOT LIKE '%NU%' and agent.Grade IS NOT NULL and agent.type_agent=1
                GROUP BY 
                agent.Mat_agent,agent.Nom_agent,agent.Post_agent,
                agent.Prenom,agent.Grade
                ORDER BY agent.Nom_agent,agent.Post_agent, agent.Prenom";

        $tos=$con->prepare($sql);
        $tos->bindParam(":idcat",$IdCategorie);
        $tos->execute();

        $data = array();
        while ($row = $tos->fetch()) 
        {
                $data[] = $row;
        }

        // renvoie de la reponse JSON
        echo json_encode($data);
}
?>