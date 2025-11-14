<?php
include("../../../Connexion_BDD/Connexion_1.php");

// Déclaration des variables
$statut = "Effectuée";
$prefix = "Dec_usd_";

// Récupération de la date envoyée depuis le frontend
//$data = json_decode(file_get_contents("php://input"), true); // Lire JSON envoyé
//$date_jr = $data['date'] ?? date('Y-m-d'); // Utiliser la date reçue ou celle du jour

$query_usd = "SELECT SUM(decaissement_caisse.Montant) as total_dec_usd_jr 
              FROM decaissement_caisse
              INNER JOIN numero_piece 
                  ON decaissement_caisse.Num_piece = numero_piece.numero_pce
              WHERE decaissement_caisse.Statut = :statut 
              AND decaissement_caisse.Num_piece LIKE :prefix";

$stmt_usd = $con->prepare($query_usd);
$prefixLikeUSD = $prefix . '%';

$stmt_usd->bindParam(':statut', $statut);
$stmt_usd->bindParam(':prefix', $prefixLikeUSD);
//$stmt_usd->bindParam(':date_oper', $date_jr);

$stmt_usd->execute();
$soldeDec = $stmt_usd->fetch(PDO::FETCH_ASSOC);
$Soldedec = $soldeDec['total_dec_usd_jr'] ?? 0;



$prefix = "Dec_cdf_";
$query_cdf = "SELECT SUM(decaissement_caisse.Montant) as total_dec_cdf_jr 
              FROM decaissement_caisse
              INNER JOIN numero_piece 
                  ON decaissement_caisse.Num_piece = numero_piece.numero_pce
              WHERE decaissement_caisse.Statut = :statut 
              AND decaissement_caisse.Num_piece LIKE :prefix";

$stmt_cdf = $con->prepare($query_cdf);
$prefixLikeCDF = $prefix . '%';

$stmt_cdf->bindParam(':statut', $statut);
$stmt_cdf->bindParam(':prefix', $prefixLikeCDF);
//$stmt_cdf->bindParam(':date_oper', $date_jr);

$stmt_cdf->execute();
$soldeDec_cdf = $stmt_cdf->fetch(PDO::FETCH_ASSOC);
$Soldedec_cdf = $soldeDec_cdf['total_dec_cdf_jr'] ?? 0;

// ======== Réponse JSON ========
header('Content-Type: application/json');
echo json_encode([
    "Solde__dec_usd" => number_format($Soldedec, 2),
    "Solde__dec_cdf" => number_format($Soldedec_cdf, 2)
]);
exit;

?>
