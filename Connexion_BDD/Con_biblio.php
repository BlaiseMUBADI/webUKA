<?php
$Ip_Serveur="localhost";
$Nom_bdd="bdduka";
$Nom_user="uka";
$Mot_passe="Admin@12345";
try
{
	//$bdd = new PDO('mysql:host=localhost;dbname=bdduka;charset=utf8', 'root', '');
	$con = new PDO('mysql:host='.$Ip_Serveur.';dbname='.$Nom_bdd.';charset=utf8',$Nom_user, $Mot_passe);
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}


 ?>
 


