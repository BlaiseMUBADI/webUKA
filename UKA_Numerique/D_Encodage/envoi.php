 <?php 

 include("../../Connexion_BDD/Connexion_1.php");

 

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->begintransaction();


    $Annee_academique=$_GET['annee_insert'];
    $Faculte=$_GET['faculte_insert'];
    $Promotion=$_GET['promotion_insert'];


    $Matricule=$_GET['matricule'];
    $Nom_etudiant=$_GET['nom_etudiant'];
    $Postnom_etudiant=$_GET['postnom_etudiant'];
    $Prenom_etudiant=$_GET['prenom_etudiant'];
    $Sexe_etudiant=$_GET['Sexe_etudiant'];

    echo "affichage de'enregistrement".'<br><br>';
    echo $Promotion.'<br><br>';
    echo $Matricule.'<br><br>';

   
    $sql_insert_etudiant="INSERT INTO `etudiant`(`Matricule`, `Nom`, `Postnom`, `Prenom`, `Sexe`) VALUES ('".$Matricule."','".$Nom_etudiant."','".$Postnom_etudiant."','".$Prenom_etudiant."','".$Sexe_etudiant."')";

    $sql_insert_etudiant_passer_par="INSERT INTO `passer_par`(`Etudiant_Matricule`, `Code_Promotion`, `idAnnee_academique`, `Decision_jury`, `Session1`, `Mention1`, `Session2`, `Mention2`, `Active`) VALUES ('".$Matricule."','". $Promotion."','".$Annee_academique."',Null,'0','-','0','-','Active')";


    $sql_insert_etudiant_AutreInfo_etudiant="INSERT INTO `autreinfo_etudiant`( `Matricule`) VALUES ('".$Matricule."')";

    
   $stmt=$con->prepare($sql_insert_etudiant);
    //$con-> query($sql_insert_etudiant);
   $stmt->execute();

   //$con-> query($sql_insert_etudiant_passer_par);
   $stmt=$con->prepare($sql_insert_etudiant_passer_par);
   $stmt->execute();

   //$con-> query($sql_insert_etudiant_AutreInfo_etudiant);
   $stmt=$con->prepare($sql_insert_etudiant_AutreInfo_etudiant);
   $stmt->execute();

    if ($con -> commit()) {
       echo "les données ont été bien envoyée dans la base";
    } else{
      echo "Erreur lors de l'insertion de données dans la base de données";
    }


/*$stmt=$con->prepare($sql_insert_etudiant);
    $stmt->bindParam(':matr',$Matricule);            
    $stmt->bindParam(':nom',$Nom_etudiant);
    $stmt->bindParam(':postnom',$Postnom_etudiant);
    $stmt->bindParam(':prenom',$Prenom_etudiant);
    $stmt->bindParam(':sexe',$Sexe_etudiant);*/
    //$stmt->execute();

    //$stmt1=$con->prepare($sql_insert_etudiant_passer_par);
    //$stmt1->execute();

    //$stmt2=$con->prepare($sql_insert_etudiant_AutreInfo_etudiant);
    //$stmt2->execute();
 ?>