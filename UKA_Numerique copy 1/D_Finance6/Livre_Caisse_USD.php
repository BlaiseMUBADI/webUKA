<div class="container" style="display: none;" id="entetepage">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px; text-align: center; position: relative;">
                
                <!-- Logo à gauche en position absolue -->
                <img src="D_Finance/img/logo.png" style="position: absolute; top: 10px; left: 10px; width: 100px; height: auto;">

                <!-- Texte centré -->
                <div style="display: inline-block; text-align: center;">
                    <p style="font-family: 'Times New Roman', Times, serif; font-size: 18px; font-weight: bold; margin-bottom: 5px;">
                        MINISTÈRE DE L'ENSEIGNEMENT SUPÉRIEUR ET UNIVERSITAIRE
                    </p>
                    <p style="font-family: 'Times New Roman', Times, serif; font-size: 17px; margin-bottom: 5px;">
                        UNIVERSITÉ NOTRE-DAME DU KASAYI
                    </p>
                    <p style="font-family: 'Times New Roman', Times, serif; font-size: 16px; margin-bottom: 5px;">
                        Administration du Budget - Service Comptable
                    </p>
                    <p style="font-family: 'Times New Roman', Times, serif; font-size: 16px; font-weight: bold; margin-top: 10px;">
                        LIVRE DE DE CAISSE
                    </p>
                </div>

                <hr style="border: 1px solid black; margin-top: 0px;">
            </div>
        </div>
    </div>
</div>

<div class="encaissement-container">
  <div class="encaissement-header">LIVRE DE CAISSE</div>
  
  <!-- Conteneur pour les éléments sur la même ligne -->
  <div class="input-container">
    <!-- Select pour "Tout", "Par date" et "Par tranche" -->
   
    
    <!-- Premier champ de date -->
    <input type="date" class="date-input"id="date_A">
    &nbsp &nbsp &nbsp &nbsp &nbsp 
    <!-- Deuxième champ de date -->
    <input type="date" class="date-input" id="date_B">
    
    <!-- Bouton pour dérouler les options -->
    
    <select id="Select" class="custom-select">
      <option value="CDF">Livre de caisse CDF</option>
      <option value="USD">Livre de caisse USD</option>
    </select>


  </div>
</div>
<style>
  
</style>

<div class="table-container"id="Print_Livre_Caisse">
  <div class="table-header"> <u></u>
     <button id="btn-action" class="action-button">Exporter</button>
     <button id="btn-print" class="action-butto" onclick="imprimer_livre_caisse()"><i class="fas fa-print icon-style"style="font-size:1.3em;"> </i></button></div>
     
  <table class="table" id="tableEncaissement_Dec">
    <thead>
      
    </thead>
    <tbody>
     
    </tbody>
  </table>
 
</div>
<div class="signature-container" id="sign">
    <p>Fait à Kananga, le <span id="dateSpan"></span></p>
    <p class="signature">Nom et signature</p>
  </div>


<script src="D_Finance/js/Livre_de_Caisse.js"></script>
<script src="D_Finance/js/xlsx.full.min.js"></script>
