console.log("nous sommes dans le menu");
var page="";

function Deconnexion_encodage(){
  console.log('jesuis dans quiter');
  window.location.href="../Fonctions_PHP/Deconnexion.php";
   //const url='../Fonctions_PHP/Deconnexion.php?valeur_envoyee=BIEN';  
    //fetch(url); 
}

function option1() {
    page="Attestation";
    window.location.href='index.php?page='+page;
}

function option2() {
    page="FicheEtudiant";
    window.location.href='../D_Administration/Principal.php?page='+page; 
}

function option3() {
    page="Palmares";
    window.location.href='index.php?page='+page;
}

function option4() {
    page="Changement_Filiere";
    window.location.href='index.php?page='+page;
}

function option5() {
    page="encodage";
    window.location.href='../D_Encodage/index.php?page='+page;
}
function option6() {
    page="Inscription";
    window.location.href='index.php?page='+page;
}


