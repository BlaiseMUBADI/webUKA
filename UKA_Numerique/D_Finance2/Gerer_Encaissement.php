<style>

</style>

<div class="encaissement-container">
  <div class="encaissement-header">Encaissement</div>
  
  <!-- Conteneur pour les éléments sur la même ligne -->
  <div class="input-container">
    <!-- Select pour "Tout", "Par date" et "Par tranche" -->
   
    
    <!-- Premier champ de date -->
    <input type="date" class="date-input"id="date1">
    &nbsp &nbsp &nbsp &nbsp &nbsp 
    <!-- Deuxième champ de date -->
    <input type="date" class="date-input" id="date2">
    
    <!-- Bouton pour dérouler les options -->
    
    <select id="encaissementSelect" class="custom-select">
      <option value="CDF">Opérations FC</option>
      <option value="USD">Opérations $</option>
    </select>


  </div>
</div>
<style>
  
</style>

<div class="table-container">
  <div class="table-header">
    <span>Tableau des Encaissements</span>

    <div class="actions">
      <button id="btn-usd" class="action-button">➕ Opérations $</button>
      <button id="btn-cdf" class="action-button">➕ Opérations Fc</button>
    </div>
  </div>

  <table class="table" id="tableEncaissement">
    <thead></thead>
    <tbody></tbody>
  </table>
</div>



<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Modifier l'encaissement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="editForm" >
            
          <div class="mb-3">
            <label class="form-label">Id Opération</label>
            <input type="text" class="form-control" id="editId" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Déposant</label>
            <input type="text" class="form-control" id="editDeposant">
          </div>


          <div class="mb-3">
            <label class="form-label">Motif</label>
            <input type="text" class="form-control" id="editMotif">
          </div>

          <div class="mb-3">
            <label class="form-label">Montant</label>
            <input type="number" class="form-control" id="editMontant">
          </div>

          <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" id="editDate">
          </div>

          <div class="mb-3">
            <label class="form-label">Numéro Pièce</label>
            <input type="text" class="form-control" id="editNumeroPce" readonly>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="editer">Enregistrer</button>
        
        
      </div>

    </div>
  </div>
</div>





<div class="modal fade" id="modalEncaissementUSD" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Nouvelle opération</h5>
        <h3 class="modal-center" id="solde_USD"> Solde </h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="modalBodyEncaissementUSD">

        <div class="form_encaissement form-container">
            <h2 class="form-title text-primary">Versement en USD ($)</h2>

            <form>
                       

                           
                            <div class="mb-3">
                              <label for="annee" class="form-label">Année academique</label>
                              
                              <select id="annee" class="form-control text-center">
                                <?php 
                                $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc limit 2' );
                                    while ($ligne = $reponse->fetch()) {?>

                                <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                              </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="numeroPieceUSD" class="form-label">N° Pièce</label>
                                <input type="text" class="form-control" id="numeroPieceUSD" placeholder="N° pièce">
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
                    <input type="datetime-local" class="form-control" id="dateVersementUSD">
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
               
            </form>
        </div>

      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="modalEncaissementCDF" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Nouvelle opération </h5>
        <h3 class="modal-center" id="solde_CDF"> Solde caisse</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="modalBodyEncaissementCDF">

        <div class="form_encaissement form-container">
            <h2 class="form-title text-primary">Versement en FC</h2>

            <form>
                <div class="mb-3">
                    
                    <input type="text" class="form-control" id="numeroPieceCDF" placeholder="N° pièce">
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
                    <input type="datetime-local" class="form-control" id="dateVersementCDF">
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
                    <label for="montantCDF" class="form-label">Montant ($)</label>
                    <input type="text" class="form-control" id="montantCDF" placeholder="Montant en CDF" step="0.01">
                    <small id="erreurMontant" style="color:red;display:none;">Saisir uniquement des chiffres (0-9) et un point (.)</small>
                    <span id="en-lettres"></span>
                    <span id="en-lettresTotal" hidden></span>
                </div>

                <button id="Encaisser_CDF" type="button" class="btn btn-primary btn-Action">Encaisser CDF</button>
                
            </form>
        </div>

      </div>

    </div>
  </div>
</div>

<script src="D_Finance/js/Selection_des_Encaissements.js"></script>
<script src="D_Finance/js/Ajouter_op.js"></script>
