<?php
// appel de la page de connexion
//include("Connexion.php");

include("../../../Connexion_BDD/Connexion_1.php");


$critere=$_GET['Choix'];
$design=" ";
//echo "le critere est : ".$critere;
if ($critere == "Pourcentage plus ou égal à 60 %") {
  $design = "Frais d'inscription.";
} elseif ($critere == "Pourcentage moins de 60 %") {
  $design = "Frais d'inscription";
} else {
  $design = "Frais d'inscription spéciale";
}

// Préparer la requête SQL
$sql_select_acces = "SELECT Montant, idFrais FROM frais WHERE Libelle_Frais = :libelle";
$stmt = $con1->prepare($sql_select_acces);
$stmt->bindParam(':libelle', $design);

// Exécuter la requête et récupérer les résultats
$stmt->execute();

// Renvoyer les résultats sous forme de JSON
$etud=array();
while($ligne = $stmt->fetch())
{
    $etud[]=$ligne;

}

//Renvoyer les resultats sous forme de json



echo json_encode($etud);

   
    ?>