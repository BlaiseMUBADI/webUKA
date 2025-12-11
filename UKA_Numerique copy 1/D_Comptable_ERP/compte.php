<div class="container-fluid pt-4 px-4 table-responsive" style="display: block;" id="block_compte">
    
              <div style="border:1px solid black; border-radius: 15px;">

                <table class="table table-striped" id="table1">
                   <tr>
                       <td class="bg-primary">
                        
                          <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">Actions <i class=""></i></button> 
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Imputation</a></li>
                              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal1" onclick="nouveau_compte()"><i class="fas fa-file"></i> Nouvelle Imputation</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fas fa-search"></i> Rechercher</a></li>
                          </ul>
                         <h5 style="text-align: center; display: inline-block;">&nbsp &nbsp &nbsp &nbsp &nbsp Imputation</h5>
                       </td>
                   </tr>
                   <tr>
                       <td class=" bg-light">
                            <table class="table text-start align-middle table-responsive table-bordered table-hover  mb-0" id="table_compte">
                                <tr>
                                    <th>N°</th><th>Numéro Imputation</th><th>Libellé</th><th>Pourcentage</th>
                                </tr>
                                <?php 
                        
                        $reponse = $con->query ('SELECT * FROM t_imputation order by Num_imputation desc' );
                        $i=1;
                            while ($ligne = $reponse->fetch()) {?>
                                <tr>
                                  <td><?php echo $i++; ?></td>
                                  <td><?php echo $ligne['Num_imputation'];?></td>
                                  <td><?php echo $ligne['Intitul_compte'];?></td>
                                  <td><?php echo $ligne['Pourcent_budget'];?></td>
                                  <td><button class="btn btn-success" title="éditer" onclick="affichage_compte1()"><i class="fas fa-edit"></i> </button></td>
                                </tr>
                                <?php  } ?>
                            </table> 
                       </td>
                   </tr>
                   <tr>
                       <td>3</td>
                   </tr>
               </table>
               </div>
</div>


<!-- le formulaire qui sort -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel_compte" >Enregistrement du nouveau compte</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulaire -->
          <form id="formEnregistrement">
            <div class="mb-2">
              <label for="libelle" class="form-label">Numéro Compte</label>
              <input type="text" class="form-control" id="numero_compte" required>
            </div>
            <div class="mb-2">
              <label for="" class="form-label">Intitulé Compte</label>
              <input type="text" class="form-control" id="intitule_compte" required>
            </div>
            <div class="mb-2">
              <label for="zone3" class="form-label">Pourcentage</label>
            
              <input type="number" min="0" max="100" value="1" class="form-control" id="Pourcentage" required>  <input type="text" hidden name=""  id="input_compte">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-primary" id="enregistrer_budget" onclick="enregistrer_compte()">Enregistrer</button>
        </div>
      </div>
    </div>
  </div>
