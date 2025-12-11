console.log("nous sommes dans select promo FINANCE"); 
 function Selection_promo(){
    
    var Idfiliere = document.getElementById("filiere").value;
    var systeme2 = document.getElementById("LMD");
    var systeme1 = document.getElementById("PADEM");
    ///alert selectedElement; 
    if (Idfiliere != '') {

    // Effectuer une requête AJAX pour charger les données de la base de données
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);
        populateComboBox(data);

      }
    };
var systeme="";

    if (systeme1.checked) {systeme="Ancien systeme";}
    else {systeme="LMD";}
    console.log("c'est le systeme ::"+systeme)
    xhttp.open("GET", "D_Finance/Liste_Paiement/API/APISelect_Promo.php?IdFiliere=" + Idfiliere+"&systeme="+systeme, true);
    xhttp.send();
  } else {
    // Réinitialiser le combobox y
    document.getElementById("updateTextBox2").innerHTML = '<option value="">Sélectionner une faculté</option>';
  }
}

function populateComboBox(data) {
  var updateTextBox2 = document.getElementById("promotion");
  updateTextBox2.innerHTML = '<option value="">Sélectionner</option>';
  for (var i = 0; i < data.length; i++) {
    var option = document.createElement("option");
    option.value = data[i].Code_Promotion;
    option.text = data[i].Promtion;
    updateTextBox2.appendChild(option);
  }
}