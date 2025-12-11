<!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="D_Finance/img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    
                                    <img class="rounded-circle me-lg-2" src="D_Finance/API/Selection_image_profil.php" alt="" style="width: 50px; height: 50px;">

                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                  <img class="rounded-circle me-lg-2" src="D_Finance/API/Selection_image_profil.php" alt="" style="width: 50px; height: 50px;">

                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

                            <img class="rounded-circle me-lg-2" src="D_Finance/API/Selection_image_profil.php" alt="" style="width: 50px; height: 50px;">
                            <span class="d-none d-lg-inline-flex"><?php echo $_SESSION['prenom__user']." ".$_SESSION['Nom_user']; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                           
                            <a href="#" id="profil" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalProfil">Changer photo de profil</a>
                            <a href="#" id="motdepasse" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalMotdePasse">Changer mot de passe</a>

                            <a href="../Fonctions_PHP/Deconnexion.php" class="dropdown-item">Se déconnecter</a>
                        </div>
                    </div>
                </div>
            </nav>
        
<!-- Modal -->
<div class="modal fade" id="modalProfil" tabindex="-1" aria-labelledby="modalProfilLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProfilLabel">Mettre à jour votre profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulaire de mise à jour d'image -->
        <form action="D_Finance/API/API_Update_Image.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="Imageprofil" class="form-label">Télécharger une nouvelle image de profil</label>
            <input type="file" name="Imageprofil" class="form-control" accept="image/*" onchange="Apercu_Image(event)">
          </div>
          <center>
            <img id="preview" src="" alt="Image preview" style="display:none; height: 10rem; width: 10rem; border-radius: 8px; margin-top: 10px;">
          </center>
          <br>
          <button type="submit" id="Save_Image" class="btn btn-primary mb-3">
            <i class="fas fa-sync-alt"></i> &nbsp Mettre à jour
          </button>
        </form>
        <div id="messageBox" class="alert" style="display:none; margin-top: 10px; width: 100%;"></div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Mot de Passe -->
<div class="modal fade" id="modalMotdePasse" tabindex="-1" aria-labelledby="modalMotdePasseLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formMotDePasse" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalMotdePasseLabel">Changer le mot de passe</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="ancienMotDePasse" class="form-label">Mot de passe actuel</label>
            <input type="password" class="form-control" id="ancienMotDePasse" name="ancienMotDePasse" required>
          </div>
          <div class="mb-3">
            <label for="nouveauMotDePasse" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="nouveauMotDePasse" name="nouveauMotDePasse" required>
          </div>
          <div class="mb-3">
            <label for="confirmationMotDePasse" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="confirmationMotDePasse" name="confirmationMotDePasse" required>
          </div>
          <div id="messageMotDePasse" class="alert" style="display:none;"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-sync-alt"></i> &nbsp; Mettre à jour
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
    function Apercu_Image(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}

// Gestion de la soumission du formulaire dans le modal
document.querySelector('#modalProfil form').addEventListener('submit', function(event) {
    event.preventDefault();  // Empêche la soumission par défaut

    const formData = new FormData(this);

    fetch("D_Finance/API/API_Update_Image.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        const messageBox = document.getElementById('messageBox');
        
        // Affichage du message de succès ou d'erreur
        if (data.success) {
            messageBox.textContent = "Image mise à jour avec succès !";
            messageBox.className = "alert alert-success";  // Classe Bootstrap pour le succès
        } else {
            messageBox.textContent = "Erreur lors de l'enregistrement de l'image.";
            messageBox.className = "alert alert-danger";  // Classe Bootstrap pour l'erreur
        }

        messageBox.style.display = 'block';  // Afficher le message
    })
    .catch(error => {
        const messageBox = document.getElementById('messageBox');
        messageBox.textContent = "Erreur API : " + error.message;
        messageBox.className = "alert alert-danger";
        messageBox.style.display = 'block';  // Afficher le message d'erreur
    });
});

//modal pour mot de passe utilisateur

document.querySelector('#formMotDePasse').addEventListener('submit', function(event) {
  event.preventDefault();

  const ancien = document.getElementById('ancienMotDePasse').value;
  const nouveau = document.getElementById('nouveauMotDePasse').value;
  const confirmation = document.getElementById('confirmationMotDePasse').value;
  const messageBox = document.getElementById('messageMotDePasse');

  // Vérification côté client
  if (nouveau !== confirmation) {
    messageBox.textContent = "Les mots de passe ne correspondent pas.";
    messageBox.className = "alert alert-warning";
    messageBox.style.display = 'block';
    return;
  }

  const formData = new FormData();
  formData.append("ancienMotDePasse", ancien);
  formData.append("nouveauMotDePasse", nouveau);

  fetch("D_Finance/API/API_Changer_MotDePasse.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      messageBox.textContent = "Mot de passe changé avec succès.";
      messageBox.className = "alert alert-success";
    } else {
      messageBox.textContent = data.message || "Erreur lors du changement.";
      messageBox.className = "alert alert-danger";
    }
    messageBox.style.display = 'block';
  })
  .catch(error => {
    messageBox.textContent = "Erreur API : " + error.message;
    messageBox.className = "alert alert-danger";
    messageBox.style.display = 'block';
  });
});


</script>


               