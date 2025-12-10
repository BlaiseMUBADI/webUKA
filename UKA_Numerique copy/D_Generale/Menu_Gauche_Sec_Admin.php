<?php 
if(!isset($_SESSION['MatriculeAgent']))
{
    header('location:../Index.php');
    exit;
}
if(isset($_POST['Decon'])) 
{
   header('location:Fonctions_PHP/Deconnexion.php');
}
?>

<div class="sidebar m-0 p-0 " id="a_menu" style="font-size: 1.5em;font-family:Perpetua;">
    <div class="logo-details">
        <i class="bx bxl-c-plus-plus"></i>
        <span class="logo_name">Menus</span>
    </div>
    <ul class="nav-links m-0 p-0">
        <li id="Li_Dashboard">
            <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") echo"?page=Dashboard"; else echo"?page=non_acces";?>" class="a_menu" style="color:white;">
                <i class="fas fa-chart-line"></i> &nbsp &nbsp
                <span class="links_name"style="font-size: 0.8em;">Tableau de bord</span>
            </a>  
        </li>
        <li id="Li_Ajout_Agent">
            <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") echo"?page=AjoutAgent"; else echo"?page=non_acces";?>" class="a_menu" style="color:white;">
                <i class="fas fa-user-plus"></i> &nbsp &nbsp
                <span class="links_name"style="font-size: 0.8em;">Ajouter un agent</span>
            </a>  
        </li>

        <li id="Li_Liste_Complete">
            <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") echo"?page=ListeToutPersonnel"; else echo"?page=non_acces";?>" class="a_menu" style="color:white;">
                <i class="fas fa-print icon-style"> </i>&nbsp &nbsp
                <span class="links_name"style="font-size: 0.8em;">Liste complète</span>
            </a>
        </li>

        <li id="Li_Liste_Agent">
            <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") echo"?page=ListeAgent"; else echo"?page=non_acces";?>" class="a_menu" style="color:white;">
                <i class="fas fa-print icon-style"> </i>&nbsp &nbsp 
                <span class="links_name"style="font-size: 0.8em;">Liste par catégorie</span>
            </a>
        </li>

        <li id="Li_Etat_Paie">
            <a href="javascript:void(0);" class="a_menu" style="color:white;" onclick="toggleSubMenu('#Li_Etat_Paie .submenu');">
                <i class="fas fa-dollar-sign icon-style icon-white"></i>&nbsp &nbsp &nbsp
                <span class="links_name"style="font-size: 0.8em;">Mécanisation</span>
            </a>
            <ul class="submenu" style="font-size: 20px;">
                <li><a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") 
                echo"?page=EtatPaie"; else echo"?page=non_acces";?>" class="a_menu" >
                <i class="fas fa-sync-alt icon-style"></i>&nbsp &nbsp Mise à jour</a></li>
                <li><a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") 
                echo"?page=Primé"; else echo"?page=non_acces";?>"><i class="fas fa-check icon-style"></i>&nbsp &nbsp Avec Prime</a></li>
                <li><a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") 
                echo"?page=Base"; else echo"?page=non_acces";?>"><i class="fas fa-check icon-style"></i>&nbsp &nbsp Avec Base</a></li>
                <li><a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") 
                echo"?page=Prime_et_base"; else echo"?page=non_acces";?>"><i class="fas fa-check icon-style"></i>&nbsp &nbsp Avec Prime et Base</a></li>
                
                <li><a href="Page_Principale.php?page=Sans_Base"><i class="fas fa-times icon-style "style="color:red;"></i>&nbsp &nbsp Sans prime</a></li>
                <li><a href="Page_Principale.php?page=Non_Prime"><i class="fas fa-times icon-style"style="color:red;"></i>&nbsp &nbsp Sans Base</a></li>
                <li><a href="Page_Principale.php?page=Non_Prime_et_sans_Base"><i class="fas fa-times icon-style"style="color:red;"></i>&nbsp &nbsp Sans Base et Prime</a></li>
            </ul>
        </li>
        <li id="Li_Paie_Locale">
            <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") echo"?page=PaieLocale"; else echo"?page=non_acces";?>" class="a_menu" style="color:white;">
                <i class="fas fa-coins"></i> &nbsp &nbsp
                <span class="links_name"style="font-size: 0.8em;">Paie locale</span>
            </a>  
        </li>
        <li id="Li_Paie_Admin">
            <a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") echo"?page=Admin"; else echo"?page=non_acces";?>" class="a_menu" style="color:white;">
                <i class="fas fa-coins"></i> &nbsp &nbsp
                <span class="links_name"style="font-size: 0.8em;">Admin</span>
            </a>  
        </li>
        <li id="Li_Sanction">
        <a href="javascript:void(0);" class="a_menu" style="color:white;" onclick="toggleSubMenu('#Li_Sanction .submenuSanction');">
                <i class="fas fa-gavel-plus icon-style" style="color:red;"> </i>&nbsp &nbsp 
                <span class="links_name"style="font-size: 0.8em;">Action displinaire</span>
            </a>
            <ul class="submenuSanction" style="font-size: 20px;">
                <li><a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") 
                echo"?page=Sanctionner"; else echo"?page=non_acces";?>" class="a_menu" >
                <i class="fas fa-gavel icon-style"style="color:red;"></i>&nbsp &nbsp Sanctioner</a></li>
                <li><a href="Page_Principale.php<?php if($_SESSION['Categorie']=="Assistant Administratif") 
                echo"?page=Primé"; else echo"?page=non_acces";?>"><i class="fas fa-check icon-style"></i>&nbsp &nbsp Statistique</a></li>
            </ul>
        </li>
        
        <li id="Li_Dossier">
            <a href="#" class="a_menu" style="color:yellow;">
                <i class="fas fa-folder-plus icon-style"> </i>&nbsp &nbsp &nbsp
                <span class="links_name">Ajouter un dossier</span>
            </a> 
        </li>

        <li id="Li_Dossier">
            <a href="#" class="a_menu" style="color:yellow;">
                <i class="fas fa-search icon-style"> </i>&nbsp &nbsp &nbsp
                <span class="links_name">Rechercher un dossier</span>
            </a> 
        </li>

        <li id="Li_Supprimer">
            <a href="#" class="a_menu" style="color:yellow;">
                <i class="fas fa-trash icon-style"> </i>&nbsp &nbsp &nbsp
                <span class="links_name">Supprimer un agent</span>
            </a> 
        </li>
    </ul>
</div>

<script>
  function toggleSubMenu(submenuId) {
  var submenu = document.querySelector(submenuId);
  submenu.style.display = (submenu.style.display === 'none' || submenu.style.display === '') ? 'block' : 'none';
}

</script>

<!-- CSS -->
<style>
  .submenu, .submenuSanction {
  display: none; /* Cacher les sous-menus par défaut */
  position: absolute; /* Positionner par rapport à l'élément parent */
  top: 100%; /* Placer juste en dessous de l'élément parent */
  left: 0;
  width: 100%; /* Optionnel : largeur égale à celle du parent */
  background-color: #333;
  border-radius: 5px;
  z-index: 1000; /* Assurer que le sous-menu soit au-dessus des autres éléments */
}

.submenu li, .submenuSanction li {
  padding: 10px;
}

.submenu li a, .submenuSanction li a {
  color: white;
  text-decoration: none; /* Ajouter ceci si tu veux éviter les soulignements */
}

.submenu li a:hover, .submenuSanction li a:hover {
  background-color: #444; /* Changer la couleur de fond au survol */
}

</style>
