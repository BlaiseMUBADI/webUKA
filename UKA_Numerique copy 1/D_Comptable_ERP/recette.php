 <!-- Sales Chart Start -->
              <div class="container-fluid  pt-4 px-4 table-responsive" id="block_recette">
    
              <div class="bg-light" style="">

                <table class="table table-striped " id="table1" >
                   <tr class="bg-primary">
                       <td >
                           <select class="form-select" style="display: inline-block; float: left; width:190px; margin-right: 4px; overflow-y: auto; " id="Id_an_acad_budget1"> 
                            <option>Année académiqe...</option>
                          <?php
                             
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['Annee_debut'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                        </select>
                          <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">Actions <i class=""></i></button> 
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tableau_budget_recette"><i class="fas fa-piggy-bank text-primary"></i> Budget partiel</a></li>
                              <li><a class="dropdown-item" href="#" onclick="Budget_general()"><i class="fas fa-piggy-bank text-primary"></i> Budget Général</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="nouveau_recette()"><i class="fas fa-file text-primary" ></i> Nouvelle Recette</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fas fa-plus text-primary" ></i> Type de recette</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fas fa-search text-primary" ></i> Rechercher</a></li>
                          </ul>
                         
                        <h5 style="text-align: center; display: inline-block;">&nbsp &nbsp &nbsp &nbsp Recettes prévues du Budget : <span style="color: white; font-size: 20; " id="budget_rectte"></span></h5>
                       </td>
                   </tr>
                   <tr>
                       <td>
                     <div style="   width: 100%; max-height: 320px; overflow: auto;">
                            <table class="table text-start align-middle table-responsive table-bordered table-hover  mb-0"  id="table_recette_prevues">
                                <tr>
                                    <th>Num</th><th>Design./Promot.</th><th>Type recette</th><th>Montant</th><th></th>
                                </tr>
                                
                            </table>
               </div>

                            <div class="col-sm-12 col-xl-6 border w-100">
                                <p style="float: right; padding-right:10%; ">Total Recettes prévues : <b><span id="total_recette" class="fw-bold" style="color: red; font-family: 'Courier New', monospace; font-size:20px;"></span></b></p>
                            </div>
                       </td>
                   </tr>
                   <tr>
                       <td> <a href="#" style="float: right;"  class="mt-1"  data-bs-toggle="modal" data-bs-target="#form_ajout_recette">Ajouter une Recette <i class="fa fa-plus" style=""></i></a></td>
                   </tr>
               </table>

               </div>
</div>
            <!-- Sales Chart End -->

  <div class="modal fade" id="tableau_budget_recette" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Choisir un Budget Partiel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="overflow:auto;">
          <!-- tableau -->
            <table class="table text-start align-middle table-responsive table-bordered table-hover  mb-0" id="table_budget_recette" style="max-height: 160px; cursor: pointer; overflow: auto;">
                  <tr>
                      <th>Réf.</th><th>Libellé</th><th>Périodicité</th><th hidden>Service</th>
                  </tr>
                  <tbody >
                 
                  </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>


   <div class="modal fade" id="form_ajout_recette" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Ajouter une source</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- tableau -->
           <form id="formulaire_recette">

             <select class="form-select" id="promotion_reste"></select>
             <input type="text" name="" id="id_budget1" style="display: none;">
           </form>
           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="enregistrer_recette()">Valider</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>