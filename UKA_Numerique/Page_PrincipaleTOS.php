
<?php
  include("Fonctions_PHP/profil_session.php");
?>
</div>
<!DOCTYPE html>
<!-- Website - www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <title>Université Notre-Dame du Kasayi</title>
    <link rel="shortcut icon" href="logo.ico" type="image/x-icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Login Form Template" name="keywords">
    <meta content="Login Form Template" name="description">
    

    <link rel="stylesheet" type="text/css" href="Styles_CSS/Nos_Tableaux.css">
    <link rel="stylesheet" type="text/css" href="Styles_CSS/Styles.css" />
    <link rel="stylesheet" type="text/css" href="Styles_CSS/Styles_specifique.css" />
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\bootstrap.min.css" >
    <link rel="stylesheet"  href="D_Perception\JavaScript\all.min.css" >

    <!--------------------------ICI ON INTEGRE LES CLASSES QUI APPORTE LES ICONS ------------------------------>
    <link rel="stylesheet" href="fontawesome-6.5.1/css/all.min.css">
    <!----------------------------------------------------------------------------------------------------->
        
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\datatables.min.css"/>  
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\all.min.css"  />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


  


  </head>
  <body style="height:1500px;" >
      <?php
         /* require_once 'Connexion.php';
/*
          if(@$_GET['mat_etudiant'])
          require_once 'Menu_Gauche_Perception.php';
          require_once 'Entree_Par_Guichet.php';*/
      ?>

      <?php
          //require_once 'D_Generale/Connexion.php';
          //include("../../../Conexion_BDD/Connexion_1.php");
          include("../Connexion_BDD/Connexion_1.php");
          
          //include("D_Generale/Connexion.php");
          ?><div style="display:bloc " id="menugauche"> <?php require_once 'D_Generale/Menu_Gauche_Perception.php'; ?></div>
          
          <?php
          if(@$_GET['page'])
          {
            if($_GET['page']=="guichet")require_once 'D_Perception/Entree_Par_Guichet.php';
            else if($_GET['page']=="banque")require_once 'D_Perception/Entree_Par_Banque.php';
            else if($_GET['page']=="Rapport_paie")require_once 'D_Perception/Entree_Raport_paie.php';            
            else if($_GET['page']=="manip_transaction")require_once 'D_Perception/Entree_Par_Manip_Operation.php';

            else if($_GET['page']=="non_acces")require_once 'D_Generale/Entree_Erreur.php';

            else if($_GET['page']=="ab_modalite_paie")require_once 'D_Budget/Entree_Par_Encodage_modalite_frais.php';
            else if($_GET['page']=="ab_taux_jours")require_once 'D_Budget/Entree_Par_Taux_Jours.php';
            


            else if($_GET['page']=="admin")require_once 'D_Administration/Entree_Par_Manip_Compte_user.php';
            else if($_GET['page']=="admin_fac")require_once 'D_Administration_Fac/Entree_Par_Guichet.php';
            else if($_GET['page']=="Inscription")require_once 'D_Perception/Entree_Inscription.php';
            


            
            //else if($_GET['page']=="Rapport_paie")require_once 'D_Perception/Entree_Raport_paie.php';
            
            
            

            
          }
          else require_once 'D_Generale/Entree_page_pardefaut.php';
      ?>
      
  </body>


   
  <script type="text/javascript" src="D_Generale/JavaScript/Fonctions.js"></script>
  
  <script type="text/javascript" src="D_Generale/JavaScript/recup_promotion_et_etudiant.js"></script>  
 
  <script type="text/javascript" src="D_Generale/JavaScript/Deconnexion_inactiviter.js"></script>
  
  <script type="text/javascript" src="D_Perception/JavaScript/Paiement_nouveau_frais.js"></script>
  <script type="text/javascript" src="D_Perception/JavaScript/Entree_rapport_paie.js"></script>  
  <script type="text/javascript" src="D_Perception/JavaScript/Recup_prom_et_Manip_Transact.js"></script>

  <script type="text/javascript" src="D_Perception/JavaScript/Rapport_paie.js"></script>
  <script type="text/javascript" src="D_Perception/JavaScript/Rapport_Inscription.js"></script>
  <script type="text/javascript" src="D_Perception/JavaScript/sweetalert.min.js"></script>
  <script type="text/javascript" src="D_Generale/JavaScript/Inscription.js"></script>
  
  <script type="text/javascript" src="D_Budget/JavaScript/recup_promotion_et_frais_fixe.js"></script>
  

  <script type="text/javascript" src="bootstrap/dist/js/bootstrap.bundle.js"></script>  
  <script type="text/javascript" src="fontawesome-6.5.1/js/all.min.js"></script>
  


</html>


