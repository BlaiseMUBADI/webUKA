<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données"]);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');*/

try {
    // Récupérer l'ID du membre à supprimer
    $id_membre = isset($_POST['id_membre']) ? intval($_POST['id_membre']) : 0;
    
    if ($id_membre <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID membre invalide.'
        ]);
        exit;
    }
    
    // Vérifier si le membre existe et récupérer ses infos
    $stmt = $con->prepare("
        SELECT m.ID_jury_membre, m.role, a.Nom_agent, a.Post_agent 
        FROM t_membre_jury m
        INNER JOIN agent a ON m.Mat_agent = a.Mat_agent
        WHERE m.ID_jury_membre = ?
    ");
    $stmt->execute([$id_membre]);
    $membre = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$membre) {
        echo json_encode([
            'success' => false,
            'message' => 'Membre introuvable.'
        ]);
        exit;
    }
    
    // Supprimer le membre
    $stmt = $con->prepare("DELETE FROM t_membre_jury WHERE ID_jury_membre = ?");
    $stmt->execute([$id_membre]);
    
    echo json_encode([
        'success' => true,
        'message' => "Le membre {$membre['Nom_agent']} {$membre['Post_agent']} ({$membre['role']}) a été supprimé avec succès."
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
    ]);
}
?>
