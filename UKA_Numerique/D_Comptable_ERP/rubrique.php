 <!-- Sales Chart Start -->
              <div class="container-fluid  pt-4 px-4 table-responsive"  id="block_rubrique">
    
              <div class="bg-light" style="">

                <table class="table table-striped" id="table1">
                  <tr>
                    <th>
                       <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" style="float:right;">Opération <i class="fas  bell" style="color:gold;">🛠️</i></button> 
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal_ajout_rubrique"><i class="fas fa" style="color:#ee82ee;">📋</i> Ajouter une rubrique</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal_nouvelle_rubrique" onclick="nouveau_recette()"><i class="fas fa-file text-primary" ></i>Nouvelle rubrique</a></li>
                              <li><a class="dropdown-item" href="#" id="imprimer_prevue"><i class="fas text-primary" >🖨️</i> Imprimer</a></li>
                          </ul>
                          <h5 style="text-align: center; display: inline-block;">Rubrique <span id="titre_rubrique"></span></h5></th>
                  </tr>
                   <tr>
                       <td >


                          <select class="form-select select2 d-flex" style="float: right; width: 180px;" id="annee_academique_rubrique">
                           <?php
                        $reponse = $con->query ('SELECT * FROM annee_academique order by idAnnee_Acad DESC' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut'].'-'.$ligne['Annee_fin'];?></option>
                            <?php } ?>
                          </select> 

                          <label class="form-label">Faculté <i class="fas  bell" style="color:gold;">👉</i> </label>
                          <select id="faculte_rubrique" class="form-select d-flex select2" style="float: left; width: 200px; margin-left: 4px;" >
                              <option value="" disabled selected>Choisir une Option</option>
                          <?php
                        $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere DESC ' );
                            while ($ligne = $reponse->fetch()) {?>

                          <option value="<?php echo $ligne['IdFiliere'];?>"> <?php echo $ligne['Libelle_Filiere'];?></option>
                            <?php } ?>
                          </select> 

                          <label class="form-label">Promotion <i class="fas  bell" style="color:gold;">👉</i> </label>
                          <select class="form-select d-flex select2" style="float: left; width: 180px; margin-left: 4px;" id="promotion_rubrique">
                              <option value="" disabled selected>Choisir une Option</option>
                          </select>
                         
                         
                        
                       </td>
                   </tr>
                   <tr>
                       <td>
                     <div style="   width: 100%; max-height: 320px; overflow: auto;">
                            <table class="table text-start align-middle table-responsive table-bordered table-hover  mb-0" id="table_rubrique">
                                <tr>
                                    <th>N°</th><th>Libellé</th><th>Pourcentage</th><th>Montant</th><th></th>
                                </tr>
                                <tbody>
                                  
                                </tbody>
                            </table>
               </div>

                            <div class="col-sm-12 col-xl-6 border w-100">
                                <p style="float: right; padding-right:10%; ">Total frais Académique : <span id="total_frais" class="fw-bold" style="color: red;"></span></p>
                            </div>
                       </td>
                   </tr>
                   <tr>
                       <td> <a href="#" style="float: right;"  class="mt-1"  data-bs-toggle="modal" data-bs-target="#exampleModal_ajout_rubrique">Ajouter une Rubrique <i class="fa fa-plus" style=""></i></a></td>
                   </tr>
               </table>

               </div>
</div>
            <!-- Sales Chart End -->

 <!-- le formulaire qui sort pour ajouter une rubrique dans la promotion -->
<div class="modal fade" id="exampleModal_ajout_rubrique" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Ajouter une autre rubrique</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulaire -->
          <form id="formEnregistrement">
            
           
           
            <div class="mb-3">
              <label for="zone4" class="form-label">Rubrique consernée</label>
              <select class="form-select" id="rubrique">
               <?php 
                      
                        $reponse = $con->query ("SELECT * FROM rubrique");
                            while ($ligne = $reponse->fetch()) {?>

                        <option value="<?php echo $ligne['Id_rubrique'];?>"><?php echo $ligne['Libelle'];?></option> <?php } ?>
                    </select>
                 
            </div>
            <div class="mb-2" >
             
              <label for="zone4" class="form-label">Montant prévu</label>
              <input type="text" class="form-control" id="montant_rubrique" required>
              </div>
             
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary" id="enregistrer_ajout_rubrique" onclick="enregistrer_ajout_rubrique()">Enregistrer</button>
        </div>
      </div>
    </div>
  </div>




<!-- le formulaire qui sort pour ajouter une rubrique dans la liste des rubiques qui existe -->
<div class="modal fade" id="exampleModal_nouvelle_rubrique" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" >Ajouter une Nouvelle rubrique</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulaire -->
          <form id="formEnregistrement">
            
            <div class="mb-2" >
              <label for="zone4" class="form-label">Numéro</label>
              <input type="number" class="form-control" id="id_rubrique" required>
            </div>

           <div class="mb-2" >
              <label for="zone4" class="form-label">Libellé rubrique</label>
              <input type="text" class="form-control" id="libelle_rubrique" required>
            </div>
            <div class="mb-3">
              <label for="zone4" class="form-label">catégorie</label>
              <select class="form-select" id="categorie_rubrique">
                <option value="Frais Académique">Frais Académique</option>
                <option value="Frais connexes">Frais Connexes</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary" id="enregistrer_nouvelle_rubrique" onclick="enregistrer_nouvelle_rubrique()">Enregistrer</button>
        </div>
      </div>
    </div>
  </div>
 