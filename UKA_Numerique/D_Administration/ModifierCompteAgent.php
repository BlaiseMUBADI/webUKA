
    <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      
       <?php 
          session_start();
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
              
                      if (isset($_POST['ModifierPhoto'])) {
                        if (isset($_FILES['Imageprofil']) && $_FILES['Imageprofil']['error'] == 0) {
                            try {
                                // Vérifier la connexion à la base de données
                                if (!$con) {
                                    die("Erreur de connexion à la base de données.");
                                }
                    
                                // Débogage : Vérifier les détails du fichier uploadé
                                print_r($_FILES['Imageprofil']);
                    
                                // Vérifier si le fichier temporaire existe
                                if (!file_exists($_FILES['Imageprofil']['tmp_name'])) {
                                    echo "Erreur : fichier introuvable sur le serveur.";
                                    exit;
                                }
                    
                                // Autoriser uniquement certains formats d'image
                                $allowedTypes = ['image/jpeg', 'image/png'];
                                if (!in_array($_FILES['Imageprofil']['type'], $allowedTypes)) {
                                    echo "Erreur : format de fichier non autorisé.";
                                    exit;
                                }
                    
                                // Vérifier la taille du fichier (max 5 Mo)
                                if ($_FILES['Imageprofil']['size'] > 5 * 1024 * 1024) {
                                    echo "Erreur : fichier trop volumineux.";
                                    exit;
                                }
                    
                                // Lecture du fichier
                                $photo = file_get_contents($_FILES["Imageprofil"]["tmp_name"]);
                                $nomImage = $_FILES["Imageprofil"]["name"];
                                $typeImage = $_FILES["Imageprofil"]["type"];
                                $matricule = $_SESSION['agent']['matr'];
                    
                                // Préparation de la requête SQL
                                $sql = "UPDATE compte_agent SET Photo_profil=:photo, Nom_image=:nom, Type_image=:typeimage WHERE Mat_agent =:matricule";
                                $stmt = $con->prepare($sql);
                                $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB); // Sécurisation du fichier binaire
                                $stmt->bindParam(':nom', $nomImage, PDO::PARAM_STR);
                                $stmt->bindParam(':typeimage', $typeImage, PDO::PARAM_STR);
                                $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
                    
                                // Exécution de la requête
                                if ($stmt->execute()) {
                                    echo "<script language='javascript'>alert('Votre photo de profil a été modifiée avec succès');</script>";
                                } else {
                                    echo "Erreur lors de la mise à jour.";
                                }
                    
                            } catch (Exception $e) {
                                echo "Erreur : " . $e->getMessage();
                            }
                        } else {
                            echo "Erreur : fichier non valide.";
                        }
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
                    $_SESSION['agent']['matr'] = $ligne['Mat_agent']
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
                       <select name="Fonction" id="" class="form-select border-0 shadow-sm" onchange="Affichage_promotion_toutes()">
                            <option value="">Sélectionner</option>
                            <?php 
                            $reponse = $con->query ('SELECT * FROM fonction_user ORDER BY id');
                            while ($ligne = $reponse->fetch()) { ?>
                                <option value="<?php echo $ligne['Designation'];?>"><?php echo $ligne['Designation'];?></option>
                            <?php } ?>
                        </select>
                        <button style="position: relative;display: inline-block;" type="submit" name="ModifierFonction" class="btn btn-primary float-left"> Modifier</button>
                      </div>
                    
                      <div class=" mb-3">
                        <label>Date Création :</label>
                        <input type="Date" name="Datecreation"id="datecrea" class="form-control">
                      </div>
                     
                           
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



</script>