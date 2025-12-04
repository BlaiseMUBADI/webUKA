<style>
  /* Scroll vertical/horizontal pour le tableau agents (identique modèle faculté) */
  .table-scroll-agent {
    max-height: 400px;
    overflow-y: auto !important;
    overflow-x: auto !important;
    background: #fff;
  }
  /* Sélection ligne agent : vert clair comme faculté */
  #table_agent tbody tr.selected {
    background: #d1fae5 !important;
    border-left: 4px solid #10b981 !important;
    color: #222;
  }
</style>
<style>
  /* Style pour les lignes du tableau agents (comme enseignants) */
  #table_agent tbody tr {
    transition: all 0.2s ease;
    cursor: pointer;
  }
  #table_agent tbody tr:hover {
    background-color: #f0f4ff !important;
    transform: translateX(2px);
    color: #222;
  }
  /* Pour la sélection future (si besoin) */
  #table_agent tbody tr.selected {
    background: #d1fae5 !important;
    border-left: 4px solid #10b981 !important;
  }
</style>
<!-- ================= MENU CONTEXTUEL ENSEIGNANT (AGENT) ================= -->
<div id="contextMenu" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.2); z-index: 10000; min-width: 240px; overflow: hidden;">
  <div style="padding: 12px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.2);">
    <i class="fas fa-user-cog me-2"></i>Actions Agent
  </div>
  <div style="padding: 4px 0;">
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#4CAF50'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="afficherInfosEnseignant()">
      <i class="fas fa-info-circle" style="margin-right: 12px; color: #4CAF50; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Afficher Informations</span>
    </div>
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#2196F3'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="modifierEnseignant()">
      <i class="fas fa-edit" style="margin-right: 12px; color: #2196F3; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Modifier les Données</span>
    </div>
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#FF9800'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="afficherHistoriqueCours()">
      <i class="fas fa-history" style="margin-right: 12px; color: #FF9800; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Historique des Cours</span>
    </div>
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#9C27B0'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="attribuerNouveauCours()">
      <i class="fas fa-plus-circle" style="margin-right: 12px; color: #9C27B0; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Attribuer un Cours</span>
    </div>
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#00BCD4'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="genererFicheEnseignant()">
      <i class="fas fa-file-pdf" style="margin-right: 12px; color: #00BCD4; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Générer Fiche PDF</span>
    </div>
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#E91E63'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="envoyerEmailEnseignant()">
      <i class="fas fa-envelope" style="margin-right: 12px; color: #E91E63; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Envoyer un Email</span>
    </div>
  </div>
  <div style="padding: 8px 16px; background: #f8f9fa; border-top: 1px solid #e9ecef; text-align: center;">
    <small style="color: #6c757d; font-size: 0.75rem;">
      <i class="fas fa-mouse-pointer me-1"></i>Clic gauche pour sélectionner
    </small>
  </div>
</div>

