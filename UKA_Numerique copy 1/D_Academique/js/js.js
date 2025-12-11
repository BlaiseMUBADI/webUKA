var modal = document.getElementById("myModal");
var btn = document.getElementById("openModalBtn");

var span = document.getElementsByClassName("close")[0];



btn.onclick = function() {
  modal.style.display = "block";
  console.log("nous sommes dans le bouton affiche modal");
}




span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

// Fonction pour pré-remplir les champs du formulaire avec les données du client
function remplirChamps(nom, prenom, postnom) {
  document.getElementById("nom").value = nom;
  document.getElementById("prenom").value = prenom;
  document.getElementById("postnom").value = postnom;
}