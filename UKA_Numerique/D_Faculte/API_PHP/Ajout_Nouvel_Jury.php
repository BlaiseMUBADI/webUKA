<?php
session_start();
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// API pour ajouter un jury via la procédure stockée AddJury
//header('Content-Type: application/json');

include("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lire le JSON brut envoyé par fetch
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $nom_jury = isset($data['nom_jury']) ? $data['nom_jury'] : '';
    $date_jury = isset($data['date_jury']) ? $data['date_jury'] : '';
    $code_promotion = isset($data['code_promotion']) ? $data['code_promotion'] : '';
    $id_annee_acad = isset($data['id_annee_acad']) ? $data['id_annee_acad'] : '';

    if ($nom_jury && $date_jury && $code_promotion && $id_annee_acad) {
        try {
            $stmt = $con->prepare('CALL Ajout_Nouvel_Jury(:nom_jury, :date_jury, :code_promotion, :id_annee_acad)');
            $stmt->bindParam(':nom_jury', $nom_jury);
            $stmt->bindParam(':date_jury', $date_jury);
            $stmt->bindParam(':code_promotion', $code_promotion);
            $stmt->bindParam(':id_annee_acad', $id_annee_acad);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Jury ajouté avec succès']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>
