console.log("nous sommes dans le jsinscription");


function list_etudiant_inscrit(){
/*
*/

var faculte_inscrit = document.getElementById("faculte_inscrit").value;
var systeme = "LMD";

    
    ///alert selectedElement; 
    if (faculte_inscrit != '') {

    // Effectuer une requête AJAX pour charger les données de la base de données
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);
        affiche_inserer(data);

      }
    };

 
    xhttp.open("GET", "API/affiche_promotion.php?element=" + faculte_inscrit+"&systeme="+systeme, true);
    xhttp.send();
  } else {
    // Réinitialiser le combobox y
    
  }
}


function affiche_inserer(data) {
  var combo_promotion = document.getElementById("promotion_inscrit");
  combo_promotion.innerHTML = '<option value="">Sélectionner</option>';
  for (var i = 0; i < data.length; i++) {
    var option = document.createElement("option");
    option.value = data[i].Code_Promotion;
    option.text = data[i].Promtion;
    combo_promotion.appendChild(option);
  }
}
var annee_acad=document.getElementById("annee_acad1");
var promotion_inscrit=document.getElementById("promotion_inscrit");

var combo_promotion = document.getElementById("promotion_inscrit");
promotion_inscrit.addEventListener('change',(event) => { 
  var idAnnee_acad1=annee_acad.value;
  var code_promo=promotion_inscrit.value;
  Affichage_etudiant(code_promo,idAnnee_acad1);
});



function Affichage_etudiant(code_promo,idAnnee_acad1) 
{
  var zone_active = document.getElementById("zone_rech_inscription");
  zone_active.disabled=false;
  zone_active.focus();

var tableau1 = document.getElementById("table_inscrit");
var tbody = document.createElement("tbody");

while(tableau1.rows.length>1){
    tableau1.deleteRow(1);
  }

  // Contacte de l'API PHP
  const url='API/affiche_inscrit.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_acad1;  
  var i=1;

    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
              var tr = document.createElement("tr");


              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprenom = document.createElement("td");
              var tdsexe = document.createElement("td");
              
              
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprenom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;


              tr.appendChild(tdnum);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprenom);
              tr.appendChild(tdsexe);

              tbody.appendChild(tr);
              i++
              document.getElementById('effectifs_etudiant').innerHTML=i-1;
              tr.addEventListener("click", function() {
                Recuperation_Transactions_inscrit(infos.Matricule,infos.Nom,infos.Postnom, infos.Prenom, infos.Sexe, infos.LieuNaissance, infos.DateNaissance,tr);
              });

               });

              

              
              
              
              

      
              
    })

    tableau1.appendChild(tbody);

  }

var matricule_select="";
function Recuperation_Transactions_inscrit(Matricule,Nom,Postnom, Prenom, Sexe, LieuNaissance, DateNaissance,tr1)
{
  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tableau = document.getElementById("table_inscrit");
  var rows = tableau.getElementsByTagName('tr');  
  for(var j = 0; j < rows.length; j++) 
  {
    if(j!=0) rows[j].style.backgroundColor = '';
  }
  tr1.style.backgroundColor = 'blue';
val_tr=tr1;
    document.getElementById('zone_matricule').value=Matricule;
    document.getElementById('zone_nom').value=Nom;
    document.getElementById('zone_postnom').value=Postnom;
    document.getElementById('zone_prenom').value=Prenom;
    matricule_select=Matricule;

      

    document.getElementById('zone_sexe').innerHTML="";
    var option1 = document.createElement("option");
    if (Sexe=="M"){
    option1.value="M";
    option1.text="Masculin";

    }
    else if (Sexe=="F") {
    option1.value="F";
    option1.text="Féminin";
    console.log("nous sommes dans F");
    }
    else {
    option1.value="";
    option1.text="Faites votre choix";
    }

    document.getElementById('zone_sexe').add(option1);
    option1.selected=true;
    option1.hidden=true;

  var option2 = document.createElement("option");
    option2.value="M";
    option2.text="Masculin";
    document.getElementById('zone_sexe').add(option2);

    var option3 = document.createElement("option");
    option3.value="F";
    option3.text="Féminin";
    document.getElementById('zone_sexe').add(option3);



    document.getElementById('zone_lieu_naiss').value=LieuNaissance;
    document.getElementById('zone_date_naiss').value=DateNaissance;


  console.log("nous sommes dans selection ligne");

  affiche_autre_info(Matricule);
}


