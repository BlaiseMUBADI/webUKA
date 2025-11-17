<nav style="color:white; background-color:#081d45;"class="mb-5 navbar navbar-expand-lg bg-body-tertiary fixed-top">
  <div class="m-0 p-0 " style="width:50%;color:white; font-weight:bold; font-size:x-large;"id="profilguiche">
    <i class="bx bx-menu sidebarBtn"></i>
    <span class="dashboard " > 
      <?php 
        if(@$_GET['page'])
        {
          if($_GET['page']=="guichet") echo "PERCEPTION Par ( Guichet )";
          else if($_GET['page']=="banque") echo "PERCEPTION Par ( Banque )";
          else if($_GET['page']=="Rapport_paie") echo "Rapport Journalier";

          
          else if($_GET['page']=="ab_modalite_paie") echo "Administrateur du Budget (Fixation frais)";
          else if($_GET['page']=="ab_taux_jours") echo "Administrateur du Budget (Fixation Taux du Jour)";
          else if($_GET['page']=="manip_transaction") echo "Manipulations Transactions";
          else if($_GET['page']=="admin") echo "Administration";
          else if($_GET['page']=="Inscription") echo "Inscription";
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
    <img src="../Fonctions_PHP/Fonctions.php?id=<?php echo $_SESSION['MatriculeAgent']; ?>" alt="Image" style="width: 90%; height: 100%;"/>
    <span class="admin_name" > 
      Matricule : <?php echo $_SESSION['MatriculeAgent'];?>  <br>
      Nom: <?php echo $_SESSION['Nom_user']; ?><br>
      Fonction : <?php  echo $_SESSION['Categorie']; ?>
    </span>
    <i class="bx bx-chevron-down"></i>
</div>

</nav>




