<style>
  
</style>

<div class="encaissement-container">
  <div class="encaissement-header">Décaissements</div>
  
  <!-- Conteneur pour les éléments sur la même ligne -->
  <div class="input-container">
    <!-- Select pour "Tout", "Par date" et "Par tranche" -->
   
    
    <!-- Premier champ de date -->
    <input type="date" class="date-input"id="dateDec1">
    
    <!-- Deuxième champ de date -->
    <input type="date" class="date-input" id="dateDec2">
    
    <!-- Bouton pour dérouler les options -->
    
    <select id="decaissementSelect" class="custom-select">
      <option value="CDF">Décaissement CDF ✅</option>
      <option value="USD">Décaissement USD ✅</option>
      
    </select>


  </div>
</div>
<style>
  
</style>

<div class="table-container">
  <div class="table-header">
    <span>Tableau des Décaissements</span>
      <div class="actions">
        <button id="btn-usd" class="action-button">➕ Opérations $</button>
        <button id="btn-cdf" class="action-button">➕ Opérations Fc</button>
      </div>

  </div>
  
      <table class="table" id="tableDecaissement">
        <thead>
          
        </thead>
        <tbody>
        
        </tbody>
      </table>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Modifier l'opération de décaissement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="editForm" >
            
          <div class="mb-3">
            <label class="form-label">Id Opération</label>
            <input type="text" class="form-control" id="editId" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Bénéficiaire</label>
            <input type="text" class="form-control" id="editBen">
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
            <input type="datetime-local" class="form-control" id="editDate" max="">
          </div>

          <div class="mb-3">
            <label class="form-label">Numéro Pièce</label>
            <input type="text" class="form-control" id="editNumeroPce" readonly>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="editer">Enregistrer</button>
        
        
      </div>

    </div>
  </div>
</div>
<script>
  editDate.max = new Date().toISOString().slice(0,16);

</script>


<script src="D_Finance/js/Selection_des_Decaissements.js"></script>
