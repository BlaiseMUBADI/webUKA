

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_User_Connecter.php';
      ?>
  <div class="home-content m-0 me-3 ms-3 " id="div_gen_gestion_SM_ECs">

        <!-- +++++++++++++++++ ++++++++++++++++++++++++++++++++++++++++++++++++++ -->
        <!-- +++      LE BLOC POUR FILTRER lES UES LA REHERCHE DES ECs selon  ------>
        <!-- +++      LE SEMESTRE ET LA PROMOTION  ------->

    <div class="rounded m-0 p-0 mb-2 text-center" style="color:white;background-color: #273746;">
      <div class="row p-2">
        
        <div class="col p-0 m-0 fw-medium ms-2 me-3">

          <div class="input-group mb-1 p-1"style="color:white;">
            <label for="id_semestre_FAC">Selection Semestre :</label>
            <select id="id_semestre_FAC"  class="form-control p-0 pe-2 
                      fw-bolder text-center border ms-2"
                      style="background-color:#273746;color:white; font-weight:bold;">*
              <option value="rien" selected >Selection Semestre</option>
                  <?php 
                    $req="SELECT * from semestre ORDER BY semestre.Id_Semestre  ASC";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
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
            <label for="code_prom_FAC">Selection Promotion:</label>
            <select id="code_prom_FAC"  class="form-control p-0 pe-2 fw-bolder text-center 
            border ms-2"
                      style="background-color:#273746;color:white; font-weight:bold;">
              
                      <option value="rien" selected >Selection Promotion</option>
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

    <!----------------------------- ------------------- -----------------------------
    <!-------------------------------- ICI LE BLOC POUR RECHERCHE DES UTILISATEURS --------------
    <div class="container m-0 p-3 mt-1 border"
      style=" width:100%; margin:0px; background-color:#273746;color:white; font-weight:bold;">
      <div class="container">

          <div class="input-group p-1 border rounded">
            
            <span class="input-group-text p-0 border-0 font-weight-bold" 
                style="background-color:#273746;color:white;">Recherche user ... </span>

            <input id="txt_recherch_user" type="text" 
            class="form-control p-0 ps-2 fw-bolder text-s border-0" 
                                aria-label="Saisir en franc congolais"
                                style="background-color:#273746;color:white; font-weight:bold;">
            
          </div>
      </div>
    </div>
    <!-----------------------------  FIN BLOC RECHERCHE ----------------------------------->
    <!------------------------------------------------------------------------------------->



    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L'AFFICHAGE DES ETUDIANTS ET AFFICHAGE DE DETAILLE A COTE-------------->
    <!----------------------------------------------------------------------------------------------->

    <div class="container text-center m-0 p-3 mt-1 border" 
          style="background-color:rgb(39,55,70);height:450px">

      <div class="container p-0 m-0" style="width: 40%; float: left;height:100%;">


        <div class="container table-responsive small p-0 m-0" style="width: 100%; height:90%;">
        
          <table  class="tab1 table-bordered text-center" id="table_ue_fac" style="width:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold ">
              <tr>
                <th>N°</th>
                <th>UE</th>
                <th>Catégorie</th>
              </tr>
            </thead>
            
            <tbody>
              
            </tbody>
          </table> 
        </div>

        <div class="container p-0 m-0 mt-2">
        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                type="button">Ajouter une UE
            </button>
          </div>
        </div>


      </div>

      <div class="container p-0 m-0" style="width: 55%; float:right; height:100%;">


        <div class="container table-responsive small p-0 m-0" style="width: 100%;height:90%;">
          <table  class="tab1 table-hover table-striped" id="table_ecs_fac" style="width:100%; ">              
            <thead class="sticky-sm-top m-0 fw-bold ">
              <tr>
                <th>N°</th>
                <th>Code UE</th>
                <th>Nom UE</th>
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
                type="button" onclick="Ouvrir_Form_EC()">Ajouter une Unité d'Enseignement
            </button>
          </div> 
        </div>


      </div>



      
    </div>
    <!-------------------------------Fin bloc affichage SM et UEs ---------------------------------------------->



  </div>
</section>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    La boite qui permet d'afficher un formulaire pour la saisie de donnée EC----------------------------->

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
                <label for="txt_tpe">TPE : </label>
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
                <label for="txt_vht">VHT : </label>
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



    
       