<?php 

if(!isset($_SESSION['MatriculeAgent']))
{
    header('location:../index.php');
    exit;
}if(isset($_POST['Decon'])) 
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

        <li id="Li_Perception">

          <a href="#" class="a_menu">
            <i class="fas fa-money-bill-wave" style="color: #ffffffff !important;"></i>
            <span class="links_name">Perception Frais</span>
          </a>

          <!-- Menu contextuel sur le menu Perception Frais-->
          <div id="Menu_contextuel_Perception"
            class="dropdown-menu"
            data-header="Perception Frais"
            style="z-index: 99999 !important; position: absolute !important;">

            <a class="dropdown-item" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=guichet";
                  else echo"?page=non_acces";?>">
              <i class="fas fa-cash-register" style="color: #16a085 !important;"></i>
              <span class="links_name">Par Guichet</span>
            </a>

            <a class="dropdown-item" 
              href="Principale_perception.php<?php 
                  if($_SESSION['Categorie']=="Guichetier") echo"?page=banque";
                  else echo"?page=non_acces"; ?>">
              <i class="fas fa-university" style="color: #3498db !important;"></i>
              <span class="links_name">Par Banque</span>
            </a>

            <a class="dropdown-item" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=Rapport_paie";
                  else echo"?page=non_acces";?>">
              <i class="fas fa-file-invoice-dollar" style="color: #f39c12 !important;"></i>
              <span class="links_name">Rapport Journalier</span>
            </a>

            <a class="dropdown-item" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=manip_transaction";
                  else echo"?page=non_acces";?>">
              <i class="fas fa-exchange-alt" style="color: #9b59b6 !important;"></i>
              <span class="links_name">Manip Transaction</span>
            </a>
          </div>

          <!-- fin menu contextuel menu Perception Frais-->
          
        </li>




        <!---------------------------------- MENU AB --------------------------------------->
        <!---------------------------------------------------------------------------------->
        <li id="Li_Budget">
          
          <a href="#" class="a_menu">
            <i class="fas fa-calculator" style="color: #ffffffff !important;"></i>
            <span class="links_name">Budget</span>
          </a>

          <div id="Menu_contextuel_Budget"
            class="dropdown-menu"
            data-header="Gestion Budget"
            style="z-index: 99999 !important; position: absolute !important;">


            <a class="dropdown-item" 
              href="Principale_perception.php<?php  if($_SESSION['Categorie']=="Comptable") echo"?page=ab_taux_jours";
                  else echo"?page=non_acces";?>">
              <i class="fas fa-chart-line" style="color: #16a085 !important;"></i>
              <span class="links_name">Taux du Jour</span>
            </a>

            <a class="dropdown-item" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Comptable") echo"?page=ab_modalite_paie";
                  else echo"?page=non_acces";?>">
              <i class="fas fa-dollar-sign" style="color: #3498db !important;"></i>
              <span class="links_name">Fixation Frais</span>
            </a>

            <a class="dropdown-item" 
              href="Principale_perception.php<?php 
                  if($_SESSION['Categorie']=="Comptable") echo"?page=ab";
                  else echo"?page=non_acces";?>">
              <i class="fas fa-receipt" style="color: #f39c12 !important;"></i>
              <span class="links_name">Dépenses</span>
            </a>
          </div>
        </li>
        <!---------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------->

        <li>
          <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Comptable") echo"?page=Rapport_paie";else echo"?page=non_acces"; ?>"
          class="a_menu disabled">
            <i class="fas fa-user-tie" style="color: #ffffffff !important;"></i>
            <span class="links_name">Assistant Financier</span>
          </a>
        </li>

        <li>
          <a href="Principale_perception.php<?php if($_SESSION['Categorie']=="Comptable") echo"?page=Rapport_paie";else echo"?page=non_acces"; ?>"
          class="a_menu disabled">
            <i class="fas fa-cash-register" style="color: #ffffffff !important;"></i>
            <span class="links_name">Caisse</span>
          </a>
        </li>

        <li>
          <a href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=Inscription";else echo"?page=non_acces"; ?>"
          class="a_menu disabled">
            <i class="fas fa-file-signature" style="color: #ffffffff !important;"></i>
            <span class="links_name">Frais d'Inscription</span>
          </a>
        </li>

        <li>
          <a href="../index.php"
          class="a_menu disabled">
            <i class="fas fa-sign-out-alt" style="color: #ffffffff !important;"></i>
            <span class="links_name">Quitter</span>
          </a>
        </li>

      </ul>
</div>