<?php session_start();?>

<!DOCTYPE html>
<html  >
<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v5.8.0, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/logouka.jpg" type="image/x-icon">
  <meta name="description" content="">
  
  
  <title>web.uka.ac.cd</title>
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons2/mobirise2.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css"><link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">

 
  
  
</head>
<body>
<?php
    include("../../Connexion_BDD/Connexion_1.php");
    //include("connexion.php");
      ?>
              
            </div>
  <section data-bs-version="5.1" class="menu menu3 cid-sFAA5oUu2Y" once="menu" id="menu3-1" style="display:bloc">
    
    <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg ">
      <div class="container ">
            
        <?php	
        
            include("Menu.php");
        ?>
			</div>
    </nav>
</section>

<section data-bs-version="5.1" class="header1 cid-sFCAOqBTxa" id="header1-i">
  <div class="container">
        
                <?php
                 if (@$_GET['page']) 
                 { 
                      if ($_GET['page']=="CreerCompteAgent") 
                        {
                            include("CreerCompteAgent.php");
                        }
                      elseif ($_GET['page']=="AfficherCompteAgent")
                        {
                            include("AfficherCompteAgent.php");
                        }
                      elseif ($_GET['page']=="ModifierCompteAgent")
                      {
                        include("ModifierCompteAgent.php");
                        
                      }
                      elseif ($_GET['page']=="Semestre")
                      {
                        include("Semestre.php");
                      }
                      elseif ($_GET['page']=="ListeSemetre")
                      {
                        include("Liste_Semestre_UE.php");
                      }
                      elseif ($_GET['page']=="UE")
                      {
                        include("Saisie_UE.php");
                      }
                      elseif ($_GET['page']=="ListeUE")
                      {
                        include("Liste_UE.php");
                      }
                      elseif ($_GET['page']=="EC")
                      {
                        include("Saisie_EC.php");
                      }
                      elseif ($_GET['page']=="Liste")
                      {
                        include("Listepaie.php");
                      }
                      else 
                      {
                        echo "<h1 style='text-align: center;'> Vous êtes sur la plateforme d'Administration Générale</h1><br><br><br><br><br>";
                        echo "<h3 style='text-align: center;'> Bienvenu et Bon Travail</h3><br><br><br>";
                      }
                
                 }
                      
                ?>
               
                
           
                
            </div>
        </div>
    </div>
</section>

<section data-bs-version="5.1" class="footer7 cid-tVuvH1dJHp" once="footers" id="footer7-0">

    

    <div class="mbr-overlay" style="opacity: 0.6; background-color: rgb(35, 35, 35);"></div>

    <div class="container" style="display:bloc" id="fin">
        <div class="media-container-row align-center mbr-white">
            <div class="col-12">
                <p class="mbr-text mb-0 mbr-fonts-style display-7">
                    © <a href="Authentification.php" style="color:#fff;">Copyright </a> | Cellule Informatique de l'UKA</p>
            </div>
        </div>
    </div>
</section>

    
    <script type="text/javascript" src="js/AfficherSemestre.js"></script>
    
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/smoothscroll/smooth-scroll.js"></script>  
    <script src="assets/ytplayer/index.js"></script>  <script src="assets/dropdown/js/navbar-dropdown.js"></script>  
    <script src="assets/embla/embla.min.js"></script>  <script src="assets/embla/script.js"></script>  
    <script src="assets/theme/js/script.js"></script>  
  
  
</body>
</html>