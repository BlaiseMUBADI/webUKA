<?php
include("../../../Connexion_BDD/Connexion_1.php");


$id = $_GET['id'];
$champ = $_GET['champ'];
$valeur = $_GET['valeur'];

$champsAutorises = ['Intitul_compte', 'Montant'];
if (!in_array($champ, $champsAutorises)) {
    exit("Champ non autorisé");
}

$req = $con->prepare("UPDATE t_depense_prevues SET $champ = ? WHERE Id_depense = ?");
$req->execute([$valeur, $id]);

echo "OK";
?>