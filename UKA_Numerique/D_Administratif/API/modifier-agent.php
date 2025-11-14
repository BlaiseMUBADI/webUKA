<?php
// Exemple de gestion d'une requête PUT en PHP
header('Content-Type: application/json');
include("../../../Connexion_BDD/Connexion_1.php");

// Récupérer les données de la requête (en JSON)
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Aucune donnée reçue']);
    exit;
}

// Traitement des données ici (par exemple, mise à jour dans la base de données)
$matricule = $data['matricule'];
$nom = $data['nom'];
$postnom = $data['postnom'];
$prenom = $data['prenom'];
$sexe = $data['sexe'];
$etatCivil = $data['etatCivil'];
$lieuNaissance = $data['lieuNaissance'];
$dateNaissance = $data['dateNaissance'];

// Mettre à jour la base de données ou effectuer la logique nécessaire ici
// Exemple d'une requête SQL pour la mise à jour

// Connexion à la base de données (exemple avec PDO)
try {
   
    // Mise à jour dans la base de données
    $stmt = $con->prepare("UPDATE agent SET Nom_agent = ?, Post_agent = ?, Prenom = ?, Sexe = ?, 
    Lieu = ?, DateNaissance = ?, EtatCivil = ? WHERE Mat_agent = ?");
    $stmt->execute([$nom, $postnom, $prenom, $sexe, $etatCivil, $lieuNaissance, $dateNaissance, $matricule]);

    echo json_encode(['success' => true, 'message' => 'Agent mis à jour avec succès']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour: ' . $e->getMessage()]);
}
?>
