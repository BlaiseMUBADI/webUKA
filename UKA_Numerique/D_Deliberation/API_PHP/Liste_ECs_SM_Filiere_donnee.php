<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");

$code_ue=$_GET['code_ue'];
$code_prom=$_GET['code_prom'];
//echo " id_fili est ".$id_filiere;

$sql_select="SELECT 
element_constutifs.id_ec as id_ec
,element_constutifs.Intutile_ec as nom_ec
,CONCAT(element_constutifs.CMI,' H') as cmi
,CONCAT(element_constutifs.Hr_TD,' H') as hr_td
,CONCAT(element_constutifs.Hr_TP,' H') as hr_tp
,CONCAT(element_constutifs.TPE,' H') as tpe
,CONCAT(element_constutifs.VHT,' H') as vht
,element_constutifs.Credit as credit
FROM element_constutifs,promotion,unite_enseignement WHERE 
promotion.Code_Promotion=element_constutifs.Code_Promotion
and element_constutifs.Code_ue=unite_enseignement.Code_ue
and element_constutifs.Code_ue=:code_ue
and promotion.Code_Promotion=:code_prom
ORDER BY element_constutifs.id_ec DESC"
;

$stmt=$con->prepare($sql_select);
$stmt->bindParam(':code_ue',$code_ue);
$stmt->bindParam(':code_prom',$code_prom);
$stmt->execute();

$etud=array();
while($ligne = $stmt->fetch()) $etud[]=$ligne;
echo json_encode($etud);

?>

