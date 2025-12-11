<?php
include('../../D_Generale/session_check.php');
require_once("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion PDO
if (!isset($con) || !($con instanceof PDO)) {
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


$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Initialiser la réponse
$response = [
    'success' => false,
    'message' => ''
];

try {
    // Récupérer les données
    $id_jury = isset($data['id_jury']) ? $data['id_jury'] : null;
    $mat_agent = isset($data['mat_agent']) ? $data['mat_agent'] : null;
    $role = isset($data['role']) ? $data['role'] : null;
    $login = isset($data['login']) && !empty($data['login']) ? $data['login'] : null;
    $password = isset($data['password']) && !empty($data['password']) ? $data['password'] : null;
    $statut = isset($data['statut']) ? $data['statut'] : 'Actif';

    // Crypter le mot de passe avec bcrypt si fourni
    $mot_passe_crypte = null;
    if (!empty($password)) {
        $mot_passe_crypte = password_hash($password, PASSWORD_BCRYPT);
    }

    // Appeler la procédure stockée
    $sql = "CALL Ajouter_Membre_Jury(:id_jury, :mat_agent, :role, :login, :mot_passe, :statut, @success, @message, @id_membre)";
    $stmt = $con->prepare($sql);
    
    $stmt->bindParam(':id_jury', $id_jury, PDO::PARAM_INT);
    $stmt->bindParam(':mat_agent', $mat_agent, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':mot_passe', $mot_passe_crypte, PDO::PARAM_STR);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
    
    $stmt->execute();
    
    // Récupérer les valeurs de sortie
    $result = $con->query("SELECT @success AS success, @message AS message, @id_membre AS id_membre")->fetch(PDO::FETCH_ASSOC);
    
    $response['success'] = (bool)$result['success'];
    $response['message'] = $result['message'];
    
    if ($response['success']) {
        $response['id_membre'] = $result['id_membre'];
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Envoyer la réponse JSON
echo json_encode($response);
?>


