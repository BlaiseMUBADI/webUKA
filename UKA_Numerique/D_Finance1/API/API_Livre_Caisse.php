<?php
include("../../../Connexion_BDD/Connexion_1.php");
header('Content-Type: application/json');

if (!isset($_GET['type'])) {
    echo json_encode(["error" => "Le paramètre 'type' est requis"]);
    exit;
}

$typeEncaissement = $_GET['type'];

if (($typeEncaissement === "USD" || $typeEncaissement === "CDF") && 
    (!isset($_GET['date1'], $_GET['date2']))) {
    echo json_encode(["error" => "Les paramètres date1 et date2 sont requis"]);
    exit;
}

try {
    $date1_str = $_GET['date1'];
    $date2_str = $_GET['date2'];

    $date1 = $date1_str . " 00:00:00";
    $date2 = $date2_str . " 23:59:59";
    $statut = "Effectuée";

    if ($typeEncaissement === "USD" || $typeEncaissement === "CDF") {
        $prefix_enc = $typeEncaissement === "USD" ? "Enc_usd_%" : "Enc_cdf_%";
        $prefix_dec = $typeEncaissement === "USD" ? "Dec_usd_%" : "Dec_cdf_%";
        $devise = strtolower($typeEncaissement);

        // Requête principale des opérations
        $query = "SELECT 
                    Date_Oper,
                    Deposant AS Personne,
                    Motif,
                    Numero_pce AS Numero,
                    Imputation,
                    Montant AS Montant_Encaisse,
                    NULL AS Montant_Decaisse,
                    'Encaissement' AS Type_Operation
                FROM encaissement_caisse
                WHERE Numero_pce LIKE :prefix_enc
                    AND Statut = :statut
                    AND Date_Oper BETWEEN :date1 AND :date2

                UNION ALL

                SELECT 
                    Date_Oper,
                    Beneficiaire AS Personne,
                    Motif,
                    Num_piece AS Numero,
                    Imputation,
                    NULL AS Montant_Encaisse,
                    Montant AS Montant_Decaisse,
                    'Décaissement' AS Type_Operation
                FROM decaissement_caisse
                WHERE Num_piece LIKE :prefix_dec
                    AND Statut = :statut
                    AND Date_Oper BETWEEN :date1 AND :date2

                ORDER BY Date_Oper ASC";

        $stmt = $con->prepare($query);
        $stmt->execute([
            ':prefix_enc' => $prefix_enc,
            ':prefix_dec' => $prefix_dec,
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔄 LOGIQUE POUR LE SOLDE
        if ($date1_str === $date2_str) {
            $date_jour = new DateTime($date1_str);
            do {
                $date_jour->modify('-1 day');
            } while (in_array($date_jour->format('N'), [6, 7])); // 6 = Samedi, 7 = Dimanche

            $date_veille = $date_jour->format('Y-m-d');

            $query_solde = "SELECT Montant FROM solde 
                            WHERE devise = :devise 
                            AND DATE(date_solde) = :date_solde
                            ORDER BY date_solde DESC 
                            LIMIT 1";

            $stmt_solde = $con->prepare($query_solde);
            $stmt_solde->execute([
                ':devise' => $devise,
                ':date_solde' => $date_veille
            ]);
            $row_solde = $stmt_solde->fetch(PDO::FETCH_ASSOC);
            $solde_periode = $row_solde['Montant'] ?? 0;
            $date_solde = $row_solde ? $date_veille : null;

            if ($solde_periode === 0) {
                // Si aucun solde n'est trouvé, on renvoie un avertissement mais on continue l'exécution
                echo json_encode([
                    "warning" => "Aucun solde trouvé pour la date spécifiée (" . $date_veille . "). Le solde initial est 0.",
                    "operations" => $result,
                    "solde_periode" => number_format($solde_periode, 2, '.', ''),
                    "date_solde" => $date_solde
                ]);
                return;  // On continue l'exécution avec un solde de 0
            }

        } else {
            $query_solde = "SELECT SUM(Montant) AS total_solde FROM solde 
                            WHERE devise = :devise 
                            AND DATE(date_solde) BETWEEN :date1 AND :date2";

            $stmt_solde = $con->prepare($query_solde);
            $stmt_solde->execute([
                ':devise' => $devise,
                ':date1' => $date1_str,
                ':date2' => $date2_str
            ]);
            $solde_row = $stmt_solde->fetch(PDO::FETCH_ASSOC);
            $solde_periode = $solde_row['total_solde'] ?? 0;
            $date_solde = null;
        }

        echo json_encode([
            "operations" => $result,
            "solde_periode" => number_format($solde_periode, 2, '.', ''),
            "date_solde" => $date_solde
        ]);
    } else {
        echo json_encode(["error" => "Type d'encaissement non géré."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
    exit;
}
?>
