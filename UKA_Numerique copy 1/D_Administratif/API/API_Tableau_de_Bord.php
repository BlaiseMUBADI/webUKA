<?php
include("../../../Connexion_BDD/Connexion_1.php");

// Liste des catégories possibles
$categories = [1, 2, 3, 4]; // Remplacez par les catégories réelles

// Préparer la première requête pour obtenir le nombre de parents et de parents décédés
$sql = "SELECT idCategorie, COUNT(*) as nombre_agents FROM agent WHERE agent.type_agent=1 and agent.Grade IS NOT NULL GROUP BY idCategorie;";
$tos = $con->prepare($sql);
$tos->execute();
$parents_data = $tos->fetchAll(PDO::FETCH_ASSOC);

// Créer un tableau associatif avec les catégories et le nombre d'agents
$categories_data = [];
foreach ($parents_data as $data) {
    $categories_data[$data['idCategorie']] = $data['nombre_agents'];
}

// Remplir les catégories manquantes avec 0
$response = [];
foreach ($categories as $categorie) {
    $response[] = [
        'idCategorie' => $categorie,
        'nombre_agent' => isset($categories_data[$categorie]) ? $categories_data[$categorie] : 0
    ];
}

echo json_encode($response);
?>
