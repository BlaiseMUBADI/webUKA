<div class="container-fluid pt-4 px-4 table-responsive " style="display: none;" id="block_budget">
    
              <div class="bg-light" style="border:0px solid red; border-radius: 15px;">

               <table class="table" id="table1" style="border-radius: 15px; overflow: hidden;">     <tr>
                       <td >
                        
                          <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">Actions <i class=""></i></button> 
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#"><i class="fas fa-piggy-bank text-primary"></i> Budgets</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="nouveau_budget()"><i class="fas fa-file text-primary" ></i> Nouveau budget</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fas fa-search text-primary" ></i> Rechercher</a></li>
                          </ul>
                         
                       
                        <select class="form-select" style="display: inline-block; float: left; width:130px; margin-right: 4px; overflow-y: auto; " id="Id_an_acad_budget1"> 
                          <?php
                             
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['Annee_debut'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                        </select> <h5 style="text-align: center; display: inline-block;"> &nbsp &nbsp &nbsp &nbsp &nbsp Budgets</h5>
                       </td>
                   </tr>
                   <tr>

                       <td>
                        <div style="width: 100%; max-height: 280px; overflow: auto;">
                            <table class="table text-start align-middle table-responsive  table-hover  mb-0 table-sm" id="table_1">
                               <thead class="table-light">
                                <tr>
                                    <th>Réf Budget1</th><th>Libellé</th><th>Périodicité</th><th>Année Acad.</th><th>Service Conserné</th><th></th>
                                </tr>
                              </thead>
                                <?php 
                      
                        $reponse = $con->query ('SELECT t_budget.Ref_budget, t_budget.Libelle as libelle1, t_budget.Periodicite, t_budget.Annee_debut,t_budget.Annee_fin, service.Libelle as libelle2 FROM t_budget, service where t_budget.Idservice=service.IdService order by Annee_debut ASC' );
                        $i=1;
                            while ($ligne = $reponse->fetch()) {?>
                                <tr>
                                  <td><?php echo $i++; ?></td>
                                  <td><?php echo $ligne['libelle1'];?></td>
                                  <td><?php echo $ligne['Periodicite'];?></td>
                                  <td><?php echo $ligne['Annee_debut'];?> - <?php echo $ligne['Annee_fin'];?></td>
                                  <td><?php echo $ligne['libelle2'];?></td>
                                  <input type="text" name="" hidden id="id_budget" value="<?php echo $ligne['Ref_budget'];?>">
                                  <td><button class="btn btn-primary" title="éditer1" id="editer_budget" onclick="
                                  "><i class="fas fa-edit"></i> </button></td>
                                </tr>
                                <?php  } ?>
                            </table> 
                            </div>
                       </td>
                   </tr>
                   <tr>
                       <td>
                       </td>
                   </tr>
               </table>
               </div>
</div>

<!-- le formulaire qui sort -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Enregistrement du nouveau budget</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulaire -->
          <form id="formEnregistrement">
            <div class="mb-2">
              <label for="libelle" class="form-label">Libellé Budget</label>
              <input type="text" class="form-control" id="libelle" required>
            </div>
            <div class="mb-2">
              <label for="" class="form-label">Description du Budget</label>
              <textarea id="description"  class="form-control" placeholder="Écrivez la description ici" rows="3"></textarea>
            </div>
            <div class="mb-2">
              <label for="zone3" class="form-label">Périodicité</label>
              <select class="form-select" id="Periodicite">
                <option value="Mensuelle">Mensuelle</option>
                <option value="Bimestrielle">Bimestrielle</option>
                <option value="Trimestrielle">Trimestrielle</option>
                <option value="Quadrimestrielle">Quadrimestrielle</option>
                <option value="Semestrielle">Semestrielle</option>
                <option value="Annuelle">Annuelle</option>
              </select>
            </div>
            <div class="mb-2" >
              <div class="col-md-5" style="display: inline-block;">
                <label for="zone4" class="form-label">Année du début</label>
              <input type="text" class="form-control" id="Annee_debut" required>
              </div> &nbsp &nbsp &nbsp &nbsp &nbsp
              <div class="col-md-5" style="display: inline-block;">
                <label for="zone4" class="">Année de fin</label>
              <input type="text" class="form-control"  id="Annee_fin" required>
              </div>
            </div>
           
            <div class="mb-3">
              <label for="zone4" class="form-label">Service conserné</label>
              <select class="form-select" id="service">
               <?php 
                        
                        $reponse = $con->query ("SELECT 
    IdService AS id, 
    CONCAT('Ser. ', Libelle) AS libelle, 
    'service' AS type 
FROM 
    service

UNION ALL

SELECT 
    IdFiliere AS id, 
    CONCAT('Fac. ', Libelle_Filiere) AS libelle, 
    'filiere' AS type 
FROM 
    filiere;");
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['id'];?>"><?php echo $ligne['libelle'];?></option> <?php } ?>
                    </select>
                    <input type="text" hidden name=""  id="id_budge">

                 
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary" id="enregistrer_budget" onclick="enregistrer_budget()">Enregistrer</button>
        </div>
      </div>
    </div>
  </div>









<div class="container-fluid pt-4 px-4" id="block_budget_general" style="display: none;">
  <div class="col-sm-12 col-xl-6 w-100 bg-primary  p-2 mb-2" style="border-radius:5px 5px 0px 0px;">
        <p id="nom_budget" style="font-weight:bold; font-size:18px;"><span class="fw-bold fs-28" id="resultat_final"> ....</span>
            &nbsp;&nbsp;&nbsp;&nbsp;<i id="icone_resultat" class="fa fa-bell bell"></i> <span id="text_resultat" class="fw-bold">...</span>
        </p>

    </div>

     <?php
    $categorie = isset($_SESSION['Categorie']) ? $_SESSION['Categorie'] : '';
    $masquerAccordeon = ($categorie == "Administrateur de Budget"); // Masquer pour Administrateur de Budget
    $afficherAccordeonComptable = ($categorie == "Comptable"); // Afficher pour Comptable
?>

<div class="row g-4" id="parentAccordion">
    <!-- Dépenses -->
    <div class="col-sm-12 col-xl-12" 
        <?php 
            echo $masquerAccordeon ? 'style="display: none;"' : ($afficherAccordeonComptable ? 'style="display: block;"' : ''); 
        ?>> 
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseBoxCDF" aria-expanded="false" onclick="changerIcon('toggleIconDepense', 'collapseBoxCDF')">
                    <span id="toggleIconDepense">➕</span> Dépenses de Fonctionnement
                </button>
            </h2>
            <div id="collapseBoxCDF" class="accordion-collapse collapse" data-bs-parent="#parentAccordion">
                <div class="accordion-body">
                    <div class="col-sm-12 col-xl-6 w-100 mb-2" style="overflow: auto;">
                        <table class="table table_depenses table-striped table-sm" id="table_depenses_generale" style="width: 100%;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Compte</th>
                                    <th style="width: 60%;">Intitulé</th>
                                    <th style="width: 20%;">Montant en USD</th>
                                    <th style="width: 20%;">% Budget</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-xl-6 border w-100">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recettes -->
    <div class="col-sm-12 col-xl-12 mt-1" 
        <?php 
            echo $masquerAccordeon ? 'style="display: none;"' : ($afficherAccordeonComptable ? 'style="display: block;"' : ''); 
        ?>> 
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseDecaissementCDF" aria-expanded="false" onclick="changerIcon('toggleIconRecette', 'collapseDecaissementCDF')">
                    <span id="toggleIconRecette">➕</span> Recettes de Fonctionnement
                </button>
            </h2>
            <div id="collapseDecaissementCDF" class="accordion-collapse collapse" data-bs-parent="#parentAccordion">
                <div class="accordion-body">
                    <div class="col-sm-12 col-xl-6 w-100 mb-2" style="overflow: auto;">
                        <table class="table table_depenses table-striped table-hover table-sm" id="table_recette_generale" style="width: 100%;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Compte</th>
                                    <th style="width: 60%;">Intitulé</th>
                                    <th style="width: 20%;">Montant en USD</th>
                                    <th style="width: 20%;">% Budget</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-xl-6 border w-100">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Resultat d'Exploitation -->
<div class="col-sm-12 col-xl-12 mt-4"> 
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseResultatExploitation" aria-expanded="false"
                onclick="changerIcon('toggleIconResultat', 'collapseResultatExploitation')">
                <span id="toggleIconResultat">➕</span> Résultat d'exploitation
            </button>
        </h2>
        <div id="collapseResultatExploitation" class="accordion-collapse collapse"
            data-bs-parent="#parentAccordion"> <!-- THIS LINE IS IMPORTANT -->
            <div class="accordion-body">
                <div class="col-sm-12 col-xl-6 w-100 mb-2" style="overflow: auto;">
                   
                    <table id="table_budget_general" class="table table-striped table-hover table-sm" style="width: 100%;">
                          <caption style="font-weight: bold; caption-side: top;">
                              Université Notre-Dame du Kasayi<br>
                              <small id="titre_tableau"></small>
                          </caption>
                          <thead>
                              <tr>
                                  <th style="width:5%;">Compte</th>
                                  <th style="width:35%;">Intitulé Dépense</th>
                                  <th style="width:10%;">Montant</th>
                                  <th style="width:10%;">% budget</th>
                                  <th style="width:5%;">Compte</th>
                                  <th style="width:35%">Intitulé Recette</th>
                                  <th style="width:10%">Montant</th>
                                  <th style="width:10%">% Budget</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>

            
        <button id="imprimer_budget_general" class="btn btn-outline-primary btn-sm mt-2">
            🖨️ Imprimer le Budget Général
        </button>
    


                    
                </div>
            </div>
        </div>
    </div>
</div>
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









<?php
   // include("suivi_recette_prevue_reelle.php");
?>
