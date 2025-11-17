

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Perception/Profil_Guichet.php';
      ?>
  <div class="home-content me-3 ms-3">

  <!-------------------------------------------------------------------------------------------->
  <!------- Bloc pour affichage  -----------> 
  <!-------------------------------------------------------------------------------------------->
  
  <div class="sales-boxes m-0 p-3 mt-3 border" 
          style="background-color:rgb(39,55,70);">
      
      <!----------------------------------------------------------------------------------------------->
      <!---------------------------------CE BLOC CONCERNE L'AFFICHAGE DES FRAITS--------------------->
      
      <div class="container table-responsive small p-0 m-0" 
          style="color:white;">

          <div class="col fs-4 fw-bolder text-center font-weight-bold p-0 ">
             N.B.: Ecriver le taux d'un 10 $ Exemple : (25000 Fc)
          </div>
      </div>
    </div> 

  <!------------------------------------- FIN BLOC ------------------------------------------------------->
    

    

    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L4AFFICHAGE DES ETUDIANTS ET AFFICHAGE DE DETAILLE A COTE-------------->
    <!----------------------------------------------------------------------------------------------->
    <div class="sales-boxes m-0 p-3 mt-3 border" 
          style="background-color:rgb(39,55,70); height:350px">
      
      <!----------------------------------------------------------------------------------------------->
      <!---------------------------------CE BLOC CONCERNE L'AFFICHAGE DES FRAITS--------------------->
      
      <div class="container table-responsive small p-0 m-0" 
          style="width: 60%; float: left;color:white;">

          <div class="col fs-4 fw-bolder text-center font-weight-bold p-0 mb-3">
             Le taux dans la base
          </div>

          <div class="col fw-bolder text-center font-weight-bold p-0 " id="div_contenaire_taux">
            <div class="row align-items-start p-2 m-0  mt-2 border"> 
              <div class="col fw-bolder text-center font-weight-bold p-0">
                Id taux
              </div>

              <div class="col fw-bolder text-center font-weight-bold p-0">
                Taux
              </div>

              <div class="col fw-bolder text-center font-weight-bold p-0">
                Date Modification
              </div>
            </div>


            <div class="row align-items-start p-3 m-0 border"> 
              <div class="col  text-center font-weight-bold p-0" id="div_id_taux">
                Id taux
              </div>

              <div class="col  text-center font-weight-bold p-0" id="div_taux">
                Taux
              </div>

              <div class="col  text-center font-weight-bold p-0" id="div_date_mod">
                Date Modification
              </div>
            </div>

          </div>
        </div>

      <div class="bloc2 shadow-lg bg-body-tertiary rounded border m-0 p-3 m-0" style="color:white; float: right; width: 39%;margin-left:7px;">
        
        <!--************ Bloc operation *********************-------->
        
        <div class="row align-items-start p-0 m-0 mb-2 mt-2 border"> 
          <div class="col fs-7 fw-bolder text-center font-weight-bold p-0">
             Changer le taux
          </div>
        </div>        
        <!-----------------------------------------------------------> 
        
        <!-- Ici c'est le div pour sais le montant frais-->
      
        <div class="row align-items-start p-0 m-0 mt-5 ">
          <div class="col fs-7 fw-light text-start   p-0"  >
                        Taux  :
          </div>
        
          <div class="col col-8 fs-7 fw-bolder text-end font-weight-bold p-0 ">
            <div class="input-group mb-1 p-0  border rounded"
              style="color:white;">

              <input 

                id="txt_taux" 
                type="number" 
                class="form-control p-0 pe-2 fw-bolder text-center border-0" 
                                  aria-label="Saisir en franc congolais"
                                  style="background-color:#273746;color:white; font-weight:bold;">

              <span class="input-group-text p-0 border-0 font-weight-bold" 
              style="background-color:#273746;color:white;"> . Fc . </span>
            </div>
          </div>
        </div>      
        <!-- ++++++++++++++++++++++++++++++++++++++ -->


        <!-------------------------------------------------------------------------->
        <div class="row align-items-start p-0 m-0 mt-2 ">    
          <div class="col fs-7 fw-light text-end p-0 mx-2"  >
              Date du jour : 
          </div>
          
          <div class="col fs-7 fw-bolder text-center font-weight-bold p-0 ">
            <div class="form-group p-0 m-0">
              <input type="date" class="form-control p-0 m-0 text-center" id="date_modif_taux">
            </div>
          </div>
        </div>

        <!-------------------------------------------------------------------------->


        <!-- ICi le bloc pour afficher le montant en lettre-->
        <div class="row fs-7 fw-bolder
            align-items-start p-2 m-0 mt-4 mb-2 border" 
            style="color:white;" id="txt_taux_caractere">    
          
        </div>
        <!-- ++++++++++++++++++++++++++++++++++++++ -->
        
        <!-- Ici l'insersion du bouton-->
        <div class="row align-items-start p-0 m-0 mb-2 mt-3"> 
          <div class="col fs-7 fw-bolder text-center font-weight-bold p-0" id="sommeEnrolement">
            <div class="d-grid gap-1 p-0 m-0">
              <button id="btn_valider_paie_guichet" 
              class="btn btn-primary p-0 m-0 font-weight-bold" 
              type="button"
              onclick="Nouveau_Taux()">Valider
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
    
       