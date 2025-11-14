


console.log("nous sommes dans select promo inscription"); 
const combo_filiere=document.getElementById("filiereInscription");
const promo_filiere=document.getElementById("promoInscription");
const anne_acad_filiere=document.getElementById("Id_an_acadInscription");


if(combo_filiere!==null)
  {
   combo_filiere.addEventListener('change',(event) => {
     var id_filiere=combo_filiere.value;
     Affichage_promo(id_filiere);
     Affichage_nombre_etudiant();
    
    //console.log("le code promotion est "+promo_filie);
   });
 
  }

  document.getElementById('promoInscription').addEventListener('change', function() {

    
    Affichage_nombre_etudiant();
});
//affichage du matricule apres changement de l'annee
document.getElementById('Id_an_acadInscription').addEventListener('change', function() {
var promo_filiere=document.getElementById("promoInscription").value;
    
  console.log("le code promotion est "+promo_filiere);
  Affichage_nombre_etudiant()
});
 
   
  

  // ICI LA FONCTION POUR LA RECUPERATIONS DES PROMOTIONS EN FONCTION DE LA FILIERE CHOISIE
function Affichage_promo(Idfiliere ) {

  // Réinitialiser le contenu de la balise select des promotions
  var cmb_promotion=document.getElementById("promoInscription");


  cmb_promotion.innerHTML = "";

  
  // Contacte de l'API PHP
  const url='D_Generale/API_PHP/Recup_prom_filiere_inscription.php?idFiliere='+Idfiliere;
        
  fetch(url) 
  .then(response => response.json())
  .then(data => {
    // Créer et ajouter l'option "faites le choix"
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "faites le choix";
    cmb_promotion.appendChild(defaultOption);

    // Ajouter les options provenant de la base de données
    data.forEach(infos => {
      const option = document.createElement("option");
      option.value = infos.cd_prom;
      option.textContent = infos.abv + " - " + infos.lib_mention;
      cmb_promotion.appendChild(option);
    });
  })
  .catch(error => console.error('Erreur lors de la récupération des promotions :', error));

}

// AFFICHAGE NOMBRE ETUDIANT
function Affichage_nombre_etudiant()
{  
  var code_promo=promo_filiere.value;
  var Id_an_acad=anne_acad_filiere.value;
  Affichage_Candidat(code_promo,Id_an_acad);
    // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
    var url='D_Generale/API_PHP/API_Select_Nombre_Etudiant.php?Id_annee_acad='+Id_an_acad+'&code_promo='+code_promo;
        

    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {      

      const promo=document.getElementById("promoInscription");
      const selectedPromo = promo.options[promo.selectedIndex].text;
      //console.log("la promotion est: "+selectedPromo);
          document.getElementById("matricule").value=selectedPromo.substring(8, 15)+" "+ data.Nombre;
             

          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
         
}

// LA FONCTION D'AFFIHAGE DES ETUDIANTS D'UNE PROMOTION DANS UNE ANNEE ACADEMIQUE CHOISIE   219
function Affichage_Candidat(code_promo,Id_an_acad)
{

    // ici on appele la méthode pour l'affichage des modalités
   
    
    var tableau = document.getElementById("table_paiement");

    while (tableau .firstChild) {
      tableau .removeChild(tableau .firstChild);
    }
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
    var td6 = document.createElement("td");
      

    td1.textContent = "N°";
    td2.textContent = "Matricule";
    td3.textContent = "Nom";
    td4.textContent = "Postnom";
    td5.textContent = "Prenom";
    td6.textContent = "Sexe";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);

      
    thead.appendChild(tr1);
    tableau.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    
    // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
    var url='D_Generale/API_PHP/Liste_de_Candidats.php?Id_annee_acad='+Id_an_acad+'&code_promo='+code_promo;
        
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
              var tdsexe = document.createElement("td");
              var tdbtn = document.createElement("td");

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
              var btn=document.createElement('button');
              btn.setAttribute('type','button');
              //btn.setAttribute('class','float-end');
              btn.innerHTML = '<i class="fa fa-times" style="color: red;"></i>'; // Utilisation de Font Awesome pour la croix rouge

              tdbtn.appendChild(btn);
              
              btn.addEventListener("click", function() 
              {
                SuppressionCandidats(infos.Matricule);
                //Affichage_Imprimer(MatEtudiant);
                //Affichage_Cursus(MatEtudiant);
              });
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              tr.appendChild(tdbtn);
              tr_globale_ligne_select_etudiant=tr;
              tbody.appendChild(tr);
              i++;

              tr.addEventListener("click", function() {
                // Retirer la couleur rouge de toutes les lignes
                const rows = tbody.querySelectorAll("tr");
                rows.forEach(r => r.style.backgroundColor = '');
    
                // Colorer la ligne cliquée
                tr.style.backgroundColor = 'red';
    
                // Afficher le nom dans la console
                console.log(infos.Nom);
                document.getElementById("nom_Candidat").innerText=infos.Nom+"-"+infos.Postnom+"-"+infos.Prenom;
                document.getElementById("Nom").value=infos.Nom;
                document.getElementById("Postnom").value=infos.Postnom;
                document.getElementById("Prenom").value=infos.Prenom;
              
              });
      
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tableau.appendChild(tbody);
}
//EFFACER LES CANDIDATS
function SuppressionCandidats(MatEtudiant) {
  var lien = 'D_Perception/API_PHP/API_Sup_Candidats.php?MatCandidat=' + MatEtudiant;
  console.log('matricule candidat sup : ' + MatEtudiant);

  fetch(lien)
      .then(response => response.json())
      .then(data => {
          swal({
              title: data.success ? "Succès" : "Erreur",
              text: data.message,
              icon: data.success ? "success" : "error",
              button: "OK",
              closeOnClickOutside: false,
              closeOnEsc: false
          }).then(() => {
              if (data.success) {
                  // Appelle la fonction pour actualiser le tableau ici
                  var code_promo = promo_filiere.value;
                  var Id_an_acad = anne_acad_filiere.value;
                  Affichage_Candidat(code_promo, Id_an_acad);
                  Affichage_nombre_etudiant();
              }
          });
      })
      .catch(error => {
          console.error('Erreur lors de la suppression:', error);
          swal({
              title: "Erreur",
              text: "Une erreur s'est produite lors de la suppression du candidat.",
              icon: "error",
              button: "OK",
              closeOnClickOutside: false,
              closeOnEsc: false
          });
      });
}


