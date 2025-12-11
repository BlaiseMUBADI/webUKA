
<section class="home-section " style="height: auto;">
      <?php
        require_once 'D_Generale/Profil_Sec_Administratif.php';
      ?>

  <div class="home-content me-3 ms-3"  >
    <div class="sales-boxes m-0 p-0 " >
      <div class="recent-sales box " style="width:100%; margin:0px;">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Catégorie agent</label>
            <div class="col-sm-2 ">  

              <select id="Categorielisteagent" name="" class="form-control " style="width:100%;font-family:Palatino Linotype;">
              <option value="" selected>-</option>
              <?php 
                    //Requette de sélection de catégorie agent
                    $req="select * from categorie order by IdCategorie Asc";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
                    {
                    ?>
                    <option value="<?php echo $ligne['IdCategorie']?>"><?php echo $ligne['Libelle'];?></option>
                    <?php 
                      }
                    ?>     
              </select>
            </div>
            <label class="col-sm-2 col-form-label" style="color: white;">Filtré les données</label>
            <div class="col-sm-2">  

              <select id="Critere" name="" class="form-control " style="width:100%;font-family:Palatino Linotype;">
              
              <option value="-" selected>Tous</option>
              <option value="NU">Les NU</option>
              <option value="Matriculé">Les Matriculés</option>
                    
              </select>
            </div>
            <div class="col-sm-4">  

              <input id="rechercher"placeholder="Recherche par nom de l'agent" name="" class="form-control " style="width:100%;font-family:Palatino Linotype;">

             
            </div>
      </div>
        <div class="row g-3 align-items-center"style="width:100%; margin:auto;">
        
        <table id="TabListeAgent_cat">
            <thead>
              <th>Matricule</th>
              <th>Nom</th>
              <th>Postnom</th>
              <th>Prénom</th>
              <th>Sexe</th>
              <th>Grade</th>
            </thead>
            <tbody>
            
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
</section>
    
<div class="modal" id="modifierModal" tabindex="-1" style="display:none; position:fixed; z-index:1050; background:rgba(0,0,0,0.5); top:0; left:0; width:100%; height:100%;">
  <div style="background:white; margin:5% auto; padding:20px; width:50%; border-radius:5px; position:relative;">
    <h4>Modifier Agent</h4>
    <form id="formModifierAgent">
      
      <!-- Étape 1 -->
      <div id="formStep1">
        <div style="margin-bottom:10px;">
          <label>Matricule:</label>
          <input type="text" id="matricule" class="form-control" disabled>
        </div>
        <div style="margin-bottom:10px;">
          <label>Nom:</label>
          <input type="text" id="nom" class="form-control">
        </div>
        <div style="margin-bottom:10px;">
          <label>Postnom:</label>
          <input type="text" id="postnom" class="form-control">
        </div>
        <div style="margin-bottom:10px;">
          <label>Prénom:</label>
          <input type="text" id="prenom" class="form-control">
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 15px;">
          <button type="button" onclick="fermerModal()" class="btn btn-secondary">Annuler</button>
          <button type="button" class="btn btn-primary" onclick="afficherEtape2()">Suivant</button>
        </div>
      </div>

      <!-- Étape 2 -->
      <div id="formStep2" style="display:none;">
        <div style="margin-bottom:10px;">
          <label>Sexe</label>
          <select id="sexe" class="form-control">
            <option value="M">Masculin</option>
            <option value="F">Féminin</option>
          </select>
        </div>
        <div style="margin-bottom:10px;">
          <label>État civil:</label>
          <select id="Etatciv" class="form-control">
            <option value="Célibataire">Célibataire</option>
                <option value="Marié(e)">Marié(e)</option>
                <option value="Veuve">Veuve</option>
                <option value="Veuf">Veuf</option>
          </select>
        </div>
        <div style="margin-bottom:10px;">
          <label>Lieu de naissance:</label>
          <input type="text" id="lieuNaissance" class="form-control">
        </div>
        <div style="margin-bottom:10px;">
          <label>Date de naissance:</label>
          <input type="date" id="dateNaissance" class="form-control">
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 15px;">
          <button type="button" onclick="fermerModal()" class="btn btn-secondary">Annuler</button>
          <div>
            <button type="button" class="btn btn-secondary" onclick="afficherEtape1()">Précédent</button>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>





