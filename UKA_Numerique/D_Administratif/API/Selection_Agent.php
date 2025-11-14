<?php

include("../../../Connexion_BDD/Connexion_1.php");


$sql = "SELECT agent.Mat_agent, agent.Nom_agent, agent.Post_agent,
       agent.Prenom, agent.Sexe,
       agent.Grade, agent.Lieu, agent.DateNaissance,
       agent.Niveau_Etude, agent.Tel, agent.Mail, agent.EtatCivil, agent.Domaine
      
       
FROM agent
WHERE agent.Grade IS NOT NULL and agent.type_agent=1
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