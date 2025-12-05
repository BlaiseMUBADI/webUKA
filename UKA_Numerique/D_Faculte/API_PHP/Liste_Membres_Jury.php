<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');*/

try {
    $id_jury = isset($_GET['id_jury']) ? $_GET['id_jury'] : null;

    if (!$id_jury) {
        echo json_encode(['success' => false, 'message' => 'ID jury non fourni.']);
        exit;
    }

    $sql = "SELECT 
                m.ID_jury_membre,
                m.Mat_agent,
                m.role,
                m.Login,
                m.Statut,
                m.date_ajout,
                CONCAT(a.Nom_agent, ' ', a.Post_agent, ' ', a.Prenom) AS nom_complet,
                a.Grade,
                a.Sexe
            FROM t_membre_jury m
            INNER JOIN agent a ON m.Mat_agent = a.Mat_agent
            WHERE m.ID_jury = :id_jury
            ORDER BY 
                CASE m.role
                    WHEN 'Président' THEN 1
                    WHEN 'Secrétaire' THEN 2
                    WHEN 'Membre' THEN 3
                END,
                m.date_ajout ASC";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id_jury', $id_jury, PDO::PARAM_INT);
    $stmt->execute();

    $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'membres' => $membres,
        'count' => count($membres)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur : ' . $e->getMessage()
    ]);
}
?>
