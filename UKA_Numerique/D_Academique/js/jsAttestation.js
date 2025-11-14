console.log("nous sommes dans le jsAttestation");

var rech_par="matricule";

function affiche_recherche(){
  var zone_active = document.getElementById("zone_rech1");
  zone_active.disabled=false;
  zone_active.focus();
}

//££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££
const filiere=document.getElementById("faculte");
var position =1;
  	 
  document.addEventListener('DOMContentLoaded', function() {
  	var searchInput = document.getElementById("zone_rech1");
    searchInput.addEventListener('input', function() {
        var query = searchInput.value.trim();
        console.log("nous entrain de saisir");
        if (query.length > 0) {
            search(query);
        } else {
    var tableau = document.getElementById("table_affichage_etudiant_attestation");
  	var tbody = document.createElement("tbody");
	while(tableau.rows.length>1){ tableau.deleteRow(1);
			}
        }
    });

 
    function search(query) {
    var id_filiere=filiere.value;
    console.log(rech_par);

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
        xhr.open('GET', 'API/affichage_etudiant.php?id_filiere='+id_filiere+'&text_rech='+query+'&rech_par='+rech_par);
        xhr.send();
    }

    function displayResults(results) {
        var html = '';
        var i=1;
    var tableau = document.getElementById("table_affichage_etudiant_attestation");
  	var tbody = document.createElement("tbody");
	while(tableau.rows.length>1){ tableau.deleteRow(1);}
console.log("CRÉATION tableau");


        if (results.length > 0) {
            results.forEach(function(infos) {
            	 var tr = document.createElement("tr");
               tr.style.cursor = 'pointer';

            	var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              var tdactiver = document.createElement("td");

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdactiver.textContent=infos.Sexe;
     

              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdactiver);
              

              tbody.appendChild(tr);
              i++;


               tr.addEventListener("click", function() {
                Recuperation_Transactions(infos.Matricule,infos.Nom,infos.Postnom, infos.Prenom, infos.Sexe, infos.LieuNaissance, infos.DateNaissance,tr);
              });
            });
        } else {
        	var tr = document.createElement("tr");
        	var info = document.createElement("td");
        	info.textContent="Aucune information trouvé pour votre recherche";
        	tr.appendChild(info);
        }
         tableau.appendChild(tbody);
    }
});
var val_tr;
var nom_etudiant="";
var matricule_cursus=" ";
function Recuperation_Transactions(Matricule,Nom,Postnom, Prenom, Sexe, LieuNaissance, DateNaissance,tr1)
{
  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tableau = document.getElementById("table_affichage_etudiant_attestation");
  var rows = tableau.getElementsByTagName('tr');  
  for(var j = 0; j < rows.length; j++) 
  {
    if(j!=0) rows[j].style.backgroundColor = '';
  }
  tr1.style.backgroundColor = 'blue';
val_tr=tr1;
    document.getElementById('zone_Matricule').value=Matricule;
    document.getElementById('zone_nom').value=Nom;
    document.getElementById('zone_postnom').value=Postnom;
    document.getElementById('zone_prenom').value=Prenom;
    matricule_select=Matricule;


    document.getElementById("titre_cursus").innerHTML="Le cursus de l'etudiant (e) "
    +document.getElementById('zone_nom').value+
    " "+document.getElementById('zone_postnom').value+
    " "+document.getElementById('zone_prenom').value;

    nom_etudiant=document.getElementById('nom_etudiant').innerHTML=Nom+" "+Postnom+" "+Prenom;

  


    document.getElementById('matricule_etudiant').innerHTML=Matricule;
    document.getElementById('lieu').innerHTML=LieuNaissance;
    //const options={weekday: 'long', year: 'numeric', month:'long', day: 'numeric'};
    const options={ year: 'numeric', month:'long', day: 'numeric'};
    const date = new Date(DateNaissance);
    const longDate = date.toLocaleDateString('fr-FR', options);
    document.getElementById('date_naiss').innerHTML=longDate;

    



    const date_jour = new Date();
    const longDate_jour = date_jour.toLocaleDateString('fr-FR', {year: 'numeric', month:'long', day: 'numeric'});
    document.getElementById('date_jour').innerHTML=longDate_jour;

     if (Sexe=="M")
    {
      document.getElementById('nomme').innerHTML="le nommé";
      document.getElementById('ne').innerHTML="né";
      document.getElementById('etudiant').innerHTML="étudiant";
      document.getElementById('inscrit').innerHTML="inscrit";
    }
    else if (Sexe=="F")
    {
      document.getElementById('nomme').innerHTML="la nommée";
      document.getElementById('ne').innerHTML="née";
      document.getElementById('inscrit').innerHTML="inscrite";

    }
    else
    {
      document.getElementById('nomme').innerHTML="le nommé";
      document.getElementById('ne').innerHTML="né";
      document.getElementById('inscrit').innerHTML="inscrit";

    }
      
      var fac=document.getElementById('faculte').selectedOptions[0];
      var fac_val=document.getElementById('faculte').value;
      if (fac_val==5 || fac_val==8)
      {
      document.getElementById('fac').innerHTML="d'<br>"+fac.textContent+'</b>';
      }else  document.getElementById('fac').innerHTML=' de <b>'+fac.textContent+'</b>';

      CODE_QR(Nom,Postnom,Prenom,Matricule,fac.textContent,longDate_jour);

      document.getElementById('zone_sexe').innerHTML="";
    var option1 = document.createElement("option");
    if (Sexe=="M"){
    option1.value="M";
    option1.text="Masculin";

    }
    else if (Sexe=="F") {
    option1.value="F";
    option1.text="Féminin";

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

  affiche_curcus(Matricule,fac_val);
  matricule_cursus=Matricule;
}

var Annee_debut_1="";
var nbr_Annee_debut_1="";
var matricule_select="";

function affiche_curcus(Matricule,fac_val)
{

var url='API/affiche_curcus.php?matricule='+Matricule+'&vale=moi';
        
    var tableau2 = document.getElementById("tableau_curcus_document");
    var tableau1 = document.getElementById("tableau_curcus");
    var tableau3 = document.getElementById("tableau_curcus_modif");
    var tbody = document.createElement("tbody");
    var tbody1 = document.createElement("tbody");
    var tbody3 = document.createElement("tbody");

    while(tableau1.rows.length>1){ tableau1.deleteRow(1);}
    while(tableau2.rows.length>1){ tableau2.deleteRow(1);}
    while(tableau3.rows.length>1){ tableau3.deleteRow(1);}
       var y=0;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {

        
              var tr = document.createElement("tr");

              var tdnum = document.createElement("td");
              tdnum.textContent = ">>";
              

              var tdannee= document.createElement("td");
              var tdpromotion = document.createElement("td");
              var tdmention = document.createElement("td");
              var tdsesion = document.createElement("td");

              var mention_promotion=infos.Libelle_mention;

              if (fac_val!=1){
              // option à mettre sur l'attestation -------------------------------------------------
              document.getElementById('option_fac').innerHTML=', Option <b>'+mention_promotion+'</b>';
              //__________________________________________________________________________________
            }else {
              document.getElementById('option_fac').innerHTML='';
               }

              console.log('la mention est :'+mention_promotion);

              var mention="";
              var session="";

              tdannee.textContent =infos.Annee_debut+" - "+infos.Annee_fin;
              tdpromotion.textContent=infos.Abréviation;
              
                if (infos.Mention1=="S"){mention="Satisfaction"; session=" 1ère";}
                else if (infos.Mention1=="D"){mention="Distinction"; session="1ère";}
                else if (infos.Mention1=="GD"){mention=" Grande Distinction"; session="1ère";}
                else if (infos.Mention1=="TGD"){mention=" Très Grande Distinction"; session="1ère";}

                if (infos.Mention2=="S"){mention="Satisfaction"; session="2ème";}
                else if (infos.Mention2=="D"){mention="Distinction"; session="2ème";}
                else if (infos.Mention2=="GD"){mention="Grande Distinction"; session="2ème";}
                else if (infos.Mention2=="TGD"){mention="Très Grande Distinction"; session="2ème";}


                if (infos.Mention2=="A"){mention="Ajourné"; session="2ème";}

            tdmention.textContent=mention;
            tdsesion.textContent=session;
 

              tr.appendChild(tdnum);
              tr.appendChild(tdannee);
              tr.appendChild(tdpromotion);
              tr.appendChild(tdmention);
              tr.appendChild(tdsesion);

              tbody.appendChild(tr);

              var tr1 = document.createElement("tr");

              var tdpuce = document.createElement("td");
              tdpuce.textContent = ">>";
              

              var tdannee_1= document.createElement("td");
              var tdpromotion_1 = document.createElement("td");
              var tdmention_1 = document.createElement("td");
              var tdsesion_1 = document.createElement("td");

              var mention_1="";
              var session_1="";

              tdannee_1.textContent =infos.Annee_debut+" - "+infos.Annee_fin;
              tdpromotion_1.textContent=infos.Abréviation;
              Annee_debut_1=infos.Annee_debut;
              

              
                if (infos.Mention1=="S"){mention_1="Satisfaction"; session_1=" 1ère";}
                else if (infos.Mention1=="D"){mention_1="Distinction"; session_1="1ère";}
                else if (infos.Mention1=="GD"){mention_1=" Grande Distinction"; session_1="1ère";}
                else if (infos.Mention1=="TGD"){mention_1=" Très Grande Distinction"; session_1="1ère";}

                if (infos.Mention2=="S"){mention_1="Satisfaction"; session_1="2ème";}
                else if (infos.Mention2=="D"){mention_1="Distinction"; session_1="2ème";}
                else if (infos.Mention2=="GD"){mention_1="Grande Distinction"; session_1="2ème";}
                else if (infos.Mention2=="TGD"){mention_1="Très Grande Distinction"; session_1="2ème";}

                 if (infos.Mention2=="A"){mention_1="Ajourné"; session_1="2ème";}

            tdmention_1.textContent=mention_1;
            tdsesion_1.textContent=session_1;
               
              tr1.appendChild(tdpuce);
              tr1.appendChild(tdannee_1);
              tr1.appendChild(tdpromotion_1);
              tr1.appendChild(tdmention_1);
              tr1.appendChild(tdsesion_1);
             

              tbody1.appendChild(tr1);
              y++;
              nbr_Annee_debut_1=Annee_debut_1-y+1;
              Annee_debut_1++;
               document.getElementById('anne_debut_ac').innerHTML=nbr_Annee_debut_1 +" à "+Annee_debut_1;


               var tr2 = document.createElement("tr");

              var tdannee_2= document.createElement("td");
              var tdpromotion_2 = document.createElement("td");
              var tdsession_21 = document.createElement("td");
              var tdmention_21 = document.createElement("td");
              var tdsession_22 = document.createElement("td");
              var tdmention_22 = document.createElement("td");
              var tddecision = document.createElement("td");

              tdannee_2.textContent =infos.Annee_debut+" - "+infos.Annee_fin;
              tdpromotion_2.textContent=infos.Abréviation;

              var zone1=document.createElement('input');
              zone1.setAttribute('type','text');
              zone1.setAttribute('style','width:60px;');
              zone1.setAttribute('value',infos.Session1);

              tdsession_21.appendChild(zone1);
              zone1.addEventListener("blur", function() {
                Afficher(zone1.value,infos.Matricule,infos.idAnnee_Acad,infos.Code_Promotion, "zone_1");
              });


              var zone2=document.createElement('input');
              zone2.setAttribute('type','text');
              zone2.setAttribute('style','width:60px;');
              zone2.setAttribute('value',infos.Mention1);

              tdmention_21.appendChild(zone2);
              zone2.addEventListener("blur", function() {
                Afficher(zone2.value,infos.Matricule,infos.idAnnee_Acad,infos.Code_Promotion,"zone_2");
              });


              var zone3=document.createElement('input');
              zone3.setAttribute('type','text');
              zone3.setAttribute('style','width:60px;');
              zone3.setAttribute('value',infos.Session2);

              tdsession_22.appendChild(zone3);
              zone3.addEventListener("blur", function() {
                Afficher(zone3.value,infos.Matricule,infos.idAnnee_Acad,infos.Code_Promotion,"zone_3");
              });

               var zone4=document.createElement('input');
              zone4.setAttribute('type','text');
              zone4.setAttribute('style','width:60px;');
              zone4.setAttribute('value',infos.Mention2);

              tdmention_22.appendChild(zone4);
              zone4.addEventListener("blur", function() {
                Afficher(zone4.value,infos.Matricule,infos.idAnnee_Acad,infos.Code_Promotion,"zone_4");
              });

               var zone5=document.createElement('input');
              zone5.setAttribute('type','text');
              zone5.setAttribute('style','width:80px;');
              zone5.setAttribute('value',infos.Decision_jury);

              tddecision.appendChild(zone5);
              zone5.addEventListener("blur", function() {
                Afficher(zone5.value,infos.Matricule,infos.idAnnee_Acad,infos.Code_Promotion,"zone_5");
              });

               
              tr2.appendChild(tdannee_2);
              tr2.appendChild(tdpromotion_2);
              tr2.appendChild(tdsession_21);
              tr2.appendChild(tdmention_21);
              tr2.appendChild(tdsession_22);
              tr2.appendChild(tdmention_22);
              tr2.appendChild(tddecision);
             

              tbody3.appendChild(tr2);

            });
           
      tableau1.appendChild(tbody);
      tableau2.appendChild(tbody1);
      tableau3.appendChild(tbody3);
  });
}


