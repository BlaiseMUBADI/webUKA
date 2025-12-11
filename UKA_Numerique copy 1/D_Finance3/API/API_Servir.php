<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");
include("../../../Connexion_BDD/Connexion_1.php");

$statut = "Effectuée";

// 🔹 Récupération des paramètres
$numero_piece = $_GET["Num_Pce"] ?? "";
$beneficiaire = $_GET["beneficiaire"] ?? "";
$imputation = $_GET["imputation"] ?? "";
$motif = $_GET["motif"] ?? "";
$montant = isset($_GET["montant"]) ? floatval($_GET["montant"]) : 0;
$date = date("Y-m-d H:i:s");
$operation = $_GET["operation"] ?? "";
$solde_brut = isset($_GET["solde"]) ? floatval($_GET["solde"]) : 0;
$annee_acad = $_GET['Id_Anne_Acad'] ?? null;
$num_autoriz = $_GET['Num_Autoriz'] ?? null;

// 🔹 Vérifications de base
if ($montant > $solde_brut) {
    echo json_encode(['error' => true, 'message' => '❌ Votre solde est inférieur au montant à décaisser.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($motif)) {
    echo json_encode(['error' => true, 'message' => '❌ Chaque opération a un Motif spécifique.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($montant)) {
    echo json_encode(['error' => true, 'message' => '❌ Saisissez le montant pour cette opération.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// ================================================================
// 🔹 Traitement du décaissement USD
// ================================================================
if ($operation === "Dec_USD") {
    // 🔹 Vérification autorisation
if (!empty($num_autoriz)) {
    $checkAuthQuery = "SELECT COUNT(*) FROM decaissement_caisse WHERE Num_Autoriz = :num_autoriz and Motif= :motif";
    $stmtCheckAuth = $con->prepare($checkAuthQuery);
    $stmtCheckAuth->bindParam(':num_autoriz', $num_autoriz);
    $stmtCheckAuth->bindParam(':motif', $motif);
    $stmtCheckAuth->execute();
    $authCount = $stmtCheckAuth->fetchColumn();

    if ($authCount > 0) {
        echo json_encode([
            'error' => true,
            'message' => "❌ Cette autorisation a déjà été servie."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}



    $prefix = "Dec_usd_";
    $numero_piece = $prefix . $numero_piece; // ✅ Application du préfixe

    // Vérifie si le numéro existe déjà
    $checkQuery = "SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce";
    $stmtCheck = $con->prepare($checkQuery);
    $stmtCheck->bindParam(':num_pce', $numero_piece);
    $stmtCheck->execute();
    $count = $stmtCheck->fetchColumn();

    // S'il n'existe pas, on l'insère
    if ($count == 0) {
        $insertNum = "INSERT INTO numero_piece (numero_pce) VALUES (:num_pce)";
        $stmtNum = $con->prepare($insertNum);
        $stmtNum->bindParam(':num_pce', $numero_piece);
        $stmtNum->execute();
    }

    // Enregistrement dans decaissement_caisse
    $insert = "INSERT INTO decaissement_caisse 
            (Num_piece, Beneficiaire, Imputation, Motif, Montant, Date_Oper, Statut, Id_Anne_Acad, Num_Autoriz)
            VALUES 
            (:Num_pce, :Beneficiaire, :Imputation, :Motif, :Montant, :Date_oper, :Statut, :Id_Anne_Acad, :Num_Autoriz)";
    $stmt = $con->prepare($insert);
    $stmt->execute([
        ':Num_pce' => $numero_piece,
        ':Beneficiaire' => $beneficiaire,
        ':Imputation' => $imputation,
        ':Motif' => $motif,
        ':Montant' => $montant,
        ':Date_oper' => $date,
        ':Statut' => $statut,
        ':Id_Anne_Acad' => $annee_acad,
        ':Num_Autoriz' => $num_autoriz
    ]);

    echo json_encode([
        "success" => true,
        "message" => "✅ Décaissement USD effectué avec succès.",
        "num_piece" => $numero_piece
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ================================================================
// 🔹 Traitement du décaissement CDF
// ================================================================
elseif ($operation === "Dec_CDF") {
    // 🔹 Vérification autorisation
    if (!empty($num_autoriz)) {
    $checkAuthQuery = "SELECT COUNT(*) FROM decaissement_caisse WHERE Num_Autoriz = :num_autoriz and Motif= :motif";
    $stmtCheckAuth = $con->prepare($checkAuthQuery);
    $stmtCheckAuth->bindParam(':num_autoriz', $num_autoriz);
    $stmtCheckAuth->bindParam(':motif', $motif);
    $stmtCheckAuth->execute();
    $authCount = $stmtCheckAuth->fetchColumn();

    if ($authCount > 0) {
        echo json_encode([
            'error' => true,
            'message' => "❌ Cette autorisation a déjà été servie."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}



    $prefix = "Dec_cdf_";
    $numero_piece = $prefix . $numero_piece; // ✅ Application du préfixe

    // Vérifie si le numéro existe déjà
    $checkQuery = "SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce";
    $stmtCheck = $con->prepare($checkQuery);
    $stmtCheck->bindParam(':num_pce', $numero_piece);
    $stmtCheck->execute();
    $count = $stmtCheck->fetchColumn();

    // S'il n'existe pas, on l'insère
    if ($count == 0) {
        $insertNum = "INSERT INTO numero_piece (numero_pce) VALUES (:num_pce)";
        $stmtNum = $con->prepare($insertNum);
        $stmtNum->bindParam(':num_pce', $numero_piece);
        $stmtNum->execute();
    }

    // Enregistrement dans decaissement_caisse
    $insert = "INSERT INTO decaissement_caisse 
            (Num_piece, Beneficiaire, Imputation, Motif, Montant, Date_Oper, Statut, Id_Anne_Acad, Num_Autoriz)
            VALUES 
            (:Num_pce, :Beneficiaire, :Imputation, :Motif, :Montant, :Date_oper, :Statut, :Id_Anne_Acad, :Num_Autoriz)";
    $stmt = $con->prepare($insert);
    $stmt->execute([
        ':Num_pce' => $numero_piece,
        ':Beneficiaire' => $beneficiaire,
        ':Imputation' => $imputation,
        ':Motif' => $motif,
        ':Montant' => $montant,
        ':Date_oper' => $date,
        ':Statut' => $statut,
        ':Id_Anne_Acad' => $annee_acad,
        ':Num_Autoriz' => $num_autoriz
    ]);

    echo json_encode([
        "success" => true,
        "message" => "✅ Décaissement CDF effectué avec succès.",
        "num_suivant" => $numero_piece 
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
