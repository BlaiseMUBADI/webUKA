
    <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      
       <?php 
             include("../../Connexion_BDD/Connexion_1.php");  
             $Mat_agent=htmlspecialchars($_GET['page']);
             $message="";
              if (isset($_POST['ModifierNomUser'])) 
                      {
                          $login=htmlspecialchars($_POST['login']);
                          $sql = "UPDATE compte_agent SET Login = :Login WHERE Mat_agent = :Mat";
                          $stmt = $con->prepare($sql);

                          $stmt->bindParam(':Login', $login);
                          $stmt->bindParam(':Mat', $Mat_agent);
                      if ($stmt->execute()) 
                          {
                             $message= "Le Nom utilisateur a été mises à jour avec succès";
                          } 
                    else {
                             $message= "Échec de la mise à jour du Nom utilisateur";
                          }

                      }
               elseif (isset($_POST['ModifierMotdepasse'])) 
                      {
                          $login=htmlspecialchars($_POST['login']);
                          $motdepasse=sha1($_POST['motdepasse']);
                          $motdepasseConf=htmlspecialchars($_POST['motdepasseConf']);
                          $fonction=htmlspecialchars($_POST['Fonction']);
                         
                          if ($_POST['motdepasse']!=$_POST['motdepasseConf']) 
                          {
                            $message="Mot de passe non identique";
                          }
                          else
                          {                             
                                $sql = "UPDATE compte_agent SET Mot_passe = :motdepasse WHERE Mat_agent = :Mat";
                                $stmt = $con->prepare($sql);

                                $stmt->bindParam(':motdepasse', $motdepasse);
                                $stmt->bindParam(':Mat', $Mat_agent);
                            if ($stmt->execute()) 
                                {
                                   $message= "Le Mot de Passe a été mis à Jour Avec Succès";
                                } 
                            else {
                                   $message= "Échec de la mise à jour du mo de passe";
                                }
                          }
                      
                      }
               elseif (isset($_POST['ModifierEtat'])) 
                      {
                          $etat=htmlspecialchars($_POST['Etat']);
                          $sql = "UPDATE compte_agent SET Etat = :etat WHERE Mat_agent = :Mat";
                          $stmt = $con->prepare($sql);

                          $stmt->bindParam(':etat', $etat);
                          $stmt->bindParam(':Mat', $Mat_agent);
                      if ($stmt->execute()) 
                          {
                            if ($etat=="Non Actif") {
                             $message= "Le Compte a été Désactiver avec succès";   
                            }
                            else
                             {
                             $message= "Le Compte a été Activé avec Succès";                              
                             } 
                          } 
                    else {
                             $message= "Échec de la mise à jour du Nom utilisateur";
                          }

                      }
              elseif (isset($_POST['ModifierFonction'])) 
                      {
                          $cat=htmlspecialchars($_POST['Fonction']);
                          $sql = "UPDATE compte_agent SET Categorie = :Cat WHERE Mat_agent = :Mat";
                          $stmt = $con->prepare($sql);

                          $stmt->bindParam(':Cat', $cat);
                          $stmt->bindParam(':Mat', $Mat_agent);
                      if ($stmt->execute()) 
                          {
                             $message= "La Fonction a été mise à jour avec succès";
                          } 
                    else {
                             $message= "Échec de la mise à jour";
                          }

                      }
                      else
                      {
                        $message="Spécifier l'opération en ciquant sur l'un de bouton";
                      } 
              ?>
  <div class="container mt-5 "> 

   
       <div class="row "> 
        <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="card">
                  <div class="card-header">
                        <h4>Modifier un Compte Agent         
                            <a href="AfficherCompteAgent.php?=CompteExiste" class="btn btn-danger float-end">Afficher les Comptes existant</a>
                        </h4>
                         <?php 
                            if (isset($message)) {

                                echo '<font color="red" size="5%">'. $message."</font>";
                                
                            }
                         ?>
                  </div>

         
                  <div class="card-body">
                    <?php 

                          if (isset($_GET['page'])) 
                      {
                        
                        $reqmatri = $con ->prepare("SELECT * FROM compte_agent WHERE Mat_agent=?");
                        $reqmatri  -> execute(array($Mat_agent));
                        $MatExiste = $reqmatri->rowCount();

                        if ($MatExiste>0) {
                $resultat = $reqmatri->fetchAll(PDO::FETCH_ASSOC); 
                          
                        foreach($resultat as $ligne)
                {
                    ?>
                    <form action="" method="POST">
                   

                      <div class=" mb-3">
                        <b><label>Matricule : </label></b>
                        <input type="text" name="Matricule" value="<?php echo $ligne['Mat_agent']; ?>" disabled class="form-control" maxlength="30">
                      </div>
                      <div class=" mb-3 " >

                        <b><label>Nom de Connexion : </label></b><br>
                        <input style="width: 88%;display: inline-block;" type="text" name="login"  value="<?php echo $ligne['Login']; ?>" class="form-control">
                            <tr><td>

                            <button style="position: relative;display: inline-block;" type="submit" name="ModifierNomUser" class="btn btn-primary float-end">Modifier</button>
</td></tr>
                      </div>
                      <div class=" mb-3">
                        <b><label>Mot de Passe :</label></b> <br>
                        <input style="width: 88%;display: inline-block;" type="password" name="motdepasse" value="<?php echo ($ligne['Mot_passe']); ?>" class="form-control">
                      </div>
                      <div class=" mb-3">
                       <b> <label>Confirmer le Mot de Passe : </label></b>
                        <input style="width: 88%;display: inline-block;" type="password" name="motdepasseConf" value="<?php echo ($ligne['Mot_passe']); ?>" class="form-control">
                        <button style="position: relative;display: inline-block;" type="submit" name="ModifierMotdepasse" class="btn btn-primary float-end">Modifier</button>
                      </div>
                         <div class=" mb-3">
                        <b><label>Etat : </label></b><br>

                        <select style="width: 88%;display: inline-block;" name="Etat">
                         
                          <option value="<?php echo ($ligne['Etat']); ?>" selected> <?php echo ($ligne['Etat']); ?></option>
                          <option value="Actif">Actif</option>
                          <option value="Non Actif">Non Actif</option>
                          
                        </select>
                         <button style="position: relative;display: inline-block;" type="submit" name="ModifierEtat" class="btn btn-primary float-left"> Modifier</button>
                        </div>



                      <div class=" mb-3">
                       <b><label>Fonction : </label></b><br>
                        <select style="width: 88%;display: inline-block;" name="Fonction">
                          <option value="Administrateur de Budget">Administrateur du Budget</option>
                          <option value="Comptable">Comptable</option>
                          <option value="Guichetier">Guichetier</option>
                          <option value="Encodeur">Encodeur</option>
                          <option value="Admin">Admin</option>
                        </select>
                        <button style="position: relative;display: inline-block;" type="submit" name="ModifierFonction" class="btn btn-primary float-left"> Modifier</button>
                      </div>
                    
                      <div class=" mb-3">
                      <input type="file" name="Imageprofil" class="form-control"  onchange="previewImage(event)" accept="image/*">                                           
                      <br>
                      <center>
                        <img id="preview" src="#" alt="Image preview" style="display:none; height: 10rem; width: 10rem; border-radius: 8px; mt-2">
                       </center>
                            <script>
                        function previewImage(event)
                        {
                          console.log("dans la fonction");
                          var reader = new FileReader();
                          reader.onload = function () {
                            var preview = document.getElementById('preview');
                            preview.src = reader.result;
                            preview.style.display = 'block';
                        }
                        reader.readAsDataURL(event.target.files[0]);
                    }
                    </script>
                        <button style="position: relative;display: inline-block;" type="submit" name="ModifierPhoto" class="btn btn-primary float-center"> Modifier</button>

                      </div>
                    </form>
             <?php
              }
            }
              else{
                echo "Compte inéxistant";
              } 
              }
             ?>
                    </div>
            </div>
       </div>

    </div>
 </div>


