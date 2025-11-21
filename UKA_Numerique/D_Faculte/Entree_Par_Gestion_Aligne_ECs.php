

<style>
  /* Style pour le menu contextuel */
  #contextMenu {
    animation: slideIn 0.2s ease-out;
  }
  
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px) scale(0.95);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
  
  /* Style pour les lignes du tableau enseignants */
  #table_aligne_enseignant tbody tr {
    transition: all 0.2s ease;
    cursor: pointer;
  }
  
  #table_aligne_enseignant tbody tr:hover {
    background-color: #f0f4ff !important;
    transform: translateX(2px);
  }
  
  /* Amélioration du scrollbar pour la boîte de dialogue */
  #boite_Infos_Enseignant .modal-body::-webkit-scrollbar {
    width: 8px;
  }
  
  #boite_Infos_Enseignant .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  
  #boite_Infos_Enseignant .modal-body::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
  }
  
  #boite_Infos_Enseignant .modal-body::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  }

  /* ====== Nouveau layout flex pour les trois tableaux ====== */
  .ligne-tables {
    display: flex;
    flex-direction: row;
    gap: 12px;
    height: 500px;
    background-color: rgb(39,55,70);
  }
  .ligne-tables .bloc {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
  }
  .bloc .table-scroll {
    flex: 1 1 auto;
    overflow-y: auto;
    overflow-x: auto;
  }
  .enseignants { flex: 0 0 28%; }
  .assistants  { flex: 0 0 28%; }
  .ecs         { flex: 1 1 0%; min-width: 380px; }
  /* En-têtes gradients regroupés (facultatif pour future factorisation) */
  .header-enseignants { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: #fff; }
  .header-ecs         { background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); color: #fff; }
  .header-assistants  { background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #fff; }
  /* Sélection EC */
  #table_aligne_EC tbody tr { cursor:pointer; transition:background .2s ease, box-shadow .2s ease; }
  #table_aligne_EC tbody tr.ec-selected { background:#d1fae5 !important; border-left:4px solid #10b981 !important; }
  #table_aligne_EC tbody tr.ec-selected td { font-weight:600; }
  #table_aligne_EC tbody tr.ec-selected td:first-child { padding-left:8px; }
  /* Icône dans libellé EC */
  .ec-selected-icon { display:inline-block; margin-left:8px; color:#10b981; font-weight:bold; font-size:16px; }
  /* Responsive empilement */
  @media (max-width: 1100px) {
    .ligne-tables { flex-direction: column; height: auto; }
    .enseignants, .assistants, .ecs { flex: 0 0 auto; min-width: 100%; }
    .ecs { min-width: 100%; }
  }
</style>

<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Gestion_delibe.php';
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

    <div class="home-content text-center m-0 p-3 mt-1 border">
      <div class="ligne-tables">

        <!-- Bloc Enseignants -->
        <div class="bloc enseignants">
          <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center header-enseignants">
            <div>
              <i class="fas fa-chalkboard-teacher me-2"></i>
              <strong>Enseignants</strong>
            </div>
            <span class="badge bg-light text-primary" id="badge_enseignants">0</span>
          </div>
          <div class="container table-responsive small p-0 m-0 table-scroll">
            <table class="tab1 table-hover text-center" id="table_aligne_enseignant" style="width:100%; border-collapse: collapse;">             
              <thead>
                <tr style="border-bottom: 2px solid white;">
                  <th style="border: none;">N°</th>
                  <th style="border: none;">ENSEIGNANT</th>
                  <th style="border: none;">Titre Académique</th>
                  <th style="border: none;">Domaine</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <!-- Bloc ECs -->
        <div class="bloc ecs">
          <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center header-ecs">
            <div>
              <i class="fas fa-book-open me-2"></i>
              <strong>Éléments Constitutifs (ECs)</strong>
            </div>
            <span class="badge bg-light text-purple" id="badge_ecs" style="color:#7c3aed;">0</span>
          </div>
          <div class="container table-responsive small p-0 m-0 table-scroll" style="overflow-x:hidden;">
            <table class="tab1 table-hover table-striped" id="table_aligne_EC" style="width:100%;">             
              <thead>
                <tr style="border-bottom: 2px solid white;">
                  <th style="width: 8%; border: none;">N°</th>
                  <th style="width: 10%; border: none;">ACTION</th>
                  <th style="width: 35%; border: none;">INTITULÉ EC</th>
                  <th style="width: 15%; border: none;">CRÉDITS</th>
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

        <!-- Bloc Assistants -->
        <div class="bloc assistants">
          <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center header-assistants">
            <div>
              <i class="fas fa-user-graduate me-2"></i>
              <strong>Assistants</strong>
            </div>
            <span class="badge bg-light text-success" id="badge_assistants">0</span>
          </div>
          <div class="container table-responsive small p-0 m-0 table-scroll" style="overflow-x:hidden;">
            <table class="tab1 table-hover text-center" id="table_aligne_assistant" style="width:100%;">             
              <thead>
                <tr style="border-bottom: 2px solid white;">
                  <th style="width: 8%; border: none;">N°</th>
                  <th style="width: 10%; border: none;">ACTION</th>
                  <th style="width: 52%; border: none;">ASSISTANT</th>
                  <th style="width: 30%; border: none;">STATUT</th>
                </tr>
              </thead>
              <tbody>
                <tr class="empty-state">
                  <td colspan="4" class="text-center text-muted fst-italic" style="padding: 40px 20px; background: rgba(148, 163, 184, 0.05);">
                    <div style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;">
                      <i class="fas fa-hand-pointer"></i>
                    </div>
                    <div style="font-size: 0.9rem;">Sélectionnez un enseignant</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Légende des couleurs -->
          <div class="mt-2 p-2 rounded" style="background: rgba(148, 163, 184, 0.1); font-size: 0.75rem;">
            <div class="fw-bold mb-1" style="font-size: 0.8rem;">
              <i class="fas fa-info-circle me-1"></i>Légende
            </div>
            <div class="d-flex flex-wrap gap-2">
              <div class="d-flex align-items-center">
                <div style="width: 20px; height: 3px; background: #10b981; margin-right: 5px;"></div>
                <span>🏠 Votre filière</span>
              </div>
              <div class="d-flex align-items-center">
                <div style="width: 20px; height: 3px; background: #3b82f6; margin-right: 5px;"></div>
                <span>🔵 Autre filière</span>
              </div>
              <div class="d-flex align-items-center">
                <div style="width: 20px; height: 3px; background: #f59e0b; border-style: dashed; margin-right: 5px;"></div>
                <span>⚠️ Occupé</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-------------------------------Fin bloc affichage SM et UEs ---------------------------------------------->
  </div>
</section>





<!------------Menu contextuel pour les enseignants ----------------------------->
<div id="contextMenu" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.2); z-index: 10000; min-width: 240px; overflow: hidden;">
  
  <!-- En-tête du menu -->
  <div style="padding: 12px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.2);">
    <i class="fas fa-user-cog me-2"></i>Actions Enseignant
  </div>
  
  <!-- Options du menu -->
  <div style="padding: 4px 0;">
    <!-- Afficher Infos -->
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#4CAF50'; this.style.paddingLeft='20px';" 
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="afficherInfosEnseignant()">
      <i class="fas fa-info-circle" style="margin-right: 12px; color: #4CAF50; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Afficher Informations</span>
    </div>
    
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    
    <!-- Modifier Enseignant -->
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#2196F3'; this.style.paddingLeft='20px';" 
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="modifierEnseignant()">
      <i class="fas fa-edit" style="margin-right: 12px; color: #2196F3; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Modifier les Données</span>
    </div>
    
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    
    <!-- Historique des Cours -->
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#FF9800'; this.style.paddingLeft='20px';" 
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="afficherHistoriqueCours()">
      <i class="fas fa-history" style="margin-right: 12px; color: #FF9800; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Historique des Cours</span>
    </div>
    
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    
    <!-- Attribuer Nouveau Cours -->
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#9C27B0'; this.style.paddingLeft='20px';" 
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="attribuerNouveauCours()">
      <i class="fas fa-plus-circle" style="margin-right: 12px; color: #9C27B0; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Attribuer un Cours</span>
    </div>
    
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    
    <!-- Générer Fiche -->
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#00BCD4'; this.style.paddingLeft='20px';" 
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="genererFicheEnseignant()">
      <i class="fas fa-file-pdf" style="margin-right: 12px; color: #00BCD4; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Générer Fiche PDF</span>
    </div>
    
    <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
    
    <!-- Envoyer Email -->
    <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
         onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#E91E63'; this.style.paddingLeft='20px';" 
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="envoyerEmailEnseignant()">
      <i class="fas fa-envelope" style="margin-right: 12px; color: #E91E63; width: 20px;"></i>
      <span style="font-size: 0.95rem;">Envoyer un Email</span>
    </div>
  </div>
  
  <!-- Pied du menu -->
  <div style="padding: 8px 16px; background: #f8f9fa; border-top: 1px solid #e9ecef; text-align: center;">
    <small style="color: #6c757d; font-size: 0.75rem;">
      <i class="fas fa-mouse-pointer me-1"></i>Clic gauche pour sélectionner
    </small>
  </div>
</div>

<!------------Boîte de dialogue pour afficher les informations de l'enseignant ----------------------------->
<dialog id="boite_Infos_Enseignant" 
  class="shadow-lg p-0 rounded" 
  style="border: none; max-width: 600px; width: 90%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
  
  <div class="container" style="background: white; border-radius: 8px; overflow: hidden;">
    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px;">
      <div style="display: flex; align-items: center; width: 100%;">
        <i class="fas fa-user-circle me-3" style="font-size: 1.5rem;"></i>
        <h5 class="modal-title mb-0" style="flex: 1;">Informations de l'Enseignant</h5>
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
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 500px; animation: slideDown 0.3s ease-out;">
  
  <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-exclamation-triangle"></i>
      Attention (U.KA. @ CIUKA)
    </h5>
    <button type="button" onclick="Fermer_Boite_Alert_SM_EC()" 
      style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
      onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'"
      onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)'">
      <span>&times;</span>
    </button>
  </div>
  
  <div style="background: white; padding: 40px 30px; border-radius: 0 0 20px 20px; text-align: center;">
    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 15px rgba(56,239,125,0.3);">
      <i class="fas fa-exclamation-circle" style="color: white; font-size: 32px;"></i>
    </div>
    <h5 id="text_alert_boite_EC" style="color: #2d3748; font-weight: 600; font-size: 18px; line-height: 1.6; margin: 0;">Message</h5>
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



    
       