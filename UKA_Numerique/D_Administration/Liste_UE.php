<link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      

<div class="container "> 
    <div class="row "> 
        <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="card">
                    <div class="card-header">
                        <h4 style="text-align:center">Ajout Elements Constitutifs aux UE</h4>


    <table class="table table-bordered table-striped">
        <thead>
          <tr style="text-align: center;">
            
            <th>Code UE</th>
            <th>Semestre</th>
            <th>Intitulé</th>
            </tr>
        </thead>
          <tbody>
            <?php
            // lancement de la requete
            // Selection du Semestre concerné


      
            $sql = "SELECT semestre.libelle_semestre, 
                            unite_enseignement.Code_ue,
                            unite_enseignement.Intitule_ue
                    FROM
                            semestre, unite_enseignement
                    WHERE 
                            semestre.Id_Semestre=unite_enseignement.Id_Semestre ";
            $stmt=$con->prepare($sql);
            $stmt->execute();   
              while($ligne = $stmt->fetch())
              {
           ?> 
                <tr>
                    <td> <?php echo $ligne['Code_ue']; ?></td>
                    <td> <?php  echo $ligne['libelle_semestre']; ?></td>
                    <td><?php echo $ligne['Intitule_ue']; ?></td>
                    <td>
                    <form action="" method="POST" class="d-inline">
                    <a href="Principal.php?page=EC&code=<?php echo $ligne['Code_ue'];  ?>&intituleUE=<?php echo $ligne['Intitule_ue'];  ?>" class="btn btn-success btn-sm">Ajouter E.C</a></td>
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