//la zone de recherche


 document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById("zone_rech_inscription");
    searchInput.addEventListener('input', function() {
        var query = searchInput.value.trim();
        console.log("nous entrain de saisir");
        if (query.length > 0) {
            search(query);
        } else {
    var tableau = document.getElementById("table_inscrit");
    var tbody = document.createElement("tbody");
  while(tableau.rows.length>1){ tableau.deleteRow(1);
      }
        }
    });

 var promtion = document.getElementById("promotion_inscrit");
    function search(query) {
    var id_filiere=faculte_inscrit.value;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    displayResults(response);
                } else {
                    console.error('Erreur: ' + xhr.status);
                }
            }
        };
        xhr.open('GET', 'API/affichage_etudiant_inscrit.php?id_filiere='+id_filiere+'&text_rech='+query+'&promtion='+promtion.value+'&annee_acad='+annee_acad.value);
        xhr.send();
    }

    function displayResults(results) {
        var html = '';
        var i=1;
    var tableau = document.getElementById("table_inscrit");
    var tbody = document.createElement("tbody");
  while(tableau.rows.length>1){ tableau.deleteRow(1);}
console.log("CRÉATION tableau");


        if (results.length > 0) {
            results.forEach(function(infos) {
               var tr = document.createElement("tr");
               
              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              var tdsexe = document.createElement("td");

              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
     

              tr.appendChild(tdnum);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              tr.style.cursor = 'pointer';


              tbody.appendChild(tr);
              i++;

      

               tr.addEventListener("click", function() {
                Recuperation_Transactions_inscrit(infos.Matricule,infos.Nom,infos.Postnom, infos.Prenom, infos.Sexe, infos.LieuNaissance, infos.DateNaissance,tr);
              });
            });
        } else {
          var tr = document.createElement("tr");
          var info = document.createElement("td");
          info.textContent="Aucune information trouvé pour votre recherche";
          tr.appendChild(info);
          tbody.appendChild(tr);

        }
         tableau.appendChild(tbody);

    }
});
//fin zone de recherche inscription





function affiche_autre_info(Matricule)
{

var url='API/affiche_autre_info.php?matricule='+Matricule;
        
    
       var y=0;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          document.getElementById('zone_religion').value=infos.Religion;

          document.getElementById('zone_nationalite').innerHTML="";
          var option1_1 = document.createElement("option");
          option1_1.value=infos.Nationalite;
          option1_1.text=infos.Nationalite;

          var option1_1_2 = document.createElement("option");
          option1_1_2.value="Congolaise";
          option1_1_2.text="Congolaise";
          document.getElementById('zone_nationalite').add(option1_1_2);

          var option1_1_3 = document.createElement("option");
          option1_1_3.value="Autre";
          option1_1_3.text="Autre";
          document.getElementById('zone_nationalite').add(option1_1_3);

          document.getElementById('zone_nationalite').add(option1_1);
          option1_1.selected=true;
          option1_1.hidden=true;



          document.getElementById('zone_etat_civil').innerHTML="";
          var option1_2 = document.createElement("option");
          option1_2.value=infos.EtatCiv;
          option1_2.text=infos.EtatCiv;

          var option1_2_2 = document.createElement("option");
          option1_2_2.value="Célibataire";
          option1_2_2.text="Célibataire";
          document.getElementById('zone_etat_civil').add(option1_2_2);

          var option1_2_3 = document.createElement("option");
          option1_2_3.value="Marié(e)";
          option1_2_3.text="Marié(e)";
          document.getElementById('zone_etat_civil').add(option1_2_3);

          document.getElementById('zone_etat_civil').add(option1_2);
          option1_2.selected=true;
          option1_2.hidden=true;
          

          document.getElementById('zone_nom_pere').value=infos.NomPere;
          document.getElementById('zone_profession_pere').value=infos.ProfPere;
          document.getElementById('zone_nom_mere').value=infos.NomMere;
          document.getElementById('zone_profession_mere').value=infos.ProfMere;
          document.getElementById('zone_adresse_actuelle').value=infos.AdresseActuelle;
          document.getElementById('zone_paroisse').value=infos.Paroisse;
          document.getElementById('zone_diocese').value=infos.Diocese;
          document.getElementById('zone_num_vodacom').value=infos.TelVoda;
          document.getElementById('zone_num_orange').value=infos.TelOrange;
          document.getElementById('zone_num_airtel').value=infos.TelAirtel;
          document.getElementById('zone_anne_scolaire_primaire').value=infos.Annscol;
          document.getElementById('zone_etablissement_primaire').value=infos.NomEtablis;
          document.getElementById('zone_pourc_certificat_primaire').value=infos.PourceCertificat;
          document.getElementById('zone_pourcentage_diplome').value=infos.PourceDiplome;
          document.getElementById('zone_num_dip').value=infos.NumDiplom;
          document.getElementById('zone_section').value=infos.SetionEtude;
          document.getElementById('zone_option').value=infos.OptionEtude;
          document.getElementById('zone_lieu_delivrance').value=infos.Lieudelivrance;
          document.getElementById('zone_date_delivrance').value=infos.Datedelivrance;
          document.getElementById('zone_ecole_provenance').value=infos.Ecole;
          document.getElementById('zone_province_educationnelle').value=infos.Province;
          document.getElementById('zone_province_origine').value=infos.ProvinceOrigine;
          document.getElementById('zone_territoire_origine').value=infos.Territoire;
          document.getElementById('zone_contact_responsable').value=infos.TelResponsable;
        
              
        });
           
     
  });
}


