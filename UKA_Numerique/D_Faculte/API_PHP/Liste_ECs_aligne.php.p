<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");

$mat_agent=$_GET['mat_agent'];
$code_prom=$_GET['code_prom'];
$id_annee_acad=$_GET['annee_acad'];
//echo " id_fili est ".$id_filiere;

$sql_select="SELECT 
    element_constutifs.id_ec AS id_ec,
    element_constutifs.Intutile_ec AS nom_ec,
    CONCAT(element_constutifs.CMI, ' H') AS cmi,
    CONCAT(element_constutifs.Hr_TD, ' H') AS hr_td,
    CONCAT(element_constutifs.Hr_TP, ' H') AS hr_tp,
    CONCAT(element_constutifs.TPE, ' H') AS tpe,
    CONCAT(element_constutifs.VHT, ' H') AS vht,
    element_constutifs.Credit AS credit,
    CASE
        WHEN element_constitufs_aligne.id_ec_aligne != '' 
            AND element_constitufs_aligne.idAnnee_Acad=$id_annee_acad THEN 'true'
        ELSE 'false'
    END AS etat_ec,
    CASE
        WHEN element_constitufs_aligne.Mat_agent = '$mat_agent' THEN 'true'
        ELSE 'false'
    END AS appartenance_enseignant
FROM 
(element_constutifs
LEFT JOIN element_constitufs_aligne ON element_constutifs.id_ec = element_constitufs_aligne.id_ec
AND element_constitufs_aligne.idAnnee_Acad=$id_annee_acad)
INNER JOIN promotion ON element_constutifs.Code_Promotion = promotion.Code_Promotion

WHERE 
    promotion.Code_Promotion=:code_prom
ORDER BY 

	CASE 
    	WHEN element_constitufs_aligne.Mat_agent = '$mat_agent' THEN 1 
    	WHEN element_constitufs_aligne.id_ec_aligne != '' THEN 2        
    ELSE 3 END,
    element_constutifs.id_ec DESC";
//echo " Voici la requette ".$sql_select."\n" ;
$stmt=$con->prepare($sql_select);
$stmt->bindParam(':code_prom',$code_prom);
$stmt->execute();
#and element_constutifs.Code_ue=:code_ue
#$stmt->bindParam(':code_ue',$code_ue);

$etud=array();
while($ligne = $stmt->fetch()) $etud[]=$ligne;
echo json_encode($etud);

?>

