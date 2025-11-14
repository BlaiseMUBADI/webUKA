console.log(" Le script recup_promotion_et_etudiant s'est lancé")
//declaration constante


       

//const annee=document.getElementById("annee");
//const promotion=document.getElementById("promotion");
const promotion=document.getElementById("promotion");
const promo_affectation=document.getElementById("promotion1");
const promo_activation=document.getElementById("promotion_activation");

//const liste=document.getElementById("liste");
//const liste_label=document.getElementById("liste_label");

const annee=document.getElementById("annee");
const annee1=document.getElementById("annee1");
const annee_activation=document.getElementById("annee_activation");

var systeme11 = document.getElementById("option12");
var systeme22 = document.getElementById("option22");
var systeme2="";
var systeme="";
    if (systeme11.checked) systeme2="Ancien systeme";
    else systeme2="LMD";

var systeme1 = document.getElementById("option11");
    var systeme2 = document.getElementById("option21");

      if (systeme1.checked) {systeme="Ancien systeme";}
    else {systeme="LMD";}
    console.log("c'est le systeme ::"+systeme)
const Matricule_const="";

/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ La partie d'ajout des évenements à chaque composant +++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

var code_promo1=promo_affectation.value;
  
var au;
 // Lorque le combo box promotion  change
promotion.addEventListener('change',(event) => { 
  var idAnnee_Acad=annee.value;
  var code_promo=promotion.value;
  Affichage_etudiant(code_promo,idAnnee_Acad);
});

promotion1.addEventListener('change',(event) => {
  var idAnnee_Acad=annee1.value;
  var code_promo1=promo_affectation.value;
  console.log("la valeur de promotion est :::: "+code_promo1)
  console.log("la valeur de annee est :::: "+idAnnee_Acad)
  Affichage_etudiant1(code_promo1,idAnnee_Acad);

});





annee.addEventListener('change',(event) => {
  var code_promo=promotion.value;
  var idAnnee_Acad=annee.value;
  reinitialiser_tableau(idAnnee_Acad);
});



function reinitialiser_tableau(idAnnee_Acad) 
{
  var tableau=document.getElementById("table");
  while(tableau.rows.length>1){
    tableau.deleteRow(1);
  }
}

function Afficher(valeur_envoyee,Matricule,Zone) 
{
    console.log(" je suis dans Afficher");
    var idAnnee_Acad=annee.value;
    var code_promo=promotion.value;

//console.log("la valeur de envoyer esr "+valeur_envoyee);
//console.log("la valeur de envoyer esr "+Zone);
const url='Encodage_etudiant.php?valeur_envoyee='+valeur_envoyee+'&Matricule='+Matricule+'&code_promo='+code_promo+'&id_annee_acad='+idAnnee_Acad+'&Zone='+Zone;  


    fetch(url) 

   /* var xhr = new XMLHttpRequest();
    xhr.open("POST", "Encodage_etudiant.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            console.log(xhr.responseText)
            // Réponse du serveur
            if(xhr.responseText!="Ok") 
            {
                //alert( " Reussi ");
                
            }
                
            else 
            {
                alert( " Echec d'eregistrement ");
            }
        }
    };
    xhr.send("valeur_envoyee="+valeur_envoyee
              +"&Matricule="+Matricule
              +"&code_promo="+code_promo
              +"&id_annee_acad="+idAnnee_Acad
              +"&Zone="+Zone);*/

}

function Envoi_etudiant() 
{
console.log(" je suis dans envoi dans la base");

var promotion_insert=document.getElementById("promotion_insertion").value;
var annee_insert=document.getElementById("annee_insertion").value;
var faculte_insert=document.getElementById("faculte_insertion").value;

var matricule1=document.getElementById("Matricule_insert").value;
var nom_etudiant=document.getElementById("Nom_etudiant").value;
var postnom_etudiant=document.getElementById("Postnom_etudiant").value;
var prenom_etudiant=document.getElementById("Prenom_etudiant").value;
var Sexe_etudiant=document.getElementById("Sexe_etudiant").value;

console.log('matricule ='+matricule1);


/*=$_POST['Matricule_etudiant'];
    $Nom_etudiant=$_POST['Nom_etudiant'];
    $Postnom_etudiant=$_POST['Postnom_etudiant'];
    $Prenom_etudiant=$_POST['Prenom_etudiant'];
    $Sexe_etudiant=$_POST['Sexe_etudiant'];*/
    //var idAnnee_Acad=annee.value;
    //var code_promo=promotion.value;

//console.log("la valeur de envoyer esr "+valeur_envoyee);
//console.log("la valeur de envoyer esr "+Zone);
const url='envoi.php?promotion_insert='+promotion_insert
+'&annee_insert='+annee_insert+'&faculte_insert='+faculte_insert
+'&matricule='+matricule1+'&nom_etudiant='+nom_etudiant+'&postnom_etudiant='
+postnom_etudiant+'&prenom_etudiant='+prenom_etudiant+'&Sexe_etudiant='+Sexe_etudiant;  


    fetch(url) ;

    viderzone();

}
  
