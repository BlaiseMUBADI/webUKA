<?php session_start(); 

if (!isset($_SESSION['MatriculeAgent'])) {
    header('location: ../index.php');
    // code...
}
?>
<!DOCTYPE html>
<html  >
<head>

    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="code.css"/>  
    <title>encodage de données</title>

    

</head>
<body >
<section data-bs-version="5.1" class="font-family-base text-dark" id="image2-l">

<!-- le début d'encodage ici-->
<br>
<div class="container " id="menu">
    <div class="row ">
        <div class="col-md-12 ">
            <div class="mon_bloc bg-light">
               <button type="buttom" id="option1" class="btn btn-primary btn-effet mt-2" onclick="option1()" > Encodage des données</button>

               <button type="buttom" id="option2" class="btn btn-primary btn-effet mt-2" onclick="option2()"> Orientation Etudiant </button>

               <button type="buttom" id="option3" class="btn btn-primary btn-effet mt-2" onclick="option3()">Confirmation Etudiants et listes</button>

               <button type="buttom" id="insertion" class="btn btn-primary btn-effet mt-2" onclick="option5()">Ajouter Etudiant</button>

               <button type="buttom" id="option" class="btn btn-primary btn-effet mt-2" onclick="Deconnexion_encodage()"> Quitter l'application</button>
                
            </div>
        </div>
    </div>
</div>

<!-- encodage des resultat de la déliberation -->
<div class="container" style="display:none;" id="block1">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc">
                <p class="text-center fw-bolder font-weight-bold text-dark fs-10">Encodage de cotes des etudiants</p>
                <h6>Sélectionnez une option : </h6>
               
                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end " name="option" type="radio" role="switch" id="option21" value="LMD" checked >

                        <label class="form-check-label float-start"  for="case_ems">Système LMD </label> 
                    </div>
                    <div class="form-check form-switch">

                        <input class="form-check-input float-end  " name="option" type="radio" role="switch" id="option11" value="Ancien systeme" >  
                        <label class="form-check-label float-start" for="case_ems" >Ancien système </label>
                    </div>
                </div>
                <br>

                <label>Année Académique : </label>
                    <select id="annee">
                        <?php 
                        include("../../Connexion_BDD/Connexion_1.php");
                        $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                    </select>
                 
                    &nbsp
                <label>Faculté : </label>
                <select size="" id="faculte" style="width:160px;" onchange="updateTextBox1()" >
                    <option value="">Sélectionner</option>
                    <?php
                    
                    $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                    while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                </select>
                &nbsp &nbsp

                <label>Promotion : </label>
                <select id="promotion" style="width:160px;" >
                        <option value="">Sélectionner</option>
                </select>
                   &nbsp &nbsp

                <label>Session : </label>
                <select id="Session" disabled>
                        <option value="">Toutes les sessions</option>
                        <option value="">Première session</option>
                        <option value="">Deuxième session</option>
                </select>

            </div>
        </div>
    </div>
</div> 

<!-- affichage tableau de données venant de la base de données-->

<div class="container" style="display:none;" id="block2">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc">
                <table id="table" border="1"  class="table  table-bordered table-striped table-fixed-header">      
                    <thead>
                        <tr class="table-success">
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prenom</th>
                            <th>Sess1</th>
                            <th>Mention1</th>
                            <th>Sess2</th>
                            <th>Mention2</th>
                            <th id="decision">Décision</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>              
                <!-- la fin de l'encodage-->

<!-- debut de bloc 2 pour l'affectation des etudiants dans les promotion -->

<!-- encodage des resultat de la déliberation -->
<div class="container" style="display:none;" id="block3">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc bg-light">
                <p class="text-center fw-bolder font-weight-bold text-dark fs-10">Orientation des étudiant dans des mentions (Options)</p>
                <h6>Sélectionnez une option : </h6>
               
                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end " name="option1" type="radio" role="switch" id="option22" value="Systeme2" checked >
                        <label class="form-check-label float-start"  for="case_ems">Système LMD </label> 
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end  " name="option1" type="radio" role="switch" id="option12" value="Systeme1" >  
                        <label class="form-check-label float-start" for="case_ems" >Ancien système </label>
                    </div>
                </div>
                <br>

                <label>Année Académique : </label>
                    <select id="annee1">
                        <?php 
                       
                        $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                    </select>
                 
                    &nbsp
                <label>Faculté : </label>
                <select size="" id="faculte1" style="width:160px;" onchange="sect_faculte()" >
                    <option value="">Sélectionner</option>
                    <?php
                    
                    $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                    while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                </select>
                &nbsp &nbsp

                <label>Promotion : </label>
                <select id="promotion1" style="width:160px;" >
                        <option value="">Sélectionner</option>
                </select>
            </div>
        </div>
    </div>
