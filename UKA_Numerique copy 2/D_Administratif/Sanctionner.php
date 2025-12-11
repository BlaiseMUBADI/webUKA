
<section class="home-section " style="height: auto;">
      <?php
        require_once 'D_Generale/Profil_Sec_Administratif.php';
      ?>

  <div class="home-content me-3 ms-3"  >
    <div class="sales-boxes m-0 p-0 " >
      <div class="recent-sales box " style="width:100%; margin:0px;">
        <div class="mb-3 row ">
            <label class="col-sm-2 col-form-label" style="color: white;">Recherchez...</label>
            <div class="col-sm-4"style="width:80%; margin:0px;">  

              <input type="search" id="Rechercher" name="rechercher" class="form-control " style="width:100%;font-family:Palatino Linotype;">
             
            </div>
         
            
      </div>
        <div class="container table-responsive small p-0 m-0" 
          style="width: 60%; float: left; height: 500px;">
        
        <table id="TableAgentSanction"style="width:100%;height: 30px;">
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
    
       



