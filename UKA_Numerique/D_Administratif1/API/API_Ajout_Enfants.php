<?php

include("../../../Connexion_BDD/Connexion_1.php");


// Récupérer les données via POST et vérifier si elles existent
$matricule = $_GET['mat'];
$nomenfant = $_GET['nom'];
$lieunais = $_GET['lieu'];
$datenais = $_GET['daten'];


if (empty($matricule)) 
    { 
        echo json_encode(['success' => false, 'message' => 'La champ Matricule agent ne peut pas être vide.']);
        exit;
    }

if (empty($nomenfant)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ Nom ne peut pas être vide.']);
        exit;
    }
if (empty($lieunais)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ lieu de naissance ne peut pas être vide.']);
        exit;
    }
    if (empty($datenais)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Précisez la date de naissance.']);
        exit;
    }

try {
    ob_start(); // Démarrer la capture du buffer de sortie
    $con->beginTransaction();


    // Insertion dans la table parents

// Appeler la procédure stockée
$stmt = $con->prepare("CALL inserer_enfant(:nom_enfant, :lieu,:datenais,:matricule)");
    
// Lier les paramètres de la procédure aux variables PHP
$stmt->bindParam(':matricule', $matricule);
$stmt->bindParam(':nom_enfant', $nomenfant);
$stmt->bindParam(':lieu', $lieunais);
$stmt->bindParam(':datenais', $datenais);

// Exécuter la procédure stockée
$stmt->execute();





    // Si toutes les insertions ont réussi
    $con->commit();
    ob_clean(); // Vider les buffers de sortie
    echo json_encode(['success' => true, 'message' => 'Enregistrement réussi.']);
} catch (PDOException $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    $con->rollBack();
    ob_clean(); // Vider les buffers de sortie
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
} catch (Exception $e) {
    // Capturer toute autre erreur
    $con->rollBack();
    ob_clean(); // Vider les buffers de sortie
    echo json_encode(['success' => false, 'message' => 'Erreur inattendue : ' . $e->getMessage()]);
}
?>
