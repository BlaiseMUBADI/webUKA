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
       
        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="fontawesome-6.5.1/css/all.min.css">
        
        <!-- Stylesheet -->
        <link href="Styles_CSS/Style_connexion.css" rel="stylesheet">
        
        <style>
            /* Animation shake pour les erreurs */
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
                20%, 40%, 60%, 80% { transform: translateX(10px); }
            }
            
            .error-message {
                animation: shake 0.5s;
            }
            
            /* Amélioration du formulaire */
            .login-form input[type="text"],
            .login-form input[type="password"] {
                transition: all 0.3s ease;
            }
            
            .login-form input[type="text"]:focus,
            .login-form input[type="password"]:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                outline: none;
            }
            
            .login-form input[type="text"].error,
            .login-form input[type="password"].error {
                border-color: #ef4444;
                animation: shake 0.5s;
            }
        </style>
    </head>
    <body>
        <?php 
            //include("../../../Conexion_BDD/Connexion_1.php" C:\wamp64\www\webUKA\UKA_Numerique);
            include("../Connexion_BDD/Connexion_1.php");
            require_once("D_Generale/SessionManager.php");

            // Variables d'initialisation
            $msgerreur='';  // Message d'erreur à afficher en cas d'échec de connexion
            $afficher_choix = false;  // Flag pour afficher la boîte de dialogue de choix de compte
            $afficher_demande_confirmation = false; // Flag pour afficher la demande de confirmation au nouveau demandeur
            $demande_info = null; // Informations de la demande en attente
            
            // Initialiser le gestionnaire de sessions
            $sessionManager = new SessionManager($con);
            
            /**
             * Fonction centralisée de redirection selon la catégorie d'agent
             * 
             * Cette fonction évite la duplication de code en centralisant toutes les redirections
             * basées sur la catégorie de l'utilisateur. Au lieu de répéter 16 conditions if-else
             * dans trois endroits différents, on utilise un tableau associatif pour mapper
             * chaque catégorie à sa page de destination.
             * 
             * @param string $categorie La catégorie de l'agent (Guichetier, Admin, Doyen, etc.)
             * @return bool Retourne false si la catégorie est inconnue, sinon redirige et termine
             */
            function rediriger_selon_categorie($categorie) {
                // Tableau associatif mappant chaque catégorie d'agent à sa page d'accueil
                // Avantage: Maintenance simplifiée - un seul endroit pour modifier les redirections
                $redirections = [
                    'Guichetier' => 'D_Perception/Principale_perception.php',
                    'Admin' => 'D_Administration/Principal.php?page=CreerCompteAgent',
                    'Assistant Administratif' => 'Page_Principale.php?page=Dashboard',
                    'Administrateur de Budget' => 'Page_Principale_Finance.php?page=Dash_Board',
                    'Recteur' => 'Page_Principale_Finance.php?page=Dash_Board',
                    'Caissière principale' => 'Page_Principale_Finance.php?page=Dash_Board_Caisse',
                    'Encodeur' => 'D_Encodage/index.php',
                    'Comptable' => 'D_Perception/Principale_perception.php',
                    'Contrôleur interne' => 'Page_Principale_Finance.php?page=Dash_Board',
                    'Assistant AB' => 'Page_Principale_Finance.php?page=autorisation',
                    'Academique' => 'D_Academique/index.php',
                    'Admin_Fac' => 'D_Administration_Fac/Principale_admin_fac.php',
                    'Doyen' => 'D_Faculte/Principale_fac.php',
                    'VD' => 'D_Faculte/Principale_fac.php',
                    'Sec_facultaire' => 'D_Faculte/Principale_fac.php',
                    'Secrétaire_jury' => 'D_Faculte/Principale_fac.php'
                ];
                
                // Vérifier si la catégorie existe dans le tableau
                if (isset($redirections[$categorie])) {
                    // Rediriger vers la page appropriée et arrêter l'exécution
                    header('location:' . $redirections[$categorie]);
                    exit;
                } else {
                    // Retourner false pour permettre une gestion personnalisée de l'erreur
                    return false; // Catégorie non reconnue
                }
            }
            
            /*
             * BLOC 1: GESTION DU CHOIX DE COMPTE
             * 
             * Ce bloc traite la sélection de l'utilisateur lorsqu'il possède deux comptes actifs:
             * - Un compte agent (administratif classique)
             * - Un compte membre de jury (délibération)
             * 
             * L'utilisateur a déjà été authentifié et a choisi quel compte utiliser.
             * On récupère les données stockées en session temporaire et on établit la session définitive.
             */
            if(isset($_POST['choix_type_compte'])) 
            {
                $type_compte = $_POST['choix_type_compte'];
                
                // L'utilisateur a choisi d'utiliser son compte agent
                if($type_compte == 'agent' && isset($_SESSION['choix_compte_agent'])) 
                {
                    // Récupérer les données du compte agent depuis la session temporaire
                    $ligne = $_SESSION['choix_compte_agent'];
                    
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
                    
                    // Nettoyer les sessions temporaires (plus nécessaires après le choix)
                    unset($_SESSION['choix_compte_agent']);
                    unset($_SESSION['choix_compte_jury']);
                    unset($_SESSION['choix_en_cours']);
                    
                    // Rediriger vers la page appropriée selon la catégorie
                    // Si la catégorie est inconnue, rediriger vers le tableau de bord par défaut
                    if (!rediriger_selon_categorie($ligne['categorie'])) {
                        header('location:Page_Principale.php?page=Dashboard');
                        exit;
                    }
                    
                } 
                
                else if($type_compte == 'jury' && isset($_SESSION['choix_compte_jury'])) 
                {
                    $ligne = $_SESSION['choix_compte_jury'];
                    
                    $_SESSION['MatriculeAgent'] = $ligne['Mat_agent'];
                    $_SESSION['Login_user'] = $ligne['login_jury'];                    ;
                    $_SESSION['Role_Jury'] = $ligne['role'];
                    $_SESSION['Categorie'] = 'Membre_Jury/'.$ligne['role'];
                    $_SESSION['Nom_user'] = $ligne['Nom_agent'];
                    $_SESSION['Postnom_user'] = $ligne['Post_agent'];
                    $_SESSION['prenom__user'] = $ligne['Prenom'];
                    $_SESSION['Libelle_jury'] = $ligne['Libelle_jury'];
                    $_SESSION['code_prom'] = $ligne['Code_Promotion'];
                    $_SESSION['ID_jury'] = $ligne['ID_jury'];
                    $_SESSION['id_annee_acad'] = $ligne['idAnnee_Acad'];
                    $_SESSION['id_fac'] = $ligne['id_fac'];
                    $_SESSION['libelle_fac'] = $ligne['libelle_fac'];
                    $_SESSION['prommotion'] = $ligne['promm'];
                    
                    // Nettoyer les sessions temporaires
                    unset($_SESSION['choix_compte_agent']);
                    unset($_SESSION['choix_compte_jury']);
                    unset($_SESSION['choix_en_cours']);
                    
                    // Redirection selon le rôle
                    if($ligne['role'] == 'Président') {
                        header('location:D_Faculte/Principale_fac.php?page=gestion_deliberation');
                    } else if($ligne['role'] == 'Secrétaire') {
                        header('location:D_Faculte/Principale_fac.php?page=gestion_encodage');
                    } else {
                        header('location:D_Faculte/Principale_fac.php?page=consultation_jury');
                    }
                    exit;
                }
            }
            
            /*
             * BLOC 2: TRAITEMENT DE LA CONNEXION INITIALE
             * 
             * Ce bloc implémente un système d'authentification duale avec gestion des sessions:
             * 1. Recherche simultanée dans compte_agent ET t_membre_jury
             * 2. Validation du mot de passe (SHA1 legacy ou bcrypt moderne)
             * 3. Vérification de session active existante
             * 4. Création de demande de connexion si session active
             * 5. Gestion de 4 cas possibles:
             *    - Aucun compte valide → Message d'erreur
             *    - Les deux comptes valides → Afficher boîte de dialogue de choix
             *    - Seulement compte jury → Connexion directe au jury
             *    - Seulement compte agent → Connexion directe selon catégorie
             */
            if(isset($_POST['Connexion'])) 
            {
                // Récupérer les identifiants saisis par l'utilisateur
                $login = ($_POST['nom_utilisateur']);
                $motdepasse =  ($_POST['mot_de_passe']);
                
                // ===== ÉTAPE 1: RECHERCHE DANS LA TABLE compte_agent =====
                // Requête pour récupérer les informations complètes de l'agent
                // Jointures multiples pour obtenir: agent, filière, promotion, mentions
                $sql_agent="SELECT compte_agent.Mat_agent as mat_agent, 
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
             
                $stat_agent = $con->prepare($sql_agent);
                $stat_agent->execute(array($login));
                $total_agent = $stat_agent->rowCount();
                $resultat_agent = $stat_agent->fetchAll(PDO::FETCH_ASSOC);
                
                // ===== ÉTAPE 2: RECHERCHE DANS LA TABLE t_membre_jury =====
                // Requête pour récupérer les informations du membre de jury
                // Jointures: t_jury_deliberation (infos jury), agent (infos personnelles),
                // promotion → mentions → filiere (pour obtenir la faculté)
                $sql_jury = "SELECT 
                        m.ID_jury_membre,
                        m.ID_jury,
                        m.Mat_agent,
                        m.role,
                        m.Login as login_jury,
                        m.Mot_passe as password_jury,
                        m.Statut as statut_jury,
                        j.Libelle_jury,
                        j.Code_Promotion,
                        j.idAnnee_Acad,
                        a.Nom_agent,
                        a.Post_agent,
                        a.Prenom,
                        f.IdFiliere as id_fac,
                        f.Libelle_Filiere as libelle_fac,
                        CONCAT(p.Abréviation, ' ', me.Libelle_mention) as promm
                    FROM t_membre_jury m
                    INNER JOIN t_jury_deliberation j ON m.ID_jury = j.ID_jury
                    INNER JOIN agent a ON m.Mat_agent = a.Mat_agent
                    LEFT JOIN promotion p ON j.Code_Promotion = p.Code_Promotion
                    LEFT JOIN mentions me ON p.idMentions = me.idMentions
                    LEFT JOIN filiere f ON me.IdFiliere = f.IdFiliere
                    WHERE m.Login = ? AND m.Statut = 'Actif'";
                
                $stat_jury = $con->prepare($sql_jury);
                $stat_jury->execute(array($login));
                $total_jury = $stat_jury->rowCount();
                $resultat_jury = $stat_jury->fetchAll(PDO::FETCH_ASSOC);
                
                // ===== ÉTAPE 3: VALIDATION DES MOTS DE PASSE =====
                // Initialisation des variables de validation
                $compte_agent_valide = false;  // True si login + mot de passe agent corrects
                $compte_jury_valide = false;   // True si login + mot de passe jury corrects
                $data_agent = null;  // Données du compte agent si valide
                $data_jury = null;   // Données du compte jury si valide
                
                // Vérifier compte agent (si le login existe)
                if($total_agent > 0) {
                    foreach($resultat_agent as $ligne) {
                        // Double validation: SHA1 (ancien système) OU bcrypt (nouveau système)
                        // Cela permet la transition progressive vers bcrypt sans casser les anciens comptes
                        $password_valid = (sha1($motdepasse) === $ligne['password_agent']) || 
                                         (password_verify($motdepasse, $ligne['password_agent']) === true);
                        if($password_valid && $ligne['etat_compte'] == "Actif") {
                            $compte_agent_valide = true;
                            $data_agent = $ligne;
                            break;
                        }
                    }
                }
                
                // Vérifier compte jury (si le login existe)
                if($total_jury > 0) {
                    foreach($resultat_jury as $ligne) {
                        // Les comptes jury utilisent UNIQUEMENT bcrypt (système moderne)
                        if(password_verify($motdepasse, $ligne['password_jury'])) {
                            $compte_jury_valide = true;
                            $data_jury = $ligne;
                            break;
                        }
                    }
                }
                
                // ===== ÉTAPE 3.5: VALIDATION MATRICULE POUR LE CHOIX =====
                // Si les deux comptes sont valides, vérifier s'ils ont le MÊME matricule
                // Le choix n'est proposé QUE si c'est la même personne (même matricule)
                $choix_possible = false;
                if($compte_agent_valide && $compte_jury_valide) {
                    // Comparer les matricules
                    if($data_agent['mat_agent'] === $data_jury['Mat_agent']) {
                        // MÊME MATRICULE = MÊME PERSONNE
                        // On peut proposer le choix entre les deux comptes
                        $choix_possible = true;
                    } else {
                        // MATRICULES DIFFÉRENTS = PERSONNES DIFFÉRENTES
                        // Priorité au compte agent (connexion directe)
                        // Le compte jury est ignoré dans ce cas
                        $compte_jury_valide = false;
                        $data_jury = null;
                    }
                }
                
                // ===== ÉTAPE 4: GESTION DES DIFFÉRENTS CAS =====
                // Variables de compatibilité (pour ne pas casser le code existant)
                $totale = $total_agent;
                $resultat = $resultat_agent;
                
                // --- CAS 1: AUCUN COMPTE VALIDE ---
                // Soit le login n'existe pas, soit le mot de passe est incorrect
                if(!$compte_agent_valide && !$compte_jury_valide)
                {
                    if($total_agent == 0 && $total_jury == 0) {
                        $msgerreur = '<i class="fas fa-user-times"></i> Nom d\'utilisateur introuvable';
                    } else {
                        $msgerreur = '<i class="fas fa-key"></i> Mot de passe incorrect ou compte inactif';
                    }
                }
                // --- CAS 2: LES DEUX COMPTES SONT VALIDES ET MÊME MATRICULE ---
                // L'utilisateur a deux rôles actifs (agent ET membre de jury)
                // ET c'est la même personne (même matricule)
                // On doit lui demander quel compte il veut utiliser pour cette session
                else if($compte_agent_valide && $compte_jury_valide && $choix_possible)
                {
                    // Stocker les données en session temporaire (accessible après rechargement de page)
                    // Ces données seront utilisées dans le BLOC 1 quand l'utilisateur fait son choix
                    $_SESSION['choix_compte_agent'] = $data_agent;
                    $_SESSION['choix_compte_jury'] = $data_jury;
                    $_SESSION['choix_en_cours'] = true;  // Flag pour afficher la boîte de dialogue
                }
                // --- CAS 3: SEULEMENT COMPTE JURY VALIDE ---
                // Connexion directe en tant que membre de jury
                // AVEC VÉRIFICATION DE SESSION ACTIVE
                else if($compte_jury_valide && !$compte_agent_valide)
                {
                    $ligne = $data_jury;
                    
                    // **NOUVEAU: Vérifier si une session active existe déjà**
                    if($sessionManager->hasActiveSession($ligne['login_jury'], 'jury')) {
                        // Une session est déjà active pour cet utilisateur
                        $user_data = [
                            'login' => $ligne['login_jury'],
                            'matricule' => $ligne['Mat_agent'],
                            'type_compte' => 'jury'
                        ];
                        
                        $session_actuelle = $sessionManager->getActiveSession($ligne['login_jury'], 'jury');
                        $demande = $sessionManager->createConnectionRequest($user_data, $session_actuelle['session_id']);
                        
                        $afficher_demande_confirmation = true;
                        $demande_info = [
                            'demande_id' => $demande['demande_id'],
                            'token' => $demande['token'],
                            'nom_complet' => $ligne['Nom_agent'] . ' ' . $ligne['Post_agent'],
                            'login' => $ligne['login_jury']
                        ];
                        
                        $msgerreur = '<i class="fas fa-info-circle"></i> Une session est déjà active. En attente de confirmation...';
                    } else {
                        // Aucune session active, créer une nouvelle session
                        $user_data = [
                            'login' => $ligne['login_jury'],
                            'matricule' => $ligne['Mat_agent'],
                            'type_compte' => 'jury'
                        ];
                        
                        $sessionManager->createSession($user_data);
                        
                        $_SESSION['MatriculeAgent'] = $ligne['Mat_agent'];
                        $_SESSION['Login_user'] = $ligne['login_jury'];
                        $_SESSION['Role_Jury'] = $ligne['role'];
                        $_SESSION['Categorie'] = 'Membre_Jury/'.$ligne['role'];
                        $_SESSION['Nom_user'] = $ligne['Nom_agent'];
                        $_SESSION['Postnom_user'] = $ligne['Post_agent'];
                        $_SESSION['prenom__user'] = $ligne['Prenom'];
                        $_SESSION['Libelle_jury'] = $ligne['Libelle_jury'];
                        $_SESSION['code_prom'] = $ligne['Code_Promotion'];
                        $_SESSION['ID_jury'] = $ligne['ID_jury'];
                        $_SESSION['id_annee_acad'] = $ligne['idAnnee_Acad'];
                        $_SESSION['id_fac'] = $ligne['id_fac'];
                        $_SESSION['libelle_fac'] = $ligne['libelle_fac'];
                        $_SESSION['prommotion'] = $ligne['promm'];
                        
                        // Redirection selon le rôle
                        if($ligne['role'] == 'Président') {
                            header('location:D_Faculte/Principale_fac.php?page=gestion_deliberation');
                        } else if($ligne['role'] == 'Secrétaire') {
                            header('location:D_Faculte/Principale_fac.php?page=gestion_encodage');
                        } else {
                            header('location:D_Faculte/Principale_fac.php?page=consultation_jury');
                        }
                        exit;
                    }
                }


                // --- CAS 4: SEULEMENT COMPTE AGENT VALIDE ---
                // Connexion directe en tant qu'agent selon la catégorie
                // AVEC VÉRIFICATION DE SESSION ACTIVE
                else if($compte_agent_valide && !$compte_jury_valide)
                {
                    $ligne = $data_agent;
                    
                    // **NOUVEAU: Vérifier si une session active existe déjà**
                    if($sessionManager->hasActiveSession($ligne['login_agent'], 'agent')) {
                        // Une session est déjà active pour cet utilisateur
                        // Créer une demande de connexion
                        $user_data = [
                            'login' => $ligne['login_agent'],
                            'matricule' => $ligne['mat_agent'],
                            'type_compte' => 'agent'
                        ];
                        
                        $session_actuelle = $sessionManager->getActiveSession($ligne['login_agent'], 'agent');
                        $demande = $sessionManager->createConnectionRequest($user_data, $session_actuelle['session_id']);
                        
                        // Stocker les informations pour afficher la modal de confirmation
                        $afficher_demande_confirmation = true;
                        $demande_info = [
                            'demande_id' => $demande['demande_id'],
                            'token' => $demande['token'],
                            'nom_complet' => $ligne['nom_agent'] . ' ' . $ligne['postnom'],
                            'login' => $ligne['login_agent']
                        ];
                        
                        // Message d'information
                        $msgerreur = '<i class="fas fa-info-circle"></i> Une session est déjà active. En attente de confirmation...';
                    } else {
                        // Aucune session active, créer une nouvelle session
                        $user_data = [
                            'login' => $ligne['login_agent'],
                            'matricule' => $ligne['mat_agent'],
                            'type_compte' => 'agent'
                        ];
                        
                        $sessionManager->createSession($user_data);
                        
                        // Établir les sessions PHP normales
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

                        // Redirection selon catégorie
                        if (!rediriger_selon_categorie($ligne['categorie'])) {
                            // Catégorie inconnue, redirection par défaut
                            header('location:Page_Principale.php?page=Dashboard');
                            exit;
                        }
                    }
                }
            }
            
            /*
             * BLOC 3: ACTIVATION DE LA BOÎTE DE DIALOGUE
             * 
             * Cette vérification se fait APRÈS le traitement POST pour éviter le bug
             * où la boîte de dialogue s'affichait immédiatement sans traiter la connexion.
             * 
             * Le flag $afficher_choix déclenche l'affichage du modal HTML plus bas dans la page.
             */
            if(isset($_SESSION['choix_en_cours']) && $_SESSION['choix_en_cours'] === true) {
                $afficher_choix = true;
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
                            <?php 
                                if (!empty($msgerreur))
                                {
                                    echo '<div class="error-message" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 12px 20px; border-radius: 8px; margin-top: 15px; text-align: center; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); animation: shake 0.5s;">'. $msgerreur."</div>";
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Boîte de dialogue moderne pour choisir le type de compte -->
        <?php if($afficher_choix): ?>
        <div id="modal_choix_compte" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-out;">
            
            <div style="
                background: white;
                border-radius: 20px;
                padding: 0;
                max-width: 600px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                animation: slideDown 0.3s ease-out;
                overflow: hidden;">
                
                <!-- En-tête -->
                <div style="
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 30px;
                    text-align: center;
                    color: white;">
                    <i class="fas fa-user-cog" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.9;"></i>
                    <h2 style="margin: 0 0 10px 0; font-size: 1.8rem; font-weight: 700;">Sélectionnez votre service</h2>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Vous disposez de plusieurs accès</p>
                </div>
                
                <!-- Contenu -->
                <div style="padding: 40px 30px;">
                    <p style="
                        text-align: center;
                        color: #64748b;
                        margin-bottom: 30px;
                        font-size: 1rem;
                        line-height: 1.6;">
                        Bonjour <strong style="color: #1e293b;"><?php echo htmlspecialchars($_SESSION['choix_compte_agent']['nom_agent'] . ' ' . $_SESSION['choix_compte_agent']['postnom']); ?></strong>,<br>
                        Vous possédez deux comptes actifs. Veuillez choisir le service que vous souhaitez utiliser.
                    </p>
                    
                    <form method="POST" action="">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            
                            <!-- Carte Compte Agent -->
                            <button type="submit" name="choix_type_compte" value="agent" style="
                                border: 3px solid #e2e8f0;
                                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                                border-radius: 15px;
                                padding: 25px 20px;
                                cursor: pointer;
                                transition: all 0.3s;
                                text-align: center;"
                                onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(59,130,246,0.3)';"
                                onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                
                                <div style="
                                    width: 70px;
                                    height: 70px;
                                    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin: 0 auto 15px auto;
                                    box-shadow: 0 8px 20px rgba(59,130,246,0.3);">
                                    <i class="fas fa-briefcase" style="font-size: 2rem; color: white;"></i>
                                </div>
                                
                                <h3 style="
                                    margin: 0 0 8px 0;
                                    color: #1e293b;
                                    font-size: 1.1rem;
                                    font-weight: 700;">
                                    Compte Agent
                                </h3>
                                
                                <p style="
                                    margin: 0 0 12px 0;
                                    color: #64748b;
                                    font-size: 0.85rem;
                                    line-height: 1.4;">
                                    Accès administratif<br>et gestion courante
                                </p>
                                
                                <div style="
                                    background: rgba(59,130,246,0.1);
                                    border-radius: 8px;
                                    padding: 8px 12px;
                                    font-size: 0.8rem;
                                    color: #2563eb;
                                    font-weight: 600;">
                                    <?php echo htmlspecialchars($_SESSION['choix_compte_agent']['categorie']); ?>
                                </div>
                            </button>
                            
                            <!-- Carte Compte Jury -->
                            <button type="submit" name="choix_type_compte" value="jury" style="
                                border: 3px solid #e2e8f0;
                                background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
                                border-radius: 15px;
                                padding: 25px 20px;
                                cursor: pointer;
                                transition: all 0.3s;
                                text-align: center;"
                                onmouseover="this.style.borderColor='#10b981'; this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(16,185,129,0.3)';"
                                onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                
                                <div style="
                                    width: 70px;
                                    height: 70px;
                                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin: 0 auto 15px auto;
                                    box-shadow: 0 8px 20px rgba(16,185,129,0.3);">
                                    <i class="fas fa-gavel" style="font-size: 2rem; color: white;"></i>
                                </div>
                                
                                <h3 style="
                                    margin: 0 0 8px 0;
                                    color: #1e293b;
                                    font-size: 1.1rem;
                                    font-weight: 700;">
                                    Membre de Jury
                                </h3>
                                
                                <p style="
                                    margin: 0 0 12px 0;
                                    color: #64748b;
                                    font-size: 0.85rem;
                                    line-height: 1.4;">
                                    Délibération et<br>gestion des jurys
                                </p>
                                
                                <div style="
                                    background: rgba(16,185,129,0.15);
                                    border-radius: 8px;
                                    padding: 8px 12px;
                                    font-size: 0.8rem;
                                    color: #059669;
                                    font-weight: 600;">
                                    <?php echo htmlspecialchars($_SESSION['choix_compte_jury']['role']); ?>
                                </div>
                            </button>
                            
                        </div>
                        
                        <p style="
                            text-align: center;
                            color: #94a3b8;
                            font-size: 0.85rem;
                            margin: 20px 0 0 0;">
                            <i class="fas fa-info-circle"></i> Cliquez sur le service que vous souhaitez utiliser
                        </p>
                    </form>
                </div>
            </div>
        </div>
        
        <style>
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideDown {
                from { 
                    opacity: 0;
                    transform: translateY(-50px);
                }
                to { 
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
        <?php endif; ?>
        
        <!-- Modal de confirmation pour le nouveau demandeur -->
        <?php if($afficher_demande_confirmation && $demande_info): ?>
        <div id="modal_demande_confirmation" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-out;">
            
            <div style="
                background: white;
                border-radius: 20px;
                padding: 0;
                max-width: 550px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.4);
                animation: slideDown 0.3s ease-out;
                overflow: hidden;">
                
                <!-- En-tête -->
                <div style="
                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                    padding: 30px;
                    text-align: center;
                    color: white;">
                    <i class="fas fa-hourglass-half" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.9;"></i>
                    <h2 style="margin: 0 0 10px 0; font-size: 1.8rem; font-weight: 700;">Session déjà active</h2>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">En attente d'autorisation</p>
                </div>
                
                <!-- Contenu -->
                <div style="padding: 40px 30px;">
                    <p style="
                        text-align: center;
                        color: #64748b;
                        margin-bottom: 25px;
                        font-size: 1rem;
                        line-height: 1.6;">
                        Bonjour <strong style="color: #1e293b;"><?php echo htmlspecialchars($demande_info['nom_complet']); ?></strong>,<br>
                        Une session est déjà active sur un autre appareil.<br>
                        L'utilisateur actuel a été notifié de votre demande.
                    </p>
                    
                    <div style="
                        background: #fef3c7;
                        border-left: 4px solid #f59e0b;
                        padding: 15px 20px;
                        border-radius: 8px;
                        margin-bottom: 25px;">
                        <p style="margin: 0; color: #92400e; font-size: 0.9rem; line-height: 1.5;">
                            <i class="fas fa-info-circle"></i> Cette fenêtre se mettra à jour automatiquement lorsque l'utilisateur actuel répondra à votre demande.
                        </p>
                    </div>
                    
                    <div id="statut_demande" style="
                        text-align: center;
                        padding: 20px;
                        background: #f8fafc;
                        border-radius: 10px;
                        margin-bottom: 20px;">
                        <div style="
                            display: inline-block;
                            width: 40px;
                            height: 40px;
                            border: 4px solid #f59e0b;
                            border-top-color: transparent;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
                        "></div>
                        <p style="margin: 15px 0 0 0; color: #64748b; font-size: 0.9rem;">
                            Vérification du statut...
                        </p>
                    </div>
                    
                    <div style="text-align: center;">
                        <button onclick="annulerDemande()" style="
                            padding: 12px 30px;
                            background: #64748b;
                            color: white;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                            font-size: 14px;
                            transition: all 0.3s;">
                            <i class="fas fa-times"></i> Annuler et retourner
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        </style>
        
        <script>
            const demandeId = <?php echo $demande_info['demande_id']; ?>;
            const demandeToken = '<?php echo $demande_info['token']; ?>';
            let checkInterval;
            
            // Vérifier le statut de la demande toutes les 3 secondes
            function verifierStatutDemande() {
                fetch('API/check_request_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        demande_id: demandeId,
                        token: demandeToken
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statutDiv = document.getElementById('statut_demande');
                        
                        if (data.expired) {
                            clearInterval(checkInterval);
                            statutDiv.innerHTML = `
                                <i class="fas fa-times-circle" style="font-size: 3rem; color: #dc2626;"></i>
                                <p style="margin: 15px 0 0 0; color: #dc2626; font-weight: bold;">
                                    Demande expirée
                                </p>
                                <p style="margin: 10px 0 0 0; color: #64748b; font-size: 0.9rem;">
                                    Temps d'attente dépassé. Veuillez réessayer.
                                </p>
                            `;
                            setTimeout(() => window.location.reload(), 3000);
                        }
                        else if (data.can_confirm) {
                            // L'utilisateur actuel a accepté, demander confirmation
                            clearInterval(checkInterval);
                            afficherConfirmation();
                        }
                        else if (data.statut_demande === 'refusee') {
                            clearInterval(checkInterval);
                            statutDiv.innerHTML = `
                                <i class="fas fa-ban" style="font-size: 3rem; color: #dc2626;"></i>
                                <p style="margin: 15px 0 0 0; color: #dc2626; font-weight: bold;">
                                    Demande refusée
                                </p>
                                <p style="margin: 10px 0 0 0; color: #64748b; font-size: 0.9rem;">
                                    L'utilisateur actuel a refusé votre demande.
                                </p>
                            `;
                            setTimeout(() => window.location.reload(), 3000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            }
            
            // Afficher la demande de confirmation finale
            function afficherConfirmation() {
                const statutDiv = document.getElementById('statut_demande');
                statutDiv.innerHTML = `
                    <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981;"></i>
                    <p style="margin: 15px 0; color: #059669; font-weight: bold;">
                        Demande acceptée !
                    </p>
                    <p style="margin: 15px 0; color: #64748b; font-size: 0.95rem;">
                        Voulez-vous activer votre session maintenant ?
                    </p>
                    <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                        <button onclick="refuserActivation()" style="
                            padding: 10px 20px;
                            background: #64748b;
                            color: white;
                            border: none;
                            border-radius: 5px;
                            cursor: pointer;">
                            <i class="fas fa-times"></i> Non, annuler
                        </button>
                        <button onclick="confirmerActivation()" style="
                            padding: 10px 20px;
                            background: #10b981;
                            color: white;
                            border: none;
                            border-radius: 5px;
                            cursor: pointer;">
                            <i class="fas fa-check"></i> Oui, activer
                        </button>
                    </div>
                `;
            }
            
            // Confirmer l'activation de la session
            function confirmerActivation() {
                fetch('API/confirm_new_session.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        demande_id: demandeId,
                        token: demandeToken,
                        action: 'accepte'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        alert('Session activée avec succès !');
                        window.location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de l\'activation');
                });
            }
            
            // Refuser l'activation
            function refuserActivation() {
                fetch('API/confirm_new_session.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        demande_id: demandeId,
                        token: demandeToken,
                        action: 'refuse'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            }
            
            // Annuler la demande
            function annulerDemande() {
                if (confirm('Voulez-vous vraiment annuler votre demande de connexion ?')) {
                    clearInterval(checkInterval);
                    refuserActivation();
                }
            }
            
            // Démarrer la vérification
            checkInterval = setInterval(verifierStatutDemande, 3000);
            verifierStatutDemande(); // Première vérification immédiate
        </script>
        <?php endif; ?>
        
    </body>
</html>
