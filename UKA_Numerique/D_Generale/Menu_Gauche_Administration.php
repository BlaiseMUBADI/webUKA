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
        <i class="bx bxl-c-plus-plus"></i>
        <span class="logo_name">Menus</span>
      </div>
      <ul class="nav-links m-0 p-0">

      <!-- ************* Le Premier Ménu et ces sous-menus*************** -->
        <li id="Li_Gestion_user">

          <a href="#" class="a_menu" style="color:red;">
            <i class="bx bx-grid-alt"> </i>
            <span class="links_name">Gestion USER</span>
          </a>

          <!-- Menu contextuel sur le menu Percton Frais-->
          <div id="Menu_contextuel_Gestion_user"

            class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
            style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_admin_fac.php<?php if($_SESSION['Categorie']=="Admin_Fac") echo"?page=gestion_user";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Comptes USER
              </span>
            </a>


            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Page_Principale.php<?php 
                  if($_SESSION['Categorie']=="compte_user") echo"?page=compte_user";
                  else echo"?page=non_acces"; ?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Utilisateurs
              </span>
            </a>

          </div>
        </li>
      <!-- ************* Fin premier menu Gestion USER *************** -->



      <!-- ************* Le Premier Ménu et ces sous-menus*************** -->
      <li id="Li_nommination">

        <a href="#" class="a_menu" style="color:red;">
          <i class="bx bx-grid-alt"> </i>
          <span class="links_name">Nomination</span>
        </a>

        <!-- Menu contextuel sur le menu Percton Frais-->
        <div id="Menu_contextuel_Gestion_user"

          class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
          style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

          <a class="a_menu dropdown-item fs-6 p-1 m-0" 
            href="Page_Principale.php<?php if($_SESSION['Categorie']=="Admin_Fac") echo"?page=gestion_user";
                else echo"?page=non_acces";?>">
            
            <span class="links_name text-center border" style="width: 100%;">
            Gestion Décanale
            </span>
          </a>


          

        </div>
      </li>
<!-- ************* Fin premier menu Gestion USER *************** -->






        
        <li>
          <a href="../index.php"
         
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Quitter</span>
          </a>
        </li>


      </ul>
</div>