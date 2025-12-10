<?php

include("../../../Connexion_BDD/Connexion_1.php");


$sql = "SELECT 
    agent.Mat_agent, 
    agent.Nom_agent, 
    agent.Post_agent,
    agent.Prenom, 
    agent.Grade,
    MAX(CASE WHEN paie_locale.Observation = '1' THEN paie_locale.Libelle ELSE NULL END) AS Quinzaine,
    MAX(CASE WHEN paie_locale.Observation = '2' THEN paie_locale.Libelle ELSE NULL END) AS Prime_Inst,
    CASE 
        WHEN MAX(CASE WHEN paie_locale.Observation = '1' THEN paie_locale.Libelle ELSE NULL END) IS NOT NULL 
        AND MAX(CASE WHEN paie_locale.Observation = '2' THEN paie_locale.Libelle ELSE NULL END) IS NOT NULL 
        THEN 'Quinzaine et Prime_Inst'
        WHEN MAX(CASE WHEN paie_locale.Observation = '1' THEN paie_locale.Libelle ELSE NULL END) IS NOT NULL 
        THEN 'Quinzaine'
        WHEN MAX(CASE WHEN paie_locale.Observation = '2' THEN paie_locale.Libelle ELSE NULL END) IS NOT NULL 
        THEN 'Prime_Inst'
        ELSE 'Aucune'
    END AS Type_Observation
FROM 
    agent 
    
LEFT JOIN 
    paie_locale ON agent.Mat_agent = paie_locale.MatriculeAgent
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