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
    if (isset($data['matricule']) && isset($data['libelle']) && isset($data['observ']) && isset($data['action'])) {
        $matricule = $data['matricule'];
        $lib = $data['libelle'];
        $Observation = $data['observ'];
        $action = $data['action']; // Récupérer l'action (insert ou delete)

        if ($action == 'insert') {
            // Insérer les données dans la table paie_locale
            $sql = "INSERT INTO paie_locale (MatriculeAgent, Libelle, Observation) VALUES (:matricule, :libelle, :obs)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':matricule', $matricule);
            $stmt->bindParam(':libelle', $lib);
            $stmt->bindParam(':obs', $Observation);

            if ($stmt->execute()) {
                // Si l'insertion est réussie
                $response = array("status" => "success", "message" => "Données insérées avec succès.");
            } else {
                // Si l'insertion échoue
                $response = array("status" => "error", "message" => "Erreur lors de l'insertion.");
            }
        } elseif ($action == 'delete') {
            // Supprimer les données de la table paie_locale
            $sql = "DELETE FROM paie_locale WHERE MatriculeAgent = :matricule AND Libelle = :libelle AND Observation = :obs";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':matricule', $matricule);
            $stmt->bindParam(':libelle', $lib);
            $stmt->bindParam(':obs', $Observation);

            if ($stmt->execute()) {
                // Si la suppression est réussie
                $response = array("status" => "success", "message" => "Données supprimées avec succès.");
            } else {
                // Si la suppression échoue
                $response = array("status" => "error", "message" => "Erreur lors de la suppression.");
            }
        } else {
            // Si l'action n'est pas valide
            $response = array("status" => "error", "message" => "Action invalide.");
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
