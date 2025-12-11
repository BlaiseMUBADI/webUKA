<?php

include("../../../Connexion_BDD/Connexion_1.php");

if (isset($_GET["actionBtn"])) {
    $actionBtn = $_GET["Text_Btn"];
    $type = "";
    $devise = "";
    $statut = "Effectuée";

    // LES VARIABLES POUR L'ENCAISSEMENT EN $
    $num_pce = $_GET["Num_Pce"];
    $Motif_USD = $_GET["Motif_USD"];
    $Idserv = $_GET["Idser_USD"];
    $Montant = $_GET["Montant_USD"];
    $Date_Encaiss = $_GET["Date_op_usd"];

    // LES VARIABLES POUR L'ENCAISSEMENT EN CDF
    $num_pceCDF = $_GET["Num_PceCDF"];
    $Motif_CDF = $_GET["Motif_CDF"];
    $IdservCDF = $_GET["Idser_CDF"];
    $MontantCDF = $_GET["Montant_CDF"];
    $Date_Encaiss_CDF = $_GET["Date_op_CDF"];


    if ($actionBtn == "Encaisser USD") {
        if (empty($Motif_USD)) { 
            echo json_encode(['error' => true, 'message' => 'Chaque opération a un Motif spécifique.']);
            exit;
        }
        if (empty($Montant)) { 
            echo json_encode(['error' => true, 'message' => 'Saissez le montant pour cette opération.']);
            exit;
        }

        $type = "Encaissement";
        $devise = "USD";

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
            $insertQuery = "INSERT INTO numero_piece (type_operation, devise, numero_pce)
                            VALUES (:type_oper, :devise, :num_pce)";
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

        echo json_encode([
            "success" => true,
            "message" => "Encaissement réussi",
            "next_numero_pce" => $nextNum
        ]);
        exit;
    }
    //************************************* POUR LES ENCAISSEMENT EN CDF ***********************/
    elseif($actionBtn == "Encaisser USD")
    {

    }
}

// Fonction pour extraire texte + nombre depuis l'identifiant de service
function extraireTexteEtNombre($chaine) {
    preg_match('/([a-zA-Z]+)\s*(\d+)/', $chaine, $matches);
    return array('texte' => $matches[1], 'nombre' => $matches[2]);
}
?>
