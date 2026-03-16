<?php
include("../../../Connexion_BDD/Connexion_1.php");

header('Content-Type: application/json');

// Vérification du paramètre type
if (!isset($_GET['type'])) {
    echo json_encode(["error" => "Le paramètre 'type' est requis"]);
    exit;
}

$type = $_GET['type'];

// Vérification des dates
if (($type === "USD" || $type === "CDF") &&
    (!isset($_GET['date1'], $_GET['date2']))) 
{
    echo json_encode(["error" => "Les paramètres date1 et date2 sont requis"]);
    exit;
}

try {

    if ($type === "USD") {

        $date1 = $_GET['date1'] . " 00:00:00";
        $date2 = $_GET['date2'] . " 23:59:59";

        $prefixDec = "Dec_usd_";
        $prefixEnc = "Enc_usd_";
        $statut = "Effectuée";

        // 🔥 Requête décaissements USD
        $sqlDec = "
            SELECT 
                d.Imputation,
                SUM(d.Montant) AS MontantTotal,
                t.Intitul_compte
            FROM decaissement_caisse d
            JOIN t_imputation t ON d.Imputation = t.Num_imputation
            WHERE d.Num_piece LIKE :prefixDec
              AND d.Statut = :statut
              AND d.Date_Oper BETWEEN :date1 AND :date2
            GROUP BY d.Imputation
            ORDER BY d.Imputation ASC
        ";

        // 🔥 Requête encaissements USD (corrigée)
        $sqlEnc = "
            SELECT 
                e.Imputation,
                SUM(e.Montant) AS MontantTotal,
                t.Intitul_compte
            FROM encaissement_caisse e
            JOIN t_imputation t ON e.Imputation = t.Num_imputation
            WHERE e.Numero_pce LIKE :prefixEnc
              AND e.Statut = :statut
              AND e.Date_Oper BETWEEN :date1 AND :date2
            GROUP BY e.Imputation
            ORDER BY e.Imputation ASC
        ";

        // 👉 Préparer & exécuter
        $stmtDec = $con->prepare($sqlDec);
        $stmtEnc = $con->prepare($sqlEnc);

        $paramsDec = [
            ':prefixDec' => $prefixDec . '%',
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ];

        $paramsEnc = [
            ':prefixEnc' => $prefixEnc . '%',
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ];

        $stmtDec->execute($paramsDec);
        $stmtEnc->execute($paramsEnc);

        $decaissements = $stmtDec->fetchAll(PDO::FETCH_ASSOC);
        $encaissements = $stmtEnc->fetchAll(PDO::FETCH_ASSOC);

        // 🔥 Retour JSON propre
        echo json_encode([
            "decaissements_usd" => $decaissements,
            "encaissements_usd" => $encaissements
        ]);
    }

    if ($type === "CDF") {

        $date1 = $_GET['date1'] . " 00:00:00";
        $date2 = $_GET['date2'] . " 23:59:59";

        $prefixDec = "Dec_cdf_";
        $prefixEnc = "Enc_cdf_";
        $statut = "Effectuée";

        // 🔥 Requête décaissements USD
        $sqlDec = "
            SELECT 
                d.Imputation,
                SUM(d.Montant) AS MontantTotal,
                t.Intitul_compte
            FROM decaissement_caisse d
            JOIN t_imputation t ON d.Imputation = t.Num_imputation
            WHERE d.Num_piece LIKE :prefixDec
              AND d.Statut = :statut
              AND d.Date_Oper BETWEEN :date1 AND :date2
            GROUP BY d.Imputation
            ORDER BY d.Imputation ASC
        ";

        // 🔥 Requête encaissements USD (corrigée)
        $sqlEnc = "
            SELECT 
                e.Imputation,
                SUM(e.Montant) AS MontantTotal,
                t.Intitul_compte
            FROM encaissement_caisse e
            JOIN t_imputation t ON e.Imputation = t.Num_imputation
            WHERE e.Numero_pce LIKE :prefixEnc
              AND e.Statut = :statut
              AND e.Date_Oper BETWEEN :date1 AND :date2
            GROUP BY e.Imputation
            ORDER BY e.Imputation ASC
        ";

        // 👉 Préparer & exécuter
        $stmtDec = $con->prepare($sqlDec);
        $stmtEnc = $con->prepare($sqlEnc);

        $paramsDec = [
            ':prefixDec' => $prefixDec . '%',
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ];

        $paramsEnc = [
            ':prefixEnc' => $prefixEnc . '%',
            ':statut' => $statut,
            ':date1' => $date1,
            ':date2' => $date2
        ];

        $stmtDec->execute($paramsDec);
        $stmtEnc->execute($paramsEnc);

        $decaissements = $stmtDec->fetchAll(PDO::FETCH_ASSOC);
        $encaissements = $stmtEnc->fetchAll(PDO::FETCH_ASSOC);

        // 🔥 Retour JSON propre
        echo json_encode([
            "decaissements_cdf" => $decaissements,
            "encaissements_cdf" => $encaissements
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>
