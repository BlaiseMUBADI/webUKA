<?php
    session_start();
    include("../../../Connexion_BDD/Connexion_1.php");

    
   
    
    $id_filiere=$_SESSION['id_fac'];
    $categorie_enseignant="Scientifique";

    $mat_enseignant=$_POST['mat_enseignant'];
    $nom_enseignant=$_POST['nom_enseignant'];
    $post_enseignant=$_POST['post_enseignant'];
    $prenom_enseignant=$_POST['prenom_enseignant'];
    $sexe_enseignant=$_POST['sexe_enseignant'];

    $telephone_enseignant=$_POST['telephone_enseignant'];
    $email_enseignant=$_POST['email_enseignant'];


    $niveau_etude_enseignant=$_POST['niveau_etude_enseignant'];
    $domaine_enseignant=$_POST['domaine_enseignant'];
    $titre_academique_enseignant=$_POST['titre_academique_enseignant'];
    $institution_attache_enseignant=$_POST['institution_enseignant'];


    //$photo_enseignant=$_FILES['photo_enseignant'];

    /*$nom_du_fichier = $_FILES['photo_enseignant']['name'];
    $type_du_Mimetype_fichier = $_FILES['photo_enseignant']['type'];*/

    //$photo_enseignant = file_get_contents($_FILES['photo_enseignant']['tmp_name']);
    $photo_enseignant =null;
    
    //$con->beginTransaction();
    
    /*
    INSERT INTO `agent`(`Mat_agent`, `Nom_agent`, `Post_agent`, `Prenom`, `Sexe`, `Niveau_Etude`, `Numero_Tel`, `Adresse_Mail`, `Photo_profil`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]')
    */

    
try
{
   $sql_insert_agent = "INSERT INTO agent(
    Mat_agent, 
    Nom_agent, 
    Post_agent, 
    Prenom, 
    Sexe, 
    Niveau_Etude, 
    Categorie,
    Numero_Tel, 
    Adresse_Mail,
    Photo_profil) 

    VALUES (
    :mat,
    :nom,
    :postnom,
    :prenom,
    :sexe,
    :etude,
    :cat,
    :phone,
    :adresse,
    :photo)";

    $stmt = $con->prepare($sql_insert_agent);
    
    $stmt->bindParam(':mat', $mat_enseignant);
    $stmt->bindParam(':nom', $nom_enseignant);
    $stmt->bindParam(':postnom', $post_enseignant);
    $stmt->bindParam(':prenom', $prenom_enseignant);
    $stmt->bindParam(':sexe', $sexe_enseignant);
    $stmt->bindParam(':etude', $niveau_etude_enseignant);
    $stmt->bindParam(':cat', $categorie_enseignant);
    $stmt->bindParam(':phone', $telephone_enseignant);
    $stmt->bindParam(':adresse', $email_enseignant);
    $stmt->bindParam(':photo', $photo_enseignant);
    
    
    if($stmt->execute())
    {
        $sql_insert_infos="INSERT INTO infos_enseignants
        (Mat_agent,
        Titre_academique,
        Domaine,
        Institution_attache,
        IdFiliere_Attache) 
        VALUES (
        :mat_1,
        :titre,
        :domaine,
        :institut,
        :filiere)";

        $stmt = $con->prepare($sql_insert_infos);
            
        $stmt->bindParam(':mat_1', $mat_enseignant);
        $stmt->bindParam(':titre', $titre_academique_enseignant);
        $stmt->bindParam(':domaine', $domaine_enseignant);
        $stmt->bindParam(':institut', $institution_attache_enseignant);
        $stmt->bindParam(':filiere', $id_filiere);

        if($stmt->execute()) echo "\n\nOk\n\n";
        else echo "\n\nImpossible d'ajouter les infos de cet enseignant\n\n";
        
    }
    
    else echo "\n\nimpossible de faire cet enregistrment \n\n";
    //$con->commit();
    
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    //$con->rollback();
    echo "Erreur lors de l'insertion: " . $e->getMessage();
    
}




?>


