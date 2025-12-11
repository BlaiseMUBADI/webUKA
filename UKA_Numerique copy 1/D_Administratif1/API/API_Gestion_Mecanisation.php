<?php

include("../../../Connexion_BDD/Connexion_1.php");

try {
    // Vérifier la connexion à la base de données avec PDO
    if (!$con) {
        throw new Exception("Échec de la connexion à la base de données.");
    }

    // Lire les données envoyées en JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérifier que les données ont été reçues
    if (isset($data['matricule']) && isset($data['charge']) && isset($data['observ'])) {
        $matricule = $data['matricule'];
        $type = $data['charge'];
        $Observation = $data['observ'];

        // Insérer les données dans la table mecanisation
        $sql = "INSERT INTO mecanisation (MatriculeAgent, Libelle,Observation) VALUES (:matricule, :libelle,:obs)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->bindParam(':libelle', $type);
        $stmt->bindParam(':obs', $Observation);

        if ($stmt->execute()) {
            // Si l'insertion est réussie
            $response = array("status" => "success", "message" => "Données insérées avec succès.");
        } else {
            // Si l'insertion échoue
            $response = array("status" => "error", "message" => "Erreur lors de l'insertion.");
        }
    } else {
        // Si les données sont manquantes
        $response = array("status" => "error", "message" => "Données manquantes.");
    }
} catch (Exception $e) {
    // Gérer les erreurs de connexion ou autres
    $response = array("status" => "error", "message" => $e->getMessage());
}

// Envoyer la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);

// Fermeture de la connexion (facultatif, car PDO la ferme automatiquement)
$con = null;


?>
