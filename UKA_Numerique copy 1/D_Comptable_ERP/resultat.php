<div class="container-fluid pt-4 px-4" id="block_resultat" style="display: none;">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Recettes Prévues</h6>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#tableau_budget_resultat">Sélectionner un budget <i class="fa fa-bell bell"></i></a>
                </div>
                <div class="col-sm-12 col-xl-6 w-100 mb-2" style="max-height: 280px; overflow: auto;">
                    <table class="table table_depenses table-striped table-hover table-sm" id="table_recette_resultat" style="overflow: auto; display: block; width: 100%;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 10%;">N°</th>
                                <th style="width: 30%;">Design./Promot.</th>
                                <th style="width: 30%;">Type Recette</th>
                                <th style="width: 30%;">Montant</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Données dynamiques -->
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12 col-xl-6 border w-100">
                    <p>Total Recettes prévues &nbsp;&nbsp;&nbsp;<span class="fw-bold fs-24" style="color:red;" id="affichage_montant_recette_resultat"></span></p>
                </div>
                <a href="#" class="mt-1" style="float: right;">Ajouter une Recette <i class="fa fa-plus"></i></a>
            </div>
        </div>

        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Dépenses Prévues</h6>
                    <a href="#">Actualiser <i class="fa fa-sync"></i></a>
                </div>
                <div class="col-sm-12 col-xl-6 w-100 mb-2" style="max-height: 280px; overflow: auto;">
                    <table class="table table_depenses table-striped table-hover table-sm" id="table_depenses_resultat" style="overflow: auto; display: block; width: 100%;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 10%;">N°</th>
                                <th style="width: 30%;">Num Compte</th>
                                <th style="width: 30%;">Intitulé</th>
                                <th style="width: 30%;">Montant</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Données dynamiques -->
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12 col-xl-6 border w-100">
                    <p>Total Dépenses prévues &nbsp;&nbsp;&nbsp;<span id="affichage_montant_depense_resultat" class="fw-bold fs-24" style="color:red;"></span></p>
                </div>
                <a href="#" class="mt-1" style="float: right;">Ajouter une dépense <i class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-xl-6 w-100 bg-light rounded p-2">
        <button class="btn btn-success" id="bouton_resultat" style="display: none;" onclick="afficher_resultat()">Afficher le Résultat</button>
        <p>Résultat <span class="fw-bold fs-28" id="resultat_final"> ....</span>
            &nbsp;&nbsp;&nbsp;&nbsp;<i id="icone_resultat" class="fa fa-bell bell"></i> <span id="text_resultat" class="fw-bold">...</span>
        </p>

        <input type="number" id="montant1" style="display: none;">
        <input type="number" id="montant2" style="display: none;">
    </div>
</div>

<!-- MODALE -->
<div class="modal fade" id="tableau_budget_resultat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="width:60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choisir un Budget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" style="width: 100%; max-height: 500px; overflow-y: auto;">
                <table class="table text-start align-middle table-responsive table-bordered table-hover mb-0" id="table_resultat">
                    <thead>
                        <tr>
                            <th>Réf.</th>
                            <th>Libellé</th>
                            <th>Périodicité</th>
                            <th>Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $reponse = $con->query('SELECT t_budget.Ref_budget, t_budget.Libelle as libelle1, t_budget.Periodicite, t_budget.Annee_debut, t_budget.Annee_fin as libelle2 FROM t_budget ORDER BY ref_budget DESC');
                        while ($ligne = $reponse->fetch()) { ?>
                            <tr style="cursor: pointer;">
                                <td><?php echo $ligne['Ref_budget']; ?></td>
                                <td><?php echo $ligne['libelle1']; ?></td>
                                <td><?php echo $ligne['Periodicite']; ?></td>
                                <input type="text" hidden id="id_budget_select" value="<?php echo $ligne['Ref_budget']; ?>">
                                <td><button class="btn btn-success" title="Éditer" onclick=""><i class="fas fa-edit"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