function viderzone()
{

var matricule1=document.getElementById("Matricule_insert");
var nom_etudiant=document.getElementById("Nom_etudiant");
var postnom_etudiant=document.getElementById("Postnom_etudiant");
var prenom_etudiant=document.getElementById("Prenom_etudiant");
var Sexe_1=document.getElementById("Sexe_etudiant");


matricule1.value='';
nom_etudiant.value='';
nom_etudiant.focus();
postnom_etudiant.value='';
prenom_etudiant.value='';
Sexe_1.value='Faites Votre Choix ...';
}



// LA FONCTION POUR RECUPERE LES MODALITES DE PAIEMENT POUR CHAQUE PROMOTION CHOISIE
function Affichage_etudiant(code_promo,idAnnee_Acad) 
{

  var tableau = document.getElementById("table");
  var tbody = document.createElement("tbody");


  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
    
  }
  console.log(tableau.rows.length);
  // Contacte de l'API PHP
  const url='donnee2.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_Acad;  
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

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              var tdchamp1 = document.createElement("td");
              var tdchamp2 = document.createElement("td");
              var tdchamp3 = document.createElement("td");
              var tdchamp4 = document.createElement("td");
              var tdchamp5 = document.createElement("td");
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;



              var zone1=document.createElement('input');
              zone1.setAttribute('type','text');
              zone1.setAttribute('style','width:40px;');
              zone1.setAttribute('value',infos.Session1);

              tdchamp1.appendChild(zone1);
              zone1.addEventListener("blur", function() {
                Afficher(zone1.value,infos.Matricule,"zone_1");
              });


              /*var zone2=document.createElement('input');
              zone2.setAttribute('type','text');
              zone2.setAttribute('id','zone2'+i);
              zone2.setAttribute('style','width:60px;');
              zone2.setAttribute('value',infos.Mention1);*/



/************************************
 * 
 * 
 */


