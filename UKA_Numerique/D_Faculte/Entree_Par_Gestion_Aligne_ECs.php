

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_Gestion_delibe.php';
      ?>
  <div class="home-content m-0 me-3 ms-3 " id="div_gen_Aligne_Enseignant">

        <!-- +++++++++++++++++ ++++++++++++++++++++++++++++++++++++++++++++++++++ -->
        <!-- +++      LE BLOC POUR FILTRER lES UES LA REHERCHE DES ECs selon  ------>
        <!-- +++      LE SEMESTRE ET LA PROMOTION  ------->

    <div class="rounded m-0 p-0 mb-2 text-center" style="color:white;background-color: #273746;">
      
      <div class="row p-2">        
        <div class="col p-0 m-0 fw-medium ms-2 me-3">
          <div class="input-group mb-1 p-1"style="color:white;">
            <select id="id_fac_annee"  class="form-control p-0 pe-2 
                      fw-bolder text-center border ms-2"
                      style="background-color:#273746;color:white; font-weight:bold;">*
                <?php 
                    $req="SELECT * from annee_academique ORDER BY Annee_debut DESC";
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
        </div>




        <div class="col p-0 m-0 fw-medium ms-2 me-3">
          <div class="input-group mb-1 p-1 "style="color:white;">
            <select id="id_semestre"  
              class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
              style="background-color:#273746;color:white; font-weight:bold;">
              
                      <option value="rien" selected >Séléction Semestre</option>
                  <?php 
                    //$req="SELECT * from semestre ORDER BY semestre.Id_Semestre  ASC";
                    $req="
                          SELECT Id_Semestre, libelle_semestre FROM semestre; order by LENGTH(Libelle_mention) asc";
                    $stmt=$con->prepare($req);
                    $stmt->execute();


                    //$data= $con-> query($req);
                    while ($ligne=$stmt->fetch())
                    {
                  ?>
                      <option value=<?php echo $ligne["Id_Semestre"];?>><?php echo $ligne['libelle_semestre']?></option>
                      
                      <?php 
                    }
                      ?>
            </select>                    
          </div>
        </div>


        <div class="col p-0 m-0 fw-medium ms-2 me-3">
          <div class="input-group mb-1 p-1 "style="color:white;">
            <select id="code_prom_Align_EC"  
              class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
              style="background-color:#273746;color:white; font-weight:bold;">
              
                      <option value="rien" selected >Séléction Promotion</option>
                  <?php 
                    //$req="SELECT * from semestre ORDER BY semestre.Id_Semestre  ASC";
                    $req="
                          SELECT promotion.Code_Promotion as cd_prom, 
                          CONCAT(promotion.Abréviation,' ',mentions.Libelle_mention) as lib_mention 

                          from promotion,mentions,filiere

                          where promotion.IdMentions=mentions.IdMentions
                          and promotion.Abréviation LIKE '%LMD%'
                          and mentions.IdFiliere=filiere.IdFiliere
                          and filiere.IdFiliere=:idFiliere order by LENGTH(Libelle_mention) asc";
                    $stmt=$con->prepare($req);
                    $stmt->bindParam(':idFiliere',$_SESSION['id_fac']);
                    $stmt->execute();


                    //$data= $con-> query($req);
                    while ($ligne=$stmt->fetch())
                    {
                  ?>
                      <option value=<?php echo $ligne["cd_prom"];?>><?php echo $ligne['lib_mention']?></option>
                      
                      <?php 
                    }
                      ?>
            </select>
                    
          </div>
        </div>
      </div> 

    </div>


    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L'AFFICHAGE : ENSEIGNANTS | ASSISTANTS | ECs------------------------->
    <!----------------------------------------------------------------------------------------------->

    <div class="home-content text-center m-0 p-3 mt-1 border" 
          style="background-color:rgb(39,55,70);height:500px">

      <!-- Colonne 1 : ENSEIGNANTS (33%) -->
      <div class="container p-0 m-0 me-2" style="width: 33%; float: left; height:100%;">
        <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center" 
             style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
          <div>
            <i class="fas fa-chalkboard-teacher me-2"></i>
            <strong>Enseignants</strong>
          </div>
          <span class="badge bg-light text-primary" id="badge_enseignants">0</span>
        </div>
        <div class="container table-responsive small p-0 m-0" style="width: 100%; height:calc(100% - 50px); overflow-y: auto; overflow-x: hidden;">
          <table class="tab1 table-bordered table-hover text-center" id="table_aligne_enseignant" style="width:100%;">              
            <thead>
              <tr>
                <th style="width: 10%;">N°</th>
                <th style="width: 50%;">ENSEIGNANT</th>
                <th style="width: 25%;">DOMAINE</th>
                <th style="width: 15%;">TITRE</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table> 
        </div>
      </div>

      <!-- Colonne 2 : ASSISTANTS (33%) -->
      <div class="container p-0 m-0 me-2" style="width: 33%; float: left; height:100%;">
        <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center" 
             style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white;">
          <div>
            <i class="fas fa-user-graduate me-2"></i>
            <strong>Assistants</strong>
          </div>
          <span class="badge bg-light text-success" id="badge_assistants">0</span>
        </div>
        <div class="container table-responsive small p-0 m-0" style="width: 100%; height:calc(100% - 50px); overflow-y: auto; overflow-x: hidden;">
          <table class="tab1 table-bordered table-hover text-center" id="table_aligne_assistant" style="width:100%;">              
            <thead>
              <tr>
                <th style="width: 10%;">N°</th>
                <th style="width: 60%;">ASSISTANT</th>
                <th style="width: 30%;">STATUT</th>
              </tr>
            </thead>
            <tbody>
              <tr class="empty-state">
                <td colspan="3" class="text-center text-muted fst-italic" style="padding: 40px 20px; background: rgba(148, 163, 184, 0.05);">
                  <div style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;">
                    <i class="fas fa-hand-pointer"></i>
                  </div>
                  <div style="font-size: 0.9rem;">Sélectionnez un enseignant</div>
                </td>
              </tr>
            </tbody>
          </table> 
        </div>
      </div>

      <!-- Colonne 3 : ECs (30%) -->
      <div class="container p-0 m-0" style="width: 30%; float: left; height:100%;">
        <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center" 
             style="background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); color: white;">
          <div>
            <i class="fas fa-book-open me-2"></i>
            <strong>Éléments Constitutifs (ECs)</strong>
          </div>
          <span class="badge bg-light text-purple" id="badge_ecs" style="color: #7c3aed;">0</span>
        </div>
        <div class="container table-responsive small p-0 m-0" style="width: 100%; height:calc(100% - 50px); overflow-y: auto; overflow-x: hidden;">
          <table class="tab1 table-bordered table-hover table-striped" id="table_aligne_EC" style="width:100%;">              
            <thead>
              <tr>
                <th style="width: 8%;">N°</th>
                <th style="width: 10%;">ACTION</th>
                <th style="width: 35%;">INTITULÉ EC</th>
                <th style="width: 15%;">CRÉDITS</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-muted fst-italic" style="padding: 30px;">
                  <i class="fas fa-hand-pointer me-2"></i>
                  Sélectionnez un enseignant ou un assistant
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>     
    </div>
    <!-------------------------------Fin bloc affichage SM et UEs ---------------------------------------------->
  </div>
