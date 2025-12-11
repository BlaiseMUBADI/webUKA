
    <link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      

  <div class="container mt-5 "> 

    <?php 

    include("../../Connexion_BDD/Connexion_1.php");  
     
    if (isset($_POST['Creer'])) 
    {
    $message='';
     if(empty($_POST['Matricule'])) 
        {
        $message = "Le Matricule ne peut être vide";
         }
         else
         {
         
          $Matricule=htmlspecialchars($_POST['Matricule']);
          $login=htmlspecialchars($_POST['login']);
          $motdepasse=password_hash($_POST['motdepasse'],PASSWORD_BCRYPT);
          $motdepasseConf=htmlspecialchars($_POST['motdepasseConf']);
          $etat=$_POST['Etat'];
          $fonction=htmlspecialchars($_POST['Fonction']);
          
          $reqmatri = $con ->prepare("SELECT * FROM agent WHERE Mat_agent=?");
          $reqmatri  -> execute(array($Matricule));
          $Mat = $reqmatri->rowCount();
          //Tester la validité d'un numéro matricule agent
            if ($Mat==0) {
             $message="Matricule Agent non Atribué";
            }
            else
            {

          //Test Compte existe
          $reqmatri = $con ->prepare("SELECT * FROM compte_agent WHERE Mat_agent=?");
          $reqmatri  -> execute(array($Matricule));
          $MatExiste = $reqmatri->rowCount();
          $message=$etat;
          
          if ($_POST['motdepasse']!=$_POST['motdepasseConf']) 
          {
              $message="Mot de passe non identique";
          }
          
          else
          {
              $message="RAS";
              if ($MatExiste == 1) 
               {
                $message="Il existe déjà un Compte avec ce Numéro Matricule";
               }
               else
               {
                $query="INSERT INTO compte_agent 
                  (Mat_agent,Login,Mot_passe,Etat,Categorie) VALUES ('$Matricule','$login','$motdepasse','$etat', '$fonction')";
                $con->exec($query);
                $message="Le Compte a été créer avec Succès";

               }
              
          }
  
   
         }
}
} 
?>
       <div class="row "> 
        <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="card">
                  <div class="card-header">
                        <h4>Création de Compte Agent         
                            <a href="AfficherCompteAgent.php?=CompteExiste" class="btn btn-danger float-end">Afficher les Comptes existant</a>
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
                        <label>Matricule : </label>
                        <input type="text" name="Matricule" class="form-control" maxlength="30">
                      </div>
                      <div class=" mb-3">
                        <label>Nom de Connexion : </label>
                        <input type="text" name="login" class="form-control">
                      </div>
                      <div class=" mb-3">
                        <label>Mot de Passe :</label>
                        <input type="password" name="motdepasse" class="form-control">
                      </div>
                      <div class=" mb-3">
                        <label>Confirmer le Mot de Passe : </label>
                        <input type="password" name="motdepasseConf" class="form-control">
                      </div>
                        <div class=" mb-3">
                        <label>Etat : </label>
                        <select name="Etat">
                          <option value="Actif">Actif</option>
                          <option value="Inactif">Inactif</option>
                          <option value="Bloqué">Bloqué</option>
                        </select>
                      </div>
                      <div class=" mb-3">
                        <label>Fonction : </label>
                        <select name="Fonction">
                          <option value="Administrateur de Budget">Administrateur de Budget</option>
                          <option value="Comptable">Comptable</option>
                          <option value="Guichetier">Guichetier</option>
                          <option value="Guichetier">Encodeur</option>                         
                          <option value="Admin">Admin</option>
                          <option value="Admin_Fac">AdminFAC</option>
                        </select>
                      </div>
                    
                      <div class=" mb-3">
                            <button type="submit" name="Creer" class="btn btn-primary float-end">Créer</button>
                      </div>
                    </form>
                    </div>
            </div>
       </div>

    </div>
 </div>


