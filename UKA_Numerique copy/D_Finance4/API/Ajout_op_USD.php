<?php
include("../../../Connexion_BDD/Connexion_1.php");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // 🔹 Commencer une transaction
    $con->beginTransaction();

    if (isset($_GET["Num_Pce"]) && isset($_GET["Text_Btn"])) {
        $num_pce = $_GET["Num_Pce"];
        $actionBtn = $_GET["Text_Btn"];
        $type = "";
        $devise = "";
        //LES VARIABLES POUR L'ENCAISSEMENT EN $
        $Motif_USD = $_GET["Motif_USD"];
        $Idserv = $_GET["Idser_USD"];
        $Montant = $_GET["Montant_USD"];
        $Date_Encaiss = $_GET["Date_op_usd"];
        $statut = "Effectuée";

        if ($actionBtn == "Encaisser USD") {
            $type = "Encaissement";
            $devise = "USD";

            // 🔹 Insertion du nouveau numéro
            $insertQuery = "INSERT INTO numero_piece (type_operation, devise, numero_pce)
                            VALUES (:type_oper, :devise, :num_pce)";
            $stmtInsert = $con->prepare($insertQuery);
            $stmtInsert->bindParam(':type_oper', $type);
            $stmtInsert->bindParam(':devise', $devise);
            $stmtInsert->bindParam(':num_pce', $num_pce);
            $stmtInsert->execute();

            // 🔹 Insertion des détails d'encaissement

            if (!empty($Idserv)) 
            {
    
                $sql_insert="";
                $tab_prefix_nombre = extraireTexteEtNombre($Idserv); // Nous 
    
                $chaine=$tab_prefix_nombre['texte'];
                $id=$tab_prefix_nombre['nombre'];
    
    
    
                if($chaine=="fac")
                {
                    $insert = "INSERT INTO encaissement_caisse (Motif, Id_filiere, Montant,Numero_pce, Date_Oper, Statut)
                            VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut)";
    
                }
                else
                {
                    $insert = "INSERT INTO encaissement_caisse (Motif, Id_Service, Montant,Numero_pce, Date_Oper, Statut)
                            VALUES (:Motif, :Id_Service, :Montant, :Num_pce, :Date_Op, :Statut)";
                }
                $stmtInsert1 = $con->prepare($insert);
                $stmtInsert1->bindParam(':Motif', $Motif_USD);
                $stmtInsert1->bindParam(':Id_Service', $Idserv);
                $stmtInsert1->bindParam(':Montant', $Montant);
                $stmtInsert1->bindParam(':Num_pce', $num_pce);
                $stmtInsert1->bindParam(':Date_Op', $Date_Encaiss);
                $stmtInsert1->bindParam(':Statut', $statut);
                $stmtInsert1->execute();
            }

          
        }
    }

    // 🔹 Sélectionner le dernier numéro de pièce
    $query = "SELECT numero_pce FROM numero_piece 
              WHERE type_operation = 'Encaissement' 
              AND devise = 'USD' 
              ORDER BY numero_pce DESC LIMIT 1";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $nouveauNumero = ($result) ? intval($result["numero_pce"]) + 1 : 1;

    // ✅ Si tout est OK, valider la transaction
    $con->commit();

    echo json_encode(["nouveau_numero" => $nouveauNumero]);

} catch (PDOException $e) {
    // ❌ En cas d'erreur, annuler la transaction
    $con->rollBack();
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}

// 🔹 Fermer la connexion PDO
$con = null;

?>
