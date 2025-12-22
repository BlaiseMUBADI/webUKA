
<link rel="stylesheet" href="Styles_CSS/Encodage_Modern.css">

<section class="home-section" style="height: 100%;">
  <?php require_once 'Profil_Gestion_delibe.php'; ?>
  
  <div id="encodage-container" class="">
    
    <!-- En-tête moderne -->
    <div class="encodage-header">
      <button class="toggle-menu-btn" onclick="toggleMenuEncodage()" title="Masquer/Afficher le menu">
        <i class="fas fa-bars"></i>
      </button>
      
      <div class="fullscreen-indicator" id="fullscreen-indicator" style="display: none;">
        <i class="fas fa-expand-arrows-alt"></i> 
        <span>Plein Écran</span>
        <span class="badge-plus">+180px</span>
      </div>
      
      <div class="controls-group">
        <div class="semestre-selector">
          <select id="id_semestre_encodage">
            <option value='0' selected>📚 Sélectionner un Semestre</option>
            <?php 
            if (isset($con)) {
                $req = "SELECT semestre.Id_Semestre, semestre.libelle_semestre 
                        FROM element_constitutifs_aligne 
                        JOIN semestre ON element_constitutifs_aligne.Id_Semestre = semestre.Id_Semestre
                        WHERE element_constitutifs_aligne.Code_Promotion = :code_prom 
                        GROUP BY semestre.Id_Semestre
                        ORDER BY LENGTH(libelle_semestre) ASC";
                $stmt = $con->prepare($req);
                if ($stmt) {
                    $stmt->bindParam(':code_prom', $_SESSION['code_prom'], PDO::PARAM_STR);
                    $stmt->execute();
                    while ($ligne = $stmt->fetch()) {
            ?>
              <option value="<?php echo $ligne['Id_Semestre']; ?>">
                <?php echo $ligne['libelle_semestre']; ?>
              </option>
            <?php 
                    }
                }
            } 
            ?>
          </select>
        </div>
        
        <div class="search-container">
          <i class="fas fa-search search-icon"></i>
          <input type="text" 
                 id="search-student" 
                 class="search-input" 
                 placeholder="🔍 Rechercher un étudiant (nom, prénom, matricule)..."
                 autocomplete="off">
          <button class="clear-search" id="clear-search" style="display: none;" title="Effacer la recherche">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      
      <div class="encodage-stats">
        <div class="stat-badge">
          <i class="fas fa-users"></i> <span id="count-etudiants">0</span> Étudiants
        </div>
        <div class="stat-badge">
          <i class="fas fa-book"></i> <span id="count-ecs">0</span> ECs
        </div>
        <div class="stat-badge">
          <i class="fas fa-check-circle"></i> <span id="count-cotes">0</span> Côtes
        </div>
      </div>
    </div>
    
    <!-- Conteneur du tableau -->
    <div class="table-container-encodage">
      <div class="table-wrapper-encodage" id="div_gen_deliberation">
        <table id="table_deliberation">
          <thead>
            <!-- Sera rempli par JavaScript -->
          </thead>
          <tbody>
            <!-- Sera rempli par JavaScript -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

</section>

