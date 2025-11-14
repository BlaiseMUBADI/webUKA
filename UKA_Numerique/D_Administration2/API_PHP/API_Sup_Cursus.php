<?php
include("../../../Connexion_BDD/Connexion_1.php");

try
{
//recupération des variables qui viennent avec les données
$Mat = $_GET['matEtud'];
$CodePromo = $_GET['promo'];
$idAnne = $_GET['Anne'];
//$CodePromo = $_GET['code_pro'];
//echo"le matricule est".$Mat;
//echo"le matricule est".$CodePromo;
//echo"le matricule est".$idAnne;

$sql = "DELETE FROM passer_par WHERE 
        passer_par.Etudiant_Matricule =:matricule 
        AND passer_par.Code_Promotion =:Code
        AND passer_par.idAnnee_academique =:idanne";

$tos=$con->prepare($sql);
$tos->bindParam(":matricule",$Mat);
$tos->bindParam(":Code",$CodePromo);
$tos->bindParam(":idanne",$idAnne);
$tos->execute();

if($stmt->execute()) echo "Ok";
else echo "faux";
$con->commit();
} 
catch(PDOException $e) {
// Annuler la transaction en cas d'erreur
$con->rollback();
echo "Erreur lors de la suppresion de la transaction: " . $e->getMessage();
}

?>