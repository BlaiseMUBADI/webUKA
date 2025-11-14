

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_User_Connecter.php';
      ?>
  <div class="home-content me-3 ms-3"   id="div_gen_UE">


    <!----------------------------------------------------------------------------------------------->
    <!------------------ CE BLOC CONCERNE L'AFFICHAGE DES UES et ECS -------------------------------->
    <!----------------------------------------------------------------------------------------------->

    <div class="home-content text-center m-0 p-3 mt-1"style="background-color:rgb(39,55,70);height:450px">

      <div class="container p-0 m-0" style="width:48%; float:left; height:100%;">
        <div class="container table-responsive small p-0 m-0" style="width: 100%;height:90%;">
          <table  class="tab1 table-hover table-striped table-bordered" id="table_ues" style="width:100%; ">              
            <thead class="sticky-sm-top m-0 fw-bold ">
              <tr>
                <th>N°</th>
                <th>Code UE</th>
                <th>Nom UE</th>
                <th>Categorie</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

        <div class="container p-0 m-0 mt-2">        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                type="button" onclick="Ouvrir_Form_UEs()">Ajouter une Unité d'Enseignement
            </button>
          </div> 
        </div>
      </div>
      <!-------------------------------Fin bloc affichage UE  ---------------------------------------------->
    
    
    
    
      <div class="container p-0 m-0" style="width:48%; float: right;height:100%;">        
        <div class="container table-responsive small p-0 m-0" style="width: 100%; height:90%;">        
          <table  class="tab1 table-bordered text-center" id="table_ecs" style="width:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold ">
              <tr>
                <th>Semestre</th>
                <th>Niveau Semestre</th>
              </tr>
            </thead>
            
            <tbody>              
            </tbody>
          </table> 
        </div>

        <div class="container p-0 m-0 mt-2">        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                type="button" onclick="Ouvrir_Form_EC()">Ajouter un EC
            </button>
          </div>
        </div>
      </div>
      <!-------------------------------Fin bloc affichage EC  ---------------------------------------------->  
    </div>
    <!-------------------------------Fin bloc affichage SM et UEs ---------------------------------------------->
  </div>

</section>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire ------------------------------>

<dialog id="boite_Form_UE" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Ajouter des UEs</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Form_UE()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form>
        <div class="form-group">
          <div class="row m-0 p-0">
            <div class="col-5 text-end">
              <label for="txt_code_ue">Code UE : </label>
            </div>
            <div class="col-7">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_code_ue" type="text" class="form-control p-1 pe-2  ms-2 
                      fw-bolder text-center" 
                    placeholder="Math01"
                    style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>           
          </div>
          <div class="row m-0 p-0 mt-2">
            <div class="col-5 text-end">
              <label for="txt_code_ue">Intitulé UE : </label>
            </div>
            <div class="col-7">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_libelle_ue" type="text" class="form-control p-1 pe-2  ms-2 
                      fw-bolder text-center" 
                      placeholder="Mathématique"
                      style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>           
          </div>
          <div class="row m-0 p-0 mt-2">
            <div class="col-5 text-end">
                <label for="categorie_ue">Catégorie UE : </label>
            </div>              
            <div class="col-7">
            <div class="input-group mb-1"style="color:white;background-color:#273746;">
              <select id="categorie_ue"  class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
                      style="background-color:#273746;color:white; font-weight:bold;">
              
                <option value="rien" selected >Selection Catégorie</option>
                <option value="pratique" >UE patique</option>
                <option value="magistral" >UE magistral</option>
              </select>
            </div>
          </div>           
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary"onclick="Ajout_UE()"
        style="width:100%;">Valider</button>
    </div>
  </div>
</dialog>




<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire pour la saisie de donnée ----------------------------->

<dialog id="boite_Form_EC" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Ajouter des ECs</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Form_EC()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form>
        <div class="form-group">
          <div class="row m-0 p-0">
            <div class="col-4 text-end">
              <label for="txt_nom_ec">Nom EC : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_nom_ec" type="text" class="form-control p-1 pe-2  ms-2 
                      fw-bolder text-center" 
                    placeholder="Math01"
                    style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>
          </div>
          <div class="row m-0 p-0 mt-2">
            <div class="col-4 text-end">
                <label for="txt_nb_credit">NB. Crédit : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
               <input id="txt_nb_credit" type="numeric" class="form-control p-1 pe-2  ms-2 
                      fw-bolder text-center" 
                      placeholder="5"
                      style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div> 
          </div>

          <div class="row m-0 p-0 mt-2">
            <div class="col-4 text-end">
                <label for="txt_cmi">CMI : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
               <input id="txt_cmi" type="numeric" class="form-control p-1 pe-2  ms-2 
                      fw-bolder text-center" 
                      placeholder="5"
                      style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div> 
          </div>
          <div class="row m-0 p-0 mt-2">
            <div class="col-4 text-end">
                  <label for="txt_hr_td">NB. HR. TD. : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_hr_td" type="numeric" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="20"
                        style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>           
          </div>
          <div class="row m-0 p-0 mt-2">
            <div class="col-4 text-end">
                <label for="txt_hr_tp">NB. HR. TP. : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_hr_tp" type="numeric" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="20"
                        style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>           
          </div>

          <div class="row m-0 p-0 mt-2">
            <div class="col-4 text-end">
                <label for="txt_tpe">NB. HR. TPE. : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_tpe" type="numeric" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="20"
                        style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>           
          </div>

          <div class="row m-0 p-0 mt-2">
            <div class="col-4 text-end">
                <label for="txt_vht">NB. HR. VHT : </label>
            </div>
            <div class="col-8">
              <div class="input-group mb-1"style="color:white;background-color:#273746;">
                <input id="txt_vht" type="numeric" class="form-control p-1 pe-2  ms-2 
                        fw-bolder text-center" 
                        placeholder="20"
                        style="background-color:#273746;color:white; font-weight:bold;">

              </div>
            </div>           
          </div>


        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary"onclick="Ajout_EC()" style="width:100%;">Valider</button>
    </div>
  </div>
</dialog>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher un message de confirmation d'enregistrement ou d'échec ------>

<dialog id="boite_alert_SM_UE" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Message (U.KA. @ CIUKA )</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Boite_Alert_SM_UE()">
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
      <!--button type="button" class="close ms-3" onclick="Confirmation_SM_UE_NON()">
        <span aria-hidden="true">&times;</span>
      </button-->
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



    
       