<?php
include("../../../Connexion_BDD/Connexion_1.php");
// Empêcher les warnings/notice d'être envoyés au JS
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Forcer le JSON
header('Content-Type: application/json');
// Définir le statut commun
$statut = "Effectuée";

// Récupération sécurisée des paramètres
$actionBtn = isset($_GET["Text_Btn"]) ? $_GET["Text_Btn"] : null;

// Fonction pour vérifier si un numéro de pièce existe
function numeroPieceExiste($num_pce) {
    global $con;
    $stmt = $con->prepare("SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce");
    $stmt->bindParam(':num_pce', $num_pce);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

// Fonction d'extraction texte et nombre
function extraireTexteEtNombre($chaine) {
    preg_match('/([a-zA-Z]+)\s*(\d+)/', $chaine, $matches);
    return array('texte' => $matches[1] ?? '', 'nombre' => $matches[2] ?? 0);
}
    $Annee = isset($_GET["AnneeAcad"]) ? $_GET["AnneeAcad"] : null;

// --- ENCAISSEMENT USD ---
if ($actionBtn === "Encaisser USD") {
    $Motif_USD = isset($_GET["Motif_USD"]) ? $_GET["Motif_USD"] : '';
    $Idserv = isset($_GET["Idser_USD"]) ? $_GET["Idser_USD"] : '';
    $Montant = isset($_GET["Montant_USD"]) ? floatval($_GET["Montant_USD"]) : 0;
    $deposant = isset($_GET["Deposant_usd"]) ? $_GET["Deposant_usd"] : '';
    $imputation = isset($_GET["Imputation_usd"]) ? $_GET["Imputation_usd"] : '';
    $num_pce = isset($_GET["Num_Pce"]) ? $_GET["Num_Pce"] : null;

    if (empty($Motif_USD)) {
        echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']); exit;
    }
    if ($Montant <= 0) {
        echo json_encode(['error' => true, 'message' => 'Saisissez le montant pour cette opération.']); exit;
    }

    $prefix = "Enc_usd_";

    // Gestion du numéro de pièce
    if (!empty($num_pce) && numeroPieceExiste($prefix . $num_pce)) {
        $numero_piece = $prefix . $num_pce;
    } else {
        // Génération automatique
        $stmt = $con->prepare("SELECT numero_pce 
                               FROM numero_piece 
                               WHERE numero_pce LIKE :prefix 
                               ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(:prefix_clean) + 1) AS UNSIGNED) DESC 
                               LIMIT 1");
        $stmt->execute([':prefix' => $prefix . '%', ':prefix_clean' => $prefix]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $numericPart = intval(substr($row['numero_pce'], strlen($prefix))) + 1;
        } else {
            $numericPart = 1;
        }

        $numero_piece = $prefix . $numericPart;

        if (!numeroPieceExiste($numero_piece)) {
            $stmtInsert = $con->prepare("INSERT INTO numero_piece (numero_pce) VALUES (:num_pce)");
            $stmtInsert->bindParam(':num_pce', $numero_piece);
            $stmtInsert->execute();
        }
    }

    // Extraire l'ID service
    $tab_prefix_nombre = extraireTexteEtNombre($Idserv);
    $id = $tab_prefix_nombre['nombre'] ?? 0;

    $insert = ($tab_prefix_nombre['texte'] === "fac")
        ? "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant, Numero_pce, Date_Oper, Statut, Deposant, Imputation, IdAnnee)
           VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depos, :imputation, :annee)"
        : "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant, Numero_pce, Date_Oper, Statut, Deposant, Imputation, IdAnnee)
           VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depos, :imputation, :annee)";

    $stmtInsert = $con->prepare($insert);
    $stmtInsert->bindParam(':Motif', $Motif_USD);
    $stmtInsert->bindParam(':Id_Service', $id);
    $stmtInsert->bindParam(':Montant', $Montant);
    $stmtInsert->bindParam(':Num_pce', $numero_piece);
    $stmtInsert->bindParam(':Date_Op', date("Y-m-d H:i:s"));
    $stmtInsert->bindParam(':Statut', $statut);
    $stmtInsert->bindParam(':depos', $deposant);
    $stmtInsert->bindParam(':imputation', $imputation);
    $stmtInsert->bindParam(':annee', $Annee);
    $stmtInsert->execute();

    // Calcul du solde USD
    $stmtUSD = $con->prepare("SELECT ROUND(SUM(Montant),2) as solde_usd 
                              FROM encaissement_caisse 
                              WHERE Statut=:statut AND Numero_pce LIKE :prefix");
    $stmtUSD->bindParam(':statut', $statut);
    $stmtUSD->bindValue(':prefix', $prefix . '%');
    $stmtUSD->execute();
    $SoldeUSD = $stmtUSD->fetch(PDO::FETCH_ASSOC)['solde_usd'] ?? 0;

    $response = [
        "success" => true,
        "message" => "Encaissement en USD réussi",
        "SOLDE_usd" => $SoldeUSD
    ];

    if (empty($num_pce) || !numeroPieceExiste($prefix . $num_pce)) {
        $response["NumeroPieceSuivant"] = $numericPart + 1;
    }

    echo json_encode($response);
    exit;
}

