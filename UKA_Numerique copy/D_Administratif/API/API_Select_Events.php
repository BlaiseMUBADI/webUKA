<?php

include("../../../Connexion_BDD/Connexion_1.php");

$sql = "SELECT id, title, start, end,details FROM events WHERE start IS NOT NULL ORDER BY start";

$tos = $con->prepare($sql);
$tos->execute();

$events = array();
while ($row = $tos->fetch(PDO::FETCH_ASSOC)) {
    $events[] = $row;
}

// Renvoie de la réponse JSON
echo json_encode($events);

?>