if (systeme=="LMD"){
              var zone2=document.createElement("select");
              zone2.setAttribute('style','width:60px;');
              //zone5.setAttribute('value',infos.Decision_jury);

              var option02 = document.createElement("option");
              option02.value=infos.Mention1;
              option02.text=infos.Mention1;
              zone2.appendChild(option02);


              var option12 = document.createElement("option");
              option12.value="A.";
              option12.text="A";
              zone2.appendChild(option12);

              var option22 = document.createElement("option");
              option22.value="B";
              option22.text="B";
              zone2.appendChild(option22);

              var option32 = document.createElement("option");
              option32.value="C";
              option32.text="C";
              zone2.appendChild(option32);

              var option42 = document.createElement("option");
              option42.value="D.";
              option42.text="D";
              zone2.appendChild(option42);

              var option52 = document.createElement("option");
              option52.value="E";
              option52.text="E";
              zone2.appendChild(option52);

              var option62 = document.createElement("option");
              option62.value="F";
              option62.text="F";
              zone2.appendChild(option62);

              var option72 = document.createElement("option");
              option72.value="G";
              option72.text="G";
              zone2.appendChild(option72);

              var option82 = document.createElement("option");
              option82.value="-";
              option82.text="-";
              zone2.appendChild(option82);
              
              

              tdchamp2.appendChild(zone2);
              zone2.addEventListener("change", function() {
                Afficher(zone2.value,infos.Matricule,"zone_2");
              });

              }else{
             var zone2=document.createElement("select");
              zone2.setAttribute('style','width:60px;');
              //zone5.setAttribute('value',infos.Decision_jury);

              var option02 = document.createElement("option");
              option02.value=infos.Mention1;
              option02.text=infos.Mention1;
              zone2.appendChild(option02);


              var option12 = document.createElement("option");
              option12.value="AA";
              option12.text="AA";
              zone2.appendChild(option12);

              var option22 = document.createElement("option");
              option22.value="A";
              option22.text="A";
              zone2.appendChild(option22);

              var option32 = document.createElement("option");
              option32.value="S";
              option32.text="S";
              zone2.appendChild(option32);

              var option42 = document.createElement("option");
              option42.value="D";
              option42.text="D";
              zone2.appendChild(option42);

              var option52 = document.createElement("option");
              option52.value="GD";
              option52.text="GD";
              zone2.appendChild(option52);

              var option62 = document.createElement("option");
              option62.value="TGD";
              option62.text="TGD";
              zone2.appendChild(option62);

              var option72 = document.createElement("option");
              option72.value="-";
              option72.text="Null";
              zone2.appendChild(option72);
              

              tdchamp2.appendChild(zone2);
              zone2.addEventListener("change", function() {
                Afficher(zone2.value,infos.Matricule,"zone_2");
              });

             

              }

 /************
  * 
  */

              tdchamp2.appendChild(zone2);
              zone2.addEventListener("blur", function() {
                Afficher(zone2.value,infos.Matricule,"zone_2");
              });


              var zone3=document.createElement('input');
              zone3.setAttribute('type','text');
              zone3.setAttribute('id','zone3'+i);
              zone3.setAttribute('style','width:40px;');
              zone3.setAttribute('value',infos.Session2);



              tdchamp3.appendChild(zone3);
              zone3.addEventListener("blur", function() {
                Afficher(zone3.value,infos.Matricule,"zone_3");
              });


              /*var zone4=document.createElement('input');
              zone4.setAttribute('type','text');
              zone4.setAttribute('id','zone4'+i);
              zone4.setAttribute('style','width:60px;');
              zone4.setAttribute('value',infos.Mention2);
              //zone4.setAttribute('value','zone4'+i);

              tdchamp4.appendChild(zone4);
              zone4.addEventListener("blur", function() {
                Afficher(zone4.value,infos.Matricule,"zone_4");
              });*/



              /**************************************************
               * 
               * */


               if (systeme=="LMD"){
              var zone4=document.createElement("select");
              zone4.setAttribute('style','width:60px;');
              //zone5.setAttribute('value',infos.Decision_jury);

              var option04 = document.createElement("option");
              option04.value=infos.Mention2;
              option04.text=infos.Mention2;
              zone4.appendChild(option04);


              var option14 = document.createElement("option");
              option14.value="A.";
              option14.text="A";
              zone4.appendChild(option14);

              var option24 = document.createElement("option");
              option24.value="B";
              option24.text="B";
              zone4.appendChild(option24);

              var option34 = document.createElement("option");
              option34.value="C";
              option34.text="C";
              zone4.appendChild(option34);

              var option44 = document.createElement("option");
              option44.value="D.";
              option44.text="D";
              zone4.appendChild(option44);

              var option54 = document.createElement("option");
              option54.value="E";
              option54.text="E";
              zone4.appendChild(option54);

              var option64 = document.createElement("option");
              option64.value="F";
              option64.text="F";
              zone4.appendChild(option64);

              var option74 = document.createElement("option");
              option74.value="G";
              option74.text="G";
              zone4.appendChild(option74);
              

              tdchamp4.appendChild(zone4);
              zone4.addEventListener("change", function() {
                Afficher(zone4.value,infos.Matricule,"zone_4");
              });

              }else{
             var zone4=document.createElement("select");
              zone4.setAttribute('style','width:60px;');
              //zone5.setAttribute('value',infos.Decision_jury);

              var option04 = document.createElement("option");
              option04.value=infos.Mention2;
              option04.text=infos.Mention2;
              zone4.appendChild(option04);


              var option14 = document.createElement("option");
              option14.value="AA";
              option14.text="AA";
              zone4.appendChild(option14);

              var option24 = document.createElement("option");
              option24.value="A";
              option24.text="A";
              zone4.appendChild(option24);

              var option34 = document.createElement("option");
              option34.value="S";
              option34.text="S";
              zone4.appendChild(option34);

              var option44 = document.createElement("option");
              option44.value="D";
              option44.text="D";
              zone4.appendChild(option44);

              var option54 = document.createElement("option");
              option54.value="GD";
              option54.text="GD";
              zone4.appendChild(option54);

              var option64 = document.createElement("option");
              option64.value="TGD";
              option64.text="TGD";
              zone4.appendChild(option64);

              var option74 = document.createElement("option");
              option74.value="-";
              option74.text="Null";
              zone4.appendChild(option74);
              

              tdchamp4.appendChild(zone4);
              zone4.addEventListener("change", function() {
                Afficher(zone4.value,infos.Matricule,"zone_4");
              });

             

              }



               /*************************************************************************
                * 
                * */

              if (systeme=="LMD"){
              var zone5=document.createElement("select");
              zone5.setAttribute('style','width:100px;');
              //zone5.setAttribute('value',infos.Decision_jury);

              var option0 = document.createElement("option");
              option0.value=infos.Decision_jury;
              option0.text=infos.Decision_jury;
              zone5.appendChild(option0);


              var option1 = document.createElement("option");
              option1.value="Admis";
              option1.text="Admis";
              zone5.appendChild(option1);

              var option2 = document.createElement("option");
              option2.value="Admis sous condition";
              option2.text="Admis sous condition";
              zone5.appendChild(option2);

              var option3 = document.createElement("option");
              option3.value="Recaler";
              option3.text="Recaler";
              zone5.appendChild(option3);

              var option3 = document.createElement("option");
              option3.value="null";
              option3.text="null";
              zone5.appendChild(option3);

              tdchamp5.appendChild(zone5);
              zone5.addEventListener("change", function() {
                Afficher(zone5.value,infos.Matricule,"zone_5");
              });

              }else{
             var affiche_colonne_decision = document.getElementById("decision");
             affiche_colonne_decision.innerHTML="";


              }
             
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdchamp1);
              tr.appendChild(tdchamp2);
              tr.appendChild(tdchamp3);
              tr.appendChild(tdchamp4);
              if (systeme=="LMD"){tr.appendChild(tdchamp5);}

              
              
              
              tbody.appendChild(tr);

              
              i++;
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);

        });
        
     
          tableau.appendChild(tbody);

}



