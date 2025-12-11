   var systeme="";

console.log("je suis dans le fichier autes fonction js");

  function updateTextBox1(){
    var selectedElement = document.getElementById("faculte").value;
    var systeme1 = document.getElementById("option11");
    var systeme2 = document.getElementById("option21");
    ///alert selectedElement; 
    if (selectedElement != '') {

    // Effectuer une requête AJAX pour charger les données de la base de données
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);
        populateComboBox(data);

      }
    };


    if (systeme1.checked) {systeme="Ancien systeme";}
    else {systeme="LMD";}
    console.log("c'est le systeme ::"+systeme)
    xhttp.open("GET", "donnee1.php?element=" + selectedElement+"&systeme="+systeme, true);
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




function fac_activation(){
   var faculte_activation = document.getElementById("faculte_activation").value;
var systeme1 = document.getElementById("option11_activation");
var systeme2 = document.getElementById("option21_activation");
 if (systeme1.checked) {systeme="Ancien systeme";}
    else {systeme="LMD";}
    
    ///alert selectedElement; 
    if (faculte_activation != '') {

    // Effectuer une requête AJAX pour charger les données de la base de données
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);
        affiche_comboBoxe(data);

      }
    };

    console.log("c'est le systeme ::"+systeme)
    xhttp.open("GET", "traitement_promotion_activation.php?fac_activation=" + faculte_activation+"&systeme="+systeme, true);
    xhttp.send();
  } else {
    // Réinitialiser le combobox y
    document.getElementById("updateTextBox2").innerHTML = '<option value="">Sélectionner une faculté</option>';
  }
}

function affiche_comboBoxe(data) {
  var combobox_activation = document.getElementById("promotion_activation");
  combobox_activation.innerHTML = '<option value="">Sélectionner</option>';
  for (var i = 0; i < data.length; i++) {
    var option = document.createElement("option");
    option.value = data[i].Code_Promotion;
    option.text = data[i].Promtion;
    combobox_activation.appendChild(option);
  }
}



/*************************************************************************************
 * 
 * insertion étudiant
 * 
 * *****************************/



function fac_insertion(){
var faculte_insertion = document.getElementById("faculte_insertion").value;
var systeme1 = document.getElementById("option11_insertion");
var systeme2 = document.getElementById("option21_insertion");
 if (systeme1.checked) {systeme="Ancien systeme";}
    else {systeme="LMD";}
    
    ///alert selectedElement; 
    if (faculte_insertion != '') {

    // Effectuer une requête AJAX pour charger les données de la base de données
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);
        affiche_inserer(data);

      }
    };

    //console.log("c'est le systeme ::"+systeme)
    xhttp.open("GET", "traitement_promotion_inserer.php?faculte_insertion=" + faculte_insertion+"&systeme="+systeme, true);
    xhttp.send();
  } else {
    // Réinitialiser le combobox y
    document.getElementById("updateTextBox2").innerHTML = '<option value="">Sélectionner une faculté</option>';
  }
}

function affiche_inserer(data) {
  var combobox_activation = document.getElementById("promotion_insertion");
  combobox_activation.innerHTML = '<option value="">Sélectionner</option>';
  for (var i = 0; i < data.length; i++) {
    var option = document.createElement("option");
    option.value = data[i].Code_Promotion;
    option.text = data[i].Promtion;
    combobox_activation.appendChild(option);
  }
}



    
function option1(){
  console.log("je suis dans le fichier autes fonction js");
    var block1 = document.getElementById('block1'); block1.style.display='block';
    var block2 = document.getElementById('block2'); block2.style.display='block';

    var block3 = document.getElementById('block3'); block3.style.display='none';
    var block4 = document.getElementById('block4'); block4.style.display='none';

    var block5 = document.getElementById('block5'); block5.style.display='none';
    var block6 = document.getElementById('block6'); block6.style.display='none';
    
    var insertion1 = document.getElementById('insertion1'); insertion1.style.display='none';
    var insertion2 = document.getElementById('insertion2'); insertion2.style.display='none';
}
   


function option2(){
    var block1 = document.getElementById('block1'); block1.style.display='none';
    var block2 = document.getElementById('block2'); block2.style.display='none';

    var block3 = document.getElementById('block3'); block3.style.display='block';
    var block4 = document.getElementById('block4'); block4.style.display='block';

    var block5 = document.getElementById('block5'); block5.style.display='none';
    var block6 = document.getElementById('block6'); block6.style.display='none';

    var insertion1 = document.getElementById('insertion1'); insertion1.style.display='none';
    var insertion2 = document.getElementById('insertion2'); insertion2.style.display='none';

}

function option3(){
    var block1 = document.getElementById('block1'); block1.style.display='none';
    var block2 = document.getElementById('block2'); block2.style.display='none';

    var block3 = document.getElementById('block3'); block3.style.display='none';
    var block4 = document.getElementById('block4'); block4.style.display='none';
    
    var block5 = document.getElementById('block5'); block5.style.display='block';
    var block6 = document.getElementById('block6'); block6.style.display='block';

    var insertion1 = document.getElementById('insertion1'); insertion1.style.display='none';
    var insertion2 = document.getElementById('insertion2'); insertion2.style.display='none';

}
function Deconnexion_encodage(){
  console.log('jesuis dans quiter');
  window.location.href="../Fonctions_PHP/Deconnexion.php";
   //const url='../Fonctions_PHP/Deconnexion.php?valeur_envoyee=BIEN';  
    //fetch(url); 
}
function option5(){
    var block1 = document.getElementById('block1'); block1.style.display='none';
    var block2 = document.getElementById('block2'); block2.style.display='none';

    var block3 = document.getElementById('block3'); block3.style.display='none';
    var block4 = document.getElementById('block4'); block4.style.display='none';
    
    var block5 = document.getElementById('block5'); block5.style.display='none';
    var block6 = document.getElementById('block6'); block6.style.display='none';

    var insertion1 = document.getElementById('insertion1'); insertion1.style.display='block';
    var insertion2 = document.getElementById('insertion2'); insertion2.style.display='block';


}