<!-- ================= BOÎTE DE DIALOG INFOS ENSEIGNANT (AGENT) ================= -->
<dialog id="boite_Infos_Enseignant" 
  class="shadow-lg p-0 rounded" 
  style="border: none; max-width: 600px; width: 90%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
  <div class="container" style="background: white; border-radius: 8px; overflow: hidden;">
    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px;">
      <div style="display: flex; align-items: center; width: 100%;">
        <i class="fas fa-user-circle me-3" style="font-size: 1.5rem;"></i>
        <h5 class="modal-title mb-0" style="flex: 1;">Informations de l'Agent</h5>
        <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('boite_Infos_Enseignant').close()" 
                style="filter: brightness(0) invert(1);"></button>
      </div>
    </div>
    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
      <div class="row g-3">
        <!-- Identité -->
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
            <div class="card-body">
              <h6 class="card-title mb-3" style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 8px;">
                <i class="fas fa-id-card me-2"></i>Identité
              </h6>
              <div class="row g-2">
                <div class="col-md-4">
                  <small class="text-muted d-block">Matricule</small>
                  <strong id="info_mat_agent">-</strong>
                </div>
                <div class="col-md-8">
                  <small class="text-muted d-block">Nom Complet</small>
                  <strong id="info_nom_complet">-</strong>
                </div>
                <div class="col-md-4">
                  <small class="text-muted d-block">Sexe</small>
                  <span id="info_sexe">-</span>
                </div>
                <div class="col-md-8">
                  <small class="text-muted d-block">Catégorie</small>
                  <span id="info_categorie">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Contact -->
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
            <div class="card-body">
              <h6 class="card-title mb-3" style="color: #f5576c; border-bottom: 2px solid #f5576c; padding-bottom: 8px;">
                <i class="fas fa-address-book me-2"></i>Contact
              </h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <small class="text-muted d-block">Téléphone</small>
                  <span id="info_telephone">-</span>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block">Email</small>
                  <span id="info_email" style="word-break: break-all;">-</span>
                </div>
                <div class="col-12">
                  <small class="text-muted d-block">Adresse</small>
                  <span id="info_adresse">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Académique -->
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);">
            <div class="card-body">
              <h6 class="card-title mb-3" style="color: #4facfe; border-bottom: 2px solid #4facfe; padding-bottom: 8px;">
                <i class="fas fa-graduation-cap me-2"></i>Informations Académiques
              </h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <small class="text-muted d-block">Titre Académique (Grade)</small>
                  <strong id="info_titre_academique" style="color: #4facfe;">-</strong>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block">Domaine d'Étude</small>
                  <strong id="info_domaine" style="color: #4facfe;">-</strong>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block">Niveau d'Étude</small>
                  <span id="info_niveau_etude">-</span>
                </div>
                <div class="col-md-6">
                  <small class="text-muted d-block">Filière</small>
                  <span id="info_filiere">-</span>
                </div>
                <div class="col-12">
                  <small class="text-muted d-block">Institution Attachée</small>
                  <span id="info_institut_attache">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 15px 20px;">
      <button type="button" class="btn btn-secondary" onclick="document.getElementById('boite_Infos_Enseignant').close()" 
              style="background: #6c757d; border: none; padding: 8px 20px;">
        <i class="fas fa-times me-2"></i>Fermer
      </button>
    </div>
  </div>
</dialog>


