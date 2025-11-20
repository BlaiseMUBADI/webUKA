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
        <i class="fas fa-home" style="color: #3498db !important;"></i>
        <span class="logo_name">MENUS</span>
      </div>
      <ul class="nav-links m-0 p-0">

      <!-- ************* MENU ET ITEMS SUR LA GESTION DES UEs*************** -->
        <li id="Li_Gestion_UEs">

          <a href="#" class="a_menu">
            <i class="fas fa-graduation-cap" style="color: #ffffffff !important;"></i>
            <span class="links_name">Cours</span>
          </a>

          <!-- Menu contextuel sur le menu de la gestion des UES-->
          <div id="Menu_contextuel_Gestion_UE" class="dropdown-menu" data-header="Gestion Cours"style="z-index: 99999 !important; position: absolute !important;">
            <a class="dropdown-item" href="Principale_fac.php<?php 
              
                if($_SESSION['Categorie']=="Doyen" 
                || $_SESSION['Categorie']=="VD"
                || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_UEs";
                
                else echo"?page=non_acces";?>">
              <i class="fas fa-book" style="color: #16a085 !important;"></i>
              <span class="links_name">Gestion UEs</span>
            </a>

            <a class="dropdown-item" href="Principale_fac.php<?php 
              
                if($_SESSION['Categorie']=="Doyen" 
                || $_SESSION['Categorie']=="VD"
                || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_Aligne_ECs";
                
                else echo"?page=non_acces";?>">
              <i class="fas fa-align-justify" style="color: #3498db !important;"></i>
              <span class="links_name">Aligner ECs</span>
            </a>

            <a class="dropdown-item" href="Principale_fac.php<?php 
              
                if($_SESSION['Categorie']=="Doyen" 
                || $_SESSION['Categorie']=="VD"
                || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_jury";
                
                else echo"?page=non_acces";?>">
              <i class="fas fa-users" style="color: #f39c12 !important;"></i>
              <span class="links_name">Gestion Jury</span>
            </a>

          </div>
        </li>
      <!-- ************* Fin premier menu Gestion UEs *************** -->


      <!-- ************* MENU ET ITEMS SUR LA GESTION DES UEs*************** -->
      <li id="Li_Gestion_Encodage">

        <a href="#" class="a_menu">
          <i class="fas fa-chart-line" style="color: #ffffffff !important;"></i>
          <span class="links_name">G. Côtes</span>
        </a>

        <!-- Menu contextuel sur le menu de la gestion des Côtes-->
        <div id="Menu_contextuel_Gestion_cote"
          class="dropdown-menu"
          data-header="Gestion Côtes"
          style="z-index: 99999 !important; position: absolute !important;">

          <a class="dropdown-item" 
            href="Principale_fac.php<?php 
            
              if($_SESSION['Categorie']=="Secrétaire_jury") echo"?page=gestion_encodage";
              
              else echo"?page=non_acces";?>">
            <i class="fas fa-keyboard" style="color: #16a085 !important;"></i>
            <span class="links_name">Encodage</span>
          </a>

          <a class="dropdown-item" 
            href="Principale_fac.php<?php 
            
              if($_SESSION['Categorie']=="Secrétaire_jury") echo"?page=gestion_deliberation";
              
              else echo"?page=non_acces";?>">
            <i class="fas fa-gavel" style="color: #3498db !important;"></i>
            <span class="links_name">Délibération</span>
          </a>

        </div>
      </li>
      <!-- ************* Fin premier menu Gestion UEs *************** -->







      <!-- ************* Ménu gestion des enseignants  *************** -->
      <li id="Li_Gestion_Enseignants">

      <a href="#" class="a_menu">
        <i class="fas fa-chalkboard-teacher" style="color: #ffffffff !important;"></i>
        <span class="links_name">Enseignant</span>
      </a>

      <!-- Menu contextuel sur le menu de la gestion des Enseignants-->
      <div id="Menu_contextuel_Gestion_Enseignants"
        class="dropdown-menu"
        data-header="Actions Enseignant"
        style="z-index: 99999 !important; position: absolute !important;">

        <a class="dropdown-item" href="Principale_fac.php<?php 
          
            if($_SESSION['Categorie']=="Doyen" 
            || $_SESSION['Categorie']=="VD"
            || $_SESSION['Categorie']=="Sec_facultaire") echo"?page=gestion_Enseignants";
            
            else echo"?page=non_acces";?>">
          <i class="fas fa-user-tie" style="color: #16a085 !important;"></i>
          <span class="links_name">Gérer Enseignants</span>
        </a>

      </div>
      </li>
      <!-- ************* Fin premier menu Gestion UEs *************** -->








        
        <li>
          <a href="../index.php" class="a_menu disabled">
            <i class="fas fa-sign-out-alt" style="color: #ffffffff !important;"></i>
            <span class="links_name">Quitter</span>
          </a>
        </li>


      </ul>
</div>