</div> 

<!-- affichage tableau de données venant de la base de données-->

<div class="container" style="display: none;" id="block4">
    <div class="row">
        <div class="col-md-8">
            <div class="mon_bloc">
                <table id="table1" border="1"  class="table  table-bordered table-striped table-fixed-header">      
                    <thead>
                        <tr class="table-success">
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prenom</th>
                          
                            <th>Activer</th>                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- le petit bloc pour afficher les mention ou option des facultés-->
        <div class="col-md-4" >
            <div class="mon_bloc" id="mon_blo_affiche_ration_mention">
                

            </div>
        </div>
    </div>
</div>  



<!--------- le bloc d'activation des etudiants dans une promotion-----------> 


<div class="container" style="display:none;" id="block5">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc bg-light">

                <p class="text-center fw-bolder font-weight-bold text-dark fs-10">Confirmer les etudiants qui sont réellement dans une promotion et Imprimer les listes</p>
                <h6>Sélectionnez une option : </h6>
               
                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end " name="option_affectation" type="radio" role="switch" id="option21_activation" value="LMD" checked >
                        <label class="form-check-label float-start"  for="case_ems">Système LMD </label> 
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end  " name="option_affectation" type="radio" role="switch" id="option11_activation" value="Ancien systeme" >  
                        <label class="form-check-label float-start" for="case_ems" >Ancien système </label>
                    </div>
                </div>
                <br>

                <label>Année Académique : </label>
                    <select id="annee_activation">
                        <?php 
                        $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                    </select>
                 
                    &nbsp
                <label>Faculté : </label>
                <select size="" id="faculte_activation" style="width:160px;" onchange="fac_activation()" >
                    <option value="">Sélectionner</option>
                    <?php
                    
                    $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                    while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                </select>
                &nbsp &nbsp

                <label>Promotion : </label>
                <select id="promotion_activation" style="width:160px;" >
                        <option value="">Sélectionner</option>
                </select>
                &nbsp &nbsp &nbsp
                &nbsp &nbsp &nbsp
               

                <button type="buttom" id="importer" class="btn btn-primary btn-effet "  onchange="imprime_liste()"> Importer les listes</button>
                
                 
                
            </div>
        </div>
    </div>
</div> 

<!-- affichage tableau de données venant de la base de données-->


<div class="container" style="display: none;" id="block6">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
                <table id="table_activation" border="1"  class="table  table-bordered table-striped table-fixed-header">      
                    <thead>
                        <tr class="table-success">
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prenom</th>
                            <th>Activer</th>
                            
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>              
                <!-- la fin de bloc d'activation des etudiants dans une promotion en cours-->         









<div class="container" style="display: none;" id="block7">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">

                <button type="buttom" id="option1" class="btn btn-primary btn-effet" onclick="fonction_imprimer()"> imprimer</button>

                <button type="buttom" id="option1" class="btn btn-primary btn-effet" onclick="fonction_pdf()"> Importer en PDF</button>

                <button type="buttom" id="option1" class="btn btn-primary btn-effet" onclick="fonction_excel()"> Importer en Excel</button>

                

            </div>
        </div>
    </div>
</div>    




<div class="container"  id="entete" style="display: none;">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
               <img src="images/logo.jpg" style="float:left; ">
                <center>

                <h6 class="font-family " style="font-family:Times New Roman; font-size: 22px; ">République Démocratique du Congo</h6>
                <h6>Ministère de l'Enseignement Supérieur et Universitaire</h6>
                <h6>Université Notre dame du Kasayi</h6>
                <h6 style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">SECRETARIAT GENERAL ACADEMIQUE</h6>
                <hr class="border">

                <p style="font-family:Times New Roman;  font-size: 15px;">Liste définitive des etudiants en <span id="affiche_promoton" style="font-weight: bold"></span></p>
                </center>


            </div>
        </div>
    </div>
