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

// ...existing code...
$id_filiere = $_SESSION['id_fac'];
$data = json_decode(file_get_contents('php://input'), true);
$mat_agent = $data['mat_agent'];
$id_annee_acad = $data['id_annee_acad'];
if ($id_annee_acad === '' || $id_annee_acad === null || strtolower($id_annee_acad) === 'rien') {
    $id_annee_acad = null;
} else {
    $id_annee_acad = (int)$id_annee_acad;
}
$code_prom = $data['code_prom'];
$id_semestre = $data['id_semestre'];
if ($id_semestre === '' || $id_semestre === null || strtolower($id_semestre) === 'rien') {
    $id_semestre = null;
}
try {
    $sql_select = "CALL Liste_EC_Aligne(:id_filiere,:mat_agent,:id_annee_acad,:id_semestre,:code_prom)";
    $stmt = $con->prepare($sql_select);
    $stmt->bindParam(':id_filiere', $id_filiere);
    $stmt->bindParam(':mat_agent', $mat_agent);
    $stmt->bindParam(':id_annee_acad', $id_annee_acad, PDO::PARAM_INT);
    $stmt->bindParam(':id_semestre', $id_semestre, PDO::PARAM_INT);
    $stmt->bindParam(':code_prom', $code_prom);
    $stmt->execute();

    $ecs = array();
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ecs[] = $ligne;
    }
    echo json_encode($ecs);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de la récupération des ECs: " . $e->getMessage()]);
}
?>