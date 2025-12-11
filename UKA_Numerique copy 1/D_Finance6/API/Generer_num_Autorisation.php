<?php
include("../../../Connexion_BDD/Connexion_1.php");

if (!isset($_GET['devise'])) {
    echo json_encode(['error' => 'Aucune devise fournie']);
    exit;
}

$devise = $_GET['devise'];

if ($devise === 'USD') {
    $prefix = 'Auto_Dec_usd_';
} elseif ($devise === 'CDF') {
    $prefix = 'Auto_Dec_cdf_';
} else {
    echo json_encode(['error' => 'Devise inconnue']);
    exit;
}

$reponse = $con->query("SELECT numero_pce 
    FROM numero_autorisation 
    WHERE numero_pce LIKE '$prefix%' 
    ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
    LIMIT 1
");

if ($ligne = $reponse->fetch()) {
    $lastNumero = $ligne['numero_pce'];
    $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
} else {
    $numericPart = 1;
}

echo json_encode([
    'numero' => $prefix . $numericPart
]);
?>