function  Afficher(valeur_zone, matricule, annee_academique,Code_Promotion, zone){


  console.log("annee_academique est "+annee_academique+" "+matricule+ " "+zone);
    const url='../D_Encodage/Encodage_etudiant.php?valeur_envoyee='
    +valeur_zone+'&Matricule='+matricule
    +'&code_promo='+Code_Promotion+'&id_annee_acad='+annee_academique
    +'&Zone='+zone;

    fetch(url) 
}


function  CODE_QR(Nom, Postnom, Prenom,Matricule, fac, longDate){
console.log("nous sommes dans qr code");

    const url='API/code_qr_attestation.php?nom_etudiant='
    +Nom+'&postnom_etudiant='+Postnom
    +'&prenom_etudiant='+Prenom
    +'&matricule='+Matricule
    +'&faculte='+fac+'&date_livraison='+longDate;

    fetch(url) 
}

function Imprimer_attestation() {

    var attestation_document = document.getElementById('attestation_document'); attestation_document.style.display='block';
    var attesttion_page = document.getElementById('attesttion_page'); attesttion_page.style.display='none';
    var pied = document.getElementById('pied'); pied.style.display='none';
    var menu = document.getElementById('menu'); menu.style.display='none';
    var logo = document.getElementById('logo1'); logo.style.display='none';

  


    document.getElementById('title').innerHTML="Attestation_"+nom_etudiant+"_"+document.getElementById("faculte").options[document.getElementById("faculte").selectedIndex].text;
    window.print();

    var attestation_document = document.getElementById('attestation_document'); attestation_document.style.display='none';
    var attesttion_page = document.getElementById('attesttion_page'); attesttion_page.style.display='block';
    var pied = document.getElementById('pied'); pied.style.display='block';
    var menu = document.getElementById('menu'); menu.style.display='block';
    var logo = document.getElementById('logo1'); logo.style.display='block';
    document.getElementById('title').innerHTML="Université Notre-Dame du Kasayi";
}


