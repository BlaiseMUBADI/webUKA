<?php

        include("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la méthode et la présence du fichier
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["Imageprofil"])) {
    try {

        // Vérifier si l'utilisateur est bien connecté
        session_start();
        if (!isset($_SESSION['MatriculeAgent'])) {
            echo json_encode(["success" => false, "error" => "Session non valide"]);
            exit;
        }

        // Vérifier l'existence du fichier temporaire
        if (!file_exists($_FILES["Imageprofil"]["tmp_name"])) {
            echo json_encode(["success" => false, "error" => "Fichier introuvable"]);
            exit;
        }

        // Vérifier le type et la taille du fichier
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES["Imageprofil"]["type"], $allowedTypes)) {
            echo json_encode(["success" => false, "error" => "Format non autorisé"]);
            exit;
        }
        
        if ($_FILES["Imageprofil"]["size"] > 5 * 1024 * 1024) { // 5 Mo max
            echo json_encode(["success" => false, "error" => "Fichier trop volumineux"]);
            exit;
        }

        // Lecture du fichier
        $photo = file_get_contents($_FILES["Imageprofil"]["tmp_name"]);
        $nomImage = $_FILES["Imageprofil"]["name"];
        $typeImage = $_FILES["Imageprofil"]["type"];
        $matricule = $_SESSION['MatriculeAgent'];  // Assurez-vous que la variable session 'MatriculeAgent' est définie

        // Préparation et exécution de la requête SQL
        $sql = "UPDATE compte_agent SET Photo_profil=:photo, Nom_image=:nom, Type_image=:typeimage WHERE Mat_agent=:matricule";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
        $stmt->bindParam(':nom', $nomImage);
        $stmt->bindParam(':typeimage', $typeImage);
        $stmt->bindParam(':matricule', $matricule);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Photo de profil mise à jour"]);
        } else {
            echo json_encode(["success" => false, "error" => "Erreur SQL"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Aucun fichier reçu"]);
}
?>
