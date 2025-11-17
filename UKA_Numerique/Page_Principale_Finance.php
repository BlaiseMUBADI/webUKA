<?php
  include("Fonctions_PHP/profil_session.php");
  include("../Connexion_BDD/Connexion_1.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Université Notre-Dame du Kasayi</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon  -->
    <link href="D_Finance/img/logo.ico" rel="icon">

    <!-- Google Web Fonts -->

    
    <!-- Icon Font Stylesheet -->
     <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\all.min.css" >

    

    <!-- Libraries Stylesheet -->
    <link href="D_Finance/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="D_Finance/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link rel="stylesheet" type="text/css" href="bootstrap\dist\css\bootstrap.min.css" >

    <!-- Template Stylesheet -->
   
    <link rel="stylesheet" type="text/css" href="D_Finance/css/style_finance.css" />
    <link rel="stylesheet" type="text/css" href="D_Finance/css/style_finance_complet.css" />
    <link rel="stylesheet" type="text/css" href="D_Finance/css/select2.min.css" />
  
</head>

<body >
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Début Menu Gauche -->
            <?php 
                include("D_Finance/MenuGauche.php")
            ?>
        <!-- Fin Menu Gauche -->


        <!-- Content Start -->
        <div class="content bg-dark">
            <!-- Début Menu du Haut -->
                <?php 
                    include("D_Finance/MenuduHaut.php");
                ?>
            <!-- Fin Menu du Haut -->

            <?php
          if(@$_GET['page'])
          {
            if($_GET['page']=="ListePaie_Etudiant")require_once 'D_Finance/Liste_Paiement/Listepaie.php';
            elseif($_GET['page']=="statistique")require_once 'D_Finance/Statistique.php';
            elseif($_GET['page']=="Dash_Board_Caisse")require_once 'D_Finance/Dash_board_Caisse.php';
            elseif($_GET['page']=="Gerer_Encaissement")require_once 'D_Finance/Gerer_Encaissement.php';
            elseif($_GET['page']=="Gerer_Decaissement")require_once 'D_Finance/Gerer_Decaissement.php';
            elseif($_GET['page']=="Gerer_Encaissement_Dec")require_once 'D_Finance/Livre_Caisse_USD.php';
            elseif($_GET['page']=="RapportCaisse")require_once 'D_Finance/Rapport_Caisse.php';
            elseif($_GET['page']=="Rapport_guichet")require_once 'D_Finance/Rapport_guichet.php';
            elseif($_GET['page']=="autorisation")require_once 'D_Finance/Autorisation_De_Depenses.php';
            elseif($_GET['page']=="recteur")require_once 'D_Finance/Page_Autorisations.php';
            elseif($_GET['page']=="Autoriser")require_once 'D_Finance/Page_Autorisations.php';
            elseif($_GET['page']=="AutoValide")require_once 'D_Finance/AutorisationsValides.php';
            elseif($_GET['page']=="Change")require_once 'D_Finance/Change.php';
            elseif($_GET['page']=="Suivi_autorisation")require_once 'D_Finance/Liste_Autorisations_Envoyees.php';
            elseif($_GET['page']=="Afficher")require_once 'D_Finance/Saisie_Bon.php';
            elseif($_GET['page']=="Modif")require_once 'D_Finance/Mise_a_jour_Autoriz.php';
            
           
            

            //ERICK
            elseif($_GET['page']=="Dash_Board")require_once 'D_Comptable_ERP/Dashboard_ERP.php';
            elseif($_GET['page']=="Planification_Budget")require_once 'D_Comptable_ERP/Planification_Budget.php';
            elseif($_GET['page']=="recette_prevue")require_once 'D_Comptable_ERP/recette.php';
            elseif($_GET['page']=="depense_prevue")require_once 'D_Comptable_ERP/depenses.php';
            elseif($_GET['page']=="rubriques")require_once 'D_Comptable_ERP/rubrique.php';
            elseif($_GET['page']=="repartition")require_once 'D_Comptable_ERP/repartition.php';
            elseif($_GET['page']=="imputation")require_once 'D_Comptable_ERP/compte.php';

            else if($_GET['page']=="non_acces")require_once 'D_Generale/Entree_Erreur.php';
        
            
          }
         
      ?>

          
        </div>
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    </div>

    <!-- JavaScript Libraries -->
    <script src="D_Finance/js/jquery-3.6.0.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
     <script src="D_Finance/lib/chart/chart.min.js"></script>
    <script src="D_Finance/lib/easing/easing.min.js"></script>
    <script src="D_Finance/lib/waypoints/waypoints.min.js"></script>
    <script src="D_Finance/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="D_Finance/lib/tempusdominus/js/moment.min.js"></script>
    <script src="D_Finance/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="D_Finance/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="D_Comptable_ERP/js/main.js"></script>
    <script src="D_Finance/js/Select_Total_Paiement.js"></script>
    <script src="D_Finance/js/update_profil_user.js"></script>
    <script src="D_Finance/js/sweetalert2@11.js"></script>

    <!-- JS de Select2 et jQuery (nécessaire) --> 
    <script src="D_Finance/js/select2.min.js"></script>
   <!-- Ton script d'initialisation Select2 -->
<script>
  $(document).ready(function() {
    

    // focus automatique dans le champ de recherche dès ouverture du Select2
    $(document).on('select2:open', function() {
      setTimeout(() => {
        document.querySelector('.select2-container--open .select2-search__field')?.focus();
      }, 0);
    });
  });
</script>


    <!-- ERICK -->
    <script src="D_Comptable_ERP/JavaScript/affichage_block.js"></script>
    <script src="D_Comptable_ERP/JavaScript/pooper.min.js"></script>
    <script src="D_Comptable_ERP/JavaScript/affichage_tableau_budget.js"></script>
    <script src="D_Comptable_ERP/JavaScript/fonction.js"></script>

</body>

</html>