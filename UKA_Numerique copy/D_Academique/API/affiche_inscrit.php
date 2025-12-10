<?php  
 //include('connexion.php') ;
include("../../../Connexion_BDD/Connexion_1.php");

        
// Récupération de la date sélectionnée
$code_promo = $_GET['code_promo'];
$id_annee_acad = $_GET['Id_annee_acad'];




$sql_select_acces="SELECT etudiant.Matricule,
      etudiant.Nom,
      etudiant.Postnom,
      etudiant.Prenom,
      etudiant.Sexe,
      etudiant.DateNaissance,
      etudiant.LieuNaissance
      

      FROM etudiant,passer_par,promotion,mentions,filiere,annee_academique
      WHERE 
      etudiant.Matricule=passer_par.Etudiant_Matricule 
      AND passer_par.Code_Promotion=promotion.Code_Promotion 
      AND promotion.idMentions=mentions.idMentions 
      AND mentions.IdFiliere=filiere.IdFiliere 
      AND passer_par.idAnnee_academique=annee_academique.idAnnee_Acad
      AND annee_academique.idAnnee_Acad=:idannee 
      AND promotion.Code_Promotion=:code_prom order by etudiant.Nom, etudiant.Postnom  ASC";
      $stmt=$con1->prepare($sql_select_acces);

      $stmt->bindParam(':idannee',$id_annee_acad);
      $stmt->bindParam(':code_prom',$code_promo);

      $stmt->execute();
    
    
    $etud=array();
    while($ligne = $stmt->fetch())
    {
        $etud[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
    echo json_encode($etud);
//$bdd->close();
?>