// --- ENCAISSEMENT CDF ---
elseif ($actionBtn === "Encaisser CDF") {
    $Motif_CDF = isset($_GET["Motif_CDF"]) ? $_GET["Motif_CDF"] : '';
    $IdservCDF = isset($_GET["Idser_CDF"]) ? $_GET["Idser_CDF"] : '';
    $MontantCDF = isset($_GET["Montant_CDF"]) ? floatval($_GET["Montant_CDF"]) : 0;
    $deposant = isset($_GET["Deposant_cdf"]) ? $_GET["Deposant_cdf"] : '';
    $imputation = isset($_GET["Imputation_cdf"]) ? $_GET["Imputation_cdf"] : '';
    $num_pceCDF = isset($_GET["Num_PceCDF"]) ? $_GET["Num_PceCDF"] : null;

    if (empty($Motif_CDF)) {
        echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']); exit;
    }
    if ($MontantCDF <= 0) {
        echo json_encode(['error' => true, 'message' => 'Saisissez le montant pour cette opération.']); exit;
    }

    $prefix = "Enc_cdf_";

    if (!empty($num_pceCDF) && numeroPieceExiste($prefix . $num_pceCDF)) {
        $numero_pieceCDF = $prefix . $num_pceCDF;
    } else {
        $stmt = $con->prepare("SELECT numero_pce 
                               FROM numero_piece 
                               WHERE numero_pce LIKE :prefix 
                               ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(:prefix_clean) + 1) AS UNSIGNED) DESC 
                               LIMIT 1");
        $stmt->execute([':prefix' => $prefix . '%', ':prefix_clean' => $prefix]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $numericPart = intval(substr($row['numero_pce'], strlen($prefix))) + 1;
        } else {
            $numericPart = 1;
        }

        $numero_pieceCDF = $prefix . $numericPart;

        if (!numeroPieceExiste($numero_pieceCDF)) {
            $stmtInsert = $con->prepare("INSERT INTO numero_piece (numero_pce) VALUES (:num_pce)");
            $stmtInsert->bindParam(':num_pce', $numero_pieceCDF);
            $stmtInsert->execute();
        }
    }

    // Extraire l'ID service
    $tab_prefix_nombre = extraireTexteEtNombre($IdservCDF);
    $id = $tab_prefix_nombre['nombre'] ?? 0;

    $insert = ($tab_prefix_nombre['texte'] === "fac")
       ? "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant, Numero_pce, Date_Oper, Statut, Deposant, Imputation, IdAnnee)
           VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depos, :imputation, :annee)"
        : "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant, Numero_pce, Date_Oper, Statut, Deposant, Imputation, IdAnnee)
           VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depos, :imputation, :annee)";

    $stmtInsert = $con->prepare($insert);
    $stmtInsert->bindParam(':Motif', $Motif_CDF);
    $stmtInsert->bindParam(':Id_Service', $id);
    $stmtInsert->bindParam(':Montant', $MontantCDF);
    $stmtInsert->bindParam(':Num_pce', $numero_pieceCDF);
    $stmtInsert->bindParam(':Date_Op', date("Y-m-d H:i:s"));
    $stmtInsert->bindParam(':Statut', $statut);
    $stmtInsert->bindParam(':depos', $deposant);
    $stmtInsert->bindParam(':imputation', $imputation);
    $stmtInsert->bindParam(':annee', $Annee);
    $stmtInsert->execute();

    // Calcul du solde CDF
    $stmtCDF = $con->prepare("SELECT ROUND(SUM(Montant),2) as solde_cdf 
                              FROM encaissement_caisse 
                              WHERE Statut=:statut AND Numero_pce LIKE :prefix");
    $stmtCDF->bindParam(':statut', $statut);
    $stmtCDF->bindValue(':prefix', $prefix . '%');
    $stmtCDF->execute();
    $SoldeCDF = $stmtCDF->fetch(PDO::FETCH_ASSOC)['solde_cdf'] ?? 0;

    $response = [
        "success" => true,
        "message" => "Encaissement en CDF réussi",
        "SOLDE_cdf" => $SoldeCDF
    ];

    if (empty($num_pceCDF) || !numeroPieceExiste($prefix . $num_pceCDF)) {
        $response["NumeroPieceSuivant"] = $numericPart + 1;
    }

    echo json_encode($response);
    exit;
}
?>
