<!-- Sales Chart Start -->
<div class="container-fluid pt-4 px-4" id="block_depenses">
    <div class="row g-4">
        <!-- Bloc gauche -->
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Dépenses Prévues</h6>

                    <select class="form-select" style="width:190px; margin-right: 4px;" id="Id_an_acad_budget2">
                        <option>Année académique...</option>
                        <?php
                        $reponse = $con->query('SELECT * FROM annee_academique ORDER BY idAnnee_Acad DESC');
                        while ($ligne = $reponse->fetch()) { ?>
                            <option value="<?php echo $ligne['Annee_debut']; ?>">
                                <?php echo $ligne['Annee_debut'] . '-' . $ligne['Annee_fin']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tableau_budget_depenses">
                                    <i class="fas fa-piggy-bank text-primary"></i> Choisir le budget partiel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" id="depenses_generales1">
                                    💰 Budget Général
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table table_depenses table-striped table-hover" id="table_depenses_prevues" style="overflow: auto; display: block; width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">N°</th>
                                <th style="width: 15%;">Imputation</th>
                                <th style="width: 45%;">libellé/Rurique</th>
                                <th style="width: 15%;">Montant</th>
                                <th style="width: 20%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenu dynamique JS -->
                        </tbody>
                    </table>
                </div>

                <table class="table table_depenses table-striped table-hover">
                    <tr>
                        <td colspan="3">Total Dépenses Prévues</td>
                        <td colspan="1" style="text-align: left;">
                            <span id="affichage_montant_depense" class="fw-bold"></span>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Bloc droite -->
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Budget: <span id="affiche_nom_budget"></span></h6>
                    <a href="#" onclick="ajouter_depense()">Ajouter dépense <i class="fa fa-sync"></i></a>
                </div>

                <form style="display: none;" id="formulaire_depense">
                    <label class="label d-flex">Budget</label>
                    <select class="form-select" id="id_budget_select">
                        <?php
                        $reponse = $con->query('SELECT * FROM t_budget');
                        while ($ligne = $reponse->fetch()) { ?>
                            <option value="<?php echo $ligne['Ref_budget']; ?>">
                                <?php echo $ligne['Libelle']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label class="label d-flex">Compte</label>
                    <select class="form-select" id="id_compte">
                        <?php
                        $reponse = $con->query('SELECT * FROM t_imputation');
                        while ($ligne = $reponse->fetch()) { ?>
                            <option value="<?php echo $ligne['Num_imputation']; ?>">
                                <?php echo $ligne['Intitul_compte']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label class="label d-flex">Montant</label>
                    <input class="form-control" type="text" id="montant">

                    <button type="button" class="btn btn-success mt-2" id="valider_depense">
                        Valider <i class="fa fa-check"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Choix Budget -->
<div class="modal fade" id="tableau_budget_depenses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="overflow:auto;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choisir un Budget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table text-start align-middle table-responsive table-bordered table-hover mb-0" id="table_depenses_1">
                    <thead>
                        <tr>
                            <th>Réf.</th>
                            <th>Libellé</th>
                            <th>Périodicité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- À remplir dynamiquement -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
