
<nav style="color:white; background-color:#081d45;"class="mb-5 navbar navbar-expand-lg bg-body-tertiary fixed-top">

        <div class="m-0 p-0 " style="width:50%;color:white; font-weight:bold; font-size:x-large;"id="profilguiche">
          <i class="bx bx-menu sidebarBtn"></i>
          <span class="dashboard " > 
            <?php 
              if(@$_GET['page'])
              {
                if($_GET['page']=="Dashboard") echo "Tableau de bord";
                else if($_GET['page']=="AjoutAgent") echo "Enregistement du Personnel";
                else if($_GET['page']=="ListeToutPersonnel") echo "Liste declarative du personnel";
                else if($_GET['page']=="ListeAgent") echo "Impression par catégorie";
                else if($_GET['page']=="EtatPaie") echo "Mise à jour de la mécanisation";
                else if($_GET['page']=="Primé") echo "Impression liste des agents ayant la Prime";
                else if($_GET['page']=="Base") echo "Impression liste des agents ayant la Base";
                else if($_GET['page']=="Prime_et_base") echo "Impression liste des agents ayant la Base et la Prime";

              }
            ?>
          </span>
        </div>


        <div class="" style="display:bloc"id="Deconnexion" > 
          <form action="" method="POST">
            <input class="btn" type="submit" name="Decon" value="Déconnexion" style="color:white;"/>
          </form>
          <i class="bx bx-search"></i>
        </div>


        <div class="profile-details ms-5 me-3 " style="height: 80%; "style="display:bloc"id="profiluser">
          <img src="<?php echo $_SESSION['Photo_profil'];?>" alt="Image" style="width: 90%; height: 100%;"/>
          <span class="admin_name" > 
            Matricule : <?php echo $_SESSION['MatriculeAgent'];?>  <br>
            Nom: <?php echo $_SESSION['Nom_user']; ?><br>
            Fonction : <?php  echo $_SESSION['Categorie']; ?>
          </span>
          <i class="bx bx-chevron-down"></i>
      </div>

</nav>




