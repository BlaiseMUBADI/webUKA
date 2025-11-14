

<section class="home-section" style="height: 100%;">
      <?php
        require_once 'D_Generale/Profil_Guichet.php';
      ?>
  <div class="home-content me-3 ms-3">

  <!-------------------------------------------------------------------------------------------->
  <!------- Bloc pour la selection de la filière, la promotion et l'année academique -----------> 
  <!-------------------------------------------------------------------------------------------->
    <div class="sales-boxes m-0 p-0" style="height:5%;">
      <div class="recent-sales box" style=" width:100%; margin:0px; ">
      



        <form method="POST" enctype="multipart/form-data" action="" style="width:100%;">  
          
          <label style="color: white;">Faculté : .</label>     

          <select  name="filiere" id="filiere_frais_fixer"  
          style="border: 1px solsid green;width:30%">
            <option value=""></option>
              <?php 
                $req="select * from filiere order by Libelle_Filiere";
                $data= $con-> query($req);
                while ($ligne=$data->fetch())
                {
              ?>
                  <option style=" width:100%;"value=<?php echo $ligne['IdFiliere'];?>><?php echo $ligne['Libelle_Filiere']?></option>
                  
                  <?php 
                }
                  ?>
          </select>


          
          <label style="color: white;">Promotion : .</label>
          <select id="promo_frais_fixer" name="Code_promo" style="width: 30%;">
            <option value="" style="border:1px solsid red;"></option>
          </select>


          <label style="color: white;">Année : .</label>
          <select id="Id_an_acad_frais_fixer" style="width: 12%;" >
                
                  <?php 
                    //Requette de sélection Année Académique
                    $req="select * from annee_academique order by Annee_debut desc";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
                    {
                    ?>

                    <option value="<?php echo $ligne['idAnnee_Acad']?>"><?php echo $ligne['Annee_debut'];?>-<?php echo $ligne['Annee_fin'];?></option>

                    <?php 
                      }
                    ?>
          </select>
        </form>
      </div>
    </div>
    <!------------------------------------- FIN BLOC ------------------------------------------------------->
    

    

    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L4AFFICHAGE DES ETUDIANTS ET AFFICHAGE DE DETAILLE A COTE-------------->
    <!----------------------------------------------------------------------------------------------->
    <div class="sales-boxes m-0 p-3 mt-3 border" 
          style="background-color:rgb(39,55,70); height:380px">
      
      <!----------------------------------------------------------------------------------------------->
      <!---------------------------------CE BLOC CONCERNE L'AFFICHAGE DES FRAITS--------------------->
      
      <div class="container table-responsive small p-0 m-0" 
          style="width: 60%; float: left;">

        <table  class="tab1" id="table_frais" style="width:100%; height:100%;">              
          <thead class="sticky-sm-top m-0 fw-bold">
            <tr>
              <th>N°</th>
              <th>Motif Frais</th>
              <th>Montant</th>
              <th>Tranche</th> 
              <th>Devise</th>
              <th>Actions</th>
              <th>Id frais</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
    
      <!-- Ici c'est le bloc pour léffichage en détail et faire un paiement-->
      
      <!-- Ici c'est pour stocker les infos de l'utilisateurs 
          (matricul,nom et autres) est qu'il soit invisible-->
      <form action="">
        <input type="hidden"  id="mat_etudiant" value="">
        <input type="hidden"  id="nom_etudiant_1" value="">
        <input type="hidden"  id="postnom_etudiant" value="">
        <input type="hidden"  id="prenom_etudiant" value="">  
        <input type="hidden"  id="sexe_etudiant" value=""> 
      </form>
      


      <div class="bloc2 shadow-lg bg-body-tertiary rounded border m-0 p-3 m-0" style="color:white; float: right; width: 39%;margin-left:7px;">
        
        <!--************ Bloc operation *********************-------->
        
        <div class="row align-items-start p-0 m-0 mb-2 mt-2 border"> 
          <div class="col fs-7 fw-bolder text-center font-weight-bold p-0">
              Ajouter un nouveau frais
          </div>
        </div>        
        <!-----------------------------------------------------------> 
        
        
        <!-- Ici c'est le div pour le choix de frais -->
        
        <div class="row align-items-start p-0 m-0 mt-2 ">  
          <div class="col fs-7 fw-light text-start   p-0"  >
                        Motif frais :
          </div>
          
          <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
            
            <select id="select_motif_frais" class="form-select form-select-sm m-0 p-0 text-center " aria-label="Small select example"style="background-color:#273746;color:white;">
              <option selected>Selection Frais</option>
              
              <option value="Frais d'inscription">Frais d'inscription</option>
              <option value="Frais de réinscription">Frais réinscription</option>
              <option value="Frais Académiques">Frais Académiques</option>
              <option value="Enrôlement à la Session">Enrôlement à la Session</option>
              <option value="Billet de stage">Billet de stage</option>
              <option value="Autres frais">Autres frais</option>
            </select>
          </div>
        </div>
      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->

        
        <!-- Ici c'est le div pour sais le montant frais-->
      
        <div class="row align-items-start p-0 m-0 mt-2 ">
          <div class="col fs-7 fw-light text-start   p-0"  >
                        Montant frais :
          </div>
        
          <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
            <div class="input-group mb-1 p-0  border rounded"
              style="color:white;">
              <input id="txt_montant_frais" type="number" 
                class="form-control p-0 pe-2 fw-bolder text-end border-0" 
                                  aria-label="Saisir en franc congolais"
                                  style="background-color:#273746;color:white; font-weight:bold;">
              <span class="input-group-text p-0 border-0 font-weight-bold" style="background-color:#273746;color:white;"> . $ . </span>
            </div>
          </div>
        </div>      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->

        <!-- Ici c'est le div pour sais le montant frais-->
      
        <div class="row align-items-start p-0 m-0 mt-2 ">
          <div class="col fs-7 fw-light text-start   p-0"  >
                        Première tranche :
          </div>
        
          <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
            <div class="input-group mb-1 p-0  border rounded" style="color:white;">
              
              <input id="txt_tranche_frais" type="number" class="form-control p-0 pe-2 fw-bolder text-end border-0" 
               aria-label="Saisir en franc congolais" style="background-color:#273746;color:white; font-weight:bold;">
              <span class="input-group-text p-0 border-0 font-weight-bold" style="background-color:#273746;color:white;">. $ .</span>
            </div>
          </div>
        </div>      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->




      
        

        <!-- Ici c'est le div pour cocher la devise-->

        <div class="row align-items-start p-0 m-0 mb-2 mt-2 border"> 
          <div class="col fs-7  text-center font-weight-bold p-0">
              Selectionner la devise
          </div>
        </div>  
        <center> 
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="devise" id="dollar" value="dollar" checked>
              <label class="form-check-label" for="dollar">Dollar</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="devise" id="franc_congolais" value="franc_congolais">
            <label class="form-check-label" for="franc_congolais">Franc congolais</label>
          </div>
        </center> 
        <!-- ++++++++++++++++++++++++++++++++++++++ -->

        <!-- ICi le bloc pour afficher le montant en lettre-->
        <div class="row fs-7 fw-bolder
        align-items-start p-1 m-0 mt-4 mb-2 border" 
        style="color:white;" id="txt_monte_caractere">    
          
        </div>
        <!-- ++++++++++++++++++++++++++++++++++++++ -->
        
        <!-- Ici l'insersion du bouton-->
        <div class="row align-items-start p-0 m-0 mb-2 mt-3"> 
          <div class="col fs-7 fw-bolder text-center font-weight-bold p-0" id="sommeEnrolement">
            <div class="d-grid gap-1 p-0 m-0">
              <button id="btn_valider_paie_guichet" 
              class="btn btn-primary p-0 m-0 font-weight-bold" 
              type="button"
              onclick="Nouveau_Frais()">Valider
              </button>
            </div>
          </div>
        </div>      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->
      </div> 
    </div> 
    <!------------------------------------FIN BLOC AFFICHAGE TABLEAU ET DETAILLE----------------------------------------->
    
  

    



  </div>
</section>
    
       