//ENREGISTREMENT DES CANDIDAT ETUDIANTS, DE LEUR PAIEMENT AINSI QUE DANS D'AUTRES TABLES

document.getElementById('enregistrer').addEventListener('click', function() {
  var promo_filier=promo_filiere.value;
  var annee_acad=anne_acad_filiere.value;

  //Affichage_Montant_a_payer();
  EnregistrementCandidat(promo_filier,annee_acad);
 


});

function  EnregistrementCandidat(promo_filier,annee_acad)
{  
  const txt_mat_etudiant=document.getElementById("matricule").value;
  const txt_nom_etudiant=document.getElementById("Nom").value;
  const txt_postnom_etudiant=document.getElementById("Postnom").value;
  const txt_prenom_etudiant=document.getElementById("Prenom").value;
  const txt_sexe = document.getElementById("sexe").value;
  
  const idfrais=document.getElementById("idfrais").innerText;
  const montantpaie=document.getElementById("montantàinserer").value;
  const datepaie=document.getElementById("datepaie").value;
  

  console.log("le matricule du candidat est "+txt_mat_etudiant);
  console.log("le nom du candidat est "+txt_sexe);
    
    // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
    var url='D_Generale/API_PHP/Enregistrement_Candidat.php?matricule='+txt_mat_etudiant+'&nom='+txt_nom_etudiant+'&postnom='+txt_postnom_etudiant+'&prenom='+txt_prenom_etudiant
    +'&sexe='+txt_sexe+'&idfrais='+idfrais+'&datepaie='+datepaie+'&promo_filiere='
    +promo_filier+'&annee_acad='+annee_acad+'&montantpaie='+montantpaie;
  
    fetch(url)
    .then(response => response.json())
    .then(data => {
      swal({
        title: data.success ? "Succès" : "Erreur",
        text: data.message,
        icon: data.success ? "success" : "error",
        button: "OK",
        closeOnClickOutside: false,
        closeOnEsc: false
       
        }).then(() => {
        if (data.success) {
          // Appelle la fonction pour actualiser le tableau ici
          var code_promo=promo_filiere.value;
          var Id_an_acad=anne_acad_filiere.value;
          Affichage_Candidat(code_promo,Id_an_acad);
          imprimer_reçu();
          Affichage_nombre_etudiant();
          document.getElementById("Nom").value="";
          document.getElementById("Postnom").value="";
          document.getElementById("Prenom").value="";
          document.getElementById("sexe").value="";
        }
      });
    })
    .catch(error => {
        alert("Erreur lors de l'enregistrement : " + error);
    });
    
}
//SELECTION DE FRAIS D'INSCRIPTION SELON LE CRITERE


function Affichage_Montant_a_payer()
{  
  const select_critere=document.getElementById("choix");
  const zonecritere = select_critere.options[select_critere.selectedIndex].text;
 
    var lien='D_Generale/API_PHP/API_Select_Frais_Fixe.php?Choix='+zonecritere;
        
//-------------
    fetch(lien) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
             
              //var montant=infos.Montant;
              document.getElementById("montant").innerText= infos.Montant+" $";
              document.getElementById("montantàinserer").innerText= infos.Montant;
              //document.getElementById("montantrecu").innerText= infos.Montant+" $";
              document.getElementById("idfrais").innerText= infos.idFrais;
             
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
        
}
//AFFICHAGE DU MONTANT A PAYE SELON LE CRITERE
document.getElementById('choix').addEventListener('change', function() {
  
  Affichage_Montant_a_payer();
});

function imprimer_reçu(){
  var contenu = document.getElementById('bloc-imp-recu').innerHTML;
  var fenetreImpression = window.open('', '', 'height=600,width=800');
  fenetreImpression.document.write('<html><head><title>Impression Rapport de Paie</title>');
  // Ajout des styles pour la marge
  fenetreImpression.document.write('<style>body { margin-top: 70px; }</style>');
  fenetreImpression.document.write('</head><body >');
  fenetreImpression.document.write(contenu);
  fenetreImpression.document.write('</body></html>');
  fenetreImpression.document.close();
  fenetreImpression.print();
  fenetreImpression.close();
}