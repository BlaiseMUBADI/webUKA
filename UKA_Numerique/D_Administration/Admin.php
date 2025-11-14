<?php 
session_start();
?>
<!doctype html>
<html xml:lang="FR">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">   
    <title>Créer un Compte</title> 
    <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      
  </head>
  <body>
    <?php 
        include("../../Connexion_BDD/Connexion_1.php");  
    
         if(@$_GET['page'])
          {

            if($_GET['page']=="CreerCompte")require_once 'CreerCompteAgent.php';
           elseif($_GET['page']=="CompteExiste")require_once 'MiseAJourCompteAgent.php';
           elseif($_GET['page']=="Mat_agent")require_once 'ModifierCompteAgent.php';
            
            

            
          }
     ?>

  </body>
  </html>