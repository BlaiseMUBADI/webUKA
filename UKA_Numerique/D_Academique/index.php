<?php session_start(); 

if (!isset($_SESSION['MatriculeAgent'])) {
    header('location: ../index.php');
    // code...
}
include("../../Connexion_BDD/Connexion_1.php");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title id="title">Université Notre-Dame du Kasayi</title>
        <link rel="shortcut icon" href="logo.ico" type="image/x-icon">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        
        <meta content="Login Form Template" name="keywords">
        <meta content="Login Form Template" name="description">
    
        <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >
        <link rel="stylesheet" type="text/css" href="code.css"/>  
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="test.css">
    </head>
    <body >
<section data-bs-version="5.1" class="font-family-base text-dark" id="image2-l">

   <div class="container mt-2" id="menu">
    <div class="row ">
        <div class="col-md-12 ">
            <div class="mon_bloc bg-light">
                 <center>
               <button type="buttom" id="insertion" class="btn btn-primary btn-effet mt-1" onclick="option6()"> Inscriptions</button>
               <button type="buttom" id="option3" class="btn btn-primary btn-effet mt-1" onclick="option3()"> Attribution matricule</button>
               <button type="buttom" id="insertion" class="btn btn-primary btn-effet mt-1" onclick="option4()"> Changement Filière</button>
               <button type="buttom" id="option2" class="btn btn-primary btn-effet mt-1" onclick="option2()"> Fichde de Scolarité</button>
               <button type="buttom" id="insertion" class="btn btn-primary btn-effet mt-1" onclick="option5()"> Encodage Resultat</button>
               <button type="buttom" id="option1" class="btn btn-primary btn-effet mt-1" onclick="option1()"> Attestation</button>
               <button type="buttom" id="option" class="btn btn-primary btn-effet mt-1" onclick="Deconnexion_encodage()"> Fermer</button>
                </center>
            </div>
        </div>
    </div>
</div>



                 <?php 
            //include("../../../Conexion_BDD/Connexion_1.php" C:\wamp64\www\webUKA\UKA_Numerique);
            
        if (isset($_GET['page']))
         // if(@$_GET['page'])
          {
            if($_GET['page']=="Attestation"){
            include("Attestation.php");
           
            }
            else if($_GET['page']=="Fiche_Scolarite")require_once '../D_Administration/ListeEtudiant_Fiche.php';
            else if($_GET['page']=="Palmares")require_once 'Palmares.php';            
            else if($_GET['page']=="Changement_Filiere")require_once 'Changement_Filiere.php';
            else if($_GET['page']=="encodage")require_once '../D_Encodage/index.php';
            else if($_GET['page']=="Inscription")require_once 'inscription.php';
          }
        ?>
  

              <img src="../LOGO.png" id="logo1" style="position: fixed; bottom: 15px; opacity:0.4; right:10px; width:5%; display:block;">

<!-- le pied de la page index-->
<div class="container " id="pied" style="display:block;">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc bg-light">
               <center> Cellule Informatique UKA. copy rigth 2024 <span style="float:right;" id="numero"></span></center>
            </div>
        </div>
    </div>
</div>
</section>
    </body>
     <script type="text/javascript" src="../bootstrap/dist/js/bootstrap.bundle.js"></script>  
    <script type="text/javascript" src="js/menu.js"></script> 
    <script type="text/javascript" src="test.js"></script> 
    <script type="text/javascript" src="js/js_inscription.js"></script>
   
     <script type="text/javascript" src=""></script> 
    
  
</html>
