<?php
$nom_serveur = "localhost";
$nom_base_données = "bdd_uka";
$utiilisateur = "root";
$mot_de_passe = "";


try 
	{
	$con = new PDO("mysql:host=$nom_serveur;dbname=$nom_base_données", $utiilisateur, $mot_de_passe);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	} 
	catch (PDOException $e) {
		echo 'Erreur: ' .$e->getMessage();
	}	
 ?>
 
 <?php 