</section>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire pour la saisie de donnée ----------------------------->

<dialog id="boite_Form_EC" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Ajouter des ECs</h5>
      <button type="button" class="close ms-3" onclick="Ajout_EC()">
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






          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"onclick="Ajout_EC()"
        style="width:100%;">Valider</button>
      </div>



  </div>
</dialog>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher un message de confirmation d'enregistrement ou d'échec ------>

<dialog id="boite_alert_SM_EC" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Message (U.KA. @ CIUKA )</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Boite_Alert_SM_EC()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <div class="modal-body">
      <h5 class="modal-title  text-center" id="text_alert_boite_EC">Connexion Réussier</h5>
    </div>
  </div>
</dialog>



<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher une confirmation d'action ( suppression ou modification ) ------>

<dialog id="boite_confirmaion_action_SM_EC" 
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
      <h7 class="modal-title  text-center" id="text_confirm_EC_afficher">Connexion Réussier</h7>
      
    </div>


    <div class="modal-footer p-0 m-0">

      <div class="container">

        <div class="row  ">

          <div class="col text-center">
            <button type="button" id="btn_action_EC_oui" class="btn btn-primary"
            style="width:100%;">OUI </button>
          </div>

          <div class="col text-center">
            <button type="button" id="btn_action_EC_non" class="btn btn-primary"
            style="width:100%;">NON</button>
          </div>

        </div>
      </div>        
    </div>


  </div>
</dialog>



    
       