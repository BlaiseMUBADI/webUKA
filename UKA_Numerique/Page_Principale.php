
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
    
   

    <!-- CSS dans le bon ordre: Bootstrap d'abord, puis Nos_Tableaux.css en dernier pour priorité -->
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\datatables.min.css"/>  
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\all.min.css"  />
    <link rel="stylesheet"  href="D_Perception/JavaScript/all.min.css" >
    <link href="D_Administratif/css/style_1.css" rel="stylesheet">
    <link href="D_Administratif/css/fullcalendar.min.css" rel="stylesheet">
    <!--------------------------ICI ON INTEGRE LES CLASSES QUI APPORTE LES ICONS ----------------------------
    <link rel="stylesheet" href="D_Generale\JavaScript\all.min.css">
    <---------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="Styles_CSS/Styles.css" />
    <link rel="stylesheet" type="text/css" href="Styles_CSS/Styles_specifique.css" />
    <link rel="stylesheet" type="text/css" href="Styles_CSS/Nos_Tableaux.css">

  
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


  


  </head>
  <body style="height:auto; " >
      <?php
         
          include("../../Connexion_BDD/Connexion_1.php");
          
          //include("D_Generale/Connexion.php");
          ?>
          <div style="display:bloc " id="menugauche"> <?php
          
              if(isset($_SESSION['Categorie']))
              {
                if($_SESSION['Categorie']=="Administrateur de Budget")
                {
                  require_once 'D_Generale/Menu_Gauche_Perception.php';
                }               
                else if($_SESSION['Categorie']=="Assistant Administratif")
                {
                  require_once 'D_Generale/Menu_Gauche_Sec_Admin.php';
                }

              }
              else {
                header("Location: Fonctions_PHP/Deconnexion.php");
                exit();
              };
              
              
             
               ?>
          </div>
          
          <?php
          if(@$_GET['page'])
          {
            
            if($_GET['page']=="admin")require_once 'D_Administration/Entree_Par_Manip_Compte_user.php';
            
            
            //Secrétariat Général Administratif
            else if($_GET['page']=="AjoutAgent")require_once 'D_Administratif/Ajouter_Agent.php';
            else if($_GET['page']=="ListeAgent")require_once 'D_Administratif/Liste_Agent.php';
            else if($_GET['page']=="ListeToutPersonnel")require_once 'D_Administratif/Liste_tout_le_personnel.php';
            else if($_GET['page']=="EtatPaie")require_once 'D_Administratif/Etat_Prise_En_Charge.php';
            else if($_GET['page']=="Primé")require_once 'D_Administratif/Liste_Agent_Ayant_Prime.php';
            else if($_GET['page']=="Base")require_once 'D_Administratif/Liste_Agent_Ayant_Base.php';
            else if($_GET['page']=="Prime_et_base")require_once 'D_Administratif/Liste_Agent_Ayant_Base_et_Prime.php';
            else if($_GET['page']=="Sanctionner")require_once 'D_Administratif/Sanctionner.php';
            
            else if($_GET['page']=="PaieLocale")require_once 'D_Administratif/Paie_locale.php';
            else if($_GET['page']=="Dashboard")require_once 'D_Administratif/Tableau_de_Bord.php';
            else if($_GET['page']=="Admin")require_once 'D_Administratif/Admin.php';



            
            else if($_GET['page'] == "espacepersoagent") 
            {
              require_once 'D_Administratif/Gestion_Indiv_Agent.php';
              echo "<div class='calendar-container'><div id='calendar'></div></div>";
              echo "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function() { calendrier(); });</script>";
            }

            

            else if($_GET['page']=="non_acces")require_once 'D_Generale/Entree_Erreur.php';
          


          //else require_once 'D_Generale/Entree_page_pardefaut.php';
            
          }
          else require_once 'D_Generale/Entree_page_pardefaut.php';
      ?>
      
  </body>


   
  <script type="text/javascript" src="D_Generale/JavaScript/Fonctions.js"></script>  
  <script type="text/javascript" src="D_Generale/JavaScript/recup_promotion_et_etudiant.js"></script> 
  <script type="text/javascript" src="D_Generale/JavaScript/Deconnexion_inactiviter.js"></script>

  <script type="text/javascript" src="D_Generale/JavaScript/Inscription.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Requetes.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Liste_Agent_par_Categorie.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Liste_Complete.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Prise_Charge_Etat.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Liste_Agent_ayant_Prime.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Liste_Agent_ayant_Base.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Liste_Agent_ayant_Base_et_Prime.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Liste_Agent_Sanctionner.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Paie_locale.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Selection_Agent_Fiche_Familiale.js"></script>
  <script type="text/javascript" src="D_Administratif/js/fullcalendar.min.js"></script>
  <script type="text/javascript" src="D_Administratif/js/tableau_de_bord_agent.js"></script>
  <script type="text/javascript" src="D_Administratif/js/chartjs-plugin-datalabels@2.0.js"></script>
  <script type="text/javascript" src="D_Administratif/js/Events_Calendrier.js"></script>
  <script type="text/javascript" src="D_Administratif/js/sweetalert2@11.js"></script>

  <!-- jQuery DOIT être chargé AVANT Bootstrap -->
  <script type="text/javascript" src="D_Administratif/js/jquery-3.3.1.slim.min.js"></script>
  <script type="text/javascript" src="D_Administratif/js/popper.min.js"></script>
  <script type="text/javascript" src="D_Administratif/js/bootstrap.min.js"></script>  

  <script type="text/javascript" src="bootstrap/dist/js/bootstrap.bundle.js"></script>  
  <script type="text/javascript" src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>  
  <script type="text/javascript" src="fontawesome-6.5.1/js/all.min.js"></script>
  

 
</html>


