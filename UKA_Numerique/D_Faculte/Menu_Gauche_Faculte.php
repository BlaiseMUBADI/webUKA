<?php 


if(!isset($_SESSION['MatriculeAgent']))
{
    header('location:../index.php');
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

      <!-- ************* MENU ET ITEMS SUR LA GESTION DES UEs*************** -->
        <li id="Li_Gestion_UEs">

          <a href="#" class="a_menu" style="color:red;">
            <i class="bx bx-grid-alt"> </i>
            <span class="links_name">Cours</span>
          </a>

          <!-- Menu contextuel sur le menu de la gestion des UES-->
          <div id="Menu_contextuel_Gestion_UE"

            class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
            style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_fac.php<?php 
              
                if($_SESSION['Categorie']=="Doyen" 
                || $_SESSION['Categorie']=="VD"
                || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_UEs";
                
                else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
                G. UEs
              </span>
            </a>

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_fac.php<?php 
              
                if($_SESSION['Categorie']=="Doyen" 
                || $_SESSION['Categorie']=="VD"
                || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_Aligne_ECs";
                
                else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
                Aligne_ECs
              </span>
            </a>

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_fac.php<?php 
              
                if($_SESSION['Categorie']=="Doyen" 
                || $_SESSION['Categorie']=="VD"
                || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_jury";
                
                else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
                Jury
              </span>
            </a>

          </div>
        </li>
      <!-- ************* Fin premier menu Gestion UEs *************** -->


      <!-- ************* MENU ET ITEMS SUR LA GESTION DES UEs*************** -->
      <li id="Li_Gestion_Encodage">

        <a href="#" class="a_menu" style="color:red;">
          <i class="bx bx-grid-alt"> </i>
          <span class="links_name">G. Côtes</span>
        </a>

        <!-- Menu contextuel sur le menu de la gestion des UES-->
        <div id="Menu_contextuel_Gestion_cote"

          class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
          style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

          <a class="a_menu dropdown-item fs-6 p-1 m-0" 
            href="Principale_fac.php<?php 
            
              if($_SESSION['Categorie']=="Secrétaire_jury") echo"?page=gestion_encodage";
              
              else echo"?page=non_acces";?>">
            
            <span class="links_name text-center border" style="width: 100%;">
              Encodage
            </span>
          </a>

          <a class="a_menu dropdown-item fs-6 p-1 m-0" 
            href="Principale_fac.php<?php 
            
              if($_SESSION['Categorie']=="Secrétaire_jury") echo"?page=gestion_deliberation";
              
              else echo"?page=non_acces";?>">
            
            <span class="links_name text-center border" style="width: 100%;">
              Délibération
            </span>
          </a>

        </div>
      </li>
      <!-- ************* Fin premier menu Gestion UEs *************** -->







      <!-- ************* Ménu gestion des enseignants  *************** -->
      <li id="Li_Gestion_Enseignants">

      <a href="#" class="a_menu" style="color:red;">
        <i class="bx bx-grid-alt"> </i>
        <span class="links_name">Enseignant</span>
      </a>

      <!-- Menu contextuel sur le menu de la gestion des UES-->
      <div id="Menu_contextuel_Gestion_Enseignants"

        class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
        style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

        <a class="a_menu dropdown-item fs-6 p-1 m-0" 
          href="Principale_fac.php<?php 
          
            if($_SESSION['Categorie']=="Doyen" 
            || $_SESSION['Categorie']=="VD"
            || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_Enseignants";
            
            else echo"?page=non_acces";?>">
          
          <span class="links_name text-center border" style="width: 100%;">
            G. Enseigants
          </span>
        </a>
        </a>

      </div>
      </li>
      <!-- ************* Fin premier menu Gestion UEs *************** -->








        
        <li>
          <a href="../index.php"
         
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Quitter</span>
          </a>
        </li>


      </ul>
</div>