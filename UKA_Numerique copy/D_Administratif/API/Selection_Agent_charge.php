<?php

include("../../../Connexion_BDD/Connexion_1.php");


$sql = "SELECT 
    agent.Mat_agent, 
    agent.Nom_agent, 
    agent.Post_agent,
    agent.Prenom, 
    agent.Grade,
    MAX(CASE WHEN mecanisation.Observation = '1' THEN mecanisation.Libelle ELSE NULL END) AS Base,
    MAX(CASE WHEN mecanisation.Observation = '2' THEN mecanisation.Libelle ELSE NULL END) AS Prime,
    CASE 
        WHEN MAX(CASE WHEN mecanisation.Observation = '1' THEN mecanisation.Libelle ELSE NULL END) IS NOT NULL 
        AND MAX(CASE WHEN mecanisation.Observation = '2' THEN mecanisation.Libelle ELSE NULL END) IS NOT NULL 
        THEN 'Base et Prime'
        WHEN MAX(CASE WHEN mecanisation.Observation = '1' THEN mecanisation.Libelle ELSE NULL END) IS NOT NULL 
        THEN 'Base'
        WHEN MAX(CASE WHEN mecanisation.Observation = '2' THEN mecanisation.Libelle ELSE NULL END) IS NOT NULL 
        THEN 'Prime'
        ELSE 'Aucune'
    END AS Type_Observation
FROM 
    agent 
    
LEFT JOIN 
    mecanisation ON agent.Mat_agent = mecanisation.MatriculeAgent
WHERE 
    agent.Grade IS NOT NULL and agent.type_agent=1
GROUP BY 
    agent.Mat_agent, agent.Nom_agent, agent.Post_agent, agent.Prenom, agent.Grade
ORDER BY 
    agent.Grade, agent.Nom_agent, agent.Post_agent, agent.Prenom";


$tos=$con->prepare($sql);
$tos->execute();

$donnees = array();
while ($row = $tos->fetch()) {
    $donnees[] = $row;


}

// renvoie de la reponse JSON
echo json_encode($donnees);

?>