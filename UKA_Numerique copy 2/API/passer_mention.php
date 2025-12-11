<?php 
//echo "bonjour";

//include('../connexion.php');
include("../../../Connexion_BDD/Connexion_1.php");

//recuperation pour changement des option
$Matricule = $_POST['Matricule'];
$annee_ac1 = $_POST['id_annee_acad'];
$code_promo1=$_POST['code_promo'];
$abreviation=$_POST['abreviation'];
$mention=$_POST['mention'];

echo $Matricule;

$data2 = $mention;


// Send JSON response
echo json_encode($data2);



 ?>