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
        <i class="bx bxl-c-plus-plus"></i>
        <span class="logo_name">Menus</span>
      </div>
      <ul class="nav-links m-0 p-0">

        <li id="Li_Perception">

          <a href="#" class="a_menu" style="color:red;">
            <i class="bx bx-grid-alt"> </i>
            <span class="links_name">Perception Frais</span>
          </a>

          <!-- Menu contextuel sur le menu Percton Frais-->
          <div id="Menu_contextuel_Perception"

            class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
            style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=guichet";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Par Guichet
              </span>
            </a>


            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php 
                  if($_SESSION['Categorie']=="Guichetier") echo"?page=banque";
                  else echo"?page=non_acces"; ?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Par Banque
              </span>
            </a>


            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=Rapport_paie";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Rapport Journalier
              </span>
            </a>

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=manip_transaction";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Manip Transact
              </span>
            </a>
          </div>

          <!-- fin menu contextuel menu Percton Frais-->
          
        </li>




        <!---------------------------------- MENU AB --------------------------------------->
        <!---------------------------------------------------------------------------------->
        <li id="Li_Budget">
          
          <a href="#" class="a_menu" >
            <i class="bx bx-grid-alt"> </i>
            <span class="links_name">Budget</span>
          </a>

          <div id="Menu_contextuel_Budget"

            class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
            style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

            


            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php  if($_SESSION['Categorie']=="Administrateur de Budget") echo"?page=ab_taux_jours";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Taux du Jour
              </span>
            </a>


            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php 
                  if($_SESSION['Categorie']=="Administrateur de Budget") echo"?page=ab";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Dépenses
              </span>
            </a>
            

          </div>
        </li>
        <!---------------------------------------------------------------------------------->



        <!--------------------------- MENU COMPTABLE ---------------------------------->
        <!---------------------------------------------------------------------------------->
        <li id="Li_Comptable">
          <a href="#" class="a_menu" >
            <i class="bx bx-grid-alt"> </i>
            <span class="links_name">Comptable</span>
          </a>

          <div id="Menu_contextuel_Comptable"

            class="dropdown-menu border border-rounded p-0 m m-0 p-3 z-3"
            style="background-color:#273746;color:white; box-shadow: 0px 3px 5px 0px white;">

            <a class="a_menu dropdown-item fs-6 p-1 m-0" 
              href="Principale_perception.php<?php if($_SESSION['Categorie']=="Comptable") echo"?page=ab_modalite_paie";
                  else echo"?page=non_acces";?>">
              
              <span class="links_name text-center border" style="width: 100%;">
              Fixation frais
              </span>
            </a>
            

          </div>
        </li>
        <!---------------------------------------------------------------------------------->

        <li>
          <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Comptable") echo"?page=Rapport_paie";else echo"?page=non_acces"; ?>"
      
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Assistant Financier</span>
          </a>
        </li>

        <li>
          <a href="Principale_perception.php<?php if($_SESSION['Categorie']=="Comptable") echo"?page=Rapport_paie";else echo"?page=non_acces"; ?>"
         
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Caisse</span>
          </a>
        </li>

        
        <li>
          <a href="../index.php"
         
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Quitter</span>
          </a>
        </li>
        <li>
          <a href="Principale_perception.php<?php if($_SESSION['Categorie']=="Guichetier") echo"?page=Inscription";else echo"?page=non_acces"; ?>"
         
          class="a_menu disabled">
            <i class="bx bx-message"></i>
            <span class="links_name ">Frais d'Inscription</span>
          </a>
        </li>

      </ul>
</div>