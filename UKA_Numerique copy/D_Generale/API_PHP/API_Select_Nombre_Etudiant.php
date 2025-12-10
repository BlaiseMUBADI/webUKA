<?php
// appel de la page de connexion
//include("Connexion.php");

include("../../../Connexion_BDD/Connexion_1.php");


$sql_select_acces="SELECT COUNT(*) AS Nombre FROM etudiant";
      $stmt=$con1->prepare($sql_select_acces);
      
      $stmt->execute();
    
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //Renvoyer les resultats sous forme de json
    echo json_encode($result);
    //echo $etudiant;
        

   /* $sql_select_acces="SELECT COUNT(*) AS Nombre 

      FROM etudiant,passer_par,promotion,mentions,filiere,annee_academique
      WHERE 
      etudiant.Matricule=passer_par.Etudiant_Matricule 
      AND passer_par.Code_Promotion=promotion.Code_Promotion 
      AND promotion.idMentions=mentions.idMentions 
      AND mentions.IdFiliere=filiere.IdFiliere 
      AND passer_par.idAnnee_academique=annee_academique.idAnnee_Acad
      AND annee_academique.idAnnee_Acad=:idannee 
      AND promotion.Code_Promotion=:code_prom";
      $stmt=$con->prepare($sql_select_acces);
      $stmt->bindParam(':idannee',$id_annee_acad);
      $stmt->bindParam(':code_prom',$code_promo);
      $stmt->execute();
    
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //Renvoyer les resultats sous forme de json
    echo json_encode($result);
    //echo $etudiant;*/
    ?>