<?php
include("../../../Connexion_BDD/Connexion_1.php");

//$mat_etudaint=$_GET['idFiliere'];
$id_annee_acad=$_GET['Id_annee_acad'];
$code_promo=$_GET['code_promo'];

$sql_select_acces= "SELECT idFrais as id_frais,
    Libelle_Frais as Lib_frais, 
    Montant as montant_fixe,
    Tranche as tranche_frais,
    Devise as devise

    FROM frais
    WHERE frais.idAnnee_Acad=:idannee
    and frais.Code_Promotion =:code_prom";

$stmt=$con->prepare($sql_select_acces);
$stmt->bindParam(':idannee',$id_annee_acad);
$stmt->bindParam(':code_prom',$code_promo);
     
$stmt->execute();
    
    
$etud=array();

while($ligne = $stmt->fetch()) $etud[]=$ligne;

echo json_encode($etud);
        

?>

