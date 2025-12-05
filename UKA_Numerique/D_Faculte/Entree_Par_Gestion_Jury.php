<style>
  /* ====== Layout flex pour les deux blocs ====== */
  .ligne-tables-jury {
    display: flex;
    flex-direction: row;
    gap: 12px;
    height: 500px;
    background-color: rgb(39,55,70);
  }
  .ligne-tables-jury .bloc {
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
  .jury-list { flex: 0 0 45%; }
  .membres-jury { flex: 0 0 53%; }
  
  /* En-têtes avec gradients */
  .header-jury { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: #fff; }
  .header-membres { background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #fff; }
  
  /* Amélioration du scrollbar */
  .table-scroll::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }
  .table-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  .table-scroll::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
  }
  .table-scroll::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  }
  
  /* Responsive */
  @media (max-width: 1100px) {
    .ligne-tables-jury { flex-direction: column; height: auto; }
    .jury-list, .membres-jury { flex: 0 0 auto; min-width: 100%; }
  }
  
  @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    #table_agents_jury tbody tr {
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    #table_agents_jury tbody tr:hover {
      background-color: #f0fdf4 !important;
      transform: translateX(3px);
    }
    
    #table_agents_jury tbody tr.agent-selected {
      background: linear-gradient(90deg, #d1fae5 0%, #a7f3d0 100%) !important;
      border-left: 4px solid #10b981;
      font-weight: 600;
    }
  
    
  </style>

<section class="home-section" style="height: 100%;">
      <?php
        require_once 'Profil_Gestion_delibe.php';
      ?>
  <div class="home-content me-3 ms-3" id="div_gen_Jury">



    <!----------------------------------------------------------------------------------------------->
    <!-------CE BLOC CONCERNE L'AFFICHAGE DES JURYS ET LEURS MEMBRES --------------------------------->
    <!----------------------------------------------------------------------------------------------->

    <div class="rounded m-0 p-0 mb-2 text-center" style="color:white;background-color: #273746;">
      <div class="row p-2">        
        <div class="col p-0 m-0 fw-medium ms-2 me-3">
          <div class="input-group mb-1 p-1" style="color:white;">
            <select id="id_fac_annee" class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
                    style="background-color:#273746;color:white; font-weight:bold;">
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
    </div>

    <div class="home-content text-center m-0 p-3 mt-1 border">
      <div class="ligne-tables-jury">

        <!-- Bloc Liste des Jurys -->
        <div class="bloc jury-list">
          <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center header-jury">
            <div>
              <i class="fas fa-gavel me-2"></i>
              <strong>Liste des Jurys</strong>
            </div>
            <span class="badge bg-light text-primary" id="badge_jury">0</span>
          </div>
          
          <div class="container table-responsive small p-0 m-0 table-scroll">
            <table class="tab1 table-hover table-striped text-center" id="table_jury" style="width:100%; border-collapse: collapse;">              
              <thead>
                <tr style="border-bottom: 2px solid white;">
                  <th style="border: none;">N°</th>
                  <th style="border: none;">Libellé Jury</th>
                  <th style="border: none;">Promotion</th>
                  <th style="border: none;">Date Jury</th>
                  <th style="border: none;">Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          <div class="mt-2">        
            <div class="d-grid gap-1">
              <button id="btn_ajout_ue" class="btn btn-primary p-2 font-weight-bold"
                  type="button" onclick="Ouvrir_Form_Jury()">
                <i class="fas fa-plus-circle me-2"></i>Ajouter un Jury
              </button>
            </div> 
          </div>
        </div>
        <!-- Bloc Membres du Jury -->
        <div class="bloc membres-jury">
          <div class="mb-2 p-2 rounded d-flex justify-content-between align-items-center header-membres">
            <div>
              <i class="fas fa-users me-2"></i>
              <strong>Membres du Jury</strong>
            </div>
            <span class="badge bg-light text-success" id="badge_membres">0</span>
          </div>
          
          <div class="container table-responsive small p-0 m-0 table-scroll">
            <table class="tab1 table-hover table-striped text-center" id="table_membres_jury" style="width:100%; border-collapse: collapse;">              
              <thead>
                <tr style="border-bottom: 2px solid white;">
                  <th style="border: none;">N°</th>
                  <th style="border: none;">Membre</th>
                  <th style="border: none;">Fonction</th>
                  <th style="border: none;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr class="empty-state">
                  <td colspan="4" class="text-center text-muted fst-italic" style="padding: 40px 20px; background: rgba(148, 163, 184, 0.05);">
                    <div style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;">
                      <i class="fas fa-hand-pointer"></i>
                    </div>
                    <div style="font-size: 0.9rem;">Sélectionnez un jury</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-2">        
            <div class="d-grid gap-1">
              <button id="btn_ajout_membre" class="btn btn-success p-2 font-weight-bold" type="button">
                <i class="fas fa-user-plus me-2"></i>Ajouter un Membre
              </button>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</section>





