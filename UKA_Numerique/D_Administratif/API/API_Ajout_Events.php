<?php

include("../../../Connexion_BDD/Connexion_1.php");



$Titre = $_GET['titre'];
$Date_debut = $_GET['datedebut'];
$Date_Fin = $_GET['datefin'];
$Details = $_GET['details'];



try {
    ob_start(); 
    $con->beginTransaction();

    $stmt = $con->prepare("INSERT INTO events (title, start,end,details)values(:titre, :debut,:fin,:detail)");
    

    $stmt->bindParam(':titre', $Titre);
    $stmt->bindParam(':debut', $Date_debut);
    $stmt->bindParam(':fin', $Date_Fin);
    $stmt->bindParam(':detail', $Details);


    $stmt->execute();

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
