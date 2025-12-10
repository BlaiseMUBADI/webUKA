<!-- Sale & Revenue Start -->
           
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-3 px-2">
    <div class="row g-2 bg-primary p-2 d-flex flex-wrap align-items-center">
        <div class="col-md-auto p-1">
            <label for="annee" class="fw-bold text-white">Année académique</label>
            <select id="annee" class="form-select">
                <?php 
                $reponse = $con->query ('SELECT * FROM annee_academique ORDER BY Annee_debut DESC');
                while ($ligne = $reponse->fetch()) { ?>
                    <option value="<?php echo $ligne['idAnnee_Acad'];?>">
                        <?php echo $ligne['Annee_debut'] . " - " . $ligne['Annee_fin']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-auto p-1">
            <label for="filiere" class="fw-bold text-white">Faculté</label>
            <select name="filiere" id="filiere" class="form-select" onchange="Affichage_promotion_toutes()">
                <option value="">Sélectionner</option>
                <?php 
                $reponse = $con->query ('SELECT * FROM filiere ORDER BY IdFiliere');
                while ($ligne = $reponse->fetch()) { ?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-auto p-1">
            <label for="promotion" class="fw-bold text-white">Filtre</label>
            <select id="promotion" class="form-select">
                <option value="">Sélectionner</option>
            </select>
        </div>
        <div class="col-md-auto p-1">
            <label for="filiere" class="fw-bold text-white">Lieu paiement</label>
            <select name="filiere" id="Lieu_paiement" class="form-select" >
                
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
                <div class="row g-4 ligne">
                    <div class="col-sm-6 col-xl-3">
                        <div class=" bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                            <div class="icon-box bg-primary">
                                <i class="fa fa-calculator text-white"></i>
                            </div>
                            <p class="mb-2" id="titreFA">Total Frais Acad</p>
                            <i>Nombre étudiants</i>
                            <h2 class="mb-0 compteur" id="Total_FA" data-target=""></h2>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 ">
                        <div class=" bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                            <div class="icon-box bg-primary">
                                <i class="fa fa-calculator text-white"></i>
                            </div>
                            <p class="mb-2" id="titre">Total Enrôlement</p>
                            <h2 class="mb-0 compteur" id="Total_Enrol_S1" data-target=""></h2>
                        </div>
        
                    </div>
                    <div class="col-sm-6 col-xl-4 ">
                        <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                            <div class="icon-box bg-primary">
                                <i class="fa fa-calculator text-white"></i>
                            </div>
                            <p class="mb-2" id="titre2">Total Enrôlement</p>
                            <h2 class="mb-0 compteur" id="Total_Enrol_S2" data-target=""></h2>
                        </div>
        
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                            <div class="icon-box bg-primary">
                                <i class="fa fa-calculator text-white"></i>
                            </div>
                            <p class="mb-2" id="titre3">Total Enrôlement</p>
                            <h2 class="mb-0 compteur" id="Total_Enrol_S3" data-target=""></h2>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                            <div class="icon-box bg-primary">
                                <i class="fa fa-calculator text-white"></i>
                            </div>
                            <p class="mb-2" id="titre4">Total </p>
                            <h2 class="mb-0 compteur" id="Total_FA_Fc" data-target=""></h2>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bx bg-light rounded d-flex flex-column align-items-center justify-content-center p-2">
                            <div class="icon-box bg-primary">
                                <i class="fa fa-calculator text-white"></i>
                            </div>
                            <p class="mb-2" id="titre5">Total </p>
                            <h2 class="mb-0 compteur" id="Total_Enrol_S1" data-target=""></h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->
            <script type="text/javascript" src="D_Finance/js/select_promo_toutes.js"></script>
