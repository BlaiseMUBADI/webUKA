<?php
include("../../../Connexion_BDD/Connexion_1.php");

header('Content-Type: application/json');

$raw = file_get_contents("php://input");

file_put_contents(__DIR__."/debug_raw.txt", $raw);

$data = json_decode($raw, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "success" => false,
        "message" => "JSON invalide : " . json_last_error_msg(),
        "raw" => $raw
    ]);
    exit;
}

$statut = "Effectuée";



$actionBtn = $data["Text_Btn"] ?? null;
$Annee = $data["AnneeAcad"] ?? null;


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
    $Annee = $data["AnneeAcad"] ?? null;

// --- ENCAISSEMENT USD ---
if ($actionBtn === "Encaisser USD") {
    $Motif_USD    = $data["Motif_USD"] ?? '';
    $Idserv       = $data["Idser_USD"] ?? '';
    $Montant      = isset($data["Montant_USD"]) ? floatval($data["Montant_USD"]) : 0;
    $deposant     = $data["Deposant_usd"] ?? '';
    $date_op_usd  = $data["Date_op_usd"] ?? null;
    $imputation   = $data["Imputation_usd"] ?? '';
    $num_pce      = $data["Num_Pce"] ?? null;

    if (empty($Motif_USD)) {
        echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']); exit;
    } 
     if (empty($Idserv)) {
        echo json_encode(['error' => true, 'message' => 'Préciser le service.']); exit;
    }
    if (empty($date_op_usd)) {
        echo json_encode(['error' => true, 'message' => 'Vous devez définir la date.']); exit;
    }
    if (empty($imputation)) {
        echo json_encode(['error' => true, 'message' => 'Préciser le compte dans lequel vous mettez la somme.']); exit;
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
    $stmtInsert->bindParam(':Date_Op', $date_op_usd);
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
    $Motif_CDF    = $data["Motif_CDF"] ?? '';
    $IdservCDF    = $data["Idser_CDF"] ?? '';
    $MontantCDF   = isset($data["Montant_CDF"]) ? floatval($data["Montant_CDF"]) : 0;
    $deposant     = $data["Deposant_cdf"] ?? '';
    $imputation   = $data["Imputation_cdf"] ?? '';
    $num_pceCDF   = $data["Num_Pce"] ?? null;
    $date_op_cdf  = $data["Date_op_CDF"] ?? null;

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
    $stmtInsert->bindParam(':Date_Op', $date_op_cdf);
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