// Récupérer le tableau
var table = document.getElementById('table');
// Récupérer toutes les lignes du tableau
var rows = table.getElementsByTagName('tr');

// Ajouter un gestionnaire d'événement sur chaque élément de chaque ligne
for (var k = 0; k < rows.length; k++) {
  var cells = rows[k].getElementsByTagName('td');
  for (var j = 0; j < cells.length; j++) {
    cells[j].addEventListener('mouseover', function() {
      // Changer la couleur de fond de la ligne lorsque le curseur est sur l'un de ses éléments
      this.parentNode.style.backgroundColor = 'red';
    });

    cells[j].addEventListener('mouseout', function() {
      // Remettre la couleur de fond d'origine lorsque le curseur quitte un élément de la ligne
      this.parentNode.style.backgroundColor = '';
    });
  }
}


document.addEventListener('DOMContentLoaded',function(){
    var options = document.querySelectorAll('input[name="option"]');

    options.forEach(function(option){
        option.addEventListener('click',function(){
            console.log('option selectionnée est '+this.value);
        });
    });
});


// Fonction à exécuter lors du clic sur le bouton
function actualiserPage() {

  var code_promo=promotion.value;
  var idAnnee_Acad=annee.value;
  

  const url1='terminer_encodage.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_Acad;  
  

    fetch(url1) 
    
    
     //location.reload(); // Actualiser la page

}


////////////////////////////////////////////////////////////////////////////////////////////

var abreviation_promotion="";
// la validation des etudiants
function Affichage_etudiant1(code_promo,idAnnee_Acad) 
{
alert("Selectionner la mention d'affectation avant de cocher un etudiant à affecter !!");

  var tableau = document.getElementById("table1");
  var tbody = document.createElement("tbody");


  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
    
  }
  console.log(tableau.rows.length);
  // Contacte de l'API PHP
  const url='affichage_etudiant_affectation_mention.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_Acad+'&action="rien"';  
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

              console.log("l abreviation de la promotion est ::::"+infos.Abréviation);

              abreviation_promotion= infos.Abréviation;
              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              //var tddecision = document.createElement("td");
              //var tdmention = document.createElement("td");
              var tdactiver = document.createElement("td");
            
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;



              /*var zone1=document.createElement('input');
              zone1.setAttribute('type','text');
              zone1.setAttribute('style','width:40px;');
              zone1.setAttribute('value',infos.Decision_jury);
              zone1.disabled=true;

              tddecision.appendChild(zone1);*/
              

              var zone2=document.createElement("input");
              zone2.setAttribute('type','checkbox');
              //zone2.checked=true;
              //zone2.setAttribute('style','width:40px;');

              tdactiver.appendChild(zone2);
              
                 zone2.addEventListener("change", function() {
                  if (zone2.checked) {
                Affection_etudiant_mention(promo_affectation.value,infos.Matricule,"zone_11");
                }
              });
              
             


              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              //tr.appendChild(tddecision);
              tr.appendChild(tdactiver);
              

              
              
              
              tbody.appendChild(tr);

              
              i++;
            //}  
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);

        });
        
     
          tableau.appendChild(tbody);

}


