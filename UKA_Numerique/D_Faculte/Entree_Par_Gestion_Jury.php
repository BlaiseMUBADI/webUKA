

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_Gestion_delibe.php';
      ?>
  <div class="home-content me-3 ms-3" id="div_gen_Jury">

    <!----------------------------- ------------------- ----------------------------------->
    <!-------------------------------- ICI LE BLOC POUR RECHERCHE DES UTILISATEURS ----------------->
    <div class="home-content m-0 p-3 mt-1 border"
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

    <div class="home-content m-0 p-3 mt-3 border" style="background-color:rgb(39,55,70);height:450px">
      
    
      <div class="container table-responsive small p-0 m-0"style="width: 45%; float: left;">
        <table  class="tab1" id="table_agent" style="width:100%;">              
          <thead class="sticky-sm-top m-0 fw-bold">
            <tr>
              <th>N°</th>
              <th>Matricule</th>
              <th>Agent</th>
              <th>Sexe</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <!------- Ici c'est la fin du bloc pour le tableau d'affiche des agants -------------->


      <!------------------------------------------------------------------------------------>
      <!------- Affichage de compte pour chaque agent -------------------------------------->
      <!------------------------------------------------------------------------------------>
      

      <div class="container shadow-lg bg-body-tertiary rounded border m-0 p-2" style="width: 53%; float: right;color:white;">

        <!--center> <h5  id="nom_etudiant"class="text border mt-2"sytle="width:100%; height:5%;"></h5> </center>
        <!------- ICI AJOUT D'UN AUTRE COMPTES USE---------------------------->
        <div class="home-content m-0 p-0 mt-3 border">
          
          <form>

          <!-- Insertion de la ligne qui contient le login et la fonction-->
            <div class="row align-items-start p-0 m-0 mt-2">
                
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                  <div class="input-group mb-1 p-1  border rounded"style="color:white;background-color:#273746;">

                    <label for="txt_login_user">Login : </label>
                    <input id="txt_login_user" type="text" class="form-control p-0 pe-2  ms-2 
                    fw-bolder text-center border" 
                                      placeholder="EX : 1234"
                                      style="background-color:#273746;color:white; font-weight:bold;"  autocomplete="off">
                  
                  </div>
              </div>

              
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1">
            
                <div class="input-group mb-1 p-1 "style="color:white;background-color:#273746;">
                  <select id="select_fonction_compte" class="form-select form-select p-0 pe-2  text-center "
                              aria-label="Small select example" 
                                style="background-color:#273746;color:white;">
                                
                      <option selected value="Selection Fonction">Selection Fonction</option>
                      <option style="width:100%;"value="Président_jury">Président du jury</option>
                      <option style="width:100%;"value="Secrétaire_jury">Secrétaire du Jury</option>
                      <option style="width:100%;"value="Membre_jury">Membre du jury</option>>
                  </select>
                </div>
                
              </div>
            </div> 
          <!-- FIN de la ligne qui contient le login et la fonction-->



          <!-- Insertion de la ligne qui contient le mot de passe-->
            <div class="row align-items-start p-0 m-0 mt-2 ">
                
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                  <div class="input-group mb-1 p-1  border rounded"style="color:white;">

                    <label for="password_user">Password : </label>
                    <input id="password_user" type="password" class="form-control p-0 pe-2 
                                      fw-bolder text-center border ms-2" placeholder="Ex : 1234"
                                      style="background-color:#273746;color:white; font-weight:bold;" autocomplete="off">
                  
                  </div>
              </div>

              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                  <div class="input-group mb-1 p-1  border rounded"style="color:white;">

                    <label for="retapez_password_user">R_Password : </label>
                    <input id="retapez_password_user" type="password" class="form-control p-0 pe-2 
                                      fw-bolder text-center border ms-2" placeholder="Ex : 1234"
                                      style="background-color:#273746;color:white; font-weight:bold;"  autocomplete="off">
                  
                  </div>
              </div>
            </div>
          <!--FIN de la ligne qui contient le login et la fonction-->



          <!-- Insertion de la ligne qui contient l'etat de compte t le bouton ajouter-->
            <div class="row align-items-start p-0 m-0 mt-2 ">
              
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                <div class="input-group mb-1 p-1 "style="color:white;background-color:#273746;">
                  <select id="select_etat_compte" class="form-select form-select p-0 pe-2  text-center "
                                aria-label="Small select example" 
                                  style="background-color:#273746;color:white;">
                                  
                        <option selected style="width:100%;"value="Etat">Etat</option>
                        <option style="width:100%;"value="Actif">Actif</option>
                        <option style="width:100%;"value="Inactif">Inactif</option>
                  </select>

                </div>
              </div>
              
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                <div class="d-grid gap-1 p-0 m-0">
                  <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                    type="button" onclick="Nouveau_Compte_agent()">Valider
                  </button>
                </div>
              </div>

            </div>
          <!-- FIN de la ligne qui contient le bouton et -->
          </form>
        </div>
        <!------- FIN AJOUT OU MAPINIPULATION COMPTE ---------------------------->


        <div class="container table-responsive small mt-2"style="height:50%;">
          <table  class="tab1 mb-1" id="table_compte_agent" style="width:100%; height:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold">
              <tr>
                <th>N°</th>
                <th>Login</th>
                <th>Password</th>
                <th>Fonction</th>                
                <th>Etat</th>
                <th>Date Création</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <!------------------------------------FIN BLOC AFFICHAGE TABLEAU ET DETAILLE----------------------------------------->
    </div>


  </div>
</section>


<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->




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


<dialog id="boite_alert_g_jury" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Message (U.KA. @ CIUKA )</h5>
      <button type="button" class="close ms-3" onclick="Fermer_Boite_Alert_G_jury()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <div class="modal-body">
      <h5 class="modal-title  text-center" id="text_alert_boite">Connexion Réussier</h5>
    </div>
  </div>
</dialog>



<dialog id="maBoiteDeDialogue" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Selection Filière</h5>
      <!--button type="button" class="close ms-3" onclick="fermerBoiteDialogue()">
        <span aria-hidden="true">&times;</span>
      </button-->
    </div>


      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="select_filiere" class="col-form-label">Filière :</label>

            <select id="promotion_jury" class="form-select form-select p-0 pe-2  text-center "
                              aria-label="Small select example"
                              style="background-color:#273746;color:white;">
              <option selected value="promotion_jury">Promotion</option>

              
              <?php
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
                    <option style=" width:100%;" value=<?php echo $ligne["cd_prom"];?>><?php echo $ligne['lib_mention']?></option>
                    
                    <?php 
                  }
                     ?>
                      
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"onclick="fermerBoiteDialogue()"
        style="width:100%;">Valider</button>
      </div>



  </div>
</dialog>
    
       