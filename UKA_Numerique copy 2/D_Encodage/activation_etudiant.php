<?php 
	
	 //include("Connexion.php");
include("../../Connexion_BDD/Connexion_1.php");



$Matricule = $_GET['Matricule1'];
$annee_academique = $_GET['id_annee_acad'];
$code_promotion_active=$_GET['code_promo'];
$action=$_GET['action'];

echo "la valeur de action est :::".$action."  :: ".$Matricule."  :: ".$annee_academique."  :: ".$code_promotion_active;
$sql_active_etudiant_promotion="UPDATE `passer_par` SET `Active`=:action WHERE `Etudiant_Matricule`=:matr AND `Code_Promotion`=:code_prom AND `idAnnee_academique` =:annee";

$stmt=$con->prepare($sql_active_etudiant_promotion);
$stmt->bindParam(':matr',$Matricule);
$stmt->bindParam(':code_prom',$code_promotion_active);
$stmt->bindParam(':annee',$annee_academique);
$stmt->bindParam(':action',$action);

$stmt->execute();
 ?>