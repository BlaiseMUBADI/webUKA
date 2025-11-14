

<section class="home-section" style="height: 100%;">
      <?php
        require_once '../D_Generale/Profil_User_Connecter.php';
      ?>
  <div class="home-content me-3 ms-3" id="div_gen_deliberation">
    
    <div class="rounded m-0 p-0 mb-2 text-center" style="color:white;background-color: #273746;">
      
        <div class="col p-0 m-0 fw-medium ms-2 me-3">
          <div class="input-group mb-1 p-1 "style="color:white;">
            <select id="id_semestre_encodage"  
              class="form-control p-0 pe-2 fw-bolder text-center border ms-2"
              style="background-color:#273746;color:white; font-weight:bold;">
              
                      <option value="rien" selected >Séléction Semestre</option>
                      <?php 
                      $req = "SELECT semestre.Id_Semestre, semestre.libelle_semestre 
                              FROM element_constitutifs_aligne 
                              JOIN semestre ON element_constitutifs_aligne.Id_Semestre = semestre.Id_Semestre
                              WHERE element_constitutifs_aligne.Code_Promotion = :code_prom 
                              GROUP BY semestre.Id_Semestre
                              ORDER BY LENGTH(libelle_semestre) ASC";
                      $stmt = $con->prepare($req);
                      $stmt->bindParam(':code_prom', $_SESSION['code_prom'], PDO::PARAM_STR);
                      $stmt->execute();

                      while ($ligne = $stmt->fetch()) {
                      ?>
                          <option value="<?php echo $ligne['Id_Semestre']; ?>"><?php echo $ligne['libelle_semestre']; ?></option>
                      <?php 
                      }
                      ?>
            </select>                    
          </div>
        </div>

    </div>


    <div class="home-content text-center m-0 p-3 mt-1 border"style="background-color:rgb(39,55,70);height:700px">
      
        <div class="home-content table-responsive small p-0 m-0" style="width: 100%; height:100%;">        
          <table  class="tab1 table-bordered text-center" id="table_deliberation" style="width:100%;">              
            <thead class="sticky-sm-top m-0 fw-bold">
              <tr>
                <th>N°</th>
                <th>Enseignant</th>
                <th>Sexe</th>
                <th>Domaine</th>
                <th>Titre</th>
                <th>Institution</th>
                <th>Filière</th>
                <th>Photo</th>
              </tr>
            </thead>
            
            <tbody>
              
            </tbody>
          </table> 
        </div>    
        
    </div>


  </div>
</section>


<!------------Ce code permet de faire une boite de dialog au dessus d'une interface----------------------------------------->


