<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id_filiere = $_SESSION['id_fac'];
$data = json_decode(file_get_contents('php://input'), true);

// Deux modes : mode enseignant OU mode EC aligné
$mat_agent = isset($data['mat_agent']) ? $data['mat_agent'] : null;
$id_ec_aligne = isset($data['id_ec_aligne']) ? $data['id_ec_aligne'] : null;
$id_annee_acad = $data['id_annee_acad'];
$code_prom = $data['code_prom'];
$id_semestre = $data['id_semestre'];

try {
    $sql_select = "CALL Liste_Assistants_Disponibles(:mat_agent,:id_ec_aligne,:id_annee_acad,:id_semestre,:code_prom,:id_filiere)";
    $stmt = $con->prepare($sql_select);
    $stmt->bindParam(':mat_agent', $mat_agent);
    $stmt->bindParam(':id_ec_aligne', $id_ec_aligne, PDO::PARAM_INT);
    $stmt->bindParam(':id_annee_acad', $id_annee_acad);
    $stmt->bindParam(':id_semestre', $id_semestre);
    $stmt->bindParam(':code_prom', $code_prom);
    $stmt->bindParam(':id_filiere', $id_filiere);
    $stmt->execute();

    $assistants = array();
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $assistants[] = $ligne;
    }
    echo json_encode($assistants);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de la récupération des assistants: " . $e->getMessage()]);
}
?>
