

<nav style="color:white; background-color:#081d45;"class="mb-5 navbar navbar-expand-lg bg-body-tertiary fixed-top">

  <div class="m-0 p-0 " style="color:white; font-weight:bold; font-size:x-large;">
    <i class="bx bx-menu sidebarBtn"></i>
    <span class="dashboard " > 
      <?php 
        if(@$_GET['page'])
        {
          if($_GET['page']=="admin_fac") echo "Administration";
          else if($_GET['page']=="gestion_user") echo "Gestion des utilisateurs";
          else if($_GET['page']=="gestion_UEs") echo "Faculté : ".$_SESSION['libelle_fac']." (G_UEs) ";          
          else if($_GET['page']=="gestion_SM_ECs") echo "Faculté : ".$_SESSION['libelle_fac']." (G_ECs) ";
          else if($_GET['page']=="gestion_Aligne_ECs") echo "Faculté : ".$_SESSION['libelle_fac']." (ALign_ECs) ";
          else if($_GET['page']=="gestion_Enseignants") echo "Faculté : ".$_SESSION['libelle_fac']." (G_Enseignants) ";
          else if($_GET['page']=="gestion_jury") echo "Faculté : ".$_SESSION['libelle_fac']." (G_Jury) ";
          else if($_GET['page']=="gestion_encodage") echo "Encodage côte  : ".$_SESSION['prommotion']." / En :  ".$_SESSION['libelle_fac'];
          else if($_GET['page']=="gestion_deliberation") echo "Délibération  : ".$_SESSION['prommotion']." / En :  ".$_SESSION['libelle_fac'];
        }
      ?>
    </span>
  </div>

  <div> 
    <form action="" method="POST">
      <input class="btn" type="submit" name="Decon" value="Déconnexion" style="color:white;"/>
    </form>
    <i class="bx bx-search"></i>
  </div>


  <div class="profile-details ms-5 me-3 " style="height: 80%; ">
    <img src="../Fonctions_PHP/Fonctions.php?id=<?php echo $_SESSION['MatriculeAgent']; ?>" alt="Image" style="width: 90%; height: 100%;"/>
    <span class="admin_name" > 
      Matricule : <?php echo $_SESSION['MatriculeAgent'];?>  <br>
      Nom: <?php echo $_SESSION['Nom_user']; ?><br>
      Fonction : <?php  echo $_SESSION['Categorie']; ?>
    </span>
    <i class="bx bx-chevron-down"></i>
  </div>  
</nav>




