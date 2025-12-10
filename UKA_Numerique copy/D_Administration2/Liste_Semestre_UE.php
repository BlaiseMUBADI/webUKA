<link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      

<div class="container "> 
    <div class="row "> 
        <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="card">
                    <div class="card-header">
                        <h4 style="text-align:center">Cliquez sur Ajout UE pour le semetre concerné</h4>


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
            // Selection du Semestre concerné
       

              $sql = "SELECT *   FROM semestre order by Id_Semestre Asc ";
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
                  <a href="Principal.php?page=UE&id=<?php echo $ligne['Id_Semestre'];  ?>&sem=<?php echo $ligne['libelle_semestre'];  ?>" class="btn btn-success btn-sm">Ajout UE</a></td>
                 
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