<form method="POST" enctype="multipart/form-data" action="" style="width:100%;">          
          
        <label style="color: white;">Faculté:</label>     

        <select  name="filiere" id="Id_lieu_paiement"  
          style="border: 1px solsid green;width:15%">
          <option value=""></option>
            <?php 
              $req="SELECT * from lieu_paiement ORDER BY lieu_paiement.Libelle_lieu ASC";
              $data= $con-> query($req);
              while ($ligne=$data->fetch())
              {
            ?>
                <option style=" width:100%;" value=<?php echo $ligne["idLieu_paiement"];?>><?php echo $ligne['Libelle_lieu']?></option>
                
                <?php 
              }
                ?>
        </select>
          <label style="color: white;">Faculté:</label>     

          <select id="filiere" style="width:30%">
            <option value=""></option>
              <?php 
                $req="select * from filiere order by Libelle_Filiere";
                $data= $con-> query($req);
                while ($ligne=$data->fetch())
                {
              ?>
                  <option style=" width:100%;"value=<?php echo $ligne['IdFiliere'];?>><?php echo $ligne['Libelle_Filiere']?></option>
                  
                  <?php 
                }
                  ?>
          </select>


          
          <label style="color: white;border:1ox solid red;" for="date_paiement">Date de :</label>
          <input type="date" class="form-control p-0 m-0 text-center" id="date_paiement" style="border: 1px solsid green;width:15%">
          


          <label style="color: white;">Année:</label>
          <select id="Id_an_acad" style="width: 12%;" >
                
                  <?php 
                    //Requette de sélection Année Académique
                    $req="select * from annee_academique order by Annee_debut desc";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
                    {
                    ?>

                    <option value="<?php echo $ligne['idAnnee_Acad']?>"><?php echo $ligne['Annee_debut'];?>-<?php echo $ligne['Annee_fin'];?></option>

                    <?php 
                      }
                    ?>
          </select>
        </form>