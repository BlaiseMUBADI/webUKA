

           <!-- encodage des resultat de la déliberation -->
<link rel="stylesheet" type="text/css" href="code_css.css">           
<div class="container" id="inscription_page" style="display:block;  ">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc">
                <p class="text-center fw-bolder font-weight-bold text-dark fs-10"> Inscription à l'UKA</p>
                <h6>Type d'inscription : </h6>
               
                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-start " name="option" type="radio" role="switch" id="option21" value="LMD" checked >

                        <label class="form-check-label float-start"  for="case_ems">Nouveaux étudiants</label> 
                    </div>
                    <div class="form-check form-switch">

                        <input class="form-check-input float-start  " name="option" type="radio" role="switch" id="option11" value="Ancien systeme" >  
                        <label class="form-check-label float-start" for="case_ems" >Spéciale </label>
                    </div>
                </div>
                <br>

                <label>Année Académique : </label>
                    <select id="annee_acad1" disabled>
                        <?php 
                        include("../../Connexion_BDD/Connexion_1.php");
                        $reponse = $con1->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                    </select>
                 
                    &nbsp
                <label>Faculté : </label>
                <select size="" id="faculte_inscrit" style="width:160px;" onchange="list_etudiant_inscrit()" >
                    <option value="">Sélectionner</option>
                    <?php
                    
                    $reponse = $con1->query ('SELECT * FROM filiere order by IdFiliere' );
                    while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                </select>
                &nbsp &nbsp

                <label>Promotion : </label>
                <select id="promotion_inscrit" style="width:160px;"  >
                        <option value="">Sélectionner</option>
                </select>
