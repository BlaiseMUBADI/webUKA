

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_Gestion_delibe.php';
      ?>
  <div class="home-content me-3 ms-3"   id="div_gen_Enseignant">

    ----------------------------------------------------------------------------------------------->

    <div class="home-content text-center m-0 p-3 mt-5 border" 
          style="background-color:rgb(39,55,70);height:450px">

      <div class="home-content p-0 m-0" style="height:100%;">


        <div class="home-content table-responsive small p-0 m-0" style="width: 100%; height:90%;">
        
          <table  class="tab1 table-bordered text-center" id="table_enseignant" style="width:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold">
              <tr>
                <th>N°</th>
                <th>Enseignant</th>
                <th>Sexe</th>
                <th>Domaine</th>
                <th>Titre</th>
                <th>Institution</th>
                <th>Filière</th>
                <th>Photo</th>
              </tr>
            </thead>
            
            <tbody>
              
            </tbody>
          </table> 
        </div>

        <div class="home-content p-0 m-0 mt-2">
        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                type="button" onclick="Ouvrir_Form_Enseignant()">Ajouter un Enseignant
            </button>
          </div>
        </div>
      </div>      
    </div>
    <!-------------------------------Fin bloc affichage Enseignant ---------------------------------------------->



  </div>
</section>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire ------------------------------>

<dialog id="boite_Form_Enseignant" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Ajouter des Enseignants</h5>
      <button type="button" class="close ms-3" onclick="Fermer_boite_Enseignant()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>


    <div class="modal-body">
      <form>
        <div class="form-group">
          
          <div class="row m-0 p-0">


            <!--------------------  Premier bloc ------------------------------->
            <div class="col">
              

              <div class="row m-0 p-0">
                <div class="col-5 text-end">
                  <label for="txt_mat_enseignant">Mat Enseignant : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_mat_enseignant" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                      placeholder="Agent_1"
                      style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_nom_enseignant">Nom : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_nom_enseignant" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_post_nom_enseignant">Post-Nom : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_post_nom_enseignant" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_prenom_enseignant">Prénom : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_prenom_enseignant" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

              

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="sexe_enseignant">Sexe : </label>
                </div>
                
                <div class="col-7">

                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                    <center class=" m-0 ms-5 p-1 pe-2"> 
                      <div class="form-check form-check-inline">
                          <input class="form-check-input form-control " type="radio" name="sexe_enseignant" id="sexe_enseignant_M" value="sexe_M" checked>
                          <label class="form-check-label" for="sexe_enseignant_M">M</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input form-control" type="radio" name="sexe_enseignant" id="sexe_enseignant_F" value="sexe_F">
                        <label class="form-check-label " for="sexe_enseignant_F">F</label>
                      </div>
                    </center> 
                  </div>
                
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="cmb_Etat_Civil">Etat civil : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                    <select id="cmb_Etat_Civil"  class="form-control p-1 pe-2 fw-bolder text-center border ms-2" style="background-color:#273746;color:white; font-weight:bold;">

                        <option value="rien" selected >état civil</option>
                        <option value="Célibataire">Célibataire</option>
                        <option value="Marié(e)">Marié(e)</option>
                        <option value="Veuve">Veuve</option>
                        <option value="Veuf">Veuf</option>

                    </select>
                  </div>
                </div>

              </div>

              
              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="cmb_Categorie">Cotégorie : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                    <select id="cmb_Categorie"  class="form-control p-1 pe-2 fw-bolder ext-center border ms-2" style="background-color:#273746;color:white; font-weight:bold;">

                        <option value="rien" selected >Cotégorie</option>
                        <?php 
                        //Requette de sélection de catégorie agent
                          $req="select * from categorie order by IdCategorie Asc LIMIT 2";
                          $data= $con-> query($req);
                          while ($ligne=$data->fetch())
                          {
                        ?>
                        <option value="<?php echo $ligne['IdCategorie']?>"><?php echo $ligne['Libelle'];?></option>
                        <?php 
                            }
                        ?>  

                    </select>
                  </div>
                </div>

              </div>
              
            </div>
            <!--------------------  Fin Premier Bloc ------------------------------->

            <!--------------------  Deuxième bloc ------------------------------->
            
            <div class="col">


              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_telephone_enseignant">Téléphone : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_telephone_enseignant" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>
              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_email_enseignant">Email : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_email_enseignant" type="mail" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="cmb_niveau_etude_enseignant">Niveau Etude : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                    <select id="cmb_niveau_etude_enseignant"  class="form-control p-1 pe-2 fw-bolder 
                    text-center border ms-2"
                        style="background-color:#273746;color:white; font-weight:bold;">

                        <option value="rien" selected >Niveau étude</option>
                        <option value="Docteur. Ph" >Docteur Ph.</option>
                        <option value="Master" >Master</option>
                        <option value="Licencié" >Licencié</option>
                        <option value="Gradué" >Gradué</option>
                        <option value="D6" >D6</option>

                    </select>
                  </div>
                </div>

              </div>
              
              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="cmbr_titre_academique">Grade : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                    <select id="cmbr_titre_academique"  class="form-control p-1 pe-2 fw-bolder text-center border ms-2" style="background-color:#273746;color:white; font-weight:bold;">

                       

                    </select>

                  </div>
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_domaine_etude">Domaine Etude : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_domaine_etude" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>


              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="txt_institution_attache">Institution Attache : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="txt_institution_attache" type="text" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

              <div class="row m-0 p-0 mt-2">
                <div class="col-5 text-end">
                  <label for="photo_profil"> Photo : </label>
                </div>
                
                <div class="col-7">
                  <div class="input-group mb-1"style="color:white;background-color:#273746;">

                      <input id="photo_profil" type="file" accept=".jpg, .jpeg, .png, .gif"
                         class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="CIUKA"
                        style="background-color:#273746;color:white; font-weight:bold;">

                  </div>
                </div>           
              </div>

            </div>
            <!--------------------  Fin Deuxième Bloc ------------------------------->
          </div>
          <!--------------------  Fin Ligne ROW ------------------------------->

        </div>
      </form>
    </div>
    
    <div class="modal-footer">
      <button type="button" class="btn btn-primary"onclick="Ajout_Enseignants()"
        style="width:100%;">
        Valider
      </button>
    </div>
  </div>

</dialog>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher un message de confirmation d'enregistrement ou d'échec ------>

<dialog id="boite_alert_Enseignant" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Message (U.KA. @ CIUKA )</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Boite_Alert_Enseignant()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <div class="modal-body">
      <h5 class="modal-title  text-center" id="text_alert_boite">Connexion Réussier</h5>
    </div>
  </div>
</dialog>



<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher une confirmation d'action ( suppression ou modification ) ------>

<dialog id="boite_confirmaion_action_SM_UE" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Confirmation (U.KA. @ CIUKA )</h5>
    </div>
    
    <div class="modal-body">
      <h7 class="modal-title  text-center" id="text_confirm_afficher">Connexion Réussier</h7>
      
    </div>

    <div class="modal-footer p-0 m-0">
      <div class="container">
        <div class="row  ">
          <div class="col text-center">
            <button type="button" id="btn_action_oui" class="btn btn-primary"
            style="width:100%;">OUI </button>
          </div>

          <div class="col text-center">
            <button type="button" id="btn_action_non" class="btn btn-primary"
            style="width:100%;">NON</button>
          </div>
        </div>
      </div>        
    </div>


  </div>
</dialog>



    
       