//création des événement pour les zones de texte pour la modification dans la base de données.
//£££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££

var zone_nom = document.getElementById('zone_nom');

zone_nom.addEventListener("blur", function() {
  var texte = zone_nom.value;
  modifier(matricule_select,texte,"zone_nom");
  Recuperation_Transactions(matricule_select,texte,zone_postnom.value, zone_prenom.value, zone_sexe.value, zone_lieu_naiss.value, zone_date_naiss.value,val_tr);


  });

var zone_postnom = document.getElementById('zone_postnom');
zone_postnom.addEventListener("blur", function() {
  var texte = zone_postnom.value;
  modifier(matricule_select,texte,"zone_postnom");
  Recuperation_Transactions(matricule_select,zone_nom.value,texte, zone_prenom.value, zone_sexe.value, zone_lieu_naiss.value, zone_date_naiss.value,val_tr);


  });

var zone_prenom = document.getElementById('zone_prenom');
zone_prenom.addEventListener("blur", function() {
  var texte = zone_prenom.value;
  modifier(matricule_select,texte,"zone_prenom");
  Recuperation_Transactions(matricule_select,zone_nom.value,zone_postnom.value, texte, zone_sexe.value, zone_lieu_naiss.value, zone_date_naiss.value,val_tr);
  });

var zone_sexe = document.getElementById('zone_sexe');
zone_sexe.addEventListener("blur", function() {
  var texte = zone_sexe.value;
  modifier(matricule_select,texte,"zone_sexe");
  Recuperation_Transactions(matricule_select,zone_nom.value,zone_postnom.value, zone_prenom.value, texte, zone_lieu_naiss.value, zone_date_naiss.value,val_tr);

  });