var mention_affecter = "";



function sect_faculte(){
    var selectedElement1 = document.getElementById("faculte1").value;
    var systeme1 = document.getElementById("option12");
    var systeme2 = document.getElementById("option22");
    ///alert selectedElement; 
    if (selectedElement1 != '') {

  // Effectuer une requête AJAX pour charger les données de la base de données
    var xhttp1 = new XMLHttpRequest();
    xhttp1.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var data1 = JSON.parse(this.responseText);
        populateComboBox1(data1);

      }
    };
    var systeme="";
    if (systeme1.checked) systeme="Ancien systeme";
    else systeme="LMD";
    xhttp1.open("GET", "affiche_mention_affect_etud.php?element1=" + selectedElement1+"&systeme="+systeme, true);
    xhttp1.send();
  } else {
    // Réinitialiser le combobox y
    document.getElementById("combo_promotion").innerHTML = '<option value="">Sélectionner une faculté</option>';
  }
   afficher_mention(selectedElement1,systeme);
}

function populateComboBox1(data1) {
  var combo_promotion = document.getElementById("promotion1");
   

  combo_promotion.innerHTML = '<option value="">Sélectionner</option>';
  for (var i = 0; i < data1.length; i++) {
    var option = document.createElement("option");
    option.value = data1[i].Code_Promotion;
    option.text = data1[i].Promtion;
    combo_promotion.appendChild(option);


  }
 }

function afficher_mention(selectedElement1,systeme){

  var bloc_mention = document.getElementById("mon_blo_affiche_ration_mention");
  bloc_mention.setAttribute('class','bg-dark text-center');
  const url='select_mention.php?id_mention1='+selectedElement1+'&systeme='+systeme;  
  //const url='donnee2.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_Acad;  
  //console.log("la valeur qui vient est :::"+selectedElement1);
  var combobox_mention = document.createElement("select");
  combobox_mention.setAttribute('style','width:100%;');
  combobox_mention.setAttribute('id','mention_passage');

  var label_affiche = document.createElement("label");
  label_affiche.innerHTML="Selectionner la mention d'affectation";
  label_affiche.setAttribute('class','text-white');


  combobox_mention.innerHTML = '<option value="">Sélectionner</option>';
    fetch(url) 
    .then(response => response.json())
    .then(data2 => 
    {
        bloc_mention.innerHTML="";
        bloc_mention.appendChild(label_affiche);
      data2.forEach(infos =>
        {

    
  //for (var i = 0; i < data.length; i++) {
    var option = document.createElement("option");
    option.value = infos.idMentions;
    option.text = infos.Libelle_mention;
    
   

    combobox_mention.appendChild(option);
  //}


   /*var bouton1=document.createElement("input");
    bouton1.type='radio';
    bouton1.name='mention';
    bouton1.value=infos.idMentions;
    console.log("la valeur de id mention est "+bouton1.value);

    var label=document.createElement("label");
    label.innerText=infos.Libelle_mention;*/
    combobox_mention.addEventListener("change", function() {
      mention_affecter=combobox_mention.value;
  console.log("la valeur de mention est :: ::"+ mention_affecter);  

    });
   
    //bloc_mention.appendChild(document.createElement('br'));
    bloc_mention.appendChild(combobox_mention);
    });
      });
}