</div>    
<div class="container" style="display:none;" id="block8">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc " id="liste_imprimer">
                <caption>Année Académique : <span id="annee_academique" style="font-weight: bold;"></span> <span style="float: right;">Effectif : <span id="nbr_etudiant" style="font-weight: bold;"></span></span></caption>   
                <table id="table_liste" border="1"  class="table  table-bordered table-striped table-fixed-header table-sm" >    
                              
                        <tr class="table-success border">
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prenom</th>
                            <th>Sexe</th>
                            
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>    




<!--- insertion des etudiants dans une promotion ------------------------>

<div class="container" style="display:none;" id="insertion1">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc bg-light">

                <p class="text-center fw-bolder font-weight-bold text-dark fs-10">Insertion des étudiants dans une promotion</p>
                <h6>Sélectionnez une option : </h6>
               
                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end " name="option_affectation" type="radio" role="switch" id="option21_insertion" value="LMD" checked >
                        <label class="form-check-label float-start"  for="case_ems">Système LMD </label> 
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input float-end  " name="option_affectation" type="radio" role="switch" id="option11_insertion" value="Ancien systeme" >  
                        <label class="form-check-label float-start" for="case_ems" >Ancien système </label>
                    </div>
                </div>
                <br>

                <label>Année Académique : </label>
                    <select id="annee_insertion" name="annee_academique">
                        <?php 
                        $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                    </select>
                 
                    &nbsp
                <label>Faculté : </label>
                <select size="" name="Faculte_insertion" id="faculte_insertion" style="width:160px;" onchange="fac_insertion()" >
                    <option value="">Sélectionner</option>
                    <?php
                    
                    $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                    while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                </select>
                &nbsp &nbsp

                <label>Promotion : </label>
                <select id="promotion_insertion" name="Promotion_insertion" style="width:160px;" >
                        <option value="">Sélectionner</option>
                </select>
               &nbsp &nbsp
               <button type="buttom" id="liste_inserer" class="btn btn-primary btn-effet" onclick="liste_inserer()"> Voir la liste</button>
                
            </div>
        </div>
    </div>
</div> 

<!-- affichage tableau de données venant de la base de données-->


<div class="container" style="display:none;" id="insertion2">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
                <form >


                    <div class="col-auto mt-3">
                    <input type="text" class="form-control" placeholder="Nom Etudiant ..." id="Nom_etudiant">
                    </div>
                    
                    <div class="col-auto mt-3">
                    <input type="text" class="form-control" placeholder="Postnom Etudiant ..." id="Postnom_etudiant">
                    </div>

                    <div class="col-auto mt-3">
                    <input type="text" class="form-control" placeholder="Prénom Etudiant ..." id="Prenom_etudiant">
                    </div>

                    <div class="col-auto mt-3">
                        <select  class="form-control" placeholder="Sexe ..." id="Sexe_etudiant">
                            <option selected>Faites votre choix ... </option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>


                    <div class="col-auto mt-3">
                    <input type="text" class="form-control" placeholder="Numéro Matricule ..." id="Matricule_insert" >
                    </div>
                    

                    </form>
                    <div class="col-auto mt-3">

                        <button type="buttom" class="btn btn-primary btn-effet" onclick="Envoi_etudiant()"> Enregistrer </button>

                    </div>
                
            </div>
        </div>
    </div>
</div>              

 <!-- la fin de bloc d'activation des etudiants dans une promotion en cours-->         

<div class="container" style="display:none;" id="voir_list">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc " id="voir_liste">
                <caption>Année Académique : <span id="annee_academique" style="font-weight: bold;"></span> <span style="float: right;">Effectif : <span id="nbr_etudiant" style="font-weight: bold;"></span></span></caption>   
                <table id="table_liste" border="1"  class="table  table-bordered table-striped table-fixed-header table-sm" >    
                        <thead>      
                        <tr class="table-success border">
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Matricule</th>
                            <th>Opération</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>    


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
<script type="text/javascript" src="js/affichage_donnee.js"></script> 
<script type="text/javascript" src="js/autre_fonctions_encodage.js"></script> 
<script type="text/javascript" src="js/affectation_etudiant.js"></script> 
<script type="text/javascript" src="jquery-3.2.1.slim.min.js"></script>
<script type="text/javascript" src="popper.min.js"></script>
<script type="text/javascript" src="bootstrap.min.js"></script>
<script type="text/javascript" src="js/jspdf.debug.js"></script>
</html>