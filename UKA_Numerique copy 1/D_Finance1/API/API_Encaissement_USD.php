<?php

include("../../../Connexion_BDD/Connexion_1.php");

if (isset($_GET["Text_Btn"])) {
    $actionBtn = $_GET["Text_Btn"];
    $Sufix = "";
    $devise = "";
    $numero_piece = "";
    $statut = "Effectuée";

    date_default_timezone_set('Africa/Lubumbashi');
 
    

    if ($actionBtn == "Encaisser USD") {

        // LES VARIABLES POUR L'ENCAISSEMENT EN $
        $Motif_USD = $_GET["Motif_USD"];
        $Idserv = $_GET["Idser_USD"];
        $Montant = $_GET["Montant_USD"];
        $Date_Encaiss = date("Y-m-d H:i:s");
        $deposant = $_GET["Deposant_usd"];
        $imputaton = $_GET["Imputation_usd"];
    
        if (empty($Motif_USD)) { 
            echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']);
            exit;
        }
        if (empty($Montant)) { 
            echo json_encode(['error' => true, 'message' => 'Saisissez le montant pour cette opération.']);
            exit;
        }
    
        // === Générer le numéro de pièce automatiquement ===
        $prefix = "Enc_usd_";
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
    
        // === Insérer dans numero_piece si inexistant ===
        $checkQuery = "SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce";
        $stmtCheck = $con->prepare($checkQuery);
        $stmtCheck->bindParam(':num_pce', $numero_piece);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();
    
        if ($count == 0) {
            $insertQuery = "INSERT INTO numero_piece (numero_pce) 
                            VALUES (:num_pce)";
            $stmtInsert = $con->prepare($insertQuery);
            $stmtInsert->bindParam(':num_pce', $numero_piece);
            $stmtInsert->execute();
        }
    
        // === Enregistrement encaissement caisse ===
        $tab_prefix_nombre = extraireTexteEtNombre($Idserv); 
        $chaine = $tab_prefix_nombre['texte'];
        $id = $tab_prefix_nombre['nombre'];
    
        if ($chaine == "fac") {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant, Numero_pce, Date_Oper, Statut, Deposant,Imputation)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depo,:imputation)";
        } else {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant, Numero_pce, Date_Oper, Statut, Deposant,Imputation)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depo,:imputation)";
        }
    
        $stmtInsert1 = $con->prepare($insert);
        $stmtInsert1->bindParam(':Motif', $Motif_USD);
        $stmtInsert1->bindParam(':Id_Service', $id);
        $stmtInsert1->bindParam(':Montant', $Montant);
        $stmtInsert1->bindParam(':Num_pce', $numero_piece);
        $stmtInsert1->bindParam(':Date_Op', $Date_Encaiss);
        $stmtInsert1->bindParam(':Statut', $statut);
        $stmtInsert1->bindParam(':depo', $deposant);
        $stmtInsert1->bindParam(':imputation', $imputaton);
        $stmtInsert1->execute();
    
        // === Récupérer le DERNIER numéro inséré ===
        $lastNumQuery = " SELECT numero_pce 
            FROM numero_piece 
            WHERE numero_pce LIKE :prefix 
            ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(:prefix_clean) + 1) AS UNSIGNED) DESC 
            LIMIT 1";
        $stmtLast = $con->prepare($lastNumQuery);
        $stmtLast->execute([
            ':prefix' => $prefix . '%',
            ':prefix_clean' => $prefix
        ]);
        $result = $stmtLast->fetch(PDO::FETCH_ASSOC);
        $nextNumeroPiece = ($numericPart + 1);

        
        $query_usd = "SELECT ROUND(SUM(encaissement_caisse.Montant), 2) as solde_usd 
                      FROM encaissement_caisse
                      INNER JOIN numero_piece ON encaissement_caisse.Numero_pce = numero_piece.numero_pce
                      WHERE encaissement_caisse.Statut = :statut 
                      AND encaissement_caisse.Numero_pce LIKE :prefix";
        
        $stmt_usd = $con->prepare($query_usd);
        $prefixLikeUSD = $prefix . '%';
        $stmt_usd->bindParam(':statut', $statut);
        $stmt_usd->bindParam(':prefix', $prefixLikeUSD);
        $stmt_usd->execute();
        $resultUSD = $stmt_usd->fetch(PDO::FETCH_ASSOC);
        $SoldeUSD = $resultUSD['solde_usd'] ?? 0;

        echo json_encode([
            "success" => true,
            "message" => "Encaissement en USD réussi",
            "NumeroPieceSuivant" => $nextNumeroPiece,
            "SOLDE_usd" => $SoldeUSD
        ]);
        exit;
        
    }
    

       
    //************************************ ENCAISSEMENT EN FRANC CONGOLAIS **********************************/
    elseif ($actionBtn == "Encaisser CDF") {
        // === VARIABLES POUR L'ENCAISSEMENT EN CDF
        $Motif_CDF = $_GET["Motif_CDF"];
        $IdservCDF = $_GET["Idser_CDF"];
        $MontantCDF = $_GET["Montant_CDF"];
        $Date_Encaiss_CDF = date("Y-m-d H:i:s");
        $deposant = $_GET["Deposant_cdf"];
        $imputaton = $_GET["Imputation_cdf"];

        if (empty($Motif_CDF)) { 
            echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']);
            exit;
        }
        if (empty($MontantCDF)) { 
            echo json_encode(['error' => true, 'message' => 'Saisissez le montant pour cette opération.']);
            exit;
        }
    
        $prefix = "Enc_cdf_";
        $typeCDF = "Encaissement";
        $deviseCDF = "CDF";
        
        // === Générer le numéro de pièce automatiquement
        $query = " SELECT numero_pce 
            FROM numero_piece 
            WHERE numero_pce LIKE :prefix 
            ORDER BY CAST(SUBSTRING(numero_pce, LENGTH(:prefix_clean) + 1) AS UNSIGNED) DESC 
            LIMIT 1";
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
    
        $numero_pieceCDF = $prefix . $numericPart;
    
        // === Insérer dans numero_piece si inexistant
        $checkQuery = "SELECT COUNT(*) FROM numero_piece WHERE numero_pce = :num_pce";
        $stmtCheck = $con->prepare($checkQuery);
        $stmtCheck->bindParam(':num_pce', $numero_pieceCDF);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();
    
        if ($count == 0) {
            $insertQuery = "INSERT INTO numero_piece (numero_pce)
                            VALUES (:num_pce)";
            $stmtInsert = $con->prepare($insertQuery);
            $stmtInsert->bindParam(':num_pce', $numero_pieceCDF);
            $stmtInsert->execute();
        }
    
        // === Enregistrement encaissement caisse
        $tab_prefix_nombre = extraireTexteEtNombre($IdservCDF); 
        $chaine = $tab_prefix_nombre['texte'];
        $id = $tab_prefix_nombre['nombre'];
    
        if ($chaine == "fac") {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant, Numero_pce, Date_Oper, Statut, Deposant,Imputation)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depos,:imputation)";
        } else {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant, Numero_pce, Date_Oper, Statut,Deposant,Imputation)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut, :depos,:imputation)";
        }
    
        $stmtInsert1 = $con->prepare($insert);
        $stmtInsert1->bindParam(':Motif', $Motif_CDF);
        $stmtInsert1->bindParam(':Id_Service', $id);
        $stmtInsert1->bindParam(':Montant', $MontantCDF);
        $stmtInsert1->bindParam(':Num_pce', $numero_pieceCDF);
        $stmtInsert1->bindParam(':Date_Op', $Date_Encaiss_CDF);
        $stmtInsert1->bindParam(':Statut', $statut);
        $stmtInsert1->bindParam(':depos', $deposant);
        $stmtInsert1->bindParam(':imputation', $imputaton);
        $stmtInsert1->execute();
    
        // === Génération du prochain numéro à utiliser (prochaine opération)
        $nextNumeroPieceCDF =($numericPart + 1);
    
        $query_cdf = "SELECT ROUND(SUM(encaissement_caisse.Montant), 2) as solde_cdf 
                      FROM encaissement_caisse
                      INNER JOIN numero_piece ON encaissement_caisse.Numero_pce = numero_piece.numero_pce
                      WHERE encaissement_caisse.Statut = :statut 
                      AND encaissement_caisse.Numero_pce LIKE :prefix";
        
        $stmt_cdf = $con->prepare($query_cdf);
        $prefixLikeCDF = $prefix . '%';
        $stmt_cdf->bindParam(':statut', $statut);
        $stmt_cdf->bindParam(':prefix', $prefixLikeCDF);
        $stmt_cdf->execute();
        $resultCDF = $stmt_cdf->fetch(PDO::FETCH_ASSOC);
        $SoldeCDF = $resultCDF['solde_cdf'] ?? 0;
    
        echo json_encode([
            "success" => true,
            "message" => "Encaissement en CDF réussi",
            "next_numero_pce" => $nextNumeroPieceCDF,
            "SOLDE_cdf" => $SoldeCDF
        ]);
        exit;
    }
    
}


// Fonction pour extraire texte + nombre depuis l'identifiant de service
function extraireTexteEtNombre($chaine) {
    preg_match('/([a-zA-Z]+)\s*(\d+)/', $chaine, $matches);
    return array('texte' => $matches[1], 'nombre' => $matches[2]);
}
?>
