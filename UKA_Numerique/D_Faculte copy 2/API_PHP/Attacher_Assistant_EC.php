<?php
session_start();

// Connexion à la base de données
require_once '../../../Connexion_BDD/Con_biblio.php';

// Récupérer les données JSON envoyées
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Extraire les paramètres
$id_ec_aligne = isset($data['id_ec_aligne']) ? $data['id_ec_aligne'] : null;
$mat_assistant = isset($data['mat_assistant']) ? $data['mat_assistant'] : null;
$id_annee_acad = isset($data['id_annee_acad']) ? $data['id_annee_acad'] : null;
$id_semestre = isset($data['id_semestre']) ? $data['id_semestre'] : null;
$code_prom = isset($data['code_prom']) ? $data['code_prom'] : null;

// Valider les paramètres obligatoires
if (!$id_ec_aligne) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID EC aligné manquant'
    ]);
    exit;
}

try {
    // Préparer la requête de mise à jour
    // Si mat_assistant est null, on détache l'assistant (mise à NULL)
    // Sinon, on attache l'assistant spécifié
    $sql = "UPDATE element_constitutifs_aligne 
            SET Mat_assistant = :mat_assistant
            WHERE id_ec_aligne = :id_ec_aligne";
    
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':mat_assistant', $mat_assistant, PDO::PARAM_STR);
    $stmt->bindParam(':id_ec_aligne', $id_ec_aligne, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Vérifier si une ligne a été affectée
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => $mat_assistant ? 'Assistant attaché avec succès' : 'Assistant détaché avec succès',
                'id_ec_aligne' => $id_ec_aligne,
                'mat_assistant' => $mat_assistant
            ]);
        } else {
            // Aucune ligne affectée - peut-être que la valeur était déjà identique
            echo json_encode([
                'status' => 'success',
                'message' => 'Aucun changement nécessaire',
                'id_ec_aligne' => $id_ec_aligne,
                'mat_assistant' => $mat_assistant
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erreur lors de la mise à jour'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