// la modification de zone de text pour inserer ou modifier les information de l'etudiant iscrit
//----------------------------------------------------------------------------------------------

var zone_nom = document.getElementById('zone_nom');

zone_nom.addEventListener("blur", function() {
  var texte = zone_nom.value;
  modif(matricule_select,texte,"zone_nom");
  });

var zone_postnom = document.getElementById('zone_postnom');

zone_postnom.addEventListener("blur", function() {
  var texte = zone_postnom.value;
  modif(matricule_select,texte,"zone_postnom");
  });

var zone_prenom = document.getElementById('zone_prenom');

zone_prenom.addEventListener("blur", function() {
  var texte = zone_prenom.value;
  modif(matricule_select,texte,"zone_prenom");
  });

var zone_sexe = document.getElementById('zone_sexe');

zone_sexe.addEventListener("blur", function() {
  var texte = zone_sexe.value;
  modif(matricule_select,texte,"zone_sexe");
  });

var zone_lieu_naiss = document.getElementById('zone_lieu_naiss');
zone_lieu_naiss.addEventListener("blur", function() {
  var texte = zone_lieu_naiss.value;
  modif(matricule_select,texte,"zone_lieu_naiss");
  });

var zone_date_naiss = document.getElementById('zone_date_naiss');

zone_date_naiss.addEventListener("blur", function() {
  var texte = zone_date_naiss.value;
  modif(matricule_select,texte,"zone_date_naiss");
  });

//pour la table autre info de l'etudiant

var zone_etat_civil = document.getElementById('zone_etat_civil');
zone_etat_civil.addEventListener("blur", function() {
  var texte = zone_etat_civil.value;
  modif(matricule_select,texte,"zone_etat_civil"); });

var zone_nationalite = document.getElementById('zone_nationalite');
zone_nationalite.addEventListener("blur", function() {
  var texte = zone_nationalite.value;
  modif(matricule_select,texte,"zone_nationalite"); });

var zone_adresse_actuelle = document.getElementById('zone_adresse_actuelle');
zone_adresse_actuelle.addEventListener("blur", function() {
  var texte = zone_adresse_actuelle.value;
  modif(matricule_select,texte,"zone_adresse_actuelle"); });


var zone_religion = document.getElementById('zone_religion');
zone_religion.addEventListener("blur", function() {
  var texte = zone_religion.value;
  modif(matricule_select,texte,"zone_religion"); });


var zone_paroisse = document.getElementById('zone_paroisse');
zone_paroisse.addEventListener("blur", function() {
  var texte = zone_paroisse.value;
  modif(matricule_select,texte,"zone_paroisse"); });

var zone_diocese = document.getElementById('zone_diocese');
zone_diocese.addEventListener("blur", function() {
  var texte = zone_diocese.value;
  modif(matricule_select,texte,"zone_diocese"); });

var zone_nom_pere = document.getElementById('zone_nom_pere');
zone_nom_pere.addEventListener("blur", function() {
  var texte = zone_nom_pere.value;
  modif(matricule_select,texte,"zone_nom_pere"); });

var zone_profession_pere = document.getElementById('zone_profession_pere');
zone_profession_pere.addEventListener("blur", function() {
  var texte = zone_profession_pere.value;
  modif(matricule_select,texte,"zone_profession_pere"); });

var zone_nom_mere = document.getElementById('zone_nom_mere');
zone_nom_mere.addEventListener("blur", function() {
  var texte = zone_nom_mere.value;
  modif(matricule_select,texte,"zone_nom_mere"); });

var zone_profession_mere = document.getElementById('zone_profession_mere');
zone_profession_mere.addEventListener("blur", function() {
  var texte = zone_profession_mere.value;
  modif(matricule_select,texte,"zone_profession_mere"); });

