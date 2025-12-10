
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
              
                $Libelle=($_POST['Libelle']);
                $Code_UE=($_POST['Code_UE']);
                $idSemestre=($_POST['idSemestre']);
                $query="INSERT INTO unite_enseignement(Code_ue,Id_Semestre,Intitule_ue)
                      VALUES (:Code_UE,:idSemestre,:Libelle)";
                      
                      $stmt = $con->prepare($query);
                      $stmt->bindParam(':Code_UE', $Code_UE);
                      $stmt->bindParam(':idSemestre', $idSemestre,PDO::PARAM_INT);
                      $stmt->bindParam(':Libelle', $Libelle);
                      $stmt->execute();
                      $message="Le Semestre a été enregistre avec succès";
              }              
          }
    ?>
  <div class="row "> 
    <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4 style="text-align:center">
              <?php  echo "Vous enregistrer les UE du ".$_GET['sem'];?>
            </h4>
            <?php 
              if (isset($message)) 
              {
                echo '<font color="red" size="5%">'. $message."</font>";
              }
            ?>
          </div>
          <div class="card-body border border-primary">
            <form action="" method="POST">
              <div class=" mb-3">
                <label>Code UE : </label>
                <input type="text" name="Code_UE" class="form-control">
              </div>
              <div class=" mb-3">
                <label>Intitulé UE : </label>
                <input type="text" name="Libelle" class="form-control">
              </div>
              <div class=" mb-3">
                <input type="text" name="idSemestre" class="form-control" hidden value="<?php echo $_GET['id']; ?>">
              </div>
              <div class=" mb-3">
                <button type="submit" name="Valider" class="btn btn-primary float-end">Enregistrer</button>
              </div>
            </form>
          </div> 
        </div>
            <br>

        <!--AFFCIHAGE DE SEMESTRE -->
        <table class="table table-bordered table-striped">
        <thead>
          <tr style="text-align: center;">
            
            <th>N°Code UE</th>
            <th>Id Semestre</th>
            <th>Libellé </th>
            </tr>
        </thead>
          <tbody>
            <?php
            // lancement de la requete
          
              $sql = "SELECT *   FROM  unite_enseignement order by Code_ue Asc ";
              $stmt=$con->prepare($sql);
              $stmt->execute();   
              while($ligne = $stmt->fetch())
              {
           ?> 
                <tr>
                  <td> <?php echo $ligne['Code_ue']; ?></td>
                  <td> <?php  echo $ligne['Id_Semestre']; ?></td>
                  <td><?php echo $ligne['Intitule_ue']; ?></td>
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


