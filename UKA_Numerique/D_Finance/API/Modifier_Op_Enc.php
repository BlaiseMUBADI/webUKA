<?php
// Connexion à la base de données
include("../../../Connexion_BDD/Connexion_1.php");

// Définir l'en-tête pour le retour en JSON
header('Content-Type: application/json');

// Vérifier que les paramètres sont bien fournis
if (!isset($_GET['type'])) {
    echo json_encode(["error" => "Le paramètre 'type' est requis"]);
    exit;
}

$typeEncaissement = $_GET['type'];

if (($typeEncaissement === "USD" || $typeEncaissement === "CDF") && 
    (!isset($_GET['date1'], $_GET['date2']))) {
    echo json_encode(["error" => "Les paramètres date1 et date2 sont requis pour les types USD et CDF"]);
    exit;
}




$typeEncaissement = $_GET['type'];


try {
    
    if ($typeEncaissement === "modifier") {
        if ($devise = $_GET['dev']==="USD")
        {
             $prefix = "Enc_usd_";
        }
        else {
             $prefix = "Enc_cdf_";
        } 

        $id = $_GET["id_op"];
        $Motif = $_GET["Motif"];
        $Montant = $_GET["Montant"];
        $Date_Encaiss = $_GET["Date_op"];
        $deposant = $_GET["Deposant"];
        $numero_pce = $_GET["Num_Pce"]; 
        $numero_piece = $prefix . $numero_pce;
        $update = "UPDATE encaissement_caisse 
                   SET Motif = :Motif, 
                       Montant = :Montant, 
                       Date_Oper = :Date_Op, Deposant = :depos 
                   WHERE Numero_pce = :Num_pce AND Id = :Id" ;
    
        $stmtUpdate = $con->prepare($update);
        $stmtUpdate->bindParam(':Motif', $Motif);
        $stmtUpdate->bindParam(':Montant', $Montant);
        $stmtUpdate->bindParam(':Date_Op', $Date_Encaiss);
        $stmtUpdate->bindParam(':depos', $deposant);
        $stmtUpdate->bindParam(':Num_pce', $numero_piece);
        $stmtUpdate->bindParam(':Id', $id);
    
        if ($stmtUpdate->execute()) {
            echo json_encode(["success" => true, "message" => "Modification effectuée avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
        }
    }
    elseif ($typeEncaissement === "statut") {
        
       
        $statut_op = "Annulée";
        $numero_piece = $_GET["Num_Pce"]; // Assure-toi qu'il est bien passé dans l'URL
    
        $update = "UPDATE encaissement_caisse 
                   SET Statut = :statut 
                   WHERE Numero_pce = :Num_pce";
    
        $stmtUpdate = $con->prepare($update);
        $stmtUpdate->bindParam(':statut', $statut_op);
        $stmtUpdate->bindParam(':Num_pce', $numero_piece);
    
        if ($stmtUpdate->execute()) {
            echo json_encode(["success" => true, "message" => "Encaissement mis à jour avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
        }
    }


     else {
        echo json_encode(["error" => "Type d'encaissement non géré."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>