var zone_lieu_naiss = document.getElementById('zone_lieu_naiss');
zone_lieu_naiss.addEventListener("blur", function() {
  var texte = zone_lieu_naiss.value;
  modifier(matricule_select,texte,"zone_lieu_naiss");
  Recuperation_Transactions(matricule_select,zone_nom.value,zone_postnom.value, zone_prenom.value, zone_sexe.value, texte, zone_date_naiss.value,val_tr);

  });

var zone_date_naiss = document.getElementById('zone_date_naiss');
zone_date_naiss.addEventListener("blur", function() {
  var texte = zone_date_naiss.value;
  modifier(matricule_select,texte,"zone_date_naiss");
  Recuperation_Transactions(matricule_select,zone_nom.value,zone_postnom.value, zone_prenom.value, zone_sexe.value, zone_lieu_naiss.value, texte,val_tr);

  });

function modifier(matricule, text,zone){
const url='API/modifier_text1.php?text='+text+'&matricule='+matricule+'&zone='+zone;  
  var i=1;

    fetch(url);
}

var valider = document.getElementById("valider");
valider.onclick = function() {
  modal.style.display = "none";
  affiche_curcus(matricule_cursus);
}

//var qr = new QRCode(document.getElementById("qrcode"),{
  //  text:"erick ngiindu", width:128, height:128} );