<hr>
    <div class="container align-items-start" style="display:block;" id="tableau">
      <div class="row">

         
            

     

    

        <div class="col-md-7 border " style11="background-size: cover; background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(255, 255, 255, 0.7)), url(bg_erick.jpg);"> 
          
          <center><p style="margin-top: 5px;  color: white; text-shadow: -1px -1px 0 black,   1px -1px 0 black, -1px 1px 0 black,1px 1px 0 black; font-size: 16px;" class="fw-bold">Liste des étudiants deja inscrits</p></center>
          <hr>
           <div class="form-group">
          <input type="text" style=""  class="form-control" id="zone_rech_inscription" placeholder="Rechercher ici ..." disabled>
        </div>
        <hr>
        <span style="float:left;"><button id="openModalBtn" class="btn btn-primary btn-effet mt-1" onclick="btn_liste_inscrit()">Liste des étudiants</button>
          <button id="openModalBtn" class="btn btn-primary btn-effet mt-1" onclick="btn_liste_inscrit_test()">Liste de ceux qui passent le test</button></span>
          <br>
          <p class="float-end" style="color: white; text-shadow: -1px -1px 0 black,   1px -1px 0 black, -1px 1px 0 black,1px 1px 0 black; font-size: 14px;">Effectif : <span id="effectifs_etudiant"></span> étudiant(s)</p>

          <div class="mt-4 mb-2" style="overflow: auto; width: 100%; height: 490px;" >
            
            <center>
               <table id="table_inscrit" border="1"  class="table  table-bordered table-striped table-fixed-header mt-2">      
                    <thead>
                        <tr class="table-success">
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prenom</th>
                            <th>Sexe</th>
                            
                        </tr>
                    </thead>
                </table>
                </center>
          </div>
          
          
            </div>


             <div class="col-md-5 border"> 
        <div class="form-step active">
            <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_matricule">Matricule de l'Etudiant : </label>
            <input  type="text" style="text-align:center;" class="form-control" id="zone_matricule" placeholder="Matricule" disabled>
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_nom">Nom de l'Etudiant : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_nom" placeholder="Nom Etudiant">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_postnom">Post-nom de l'Etudiant : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_postnom" placeholder="Post-nom">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Prénom de l'Etudiant : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_prenom" placeholder="Prénom">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_sexe">Sexe : </label>
            <select class="form-control" style="text-align: center;" id="zone_sexe" >
          </select>
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Lieu de naissance : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_lieu_naiss" placeholder="Lieu de  naissance">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_date_naiss">date de naissance : </label>
            <input type="date" style="text-align:center;" class="form-control" id="zone_date_naiss" placeholder="Date de  naissance">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_etat_civil">Etat civil: </label>
            <select class="form-control" style="text-align: center;" id="zone_etat_civil" >
              <option value="">Faites votre choix</option>
              <option value="Célibataire">Célibataire</option>
              <option value="Marié(e)">Marié(e)</option>
          </select>
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_nationalite">Nationalité : </label>
            <select class="form-control" style="text-align: center;" id="zone_nationalite" >
              <option value="">Faites votre choix</option>
              <option value="Congolais">Congolais</option>
              <option value="Autre Nationalité">Autre...</option>
          </select>
          </div>
          <center>
          <button id="" class="btn btn-primary btn-effet mt-1 prev disabled" type="button">Précédent</button>
          <button id="" class="btn btn-primary btn-effet mt-1 next" type="button">Suivant</button>
          <button class="btn btn-primary btn-effet mt-1 disabled" type="submit">Terminer</button>
          </center>
        <br>

      </div>
      
    
      <div class="form-step">
        <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Adresse actuelle : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_adresse_actuelle" placeholder="Adresse actuelle">
          </div>

         <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_nom">Religion : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_religion" placeholder="Religion">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Paroisse : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_paroisse" placeholder="Nom de la Paroisse">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Diocese : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_diocese" placeholder="Diocese">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_postnom">Nom du Père : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_nom_pere" placeholder="Nom du Père">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Profession du Père: </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_profession_pere" placeholder="Profession du Père">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Nom de la Mère : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_nom_mere" placeholder="Nom de la Mère">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Profession de la Mère : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_profession_mere" placeholder="Profession de la Mère">
          </div>
        
          
        <center>
        <button id="" class="btn btn-primary btn-effet mt-1 prev" type="button">Précédent</button>
        <button id="" class="btn btn-primary btn-effet mt-1 next" type="button">Suivant</button>
        <button class="btn btn-primary btn-effet mt-1 disabled"  type="submit">Terminer</button>
        </center>
        <br>
        </div>


        <div class="form-step">

        <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Province d'origine : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_province_origine" placeholder="Province d'origine">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Territoire d'origine : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_territoire_origine" placeholder="Province d'origine">
          </div>
          <hr>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Personne à contacter en cas d'urgence (Nom & Numéro): </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_contact_responsable" placeholder="nom / numéro">
          </div>
          
          <hr>
          <p>contact étudiant</p>
         <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Numéro Airtel: </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_num_airtel" placeholder="Numéro Airtel">
          </div>

         <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_nom">Numéro Vodacom : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_num_vodacom" placeholder="Numéro Vodacom">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Numéro Orange  : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_num_orange" placeholder="Numéro Orange">
          </div>
          <hr>
          <p>Information école Primaire</p>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Année scolaire  : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_anne_scolaire_primaire" placeholder="Annee scolaire">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Nom etablissement : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_etablissement_primaire" placeholder="Nom etablissement">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Pourcentage certificat : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_pourc_certificat_primaire" placeholder="%">
          </div>
          
        <center>
        <button id="" class="btn btn-primary btn-effet mt-1 prev" type="button" >Précédent</button>
        <button id="" class="btn btn-primary btn-effet mt-1 next " type="button">Suivant</button>
        <button class="btn btn-primary btn-effet mt-1 disabled" type="submit">Terminer</button>
        </center>
        <br>
        </div>

        <div class="form-step">
            <hr>
            <p style="font-style: ;">Informations Ecole secodaire</p>
        <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Année scolaire : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_anne_scolaire" placeholder="Année scolaire">
          </div>

         <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_nom">Province educationnelle : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_province_educationnelle" placeholder="Province educationnelle">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Ecole de provenance : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_ecole_provenance" placeholder="Ecole de provenance">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_lieu_naiss">Section : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_section" placeholder="Section">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_postnom">Option : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_option" placeholder="Option">
          </div>
           <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Pourcentage à l'examen d'Etat : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_pourcentage_diplome" placeholder="%">
          </div>
           <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Numéro diplôme : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_num_dip" placeholder="Numéro diplôme">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Lieu de delivrance: </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_lieu_delivrance" placeholder="Lieu de delivrance">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_prenom">Date de delivrance : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_date_delivrance" placeholder="Date de delivrance">
          </div>
         
        
          
        <center>
        <button id="" class="btn btn-primary btn-effet mt-1 prev" type="button">Précédent</button>
        <button id="" class="btn btn-primary btn-effet mt-1 next disabled" type="button">Suivant</button>
        <button class="btn btn-primary btn-effet mt-1 terminer" id="terminer" onclick="terminer_inscription()" >Terminer</button>
        </center>
          <br>
        </div>

        </div>
        </div>
    </div>
