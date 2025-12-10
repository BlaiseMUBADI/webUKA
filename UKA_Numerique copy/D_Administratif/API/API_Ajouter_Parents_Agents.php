<?php

include("../../../Connexion_BDD/Connexion_1.php");


// Récupérer les données via POST et vérifier si elles existent
$matricule = $_GET['mat'];
$noms = $_GET['noms'];
$statut = $_GET['statut'];
$annedec = $_GET['anneedeces'];
$NbreParent = $_GET['NbrParent'];


if ($NbreParent>=2) 
    { 
        echo json_encode(['success' => false, 'message' => 'Une personne ne peut avoir plus de 2 parents.']);
        exit;
    }
if (empty($matricule)) 
    { 
        echo json_encode(['success' => false, 'message' => 'La champ Matricule ne peut pas être vide.']);
        exit;
    }

if (empty($noms)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ Nom ne peut pas être vide.']);
        exit;
    }


try {
    ob_start(); // Démarrer la capture du buffer de sortie
    $con->beginTransaction();


    // Insertion dans la table parents

// Appeler la procédure stockée
$stmt = $con->prepare("INSERT INTO parent (MatriculeAgent, Noms,Statut,annee_dec)
    VALUES (:matricule,:nom, :statut,:Anneedec)");
    
// Lier les paramètres de la procédure aux variables PHP
$stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
$stmt->bindParam(':nom', $noms, PDO::PARAM_STR);
$stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
$stmt->bindParam(':Anneedec', $annedec, PDO::PARAM_INT);


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
