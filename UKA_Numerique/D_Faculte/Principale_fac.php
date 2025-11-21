<?php
  //session_start();
  include("../Fonctions_PHP/profil_session.php");
 
?>

<!DOCTYPE html>
<!-- Website - www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <title>Université Notre-Dame du Kasayi</title>
    <link rel="shortcut icon" href="../logo.ico" type="image/x-icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Login Form Template" name="keywords">
    <meta content="Login Form Template" name="description">

    <!-- Bootstrap en premier (priorité) -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/dist/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/dist/css/datatables.min.css"/>  
    <link rel="stylesheet" type="text/css" href="../bootstrap/dist/css/all.min.css" />
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="../fontawesome-6.5.1/css/all.min.css">
    
    <!-- Styles de base du système -->
    <link rel="stylesheet" type="text/css" href="../Styles_CSS/Nos_Tableaux.css">    
    <link rel="stylesheet" type="text/css" href="../Styles_CSS/Styles.css" />

    <style>
      /* Animation pour les dialogs */
      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateY(-30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* Style du backdrop pour les dialogs */
      dialog::backdrop {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease-out;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }

      /* Scroll personnalisé pour les dialogs */
      dialog ::-webkit-scrollbar {
        width: 8px;
      }

      dialog ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
      }

      dialog ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
      }

      dialog ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
      }
    </style>

  </head>
  <body">
      <?php
         /* require_once 'Connexion.php';
/*
          if(@$_GET['mat_etudiant'])
          require_once 'Menu_Gauche_Perception.php';
          require_once 'Entree_Par_Guichet.php';*/
      ?>

      <?php
      
          include("../../Connexion_BDD/Connexion_1.php");
          require_once 'Menu_Gauche_Faculte.php';
          


         
          if(@$_GET['page'])
          {
            
            if($_GET['page']=="gestion_UEs") require_once 'Entree_Par_Gestion_UEs.php';
            if($_GET['page']=="gestion_SM_ECs") require_once 'Entree_Par_Gestion_semestre_ECs.php';
            if($_GET['page']=="gestion_Aligne_ECs") require_once 'Entree_Par_Gestion_Aligne_ECs.php';
            if($_GET['page']=="gestion_Enseignants") require_once 'Entree_Par_Gestion_Enseignants.php';
            if($_GET['page']=="gestion_jury") require_once 'Entree_Par_Gestion_Jury.php';
            if($_GET['page']=="gestion_encodage") require_once 'Entree_Par_encodage.php';

            if($_GET['page']=="non_acces") require_once 'Entree_Erreur.php';
            
          }
          else require_once 'Entree_page_pardefaut.php';
      ?>
      
  </body>


   
  <script type="text/javascript" src="../D_Generale/JavaScript/Fonctions.js"></script>
    
  <script type="text/javascript" src="../D_Generale/JavaScript/Deconnexion_inactiviter.js"></script>
  
  <script type="text/javascript" src="JavaScript/Manip_UE.js"></script>
  <script type="text/javascript" src="JavaScript/Manip_Jury.js"></script>
  <script type="text/javascript" src="JavaScript/Manip_Enseignants.js"></script>
  <script type="text/javascript" src="JavaScript/Manip_EC_Aligner.js"></script>
  <script type="text/javascript" src="JavaScript/Manip_Encodage.js"></script>

  

  <script type="text/javascript" src="../bootstrap/dist/js/bootstrap.bundle.js"></script>  
  <script type="text/javascript" src="../fontawesome-6.5.1/js/all.min.js"></script>
  


</html>


