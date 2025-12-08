
<section class="home-section" style="height: 100%;">
  <?php require_once 'Profil_Gestion_delibe.php'; ?>
  
  <div id="encodage-container" class="" >
    
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
            <option value="rien" selected>📚 Sélectionner un Semestre</option>
            <?php 
            $req = "SELECT semestre.Id_Semestre, semestre.libelle_semestre 
                    FROM element_constitutifs_aligne 
                    JOIN semestre ON element_constitutifs_aligne.Id_Semestre = semestre.Id_Semestre
                    WHERE element_constitutifs_aligne.Code_Promotion = :code_prom 
                    GROUP BY semestre.Id_Semestre
                    ORDER BY LENGTH(libelle_semestre) ASC";
            $stmt = $con->prepare($req);
            $stmt->bindParam(':code_prom', $_SESSION['code_prom'], PDO::PARAM_STR);
            $stmt->execute();
            while ($ligne = $stmt->fetch()) {
            ?>
              <option value="<?php echo $ligne['Id_Semestre']; ?>">
                <?php echo $ligne['libelle_semestre']; ?>
              </option>
            <?php } ?>
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
      <div class="table-wrapper-encodage" id="div_gen_encodage">
        <table id="table_encodage">
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

</section>

<script>
function toggleMenuEncodage() {
  const sidebar = document.querySelector('.sidebar');
  const container = document.getElementById('encodage-container');
  const btn = document.querySelector('.toggle-menu-btn i');
  
  sidebar.classList.toggle('active');
  container.classList.toggle('fullscreen');
  
  // Changer l'icône selon l'état
  const indicator = document.getElementById('fullscreen-indicator');
  if (sidebar.classList.contains('active')) {
    btn.className = 'fas fa-angle-double-right'; // Flèche pour ouvrir
    if (indicator) indicator.style.display = 'flex'; // Afficher l'indicateur
  } else {
    btn.className = 'fas fa-bars'; // Icône menu
    if (indicator) indicator.style.display = 'none'; // Cacher l'indicateur
  }
  
  // Sauvegarder la préférence
  localStorage.setItem('menuEncodageCollapsed', sidebar.classList.contains('active'));
}

// Restaurer la préférence au chargement
document.addEventListener('DOMContentLoaded', function() {
  const isCollapsed = localStorage.getItem('menuEncodageCollapsed') === 'true';
  if (isCollapsed) {
    const sidebar = document.querySelector('.sidebar');
    const container = document.getElementById('encodage-container');
    const btn = document.querySelector('.toggle-menu-btn i');
    
    if (sidebar && container) {
      sidebar.classList.add('active');
      container.classList.add('fullscreen');
      if (btn) btn.className = 'fas fa-angle-double-right';
      
      const indicator = document.getElementById('fullscreen-indicator');
      if (indicator) indicator.style.display = 'flex';
    }
  }
  
  // Initialiser le menu contextuel pour les étudiants
  initializeContextMenu();
});

// ============ BOÎTE D'ALERTE MODERNE ============
function Ouvrir_Boite_Alert_Encodage(text_a_afficher, type = 'info') {
  const boite = document.getElementById('boite_alert_encodage');
  const texte = document.getElementById('text_alert_boite_encodage');
  const icon = document.getElementById('alert_icon_type_encodage');
  
  texte.innerText = text_a_afficher;
  
  // Changer l'icône selon le type
  if (type === 'success') {
    icon.className = 'fas fa-check-circle';
  } else if (type === 'error') {
    icon.className = 'fas fa-exclamation-circle';
  } else if (type === 'warning') {
    icon.className = 'fas fa-exclamation-triangle';
  } else {
    icon.className = 'fas fa-info-circle';
  }
  
  boite.showModal();
}

function Fermer_Boite_Alert_Encodage() {
  document.getElementById('boite_alert_encodage').close();
}

// ============ GESTION MENU CONTEXTUEL ÉTUDIANT ============
let selectedStudentMatricule = null;
let selectedStudentNom = null;

