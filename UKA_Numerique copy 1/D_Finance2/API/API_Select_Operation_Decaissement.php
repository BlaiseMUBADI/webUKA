<?php
// Connexion à la base de données
include("../../../Connexion_BDD/Connexion_1.php");

// Définir l'en-tête pour le retour en JSON
header('Content-Type: application/json');

// Vérifier que les paramètres sont bien fournis
if (!isset($_GET['type'])) {
    echo json_encode(["error" => "Le paramètre 'type' est requis"]);
    exit;
}

$typeEncaissement = $_GET['type'];

if (($typeEncaissement === "USD" || $typeEncaissement === "CDF") && 
    (!isset($_GET['date1'], $_GET['date2']))) {
    echo json_encode(["error" => "Les paramètres date1 et date2 sont requis pour les types USD et CDF"]);
    exit;
}


// Récupérer les paramètres


$typeEncaissement = $_GET['type'];

try {
    if ($typeEncaissement === "USD") {
        $date1 = $_GET['date1'] . " 00:00:00";
        $date2 = $_GET['date2'] . " 23:59:59";
        $prefix = "Dec_usd_";
        $statut = "Effectuée";

        $query = " SELECT *  
            FROM decaissement_caisse
            WHERE Num_piece LIKE :prefix 
              AND Statut = :statut
              AND Date_Oper BETWEEN :date1 AND :date2
             ORDER BY Date_Oper ASC";

        $stmt = $con->prepare($query);
        $stmt->execute([
            ':prefix' => $prefix . '%',
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result); // Renvoie les résultats au format JSON
    }
    elseif ($typeEncaissement === "CDF") {
        $date1 = $_GET['date1'] . " 00:00:00";
        $date2 = $_GET['date2'] . " 23:59:59";
        $prefix = "Dec_cdf_";
        $statut = "Effectuée";
        
        $query = "SELECT *  
                  FROM decaissement_caisse
                  WHERE Num_piece LIKE :prefix 
                    AND Statut = :statut
                    AND Date_Oper BETWEEN :date1 AND :date2
                  ORDER BY Date_Oper ASC";
        
        $stmt = $con->prepare($query);
        $stmt->execute([
            ':prefix' => $prefix . '%',
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ]);
        

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result); // Renvoie les résultats au format JSON
    }
    elseif ($typeEncaissement === "modifier") {
        
        $Motif = $_GET["Motif"];
        $Montant = $_GET["Montant"];
        //$Date_Encaiss = date("Y-m-d H:i:s");
        $beneficiaire = $_GET["Beneficiaire"];
        $numero_piece = $_GET["Num_Pce"]; // Assure-toi qu'il est bien passé dans l'URL
    
        $update = "UPDATE decaissement_caisse 
                   SET Motif = :Motif, 
                       Montant = :Montant, 
                       Beneficiaire = :depos 
                   WHERE Num_piece = :Num_pce";
    
        $stmtUpdate = $con->prepare($update);
        $stmtUpdate->bindParam(':Motif', $Motif);
        $stmtUpdate->bindParam(':Montant', $Montant);
        $stmtUpdate->bindParam(':depos', $beneficiaire);
        $stmtUpdate->bindParam(':Num_pce', $numero_piece);
    
        if ($stmtUpdate->execute()) {
            echo json_encode(["success" => true, "message" => "Modification effectuée avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
        }
    }
    elseif ($typeEncaissement === "statut") {
        
       
        $statut_op = "Annulée";
        $numero_piece = $_GET["Num_Pce"]; // Assure-toi qu'il est bien passé dans l'URL
    
        $update = "UPDATE decaissement_caisse 
                   SET Statut = :statut 
                   WHERE Num_piece = :Num_pce";
    
        $stmtUpdate = $con->prepare($update);
        $stmtUpdate->bindParam(':statut', $statut_op);
        $stmtUpdate->bindParam(':Num_pce', $numero_piece);
    
        if ($stmtUpdate->execute()) {
            echo json_encode(["success" => true, "message" => "Encaissement mis à jour avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
        }
    }


     else {
        echo json_encode(["error" => "Type d'encaissement non géré."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>
