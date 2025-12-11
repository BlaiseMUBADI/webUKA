<?php
include("../../../Connexion_BDD/Connexion_1.php");

// 🔹 USD encaissement
$prefix = 'Enc_usd_';
$reponse = $con->query("SELECT numero_pce FROM numero_piece WHERE numero_pce LIKE '$prefix%' ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix')+1) AS UNSIGNED) DESC LIMIT 1");
if ($ligne = $reponse->fetch()) {
    $lastNumero = $ligne['numero_pce'];
    $numUSD = intval(substr($lastNumero, strlen($prefix))) + 1;
} else {
    $numUSD = 1;
}

// 🔹 CDF encaissement
$prefix = 'Enc_cdf_';
$reponse = $con->query("SELECT numero_pce FROM numero_piece WHERE numero_pce LIKE '$prefix%' ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix')+1) AS UNSIGNED) DESC LIMIT 1");
if ($ligne = $reponse->fetch()) {
    $lastNumero = $ligne['numero_pce'];
    $numCDF = intval(substr($lastNumero, strlen($prefix))) + 1;
} else {
    $numCDF = 1;
}

// 🔹 Décaissement USD
$prefix = 'Dec_usd_';
$reponse = $con->query("SELECT numero_pce FROM numero_piece WHERE numero_pce LIKE '$prefix%' ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix')+1) AS UNSIGNED) DESC LIMIT 1");
if ($ligne = $reponse->fetch()) {
    $lastNumero = $ligne['numero_pce'];
    $numDecUSD = intval(substr($lastNumero, strlen($prefix))) + 1;
} else {
    $numDecUSD = 1;
}

// 🔹 Décaissement CDF
$prefix = 'Dec_cdf_';
$reponse = $con->query("SELECT numero_pce FROM numero_piece WHERE numero_pce LIKE '$prefix%' ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix')+1) AS UNSIGNED) DESC LIMIT 1");
if ($ligne = $reponse->fetch()) {
    $lastNumero = $ligne['numero_pce'];
    $numDecCDF = intval(substr($lastNumero, strlen($prefix))) + 1;
} else {
    $numDecCDF = 1;
}

// 🔹 Retour JSON
echo json_encode([
    "success" => true,
    "numUSD" => $numUSD,
    "numCDF" => $numCDF,
    "numDecUSD" => $numDecUSD,
    "numDecCDF" => $numDecCDF
]);
