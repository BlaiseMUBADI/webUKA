<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");

$id_semestre=$_GET['id_semestre'];
$id_filiere=$_SESSION['id_fac'];
//echo " id_fili est ".$id_filiere;

$sql_select="SELECT unite_enseignement.Code_ue as Code_ue
,unite_enseignement.Intitule_ue as nom_ue
,unite_enseignement.Catégorie as categorie_ue
FROM semestre,unite_enseignement,filiere 
WHERE filiere.IdFiliere=unite_enseignement.IdFiliere
and unite_enseignement.Id_Semestre=semestre.Id_Semestre
and semestre.Id_Semestre=:id_semestre
and filiere.IdFiliere=:id_filiere";

$stmt=$con->prepare($sql_select);
$stmt->bindParam(':id_semestre',$id_semestre);
$stmt->bindParam(':id_filiere',$id_filiere);
$stmt->execute();

$etud=array();
while($ligne = $stmt->fetch()) $etud[]=$ligne;
echo json_encode($etud);

?>

