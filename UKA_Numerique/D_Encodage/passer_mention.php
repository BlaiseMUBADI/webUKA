<?php 
//echo "bonjour";

//include('connexion.php');
include("../../Connexion_BDD/Connexion_1.php");



//recuperation pour changement des option
$Matricule = $_GET['Matricule1'];
$annee_ac1 = $_GET['id_annee_acad'];
$code_promo1=$_GET['code_promo'];
$abreviation=$_GET['abreviation'];
$mention=$_GET['mention'];
$nouveau_code="";


$reponse = $con->query ("SELECT `idMentions`, `Code_Promotion` FROM `promotion` WHERE `Abréviation`='$abreviation' AND `idMentions` = '$mention'" );

while ($ligne1 = $reponse->fetch()) {
    $nouveau_code=$ligne1['Code_Promotion'];
    //echo "la valeur nouvelle est ".$nouveau_code;
}




$sql_suppr_etudiant="DELETE FROM `passer_par` WHERE `Etudiant_Matricule`=:matr AND `Code_Promotion`=:code_prom AND `idAnnee_academique` =:annee";

// $data2 = array();

$stmt=$con->prepare($sql_suppr_etudiant);
$stmt->bindParam(':matr',$Matricule);
$stmt->bindParam(':code_prom',$code_promo1);
$stmt->bindParam(':annee',$annee_ac1);

$stmt->execute();

/*$sql_suppr_etudiant="DELETE FROM `passer_par` WHERE `Etudiant_Matricule`=:matr3 AND `Code_Promotion`=:code_prom3 AND `idAnnee_academique` =:annee3";


$stmt2=$con->prepare($sql_suppr_etudiant);
$stmt2->bindParam(':matr3',$Matricule);
$stmt2->bindParam(':code_prom3',$nouveau_code);
$stmt2->bindParam(':annee3',$annee_ac1);
$stmt2->execute();*/

$sql_insert_etudiant="INSERT INTO `passer_par`(`Etudiant_Matricule`, `Code_Promotion`, `idAnnee_academique`, `Decision_jury`, `Session1`, `Mention1`, `Session2`, `Mention2`,`Active`) VALUES (:matr2,:code_prom2,:annee2,NULL,'0','-','0','-','')";


$stmt1=$con->prepare($sql_insert_etudiant);
$stmt1->bindParam(':matr2',$Matricule);
$stmt1->bindParam(':code_prom2',$nouveau_code);
$stmt1->bindParam(':annee2',$annee_ac1);

$stmt1->execute();

if ($stmt1){
    echo "tout s est bien passé";
}


 /*$data2=array();
    while($ligne = $stmt1->fetch())
    {
        $data2[]=$ligne;

    }*/

// Send JSON response
//echo json_encode($data2);


 ?>