</div> </div> </div> </div> 




 <div class="row"   id="formulaire_inscription" style="display:none; margin-left:15px; margin-right:20px ;">
        <div class="col-md-12 ">
              <img src="../LOGO.png" id="logo" style="position: relative; top: 20px; float: inline-start;">
            <center>
            <p style="font-size:12px;">
            <span class="fw-bold" style="font-family: tahoma;">République Démocratique du congo</span><br>
            <span style="font-family: tahoma;">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE</span><br>
            <span style="font-family: georgia;">UNIVERSITÉ NOTRE-DAME DU KASAYI (U.KA.)</span><br>
            <span > <b> <i>Secrétariat Général Académique</i> </b></span><br>
            <span>B.P. 70 KANANGA</span><br>
              <span> E-mail : secacad@.uka.ac.cd / Tél : (+243) 81 500 36 73 </span>
            </p>
            </center>
            <hr style="border: 1px solid black;">
            <p style="text-align: right;"> N° d'enregistrement : <span id="num_enregistrememt" class="fw-bold">...</span> </p>

            <center><p class="fw-bold" style="font-family:Brush Scrip MT;">FORMULAIRE D'INSCRIPTION À L'UKA </p>
            </center>
            <p>I. Identité</p>
            <table border="0" style="font-size:11px;">
                <tr>
                    <td>Nom, Postnom et Prénom: <span id="nom" class="fw-bold"></span>&nbsp &nbsp &nbsp &nbsp &nbsp</td>
                    <td>sexe: &nbsp&nbsp&nbsp<span id="sexe" class="fw-bold"></span>.  &nbsp &nbsp &nbsp &nbsp </td>
                    <td>Nom de la Mère: <span id="nom_mere" class="fw-bold"></span></td>
                </tr>
                <tr>
                    <td colspan="2">Lieu et date de naissance: <span id="lieu_date_naiss" class="fw-bold"></span> </td>
                    <td>Profession : <span id="prof_mere" class="fw-bold"></span></td>
                </tr>
                <tr>
                    <td colspan="2">Religion pratiquée: <span id="religion" class="fw-bold"></span></td>
                    <td>Frère et Soeurs: </td>
                </tr>

                <tr>
                    <td colspan="2">Nationalité: <span id="nationalite" class="fw-bold"></span></td>
                    <td>Adresse actuelle: <span id="adresse_actuelle" class="fw-bold"></span></td>
                </tr>
                <tr>
                    <td>Etat civil : <span id="etaciv" class="fw-bold"></span></td>
                    <td></td>
                    <td>Paroisse: <span id="paroisse" class="fw-bold"></span></td>
                </tr>
                <tr>
                    <td colspan="2">Nom du Père: <span id="nom_pere" class="fw-bold"></span></td>
                    <td>diocèse: <span id="diocese" class="fw-bold"></span></td>
                </tr>
                <tr>
                    <td colspan="3"> Profession: <span id="prof_pere" class="fw-bold"></span></td>
                </tr>
                <tr>
                    <td colspan="3">Personne à contacter en cas d'urgence (Nom & numéro) : <span id="personne_contact" class="fw-bold"></span> </td>
                    
                </tr>
            </table>
           
            <p style="font-size:12px;">II. Etudes Faites <br> 1. Etudes primaires</p>
            
             <center>
                <table id="tableau_curcus_document"  class="table table-bordered border-primary" border="0" style="margin-right:40px; font-size: 11px;">      
                    <thead style="size: 10px;">
                        <tr class="table-success">
                            
                            <th>Année</th>
                            <th>Nom de l'établissement</th>
                            <th>certificat avec</th>
                        </tr>
                    </thead>
                    <tr>
                      <td id="anne_primaire"></td>
                      <td id="nom_ecole_primaire"></td>
                      <td id="Pourcentage"></td>
                    </tr>
                </table>
                </center>

                <p style="font-size:12px;">2. Etudes secondaires</p>
             <center>
                <table id="tableau_curcus_document"  class="table table-bordered border-primary" border="0" style="margin-right:40px; font-size:11px;">      
                    <thead style="size: 10px;">
                        <tr class="table-success">
                            
                            <th>Année</th>
                            <th>Nom de l'établissement</th>
                            <th>5ème avec :<br>
                            6ème avec :<br>
                        Diplôme d'Etat <br>
                            N°: <span class="fw-bold" id="num_dip"></span><br>
                            Avec : <span class="fw-bold" id="pourc_dip"></span></th>
                        </tr>
                    </thead>
                    <tr>
                      <td id="anne_sec"></td>
                      <td id="nom_ecole_sec"></td>
                      <td id="Pourcentage_sec"></td>
                    </tr>
                </table>
                </center>


                 <p style="font-size:12px;">3. Activité professionnelles : <span></span></p>
                 <p style="font-size:12px;">III. Inscription sollicitée <br>
                  1er Choix : <span id="premier_choix" class="fw-bold">  </span><br>
                  2ème Choix : <span id="deuxieme_choix"> </span><br>
                  <!--3ème Choix : <span> </span><br>-->
                 </p>

              
                <p > &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Je jure sur mon honneur que les renseignements ci-haut fournis sont exacts</p>

            
                <p style="text-align:right;">Fait à Kananga, le <span id="date_jour"></span>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<br>
                  Signature &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p> 
                  <hr style="border: 1px solid black;">
                  <p>Partie réservée au service des inscriptions de l'Université Notre-Dame du Kasayi (U.KA.)<br>
                    <span>Avis favorable motivé : </span><br>
                    <span>Avis défavorable motivé : </span>
                  </p>
        </div></div>

