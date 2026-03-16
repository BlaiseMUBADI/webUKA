
<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

$onglet_id = $_GET['idsession'] ?? null;

//Onglet ID manquant
if (!$onglet_id) {
    echo json_encode(["error" => "Accès invalide"]);
    exit;
}

// Si aucun onglet connu → on initialise
if (!isset($_SESSION['onglets'])) {
    $_SESSION['onglets'] = [];
}
 //L’onglet doit être déjà enregistré dans la session SERVEUR
/*if (!isset($_SESSION['onglets']) || !in_array($onglet_id, $_SESSION['onglets'])) {
    
     // 🔥 Associer la session au message
    $session_id = session_id();

    echo json_encode([
        "error" => "Accès non autorisé",
        "session_id" => $session_id,
        "onglet_recu" => $onglet_id
    ]);
    exit;
}
*/

// Si la session de l’utilisateur est détruite dans cet onglet → erreur
if (!isset($_SESSION['MatriculeAgent'])) {
    echo json_encode(["error" => "Session expirée"]);
    exit;
}




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../Connexion_BDD/Connexion_1.php");

$statut = "Effectuée";

// 🔹 Récupération des paramètres
$numero_piece = $_GET["Num_Pce"] ?? "";
$beneficiaire = $_GET["beneficiaire"] ?? "";
$imputation = $_GET["imputation"] ?? "";
$motif = $_GET["motif"] ?? "";
$montant = isset($_GET["montant"]) ? floatval($_GET["montant"]) : 0;
$date = $_GET["date"] ?? "";
$operation = $_GET["operation"] ?? "";
$solde_brut = isset($_GET["solde"]) ? floatval($_GET["solde"]) : 0;
$annee_acad = $_GET['AnneeAcad'] ?? null;
$num_autoriz = $_GET['Num_Autoriz'] ?? null;

// 🔹 Vérifications de base
if (empty($beneficiaire)) {
    echo json_encode(['error' => true, 'message' => '❌ Saisissez le bénéficiaire.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($montant)) {
    echo json_encode(['error' => true, 'message' => '❌ Saisissez le montant pour cette opération.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if ($montant > $solde_brut) {
    echo json_encode(['error' => true, 'message' => '❌ Votre solde est inférieur au montant à décaisser.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($motif)) {
    echo json_encode(['error' => true, 'message' => '❌ Chaque opération a un Motif spécifique.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($date)) {
    echo json_encode(['error' => true, 'message' => '❌ Choisissez une date.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (empty($imputation)) {
    echo json_encode(['error' => true, 'message' => '❌ Choisissez un compte.'], JSON_UNESCAPED_UNICODE);
    exit;
}


// ================================================================
// 🔹 Traitement du décaissement USD
// ================================================================
if ($operation === "Dec_USD") {
   
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
    $session_id = session_id();

    echo json_encode([
        "success" => true,
        "message" => "✅ Décaissement USD effectué avec succès.",
        "num_piece" => $numero_piece,
        "session_id" => $session_id  
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ================================================================
// 🔹 Traitement du décaissement CDF
// ================================================================
elseif ($operation === "Dec_CDF") {
   


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
    $session_id = session_id();
    echo json_encode([
        "success" => true,
        "message" => "✅ Décaissement CDF effectué avec succès.",
        "num_suivant" => $numero_piece,
        "session_id" => $session_id   
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
