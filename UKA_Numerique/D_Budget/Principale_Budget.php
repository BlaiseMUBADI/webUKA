
<?php
  include("../Fonctions_PHP/profil_session.php");
?>
</div>
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
    <link rel="stylesheet" type="text/css" href="../Styles_CSS/Styles.css" />
    <link rel="stylesheet" type="text/css" href="../Styles_CSS/Nos_Tableaux.css">

  
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


  


  </head>
  <body style="height:auto; " >
      <?php
         
          include("../../Connexion_BDD/Connexion_1.php");
          require_once 'Menu_Gauche_Budget.php';
          
          if(@$_GET['page'])
          {
                   

            if($_GET['page']=="ab_modalite_paie")require_once 'Entree_Par_Encodage_modalite_frais.php';
            else if($_GET['page']=="ab_taux_jours")require_once 'Entree_Par_Taux_Jours.php';
            else if($_GET['page']=="ab_suivi_paie")require_once 'Suivi_paiement_FA.php';          
            


            else if($_GET['page']=="non_acces")require_once 'Page_Erreur.php';
          


          //else require_once 'D_Generale/Entree_page_pardefaut.php';
            
          }
          else require_once 'Page_par_defaut.php';
      ?>
      
  </body>
  
  
  <script type="text/javascript" src="JavaScript/recup_promotion_et_frais_fixe.js"></script>
  

  <script type="text/javascript" src="../D_Generale/JavaScript/Fonctions.js"></script>  
  <script type="text/javascript" src="../D_Generale/JavaScript/Deconnexion_inactiviter.js"></script>  



  <script type="text/javascript" src="../D_Perception/JavaScript/Rapport_Inscription.js"></script>
  <script type="text/javascript" src="../D_Perception/JavaScript/sweetalert.min.js"></script>
  <script type="text/javascript" src="../D_Perception/JavaScript/Entree_rapport_paie.js"></script>

  

  <script type="text/javascript" src="../bootstrap/dist/js/bootstrap.bundle.js"></script>  
  <script type="text/javascript" src="../fontawesome-6.5.1/js/all.min.js"></script>
</html>