<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->




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


<!-- Boîte de confirmation moderne -->
<dialog id="boite_confirmation_jury" 
  class="shadow-lg p-0 rounded" style="border: none; max-width: 480px; width: 95%; background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
  <div class="container" style="background: white; border-radius: 16px; overflow: hidden;">
    <div class="modal-header" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white; border: none; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <span style="font-size: 1.7rem;">
          <i class="fas fa-exclamation-triangle"></i>
        </span>
        <h5 class="modal-title mb-0" style="font-weight: 600;">Confirmation (U.KA. @ CIUKA )</h5>
      </div>
      <button type="button" class="btn-close btn-close-white" onclick="Fermer_Boite_Confirmation()" style="filter: brightness(0) invert(1); font-size: 1.3rem;"></button>
    </div>
    <div class="modal-body p-4" style="text-align: center; min-height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
      <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(220,38,38,0.25); animation: popIn 0.3s;">
        <i class="fas fa-question-circle" style="color: white; font-size: 2.5rem;"></i>
      </div>
      <h5 id="text_confirmation_boite" style="color: #273746; font-weight: 600; font-size: 17px; line-height: 1.6; margin: 0 0 25px 0; word-break: break-word;">Êtes-vous sûr de vouloir continuer ?</h5>
      
      <div style="display: flex; gap: 12px; width: 100%; justify-content: center;">
        <button type="button" id="btn_confirmer" class="btn btn-danger" 
          style="padding: 12px 32px; border-radius: 10px; font-weight: 600; font-size: 15px; min-width: 120px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(220,38,38,0.3);"
          onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(220,38,38,0.4)';"
          onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(220,38,38,0.3)';">
          <i class="fas fa-check me-2"></i>Oui
        </button>
        <button type="button" onclick="Fermer_Boite_Confirmation()" class="btn btn-secondary" 
          style="padding: 12px 32px; border-radius: 10px; font-weight: 600; font-size: 15px; min-width: 120px; transition: all 0.3s;"
          onmouseover="this.style.transform='translateY(-2px)';"
          onmouseout="this.style.transform='translateY(0)';">
          <i class="fas fa-times me-2"></i>Non
        </button>
      </div>
    </div>
  </div>
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