function initializeContextMenu() {
  const table = document.getElementById('table_encodage');
  const contextMenu = document.getElementById('contextMenuStudent');
  
  if (!table || !contextMenu) return;
  
  // Événement clic droit sur les lignes étudiants
  table.addEventListener('contextmenu', function(e) {
    const row = e.target.closest('tbody tr');
    if (!row) return;
    
    e.preventDefault();
    
    // Récupérer les données de l'étudiant depuis les cellules
    const nomCell = row.querySelector('.cell-editable[data-matricule]');
    if (!nomCell) return;
    
    selectedStudentMatricule = nomCell.dataset.matricule;
    
    // Extraire le nom (avant le badge matricule)
    const nameSpan = nomCell.querySelector('.student-name');
    selectedStudentNom = nameSpan ? nameSpan.textContent.trim() : 'Étudiant';
    
    // Positionner le menu contextuel
    contextMenu.style.left = e.pageX + 'px';
    contextMenu.style.top = e.pageY + 'px';
    contextMenu.style.display = 'block';
    
    // Mettre à jour le header du menu avec le nom
    const menuHeader = contextMenu.querySelector('.context-menu-header');
    if (menuHeader) {
      menuHeader.innerHTML = `<i class="fas fa-user-graduate"></i> ${selectedStudentNom}`;
    }
  });
  
  // Fermer le menu si on clique ailleurs
  document.addEventListener('click', function(e) {
    if (!contextMenu.contains(e.target)) {
      contextMenu.style.display = 'none';
    }
  });
  
  // Empêcher la fermeture si on clique dans le menu
  contextMenu.addEventListener('click', function(e) {
    e.stopPropagation();
  });
}

// Afficher les informations complètes de l'étudiant
function afficherInfosEtudiant() {
  if (!selectedStudentMatricule) return;
  
  // Masquer le menu contextuel
  document.getElementById('contextMenuStudent').style.display = 'none';
  
  // Récupérer les données via AJAX
  fetch('API_PHP/Recup_infos_etudiant.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      matricule: selectedStudentMatricule
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      populateStudentModal(data.student);
      document.getElementById('modal_Infos_Etudiant').showModal();
    } else {
      Ouvrir_Boite_Alert_Encodage('Erreur: ' + (data.message || 'Impossible de récupérer les informations'), 'error');
    }
  })
  .catch(error => {
    console.error('Erreur:', error);
    Ouvrir_Boite_Alert_Encodage('Erreur lors de la récupération des informations', 'error');
  });
}

// Remplir le modal avec les données de l'étudiant
function populateStudentModal(student) {
  // Photo
  const photoImg = document.getElementById('student_photo');
  if (photoImg) {
    photoImg.src = student.photo_url || '../Fichiers/Images/Profil.jpg';
    photoImg.onerror = function() {
      this.src = '../Fichiers/Images/Profil.jpg';
    };
  }
  
  // Identité
  document.getElementById('student_matricule').textContent = student.Matricule || '-';
  document.getElementById('student_sexe').textContent = student.Sexe || '-';
  document.getElementById('student_nom_complet').textContent = student.ident_etudiant || '-';
  document.getElementById('student_date_naissance').textContent = student.date_naissance || '-';
  document.getElementById('student_lieu_naissance').textContent = student.lieu_naissance || '-';
  
  // Contact
  document.getElementById('student_telephone').textContent = student.telephone || '-';
  document.getElementById('student_email').textContent = student.email || '-';
  document.getElementById('student_adresse').textContent = student.adresse || '-';
  
  // Académique
  document.getElementById('student_promotion').textContent = student.promotion || '-';
  document.getElementById('student_annee_academique').textContent = student.annee_academique || '-';
  document.getElementById('student_faculte').textContent = student.faculte || '-';
  document.getElementById('student_filiere').textContent = student.filiere || '-';
}

// Fermer le modal
function closeStudentModal() {
  document.getElementById('modal_Infos_Etudiant').close();
}

// Afficher l'historique des notes (à implémenter)
function afficherHistoriqueNotes() {
  document.getElementById('contextMenuStudent').style.display = 'none';
  Ouvrir_Boite_Alert_Encodage('Fonctionnalité en cours de développement: Historique des notes pour ' + selectedStudentNom, 'info');
  // TODO: Implémenter l'affichage de l'historique
}

// Générer le bulletin PDF (à implémenter)
function genererBulletin() {
  document.getElementById('contextMenuStudent').style.display = 'none';
  Ouvrir_Boite_Alert_Encodage('Génération du bulletin pour ' + selectedStudentNom + ' en cours...', 'info');
  // TODO: Implémenter la génération du bulletin PDF
}
</script>

<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->


