<?php
    //include("Connexion.php");
include("../../../Connexion_BDD/Connexion_1.php");

    //nclude("Fonctions.php");

    $valeur_envoyee=$_GET['valeur_zone'];
    $Matricule_etudiant=$_GET['Matricule'];
    $Zone=$_GET['Zone'];   
    $id_annee_acad=$_GET['id_annee_acad'];
    $Code_Promotion=$_GET['Code_Promotion'];

    //echo "id anne suivante ".$id_annee_acad;
    $sql_update="";
     
if($Zone =="zone_1"){
        $sql_update="UPDATE `passer_par` 
          SET `Session1`=:val 
        WHERE Etudiant_Matricule=:mat_etudiant 
          AND Code_Promotion=:code_promo 
          AND idAnnee_academique=:id_annee";
          $stmt = $con->prepare($sql_update);

    $stmt->bindParam(':val', $valeur_envoyee);
    $stmt->bindParam(':mat_etudiant', $Matricule_etudiant);
    $stmt->bindParam(':code_promo', $code_promo);
    $stmt->bindParam(':id_annee', $id_annee_acad);
    
    $stmt->execute();
    echo $sql_update;

}
/*elseif ($Zone =="zone_2"){
        $sql_update="UPDATE `passer_par` SET `Mention1`=:val WHERE Etudiant_Matricule=:mat_etudiant AND Code_Promotion=:code_promo AND idAnnee_academique=:id_annee";
}elseif ($Zone =="zone_3") {
        $sql_update="UPDATE `passer_par` SET `Session2`=:val WHERE Etudiant_Matricule=:mat_etudiant AND Code_Promotion=:code_promo AND idAnnee_academique=:id_annee";
}elseif ($Zone =="zone_4") {
        $sql_update="UPDATE `passer_par` SET `Mention2`=:val WHERE Etudiant_Matricule=:mat_etudiant AND Code_Promotion=:code_promo AND idAnnee_academique=:id_annee";
}elseif ($Zone =="zone_5") {
        $sql_update="UPDATE `passer_par` SET `Decision_jury`=:val WHERE Etudiant_Matricule=:mat_etudiant AND Code_Promotion=:code_promo AND idAnnee_academique=:id_annee";
}*/
    






     /* else if($Zone=="zone_2")
    {
         $sql_update=$con->prepare("UPDATE passer_par 
         SET Mention1=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee");

    }
    else if($Zone=="zone_3")
    {
         $sql_update=$con->prepare("UPDATE passer_par 
         SET Session2=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee");

    }else if($Zone=="zone_4")
    {

      $sql_update=$con->prepare("UPDATE passer_par 
         SET Mention2=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee");
    }
    else if($Zone=="zone_5")
    {
         $sql_update=$con->prepare("UPDATE passer_par 
         SET Decision_jury=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee");
         
    }*/



    //$stmt = $con->prepare($sql_update);

   // $stmt->bindParam(':val', $valeur_envoyee);
    //$stmt->bindParam(':mat_etudiant', $Matricule_etudiant);
    //$stmt->bindParam(':code_promo', $code_promo);
    //$stmt->bindParam(':id_annee', $id_annee_acad);

    /*$sql_update->bindParam(':val', $valeur_envoyee);
    $sql_update->bindParam(':mat_etudiant', $Matricule_etudiant);
    $sql_update->bindParam(':code_promo', $code_promo);
    $sql_update->bindParam(':id_annee', $id_annee_acad);*/
    
   // echo "la veulleur de sql :".$sql_update;

    //$stmt->execute();
    
   /* if ($sql_update->execute()) {
        echo "tout s'est bien passer";
    }else {
        echo "il y a erreur";
    }*/

?>