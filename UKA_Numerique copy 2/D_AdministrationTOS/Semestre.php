
    <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      

  <div class="container "> 

    <?php 

         include("../../Connexion_BDD/Connexion_1.php");  
     
          if (isset($_POST['Valider'])) 
          {
              $message='';
            if(empty($_POST['Libelle'])) 
              {
                $message = "Le Semestre ne peut être vide";
              }
            else
              {
              
                $Semestre=htmlspecialchars($_POST['Libelle']);
                $Niveau=htmlspecialchars($_POST['Niveau']);
                $query="INSERT INTO semestre (libelle_semestre,Niveau_semestre)
                      VALUES ('$Semestre','$Niveau')";
                      $con->exec($query);
                      $message="Le Semestre a été enregistre avec succès";
              }              
          }
    ?>
       <div class="row "> 
        <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="card">
                    <div class="card-header">
                        <h4 style="text-align:center">Enregistrement des semestres         
                            
                        </h4>
                        <?php 
                            if (isset($message)) {

                                echo '<font color="red" size="5%">'. $message."</font>";
                                
                            }
                         ?>
                    </div>
                  <div class="card-body">
                    <form action="" method="POST">
                      <div class=" mb-3">
                        <label>Libelle : </label>
                        <input type="text" name="Libelle" class="form-control">
                      </div>
          
                    <div class=" mb-3">
                        <label>Niveau: </label>
                        <select name="Niveau">
                          <option value="1">Premier Cycle</option>
                          <option value="2">Deuxième Cycle</option>
                        </select>
                      </div>
                      
                    
                      <div class=" mb-3">
                            <button type="submit" name="Valider" class="btn btn-primary float-end">Enregistrer</button>
                      </div>
                    </form>
                    </div>
            </div>
      

   
    <!--AFFCIHAGE DE SEMESTRE -->
      <table class="table table-bordered table-striped">
        <thead>
          <tr style="text-align: center;">
            
            <th>N°</th>
            <th>Libellé</th>
            <th>Niveau</th>
            </tr>
        </thead>
          <tbody>
            <?php
            // lancement de la requete
          
              $sql = "SELECT *   FROM semestre order by Id_Semestre desc ";
              $stmt=$con->prepare($sql);
              $stmt->execute();   
              while($ligne = $stmt->fetch())
              {
           ?> 
                <tr>
                  <td> <?php echo $ligne['Id_Semestre']; ?></td>
                  <td> <?php  echo $ligne['libelle_semestre']; ?></td>
                  <td><?php echo $ligne['Niveau_semestre']; ?></td>
                  <td>
                  <form action="" method="POST" class="d-inline">
                  <a href="Semestre.php?id=<?php echo $ligne['Id_Semestre'];  ?>" class="btn btn-success btn-sm">Modifier</a></td>
                 
                  </form>
                                       
                </tr>
            <?php
              }
              
            ?>
        </tbody>
      </table>
      </div>
      </div>                      
 </div>