<!------------Menu contextuel pour les étudiants ----------------------------->
  <div id="contextMenuStudent" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.2); z-index: 10000; min-width: 220px; overflow: hidden;">
    <!-- En-tête du menu -->
    <div style="padding: 12px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.2);">
      <i class="fas fa-user-graduate me-2"></i>Actions Étudiant
    </div>
    
    <!-- Options du menu -->
    <div style="padding: 4px 0;">
      <!-- Afficher Infos -->
      <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
           onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#4CAF50'; this.style.paddingLeft='20px';" 
           onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
           onclick="afficherInfosEtudiant()">
        <i class="fas fa-info-circle" style="margin-right: 12px; color: #4CAF50; width: 20px;"></i>
        <span style="font-size: 0.95rem;">Voir Informations</span>
      </div>
      
      <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
      
      <!-- Historique Notes -->
      <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
           onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#FF9800'; this.style.paddingLeft='20px';" 
           onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
           onclick="afficherHistoriqueNotes()">
        <i class="fas fa-history" style="margin-right: 12px; color: #FF9800; width: 20px;"></i>
        <span style="font-size: 0.95rem;">Historique Notes</span>
      </div>
      
      <div style="height: 1px; background: #e9ecef; margin: 4px 12px;"></div>
      
      <!-- Générer Bulletin -->
      <div class="menu-item" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;" 
           onmouseover="this.style.background='#f8f9fa'; this.style.borderLeftColor='#2196F3'; this.style.paddingLeft='20px';" 
           onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
           onclick="genererBulletin()">
        <i class="fas fa-file-pdf" style="margin-right: 12px; color: #2196F3; width: 20px;"></i>
        <span style="font-size: 0.95rem;">Générer Bulletin</span>
      </div>
    </div>
    
    <!-- Pied du menu -->
    <div style="padding: 8px 16px; background: #f8f9fa; border-top: 1px solid #e9ecef; text-align: center;">
      <small style="color: #6c757d; font-size: 0.75rem;">
        <i class="fas fa-mouse-pointer me-1"></i>Clic droit pour plus d'options
      </small>
    </div>
  </div>

  <!------------Menu contextuel pour les Côtes (Compensation,Infos de transaction) ----------------------------->
  <div id="contextMenuCote" style="display: none; position: absolute; background: white; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.2); z-index: 10000; min-width: 250px; overflow: hidden;">
    <!-- En-tête du menu -->
    <div class="context-menu-title" style="padding: 12px 16px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); color: white; font-weight: bold; font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.2);">
      <i class="fas fa-calculator me-2"></i>Note: --/20
    </div>
    <!-- Option Compensation -->
    <div class="context-menu-compensation menu-item" style="padding: 12px 16px; cursor: pointer; display: none; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#fff3cd'; this.style.borderLeftColor='#ffc107'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="proposerCompensation()">
      <i class="fas fa-exchange-alt" style="margin-right: 12px; color: #ffc107; width: 20px;"></i>
      <div>
        <div style="font-size: 0.95rem; font-weight: 500;">Compenser cette note</div>
        <small style="color: #6c757d; font-size: 0.75rem;">Utiliser un autre EC de l'UE</small>
      </div>
    </div>
    <!-- Option Infos de transaction -->
    <div class="context-menu-info-transaction menu-item" style="padding: 12px 16px; cursor: pointer; display: none; align-items: center; transition: all 0.2s; border-left: 3px solid transparent;"
         onmouseover="this.style.background='#e3f7fa'; this.style.borderLeftColor='#17a2b8'; this.style.paddingLeft='20px';"
         onmouseout="this.style.background='white'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='16px';"
         onclick="ouvrirModalTransaction()">
      <i class="fas fa-info-circle" style="margin-right: 12px; color: #17a2b8; width: 20px;"></i>
      <div>
        <div style="font-size: 0.95rem; font-weight: 500;">Voir la transaction</div>
        <small class="context-menu-transaction-detail" style="color: #6c757d; font-size: 0.75rem; margin-top: 4px; display: block;"></small>
      </div>
    </div>
  </div>

  <!-- Modal d'infos de transaction (style moderne) -->
  <dialog id="modal_transaction_info" 
    class="shadow-lg p-0 rounded" 
    style="border: none; max-width: 500px; width: 95%; background: linear-gradient(135deg, #17a2b8 0%, #43a047 100%);">
    <div class="container" style="background: white; border-radius: 8px; overflow: hidden;">
      <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #43a047 100%); color: white; border: none; padding: 20px;">
        <div style="display: flex; align-items: center; width: 100%;">
          <i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
          <h5 class="modal-title mb-0" style="flex: 1;">Détail de la Transaction</h5>
          <button type="button" class="btn-close btn-close-white" onclick="fermerModalTransaction()" 
                  style="filter: brightness(0) invert(1);"></button>
        </div>
      </div>
      <div class="modal-body p-4" style="max-height: 60vh; overflow-y: auto;">
        <div class="row g-3">
          <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #e0f7fa15 0%, #e8f5e915 100%);">
              <div class="card-body" id="transaction_info_content">
                <!-- Squelette moderne pour transaction, rempli dynamiquement par JS -->
                <div class="text-center py-2">
                  <span id="transaction_type_icon" style="font-size:2rem; vertical-align:middle;"></span>
                  <span id="transaction_type_label" style="font-size:1.2em; font-weight:600; margin-left:8px;"></span>
                </div>
                <div class="text-center my-2">
                  <span id="transaction_ec_label" style="color:#333;"></span><br>
                  <span id="transaction_ec_intitule" class="badge" style="font-size:1em; margin-top:4px;"></span>
                </div>
                <div class="text-center my-2">
                  <span id="transaction_note_label" style="font-weight:500;"></span>
                  <span id="transaction_note_value" class="badge bg-warning" style="font-size:1.1em; margin-left:6px;"></span>
                </div>
                <div class="text-center mt-2" style="color:#607d8b; font-size:0.95em;">
                  Référence transaction : <b id="transaction_ref"></b>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 15px 20px;">
        <button type="button" class="btn btn-secondary" onclick="fermerModalTransaction()" 
                style="background: #6c757d; border: none; padding: 8px 20px;">
          <i class="fas fa-times me-2"></i>Fermer
        </button>
      </div>
    </div>
  </dialog>
  </div>

  <!------------Modal Compensation ----------------------------->
  <dialog id="modal_Compensation" 
    class="shadow-lg p-0 rounded" 
    style="border: none; max-width: 800px; width: 90%; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
    
    <div class="container" style="background: white; border-radius: 8px; overflow: hidden;">
      <div class="modal-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; border: none; padding: 20px;">
        <div style="display: flex; align-items: center; width: 100%;">
          <i class="fas fa-exchange-alt me-3" style="font-size: 1.5rem;"></i>
          <h5 class="modal-title mb-0" style="flex: 1;">Compensation de Note</h5>
          <button type="button" class="btn-close btn-close-white" onclick="fermerModalCompensation()" 
                  style="filter: brightness(0) invert(1);"></button>
        </div>
      </div>

      <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">

        <!-- Bloc fusionné UE + EC à compenser -->
        <div class="card mb-3" style="border: 2px solid #ffc107; border-radius: 10px; overflow: hidden;">
          <div class="card-header" style="background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%); border-bottom: 2px solid #ffc107; display: flex; align-items: center; justify-content: space-between;">
            <div>
              <h6 class="mb-0" style="color: #f57c00; font-weight: bold; display: inline-block;">
                <i class="fas fa-layer-group me-2"></i>Unité d'Enseignement
              </h6>
              <span class="badge ms-3" style="background: #ff9800; font-size: 0.85rem; vertical-align: middle;">
                <span id="comp_ue_code">-</span>
              </span>
            </div>
            <div style="color: #7f8c8d; font-size: 0.9rem;">
              <i class="fas fa-tag me-1"></i>Catégorie: <strong id="comp_ue_categorie">-</strong>
            </div>
          </div>
          <div class="card-body" style="background: #fffef7;">
            <div class="row g-2 align-items-center">
              <div class="col-md-6">
                <strong style="color: #2c3e50; font-size: 1.1rem;" id="comp_ue_intitule">-</strong>
                <div class="mb-2" id="comp_ec_beneficiaire_info" style="color: #2c3e50; font-size: 0.95rem;"></div>
              </div>
              <div class="col-md-3 text-center">
                <div style="font-size: 0.85rem; color: #7f8c8d; margin-bottom: 5px;">Note actuelle:</div>
                <div style="font-size: 2rem; font-weight: bold; color: #e74c3c;">
                  <span id="comp_cote_actuelle">-</span>/20
                </div>
              </div>
              <div class="col-md-3 text-end">
                <div style="font-size: 0.85rem; color: #7f8c8d; margin-bottom: 5px;">Déficit:</div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #e67e22;">
                  -<span id="comp_deficit">-</span> pts
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des ECs compensables -->
        <div class="card" style="border: 2px solid #4CAF50; border-radius: 10px;">
          <div class="card-header" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-bottom: 2px solid #4CAF50;">
            <h6 class="mb-0" style="color: #2e7d32; font-weight: bold;">
              <i class="fas fa-list-check me-2"></i>ECs Disponibles (même crédit ou même UE)
            </h6>
          </div>
          <div class="card-body" style="background: #f9fdf9; max-height: 400px; overflow-y: auto;" id="liste_ecs_compensables">
            <!-- Rempli dynamiquement par JavaScript -->
          </div>
        </div>

        <div class="alert alert-info mt-3 mb-0" style="background: #e3f2fd; border: 1px solid #90caf9; border-radius: 8px;">
          <i class="fas fa-info-circle me-2" style="color: #1976d2;"></i>
          <small style="color: #1565c0;">
            <strong>Règles de compensation :</strong><br>
            • Note à compenser entre <strong>8 et 9.99</strong><br>
            • Même UE et même crédit<br>
            • EC cédant doit avoir une note <strong>> 10/20</strong><br>
            • Seuls les ECs ayant le <strong>même nombre de crédits</strong> peuvent compenser entre eux
          </small>
        </div>
      </div>

      <div class="modal-footer" style="background: #f8f9fa; border-top: 2px solid #dee2e6;">
        <button type="button" class="btn btn-secondary" onclick="fermerModalCompensation()">
          <i class="fas fa-times me-2"></i>Annuler
        </button>
      </div>
    </div>
  </dialog>

  <!------------Modal Informations Étudiant ----------------------------->
  <dialog id="modal_Infos_Etudiant" 
    class="shadow-lg p-0 rounded" 
    style="border: none; max-width: 700px; width: 90%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    
    <div class="container" style="background: white; border-radius: 8px; overflow: hidden;">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px;">
        <div style="display: flex; align-items: center; width: 100%;">
          <i class="fas fa-user-graduate me-3" style="font-size: 1.5rem;"></i>
          <h5 class="modal-title mb-0" style="flex: 1;">Informations de l'Étudiant</h5>
          <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('modal_Infos_Etudiant').close()" 
                  style="filter: brightness(0) invert(1);"></button>
        </div>
      </div>

      <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
        <div class="row g-3">
          <!-- Photo et Identité -->
          <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-4 text-center">
                    <img id="student_photo" src="" alt="Photo" class="img-thumbnail" 
                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 3px solid #667eea;">
                    <div class="mt-2">
                      <span class="badge" id="student_status_badge" style="background: #4CAF50; padding: 8px 16px; font-size: 0.85rem;">
                        <i class="fas fa-check-circle me-1"></i>Actif
                      </span>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <h6 class="card-title mb-3" style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 8px;">
                      <i class="fas fa-id-card me-2"></i>Identité
                    </h6>
                    <div class="row g-2">
                      <div class="col-md-6">
                        <small class="text-muted d-block">Matricule</small>
                        <strong id="student_matricule">-</strong>
                      </div>
                      <div class="col-md-6">
                        <small class="text-muted d-block">Sexe</small>
                        <span id="student_sexe">-</span>
                      </div>
                      <div class="col-12">
                        <small class="text-muted d-block">Nom Complet</small>
                        <strong id="student_nom_complet" style="font-size: 1.1rem; color: #2c3e50;">-</strong>
                      </div>
                      <div class="col-md-6">
                        <small class="text-muted d-block">Date de Naissance</small>
                        <span id="student_date_naissance">-</span>
                      </div>
                      <div class="col-md-6">
                        <small class="text-muted d-block">Lieu de Naissance</small>
                        <span id="student_lieu_naissance">-</span>
                      </div>
                    </div>
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
                    <span id="student_telephone">-</span>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted d-block">Email</small>
                    <span id="student_email" style="word-break: break-all;">-</span>
                  </div>
                  <div class="col-12">
                    <small class="text-muted d-block">Adresse</small>
                    <span id="student_adresse">-</span>
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
                    <small class="text-muted d-block">Promotion</small>
                    <strong id="student_promotion" style="color: #4facfe;">-</strong>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted d-block">Année Académique</small>
                    <span id="student_annee_academique">-</span>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted d-block">Faculté</small>
                    <span id="student_faculte">-</span>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted d-block">Filière</small>
                    <span id="student_filiere">-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 15px 20px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('modal_Infos_Etudiant').close()" 
                style="background: #6c757d; border: none; padding: 8px 20px;">
          <i class="fas fa-times me-2"></i>Fermer
        </button>
      </div>
    </div>
  </dialog>

  <!-- Boîte d'alerte moderne -->
  <dialog id="boite_alert_encodage" 
    class="shadow-lg p-0 rounded" style="border: none; max-width: 420px; width: 95%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container" style="background: white; border-radius: 16px; overflow: hidden;">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <span id="alert_icon_encodage" style="font-size: 1.7rem;">
            <i class="fas fa-info-circle"></i>
          </span>
          <h5 class="modal-title mb-0" style="font-weight: 600;">Message (U.KA. @ CIUKA )</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="Fermer_Boite_Alert_Encodage()" style="filter: brightness(0) invert(1); font-size: 1.3rem;"></button>
      </div>
      <div class="modal-body p-4" style="text-align: center; min-height: 80px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div id="alert_icon_anim_encodage" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; box-shadow: 0 4px 15px rgba(102,126,234,0.18); animation: popIn 0.3s;">
          <i id="alert_icon_type_encodage" class="fas fa-info-circle" style="color: white; font-size: 2rem;"></i>
        </div>
        <h5 id="text_alert_boite_encodage" style="color: #273746; font-weight: 600; font-size: 18px; line-height: 1.5; margin: 0; word-break: break-word;">Message</h5>
      </div>
    </div>
    <style>
      @keyframes popIn {
        0% { transform: scale(0.7); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
      }
      @media (max-width: 600px) {
        #boite_alert_encodage { max-width: 98vw !important; }
      }
    </style>
  </dialog>

<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->