<!-- Modal pour Ajouter un Membre au Jury -->
<dialog id="boite_Ajout_Membre_Jury"
  style="border: none; border-radius: 20px; padding: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 950px; width: 90%; animation: slideDown 0.3s ease-out;">
  <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
    <h5 style="margin: 0; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px;">
      <i class="fas fa-user-plus"></i>
      Ajouter un Membre au Jury
    </h5>
    <button type="button" onclick="Fermer_Boite_Ajout_Membre()"
      style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
      onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'"
      onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)';">
      <span>&times;</span>
    </button>
  </div>
  
  <div style="background: white; padding: 30px; border-radius: 0 0 20px 20px;">
    <!-- Zone de recherche d'agents -->
    <div style="margin-bottom: 25px;">
      <div style="position: relative;">
        <input id="txt_recherche_agent_jury" type="text" class="form-control" placeholder="Rechercher un agent..."
          style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 45px 14px 20px; font-size: 15px; transition: all 0.3s; width: 100%;"
          onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.1)';"
          onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
        <i class="fas fa-search" style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 16px; pointer-events: none;"></i>
      </div>
    </div>

    <!-- Tableau des agents -->
    <div style="margin-bottom: 25px; max-height: 320px; overflow-y: auto; border: 2px solid #e2e8f0; border-radius: 12px;">
      <table class="table table-hover mb-0" id="table_agents_jury" style="width: 100%;">
        <thead style="position: sticky; top: 0; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; z-index: 10;">
          <tr>
            <th style="padding: 14px; border: none;">N°</th>
            <th style="padding: 14px; border: none;">Matricule</th>
            <th style="padding: 14px; border: none;">Nom Complet</th>
            <th style="padding: 14px; border: none;">Grade</th>
            <th style="padding: 14px; border: none;">Sexe</th>
          </tr>
        </thead>
        <tbody style="background: white;">
          <!-- Les agents seront chargés ici via JavaScript -->
        </tbody>
      </table>
    </div>

    <!-- Formulaire de configuration du membre (caché par défaut) -->
    <div id="form_config_membre" style="display: none; padding: 25px; background: linear-gradient(135deg, rgba(5,150,105,0.05) 0%, rgba(16,185,129,0.05) 100%); border-radius: 12px; margin-bottom: 20px; border: 2px solid #d1fae5;">
      <h6 style="color: #059669; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-user-cog"></i>
        Configuration du Membre : <span id="nom_agent_selectionne" style="color: #10b981;"></span>
      </h6>

      <div class="row">
        <!-- Rôle du membre -->
        <div class="col-md-6 mb-3">
          <label for="select_role_membre" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-user-tag" style="color: #10b981; margin-right: 8px;"></i>Rôle dans le Jury
          </label>
          <select id="select_role_membre" class="form-control"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;">
            <option value="Membre">Membre</option>
            <option value="Président">Président</option>
            <option value="Secrétaire">Secrétaire</option>
          </select>
        </div>

        <!-- Statut compte (Actif/Inactif) - Visible si Président ou Secrétaire -->
        <div class="col-md-6 mb-3" id="zone_statut_compte" style="display: none;">
          <label for="select_statut_compte" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
            <i class="fas fa-toggle-on" style="color: #10b981; margin-right: 8px;"></i>Statut du Compte
          </label>
          <select id="select_statut_compte" class="form-control"
            style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;">
            <option value="Actif">Actif</option>
            <option value="Inactif">Inactif</option>
          </select>
        </div>
      </div>

      <!-- Zone Login et Password - Visible si Président ou Secrétaire -->
      <div id="zone_credentials" style="display: none;">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="txt_login_membre" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
              <i class="fas fa-user-circle" style="color: #10b981; margin-right: 8px;"></i>Login
            </label>
            <input id="txt_login_membre" type="text" class="form-control" placeholder="Login"
              style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; font-size: 14px; transition: all 0.3s; width: 100%;">
          </div>
          <div class="col-md-6 mb-3">
            <label for="txt_password_membre" style="display: block; color: #4a5568; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
              <i class="fas fa-lock" style="color: #10b981; margin-right: 8px;"></i>Mot de passe
            </label>
            <div style="position: relative;">
              <input id="txt_password_membre" type="password" class="form-control" placeholder="Mot de passe"
                style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px 45px 12px 12px; font-size: 14px; transition: all 0.3s; width: 100%;">
              <i class="fas fa-eye" id="toggle_password_icon" onclick="Toggle_Password_Visibility()"
                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; cursor: pointer; font-size: 16px;"></i>
            </div>
          </div>
        </div>
      </div>

      <button type="button" onclick="Ajout_Membre()"
        style="width: 100%; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; border: none; padding: 14px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(16,185,129,0.4); margin-top: 15px;"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(16,185,129,0.5)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16,185,129,0.4)';">
        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Ajouter le Membre
      </button>
    </div>
  </div>
</dialog>



