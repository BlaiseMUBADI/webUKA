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

    if (!$id_jury) {
        throw new Exception('ID jury non fourni.');
    }

    // Vérifier si le jury a des membres
    $check_sql = "SELECT COUNT(*) as nb_membres FROM t_membre_jury WHERE ID_jury = :id_jury";
    $check_stmt = $con->prepare($check_sql);
    $check_stmt->bindParam(':id_jury', $id_jury, PDO::PARAM_INT);
    $check_stmt->execute();
    $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

    // Commencer une transaction
    $con->beginTransaction();

    // Supprimer d'abord les membres du jury
    if ($result['nb_membres'] > 0) {
        $delete_membres_sql = "DELETE FROM t_membre_jury WHERE ID_jury = :id_jury";
        $delete_membres_stmt = $con->prepare($delete_membres_sql);
        $delete_membres_stmt->bindParam(':id_jury', $id_jury, PDO::PARAM_INT);
        $delete_membres_stmt->execute();
    }

    // Supprimer le jury
    $delete_jury_sql = "DELETE FROM t_jury_deliberation WHERE ID_jury = :id_jury";
    $delete_jury_stmt = $con->prepare($delete_jury_sql);
    $delete_jury_stmt->bindParam(':id_jury', $id_jury, PDO::PARAM_INT);

    if ($delete_jury_stmt->execute()) {
        $con->commit();
        $response['success'] = true;
        $response['message'] = 'Jury supprimé avec succès.';
        $response['nb_membres_supprimes'] = $result['nb_membres'];
    } else {
        $con->rollBack();
        throw new Exception('Erreur lors de la suppression du jury.');
    }

} catch (Exception $e) {
    if ($con->inTransaction()) {
        $con->rollBack();
    }
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
