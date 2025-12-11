
      <style>
/* Style de base pour les blocs */
.bloc {
    padding: 10px;
    border-radius: 5px;
    background-color: #2c3e50; /* Couleur de fond par défaut */
    transition: all 0.3s ease;
    color: #ffffff!important; /* Texte blanc */
}

/* Effet de survol */
.bloc:hover {
    background-color: #3b4f75;
    transform: scale(1.05);
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Bloc actif sélectionné */
.bloc.active {
    background-color:rgb(232, 22, 22)!important; /* Bleu vif Bootstrap */
    color:rgb(25, 25, 25);
    transform: scale(1.08);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

/* Style pour les éléments du menu */
.nav-item {
    transition: background-color 0.3s ease, transform 0.2s ease, color 0.3s ease;
    padding: 8px 2px;
}

/* Survol des items */
.nav-item:hover {
    background-color:rgb(5, 22, 61)!important;
    transform: scale(1.05);
    cursor: pointer;
    color: #f0a500;
}

/* Item de menu actif */
.nav-item.active {
    background-color: #1e2a47!important;
    color: #ffff;
}

/* Lien dans le menu */
.nav-link {
    color: white;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #f0a500;
    background-color:rgb(5, 22, 61)!important;

}
</style>

     
       <script>

document.addEventListener('DOMContentLoaded', function () {
    var blocs = document.querySelectorAll('.bloc');

    blocs.forEach(function(bloc) {
        bloc.addEventListener('click', function(e) {
            // Empêcher la propagation du clic (utile pour dropdowns)
            e.stopPropagation();

            // Supprimer 'active' de tous les blocs
            blocs.forEach(function(b) {
                b.classList.remove('active');
            });

            // Ajouter 'active' au bloc cliqué
            this.classList.add('active');
        });
    });
});


       </script>
                     <?php
                        // Vérification de la catégorie
                        $categorie = isset($_SESSION['Categorie']) ? $_SESSION['Categorie'] : '';
                    ?>
        <div class="sidebar pe-4 pb-3 "style="background-color: rgb(5, 23, 47); color: white;">
            <nav class="navbar  navbar-light" >
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h4 class="text-primary">Numérique U.KA.</h4>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="D_Finance/API/Selection_image_profil.php" alt="" style="width: 50px; height: 50px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $_SESSION['prenom__user']." ".$_SESSION['Nom_user']; ?></h6>
                        
                       <i> <span> <?php  echo $_SESSION['Categorie']; ?></span></i>
                    </div>
                </div>

            <?php if ($categorie == "Caissière principale") : ?>
                <div class="navbar-nav w-100 ">
                   
                    <a href="Page_Principale_Finance.php?page=Dash_Board_Caisse" class="nav-item nav-link bloc"><i class="fa fa-exchange-alt me-2 text-primary"></i>Entrée</a>
                    <a href="Page_Principale_Finance.php?page=AutoValide" class="nav-item nav-link bloc mt-2"><i class="fa fa-exchange-alt me-2 text-primary"></i>Sortie</a>
                    <a href="Page_Principale_Finance.php?page=Change" class="nav-item nav-link bloc mt-2"><i class="fa fa-exchange-alt me-2 text-primary"></i>Change</a>
                    <div class="nav-item  dropdown">
                        <a href="#" class="nav-link dropdown-toggle  bloc" data-bs-toggle="dropdown"><i class="fa fa-book me-2 text-primary "></i>Journal de Caisse</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-money-bill-wave me-2"></i>Encaissements du Jour</a>
                            <a href="Page_Principale_Finance.php?page=Gerer_Decaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-arrow-down me-2"></i>Décaissements du Jour </a>
                            <a href="Page_Principale_Finance.php?page=Rapport_guichet" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-university me-2"></i>Opérations aux Guichets </a>
                        
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle  bloc" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2 text-primary "></i>Rapport & Etats</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-book me-2"></i>Livre de Caisse</a>
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>   
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa-shield-alt me-2 text-danger"></i>Sécurité/Contrôle</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-user-shield me-2 text-primary"></i>Rôles et Autorisations
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-bell bell me-2"></i>Alerts et Notifications</a>
                        </div>
                    </div>                  
                </div>
                <?php elseif ($categorie == "Administrateur de Budget") : ?>
                <div class="navbar-nav w-100 ">
                   
                    <a href="Page_Principale_Finance.php?page=Dash_Board" class="nav-item nav-link bloc"><i class="fa fa me-1 text-primary">📊</i> Tableau de Bord</a>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">⚙️</i> Autorisations</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=autorisation" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-plus-circle text-primary me-2"></i>Enregistrement</a>
                            <a href="Page_Principale_Finance.php?page=Autoriser" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-check-circle text-primary me-2"></i>Validation </a>
                            <a href="Page_Principale_Finance.php?page=Suivi_autorisation" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-clock me-2"></i>Suivi status </a>
                            <a href="Page_Principale_Finance.php?page=Modif" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-sync-alt me-2"></i>Modifier Autoriz </a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">💰</i> Opérations Fin.</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-money-bill-wave me-2"></i>Encaissements du Jour</a>
                            <a href="Page_Principale_Finance.php?page=Gerer_Decaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-arrow-down me-2"></i>Décaissements du Jour </a>
                            <a href="Page_Principale_Finance.php?page=" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-university me-2"></i>Opérations aux Guichets </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">🧾</i>Doc Comptables</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-book me-2"></i>Livre de Caisse
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>   
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">📒</i> Gestion Budg.</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Planification_Budget" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Visualiser le Budget
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="me-2 text-primary">💵 </i> Suivi Frais Acad.</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=ListePaie_Etudiant" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Contrôle
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa me-2 text-danger">🔒</i>Sécurité & Accès</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-user-shield me-2 text-primary"></i>Gestion des Utilisateurs</a>
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-bell bell me-2"></i>Alerts et Notifications</a>
                        </div>
                    </div>                  
                </div>
                <?php elseif ($categorie == "Recteur") : ?>
                <div class="navbar-nav w-100 ">
                   
                    <a href="Page_Principale_Finance.php?page=Dash_Board" class="nav-item nav-link bloc"><i class="fa fa me-2 text-primary">📊</i> Tableau de Bord</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">💰</i> Autorisations</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Autoriser" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-arrow-down me-2"></i>Validation </a>
                            <a href="Page_Principale_Finance.php?page=Suivi_autorisation" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-university me-2"></i>Suivi status </a>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">💰</i> Opérations Fin.</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-money-bill-wave me-2"></i>Encaissements du Jour</a>
                            <a href="Page_Principale_Finance.php?page=Gerer_Decaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-arrow-down me-2"></i>Décaissements du Jour </a>
                            <a href="Page_Principale_Finance.php?page=" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-university me-2"></i>Opérations aux Guichets </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">🧾</i>Doc Comptables</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-book me-2"></i>Livre de Caisse
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>   
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">📒</i> Gestion Budg.</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Planification_Budget" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Visualiser le Budget
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>
                                    
                </div> 
                
                <?php elseif ($categorie == "Comptable") : ?>
                <div class="navbar-nav w-100 ">
                   
                    <a href="Page_Principale_Finance.php?page=Dash_Board" class="nav-item nav-link bloc"><i class="fa fa me-2 text-primary">📊</i> Tableau de Bord</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">💰</i> Opérations Fin.</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-money-bill-wave me-2"></i>Encaissements du Jour</a>
                            <a href="Page_Principale_Finance.php?page=Gerer_Decaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-arrow-down me-2"></i>Décaissements du Jour </a>
                            <a href="Page_Principale_Finance.php?page=Rapport_guichet" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-university me-2"></i>Opérations aux Guichets </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">🧾</i>Doc Comptables</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-book me-2"></i>Livre de Caisse
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>   
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">📒</i> Gestion Budg.</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Planification_Budget" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Visualiser le Budget
                            <a href="Page_Principale_Finance.php?page=imputation" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-balance-scale me-2"></i>Imputation</a>
                            <a href="Page_Principale_Finance.php?page=repartition" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-balance-scale me-2"></i>Repartition</a>
                            <a href="Page_Principale_Finance.php?page=rubriques" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-balance-scale me-2"></i>Rubriques</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="me-2 text-primary">💵 </i> Suivi Frais Acad.</a>
                       
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=ListePaie_Etudiant" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Contrôle</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa me-2 text-danger">🔒</i>Sécurité & Accès</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-user-shield me-2 text-primary"></i>Gestion des Utilisateurs</a>
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-bell bell me-2"></i>Alerts et Notifications</a>
                        </div>
                    </div>                  
                </div>

                <?php elseif ($categorie == "Contrôleur interne") : ?>
                <div class="navbar-nav w-100 ">
                   
                    <a href="Page_Principale_Finance.php?page=Dash_Board" class="nav-item nav-link bloc"><i class="fa fa me-2 text-primary">📊</i> Tableau de Bord</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">💰</i> Opérations Fin.</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-money-bill-wave me-2"></i>Encaissements du Jour</a>
                            <a href="Page_Principale_Finance.php?page=Gerer_Decaissement" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-arrow-down me-2"></i>Décaissements du Jour </a>
                        <!--    <a href="Page_Principale_Finance.php?page=Rapport_guichet" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-university me-2"></i>Opérations aux Guichets </a>-->
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">🧾</i>Doc Comptables</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-book me-2"></i>Livre de Caisse
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-file-alt me-2"></i>Rapport de Caisse</a>
                        </div>
                    </div>   
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa fa me-2 text-primary">📒</i> Gestion Budg.</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Planification_Budget" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Visualiser le Budget
                            <a href="Page_Principale_Finance.php?page=imputation" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-balance-scale me-2"></i>Imputation</a>
                            <a href="Page_Principale_Finance.php?page=repartition" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-balance-scale me-2"></i>Repartition</a>
                            <a href="Page_Principale_Finance.php?page=rubriques" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-balance-scale me-2"></i>Rubriques</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="me-2 text-primary">💵 </i> Suivi Frais Acad.</a>
                       
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=ListePaie_Etudiant" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa me-2">👁️</i>Contrôle</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                       <a href="#" class="nav-link dropdown-toggle text-white bloc" data-bs-toggle="dropdown"><i class="fa me-2 text-danger">🔒</i>Sécurité & Accès</a>
                       
                       <div class="dropdown-menu bg-transparent border-0">
                            <a href="Page_Principale_Finance.php?page=Gerer_Encaissement_Dec" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-user-shield me-2 text-primary"></i>Gestion des Utilisateurs</a>
                            <a href="Page_Principale_Finance.php?page=RapportCaisse" class=" dropdown-item text-white a_menu" id="liste" style="color:white;"><i class="fa fa-bell bell me-2"></i>Alerts et Notifications</a>
                        </div>
                    </div>                  
                </div>
                <?php elseif ($categorie == "Assistant AB") : ?>
                <div class="navbar-nav w-100 ">
                   
                    <a href="Page_Principale_Finance.php?page=autorisation" class="nav-item nav-link bloc"><i class="fa fa me-1 text-primary">📊</i> Saisie bon</a>
                    <a href="Page_Principale_Finance.php?page=Afficher" class="nav-item nav-link bloc mt-2"><i class="fa fa me-1 text-primary">📊</i> Afficher</a>
                    
                    
                    
                   
                                
                </div>
                
            <?php endif; ?>
            </nav>
        </div>
        <!-- Sidebar End -->
        