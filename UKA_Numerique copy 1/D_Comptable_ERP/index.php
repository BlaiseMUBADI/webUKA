<!DOCTYPE html>
<html lang="en">

<head>
    <link href="css/select2.min.css" rel="stylesheet" />

    <style type="text/css">
      /* Taille fixe pour les textes dans le dropdown de Select2 */


  .select2-container .select2-selection--single {
    height: calc(2.25rem + 2px); /* Hauteur de .form-select */
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
  }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100%;
                right: 0.75rem;
            }



        #bouton1:hover{
            border-left:1px solid #808080;
            border-top:1px solid #808080;
            border-right:1px solid #808080;
            opacity: 0.4;
           
        }
        #bouton1{
            border: 1px solid white;
        }
        #table1{
            border-radius: 15px;
        }

        .bell {
                    font-size: 20px;
                    color: #ffcc00; /* Couleur de la cloche */
                    animation: shake 0.5s infinite;
                    transform-origin: center center; /* Pour centrer l'animation autour du centre */
                }

        /* Définition de l'animation "shake" */
        @keyframes shake {
            0% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(15deg);
            }
            50% {
                transform: rotate(0deg);
            }
            75% {
                transform: rotate(-15deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }




    
        .table-container {
        width: 100%; /* Ajuster la largeur comme nécessaire */
        max-height: 200px; /* Limiter la hauteur du tableau (par exemple, 5 lignes) */
        overflow-y: auto; /* Ajouter la barre de défilement verticale */
        }

    
    </style>
  

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->

    
    <!-- Icon Font Stylesheet -->
    <link href="../bootstrap/dist/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet"> 

    

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">

        <!-- Début Menu Gauche -->
            <?php 
                include("MenuGauche.php")
            ?>
        <!-- Fin Menu Gauche -->


        <!-- Content Start -->
        <div class="content">
            <!-- Début Menu du Haut -->
                <?php 
                    include("MenuduHaut.php");
                ?>
            <!-- Fin Menu du Haut -->


            <!-- Début Bloc contenu les petits graphiques -->
                <?php 
                    include("Bloc1.php");
                ?>
            <!-- Fin du bloc contenant les petits graphiques -->


            <!-- Sales Chart Start -->
                <?php
                    include("budget.php");
                    include("compte.php");
                    include("depenses.php");
                    include("recette.php");
                    include("resultat.php");
                    include("rubrique.php");
                    include("repartition.php");
                ?>
            <!-- Sales Chart End -->


            <!-- Recent Sales Start -->
                <?php
                    include("Bloc3.php");
                ?>
            <!-- Recent Sales End -->


            <!-- Widgets Start -->
                <?php
                    include("Bloc4.php");
                ?>
            <!-- Widgets End -->


            <!-- Footer Start -->
                 <?php
                    include("PieddePage.php");
                ?>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="JavaScript/jquery-3.4.1.min.js"></script>
    <script src="JavaScript/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    
     <!-- Script JavaScript pour gérer l'enregistrement -->
    <script src="Javascript/jquery-3.6.0.min.js"></script>
    <script src="js/select2.min.js"></script>

     <script>
  
</script>

 
 
</body>


</html>