<!--
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" style=" text-align: right;"> &times; </span>
   
    
    <span id="titre_cursus" style="text-align: justify; font-size: 16px; " class="fw-bold"></span>
    <hr>

   <table id="tableau_curcus_modif" border="1"  class="table  table-bordered table-striped table-fixed-header">      
      <thead>
          <tr class="table-success">
              <th>Année d'Etude</th>
              <th>Promotion</th>
              <th>Sess1 (%)</th>
              <th>Mention1</th>
              <th>Sess2 (%) </th>
              <th>Mention2</th>
              <th id="decision">Décision</th>
          </tr>
      </thead>
    </table>
    <hr>
     <button id="valider" class="btn btn-primary btn-effet mt-1">valider</button>

  </div>
</div>
<script type="text/javascript" src="js/jsAttestation.js"></script> 
<script src="js/js.js"></script>-->
<script src="js/autre_fonctions_encodage.js"></script>
 <script>
        // Créer un nouvel objet Date
        const today = new Date();

        // Options pour le formatage de la date
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        
        // Formater la date
        const formattedDate = today.toLocaleDateString('fr-FR', options);
        
        // Afficher la date dans l'élément HTML
        document.getElementById('date_jour').innerText = ` ${formattedDate}.`;
    </script>



<?php //liste des etudiant inscrit dans une faculté ?>

<div class="fw-bold"  id="liste_inscrit_titre" style="display: none; width: 100%;">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
               <img src="../LOGO.png" style="float:left; ">
                <center>

                <h6 class="font-family " style="font-family:Times New Roman; font-size: 22px; ">République Démocratique du Congo</h6>
                <h6>Ministère de l'Enseignement Supérieur et Universitaire</h6>
                <h6>Université Notre dame du Kasayi</h6>
                <h6 style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">SECRETARIAT GENERAL ACADEMIQUE</h6>
                <hr class="border">

                <p style="font-family:Times New Roman;  font-size: 15px;"><span id="nom_liste"></span> <span id="affiche_promoton" style="font-weight: bold"></span></p>
                </center>


            </div>
        </div>
    </div>
</div> 

<div class="containe" style="display:none;" id="liste_inscrit_tableau">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc " id="liste_inscrit">
                <caption>Année Académique : <span id="annee_academique" style="font-weight: bold;"></span>
                  <span style="float: right;">Effectif : <span id="nbr_etudiant" style="font-weight: bold;"></span>
                  </span>
                </caption>   
                
                <table id="table_liste_inscrit" border="1"  class="table  table-bordered table-striped table-fixed-header table-sm" style="font-size: 0.85rem;">    
                       <thead>       
                        <tr class="table-success border">
                            <th>N°</th>
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
