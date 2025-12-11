<?php
include("../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$IdAnne = $_GET['Id_annee_acad'];
$CodePromo = $_GET['code_promo'];
$lib="Frais Académiques";
       //echo"le code promo est :::::".$CodePromo;            

        $stmt = $con->prepare("SELECT frais.Montant, frais.Tranche, frais.idFrais, frais.Libelle_Frais FROM 
                frais 
                WHERE 
                    frais.idAnnee_Acad=:annee 
                AND frais.Code_Promotion=:codepromo"); 

 
        $stmt->bindParam(":annee",$IdAnne);
        $stmt->bindParam(":codepromo",$CodePromo);
        $stmt->execute();
        

$data = array();
while ($row = $stmt->fetch()) {
    $data[] = $row;

}

// renvoie de la reponse JSON
echo json_encode($data);


?>