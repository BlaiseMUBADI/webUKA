


<div class="container-fluid pt-4 px-4">
    <div class="accordion" id="accordionExample">
        
        <!-- 🌟 Bloc principal : Encaissements -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoxUSD" aria-expanded="false" onclick="changerIcon('toggleIconUSD')">
                    <span id="toggleIconUSD">➕</span> Encaissements et Décaissement en USD
                </button>
            </h2>
            <div id="collapseBoxUSD" class="accordion-collapse collapse">
                <div class="accordion-body">

                    <!-- 🌟 Sous-accordéon pour Encaissement/Décaissement -->
                    <div class="accordion" id="subAccordionUSD">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEncaissementUSD">
                                    ➕ Encaissements en $
                                </button>
                                <div class="ms-3" id="soldeUSD">Solde_disponible
                               
                                <strong id="solde_USD">Solde : 0.00 USD</strong>
                                </div>
                            </h2>

                            <div id="collapseEncaissementUSD" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <div class="form_encaissement form-container" id="zoneÀMettreÀJour">
                                        <h2 class="form-title text-primary">Versement en USD ($)</h2>

                                        <form>
                                            <div class="mb-3">
                                            <?php
                                                    // Connexion à la base
                                                    // $con = new PDO(...); // Assure-toi d'utiliser PDO avec le bon DSN
                                                    
                                                    $prefix = 'Enc_usd_';
                                                    $reponse = $con->query("SELECT numero_pce 
                                                        FROM numero_piece 
                                                        WHERE numero_pce LIKE '$prefix%' 
                                                        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC ");

                                                    if ($ligne = $reponse->fetch()) {
                                                        // Extraire la partie numérique
                                                        $lastNumero = $ligne['numero_pce'];
                                                        $numericPart = intval(substr($lastNumero, strlen($prefix)));
                                                        $numericPart++;
                                                        
                                                        // Optionnel : zero-padding à 4 chiffres
                                                        $numeroSuivant = $numericPart;
                                                    } else {
                                                        // Si aucun résultat, commencer à 0001
                                                        $numeroSuivant = '1';
                                                    }
                                                    ?>

                                                    <input type="text" class="form-control" id="numeroPieceUSD" value="<?php echo $numeroSuivant; ?>" disabled>

                                              
                                            </div>
                                            <div class="mb-3">
                                                <label for="Deposant_usd" class="form-label">Je sousigné</label>
                                                <input type="text" class="form-control" id="Deposant_usd" placeholder="Saisir le nom de la personne qui dépose l'argent">
                                            </div>
                                            <div class="mb-3">
                                                <label for="motifVersementUSD" class="form-label">Motif Versement</label>
                                                <input type="text" class="form-control" id="motifVersementUSD" placeholder="Saisir le motif">
                                            </div>
                                            <div class="mb-3">
                                                <label for="libelleServiceUSD" class="form-label">Libellé Service</label>
                                                <select class="form-control" name="" id="libelleServiceUSD">
                                                <?php 
                                                        // Requêtes de sélection
                                                        $req1 = "SELECT Libelle AS Lib, concat('serv ',IdService) AS Id FROM service";
                                                        $req2 = "SELECT concat('Fac. ', Libelle_Filiere) AS Lib, concat('fac ',IdFiliere) AS Id FROM filiere";
                                                        
                                                        // Exécution des requêtes
                                                        $data1 = $con->query($req1);
                                                        $data2 = $con->query($req2);
                                                        
                                                        // Combinaison des deux ensembles de résultats
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <option value="<?php echo $ligne1['Id']; ?>"><?php echo $ligne1['Lib']; ?></option>
                                                            <?php
                                                        }
                                                        
                                                        while ($ligne2 = $data2->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <option value="<?php echo $ligne2['Id']; ?>"><?php echo $ligne2['Lib']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dateVersementUSD" class="form-label">Date Versement</label>
                                                <input type="date" class="date_operation form-control " id="dateVersementUSD" placeholder="Saisir le motif">
                                            </div>
                                            <div class="mb-3">
                                                <label for="ImputationEncUSD" class="form-label">Imputation</label>
                                                <select class="form-control" name="" id="ImputationEncUSD">
                                                    <?php 
                                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                                        $data1 = $con->query($req1);
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $ligne1['Num_imputation']; ?>"><?php echo $ligne1['Num_imputation']." - ". $ligne1['Lib']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="montantUSD" class="form-label">Montant ($)</label>
                                                <input type="text" class="form-control" id="montantUSD" placeholder="Saisir le montant en USD" step="0.01"/>
                                                <small id="erreurMontant" style="color: red; display: none;">Veuillez saisir uniquement des chiffres (0-9) et un point (.)</small>
                                                <span id="en-lettres"></span>

                                            </div>
                                            <button id="Encaisser_USD" type="button" class="btn btn-primary btn-Action">Encaisser USD</button><span></span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDecaissementUSD">
                                    ➕ Décaissements en $
                                </button>
                                <div class="ms-3 text-danger" id="soldeUSD">
                                    Montant décaissé
                                    <strong  id="DecaissementUSD" style="display:none;"></strong> 
                                    <strong  id="DecaissementUSD_jour">0</strong>
                                </div>
                            </h2>
                            <div id="collapseDecaissementUSD" class="accordion-collapse collapse">
                                
                                <div class="accordion-body">
                                    
                                <div class="form_decaissement form-container mt-0">
                                    <h2 class="form-title text-danger">Décaissement en USD ($)</h2>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="numeroPieceDecUSD" class="form-label">N° Pièce</label>

                                                <?php
                                                    // Connexion à la base
                                                    // $con = new PDO(...); // Assure-toi d'utiliser PDO avec le bon DSN

                                                    $prefix = 'Dec_usd_';
                                                    $reponse = $con->query("
                                                        SELECT numero_pce 
                                                        FROM numero_piece 
                                                        WHERE numero_pce LIKE '$prefix%' 
                                                        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                                        LIMIT 1
                                                    ");

                                                    if ($ligne = $reponse->fetch()) {
                                                        // Extraire la partie numérique
                                                        $lastNumero = $ligne['numero_pce'];
                                                        $numericPart = intval(substr($lastNumero, strlen($prefix)));
                                                        $numericPart++;
                                                        
                                                        // Optionnel : zero-padding à 4 chiffres
                                                        $numeroSuivant = $numericPart;
                                                    } else {
                                                        // Si aucun résultat, commencer à 0001
                                                        $numeroSuivant = '1';
                                                    }
                                                    ?>

                                                <input type="text" class="form-control" id="numeroPieceDecUSD" value="<?php echo $numeroSuivant; ?>" disabled>
                                            </div>

                                            

                                            <div class="col-md-6 mb-3">
                                                <label for="beneficiaireUSD" class="form-label">Je sousigné</label>
                                                <input type="text" class="form-control" id="beneficiaireUSD" placeholder="Nom du bénéficiaire">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="ImputationUSD" class="form-label">Imputation</label>
                                                <select class="form-control" name="" id="ImputationUSD">
                                                    <?php 
                                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                                        $data1 = $con->query($req1);
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $ligne1['Num_imputation']; ?>"><?php echo $ligne1['Num_imputation']."-". $ligne1['Lib']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="montantDecUSD" class="form-label">Montant ($)</label>
                                                <input type="text" class="form-control" id="montantDecUSD" placeholder="Saisir le montant en USD">
                                                <small id="erreur" style="color: red; display: none;">Veuillez saisir uniquement des chiffres (0-9) et un point (.)</small>
                                                <span id="lettresDecUSD"></span>
                                                
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="dateDecaissementUSD" class="form-label">Date</label>
                                                <input type="date" class="date-decaissement form-control" data-type="USD" id="dateDecaissementUSD">
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="motifDecaissementUSD" class="form-label">Motif Décaissement</label>
                                                <textarea class="form-control" id="motifDecaissementUSD" placeholder="Saisir le motif" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <button type="button"id="BtnDecaisserUSD" class="btn btn-danger">Décaisser USD</button>
                                    </form>

                                </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- 🌟 Bloc principal : Encaissements en CDF -->
        <div class="accordion-item mt-3">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBoxCDF" aria-expanded="false" onclick="changerIcon('toggleIconCDF')">
                    <span id="toggleIconCDF">➕</span> Encaissements et Décaissement en CDF
                </button>
            </h2>
            <div id="collapseBoxCDF" class="accordion-collapse collapse">
                <div class="accordion-body">

                    <!-- 🌟 Sous-accordéon pour Encaissement/Décaissement -->
                    <div class="accordion" id="subAccordionCDF">
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEncaissementCDF">
                                    ➕ Encaissements en CDF
                                </button>
                                 <div class="ms-3" id="soldeCDF"> Solde disponible
                                    <strong id="solde_CDF">Solde : 0.00 CDF</strong> 
                                </div>
                            </h2>
                            <div id="collapseEncaissementCDF" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <div class="form_encaissement form-container">
                                        <h2 class="form-title text-success">Versement en CDF</h2>
                                        <form>
                                            <div class="mb-3">
                                                <label for="numeroPieceCDF" class="form-label">Numéro Pièce</label>
                                                <?php
                                                    // Connexion à la base
                                                    // $con = new PDO(...); // Assure-toi d'utiliser PDO avec le bon DSN

                                                    $prefix = 'Enc_cdf_';
                                                    $reponse = $con->query("
                                                        SELECT numero_pce 
                                                        FROM numero_piece 
                                                        WHERE numero_pce LIKE '$prefix%' 
                                                        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                                        LIMIT 1
                                                    ");

                                                    if ($ligne = $reponse->fetch()) {
                                                        // Extraire la partie numérique
                                                        $lastNumero = $ligne['numero_pce'];
                                                        $numericPart = intval(substr($lastNumero, strlen($prefix)));
                                                        $numericPart++;
                                                        
                                                        // Optionnel : zero-padding à 4 chiffres
                                                        $numeroSuivant = $numericPart;
                                                    } else {
                                                        // Si aucun résultat, commencer à 1
                                                        $numeroSuivant = '1';
                                                    }
                                                    ?>

                                                    <input type="text" class="form-control" id="numeroPieceCDF" value="<?php echo $numeroSuivant; ?>" disabled>
                                                
                                            </div>
                                            <div class="mb-3">
                                                <label for="Deposant_cdf" class="form-label">Je sousigné</label>
                                                <input type="text" class="form-control" id="Deposant_cdf" placeholder="Saisir le nom de la personne qui dépose l'argent">
                                            </div>
                                            <div class="mb-3">
                                                <label for="motifVersementCDF" class="form-label">Motif Versement</label>
                                                <input type="text" class="form-control" id="motifVersementCDF" placeholder="Saisir le motif">
                                            </div>
                                            <div class="mb-3">
                                                <label for="libelleServiceCDF" class="form-label">Libellé Service</label>
                                                <select class="form-control" name="" id="libelleServiceCDF">
                                                <?php 
                                                        // Requêtes de sélection
                                                        $req1 = "SELECT Libelle AS Lib, concat('serv ',IdService) AS Id FROM service";
                                                        $req2 = "SELECT concat('Fac. ', Libelle_Filiere) AS Lib, concat('fac ',IdFiliere) AS Id FROM filiere";
                                                        
                                                        // Exécution des requêtes
                                                        $data1 = $con->query($req1);
                                                        $data2 = $con->query($req2);
                                                        
                                                        // Combinaison des deux ensembles de résultats
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <option value="<?php echo $ligne1['Id']; ?>"><?php echo $ligne1['Lib']; ?></option>
                                                            <?php
                                                        }
                                                        
                                                        while ($ligne2 = $data2->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <option value="<?php echo $ligne2['Id']; ?>"><?php echo $ligne2['Lib']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="ImputationEncCDF" class="form-label">Imputation</label>
                                                <select class="form-control" id="ImputationEncCDF">
                                                    <?php 
                                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                                        $data1 = $con->query($req1);
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $ligne1['Num_imputation']; ?>"><?php echo $ligne1['Num_imputation']." - ". $ligne1['Lib']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="montantCDF" class="form-label">Montant (CDF)</label>
                                                <input type="text" class="form-control" id="montantCDF" placeholder="Saisir le montant en CDF"step="0.01"/>
                                                <small id="erreurCDF" style="color: red; display: none;">Veuillez saisir uniquement des chiffres (0-9) et un point (.)</small>
                                                <span id="en-lettresCDF"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dateVersementCDF" class="form-label">Date Versement</label>
                                                <input type="date" class="date_operation form-control " id="dateVersementCDF" placeholder="Saisir le motif">
                                            </div>
                                            <button type="button" class="btn btn-success btn-Action">Encaisser CDF</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDecaissementCDF">
                                    ➕ Décaissements en CDF
                                </button>
                                <div class="ms-3 text-danger" id="soldeCDF">Montant décaissé
                                    <strong  id="DecaissementCDF"style="display:none;"> 0.00 Fc</strong> 
                                    <strong  id="DecaissementCDF_jour">- 0.00 Fc</strong> 
                                </div>
                            </h2>
                            <div id="collapseDecaissementCDF" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                <div class="form_decaissement form-container mt-0">
                                    <h2 class="form-title text-danger">Décaissement en CDF</h2>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="numeroPieceDecCDF" class="form-label">N° Pièce</label>
                                                <?php
                                                    // Connexion à la base
                                                    // $con = new PDO(...); // Assure-toi d'utiliser PDO avec le bon DSN

                                                    $prefix = 'Dec_cdf_';
                                                    $reponse = $con->query("
                                                        SELECT numero_pce 
                                                        FROM numero_piece 
                                                        WHERE numero_pce LIKE '$prefix%' 
                                                        ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                                        LIMIT 1
                                                    ");

                                                    if ($ligne = $reponse->fetch()) {
                                                        // Extraire la partie numérique
                                                        $lastNumero = $ligne['numero_pce'];
                                                        $numericPart = intval(substr($lastNumero, strlen($prefix)));
                                                        $numericPart++;
                                                        
                                                        // Optionnel : zero-padding à 4 chiffres
                                                        $numeroSuivant = $numericPart;
                                                    } else {
                                                        // Si aucun résultat, commencer à 0001
                                                        $numeroSuivant = '1';
                                                    }
                                                    ?>
                                                <input type="text" class="form-control" id="numeroPieceDecCDF" value="<?php echo $numeroSuivant; ?>" disabled>
                                            </div>

                                            

                                            <div class="col-md-6 mb-3">
                                                <label for="beneficiaireCDF" class="form-label">Je sousigné</label>
                                                <input type="text" class="form-control" id="beneficiaireCDF" placeholder="Nom du bénéficiaire">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="ImputationCDF" class="form-label">Imputation</label>
                                                <select class="form-control" id="ImputationCDF">
                                                    <?php 
                                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                                        $data1 = $con->query($req1);
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $ligne1['Num_imputation']; ?>"><?php echo $ligne1['Num_imputation']." - ". $ligne1['Lib']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="montantDecCDF" class="form-label">Montant (CDF)</label>
                                                <input type="text" class="form-control" id="montantDecCDF" placeholder="Saisir le montant en CDF">
                                                <small id="erreurdDecCDF" style="color: red; display: none;">Veuillez saisir uniquement des chiffres (0-9) et un point (.)</small>
                                                <span id="lettresDecCDF"></span>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="dateDecaissementCDF" class="form-label">Date</label>
                                                <input type="date" class="date-decaissement form-control" data-type="CDF" id="dateDecaissementCDF">
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="motifDecaissementCDF" class="form-label">Motif Décaissement</label>
                                                <textarea class="form-control" id="motifDecaissementCDF" placeholder="Saisir le motif" rows="3" required></textarea>
                                            </div>
                                        </div>

                                        <button type="button"id="BtnDecaisserCDF" class="btn btn-danger">Décaisser CDF</button>
                                    </form>


                                </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
<!-- 🌟 Bloc Clôture de caisse -->
            <div class="accordion-item mt-5 bg-info">
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
            <!-- 🌟 Bloc Clôture de caisse en CDF -->
            <div class="accordion-item mt-3 bg-info">
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


    </div>
</div>

<!-- Script JS -->
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
  
  document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split('T')[0];

    const dateUSD = document.getElementById("dateVersementUSD");
    if (dateUSD) dateUSD.value = today;

    const dateCDF = document.getElementById("dateVersementCDF");
    if (dateCDF) dateCDF.value = today;

    const dateDecCDF = document.getElementById("dateDecaissementCDF");
    if (dateDecCDF) dateDecCDF.value = today;
    const dateDecUSD = document.getElementById("dateDecaissementUSD");
    if (dateDecUSD) dateDecUSD.value = today;
    const dateclotureCDF = document.getElementById("dateClotureCDF");
    if (dateclotureCDF) dateclotureCDF.value = today;
    const dateclotureUSD = document.getElementById("dateClotureUSD");
    if (dateclotureUSD) dateclotureUSD.value = today;
});

</script>

<!-- <script src="D_Finance/js/Caisse_Principale.js"></script>-->
<script src="D_Finance/js/Encaissement.js"></script>
<script src="D_Finance/js/Cloture_Caisse.js"></script>
