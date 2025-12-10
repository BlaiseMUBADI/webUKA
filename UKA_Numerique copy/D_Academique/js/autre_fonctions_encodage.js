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

function terminer_inscription(){

  var nom=document.getElementById('nom').innerHTML=document.getElementById('zone_nom').value +" - "
  +document.getElementById('zone_postnom').value +" - "
  +document.getElementById('zone_prenom').value;

  document.getElementById('sexe').innerHTML=document.getElementById('zone_sexe').value;

//convertion date au format français
  const today = new Date(document.getElementById('zone_date_naiss').value);
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  const formattedDate = today.toLocaleDateString('fr-FR', options);
  //document.getElementById('date_jour').innerText = ` ${formattedDate}.`;


  document.getElementById('lieu_date_naiss').innerHTML=document.getElementById('zone_lieu_naiss').value +" - "
  +` ${formattedDate}.`;


  document.getElementById('nom_mere').innerHTML=document.getElementById('zone_nom_mere').value;
  document.getElementById('prof_mere').innerHTML=document.getElementById('zone_profession_mere').value;


  document.getElementById('nom_pere').innerHTML=document.getElementById('zone_nom_pere').value;
  document.getElementById('prof_pere').innerHTML=document.getElementById('zone_profession_pere').value;

  document.getElementById('religion').innerHTML=document.getElementById('zone_religion').value;
  document.getElementById('nationalite').innerHTML=document.getElementById('zone_nationalite').value;

  document.getElementById('etaciv').innerHTML=document.getElementById('zone_etat_civil').value;
  document.getElementById('paroisse').innerHTML=document.getElementById('zone_paroisse').value;

  document.getElementById('adresse_actuelle').innerHTML=document.getElementById('zone_adresse_actuelle').value;
  document.getElementById('diocese').innerHTML=document.getElementById('zone_diocese').value;

  document.getElementById('personne_contact').innerHTML=document.getElementById('zone_contact_responsable').value;

  document.getElementById('anne_primaire').innerHTML=document.getElementById('zone_anne_scolaire_primaire').value;
  document.getElementById('nom_ecole_primaire').innerHTML=document.getElementById('zone_etablissement_primaire').value;
  document.getElementById('Pourcentage').innerHTML=document.getElementById('zone_pourc_certificat_primaire').value+' %';

  document.getElementById('num_dip').innerHTML=document.getElementById('zone_num_dip').value;
  document.getElementById('pourc_dip').innerHTML=document.getElementById('zone_pourcentage_diplome').value+' %';


  console.log('deja dans le remplissage');

 var formulaire_inscrption = document.getElementById('formulaire_inscription'); 
 formulaire_inscrption.style.display='block';
    var inscription_page = document.getElementById('inscription_page'); inscription_page.style.display='none';
    var pied = document.getElementById('pied'); pied.style.display='none';
    var menu = document.getElementById('menu'); menu.style.display='none';
    var logo = document.getElementById('logo1'); logo.style.display='none';

    var faculte=document.getElementById('faculte_inscrit');
    var select_faculte=faculte.options[faculte.selectedIndex];
    document.getElementById('premier_choix').innerHTML=select_faculte.text;
    document.getElementById('premier_choix').innerHTML=select_faculte.text;
    document.getElementById('num_enregistrememt').innerHTML=document.getElementById('zone_matricule').value;

      window.print();

    var attestation_document = document.getElementById('inscription_page'); attestation_document.style.display='block';
    var pied = document.getElementById('pied'); pied.style.display='block';
    var menu = document.getElementById('menu'); menu.style.display='block';
    var logo = document.getElementById('logo1'); logo.style.display='block';
     formulaire_inscrption.style.display='none';
}

function btn_liste_inscrit(){
  Affichage_liste_etudiant_inscrit("liste_inscrit");
}

function btn_liste_inscrit_test(){
  Affichage_liste_etudiant_inscrit("liste_inscrit_test");
}

  function Affichage_liste_etudiant_inscrit(option) 
{
    var conteur=0;

    document.getElementById('liste_inscrit_titre').style.display='block';
    document.getElementById('liste_inscrit_tableau').style.display='block';

    document.getElementById('formulaire_inscription').style.display='none';
    document.getElementById('inscription_page').style.display='none';
    var pied = document.getElementById('pied'); pied.style.display='block';
    var menu = document.getElementById('menu'); menu.style.display='none';
    var logo = document.getElementById('logo1'); logo.style.display='none';

    if (option=="liste_inscrit") {
      document.getElementById('nom_liste').innerHTML="Liste définitive des candidats inscrits en";
      }else if (option=="liste_inscrit_test"){
      document.getElementById('nom_liste').innerHTML="Liste des candidats admissibles au test d'entrée en";
      }
    var annee_acad=document.getElementById('annee_acad1');
    var promotion=document.getElementById('promotion_inscrit');

    var idAnnee_Acad=annee_acad.value;
    var code_promo1=promotion.value;
   
    var select_promotion=promotion.options[promotion.selectedIndex];
    document.getElementById('affiche_promoton').innerHTML=select_promotion.text;

    var select_ann=annee_acad.options[annee_acad.selectedIndex];
    document.getElementById('annee_academique').innerHTML=select_ann.text;

  var tableau = document.getElementById("table_liste_inscrit");
  var tbody = document.createElement("tbody");

  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
    
  }
  // Contacte de l'API PHP
  const url='API/Affichage_liste_etudiant_inscrit.php?code_promo='+code_promo1+'&Id_annee_acad='+idAnnee_Acad+'&action='+option;  
  var i=1;
  

    fetch(url) 
    .then(response => response.json())
    .then(data1 => 
    {
      data1.forEach(infos =>
        {
          // Création de TR
          //if (infos.Decision_jury=="Admis" || infos.Decision_jury=="Admis sous condition") {
              var tr = document.createElement("tr");

              //console.log("l abreviation de la promotion est ::::"+infos.Abreviation);

              //abreviation_promotion= infos.Abreviation;
              var tdnum = document.createElement("td");
              tdnum.textContent = i;
              conteur++;

              
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              //var tddecision = document.createElement("td");
              //var tdmention = document.createElement("td");
              var tdsexe = document.createElement("td");
            
              

              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;



              /*var zone1=document.createElement('input');
              zone1.setAttribute('type','text');
              zone1.setAttribute('style','width:40px;');
              zone1.setAttribute('value',infos.Decision_jury);
              zone1.disabled=true;

              tddecision.appendChild(zone1);*/
              

              tr.appendChild(tdnum);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              //tr.appendChild(tddecision)

   tbody.appendChild(tr);
    i++;
    
     tableau.appendChild(tbody);
    
              });
              
                    
              

            document.getElementById('nbr_etudiant').innerHTML=conteur;  
              window.print(); 
              
           

              document.getElementById('liste_inscrit_titre').style.display='none';
    document.getElementById('liste_inscrit_tableau').style.display='none';

    document.getElementById('formulaire_inscription').style.display='none';
    document.getElementById('inscription_page').style.display='block';
    var pied = document.getElementById('pied'); pied.style.display='block';
    var menu = document.getElementById('menu'); menu.style.display='block';
    var logo = document.getElementById('logo1'); logo.style.display='block';
             
            //}  
              
        });
      //liste.style.display='inline-block';
      //liste_label.style.display='inline-block';
        
     
         

      

}
