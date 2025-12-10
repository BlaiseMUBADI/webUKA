<?php

include("../../../Connexion_BDD/Connexion_1.php");

$matricule = $_GET['mat'];

// Préparer la première requête pour obtenir le nombre de parents et de parents décédés
$sql = "SELECT 
        COUNT(*) AS nombre_de_parents,
        SUM(CASE WHEN statut = 'décédé' THEN 1 ELSE 0 END) AS nombre_de_parents_decedes
    FROM parent
    WHERE MatriculeAgent = :matricule";

$tos = $con->prepare($sql);
$tos->bindParam(':matricule', $matricule, PDO::PARAM_STR);
$tos->execute();

// Récupérer les résultats de la première requête
$parents_data = $tos->fetch();

// Préparer la deuxième requête pour obtenir le nombre d'enfants
$sql_enfants = "SELECT 
        COUNT(*) AS nombre_enfants
    FROM enfant
    WHERE Mat_agent = :matricule";

$trmt = $con->prepare($sql_enfants);
$trmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
$trmt->execute();

// Récupérer les résultats de la deuxième requête
$enfants_data = $trmt->fetch();

// Fusionner les deux résultats dans un tableau unique
$response = [
    'nombre_de_parents' => $parents_data['nombre_de_parents'],
    'nombre_de_parents_decedes' => $parents_data['nombre_de_parents_decedes'],
    'nombre_enfants' => $enfants_data['nombre_enfants']
];

// Renvoie de la réponse JSON avec toutes les données
echo json_encode($response);

?>
