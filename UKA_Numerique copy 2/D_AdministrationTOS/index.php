<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Université Notre-Dame du Kasayi</title>
        <link rel="shortcut icon" href="logo.ico" type="image/x-icon">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Login Form Template" name="keywords">
        <meta content="Login Form Template" name="description">

        <!-- Favicon -->
       
        <!-- Stylesheet -->
        <link href="Styles_CSS/Style_connexion.css" rel="stylesheet">
    </head>
    <body>
        <?php 
            //include("../../../Conexion_BDD/Connexion_1.php" C:\wamp64\www\webUKA\UKA_Numerique);
            include("../Connexion_BDD/Connexion_1.php");

            $msgerreur='';
            
            
            if(isset($_POST['Connexion'])) 
            {
                $login = ($_POST['nom_utilisateur']);
                $motdepasse =  ($_POST['mot_de_passe']);
                
                $sql="SELECT 
                compte_agent.Mat_agent as mat_agent, 
                compte_agent.Login as login_agent, 
                compte_agent.Mot_passe as password_agent,
                compte_agent.Etat as etat_compte, 
                compte_agent.Categorie as categorie,
                compte_agent.Photo_profil as photo,
                agent.Nom_agent as nom_agent, 
                agent.Prenom as prenom,
                agent.Post_agent as postnom
                
                FROM compte_agent,agent WHERE
                compte_agent.Mat_agent=agent.Mat_agent and  compte_agent.Login=?";
             
                $stat= $con->prepare($sql);
                $stat->execute(array($login));
                $totale = $stat->rowCount();    
                $resultat = $stat->fetchAll(PDO::FETCH_ASSOC); 
                foreach($resultat as $ligne)
                {
                    $ligne_motdepasse = $ligne['password_agent'];                    
                    $etat_compte= $ligne['etat_compte'];

                    
        
          
                    if($totale==0) 
                    {
                        $msgerreur = 'Le nom utilisateur incorect <br>';
                    } 
                    else 
                    {              
                        if( $ligne_motdepasse == sha1($motdepasse) )
                        {
                            // Ici ontest si son etat est actif
                            if($etat_compte=="Actif")
                            {
                
                                $_SESSION['MatriculeAgent'] = $ligne['mat_agent'];
                                $_SESSION['Login_user'] = $ligne['login_agent'];
                                $_SESSION['Categorie'] = $ligne['categorie'];                    
                                $_SESSION['Nom_user'] = $ligne['nom_agent'];                                                     
                                $_SESSION['Postnom_user'] = $ligne['postnom'];                                                   
                                $_SESSION['prenom__user'] = $ligne['prenom'];                                        
                                $_SESSION['Photo_profil'] = $ligne['photo'];

                                if($ligne['categorie']=="Guichetier")
                                {
                                    header('location:Page_Principale.php');
                                }
                                else if($ligne['categorie']=="Administrateur de Budget")
                                {
                                    header('location:Page_Principale.php');
                                }
                                else if($ligne['categorie']=="Admin")
                                {
                                    header('location:D_Administration/Admin.php?page=CreerCompte');
                                }
                                else if($ligne['categorie']=="Encodeur")
                                {
                                    header('location:D_Encodage/index.php');
                                }
                              elseif($ligne['categorie']=="Comptable")
                                {
                                    header('location:Page_Principale.php');
                                }
                                
                                

                            }
                            else $msgerreur = "Votre compte a été desactivé veuillez contacter l'Administrateur <br>";

                        } 
                        else $msgerreur = 'Mot de passe invalide <br>';
                    }
                }
          
            } 
        ?>
        <div class="wrapper login-3">
            <div class="container">
                <div class="col-left">
                
                    <div class="login-text">
                   <!--<img src="LOGO.png" alt="Logo">-->
                    <h2>U.KA.</h2>
                <h3>Administration Numérique.</h3>
                       <hr>
                        <p >
                           De la Gestion Manuelle à la Gestion Electronique: Notre Solution Complète 
                        </p>
                        
                    </div>
                </div>
                <div class="col-right">
                    <div class="login-form">
                        <h2>Connexion</h2>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <p>
                                <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" requirefd>
                            </p>
                            <p>
                                <input type="password" name="mot_de_passe" placeholder="Mot de passe" requirefd>
                            </p>
                            <p>
                                <input class="btn" type="submit" name="Connexion" value="Valider" />
                            </p>
                            <p>
                            <?php 
                                if (isset($msgerreur))
                                {
                                    echo '<font color="red" size="3%">'. $msgerreur."</form>";
                                    
                                }
                            ?>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
