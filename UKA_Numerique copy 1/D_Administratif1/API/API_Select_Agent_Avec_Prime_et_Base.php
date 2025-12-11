<?php

include("../../../Connexion_BDD/Connexion_1.php");


$sql = "SELECT agent.Mat_agent, agent.Nom_agent, agent.Post_agent,
       agent.Prenom, agent.Sexe, agent.Grade
FROM agent
INNER JOIN mecanisation ON agent.Mat_agent = mecanisation.MatriculeAgent
WHERE mecanisation.Libelle IN ('prime', 'base')
GROUP BY agent.Mat_agent, agent.Nom_agent, agent.Post_agent,
         agent.Prenom, agent.Sexe, agent.Grade
HAVING COUNT(DISTINCT mecanisation.Libelle) = 2
ORDER BY agent.Grade, agent.Nom_agent, agent.Post_agent, agent.Prenom";

$tos=$con->prepare($sql);
$tos->execute();

$donnees = array();
while ($row = $tos->fetch()) {
    $donnees[] = $row;


}

// renvoie de la reponse JSON
echo json_encode($donnees);

?>