<?php
try
{
	//$bdd = new PDO('mysql:host=localhost;dbname=bdduka;charset=utf8', 'root', '');
	//$bdd = new PDO('mysql:host=localhost;dbname=bdduka;charset=utf8', 'uka', 'Admin12345');
	$bdd = new PDO('mysql:host=localhost;dbname=bdd_uka;charset=utf8', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

?>
