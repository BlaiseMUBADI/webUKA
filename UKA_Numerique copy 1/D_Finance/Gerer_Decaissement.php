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
  <div class="table-header">Tableau des Décaissements</div>
  
  <table class="table" id="tableEncaissement">
    <thead>
      
    </thead>
    <tbody>
     
    </tbody>
  </table>
</div>

<div id="modalEdit" class="modal-overlay" style="display:none;">
  <div class="modal-content">
    <h3>Modifier le décaissement</h3>
    <form id="editForm">
      <label>Déposant</label>
      <input type="text" id="editDeposant">
      
      <label>Motif</label>
      <input type="text" id="editMotif">
      
      <label>Montant</label>
      <input type="number" id="editMontant">
      
      <label>Date</label>
      <input type="date" id="editDate">
      
      <label>Numéro Pièce</label>
      <input type="text" id="editNumeroPce" disabled>
      
      <div class="modal-buttons">
        <button type="button" id="editer">Enregistrer</button>
        <button type="button" id="cancelEdi">Annuler</button>
      </div>
    </form>
  </div>
</div>
<style>

</style>
<script src="D_Finance/js/Selection_des_Decaissements.js"></script>
