
<nav style="color:white; background-color:#081d45; height: 13%;" 
class="mb-5 navbar navbar-expand-lg bg-body-tertiary fixed-top">

  <div class="m-0 p-0 " style="width:50%;color:white; font-weight:bold; font-size:x-large;">
    <i class="bx bx-menu sidebarBtn"></i>
    <span class="dashboard " > 
      <?php 
        if(@$_GET['page'])
        {
          if($_GET['page']=="admin_fac") echo "Administration";
          else if($_GET['page']=="gestion_user") echo "Gestion des utilisateurs";
        }
      ?>
    </span>
  </div>

  <div class="" > 
    <form action="" method="POST">
      <input class="btn" type="submit" name="Decon" value="Déconnexion" style="color:white;"/>
    </form>
    <i class="bx bx-search"></i>
  </div>


  <div class="profile-details ms-5 me-3 " style="height: 80%; ">
    <img src="<?php echo $_SESSION['Photo_profil'];?>" alt="Image" style="width: 90%; height: 100%;"/>
    <span class="admin_name" > 
      Matricule : <?php echo $_SESSION['MatriculeAgent'];?>  <br>
      Nom: <?php echo $_SESSION['Nom_user']; ?><br>
      Fonction : <?php  echo $_SESSION['Categorie']; ?>
    </span>
    <i class="bx bx-chevron-down"></i>
  </div>  
</nav>




