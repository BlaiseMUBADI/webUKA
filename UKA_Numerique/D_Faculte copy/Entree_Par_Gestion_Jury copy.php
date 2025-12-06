<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Gestion_delibe.php';
      ?>
  <div class="home-content me-3 ms-3" id="div_gen_Jury">

    <!----------------------------- ------------------- ----------------------------------->
    <!-------------------------------- ICI LE BLOC POUR RECHERCHE DES UTILISATEURS
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
    <!-----------------------------  FIN BLOC RECHERCHE ----------------------------------->
    <!------------------------------------------------------------------------------------->



    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L'AFFICHAGE DES ETUDIANTS ET AFFICHAGE DE DETAILLE A COTE-------------->
    <!----------------------------------------------------------------------------------------------->

    <div class="home-content m-0 p-3 mt-3 border" style="background-color:rgb(39,55,70);height:450px;">

      <div class="container p-0 m-0" style="width: 45%; float: left;">
        
        <div class="input-group mb-1 p-1"style="color:white;">
              <select id="id_fac_annee"  class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
                  style="background-color:#273746;color:white;font-weight:bold;border-radius:8px;height:28px;min-height:28px;max-height:28px;padding-top:0px;padding-bottom:0px;font-size:13px;line-height:1;">
                  
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
        
        <div class="table-responsive small p-0 m-0" style="width: 100%; max-height: 340px; overflow-y:auto; overflow-x:auto;">
          <table class="tab1 table-hover table-striped text-center" id="table_jury" style="width:100%; border-collapse: collapse;">              
              <thead class="sticky-sm-top m-0 fw-bold" style="background-color:midnightblue; color:white;">
                <tr style="border-bottom: 2px solid white;">
                  <th style="border: none; padding: 8px;">N°</th>
                  <th style="border: none; padding: 8px;">Libellé Jury</th>
                  <th style="border: none; padding: 8px;">Promotion</th>
                  <th style="border: none; padding: 8px;">Date Jury</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
        </div>

        <div class="container p-0 m-0 mt-2">        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_ue" class="btn btn-primary p-1 m-0 font-weight-bold"
                type="button" onclick="Ouvrir_Form_Jury()">Ajouter un Jury
            </button>
          </div> 
        </div>
      </div>
      <!------- Ici c'est la fin du bloc pour le tableau d'affiche des agents -------------->


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
  class="shadow-lg p-0 rounded" style="border: none; max-width: 420px; width: 95%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
  <div class="container" style="background: white; border-radius: 16px; overflow: hidden;">
    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <span id="alert_icon" style="font-size: 1.7rem;">
          <i class="fas fa-info-circle"></i>
        </span>
        <h5 class="modal-title mb-0" style="font-weight: 600;">Message (U.KA. @ CIUKA )</h5>
      </div>
      <button type="button" class="btn-close btn-close-white" onclick="Fermer_Boite_Alert_G_jury()" style="filter: brightness(0) invert(1); font-size: 1.3rem;"></button>
    </div>
    <div class="modal-body p-4" style="text-align: center; min-height: 80px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
      <div id="alert_icon_anim" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; box-shadow: 0 4px 15px rgba(102,126,234,0.18); animation: popIn 0.3s;">
        <i id="alert_icon_type" class="fas fa-info-circle" style="color: white; font-size: 2rem;"></i>
      </div>
      <h5 id="text_alert_boite" style="color: #273746; font-weight: 600; font-size: 18px; line-height: 1.5; margin: 0; word-break: break-word;">Connexion Réussie</h5>
    </div>
  </div>
  <style>
    @keyframes popIn {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    @media (max-width: 600px) {
      #boite_alert_g_jury { max-width: 98vw !important; }
    }
  </style>
</dialog>





<dialog id="boite_Form_Jury"
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 550px; animation: slideDown 0.3s ease-out;">
  <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-users"></i>
      Ajouter un Jury
    </h5>
    <button type="button" onclick="Fermer_Form_Jury()"
      style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
      onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'"
      onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)';">
      <span>&times;</span>
    </button>
  </div>
  <div style="background: white; padding: 30px; border-radius: 0 0 20px 20px;">
    <form>
      <div class="form-group">
        <div style="margin-bottom: 20px;">
          <label for="jury_nom" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-user" style="color: #667eea; margin-right: 8px;"></i>Nom du Jury
          </label>
          <input id="jury_nom" type="text" class="form-control"
            placeholder="Nom du Jury"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)';"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
        </div>
        <div style="margin-bottom: 20px;">
          <label for="jury_date" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-calendar" style="color: #667eea; margin-right: 8px;"></i>Date Jury
          </label>
          <input id="jury_date" type="date" class="form-control"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;"
            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)';"
            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
        </div>
        <div style="margin-bottom: 20px;">
          <label for="jury_promotion" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-layer-group" style="color: #667eea; margin-right: 8px;"></i>Promotion
          </label>
          <select id="jury_promotion" class="form-control"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; font-size: 15px; background: #f8fafc; color: #273746; font-weight: 600; transition: all 0.3s; width: 100%; cursor: pointer; box-shadow: 0 2px 8px rgba(102,126,234,0.08);">
            <option value="rien" selected style="color:#667eea; font-weight:600;">Séléction Promotion</option>
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
              while ($ligne=$stmt->fetch())
              {
            ?>
                <option value=<?php echo $ligne["cd_prom"];?>><?php echo $ligne['lib_mention']?></option>
            <?php 
              }
            ?>
          </select>
        </div>
        <!-- Ajoutez d'autres champs si nécessaire -->
      </div>
    </form>
    <button type="button" onclick="Ajouter_Jury()"
      style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 14px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; margin-top: 25px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102,126,234,0.4);"
      onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102,126,234,0.5)';"
      onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.4)';">
      <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Valider
    </button>
  </div>
</dialog>



