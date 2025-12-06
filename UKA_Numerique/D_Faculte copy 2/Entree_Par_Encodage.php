
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
});
</script>

<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->


