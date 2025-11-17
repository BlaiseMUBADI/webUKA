

<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Guichet.php';
      ?>


  <div class="home-content me-3 ms-3" id="div_gen_Rapport_paie">

    
    <div class="rounded m-0 p-3 mt-3 text-center"
      style="color:white;background-color: #273746;">
           
      
        <!-- Ici c est le div pour cocher la devise-->
         <!-- ces bouton rabios sont là pour si le rapport est élaborer quand les frais sont fixé en fc ou en dollar-->

        <div class="row align-items-start p-1 m-0 mb-2 mt-4" style="border-top:1px solid white; border-left:1px solid white;  border-right:1px solid white;">
          <div class="col fs-7  text-center font-weight-bold p-0">
              Rappor de la Situation de l'Année en cours ou l'Année passée ? : 
          </div>
        </div>  
        <center style="border-bottom:1px solid white; border-left:1px solid white;  
        border-right:1px solid white; margin-bottom:15px;"> 
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="devise_payer" id="dollar_rapport" value="dollar" checked>
              <label class="form-check-label" for="dollar">Année en cours </label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="devise_payer" id="franc_congolais_rapport" value="franc_congolais">
            <label class="form-check-label" for="franc_congolais">Année passée</label>
          </div>
        </center> 
        <!-- ++++++++++++++++++++++++++++++++++++++ -->



        <!-- Puis les autres sont là pour imprimer le rapport selon l'argent perçu journalierèment -->
         <!--  en( Fc ou $ )-->

         <div class="row align-items-start p-1 m-0 mb-2 mt-4" style="border-top:1px solid white; border-left:1px solid white;  border-right:1px solid white;">
          <div class="col fs-7  text-center font-weight-bold p-0">
              Rapport pour Argent perçu en  : 
          </div>
        </div>  
        <center style="border-bottom:1px solid white; border-left:1px solid white;  
        border-right:1px solid white; margin-bottom:15px;"> 
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="devise_percu" id="dollar_percu" value="dollar" checked>
              <label class="form-check-label" for="dollar">Dollar</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="devise_percu" id="franc_congolais_percu" value="franc_congolais">
            <label class="form-check-label" for="franc_congolais">Franc congolais</label>
          </div>
        </center> 
        <!-- ++++++++++++++++++++++++++++++++++++++ -->
    


          <div class="row mt-2 mb-2 p-2" style="border-bottom: 1px solid white;">

          Selectionner Lieu paiement : 
          <div class="col-2 p-0 m-0 fw-medium ms-2 me-3">
              
              
              <select id="Id_lieu_paiement" class="form-select form-select-sm m-0 p-0 m text-center
               fw-bold float-end"
                        aria-label="Small select example" 
                        style="background-color:#273746;color:white;">
                <option value="rien" selected >Selection paiement</option>
                  <?php 
                    $req="SELECT * from lieu_paiement ORDER BY lieu_paiement.Libelle_lieu ASC";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
                    {
                  ?>
                      <option value=<?php echo $ligne["idLieu_paiement"];?>><?php echo $ligne['Libelle_lieu']?></option>
                      
                      <?php 
                    }
                      ?>
              </select>
            </div>

            Selectionner la Filière :
            <div class="col-3 ms-2 fs-7 fw-bolder text-end font-weight-bold p-0 ">         
              <select id="filieree" class="form-select form-select-sm m-0 p-0 text-center
                fw-bold float-end"
                        aria-label="Small select example" 
                        style="background-color:#273746;color:white;">
                        
                <option value="rien" selected>Filière </option>
                <?php 
                  $req="select * from filiere order by LENGTH(Libelle_Filiere) ASC;";
                  $data= $con-> query($req);
                  while ($ligne=$data->fetch())
                  {
                ?>
                    <option style=" width:100%;"value=<?php echo $ligne['IdFiliere'];?>><?php echo $ligne['Libelle_Filiere']?></option>
                    
                <?php 
                  }
                ?>
             </select>
            </div>

          </div>


         




          <div class="row mt-1 mb-2 p-2 " style="border-bottom: 1px solid white;">

            Année Académique : 
              <div class="col-2 me-3 ms-2 fs-7 fw-bolder text-end font-weight-bold p-0 "> 
                <select id="Id_an_acade" class="form-select form-select-sm m-0 p-0 text-center
                    fw-bold float-end"
                            aria-label="Small select example" 
                            style="background-color:#273746;color:white;">
                            
                    <option value="rien" selected>Année acad </option>
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
                
              </div>

            DU :
            <div class="col-3 ms-2 me-3 fs-7 fw-bolder text-end font-weight-bold p-0 "> 
              <input type="date" class="form-control p-0 m-0 text-center" id="date_debut"
                  style="background-color:#273746;color:white;">
              
            </div>

            AU :
            <div class="col-3 ms-2  me-3 fs-7 fw-bolder text-end font-weight-bold p-0 "> 
              <input type="date" class="form-control p-0 m-0 text-center" id="date_fin"
                  style="background-color:#273746;color:white;">
              
            </div>
          </div>

         
    </div>
  </div>
</section>
    
     