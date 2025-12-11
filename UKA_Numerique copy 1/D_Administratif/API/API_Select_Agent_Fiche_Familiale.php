<?php
include("../../../Connexion_BDD/Connexion_1.php");

$matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';

if (empty($matricule)) {
    echo json_encode(['error' => 'Le matricule est requis.']);
    exit;
}

try {
    $sql = "SELECT  *
            FROM agent
            WHERE agent.Grade IS NOT NULL
              AND agent.Mat_agent = :matricule";

    $tos = $con->prepare($sql);
    $tos->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $tos->execute();

    if ($tos->rowCount() > 0) {
        $donnees = array();
        while ($row = $tos->fetch(PDO::FETCH_ASSOC)) {
            $donnees[] = $row;
        }
        echo json_encode($donnees);
    } else {
        echo json_encode(['error' => 'Aucun agent trouvé pour ce matricule.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur SQL: ' . $e->getMessage()]);
}
?>
