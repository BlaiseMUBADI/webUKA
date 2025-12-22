<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//header('Content-Type: application/json');
require_once("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données"]);
    exit;
}

try {
    // Lire le JSON envoyé par fetch
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $id_annee_acad = isset($data['id_annee_acad']) ? $data['id_annee_acad'] : '';
    $id_filiere = isset($_SESSION['id_fac']) ? $_SESSION['id_fac'] : '';

    $req = "SELECT j.ID_jury, j.Libelle_jury, j.Date_délibération, j.Code_Promotion, j.idAnnee_Acad, 
                   CONCAT(promotion.Abréviation,' ',mentions.Libelle_mention) as lib_promotion
            FROM t_jury_deliberation j
            LEFT JOIN promotion ON j.Code_Promotion = promotion.Code_Promotion
            LEFT JOIN mentions ON promotion.IdMentions = mentions.IdMentions
            LEFT JOIN annee_academique a ON j.idAnnee_Acad = a.idAnnee_Acad
            WHERE j.idAnnee_Acad = :id_annee_acad AND mentions.IdFiliere = :id_filiere
            ORDER BY j.Date_délibération DESC";
    $stmt = $con->prepare($req);
    $stmt->bindParam(':id_annee_acad', $id_annee_acad);
    $stmt->bindParam(':id_filiere', $id_filiere);
    $stmt->execute();

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = [
            'id_jury' => $row['ID_jury'],
            'nom_jury' => $row['Libelle_jury'],
            'promotion' => $row['lib_promotion'],
            'date_jury' => $row['Date_délibération'],
            'id_annee_acad' => $row['idAnnee_Acad']
        ];
    }
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
