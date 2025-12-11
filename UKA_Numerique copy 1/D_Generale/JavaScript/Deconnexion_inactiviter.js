var temps_log;
var Temps_inactivte = 50* 60 * 1000; // 5 minutes en millisecondes

// Fonction pour réinitialiser le compte à rebours
function resetLogoutTimer() {  
  clearTimeout(temps_log);
  temps_log = setTimeout(Deconnecter, Temps_inactivte);
}

// Fonction de déconnexion
function Deconnecter() {
  // Ici on ouvre la page web de deconnexion 
  window.location.href = "Fonctions_PHP/Deconnexion.php";
}

// Écouter les événements d'activité de l'utilisateur
document.addEventListener("mousemove", resetLogoutTimer);
document.addEventListener("mousedown", resetLogoutTimer);
document.addEventListener("keypress", resetLogoutTimer);
document.addEventListener("touchmove", resetLogoutTimer);
document.addEventListener("touchstart", resetLogoutTimer);
document.addEventListener("scroll", resetLogoutTimer);