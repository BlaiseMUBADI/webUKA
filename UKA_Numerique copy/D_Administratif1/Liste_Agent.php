
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
    
       



