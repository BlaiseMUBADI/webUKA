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

$typeDecaissement = $_GET['type'];

if (($typeDecaissement === "USD" || $typeDecaissement === "CDF") && 
    (!isset($_GET['date1'], $_GET['date2']))) {
    echo json_encode(["error" => "Les paramètres date1 et date2 sont requis pour les types USD et CDF"]);
    exit;
}

$typeDecaissement = $_GET['type'];

try {
    
    if ($typeDecaissement === "modifier") {
        if ($devise = $_GET['dev']==="USD")
        {
             $prefix = "Dec_usd_";
        }
        else {
             $prefix = "Dec_cdf_";
        } 

        $id = $_GET["id_op"];
        $Motif = $_GET["Motif"];
        $Montant = $_GET["Montant"];
        $Date_Decaiss = $_GET["Date_op"];
        $Ben = $_GET["Ben"];
        $numero_pce = $_GET["Num_Pce"]; 
        $numero_piece = $prefix . $numero_pce;
        $update = "UPDATE decaissement_caisse 
                   SET 
                        Beneficiaire = :ben, 
                        Motif = :Motif, 
                        Montant = :Montant, 
                        Date_Oper = :Date_Op 
                       
                   WHERE Num_piece = :Num_pce AND Id = :id" ;
    
        $stmtUpdate = $con->prepare($update);
        $stmtUpdate->bindParam(':Motif', $Motif);
        $stmtUpdate->bindParam(':Montant', $Montant);
        $stmtUpdate->bindParam(':Date_Op', $Date_Decaiss);
        $stmtUpdate->bindParam(':ben', $Ben);
        $stmtUpdate->bindParam(':Num_pce', $numero_piece);
        $stmtUpdate->bindParam(':id', $id);
    
        if ($stmtUpdate->execute()) {
            echo json_encode(["success" => true, "message" => "Modification effectuée avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
        }
    }
    elseif ($typeDecaissement === "statut") {
        
       
        $statut_op = "Annulée";
        $numero_piece = $_GET["Num_Pce"]; // Assure-toi qu'il est bien passé dans l'URL
    
        $update = "UPDATE encaissement_caisse 
                   SET Statut = :statut 
                   WHERE Numero_pce = :Num_pce";
    
        $stmtUpdate = $con->prepare($update);
        $stmtUpdate->bindParam(':statut', $statut_op);
        $stmtUpdate->bindParam(':Num_pce', $numero_piece);
    
        if ($stmtUpdate->execute()) {
            echo json_encode(["success" => true, "message" => "Decaissement mis à jour avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
        }
    }


     else {
        echo json_encode(["error" => "Type de decaissement non géré."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>
