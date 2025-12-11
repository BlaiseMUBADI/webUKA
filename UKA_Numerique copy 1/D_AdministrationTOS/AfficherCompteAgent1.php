
    <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      

  
    <?php 
             include("../../Connexion_BDD/Connexion_1.php");  
    ?>        
     
   <div class="container mt-4">


        <div class="row">
              <div class="col-md-12">
                <div class="card">
                      <div class="card-header">
                            <h4> Details Compte
                                  <a href="CreerCompteAgent.php?page=CreerCompte" class="btn btn-primary float-end">Nouveau Compte</a>
                                  <a href="../Fonctions_PHP/Deconnexion.php" class="btn btn-primary float-end ms-3 me-3">Déconnexion</a>
                            </h4>
                      </div>
                      <div class="card-body">





                    <table class="table table-bordered table-striped ">
                        <thead>
                            <tr style="text-align: center;">
                                <th>Numéro</th>
                                <th>Matricule Agent</th>
                                <th>Login</th>
                                <th>Mot de passe</th>
                                <th>Etat</th>
                                <th>Fonction</th>
                            </tr>
                        </thead>
                        <tbody>
                       <?php
// lancement de la requete
        
                         
          $sql = "SELECT * FROM compte_agent";
          $stmt=$con->prepare($sql);

          $stmt->execute();    
        
          while($ligne = $stmt->fetch())
          {
           

                ?> 
                                   <tr>
                                        <td> <?php echo $ligne['Id_Compte_agent']; ?></td>
                                        <td> <?php echo $ligne['Mat_agent']; ?></td>
                                        <td><?php echo $ligne['Login']; ?></td>
                                        <td><?php echo $ligne['Mot_passe']; ?></td>
                                        <td><?php echo $ligne['Etat']; ?></td>
                                        <td><?php echo $ligne['Categorie']; ?></td>
                                        <td>

                        <form action="" method="POST" class="d-inline">
                             <a href="ModifierCompteAgent.php?page=<?php echo $ligne['Mat_agent'];  ?>" class="btn btn-success btn-sm">Modifier</a>
                             <a href="AfficherCompteAgent.php?page=<?php echo $ligne['Mat_agent'];  ?>" onclick="confirm()" class="btn btn-danger btn-sm">Supprimer</a>

                                                

                        </form>
                                        </td>
                                  </tr>
                        <?php
              }

                   
  
                         ?>
                      </tbody>
                    </table>
                      </div>
                </div>
              </div>
        </div>

   </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