function Affection_etudiant_mention(promo_affectation,Matricule,zone) 
{
console.log("la mention qui vient dans l autre script est : :"+mention_affecter+" : : la promotion :: "+promo_affectation+" : "+abreviation_promotion+ " : : le matricule etudiant : : "+Matricule+" :: annee academique ::"+annee1.value);


 const url='passer_mention.php?mention='+mention_affecter+'&Matricule1='+Matricule+'&code_promo='+promo_affectation +'&id_annee_acad='+annee1.value  +'&abreviation='+abreviation_promotion;  
 //mention="+mention_affecter +"&Matricule="+Matricule +"&code_promo="+promo_affectation +"&id_annee_acad="+annee1.value  +"&abreviation="+abreviation_promotion);
    fetch(url) 
    

}


/****************************************************************************************
 * activation etudiant dans la promotion actuelle
 ************************************************************************************ */

  promo_activation.addEventListener('change',(event) => {
  var idAnnee_Acad=annee_activation.value;
  var code_promo1=promo_activation.value;
   var affich_promotion = document.getElementById('affiche_promoton'); 
   var affich_annee_academique = document.getElementById('annee_academique'); 
   
  var selectop=promo_activation.options[promo_activation.selectedIndex];
    affich_promotion.innerHTML=selectop.text;
  var selectann=annee_activation.options[annee_activation.selectedIndex];
  affich_annee_academique.innerHTML= selectann.text;

  //console.log("la valeur de promotion est :::: "+code_promo1)
 // console.log("la valeur de annee est :::: "+idAnnee_Acad)
  Affichage_etudiant_activation(code_promo1,idAnnee_Acad);

});

function Affichage_etudiant_activation(code_promo,idAnnee_Acad) 
{
  var tableau = document.getElementById("table_activation");
  var tbody = document.createElement("tbody");


  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
    
  }
  console.log(tableau.rows.length);
  // Contacte de l'API PHP
  const url='affichage_etudiant_affectation_mention.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_Acad+'&action=activation_etudiant';  
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

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              //var tddecision = document.createElement("td");
              //var tdmention = document.createElement("td");
              var tdactiver = document.createElement("td");
            
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;



              /*var zone1=document.createElement('input');
              zone1.setAttribute('type','text');
              zone1.setAttribute('style','width:40px;');
              zone1.setAttribute('value',infos.Decision_jury);
              zone1.disabled=true;

              tddecision.appendChild(zone1);*/
              

              var zone2=document.createElement("input");
              
              zone2.setAttribute('type','checkbox');
              if (infos.Active=='Actif') zone2.checked=true;
              //zone2.setAttribute('style','width:40px;');

              tdactiver.appendChild(zone2);
              
                 zone2.addEventListener("change", function() {
                  if (zone2.checked) {
                activation_etudiant_dans_promotion_courante(code_promo,infos.Matricule,idAnnee_Acad,"Actif");
                }else{
                activation_etudiant_dans_promotion_courante(code_promo,infos.Matricule,idAnnee_Acad," ");
                }
              });
              
             


              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              //tr.appendChild(tddecision);
              tr.appendChild(tdactiver);
              

              
              
              
              tbody.appendChild(tr);

              
              i++;
            //}  
              
        });
      //liste.style.display='inline-block';
      //liste_label.style.display='inline-block';

        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);

        });
        
     
          tableau.appendChild(tbody);

}

function activation_etudiant_dans_promotion_courante(code_promo,matricule,annee,action){

   const url='activation_etudiant.php?Matricule1='+matricule+'&code_promo='+code_promo+'&id_annee_acad='+annee+'&action='+action;  
    fetch(url) 
}




/*****************************************************************************************************
 * impression liste des etudiants
 * ******************************************************************************************************/
const liste1 = document.getElementById("table_liste");
 // console.log("la valeur de la liste "+liste1.value);
/*
  liste1.addEventListener('change',(event) => {
  var idAnnee_Acad=annee_activation.value;
  var code_promo1=promo_activation.value;
  var liste_imprimer=liste1.value;
  console.log("je suis dans liste1 ");
 // console.log("la valeur de annee est :::: "+idAnnee_Acad)
  imprimer_liste(code_promo1,idAnnee_Acad, liste_imprimer);
});*/
 var bouton_importer = document.getElementById('importer');
  bouton_importer.addEventListener('click',(event) => {
  
  console.log("je suis dans le bouton ");
 // console.log("la valeur de annee est :::: "+idAnnee_Acad)
  imprime_liste1(/*code_promo1,idAnnee_Acad, liste_imprimer*/);
});

