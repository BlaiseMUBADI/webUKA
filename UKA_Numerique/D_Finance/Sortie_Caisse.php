<style>
    #soldeCDF, #soldeUSD {
    font-size: 28px;  /* Taille du texte */
    font-weight: bold; /* Gras */
    color: green;      /* Couleur */
}
#blocSoldes .bg-light {
  transition: transform 0.2s ease-in-out;
}
#blocSoldes .bg-light:hover {
  transform: translateY(-3px);
}
</style>
<div class="container-fluid pt-4 px-4">

    <div class="row g-2 mb-2 "id="blocSoldes">
        
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fas fa-coins fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 fw-bold">Solde disponible en CDF</p>
                    <h6 class="mb-0" id="soldeCDF">Chargement...</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                <div class="ms-3">
                    <p class="mb-2 fw-bold">Solde disponible en USD</p>
                    <h6 class="mb-0" id="soldeUSD">Chargement...</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                
                <div class="ms-3">
                  
                        <strong>Année:</strong> 
                        <select id="annee">
                            <?php 
                            $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc limit 2' );
                                while ($ligne = $reponse->fetch()) {?>

                            <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                        </select>
  
                </div>
            </div>
        </div>
        
    </div>

        <div class="accordion" id="accordionExample">

    <!-- 🌟 Bloc principal : Décaissement en USD -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseBoxUSD" aria-expanded="false" onclick="changerIcon('toggleIconUSD')">
                <span id="toggleIconUSD">➕</span>&nbsp; Décaissement en USD
            </button>
        </h2>

        
        <div id="collapseBoxUSD" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
        
            <div class="accordion-body">

                <div class="form_decaissement form-container p-3 border rounded bg-light">
                    <h4 class="form-title text-danger mb-3 text-center">Décaissement en USD ($)</h4>

                    <form>
                        <div class="row g-3">

                            <!-- Numéro de pièce -->
                            <div class="col-md-3">
                                <label for="numeroPieceDecUSD" class="form-label">N° Pièce</label>
                               
                                <input type="text" class="form-control" id="numeroPieceDecUSD"disabled>
                            </div>

                            <!-- Bénéficiaire -->
                            <div class="col-md-6">
                                <label for="beneficiaireUSD" class="form-label">Bénéficiaire</label>
                                <input type="text" class="form-control" id="beneficiaireUSD"
                                    placeholder="Nom du bénéficiaire">
                            </div>

                            <!-- Montant -->
                            <div class="col-md-3">
                                <label for="montantDecUSD" class="form-label">Montant ($)</label>
                                <input type="text" class="form-control" id="montantDecUSD"
                                    placeholder="Saisir le montant en USD">
                                
                            </div>
                            <div class="col-md-12 text-center">
                                
                                <small id="erreurMontant" class="text-danger d-none">
                                    Veuillez saisir uniquement des chiffres (0-9) et un point (.)
                                </small>
                                <span id="en-lettres" class="text-danger"></span>
                                <span id="en-lettresTotal" hidden></span>
                            </div>
                                <!-- Motif -->
                            <div class="col-7">
                                <label for="motifDecaissementUSD" class="form-label">Motif Décaissement</label>
                                <textarea class="form-control" id="motifDecaissementUSD"
                                    placeholder="Saisir le motif" rows="2"></textarea>
                            </div>
                            <!-- Date -->
                            <div class="col-md-5">
                                <label for="dateDecaissementUSD" class="form-label">Date</label>
                                <input type="datetime-local" class="date-decaissement form-control"
                                    data-type="USD" id="dateDecaissementUSD">
                            </div>
                            <!-- Imputation -->
                            <div class="col-md-6">
                                <label for="ImputationUSD" class="form-label">Compte</label>
                                <select class="form-select" id="ImputationUSD">
                                    <option value="">-- Sélectionner --</option>
                                    <?php 
                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                        $data1 = $con->query($req1);
                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <option value="<?php echo $ligne1['Num_imputation']; ?>">
                                            <?php echo $ligne1['Num_imputation']." - ". $ligne1['Lib']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3 ">
                                
                                    <label for="totalDecUSD" class="form-label ms-3">Total: </label>
                                    <span id="totalDecUSD" class="form-text"></span>
                                
                            </div>
                            <!-- Bouton -->
                            <div class="col-md-3 d-flex align-items-end ">
                                
                                   
                                <button type="button" id="BtnDecaisserUSD" class="btn btn-danger btn-Action">
                                    Valider le décaissement en USD
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- 🌟 Bloc principal : Décaissement en CDF -->
    <div class="accordion-item mt-4">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseBoxCDF" aria-expanded="false" onclick="changerIcon('toggleIconCDF')">
                <span id="toggleIconCDF">➕</span>&nbsp; Décaissement en CDF
            </button>
        </h2>

       
        <div id="collapseBoxCDF" class="accordion-collapse collapse" data-bs-parent="#accordionExample">

            <div class="accordion-body">

                <div class="form_decaissement form-container p-3 border rounded bg-light">
                    <h4 class="form-title text-danger mb-3 text-center">Décaissement en CDF</h4>

                    <form>
                        <div class="row g-3">

                            <!-- Numéro de pièce -->
                            <div class="col-md-3">
                                <label for="numeroPieceDecCDF" class="form-label">N° Pièce</label>
                                
                                <input type="text" class="form-control" id="numeroPieceDecCDF" disabled>
                            </div>

                            <!-- Bénéficiaire -->
                            <div class="col-md-6">
                                <label for="beneficiaireCDF" class="form-label">Bénéficiaire</label>
                                <input type="text" class="form-control" id="beneficiaireCDF"
                                    placeholder="Nom du bénéficiaire">
                            </div>
                            <!-- Montant -->
                            <div class="col-md-3">
                                <label for="montantDecCDF" class="form-label">Montant (CDF)</label>
                                <input type="text" class="form-control" id="montantDecCDF"
                                    placeholder="Saisir le montant en CDF">
                                
                            </div>
                            <div class="col-md-12 text-center">
                                
                                <small id="erreurCDF" style="color:red;display:none;">
                                    Saisir uniquement des chiffres (0-9) et un point (.)</small>
                                <span id="en-lettresCDF"class="text-danger"></span>
                                <span id="en-lettresTotalCDF" hidden></span>
                            </div>

                            <div class="col-7">
                                <label for="motifDecaissementCDF" class="form-label">Motif Décaissement</label>
                                <textarea class="form-control" id="motifDecaissementCDF"
                                    placeholder="Saisir le motif" rows="2"></textarea>
                            </div>


                            <!-- Date -->
                            <div class="col-md-5">
                                <label for="dateDecaissementCDF" class="form-label">Date</label>
                                <input type="datetime-local" class="date-decaissement form-control"
                                    data-type="CDF" id="dateDecaissementCDF">
                            </div>

         
                            <div class="col-md-6">
                                <label for="ImputationCDF" class="form-label">Compte</label>
                                <select class="form-select" id="ImputationCDF">
                                    <option value="">-- Sélectionner --</option>
                                    <?php 
                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                        $data1 = $con->query($req1);
                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <option value="<?php echo $ligne1['Num_imputation']; ?>">
                                            <?php echo $ligne1['Num_imputation']." - ". $ligne1['Lib']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                  <label for="total" class="form-label ms-3">Total : </label>
                                    <span id="totalDecCDF" class="form-text"></span>
                               
                            </div>
                            <!-- Bouton -->
                            <div class="col-md-3">
                                
                                <button type="button" id="BtnDecaisserCDF" class="btn btn-danger btn-Action">
                                    Valider le décaissement en CDF
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

    
<script>


setTimeout(function() {
    $('#ImputationUSD').select2({ width: '100%' });
    $('#ImputationCDF').select2({ width: '100%' });
}, 500);


</script>

<script src="D_Finance/js/Decaissement.js"></script> 
