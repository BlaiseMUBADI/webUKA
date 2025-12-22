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

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$response = [
    'success' => false,
    'message' => ''
];

try {
    $id_jury = isset($data['id_jury']) ? $data['id_jury'] : null;
    $nom_jury = isset($data['nom_jury']) ? trim($data['nom_jury']) : null;
    $date_jury = isset($data['date_jury']) ? $data['date_jury'] : null;
    $code_promotion = isset($data['code_promotion']) ? $data['code_promotion'] : null;
    $id_annee_acad = isset($data['id_annee_acad']) ? $data['id_annee_acad'] : null;

    if (!$id_jury || !$nom_jury || !$date_jury || !$code_promotion || !$id_annee_acad) {
        throw new Exception('Tous les champs sont requis.');
    }

    $sql = "UPDATE t_jury_deliberation 
            SET Libelle_jury = :nom_jury,
                Date_délibération = :date_jury,
                Code_Promotion = :code_promotion,
                idAnnee_Acad = :id_annee_acad
            WHERE ID_jury = :id_jury";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':nom_jury', $nom_jury, PDO::PARAM_STR);
    $stmt->bindParam(':date_jury', $date_jury, PDO::PARAM_STR);
    $stmt->bindParam(':code_promotion', $code_promotion, PDO::PARAM_STR);
    $stmt->bindParam(':id_annee_acad', $id_annee_acad, PDO::PARAM_INT);
    $stmt->bindParam(':id_jury', $id_jury, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Jury modifié avec succès.';
    } else {
        throw new Exception('Erreur lors de la modification du jury.');
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
