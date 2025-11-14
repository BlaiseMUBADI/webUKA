<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
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
                        
                        compte_agent.Code_promotion,
                        compte_agent.id_annee_academique,
                        concat(promo.Abréviation, ' ', mentions.Libelle_mention) as promm,
                        agent.Nom_agent as nom_agent, 
                        agent.Prenom as prenom,
                        agent.Post_agent as postnom,

                        filiere.Idfiliere as id_fac,
                        filiere.Libelle_Filiere as libelle_fac
                    FROM compte_agent
                    INNER JOIN agent ON compte_agent.Mat_agent = agent.Mat_agent
                    LEFT JOIN filiere ON compte_agent.Id_filiere = filiere.IdFiliere
                    LEFT JOIN promotion promo ON compte_agent.Code_promotion = promo.Code_Promotion
                    LEFT JOIN mentions ON mentions.idMentions = promo.idMentions
                    WHERE compte_agent.Login = ?";
             
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
                        if( sha1($motdepasse) === $ligne_motdepasse || password_verify($motdepasse, $ligne_motdepasse))
                        {
                            // Ici ontest si son etat est actif
                            if($etat_compte=="Actif")
                            {
                
                                $_SESSION['MatriculeAgent'] = $ligne['mat_agent'];
                                $_SESSION['MatriculeAgent'] = $ligne['mat_agent'];
                                $_SESSION['Login_user'] = $ligne['login_agent'];
                                $_SESSION['Categorie'] = $ligne['categorie'];                    
                                $_SESSION['id_fac'] = $ligne['id_fac'];                    
                                $_SESSION['libelle_fac'] = $ligne['libelle_fac'];                    
                                $_SESSION['Nom_user'] = $ligne['nom_agent'];                                                     
                                $_SESSION['Postnom_user'] = $ligne['postnom'];                                                   
                                $_SESSION['prenom__user'] = $ligne['prenom'];                                        
                                $_SESSION['Photo_profil'] = $ligne['photo'];

                                $_SESSION['prommotion'] = $ligne['promm'];
                                $_SESSION['code_prom'] = $ligne['Code_promotion'];
                                $_SESSION['id_annee_acad'] = $ligne['id_annee_academique'];

                                if($ligne['categorie']=="Guichetier")
                                {
                                    header('location:Page_Principale.php');
                                }
                                else if($ligne['categorie']=="Assistant Administratif")
                                {
                                    header('location:Page_Principale.php?page=Dashboard');
                                }
                                else if($ligne['categorie']=="Administrateur de Budget")
                                {
                                    header('location:Page_Principale.php');
                                }
                                else if($ligne['categorie']=="Admin")
                                {
                                    header('location:D_Administration/Principal.php?page=CreerCompte');
                                }
                                else if($ligne['categorie']=="Encodeur")
                                {
                                    header('location:D_Encodage/index.php');
                                }
                                
                                else if($ligne['categorie']=="Comptable")
                                {
                                    header('location:Page_Principale.php');
                                }

                                else if($ligne['categorie']=="Academique")
                                {
                                    header('location:D_Academique/index.php');
                                }

                                // Cette condition est là pour redireiger vers le dossier D_AdministrationFac
                                else if($ligne['categorie']=="Admin_Fac")
                                {
                                    header('location:D_Administration_Fac/Principale_admin_fac.php');
                                }

                                // Cette condition est là pour redireiger vers le dossier D_Faculté
                                else if($ligne['categorie']=="Doyen" 
                                || $ligne['categorie']=="VD"
                                || $ligne['categorie']=="Sec_facultaire")
                                {
                                    header('location:D_Faculte/Principale_fac.php');
                                }

                                // Cette condition est là pour redireiger vers le dossier D_Faculté précisement das délibération

                                else if($ligne['categorie']=="Secrétaire_jury")
                                {
                                    header('location:D_Faculte/Principale_fac.php');
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