var zone_province_origine = document.getElementById('zone_province_origine');
zone_province_origine.addEventListener("blur", function() {
  var texte = zone_province_origine.value;
  modif(matricule_select,texte,"zone_province_origine"); });

var zone_territoire_origine = document.getElementById('zone_territoire_origine');
zone_territoire_origine.addEventListener("blur", function() {
  var texte = zone_territoire_origine.value;
  modif(matricule_select,texte,"zone_territoire_origine"); });

var zone_contact_responsable = document.getElementById('zone_contact_responsable');
zone_contact_responsable.addEventListener("blur", function() {
  var texte = zone_contact_responsable.value;
  modif(matricule_select,texte,"zone_contact_responsable"); });

var zone_num_airtel = document.getElementById('zone_num_airtel');
zone_num_airtel.addEventListener("blur", function() {
  var texte = zone_num_airtel.value;
  modif(matricule_select,texte,"zone_num_airtel"); });

var zone_num_vodacom = document.getElementById('zone_num_vodacom');
zone_num_vodacom.addEventListener("blur", function() {
  var texte = zone_num_vodacom.value;
  modif(matricule_select,texte,"zone_num_vodacom"); });

var zone_num_orange = document.getElementById('zone_num_orange');
zone_num_orange.addEventListener("blur", function() {
  var texte = zone_num_orange.value;
  modif(matricule_select,texte,"zone_num_orange"); });


var zone_anne_scolaire_primaire = document.getElementById('zone_anne_scolaire_primaire');
zone_anne_scolaire_primaire.addEventListener("blur", function() {
  var texte = zone_anne_scolaire_primaire.value;
  modif(matricule_select,texte,"zone_anne_scolaire_primaire"); });


var zone_etablissement_primaire = document.getElementById('zone_etablissement_primaire');
zone_etablissement_primaire.addEventListener("blur", function() {
  var texte = zone_etablissement_primaire.value;
  modif(matricule_select,texte,"zone_etablissement_primaire"); });


var zone_pourc_certificat_primaire = document.getElementById('zone_pourc_certificat_primaire');
zone_pourc_certificat_primaire.addEventListener("blur", function() {
  var texte = zone_pourc_certificat_primaire.value;
  modif(matricule_select,texte,"zone_pourc_certificat_primaire"); });


var zone_anne_scolaire = document.getElementById('zone_anne_scolaire');
zone_anne_scolaire.addEventListener("blur", function() {
  var texte = zone_anne_scolaire.value;
  modif(matricule_select,texte,"zone_anne_scolaire"); });


var zone_province_educationnelle = document.getElementById('zone_province_educationnelle');
zone_province_educationnelle.addEventListener("blur", function() {
  var texte = zone_province_educationnelle.value;
  modif(matricule_select,texte,"zone_province_educationnelle"); });


var zone_ecole_provenance = document.getElementById('zone_ecole_provenance');
zone_ecole_provenance.addEventListener("blur", function() {
  var texte = zone_ecole_provenance.value;
  modif(matricule_select,texte,"zone_ecole_provenance"); });


var zone_section = document.getElementById('zone_section');
zone_section.addEventListener("blur", function() {
  var texte = zone_section.value;
  modif(matricule_select,texte,"zone_section"); });


var zone_option = document.getElementById('zone_option');
zone_option.addEventListener("blur", function() {
  var texte = zone_option.value;
  modif(matricule_select,texte,"zone_option"); });


var zone_pourcentage_diplome = document.getElementById('zone_pourcentage_diplome');
zone_pourcentage_diplome.addEventListener("blur", function() {
  var texte = zone_pourcentage_diplome.value;
  modif(matricule_select,texte,"zone_pourcentage_diplome"); });

var zone_num_dip = document.getElementById('zone_num_dip');
zone_num_dip.addEventListener("blur", function() {
  var texte = zone_num_dip.value;
  modif(matricule_select,texte,"zone_num_dip"); });

var zone_lieu_delivrance = document.getElementById('zone_lieu_delivrance');
zone_lieu_delivrance.addEventListener("blur", function() {
  var texte = zone_lieu_delivrance.value;
  modif(matricule_select,texte,"zone_lieu_delivrance"); });

var zone_date_delivrance = document.getElementById('zone_date_delivrance');
zone_date_delivrance.addEventListener("blur", function() {
  var texte = zone_date_delivrance.value;
  modif(matricule_select,texte,"zone_date_delivrance"); });



//fonction de la modification

function modif(matricule,text,zone){
const url='API/modifier_inscrit.php?text='+text+'&matricule='+matricule+'&zone='+zone;  
    fetch(url);
   
}
//fin insertion et modification des informations concernant l'étudiant inscrit
