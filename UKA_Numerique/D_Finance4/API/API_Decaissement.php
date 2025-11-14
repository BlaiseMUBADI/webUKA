<?php
header("Content-Type: application/json");
include("../../../Connexion_BDD/Connexion_1.php");

date_default_timezone_set('Africa/Lubumbashi');
$statut = "Effectuée";

// Récupération des paramètres de la requête
$numero_piece = $_GET["Num_Pce"] ?? "";
$beneficiaire = $_GET["beneficiaire"] ?? "";
$imputation = $_GET["imputation"] ?? "";
$motif = $_GET["motif"] ?? "";
$montant = isset($_GET["montant"]) ? floatval($_GET["montant"]) : 0;
$date = date("Y-m-d H:i:s");
$operation = $_GET["operation"] ?? "";
$solde_brut = isset($_GET["solde"]) ? floatval($_GET["solde"]) : 0;

if ($montant>$solde_brut) {
    echo json_encode([
        'error' => true,
        'message' => '❌ Votre solde est inferieur au montant à décaisser.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($motif)) {
    echo json_encode([
        'error' => true,
        'message' => '❌ Chaque opération a un Motif spécifique.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($montant)) {
    echo json_encode([
        'error' => true,
        'message' => '❌ Saisissez le montant pour cette opération.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
 // === Générer le numéro de pièce automatiquement ===
 if($operation==="Dec_USD")
 {
    $prefix = "Dec_usd_";
    $query = "SELECT numero_pce 
        FROM numero_piece 
        WHERE numero_pce LIKE :prefix 
        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(:prefix_clean) + 1) AS UNSIGNED) DESC 
        LIMIT 1
    ";
    $stmt = $con->prepare($query);
    $stmt->execute([
        ':prefix' => $prefix . '%',
        ':prefix_clean' => $prefix
    ]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lastNumero = $row['numero_pce'];
        $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
    } else {
        $numericPart = 1;
    }

    $numero_piece = $prefix . $numericPart;

    // Insérer dans numero_piece si inexistant
    $checkQuery = "SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce";
    $stmtCheck = $con->prepare($checkQuery);
    $stmtCheck->bindParam(':num_pce', $numero_piece);
    $stmtCheck->execute();
    $count = $stmtCheck->fetchColumn();

    if ($count == 0) {
        $insertQuery = "INSERT INTO numero_piece (numero_pce) VALUES (:num_pce)";
        $stmtInsert = $con->prepare($insertQuery);
        $stmtInsert->bindParam(':num_pce', $numero_piece);
        $stmtInsert->execute();
    }

    // Enregistrement du décaissement
    $insert = "INSERT INTO decaissement_caisse 
                (Num_piece, Beneficiaire, Imputation, Motif, Montant, Date_Oper, Statut)
                VALUES 
                (:Num_pce, :Beneficiaire, :Imputation, :Motif, :Montant, :Date_oper, :Statut)";
    $stmt = $con->prepare($insert);
    $stmt->execute([
        ':Num_pce' => $numero_piece,
        ':Beneficiaire' => $beneficiaire,
        ':Imputation' => $imputation,
        ':Motif' => $motif,
        ':Montant' => $montant,
        ':Date_oper' => $date,
        ':Statut' => $statut
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Décaissement USD effectué avec succès.",
        "num_suivant" => $numericPart + 1
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
elseif($operation==="Dec_CDF")
{
    $prefix = "Dec_cdf_";
    $query = "
        SELECT numero_pce 
        FROM numero_piece 
        WHERE numero_pce LIKE :prefix 
        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(:prefix_clean) + 1) AS UNSIGNED) DESC 
        LIMIT 1
    ";
    $stmt = $con->prepare($query);
    $stmt->execute([
        ':prefix' => $prefix . '%',
        ':prefix_clean' => $prefix
    ]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lastNumero = $row['numero_pce'];
        $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
    } else {
        $numericPart = 1;
    }

    $numero_piece = $prefix . $numericPart;

    // Insérer dans numero_piece si inexistant
    $checkQuery = "SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce";
    $stmtCheck = $con->prepare($checkQuery);
    $stmtCheck->bindParam(':num_pce', $numero_piece);
    $stmtCheck->execute();
    $count = $stmtCheck->fetchColumn();

    if ($count == 0) {
        $insertQuery = "INSERT INTO numero_piece (numero_pce) VALUES (:num_pce)";
        $stmtInsert = $con->prepare($insertQuery);
        $stmtInsert->bindParam(':num_pce', $numero_piece);
        $stmtInsert->execute();
    }

    // Enregistrement du décaissement
    $insert = "INSERT INTO decaissement_caisse 
                (Num_piece, Beneficiaire, Imputation, Motif, Montant, Date_Oper, Statut)
                VALUES 
                (:Num_pce, :Beneficiaire, :Imputation, :Motif, :Montant, :Date_oper, :Statut)";
    $stmt = $con->prepare($insert);
    $stmt->execute([
        ':Num_pce' => $numero_piece,
        ':Beneficiaire' => $beneficiaire,
        ':Imputation' => $imputation,
        ':Motif' => $motif,
        ':Montant' => $montant,
        ':Date_oper' => $date,
        ':Statut' => $statut
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Décaissement USD effectué avec succès.",
        "num_suivant" => $numericPart + 1
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
