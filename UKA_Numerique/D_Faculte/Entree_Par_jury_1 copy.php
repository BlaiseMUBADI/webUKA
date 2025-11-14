

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_User_Connecter.php';
      ?>
  <div class="home-content m-0 me-3 ms-3 " id="div_gen_jury">
    <!----------------------------- ------------------- ----------------------------->
    <!-------------------------------- ICI LE BLOC POUR RECHERCHE DES UTILISATEURS -------------->
    <div class="container m-0 p-3 mt-1 border"
      style=" width:100%; margin:0px; background-color:#273746;color:white; font-weight:bold;">
      <div class="container">

          <div class="input-group p-1 border rounded">
            
            <span class="input-group-text p-0 border-0 font-weight-bold" 
                style="background-color:#273746;color:white;">Recherche Enseignant... </span>

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

    <div class="container text-center m-0 p-3 mt-1 border"style="background-color:rgb(39,55,70);height:450px">

      <div class="container p-0 m-0" style="width: 48%; float: left;height:100%;">

        <div class="container table-responsive small p-0 m-0" style="width: 100%; height:90%;">
        
          <table  class="tab1 table-bordered text-center" id="table_agent" style="width:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold ">
              <tr>
                <th>N°</th>
                <th>Matricule</th>
                <th>Enseignant</th>
                <th>Sexe</th>
              </tr>
            </thead>
            
            <tbody>
              
            </tbody>
          </table> 
        </div>


        <div class="container p-0 m-0 mt-2">        
          <div class="d-grid gap-1 p-0 m-0">
            <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                type="button">Ajouter Un Enseignant
            </button>
          </div>
        </div>
      </div>


      <!-----------------------------  BLOC À GAUCHE--------------------------------->
      <!------------------------------------------------------------------------------------->

      <div class="container p-0 m-0 me-2" style="width: 48%; float:right; height:100%;">


        <div class="container table-responsive small p-0 m-0 " style="width: 100%; height:33%;">

          <div class="row align-items-start p-0 m-0">
            
            <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
              <div class="input-group mb-1 p-1"style="color:white;">
                <label for="id_fac_annee">Année-Académique :</label>
                <select id="id_fac_annee"  class="form-control p-0 pe-2 fw-bolder text-center ms-2"
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
          </div> 
          <div class="row align-items-start p-0 m-0">
            <div class="col fs-7 fw-bolder text-end font-weight-bold p-1">
              
              <div class="input-group mb-1 p-1 "style="color:white;background-color:#273746;">
                <select id="select_fonction_compte" class="form-select form-select p-0 pe-2  text-center "
                            aria-label="Small select example" 
                              style="background-color:#273746;color:white;">
                              
                    <option selected value="Selection Fonction">Selection Fonction</option>
                    <option style="width:100%;"value="Président">Président</option>
                    <option style="width:100%;"value="Sécretaire">Sécretaire</option>
                    <option style="width:100%;"value="Membre">Membre</option>
                </select>
              </div>
              
            </div>
          </div>

          <div class="row align-items-start p-0 m-0">
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                <div class="d-grid gap-1 p-0 m-0">
                  <button id="btn_ajout_compte" class="btn btn-primary p-0 m-0 font-weight-bold"
                    type="button" onclick="Nouveau_Compte_agent()">Valider
                  </button>
                </div>
              </div>
          </div>
        </div>

        <div class="container table-responsive small p-0 m-0 mt-2 " style="width: 100%; height:67%;">

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



      
    </div>
    <!-------------------------------Fin bloc affichage SM et UEs ---------------------------------------------->



  </div>
</section>




<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!------------ POur la selection de la promotion ----------------------------------------->

<dialog id="maBoiteDeDialogue" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Selection Promotion</h5>
      <button type="button" class="close ms-3" onclick="fermerBoiteDialogue()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>


      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="select_prom" class="col-form-label">Promotion:</label>

            <select id="select_prom"  
              class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
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
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"onclick="fermerBoiteDialogue()"
        style="width:100%;">Valider</button>
      </div>



  </div>
</dialog>  



<dialog id="maBoiteDeDialogue_2" 
  class="shadow-lg  p-3 rounded bg-gradient-primary"  
  style="background-color:#273746;color:white">
  
  <div class="container border">
    <div class="modal-header">
      <h5 class="modal-title ms-3" id="exampleModalLabel">Création Pomotion</h5>
      <button type="button" class="close ms-3" onclick="fermerBoiteDialogue2()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>


      <div class="modal-body">
        <form>
          <div class="form-group">
              <div class="row align-items-start p-0 m-0 mt-2">
                
                <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                    <div class="input-group mb-1 p-1"style="color:white;background-color:#273746;">
  
                      <label for="txt_login_user">Login : </label>
                      <input id="txt_login_user" type="text" class="form-control p-0 pe-2  ms-2 
                      fw-bolder text-center border" 
                                        placeholder="1234"
                                        style="background-color:#273746;color:white; font-weight:bold;">
                    
                    </div>
                </div>

                <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                    <div class="input-group mb-1 p-1 "style="color:white;background-color:#273746;">
  
                      
                        <label for="select_prom" class="col-form-label">Promotion : </label>

                        <select id="select_prom"  
                          class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
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

              <div class="row align-items-start p-0 m-0 mt-2 ">
                
                <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                    <div class="input-group mb-1 p-1 "style="color:white;">

                      <label for="password_user">Password : </label>
                      <input id="password_user" type="password" class="form-control p-0 pe-2 
                                        fw-bolder text-center border ms-2" placeholder="1234"
                                        style="background-color:#273746;color:white; font-weight:bold;">
                    
                    </div>
                </div>

                <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                    <div class="input-group mb-1 p-1 "style="color:white;">

                      <label for="retapez_password_user">R_Password : </label>
                      <input id="retapez_password_user" type="password" class="form-control p-0 pe-2 
                                        fw-bolder text-center border ms-2" placeholder="1234"
                                        style="background-color:#273746;color:white; font-weight:bold;">
                    
                    </div>
                </div>
              </div>

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

              </div>



        </form>
      </div>
      <div class="modal-footer mt-3">
        <button type="button" class="btn btn-primary"onclick="fermerBoiteDialogue2()"
        style="width:100%;">Valider</button>
      </div>



  </div>
</dialog>