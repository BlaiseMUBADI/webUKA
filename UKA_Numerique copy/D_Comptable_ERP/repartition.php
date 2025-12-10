 <!-- Sales Chart Start -->
              <div class="container-fluid  pt-4 px-4 table-responsive"  id="block_repartition">
    
              <div class="bg-light" style="">

                <table class="table table-striped" id="table1">
                   <tr>
                       <td >
                        
                          <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" style="float: right;">Actions <i class="fa bell">🛠️</i></button> 
                          <ul class="dropdown-menu">
                              <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tablea"><i class="fas ">⏳</i> actualiser</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#formulaire_choix" onclick=""><i class="fas " style="color:gold;">👁️</i> Rapport par rubrique</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#formulaire_choix_toutes_rubrique" onclick=""><i class="fas " style="color:gold;">👁️</i> Rapport par Faculté - toutes les rubriques</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#formulaire_choix_toutes_rubrique_1" onclick=""><i class="fas " style="color:gold;">👁️</i> Rapport par Global</a></li>
                              
                          </ul>
                         
                        <h5 style="text-align: center; display: inline-block;">&nbsp &nbsp &nbsp &nbsp &nbsp Repartition : <span id="budget_rectte"></span></h5>
                       </td>
                   </tr>
                   <tr>
                     
                     <td>
                       
                          <select class="form-select select2 d-flex" style="float: right; width: 180px;" id="annee_academique_rubrique_reelle">
                           <?php
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                          </select> 

                          <label class="form-label">Faculté <i class="fas  bell" style="color:gold;">👉</i> </label>
                          <select id="faculte_rubrique_reelle" class="form-select d-flex select2" style="float: left; width: 220px; margin-left: 4px;" >
                              <option value="" disabled selected>Choisir une Option</option>
                          <?php
                        $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere DESC ' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['IdFiliere'];?>"> <?php echo $ligne['Libelle_Filiere'];?></option>
                            <?php } ?>
                          </select> 

                          <label class="form-label">Rubrique <i class="fas  bell" style="color:gold;">👉</i> </label>
                          <select class="form-select d-flex select2" style="float: left; width: 220px; margin-left: 4px;" id="rubrique_reelle">
                            <option value="" disabled selected>Choisir une Option</option>
                               <?php
                        $reponse = $con->query ('SELECT * FROM rubrique order by Id_rubrique DESC ' );
                            while ($ligne = $reponse->fetch()) {?>
                          <option value="<?php echo $ligne['Id_rubrique'];?>"> <?php echo $ligne['Libelle'];?></option><?php } ?>
                          </select>
                         
                     </td>
                   </tr>
                   <tr>

                       <td>
                    <div style="   width: 100%; max-height: 320px; overflow: auto;">
                            <table class="table text-start align-middle table-responsive table-bordered table-hover  mb-0" id="table_rubrique_reelle">
                                <tr>
                                    <th>N°</th><th>Description</th><th>Montant payé/Pourcentage</th><th>Montant</th><th></th>
                                </tr>
                                
                            </table>
                    </div>

                            <div class="col-sm-12 col-xl-6 border w-100">
                                <p style="float: right; padding-right:10%; ">Total frais pour cette rubrique : <span id="total_frais_reel" class="fw-bold" style="color: red;"></span></p>
                            </div>
                       </td>
                   </tr>
                   <tr>
                       <td> <a href="#" style="float: right;"  class="mt-1" id="bouton_imprimer"> <i class="fa " style="color: red;">🖨️</i> Imprimer</a></td>
                   </tr>
               </table>

               </div>
</div>
            <!-- Sales Chart End -->

  <div class="modal fade" id="tableau_budget_recette" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Choisir un Budget</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- tableau -->
            <table class="table text-start align-middle table-responsive table-bordered table-hover  mb-0" id="table_recette" style=" max-height: 50px;  overflow-y: auto;">
                                <tr>
                                    <th>Réf.</th><th>Libellé</th><th>Périodicité</th><th>Service</th>
                                </tr>
                                <tbody>
                                <?php 
                        $reponse = $con->query ('SELECT t_budget.Ref_budget, t_budget.Libelle as libelle1, t_budget.Periodicite, t_budget.Annee_debut,t_budget.Annee_fin FROM t_budget order by ref_budget desc' );
                        $i=1;
                            while ($ligne = $reponse->fetch()) {?>
                                <tr style="cursor: pointer;">
                                  <td><?php echo $ligne['Ref_budget']; ?></td>
                                  <td><?php echo $ligne['libelle1'];?></td>
                                  <td><?php echo $ligne['Periodicite'];?></td>
                                  <input type="text" name="" hidden id="id_budget_select" value="<?php echo $ligne['Ref_budget'];?>">
                                  <td><button class="btn btn-success" title="éditer" id="editer_budget" onclick="
                                  "><i class="fas fa-edit"></i> </button></td>
                                </tr>
                                <?php  } ?>
                                </tbody>
                            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>


   <div class="modal fade" id="formulaire_choix" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Choisir une année Academique et rubrique</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- tableau -->
           <form id="formulaire_recette">
              <label for="zone4" class="form-label">Année Académique</label>
            <select class="form-select" id="anne_academique_global">
                <?php
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                          </select> 
                          <br>
                         <label for="zone4" class="form-label">Rubrique</label>
                          <select class="form-select" id="Id_rubrique_general">
                          <?php
                        $reponse = $con->query ('SELECT * FROM rubrique order by Id_rubrique DESC ' );
                            while ($ligne = $reponse->fetch()) {?>
                          <option value="<?php echo $ligne['Id_rubrique'];?>"> <?php echo $ligne['Libelle'];?></option><?php } ?>
                          </select>
           
           </form>
           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="affiche_global">Valider</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  



   <div class="modal fade" id="formulaire_choix_toutes_rubrique" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Choisir une année Academique et filière</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- tableau -->
           <form id="formulaire_recette">
              <label for="zone4" class="form-label">Année Académique</label>
            <select class="form-select" id="anne_academique_global_tout">
                <?php
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                          </select> 
                          <br>
                         <label for="zone4" class="form-label">Filière1</label>
                          <select class="form-select" id="Id_filiere_general_tout">
                          <?php
                        $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere DESC ' );
                            while ($ligne = $reponse->fetch()) {?>
                          <option value="<?php echo $ligne['IdFiliere'];?>"> <?php echo $ligne['Libelle_Filiere'];?></option><?php } ?>
                          </select>
           
           </form>
           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="affiche_global_tout">Valider</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>







 <div class="modal fade" id="formulaire_choix_toutes_rubrique_1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Choisir une année Academique et filière</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- tableau -->
           <form id="formulaire_recette">
              <label for="zone4" class="form-label">Année Académique</label>
            <select class="form-select" id="anne_academique_global_tout_1">
                <?php
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                          </select> 
                          <br>
                         
           
           </form>
           
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="affiche_global_tout_1" >Valider</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>








            