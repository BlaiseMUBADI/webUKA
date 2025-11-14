<?php
    session_start();
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $mat_agent=$_POST['mat_agent'];
    $login=$_POST['login'];
    $pawwsord=$_POST['password'];
    $password_hacher=password_hash($pawwsord,PASSWORD_BCRYPT);

    $etat=$_POST['etat_compte'];
    $fonction_compte=$_POST['fonction_membre'];
    $date_creation=$_POST['date_creation'];
    $code_prom=$_POST['code_prom'];
    $id_annee_acad=$_POST['id_annee_acad'];
    
    $con->beginTransaction();
    //$con->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    

    
    try
    {
        echo " filiere est ".$code_prom;
        if($code_prom==="null")
        {
            echo "je suis dans if";

            $sql_insert = "INSERT INTO compte_agent
                (Mat_agent
                ,Login
                ,Mot_passe
                ,Etat
                ,Categorie
                ,Date_creation,code_prom) 
                VALUES (:mat,:logi,:pswd,:etat,:fonction,:date_creation,:code_prom)";
        
            $stmt = $con->prepare($sql_insert);
            
            $stmt->bindParam(':mat', $mat_agent);
            $stmt->bindParam(':logi', $login);
            $stmt->bindParam(':pswd', $password_hacher);
            $stmt->bindParam(':etat', $etat);
            $stmt->bindParam(':fonction', $fonction_compte);
            $stmt->bindParam(':date_creation', $date_creation);
            $stmt->bindValue(':code_prom', null,PDO::PARAM_STR);

        }
        else
        {
        
            echo "je suis dans elese";
        $sql_insert = "INSERT INTO compte_agent
                (Mat_agent
                ,Login
                ,Mot_passe
                ,Etat
                ,Categorie
                ,Date_creation,code_prom) 
                VALUES (:mat,:logi,:pswd,:etat,:fonction,:date_creation,:code_prom)";
            
            $stmt = $con->prepare($sql_insert);
            
            $stmt->bindParam(':mat', $mat_agent);
            $stmt->bindParam(':logi', $login);
            $stmt->bindParam(':pswd', $password_hacher);
            $stmt->bindParam(':etat', $etat);
            $stmt->bindParam(':fonction', $fonction_compte);
            $stmt->bindParam(':date_creation', $date_creation);
            $stmt->bindParam(':code_prom', $code_prom);
        }
    

        
        if($stmt->execute()) echo "\n\nOk\n\n";
        else echo "\n\nimpossible de faire cet enregistrment \n\n";
        $con->commit();
        //$con->exec("SET FOREIGN_KEY_CHECKS = 1");
    } 
    catch(PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $con->rollback();
        echo "Erreur lors de l'insertion: " . $e->getMessage();
        ///$con->exec("SET FOREIGN_KEY_CHECKS = 1");
    }




?>


