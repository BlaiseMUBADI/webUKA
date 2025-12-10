<?php

include("../../../Connexion_BDD/Connexion_1.php");


$sql = "SELECT agent.Mat_agent, agent.Nom_agent, agent.Post_agent,
       agent.Prenom, agent.Sexe, agent.Grade, mecanisation.Libelle
FROM agent, mecanisation
WHERE agent.Mat_agent = mecanisation.MatriculeAgent
  AND mecanisation.Libelle = 'base'
  AND NOT EXISTS (
      SELECT 1
      FROM mecanisation m2
      WHERE m2.MatriculeAgent = agent.Mat_agent
        AND m2.Libelle = 'prime' and agent.type_agent=1
  );


ORDER BY 
   agent.Grade, agent.Nom_agent, agent.Post_agent,
   agent.Prenom";

$tos=$con->prepare($sql);
$tos->execute();

$donnees = array();
while ($row = $tos->fetch()) {
    $donnees[] = $row;


}

// renvoie de la reponse JSON
echo json_encode($donnees);

?>