<?php
    include("../../../Connexion_BDD/Connexion_1.php");

    session_start();
    if (!isset($_SESSION['MatriculeAgent'])) {
        die("Session non valide");
    }

    $matricule = $_SESSION['MatriculeAgent'];
    $sql = "SELECT Photo_profil, Type_image FROM compte_agent WHERE Mat_agent=:matricule";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':matricule', $matricule);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        header("Content-type: " . $result['Type_image']);
        echo $result['Photo_profil'];
    } else {
        echo "Image non trouvée";
    }
?>
