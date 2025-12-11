<?php

include("../../../Connexion_BDD/Connexion_1.php");

// Préparer la première requête pour obtenir le nombre de parents et de parents décédés
$sql = "SELECT idCategorie, COUNT(*) as nombre_agents
FROM agent
WHERE agent.type_agent=1 and agent.Grade IS NOT NULL
GROUP BY idCategorie;";

$tos = $con->prepare($sql);
$tos->execute();

$parents_data = $tos->fetchAll(PDO::FETCH_ASSOC);

$response = [];
foreach ($parents_data as $data) {
    $response[] = [
        'idCategorie' => $data['idCategorie'],
        'nombre_agent' => $data['nombre_agents']
    ];
}

echo json_encode($response);

?>
