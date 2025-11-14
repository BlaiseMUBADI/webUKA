<!-- Sale & Revenue Start -->
           
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-3 px-2"data-page="paiement">
    <div class="row g-3 bg-primary p-3 rounded shadow-lg d-flex flex-wrap align-items-center">

        <!-- Année académique -->
        <div class="col-md-auto">
            <label for="annee" class="fw-bold text-white">Année académique</label>
            <select id="annee" class="form-select border-0 shadow-sm">
                <?php 
                $reponse = $con->query ('SELECT * FROM annee_academique ORDER BY Annee_debut DESC');
                while ($ligne = $reponse->fetch()) { ?>
                    <option value="<?php echo $ligne['idAnnee_Acad'];?>">
                        <?php echo $ligne['Annee_debut'] . " - " . $ligne['Annee_fin']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Faculté -->
        <div class="col-md-auto">
            <label for="filiere" class="fw-bold text-white">Faculté</label>
            <select name="filiere" id="filiere" class="form-select border-0 shadow-sm" onchange="Affichage_promotion_toutes()">
                <option value="">Sélectionner</option>
                <?php 
                $reponse = $con->query ('SELECT * FROM filiere ORDER BY IdFiliere');
                while ($ligne = $reponse->fetch()) { ?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Filtre -->
        <div class="col-md-auto">
            <label for="promotion" class="fw-bold text-white">Filtre</label>
            <select id="promotion" class="form-select border-0 shadow-sm">
                <option value="">Sélectionner</option>
            </select>
        </div>

        <!-- Lieu paiement -->
        <div class="col-md-auto">
            <label for="Lieu_paiement" class="fw-bold text-white">Lieu de paiement</label>
            <select name="Lieu_paiement" id="Lieu_paiement" class="form-select border-0 shadow-sm">
            <option value="">Sélectionner</option>

                <?php 
                $reponse = $con->query ('SELECT * FROM lieu_paiement ORDER BY idLieu_paiement');
                while ($ligne = $reponse->fetch()) { ?>
                    <option value="<?php echo $ligne['idLieu_paiement'];?>"><?php echo $ligne['Libelle_lieu'];?></option>
                <?php } ?>
            </select>
        </div>

    </div>
</div>


            <!-- Sale & Revenue End -->

            <div class="container-fluid pt-4 px-4">
    <div class="accordion" id="accordionExample">
        <!-- 🌟 Bloc affichant les taux en dollars -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoxUSD" aria-expanded="true">
                    <span id="toggleIconUSD">➕</span> Afficher les totaux en Dollars ($)
                </button>
            </h2>
            <div id="collapseBoxUSD" class="accordion-collapse collapse">
                <div class="accordion-body">
                    
                    <!-- Premier Groupe (USD) -->
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-primary">
                                    <i class="fa fa-dollar-sign text-white"></i>
                                </div>
                                <p class="mb-2" id="titreFA_USD">Total Frais Acad ($)</p>
                                <h2 class="mb-0 compteur" id="Total_FA_USD"></h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-primary">
                                    <i class="fa fa-dollar-sign text-white"></i>
                                </div>
                                <p class="mb-2" id="titre_Enrol_S1_USD">Total Enrôlement S1 ($)</p>
                                <h2 class="mb-0 compteur" id="Total_Enrol_S1_USD"></h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-primary">
                                    <i class="fa fa-dollar-sign text-white"></i>
                                </div>
                                <p class="mb-2"id="titre_Enrol_S2_USD">Total Enrôlement S2 ($)</p>
                                <h2 class="mb-0 compteur" id="Total_Enrol_S2_USD"></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Deuxième Groupe (USD) -->
                    <div class="row g-4 mt-4">
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-primary">
                                    <i class="fa fa-dollar-sign text-white"></i>
                                </div>
                                <p class="mb-2"id="titre_Enrol_S3_USD">Total Enrôlement S3 ($)</p>
                                <h2 class="mb-0 compteur" id="Total_Enrol_S3_USD"></h2>
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>

        <!-- 🌟 Bloc affichant les taux en francs congolais -->
        <div class="accordion-item mt-3">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoxCDF" aria-expanded="true">
                    <span id="toggleIconCDF">➕</span> Afficher les totaux en Francs (CDF)
                </button>
            </h2>
            <div id="collapseBoxCDF" class="accordion-collapse collapse">
                <div class="accordion-body">
                    
                    <!-- Premier Groupe (CDF) -->
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-success">
                                    <i class="fa fa-money-bill text-white"></i>
                                </div>
                                <p class="mb-2"id="titre_FA_CDF">Total Frais Acad (CDF)</p>
                                <h2 class="mb-0 compteur" id="Total_FA_CDF"></h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-success">
                                    <i class="fa fa-money-bill text-white"></i>
                                </div>
                                <p class="mb-2"id="titre_Enrol_S1_CDF">Total Enrôlement S1 (CDF)</p>
                                <h2 class="mb-0 compteur" id="Total_Enrol_S1_CDF"></h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-success">
                                    <i class="fa fa-money-bill text-white"></i>
                                </div>
                                <p class="mb-2" id="titre_Enrol_S2_CDF">Total Enrôlement S2 (CDF)</p>
                                <h2 class="mb-0 compteur" id="Total_Enrol_S2_CDF"></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Deuxième Groupe (CDF) -->
                    <div class="row g-4 mt-4">
                        <div class="col-md-4">
                            <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                                <div class="icon-box bg-success">
                                    <i class="fa fa-money-bill text-white"></i>
                                </div>
                                <p class="mb-2"id="titre_Enrol_S3_CDF">Total Enrôlement S3 (CDF)</p>
                                <h2 class="mb-0 compteur" id="Total_Enrol_S3_CDF"></h2>
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>



            <!-- Sale & Revenue End -->
            <script type="text/javascript" src="D_Finance/js/select_promo_toutes.js"></script>
<script>
    document.querySelector(".accordion-button").addEventListener("click", function () {
    var icon = document.getElementById("toggleIcon");
    icon.textContent = (icon.textContent === "➕") ? "➖" : "➕";
});

</script>