function imprime_liste1() 
{
  var tableau = document.getElementById("table_liste");
  tableau.style.size='9px';
  var tbody = document.createElement("tbody");
  var idAnnee_Acad=annee_activation.value;
  var code_promo1=promo_activation.value;
  //var liste_imprimer=liste1.value;

  console.log("je suis dans liste1 ");

  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
    
  }
  console.log(tableau.rows.length);
  // Contacte de l'API PHP
  const url='affichage_etudiant_affectation_mention.php?code_promo='+code_promo1+'&Id_annee_acad='+idAnnee_Acad+'&action=liste';  
  var i=1;

    fetch(url) 
    .then(response => response.json())
    .then(data1 => 
    {
      data1.forEach(infos =>
        {
          // Création de TR
          if (infos.Active=="Actif") {
              var tr = document.createElement("tr");

              //console.log("l abreviation de la promotion est ::::"+infos.Abreviation);

              //abreviation_promotion= infos.Abreviation;
              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              //var tddecision = document.createElement("td");
              //var tdmention = document.createElement("td");
              var tdchamp1 = document.createElement("td");
             
            
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdchamp1.textContent=infos.Sexe;



              /*var zone1=document.createElement('input');
              zone1.setAttribute('type','text');
              zone1.setAttribute('style','width:40px;');
              zone1.setAttribute('value',infos.Decision_jury);
              zone1.disabled=true;

              tddecision.appendChild(zone1);*/
              

              
              
             


              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              //tr.appendChild(tddecision);
              tr.appendChild(tdchamp1);
              
              

              
              
              
              tbody.appendChild(tr);

              
              i++;
            }  
              
        });
        var affich_annee_academique = document.getElementById('nbr_etudiant'); 
        affich_annee_academique.innerHTML=i;
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);

        });
        
     
          tableau.appendChild(tbody);
    var block6 = document.getElementById('block6'); block6.style.display='none';
    var block7 = document.getElementById('block7'); block7.style.display='block';
    var block8 = document.getElementById('block8'); block8.style.display='block';
   
    //affich_promotion.innerHTML=annee_activation.text;
     /*var option02 = document.createElement("option");
              option02.value=infos.Mention1;
              option02.text=infos.Mention1;
              zone2.appendChild(option02);*/
    //affich_promotion.innerHTML=promo_activation.o;
    
   // console.log(annee_activation.text);



}



// Fonction pour convertir le tableau en fichier PDF
function fonction_pdf() {
   let table = document.getElementById("table_liste");
    let html = table.outerHTML;
    
    // Envoi des données au serveur pour la conversion en fichier PDF
    let formData = new FormData();
    formData.append("html", html);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "convert_to_pdf.php");
    xhr.send(formData);

}


// Fonction pour imprimer le bloc de tableau


function fonction_excel() {
    var table = document.getElementById("table_liste");
    var html = table.outerHTML;

    var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);

    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = "tableau_excel.xlsx";
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Fonction pour imprimer le contenu d'un bloc
function fonction_imprimer() {

    var bloc = document.getElementById('liste_imprimer');
    var block1 = document.getElementById('block1'); block1.style.display='none';
    var block2 = document.getElementById('block2'); block2.style.display='none';

    var block3 = document.getElementById('block3'); block3.style.display='none';
    var block4 = document.getElementById('block4'); block4.style.display='none';
    
    var block5 = document.getElementById('block5'); block5.style.display='none';
    var block6 = document.getElementById('block6'); block6.style.display='none';
    var block7 = document.getElementById('block7'); block7.style.display='none';
    
    var menu = document.getElementById('menu'); menu.style.display='none';


    var entete = document.getElementById('entete'); entete.style.display='block';
    var block8 = document.getElementById('block8'); block8.style.display='block';
    var pied = document.getElementById('pied');
    var pag=block8.children;
    var pagecomptage = 1;
    
     for (var i = 0; i < pag ; i++) {
      var page = pag[i];
      console.log('la valeur de temps'+block8.children.length);
      var pagenum= document.getElementById('numero');
      pagenum.textContent=pagecomptage;

     //page.insertBefore(pagenum, page.firstChild);
      
      pagecomptage++;

      //.textContent='page ' + (i+1);
    }

   /* var nbr=document.getElementsByClassName('numero');
    for (var i = 0; i < nbr.length; i++) {
      nbr[i].textContent='page ' + (i+1);
    }*/
   
   window.print()

   
}
