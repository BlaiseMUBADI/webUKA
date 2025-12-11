<?php 


if(!isset($_SESSION['MatriculeAgent']))
{
    header('location:../Index.php');
    exit;
}
if(isset($_POST['Decon'])) 
{
   header('location:../Fonctions_PHP/Deconnexion.php');

}
?>


<div class="sidebar m-0 p-0 " id="a_menu">
      <?php 

      if(!isset($_SESSION['MatriculeAgent']))
      {
          header('location:../Index.php');
          exit;
      }
      if(isset($_POST['Decon'])) 
      {
         header('location:../Fonctions_PHP/Deconnexion.php');
      }
      ?>

      <div class="sidebar m-0 p-0 " id="a_menu">
        <div class="logo-details">
          <i class="fas fa-home" style="color: #3498db !important;"></i>
          <span class="logo_name">MENUS</span>
        </div>
        <ul class="nav-links m-0 p-0">

          <!-- ************* MENU GESTION USER *************** -->
          <li id="Li_Gestion_user">
            <a href="#" class="a_menu">
              <i class="fas fa-user-cog" style="color: #16a085 !important;"></i>
              <span class="links_name">Gestion USER</span>
            </a>
            <div id="Menu_contextuel_Gestion_user" class="dropdown-menu" data-header="Gestion USER" style="z-index: 99999 !important; position: absolute !important;">
              <a class="dropdown-item" href="Principale_admin_fac.php<?php if($_SESSION['Categorie']=="Admin_Fac") echo"?page=gestion_user"; else echo"?page=non_acces";?>">
                <i class="fas fa-users" style="color: #f39c12 !important;"></i>
                <span class="links_name">Comptes USER</span>
              </a>
              <a class="dropdown-item" href="Page_Principale.php<?php if($_SESSION['Categorie']=="compte_user") echo"?page=compte_user"; else echo"?page=non_acces";?>">
                <i class="fas fa-user" style="color: #3498db !important;"></i>
                <span class="links_name">Utilisateurs</span>
              </a>
            </div>
          </li>
          <!-- ************* Fin menu Gestion USER *************** -->

          <!-- ************* MENU NOMINATION *************** -->
          <li id="Li_nommination">
            <a href="#" class="a_menu">
              <i class="fas fa-user-tie" style="color: #16a085 !important;"></i>
              <span class="links_name">Nomination</span>
            </a>
            <div id="Menu_contextuel_Nomination" class="dropdown-menu" data-header="Nomination" style="z-index: 99999 !important; position: absolute !important;">
              <a class="dropdown-item" href="Page_Principale.php<?php if($_SESSION['Categorie']=="Admin_Fac") echo"?page=gestion_user"; else echo"?page=non_acces";?>">
                <i class="fas fa-user-shield" style="color: #f39c12 !important;"></i>
                <span class="links_name">Gestion Décanale</span>
              </a>
            </div>
          </li>
          <!-- ************* Fin menu Nomination *************** -->

          <!-- ************* MENU QUITTER *************** -->
          <li>
            <a href="../index.php" class="a_menu disabled">
              <i class="fas fa-sign-out-alt" style="color: #ffffffff !important;"></i>
              <span class="links_name">Quitter</span>
            </a>
          </li>

        </ul>
      </div>





        
        <li>
          <a href="../index.php"
         
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Quitter</span>
          </a>
        </li>


      </ul>
</div>