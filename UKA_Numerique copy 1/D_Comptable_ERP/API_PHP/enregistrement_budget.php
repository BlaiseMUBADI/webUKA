<?php  
 //include('connexion.php') ;
include("../../../Connexion_BDD/Connexion_1.php");

if ($_GET['input']=="") {
        # code...


$libelle = addslashes($_GET['libelle']);
$description = addslashes($_GET['description']);       
$Periodicite = $_GET['Periodicite'];       
$Annee_debut = $_GET['Annee_debut'];       
$Annee_fin = $_GET['Annee_fin'];       
$service = $_GET['service'];
$extrait = $_GET['Extrait'];
//echo $action." ".$id_annee_acad." ".$code_promo;

//echo $extrait;

if ($extrait=='Fac.') {
        

$sql_insert_t_budget= 
  "INSERT INTO 
  t_budget(
    Libelle, 
    Description, 
    Periodicite, 
    Annee_debut, 
    Annee_fin, 
    id_filiere)
    VALUES ('$libelle','$description','$Periodicite','$Annee_debut','$Annee_fin','$service')"; 
}else if ($extrait=='Ser.'){

    echo $extrait; 
    $sql_insert_t_budget= 
  "INSERT INTO 
  t_budget(
    Libelle, 
    Description, 
    Periodicite, 
    Annee_debut, 
    Annee_fin, 
    Idservice)
    VALUES ('$libelle','$description','$Periodicite','$Annee_debut','$Annee_fin','$service')"; 
}

 $stmt1=$con->prepare($sql_insert_t_budget);
 
 $stmt1->execute();
}else {



$Ref_budget = (int)$_GET['input'];
$libelle = addslashes($_GET['libelle']);
$description = addslashes($_GET['description']);       
$Periodicite = $_GET['Periodicite'];       
$Annee_debut = $_GET['Annee_debut'];       
$Annee_fin = $_GET['Annee_fin'];       
$service = $_GET['service'];       
//echo $action." ".$id_annee_acad." ".$code_promo;




$sql_update="UPDATE `t_budget` SET `Libelle`=:libelle,`Description`=:description,`Periodicite`=:Periodicite,`Annee_debut`=:Annee_debut,`Annee_fin`=:Annee_fin, `Idservice`=:service WHERE `Ref_budget`=:Ref_budget";



$stmt=$con->prepare($sql_update);


    $stmt->bindParam(':libelle', $libelle);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':Periodicite', $Periodicite);
    $stmt->bindParam(':Annee_debut', $Annee_debut);
    $stmt->bindParam(':Annee_fin', $Annee_fin);
    $stmt->bindParam(':service', $service);
    $stmt->bindParam(':Ref_budget', $Ref_budget);
    
   // echo "la veulleur de sql :".$sql_update;


    $stmt->execute();


     $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
}

?>