<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Admin_Fac.php';
      ?>
  <div class="home-content me-3 ms-3">

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

    <div class="sales-boxes m-0 p-3 mt-3 border" style="background-color:rgb(39,55,70);height:450px">
      
    
      <div class="container table-responsive small p-0 m-0 table-scroll-agent" style="width: 45%; float: left;">
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
      </script>
      <script>
      // ================= MENU CONTEXTUEL & BOÎTE INFOS AGENT =================
      let contextMenu = document.getElementById('contextMenu');
      let selectedAgent = null;

      // Gestion clic droit sur le tableau des agents
      document.addEventListener('DOMContentLoaded', function() {
        const tableAgent = document.getElementById('table_agent');
        if (tableAgent) {
          tableAgent.addEventListener('contextmenu', function(e) {
            // Trouver la ligne sur laquelle on a cliqué
            let tr = e.target.closest('tr');
            if (tr && tr.parentNode.tagName === 'TBODY') {
              e.preventDefault();
              // Retirer la sélection des autres lignes
              tableAgent.querySelectorAll('tr.selected').forEach(row => row.classList.remove('selected'));
              tr.classList.add('selected');
              // Stocker les infos de l'agent (à adapter selon structure)
              let tds = tr.querySelectorAll('td');
              selectedAgent = {
                mat_agent: tds[1]?.textContent || '-',
                nom_complet: tds[2]?.textContent || '-',
                sexe: tds[3]?.textContent || '-',
                // Ajouter d'autres champs si besoin
              };
              // Afficher le menu contextuel à la position de la souris
              contextMenu.style.display = 'block';
              contextMenu.style.left = e.pageX + 'px';
              contextMenu.style.top = e.pageY + 'px';
            }
          });
          // Cacher le menu contextuel au clic ailleurs
          document.addEventListener('click', function(e) {
            if (contextMenu && !contextMenu.contains(e.target)) {
              contextMenu.style.display = 'none';
            }
          });
        }
      });

      // Fonctions pour actions du menu contextuel (à adapter selon besoins)
      function afficherInfosEnseignant() {
        if (!selectedAgent) return;
        // Remplir la boîte de dialogue avec les infos de l'agent
        document.getElementById('info_mat_agent').textContent = selectedAgent.mat_agent;
        document.getElementById('info_nom_complet').textContent = selectedAgent.nom_complet;
        document.getElementById('info_sexe').textContent = selectedAgent.sexe;
        // ... autres champs à remplir si besoin
        document.getElementById('boite_Infos_Enseignant').showModal();
        contextMenu.style.display = 'none';
      }
      function modifierEnseignant() { /* ... */ }
      function afficherHistoriqueCours() { /* ... */ }
      function attribuerNouveauCours() { /* ... */ }
      function genererFicheEnseignant() { /* ... */ }
      function envoyerEmailEnseignant() { /* ... */ }
      </script>
      <!------- Ici c'est la fin du bloc pour le tableau d'affiche des agants -------------->


      <!------------------------------------------------------------------------------------>
      <!------- Affichage de compte pour chaque agent -------------------------------------->
      <!------------------------------------------------------------------------------------>
      

      <div class="container shadow-lg bg-body-tertiary rounded border m-0 p-2"
      style="width: 53%; float: right;color:white;">

        <!--center> <h5  id="nom_etudiant"class="text border mt-2"sytle="width:100%; height:5%;"></h5> </center>
        <!------- ICI AJOUT D'UN AUTRE COMPTES USE---------------------------->
        <div class="sales-boxes m-0 p-0 mt-3 border">
          
          <form>

          <!-- Insertion de la ligne qui contient le login et la fonction-->
            <div class="row align-items-start p-0 m-0 mt-2">
                
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                  <div class="input-group mb-1 p-1  border rounded"style="color:white;background-color:#273746;">

                    <label for="txt_login_user">Login : </label>
                    <input id="txt_login_user" type="text" class="form-control p-0 pe-2  ms-2 
                    fw-bolder text-center border" 
                                      placeholder="1234"
                                      style="background-color:#273746;color:white; font-weight:bold;">
                  
                  </div>
              </div>

              
              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1">
            
                <div class="input-group mb-1 p-1 "style="color:white;background-color:#273746;">
                  <select id="select_fonction_compte" class="form-select form-select p-0 pe-2  text-center "
                              aria-label="Small select example" 
                                style="background-color:#273746;color:white;">
                                
                      <option selected value="Selection Fonction">Selection Fonction</option>
                      <option style="width:100%;"value="Sécretaire Academique">Sécretaire Academique</option>
                      <option style="width:100%;"value="Doyen">Doyen</option>
                      <option style="width:100%;"value="VD">Vice-doyen</option>
                      <option style="width:100%;"value="Sec_facultaire">Sécretaire faculté</option>
                      <option style="width:100%;"value="Apparitaire">Apparitaire</option>
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
                                      fw-bolder text-center border ms-2" placeholder="1234"
                                      style="background-color:#273746;color:white; font-weight:bold;">
                  
                  </div>
              </div>

              <div class="col fs-7 fw-bolder text-end font-weight-bold p-1 ">
                  <div class="input-group mb-1 p-1  border rounded"style="color:white;">

                    <label for="retapez_password_user">R_Password : </label>
                    <input id="retapez_password_user" type="password" class="form-control p-0 pe-2 
                                      fw-bolder text-center border ms-2" placeholder="1234"
                                      style="background-color:#273746;color:white; font-weight:bold;">
                  
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




<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->
<!-----------    Une boite pour afficher une confirmation d'action ( suppression ou modification ) ------>

<dialog id="boite_confirmaion_action_g_compte" 
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


<dialog id="boite_alert_g_compte" 
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
      <button type="button" class="close ms-3" onclick="fermerBoiteDialogue()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>


      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="select_filiere" class="col-form-label">Filière :</label>

            <select id="select_filiere" class="form-select form-select p-0 pe-2  text-center "
                              aria-label="Small select example"
                              style="background-color:#273746;color:white;">
              <option selected value="filiere">Filière</option>
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
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"onclick="fermerBoiteDialogue()"
        style="width:100%;">Valider</button>
      </div>



  </div>
</dialog>
    
       