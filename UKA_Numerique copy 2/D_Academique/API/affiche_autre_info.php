<?php 
include("../../../Connexion_BDD/Connexion_1.php");

$matricule = $_GET['matricule'];

//echo "nous la";


 $rqt_sql = $con1->prepare("SELECT * FROM etudiant, autreinfo_etudiant 
    WHERE etudiant.Matricule=autreinfo_etudiant.Matricule 
    and autreinfo_etudiant.Matricule=:matricule ");


  
    $rqt_sql->bindParam(':matricule',$matricule);
    $rqt_sql->execute();
   

$data = array();
while ($row = $rqt_sql->fetch()) {
    $data[] = $row;
}

// renvoie de la reponse JSON
echo json_encode($data);
    
?>