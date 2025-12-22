<?php
session_start();
require_once("../../../Connexion_BDD/Connexion_1.php");

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
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');*/

try {
    $id_membre = isset($_GET['id_membre']) ? intval($_GET['id_membre']) : 0;
    
    if ($id_membre <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID membre invalide.']);
        exit;
    }
    
    $stmt = $con->prepare("
        SELECT 
            m.ID_jury_membre,
            m.Mat_agent,
            m.role,
            m.Login,
            m.Statut,
            a.Nom_agent,
            a.Post_agent,
            a.Prenom,
            a.Grade
        FROM t_membre_jury m
        INNER JOIN agent a ON m.Mat_agent = a.Mat_agent
        WHERE m.ID_jury_membre = ?
    ");
    $stmt->execute([$id_membre]);
    $membre = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($membre) {
        echo json_encode([
            'success' => true,
            'membre' => $membre
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Membre introuvable.'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur : ' . $e->getMessage()
    ]);
}
?>
