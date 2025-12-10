<?php
include("../../../Connexion_BDD/Connexion_1.php");
header("Content-Type: application/json");

// ⚡ On peut recevoir en POST classique ou en JSON
$input = $_POST;
if (empty($input)) {
    $input = json_decode(file_get_contents("php://input"), true);
}

if (!isset($input['devise'])) {
    echo json_encode(['success' => false, 'error' => 'Aucune devise fournie']);
    exit;
}

$devise = $input['devise'];
$Beneficiaire = $input['beneficiaire'];
$prefix = "";

if ($devise === 'USD') {
    $prefix = "Auto_Dec_usd_";
} elseif ($devise === 'CDF') {
    $prefix = "Auto_Dec_cdf_";
} else {
    echo json_encode(['success' => false, 'error' => 'Devise inconnue']);
    exit;
}

// ==========================
// Génération du numéro unique
// ==========================
$reponse = $con->prepare("SELECT numero_pce 
    FROM numero_autorisation 
    WHERE numero_pce LIKE ?
    ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(?) + 1) AS UNSIGNED) DESC 
    LIMIT 1
");
$likePrefix = $prefix . '%';
$reponse->execute([$likePrefix, $prefix]);

if ($ligne = $reponse->fetch()) {
    $lastNumero = $ligne['numero_pce'];
    $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
} else {
    $numericPart = 1;
}
$numeroFinal = $prefix . $numericPart;

// ==========================
// Transaction pour cohérence
// ==========================
try {
    $con->beginTransaction();

    // Insertion du numéro dans numero_autorisation
    $insert = $con->prepare("INSERT INTO numero_autorisation (numero_pce) VALUES (?)");
    $insert->execute([$numeroFinal]);

    // ==========================
    // Traitement des lignes reçues
    // ==========================
    if (isset($input["lignes"]) && is_array($input["lignes"])) {
        $date_ajout = date("Y-m-d H:m:s");

        $stmt = $con->prepare("INSERT INTO autorisation_depense 
            (Num_pce, Motif, Beneficiaire, Montant, Imputation, Date_ajout) 
            VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($input["lignes"] as $ligne) {
            $motif = $ligne["Motif"] ?? '';
            
            $montant = floatval($ligne["Montant"] ?? 0);
            $imputation = intval($ligne["Imputation"] ?? 0);

            $stmt->execute([$numeroFinal, $motif, $Beneficiaire, $montant, $imputation, $date_ajout]);
        }
    }

    $con->commit();
    echo json_encode(['success' => true, 'numero' => $numeroFinal]);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
