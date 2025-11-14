<?php

include("../../../Connexion_BDD/Connexion_1.php");

if (isset($_GET["Text_Btn"])) {
    $actionBtn = $_GET["Text_Btn"];
    $type = "";
    $devise = "";
    $texte = "";
    $statut = "Effectuée";

    
 
    

    if ($actionBtn == "Encaisser USD") {

    // LES VARIABLES POUR L'ENCAISSEMENT EN $
    $num_pce = $_GET["Num_Pce"];
    $Motif_USD = $_GET["Motif_USD"];
    $Idserv = $_GET["Idser_USD"];
    $Montant = $_GET["Montant_USD"];
    $Date_Encaiss = $_GET["Date_op_usd"];

        if (empty($Motif_USD)) { 
            echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']);
            exit;
        }
        if (empty($Montant)) { 
            echo json_encode(['error' => true, 'message' => 'Saissez le montant pour cette opération.']);
            exit;
        }

        $type = "Enc";
        $devise = "USD";
        $texte =$type.$devise;

        // 🔸 Vérifier l'existence du numéro de pièce
        $checkQuery = "SELECT COUNT(*) FROM numero_piece 
                       WHERE numero_pce = :num_pce 
                       AND type_operation = :type_oper 
                       AND devise = :devise";
        $stmtCheck = $con->prepare($checkQuery);
        $stmtCheck->bindParam(':num_pce', $num_pce);
        $stmtCheck->bindParam(':type_oper', $type);
        $stmtCheck->bindParam(':devise', $devise);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count == 0) {
            // 🔹 Insérer dans numero_piece si n'existe pas
            $insertQuery = "INSERT INTO numero_piece (numero_pce)
                            VALUES (:num_pce)";
            $stmtInsert = $con->prepare($insertQuery);
            $stmtInsert->bindParam(':type_oper', $type);
            $stmtInsert->bindParam(':devise', $devise);
            $stmtInsert->bindParam(':num_pce', $num_pce);
            $stmtInsert->execute();
        }

        // 🔹 Traitement de l'encaissement
        $tab_prefix_nombre = extraireTexteEtNombre($Idserv); 
        $chaine = $tab_prefix_nombre['texte'];
        $id = $tab_prefix_nombre['nombre'];

        if ($chaine == "fac") {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant, Numero_pce, Date_Oper, Statut)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut)";
        } else {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant, Numero_pce, Date_Oper, Statut)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut)";
        }

        $stmtInsert1 = $con->prepare($insert);
        $stmtInsert1->bindParam(':Motif', $Motif_USD);
        $stmtInsert1->bindParam(':Id_Service', $id);
        $stmtInsert1->bindParam(':Montant', $Montant);
        $stmtInsert1->bindParam(':Num_pce', $num_pce);
        $stmtInsert1->bindParam(':Date_Op', $Date_Encaiss);
        $stmtInsert1->bindParam(':Statut', $statut);
        $stmtInsert1->execute();

        // 🔸 Récupérer le prochain numéro de pièce (max + 1)
        $lastNumQuery = "SELECT MAX(numero_pce) as max_num FROM numero_piece 
                         WHERE type_operation = :type_oper AND devise = :devise";
        $stmtLast = $con->prepare($lastNumQuery);
        $stmtLast->bindParam(':type_oper', $type);
        $stmtLast->bindParam(':devise', $devise);
        $stmtLast->execute();
        $result = $stmtLast->fetch(PDO::FETCH_ASSOC);

        $nextNum = intval($result['max_num']) + 1;

        /// 🔸 Récupération du solde encaissement dollars
        $lastQuery = "SELECT SUM(Montant) as solde_usd FROM encaissement_caisse,numero_piece 
                    WHERE Statut=:statut 
                    AND encaissement_caisse.Numero_pce=numero_piece.numero_pce 
                    AND numero_piece.type_operation=:type_oper 
                    AND numero_piece.devise=:devise";
        $stmtsolde_usd = $con->prepare($lastQuery);
        $stmtsolde_usd->bindParam(':type_oper', $type);
        $stmtsolde_usd->bindParam(':devise', $devise);
        $stmtsolde_usd->bindParam(':statut', $statut);
        $stmtsolde_usd->execute();
        $resultat = $stmtsolde_usd->fetch(PDO::FETCH_ASSOC);

        // Vérification de la validité du résultat
        if ($resultat && isset($resultat['solde_usd'])) {
        $SoldeUsd = $resultat['solde_usd'];
        } else {
        $SoldeUsd = 0; // Si aucune valeur n'est trouvée, on retourne 0
        }

       

        echo json_encode([
        "success" => true,
        "message" => "Encaissement EN USD réussi",
        "next_numero_pce" => $nextNum,
        "solde_usd" => number_format($SoldeUsd, 2), // Affichage du solde formaté
        ]);
        exit;

       
    }//************************************ ENCAISSEMENT EN FRANC CONGOLAIS **********************************/
    elseif ($actionBtn == "Encaisser CDF") {
        // LES VARIABLES POUR L'ENCAISSEMENT EN CDF
    $num_pceCDF = $_GET["Num_PceCDF"];
    $Motif_CDF = $_GET["Motif_CDF"];
    $IdservCDF = $_GET["Idser_CDF"];
    $MontantCDF = $_GET["Montant_CDF"];
    $Date_Encaiss_CDF = $_GET["Date_op_CDF"];

        if (empty($Motif_CDF)) { 
            echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']);
            exit;
        }
        if (empty($MontantCDF)) { 
            echo json_encode(['error' => true, 'message' => 'Saissez le montant pour cette opération.']);
            exit;
        }

        $typeCDF = "Encaissement";
        $deviseCDF = "CDF";

        // 🔸 Vérifier l'existence du numéro de pièce
        $checkQuery = "SELECT COUNT(*) FROM numero_piece 
                       WHERE numero_pce = :num_pce 
                       AND type_operation = :type_oper 
                       AND devise = :devise";
        $stmtCheck = $con->prepare($checkQuery);
        $stmtCheck->bindParam(':num_pce', $num_pceCDF);
        $stmtCheck->bindParam(':type_oper', $typeCDF);
        $stmtCheck->bindParam(':devise', $deviseCDF);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count == 0) {
            // 🔹 Insérer dans numero_piece si n'existe pas
            $insertQuery = "INSERT INTO numero_piece (type_operation, devise, numero_pce)
                            VALUES (:type_oper, :devise, :num_pce)";
            $stmtInsert = $con->prepare($insertQuery);
            $stmtInsert->bindParam(':type_oper', $typeCDF);
            $stmtInsert->bindParam(':devise', $deviseCDF);
            $stmtInsert->bindParam(':num_pce', $num_pceCDF);
            $stmtInsert->execute();
        }

        // 🔹 Traitement de l'encaissement
        $tab_prefix_nombre = extraireTexteEtNombre($IdservCDF); 
        $chaine = $tab_prefix_nombre['texte'];
        $id = $tab_prefix_nombre['nombre'];

        if ($chaine == "fac") {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant, Numero_pce, Date_Oper, Statut)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut)";
        } else {
            $insert = "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant, Numero_pce, Date_Oper, Statut)
                       VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut)";
        }

        $stmtInsert1 = $con->prepare($insert);
        $stmtInsert1->bindParam(':Motif', $Motif_CDF);
        $stmtInsert1->bindParam(':Id_Service', $id);
        $stmtInsert1->bindParam(':Montant', $MontantCDF);
        $stmtInsert1->bindParam(':Num_pce', $num_pceCDF);
        $stmtInsert1->bindParam(':Date_Op', $Date_Encaiss_CDF);
        $stmtInsert1->bindParam(':Statut', $statut);
        $stmtInsert1->execute();

        // 🔸 Récupérer le prochain numéro de pièce (max + 1)
        $lastNumQuery = "SELECT MAX(numero_pce) as max_num FROM numero_piece 
                         WHERE type_operation = :type_oper AND devise = :devise";
        $stmtLast = $con->prepare($lastNumQuery);
        $stmtLast->bindParam(':type_oper', $typeCDF);
        $stmtLast->bindParam(':devise', $deviseCDF);
        $stmtLast->execute();
        $result = $stmtLast->fetch(PDO::FETCH_ASSOC);

        $nextNum = intval($result['max_num']) + 1;

         /// 🔸 Récupération du solde encaissement franc congolais
         $lastQuery = "SELECT numero_piece.devise, SUM(encaissement_caisse.Montant) as solde_cdf 
FROM encaissement_caisse 
INNER JOIN numero_piece ON encaissement_caisse.Numero_pce = numero_piece.numero_pce
WHERE encaissement_caisse.Statut = :statut 
  AND numero_piece.type_operation = :type_oper
GROUP BY numero_piece.devise";
            $stmtsolde_cdf = $con->prepare($lastQuery);
            $stmtsolde_cdf->bindParam(':type_oper', $typeCDF);
            $stmtsolde_cdf->bindParam(':devise', $deviseCDF);
            $stmtsolde_cdf->bindParam(':statut', $statut);
            $stmtsolde_cdf->execute();
            $result = $stmtsolde_cdf->fetch(PDO::FETCH_ASSOC);

            // Vérification de la validité du résultat
            if ($result && isset($result['solde_cdf'])) {
            $SoldeCDF = $result['solde_cdf'];
            } else {
            $SoldeCDF = 0; // Si aucune valeur n'est trouvée, on retourne 0
            }
        echo json_encode([
            "success" => true,
            "message" => "Encaissement en CDF réussi",
            "next_numero_pce" => $nextNum,
            "Solde_cdf" => number_format($SoldeCDF, 2) // Affichage du solde formaté

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
