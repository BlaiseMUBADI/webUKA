<?php
include("../../../Connexion_BDD/Connexion_1.php");

$valeur_envoyee = $_GET['text'];
$Matricule = $_GET['matricule'];
$zone = $_GET['zone'];



if($zone =="zone_nom"){
        $sql_update="UPDATE etudiant SET Nom=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_postnom") {
        $sql_update="UPDATE etudiant SET Postnom=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_prenom") {
        $sql_update="UPDATE etudiant SET Prenom=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_sexe") {
        $sql_update="UPDATE etudiant SET Sexe=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_lieu_naiss") {
        $sql_update="UPDATE etudiant SET LieuNaissance=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_date_naiss") {
        $sql_update="UPDATE etudiant SET DateNaissance=:val WHERE Matricule=:mat_etudiant";

}

$stmt = $con ->prepare($sql_update);
$stmt->bindParam(':val', $valeur_envoyee);
$stmt->bindParam(':mat_etudiant', $Matricule);
$stmt->execute();

?>