
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
    <div class="col-md-4">
        Promotion
        <table class="table table-bordered table-striped">
            <?php
                $sql = "SELECT DISTINCT promotion.Libelle_promotion,promotion.Abréviation,mentions.Libelle_mention,promotion.Code_Promotion
                    FROM
                        promotion,mentions,filiere,passer_par,annee_academique
                    WHERE
                        promotion.Code_Promotion=passer_par.Code_Promotion
                    AND mentions.idMentions=promotion.idMentions
                    AND mentions.IdFiliere='3'
                    AND annee_academique.idAnnee_Acad=passer_par.idAnnee_academique
                    AND promotion.Abréviation LIKE '%LMD%'order by Abréviation, Libelle_mention Asc ";

                $stmt=$con->prepare($sql);
                $stmt->execute();
                $n=1;
                while($ligne = $stmt->fetch())
                {
            ?> 
                    <tr>
                        <td>
                            <a href="Saisie_EC.php?id=<?php echo $ligne['Code_Promotion'];  ?>" class="text-decoration-none"><?php echo $n.". ". $ligne['Abréviation']." ".$ligne['Libelle_mention']; ?></a>
                        </td>
                    </tr>
            <?php
            $n++;
                }
            ?>
        </table>
    </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4 style="text-align:center">
              <?php  echo "Vous enregistrer les Elements Constitutifs de l'UE ".$_GET['intituleUE'];?>
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

                <input type="text" name="Code_UE" placeholder="Code UE"class="form-control">
                <input type="text" name="Promotion"placeholder="Promotion" value="<?php //echo $_GET['id']; ?>" class="form-control">
                <input type="text" name="Intitule"placeholder="Intitulé EC" class="form-control">
                <input type="number" name="VHT" placeholder="Heures VHT" class="form-control">
                <input type="number" name="HTP" placeholder="Heures TP" class="form-control">
             
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


