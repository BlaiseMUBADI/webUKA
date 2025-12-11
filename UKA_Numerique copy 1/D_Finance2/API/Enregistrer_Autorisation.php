<?php
include("../../../Connexion_BDD/Connexion_1.php");

if (!isset($_POST['devise'])) {
    echo json_encode(['success' => false, 'error' => 'Aucune devise fournie']);
    exit;
}

$devise = $_POST['devise'];
$prefix = "";

if ($devise === 'USD') {
    $prefix = "Auto_Dec_usd_";
} elseif ($devise === 'CDF') {
    $prefix = "Auto_Dec_cdf_";
} else {
    echo json_encode(['success' => false, 'error' => 'Devise inconnue']);
    exit;
}

// Cherche le dernier numéro existant
$reponse = $con->prepare("
    SELECT numero_pce 
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

// Vérifie si ce numéro existe (précaution)
$verif = $con->prepare("SELECT COUNT(*) FROM numero_autorisation WHERE numero_pce = ?");
$verif->execute([$numeroFinal]);

if ($verif->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'error' => 'Numéro déjà existant']);
    exit;
}

// Insertion
$insert = $con->prepare("INSERT INTO numero_autorisation (numero_pce) VALUES (?)");
if ($insert->execute([$numeroFinal])) {
    echo json_encode(['success' => true, 'numero' => $numeroFinal]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur d’insertion']);
}
?>
