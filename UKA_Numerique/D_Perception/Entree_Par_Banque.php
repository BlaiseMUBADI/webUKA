

<section class="home-section" style="height: auto;">
      <?php
        require_once 'Profil_Guichet.php';
      ?>
  <div class="home-content me-3 ms-3">
    <div class="sales-boxes m-0 p-0" style="height:5%;">
      <div class="recent-sales box" style=" width:100%; margin:0px; ">
      

      <form class="m-0 p-0" method="POST" enctype="multipart/form-data" action="" style="width:100%;">  
          
          <label style="color: white;">Faculté:</label>     

          <select  name="filiere" id="filiere"   class="text-center"    style="border: 1px solid green;width:30%;">
            <option value=""></option>
              <?php 
                $req="select * from filiere order by LENGTH(Libelle_Filiere) asc ";
                $data= $con-> query($req);
                while ($ligne=$data->fetch())
                {
              ?>
                  <option style=" width:100%;"value=<?php echo $ligne['IdFiliere'];?>><?php echo $ligne['Libelle_Filiere']?></option>
                  
                  <?php 
                }
                  ?>
          </select>


          
          <label style="color: white;">Promotion:</label>
          <select id="promo"  class="text-center" name="Code_promo" style="width: 30%;">
            <option value="" style="border:1px solsid red;"></option>
          </select>


          <label style="color: white;">Année:</label>
          <select id="Id_an_acad" class="text-center"  style="width: 12%;" >
                
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
    
    



    
    <!------------------------------------------------------------------------------------->
    <!----------------------- BLOC POUR LES MODALITES   ----------------------------------->

    <div class="overview-boxes m-0 p-0 mt-3" style="margin:0px;">
      <div class="box" style="height: 100%;">
        <div class="right-side">
          <div class="box-topic">FA</div>
          <div class="number" id="zone_affiche_tot_FA"> 

          </div>
          <span class="number" id="devise_fa">Fc</span>
          <div class="indicator">
            <i class="bx bx-down-arrow-alt down"></i>    
            <span class="text">Total à payer</span>
          </div>
        </div>
      </div>
      
      <div class="box" style="height: 100%;">
        <div class="right-side">
          <div class="box-topic">Enrôlement</div>
          <div class="number" id="zone_affiche_tot_enrolement">
            
          </div>
          <span class="number" id="devise_eronl">Fc</span>
          <div class="indicator">
            <i class="bx bx-down-arrow-alt down"></i>
            <span class="text">A payer par session</span>
          </div>
        </div>
      </div>

      <div class="box"style="height: 100%;">
        <div class="right-side">
          <div class="box-topic">Tranche</div>
          <div class="number"id="zone_affiche_tranche">
            
          </div>
          <span class="number" id="devise_tranche">Fc</span>
          <div class="indicator">
            <i class="bx bx-down-arrow-alt down"></i>
              <span class="text">Avant la grande session</span>
          </div>
        </div>
      </div>
      
      <div class="box" style="height: 100%;">
        <div class="right-side">
          <div class="box-topic">Autres frais</div>
          
          <div class="number"></div>
          <span class="number" id="devise_fa">Fc</span>
          <div class="indicator">
            <i class="bx bx-down-arrow-alt down"></i>
            <span class="text">Par document</span>
          </div>
        </div>
      </div>
    </div>
    <!-----------------------------  FIN BLOC MODALITE ----------------------------------->
    <!------------------------------------------------------------------------------------->


    <!----------------------------- ------------------- ----------------------------------->
    <!-------------------------------- ICI LE BLOC POUR RECHERCHE ET TAUX ----------------->
    <div class="sales-boxes m-0 p-0 mt-3 border">
      <div class="recent-sales box" style=" width:50%; margin:0px; ">

          <div class="input-group p-2 border rounded"
            style="color:white;">
            
            <span class="input-group-text p-0 border-0 font-weight-bold" 
                style="background-color:#273746;color:white;">Recherche étudiant ... </span>

            <input id="txt_recherch_etudiant" type="text" 
            class="form-control p-0 ps-2 fw-bolder text-s border-0" 
                                aria-label="Saisir en franc congolais"
                                style="background-color:#273746;color:white; font-weight:bold;">
            
          </div>
      </div>

      <div class="recent-sales box ms-2" style=" width:50%; margin:0px; ">

          <div class="input-group p-2 border rounded"
            style="color:white;">
            
            <span class="input-group-text p-0 border-0 font-weight-bold" 
                style="background-color:#273746;color:white;">Taux du jour ... :  </span>

            <span class="input-group-text p-0 border-0 font-weight-bold ps-5 " 
                style="background-color:#273746;color:white;"
                id="txt_tau_jours" >  </span>
          </div>
      </div>
    </div>
    <!-----------------------------  FIN BLOC RECHERCHE ----------------------------------->
    <!------------------------------------------------------------------------------------->










    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L4AFFICHAGE DES ETUDIANTS ET AFFICHAGE DE DETAILLE A COTE-------------->
    <!----------------------------------------------------------------------------------------------->
    <div class="sales-boxes m-0 p-3 mt-3 border" 
          style="background-color:rgb(39,55,70);">


      <div class="container table-responsive small p-0 m-0" 
          style="width: 60%; float: left; height: 650px; overflow-y:auto; overflow-x:auto;">

        <table  class="tab1" id="table_paiement" style="width:100%; height:100%;">              
          <thead class="sticky-sm-top m-0 fw-bold">
            <tr>
              <th>Matricule</th>
              <th>Nom</th>
              <th>Postnom</th>
              <th>Prenom</th>
              <th>Sexe</th>
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
      
      
      <div class="bloc2 shadow-lg bg-body-tertiary rounded border m-0 p-3 m-0" 
                  style="color:white; float: right; width: 39%; margin-left:7px">
          <center><h5  id="nom_etudiant"class="text border"sytle="width:100%;"></h5> </center> 
        
          <!-- Bloc pour afichage en detail donc le nom et autre -->
        
          <div class="container margin-0 p-0 ">
            <div class="row align-items-start p-0 m-0">
              <div class="col fs-7 fw-light p-0 m-0 text-end" >
                    Frais Académique payé : 
              </div>
            
              <div class="col-4 fs-7 fw-bolder text-end 
                        font-weight-bold p-0" id="sommeFA">
              </div>
            </div>
          
            <div class="row align-items-start p-0 m-0">
              <div class="col fs-7 fw-light text-end   p-0"  >
                    Frais d'Enrôlements payé M-S : 
              </div>
            
              <div class="col-4 fs-7 fw-bolder text-end font-weight-bold p-0" 
                id="sommeEnrolement_mi_session">
                        
              </div>
            </div>
          
            <div class="row align-items-start p-0 m-0">  
              <div class="col fs-7 fw-light text-end   p-0"  >
                          Frais d'Enrôlements payé  S : 
              </div>
            
              <div class="col-4 fs-7 fw-bolder text-end font-weight-bold p-0" 
              id="sommeEnrolement_Session">
            </div>
            </div>
          
            <div class="row align-items-start p-0 m-0">
              <div class="col fs-7 fw-light text-end   p-0"  >
                          Frais d'Enrôlements payé  2S : 
              </div>
            
              <div class="col-4 fs-7 fw-bolder text-end font-weight-bold p-0" 
                id="sommeEnrolement_Deuxieme_session">
              </div>
            </div>
            <div class="row align-items-start p-0 m-0">
            <div class="col fs-7 fw-light text-end   p-0"  >
                          Enrôlements Rattrapage Sem 1 : 
            </div>
            
            <div class="col-4 fs-7 fw-bolder text-end font-weight-bold p-0" 
                id="sommeRattrapage_Sem_1">
            </div>
          </div>
          <div class="row align-items-start p-0 m-0">
            <div class="col fs-7 fw-light text-end   p-0"  >
                          Enrôlements Validation crédits : 
            </div>
            
            <div class="col-4 fs-7 fw-bolder text-end font-weight-bold p-0" 
                id="sommeValidation_Credit">
            </div>
          </div>
          </div>
        
          <!--***************** FIN Bloc affichage détail***************-->

         <!--************ BLOC AFFICHAGE RESTE A PAYE  *********************  -->
        <div class="row align-items-start p-0 m-0 mb-2 mt-2 border"> 
          <!-- Ici c'est affichage effectuer un paiement-->
          
          <div class="col text-center p-0" 
              id="Reste_payer" style="font-size: 0.8em;">
          </div>
        </div>

        <!--***************** FIN Bloc affichage détail***************-->









          <!--************ Bloc operation *********************  -->
        
          <div class="row align-items-start p-0 m-0 mb-2 mt-2 border"> 
            <!-- Ici c'est affichage effectuer un paiement-->
          
            <div class="col fs-7 fw-bolder text-center font-weight-bold p-0" 
              id="sommeEnrolement">
              Effectué un paiement
            </div>
          </div>
        
          <!--/div--> 

           <!-- Ici c'est le div pour cocher la devise-->

        <div class="row align-items-start p-0 m-0 mb-2 mt-2" style="border-top:1px solid white; border-left:1px solid white;  border-right:1px solid white;">
          <div class="col fs-7  text-center font-weight-bold p-0">
              Selectionner la devise
          </div>
        </div>  
        <center style="border-bottom:1px solid white; border-left:1px solid white;  border-right:1px solid white;"> 
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="devise_payer" id="dollar_payer" value="dollar" checked>
              <label class="form-check-label" for="dollar">Dollar</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="devise_payer" id="franc_congolais_payer" value="franc_congolais">
            <label class="form-check-label" for="franc_congolais">Franc congolais</label>
          </div>
        </center> 
        <!-- ++++++++++++++++++++++++++++++++++++++ -->


          <!-- Ici c'est le div pour le choix de banque-->

          <div class="row align-items-start p-0 m-0 mt-2 ">
            <div class="col fs-7 fw-light text-start   p-0"  >
                        Chosir la banque :
            </div>
          
            <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
            
              <select id="Select_banque" class="form-select form-select-sm m-0 p-0 text-center "
                          aria-label="Small select example" 
                            style="background-color:#273746;color:white;">
                            
                  <option selected>Selection banque</option>
                
                    <?php 
                
                      $req="select idLieu_paiement,Libelle_lieu 
                      from lieu_paiement 
                      where Libelle_lieu!='Guichet UKA' order by Libelle_lieu";
                      $data= $con-> query($req);
                      while ($ligne=$data->fetch())
                      {
                    ?>
                  <option style=" width:100%;"value=<?php echo $ligne['idLieu_paiement'];?>><?php echo $ligne['Libelle_lieu']?></option>
                    <?php 
                      }
                    ?>
              </select>
            </div>
          </div>      
          <!-- ++++++++++++++++++++++++++++++++++++++ -->

          <!-- Ici c'est le div pour sais le numéro de borderao-->
      
          <div class="row align-items-start p-0 m-0 mt-2 ">
            <div class="col fs-7 fw-light text-start   p-0"  >
                        Numéro Bordérau :
            </div>
        
            <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
              <div class="input-group mb-1 p-0  border rounded"
                style="color:white;">
                <input id="txt_numero_borderau" type="text" class="form-control p-0 pe-2 fw-bolder text-end border-0" 
                                  aria-label="Saisir en franc congolais"
                                  style="background-color:#273746;color:white; font-weight:bold;">
              
              </div>
          </div>
          </div>      
          <!-- ++++++++++++++++++++++++++++++++++++++ -->
        
        
          <!-- Ici c'est le div pour saisi le montant payer-->
      
        <div class="row align-items-start p-0 m-0 mt-2 ">
          <div class="col fs-7 fw-light text-start   p-0" >
                        Montant d'argent :
          </div>
        
          <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
            <div class="input-group mb-1 p-0  border rounded"
              style="color:white;">
              <input id="txt_montant_payer" type="number" 
              class="form-control p-0 pe-2 fw-bolder text-center border-0" 
                                  aria-label="Saisir en franc congolais"
                                  style="background-color:#273746;color:white; font-weight:bold;">
              <span class="input-group-text p-0 border-0 font-weight-bold" id="symbole_devise"
                  style="background-color:#273746;color:white;"> .$. </span>
            </div>
          </div>
        </div>      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->


          <!-- Ici c'est le div pour la conversion de montant en franc vers dollar-->
      
        <div class="row align-items-start p-0 m-0 mt-2 ">
          <div class="col col-12 fs-7 fw-light text-start p-0 font-weight-bold">
            <span class="text"  id="montant_payer_fc"  style="color:green; font-weight:bold;">Montant en dollar :</span>
          </div>
        </div>      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->


      
        
          <!-- Ici c'est le div pour le choix de frais -->
        
          <div class="row align-items-start p-0 m-0 mt-2 ">  
            <div class="col fs-7 fw-light text-start   p-0"  >
                        Chosir le type de frais :
            </div>
          
            <div class="col fs-7 fw-bolder text-end font-weight-bold p-0 ">
            
              <select id="Select_type_frais" class="form-select form-select-sm m-0 p-0 text-center "
                            aria-label="Small select example" 
                            style="background-color:#273746;color:white;">
                <option selected>Selection Frais</option>
                <option value="Frais Académiques">Frais Académiques</option>
                <option value="Enrôlement à la Session">Enrôlement à la Session</option>
                <option value="Frais Académiques et Enrôlement à la Session">FA + Enrôlement</option>
                <option value="Autres frais">Autres frais</option>
              </select>
            </div>
          </div>
      
          <!-- ++++++++++++++++++++++++++++++++++++++ -->


          <!-- Ici c'est le div pour cocher les frais -->
          <div class="row align-items-start text-center p-0 m-0 mt-2 border">    
          
            <div class="col fs-7 fw-bolder font-weight-bold p-0">
              <div class="form-check form-switch">
                <label class="form-check-label float-start" for="case_ems">E-M-S :</label>
                <input class="form-check-input float-end  " type="checkbox" role="switch" id="case_ems">  
              </div>
            </div>
        
            <div class="col fs-7 fw-bolder font-weight-bold p-0">
              <div class="form-check form-switch ">
                <label class="form-check-label float-start " for="case_es">E-S : </label>
                <input class="form-check-input float-end " type="checkbox" role="switch" id="case_es">                        
              </div>
            </div>
        
            <div class="col fs-7 fw-bolder font-weight-bold p-0 ">
              <div class="form-check form-switch ">
                <label class="form-check-label float-start " for="case_e2s">E-2-S :</label>
                <input class="form-check-input float-end " type="checkbox" role="switch" id="case_e2s">   
              </div>
            </div>  
          </div> 
          <!-- ++++++++++++++++++++++++++++++++++++++ -->
          <div class="row align-items-start text-center p-0 m-0 mt-2 border">    
          
          <div class="col fs-7 fw-bolder font-weight-bold p-0">
            <div class="form-check form-switch">
              <label class="form-check-label float-start" for="case_ERSEM1">E-R-SEM-1</label>
              <input class="form-check-input float-end  " type="checkbox" role="switch" id="case_ERSEM1">  
            </div>
          </div>
        
          <div class="col fs-7 fw-bolder font-weight-bold p-0">
            <div class="form-check form-switch ">
              <label class="form-check-label float-start " for="case_VC">Valid. crédit</label>
              <input class="form-check-input float-end " type="checkbox" role="switch" id="case_VC">                        
            </div>
          </div>
        
          
           
        </div> 


          <!-- ICi le bloc pour choisir la date du jour -->
          <div class="row align-items-start p-0 m-0 mt-2 ">    
            <div class="col fs-7 fw-light text-end p-0 mx-2"  >
                Date du jour : 
            </div>
            
            <div class="col fs-7 fw-bolder text-center font-weight-bold p-0 ">
              <div class="form-group p-0 m-0">
                <input type="date" class="form-control p-0 m-0 text-center" id="date_paiement">
              </div>
            </div>
          </div>
          <!-- ++++++++++++++++++++++++++++++++++++++ -->
        
          <!-- ICi le bloc pour afficher le montant en lettre-->
          <div class="row align-items-start p-1 m-0 mt-4 mb-2 border" id="txt_monte_caractere">    
            
          </div>
          
          <!-- Ici l'insersion du bouton-->
          <div class="row align-items-start p-0 m-0 mb-2 mt-3"> 
            <div class="col fs-7 fw-bolder text-center font-weight-bold p-0" id="sommeEnrolement">
              <div class="d-grid gap-1 p-0 m-0">
                <button id="btn_valider_paie_banque"
                class="btn btn-primary p-0 m-0 font-weight-bold"
                type="button"
                onclick="Paiement_frais_banque()">Valider
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
    
       

<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher un message de confirmation d'enregistrement ou d'échec ------>

<dialog id="boite_alert_paiement_banque" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Message (U.KA. @ CIUKA )</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Boite_Paiement_Banque()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <div class="modal-body">
      <h5 class="modal-title  text-center" id="text_alert_paiement_banque">Connexion Réussier</h5>
    </div>
  </div>
</dialog>

