<?php
session_start();
header('Content-Type: application/json');

// ============================================
// 🧪 FICHIER DE TEST AVEC CÔTES FICTIVES
// ============================================
// Génère quelques côtes aléatoires pour tester
// ============================================

include("../../../Connexion_BDD/Connexion_1.php");

$promotion = $_SESSION['code_prom'];
$annee_acad = $_SESSION['id_annee_acad'];
$data = json_decode(file_get_contents('php://input'), true);
$id_semestre = $data['id_semestre'];

try {
    // Récupérer les vraies côtes d'abord
    $sql = "CALL Lister_cotes(:promo_code, :annee_acad, :id_semestre)";
    
    if (isset($con)) {
        $stmt = $con->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':promo_code', $promotion);
            $stmt->bindParam(':annee_acad', $annee_acad);
            $stmt->bindParam(':id_semestre', $id_semestre);
            
            $stmt->execute();
            $cotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } else {
            $cotes = [];
        }
    } else {
        $cotes = [];
    }
    
    // Ajouter quelques côtes fictives pour les nouvelles ECs (10 à 45)
    $matricules = array_unique(array_column($cotes, 'Matricule'));
    
    if (count($matricules) > 0) {
        // Pour chaque étudiant, ajouter quelques notes fictives
        foreach ($matricules as $matricule) {
            // Ajouter des notes pour les ECs 10 à 45 (aléatoirement)
            for ($ec = 10; $ec <= 45; $ec++) {
                // 30% de chance d'avoir une note pour chaque EC
                if (rand(1, 100) <= 30) {
                    $cotes[] = [
                        'Matricule' => $matricule,
                        'id_ec_aligne' => $ec,
                        'Cote' => rand(5, 20) // Note entre 5 et 20
                    ];
                }
            }
        }
    }
    
    echo json_encode($cotes);
    
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
