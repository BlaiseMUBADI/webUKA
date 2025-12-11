
<?php 
// appel de l'API qui appelle de code QR
?>
<div class="container " id="attesttion_page" style="display:block;">
    <div class="row">
        <div class="col-md-12 ">
            <div class="mon_bloc bg-light">
           <center> <p class="fw-bold">Attestation de Fréquentation</p> </center>
          
    <div class="row ">
      <div class="col-md-4">
        <div class="form-group">
          <label class="form-control-label" for="radio1">
            Selectionner une Faculté
          </label>
          <select class="form-control" style="text-align: center;" id="faculte" onchange="affiche_recherche()">
            <option> --- Faites le choix ---</option>
             <?php
                    
                    $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                    while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
          </select>

        </div>
        
      </div>
      <div class="col-md-6" style="display: block;" id="bloc_rech_matricule">
        <div class="form-check" style="float: left;">
          <input class="form-check-input" type="radio" name="rb_rech" id="radio1" value="matricule" checked hidden>
         
        </div>
        <div class="form-group">
          <input type="text" style="" class="form-control" id="zone_rech1" placeholder="Faites votre rechercher ici ..." disabled>
        </div>
        
      </div>
      
    </div>
    <hr>
    <div class="container align-items-start" style="display:block;" id="tableau">
      <div class="row">
      <div class="col-md-7 border"> 
       
        <table id="table_affichage_etudiant_attestation" border="1"  class="table  table-bordered table-striped table-fixed-header mt-2">      
                    <thead>
                        <tr class="table-success">
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
      
        <div class="col-md-5 border "> 
          
          <p>Les informations de l'étudiant</p>
          <hr>
          <p style="font-size:13px;"><u>Cursus académique : </u></p>

          <div class="mt-4 mb-2" >
            
            <center><table id="tableau_curcus"  class="table  table-striped table-fixed-header mt-2 col-xs-1" style="font-size: 12px;">      
                    <thead style="size: 10px; ">
                        <tr class="table-success">
                            
                            <th colspan="2">Année Académique</th>
                            <th>Année d'étude</th>
                            <th>Mention</th>
                            <th>Session</th>
                        </tr>
                    </thead>
                </table>
                </center>
          </div>
          <button id="openModalBtn" class="btn btn-primary btn-effet mt-1">Analyser le cursus</button>
          <hr>

          <p style="font-size:13px;"><u>Identité Etudiant : </u></p>
         
          
          <div class="form-group" >
            <label class="form-text-label" for="zone_Matricule">Numéro Matricule : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_Matricule" placeholder="Matricule">
          </div>
          <div class="form-group mt-2" >
            <label class="form-text-label" for="zone_nom">Nom de l'Etudiant : </label>
            <input type="text" style="text-align:center;" class="form-control" id="zone_nom" placeholder="Nom Etudiation">
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

          
         
        <hr>
          <center>  
          <div class="mt-2 mb-2" >
            <button type="buttom" id="option" class="btn btn-primary btn-effet mt-1" onclick="Imprimer_attestation()"> Imprimer l'Attestation de Fréquentation</button>
          </div>
          </center>
        </div>
      </div>
    </div>
  </div>
            </div>
        </div>
    </div>
<?php include("../autorite.php");
 ?>

 <div class="row"   id="attestation_document" style="display:none; margin-left:15px; margin-right:20px ;">
        <div class="col-md-12 ">
              <img src="../LOGO.png" id="logo" style="position: fixed; top: 45px;">
            <center>
            <p>
            <span class="fw-bold" style="font-family: tahoma;">République Démocratique du Congo</span><br>
            <span style="font-family: tahoma;">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE</span><br>
            <span style="font-family: georgia;">UNIVERSITÉ NOTRE-DAME DU KASAYI (U.KA.)</span><br>
            <span > <b> <i>Secrétariat Général Académique</i> </b></span><br>
            <span>B.P. 70 KANANGA</span><br>
              <span>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp E-mail : secacad@.uka.ac.cd</span><br>
              <span>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Tél : (+243) 81 500 36 73 </span>
            </p>
            </center>
            <hr style="border: 1px solid black;">
            <center><p class="fw-bold" style="font-family:Brush Scrip MT;">ATTESTATION DE FREQUENTATION </p></center>

            <p style="text-align: justify; line-height: 1.5;" >&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Le soussigné, <?php echo $academique; ?>, Secrétaire Général Académique de l'Université Notre-Dame du du Kasayi (U.KA.), atteste par la présente que <span id="nomme"></span> <span class="fw-bold" id="nom_etudiant"></span>, Matricule n° <span class="fw-bold" id="matricule_etudiant"></span>, <span id="ne"></span> à <span id="lieu"></span> le <span id="date_naiss"></span> était <span id="etudiant"></span> régulièrement <span id="inscrit"></span> à la Faculté <span id="fac" class=""></span><span id="option_fac"> </span>  de notre Université de <span id="anne_debut_ac"></span> selon la programmation suivante :</p>

             <center><table id="tableau_curcus_document"  class="table mt-2" border="0" style="margin-right:40px;">      
                    <thead style="size: 10px;">
                        <tr class="table-success">
                            
                            <th colspan="2">Année Académique</th>
                            <th>Année d'étude</th>
                            <th>Mention</th>
                            <th>Session</th>
                        </tr>
                    </thead>
                </table>
                </center>
                <br>
                <p> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp La présente lui est délivrée pour servir et faire valoir ce que de droit</p>

               
                <div style=" position:fixed; bottom: 5px; " id="qrcode"><img src="API/CodeQR.png" style="width: 70px; height: 70px; display:none;"></div>
                <p style="text-align:right;">Fait à Kananga, le <span id="date_jour"></span>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<br>
                  Le Secrétaire Général Académique &nbsp &nbsp &nbsp &nbsp <br><br><br>
                  <?php echo $academique; ?> &nbsp &nbsp &nbsp</p> <?php ?>
        </div></div>



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
<script src="js/js.js"></script>

