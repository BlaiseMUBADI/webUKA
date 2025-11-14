
<style>
   #blocSoldes {
  position: sticky;
  top: 100px;
  z-index: 1000;
  background-color: transparent;
  padding-top: 8px;
  padding-bottom: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  width: 100%;
}

#blocSoldes .bg-light {
  transition: transform 0.2s ease-in-out;
}
#blocSoldes .bg-light:hover {
  transform: translateY(-3px);
}


/* Optionnel : pour éviter que le contenu passe dessous */
.accordion {
  margin-top: 10px;
}
#soldeCDF, #soldeUSD {
    font-size: 28px;  /* Taille du texte */
    font-weight: bold; /* Gras */
    color: green;      /* Couleur */
}
</style>

<div class="container-fluid pt-4 px-4">

    <!-- 🌟 Affichage des soldes -->
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
        
        <!-- 🌟 Encaissements USD -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoxUSD" aria-expanded="false" onclick="changerIcon('toggleIconUSD')">
                    <span id="toggleIconUSD">➕</span> Encaissements en USD
                </button>
            </h2>
            <div id="collapseBoxUSD" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <div class="form_encaissement form-container">
                        <h2 class="form-title text-primary">Versement en USD ($)</h2>

                        <form>
                            <div class="mb-3">
                                <?php
                                    $prefix = 'Enc_usd_';
                                    $reponse = $con->query("
                                        SELECT numero_pce 
                                        FROM numero_piece 
                                        WHERE numero_pce LIKE '$prefix%' 
                                        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                        LIMIT 1
                                    ");
                                    if ($ligne = $reponse->fetch()) {
                                        $lastNumero = $ligne['numero_pce'];
                                        $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
                                    } else {
                                        $numericPart = 1;
                                    }
                                ?>
                                <input type="text" class="form-control" id="numeroPieceUSD" value="<?php echo $numericPart; ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="Deposant_usd" class="form-label">Je soussigné</label>
                                <input type="text" class="form-control" id="Deposant_usd" placeholder="Nom du déposant">
                            </div>

                            <div class="mb-3">
                                <label for="motifVersementUSD" class="form-label">Motif du versement</label>
                                <input type="text" class="form-control" id="motifVersementUSD" placeholder="Motif du versement">
                            </div>

                            <div class="mb-3">
                                <label for="libelleServiceUSD" class="form-label">Libellé Service</label>
                                <select class="form-control" id="libelleServiceUSD">
                                     <option value="">-- Sélectionner --</option>
                                    <?php 
                                        $req1 = "SELECT Libelle AS Lib, concat('serv ',IdService) AS Id FROM service";
                                        $req2 = "SELECT concat('Fac. ', Libelle_Filiere) AS Lib, concat('fac ',IdFiliere) AS Id FROM filiere";
                                        
                                        $data1 = $con->query($req1);
                                        $data2 = $con->query($req2);
                                        
                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$ligne1['Id']}'>{$ligne1['Lib']}</option>";
                                        }
                                        while ($ligne2 = $data2->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$ligne2['Id']}'>{$ligne2['Lib']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="dateVersementUSD" class="form-label">Date du versement</label>
                                <input type="date" class="form-control" id="dateVersementUSD">
                            </div>

                            <div class="mb-3">
                                <label for="ImputationEncUSD" class="form-label">Compte</label>
                                <select class="form-control" id="ImputationEncUSD">
                                     <option value="">-- Sélectionner --</option>
                                    <?php 
                                        $data1 = $con->query("SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation");
                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$ligne1['Num_imputation']}'>{$ligne1['Num_imputation']} - {$ligne1['Lib']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="montantUSD" class="form-label">Montant ($)</label>
                                <input type="text" class="form-control" id="montantUSD" placeholder="Montant en USD" step="0.01">
                                <small id="erreurMontant" style="color:red;display:none;">Saisir uniquement des chiffres (0-9) et un point (.)</small>
                                <span id="en-lettres"></span>
                                 <span id="en-lettresTotal" hidden></span>
                            </div>

                            <button id="Encaisser_USD" type="button" class="btn btn-primary btn-Action">Encaisser USD</button>
                            <label for="total" class="form-label ms-3">Total :</label>
                            <span id="totalEncUSD" class="form-text"></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🌟 Encaissements CDF -->
        <div class="accordion-item mt-3">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoxCDF" aria-expanded="false" onclick="changerIcon('toggleIconCDF')">
                    <span id="toggleIconCDF">➕</span> Encaissements en CDF
                </button>
            </h2>
            <div id="collapseBoxCDF" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <div class="form_encaissement form-container">
                        <h2 class="form-title text-success">Versement en CDF</h2>
                        <form>
                            <div class="mb-3">
                                <?php
                                    $prefix = 'Enc_cdf_';
                                    $reponse = $con->query("
                                        SELECT numero_pce 
                                        FROM numero_piece 
                                        WHERE numero_pce LIKE '$prefix%' 
                                        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                        LIMIT 1
                                    ");
                                    if ($ligne = $reponse->fetch()) {
                                        $lastNumero = $ligne['numero_pce'];
                                        $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
                                    } else {
                                        $numericPart = 1;
                                    }
                                ?>
                                <input type="text" class="form-control" id="numeroPieceCDF" value="<?php echo $numericPart; ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="Deposant_cdf" class="form-label">Je soussigné</label>
                                <input type="text" class="form-control" id="Deposant_cdf" placeholder="Nom du déposant">
                            </div>

                            <div class="mb-3">
                                <label for="motifVersementCDF" class="form-label">Motif du versement</label>
                                <input type="text" class="form-control" id="motifVersementCDF" placeholder="Motif du versement">
                            </div>

                            <div class="mb-3">
                                <label for="libelleServiceCDF" class="form-label">Libellé Service</label>
                                <select class="form-control" id="libelleServiceCDF">
                                     <option value="">-- Sélectionner --</option>
                                    <?php 
                                        $req1 = "SELECT Libelle AS Lib, concat('serv ',IdService) AS Id FROM service";
                                        $req2 = "SELECT concat('Fac. ', Libelle_Filiere) AS Lib, concat('fac ',IdFiliere) AS Id FROM filiere";
                                        
                                        $data1 = $con->query($req1);
                                        $data2 = $con->query($req2);
                                        
                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$ligne1['Id']}'>{$ligne1['Lib']}</option>";
                                        }
                                        while ($ligne2 = $data2->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$ligne2['Id']}'>{$ligne2['Lib']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dateVersementCDF" class="form-label">Date du versement</label>
                                <input type="date" class="form-control" id="dateVersementCDF">
                            </div>
                            <div class="mb-3">
                                <label for="ImputationEncCDF" class="form-label">Compte</label>
                                <select class="form-control" id="ImputationEncCDF">
                                     <option value="">-- Sélectionner --</option>
                                    <?php 
                                        $data1 = $con->query("SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation");
                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$ligne1['Num_imputation']}'>{$ligne1['Num_imputation']} - {$ligne1['Lib']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="montantCDF" class="form-label">Montant (CDF)</label>
                                <input type="text" class="form-control" id="montantCDF" placeholder="Montant en CDF" step="0.01">
                                <small id="erreurCDF" style="color:red;display:none;">Saisir uniquement des chiffres (0-9) et un point (.)</small>
                                <span id="en-lettresCDF"></span>
                                 <span id="en-lettresTotalCDF" hidden></span>
                            </div>

                            

                            <button type="button" class="btn btn-success btn-Action">Encaisser CDF</button>
                            <label for="total" class="form-label ms-3">Total :</label>
                            <span id="totalEncCDF" class="form-text"></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🌟 Clôtures USD / CDF -->
         
          <div class="accordion-item mt-5 bg-info">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClotureCDF" aria-expanded="false">
                        <span class="text-danger">➕ Clôture des opérations de caisse (CDF) 🔒</span>
                    </button>
                </h2>
                <div id="collapseClotureCDF" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="form_cloture form-container">
                            <h2 class="form-title text-success">Clôture de la caisse (CDF)</h2>
                            <form>
                                <div class="mb-3">
                                    <label for="dateClotureCDF" class="form-label">Date de clôture</label>
                                    <input type="date" class="form-control" id="dateClotureCDF">
                                </div>
                                <div class="mb-3">
                                    <label for="soldeFinalCDF" class="form-label">Solde final (CDF)</label>
                                    <input type="text" class="form-control text-center" id="soldeFinalCDF" placeholder="Solde final de la journée" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="observationsClotureCDF" class="form-label">Observations</label>
                                    <textarea class="form-control" id="observationsClotureCDF" rows="3" placeholder="Commentaires éventuels..."></textarea>
                                </div>
                                <button type="button" class="btn btn-danger" id="btnCloturerCDF">🔒 Clôturer la caisse CDF</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item mt-3 bg-info">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClotureUSD" aria-expanded="false">
                        ➕ Clôture des opérations de caisse (USD) 🔒
                    </button>
                </h2>
                <div id="collapseClotureUSD" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="form_cloture form-container">
                            <h2 class="form-title text-success">Clôture de la caisse (USD)</h2>
                            <form>
                                <div class="mb-3">
                                    <label for="dateClotureUSD" class="form-label">Date de clôture</label>
                                    <input type="date" class="form-control" id="dateClotureUSD">
                                </div>
                                <div class="mb-3">
                                    <label for="soldeFinalUSD" class="form-label">Solde final (USD)</label>
                                    <input type="text" class="form-control text-center" id="soldeFinalUSD" placeholder="Solde final de la journée" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="observationsClotureUSD" class="form-label">Observations</label>
                                    <textarea class="form-control" id="observationsClotureUSD" rows="3" placeholder="Commentaires éventuels..."></textarea>
                                </div>
                                <button type="button" class="btn btn-success" id="btnCloturerUSD">🔒 Clôturer la caisse</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!-- même contenu que la version précédente -->
    </div>
</div>

<script>
function changerIcon(iconId) {
    var icon = document.getElementById(iconId);
    icon.textContent = (icon.textContent === "➕") ? "➖" : "➕";
}
setTimeout(function() {
    $('#libelleServiceUSD').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500); // Attend 500ms avant d'exécuter Select2

  //ajoute une barre de recherche pour filtrer sur encaissement en franc congolais.
  setTimeout(function() {
    $('#libelleServiceCDF').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500); // Attend 500ms avant d'exécuter Select2

  setTimeout(function() {
    $('#ImputationUSD').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500);
  setTimeout(function() {
    $('#ImputationCDF').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500);
  setTimeout(function() {
    $('#ImputationEncUSD').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500);
  setTimeout(function() {
    $('#ImputationEncCDF').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500);
</script>
<script src="D_Finance/js/Encaissement.js"></script> 
<script src="D_Finance/js/Cloture_Caisse.js"></script>