console.log(" Je suis dans Entre_par_guichet_banque");
/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DECLARATIONS DE COMPOSANT HTML  +++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DECLARATIONS DE COMPOSANT HTML  +++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

let txt_mat_etudiant;
let txt_nom_etudiant;
let txt_postnom_etudiant;
let txt_prenom_etudiant;
let txt_sexe_etudiant;
let txt_zone_recherche_etudiant;

let cmb_filiere;
let cmb_promotion;
let cmb_annee_academique;
let zone_etudiant;

let zone_sommeFA;
let zone_sommeEnrol_Mi_session;
let zone_sommeEnrol_Session;
let zone_sommeEnrol_Deuxime_Session;
let zone_sommeEnrol_Rattrapage_Sem_1;
let zone_sommeEnrol_Validation_Credit;


/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ La partie d'ajout des évenements à chaque composant +++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

document.addEventListener("DOMContentLoaded", function(event) {
  const container = document.getElementById("div_gen_paiement") || document;

  txt_mat_etudiant = container.querySelector("#mat_etudiant") || document.getElementById("mat_etudiant");
  txt_nom_etudiant = container.querySelector("#nom_etudiant_1") || document.getElementById("nom_etudiant_1");
  txt_postnom_etudiant = container.querySelector("#postnom_etudiant") || document.getElementById("postnom_etudiant");
  txt_prenom_etudiant = container.querySelector("#prenom_etudiant") || document.getElementById("prenom_etudiant");
  txt_sexe_etudiant = container.querySelector("#sexe_etudiant") || document.getElementById("sexe_etudiant");
  txt_zone_recherche_etudiant = container.querySelector('#txt_recherch_etudiant') || document.getElementById('txt_recherch_etudiant');

  cmb_filiere = container.querySelector("#filiere") || document.getElementById("filiere");
  cmb_promotion = container.querySelector("#promo") || document.getElementById("promo");
  cmb_annee_academique = container.querySelector("#Id_an_acad") || document.getElementById("Id_an_acad");
  zone_etudiant = container.querySelector("#nom_etudiant") || document.getElementById("nom_etudiant");

  zone_sommeFA = container.querySelector("#sommeFA") || document.getElementById("sommeFA");
  zone_sommeEnrol_Mi_session = container.querySelector("#sommeEnrolement_mi_session") || document.getElementById("sommeEnrolement_mi_session");
  zone_sommeEnrol_Session = container.querySelector("#sommeEnrolement_Session") || document.getElementById("sommeEnrolement_Session");
  zone_sommeEnrol_Deuxime_Session = container.querySelector("#sommeEnrolement_Deuxieme_session") || document.getElementById("sommeEnrolement_Deuxieme_session");
  zone_sommeEnrol_Rattrapage_Sem_1 = container.querySelector("#sommeRattrapage_Sem_1") || document.getElementById("sommeRattrapage_Sem_1");
  zone_sommeEnrol_Validation_Credit = container.querySelector("#sommeValidation_Credit") || document.getElementById("sommeValidation_Credit");

 // Lorque le combo box de filiere  change
 // on test si l'élement existe vraiment sur la page html
 if(cmb_filiere!==null)
 {
  cmb_filiere.addEventListener('change',(event) => {
    var id_filiere=cmb_filiere.value;
    // Appel avec l'ID du combo box de promotion (par défaut "promo")
    Affichage_promotion(id_filiere, "promo");
  });

 }





// Lorsque le combo de promoton change
// on test si l'élement existe vraiment sur la page html
if(cmb_promotion!==null)
{
  cmb_promotion.addEventListener('change',(event)=> {
    var code_promo=cmb_promotion.value;
    var Id_annee=cmb_annee_academique.value;
    var tableau = document.getElementById("table_paiement");
    Affichage_etudiant(code_promo,Id_annee,tableau);
  
  
  });

}


// Lorsque le combo de des années academique a changé
// on test si l'élement existe vraiment sur la page html
if(cmb_annee_academique!==null)
{
  cmb_annee_academique.addEventListener('change',(event)=> {
    var code_promo=cmb_promotion.value;
    var Id_annee=cmb_annee_academique.value;
    var tableau = document.getElementById("table_paiement");
    Affichage_etudiant(code_promo,Id_annee,tableau)
  
  });

}


// Lorsque on est entrain de sair un text dans la zone de rehecher


// on test si l'élement existe vraiment sur la page html
if(txt_zone_recherche_etudiant!==null)
{
  txt_zone_recherche_etudiant.addEventListener("keyup", function(event) {
    var code_promo=cmb_promotion.value;
    var Id_annee=cmb_annee_academique.value;
    var tableau = document.getElementById("table_paiement");
    var txt_nom=txt_zone_recherche_etudiant.value;
    Affichage_etudiant_2(code_promo,Id_annee,txt_nom,tableau)
  });

}

}); // FIN DOMContentLoaded



 // LA FONCTION POUR RECUPERE LES MODALITES DE PAIEMENT POUR CHAQUE PROMOTION CHOISIE
function Affichage_modalite_paiemnt() {

  var code_promo=cmb_promotion.value;
  var Id_an_acad=cmb_annee_academique.value;
  var bloc_FA=document.getElementById("zone_affiche_tot_FA");
  var bloc_enrolement=document.getElementById("zone_affiche_tot_enrolement");
  var bloc_frais_tranche=document.getElementById("zone_affiche_tranche");

  var devise_fa=document.getElementById("devise_fa");
  var devise_tranche=document.getElementById("devise_tranche");
  var devise_enrol=document.getElementById("devise_eronl");
 
  //var bloc_autres_frais=document.getElementById("")

  // ici on initialise les zones de texte

  bloc_FA.textContent="";
  bloc_enrolement.textContent="";
  bloc_frais_tranche.textContent="";

  
  // Contacte de l'API PHP - Chemin absolu depuis la racine
  const url='API_PHP/modalite_paiement.php?code_promo='+code_promo+'&Id_annee_acad='+Id_an_acad;
        
  fetch(url) 
  .then(response => response.json())
  .then(data => {
    data.forEach(infos => 
    {
      /*
      frais.Montant,frais.Tranche,frais.Libelle_Frais
      */
      var devise=" Fc";
      if(infos.Devise==="Dollar") devise=" $";
      if(infos.Libelle_Frais=="Frais Académiques")
      {
        
        bloc_FA.textContent=infos.Montant;
        bloc_frais_tranche.textContent=infos.Tranche;
        
        
      }
      else if(infos.Libelle_Frais=="Enrôlement à la Session")
      {

        bloc_enrolement.textContent=infos.Montant;
      }
      devise_fa.innerText =devise;
      devise_tranche.innerText =devise;
      devise_enrol.innerText =devise;
      

    });
  })
  .catch(error => console.error('Erreur lors de la récupération des modalités:', error));

}
////////////////////////////////////////////////////////////////////////////////////////////




      /*
      * la méthode pour récupere la situation financière de l'étudiant passer
      * en parametre
      */
function Recuperation_situation_finaniere(mat_etudiant,Nom,Postnom,Prenom,Sexe,Id_an_acad,tr)
{



  var devise_fa=document.getElementById("devise_fa").innerHTML;
  var devise_enrol=document.getElementById("devise_eronl").innerHTML;
  
  //console.log("regarde devise FA"+(devise_fa.innerHTML).trim()+"et sa taille est "+(devise_fa.innerHTML).length);

  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tableau = document.getElementById("table_paiement");
  var rows = tableau.querySelectorAll('tbody tr');  
  rows.forEach(function(row) {
    row.classList.remove('selected');
  });
  tr.classList.add('selected');




  zone_etudiant.textContent = Nom + " - " + Postnom + " - " + Prenom;
  
  txt_mat_etudiant.value=mat_etudiant; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  txt_nom_etudiant.value=Nom; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  txt_postnom_etudiant.value=Postnom; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  txt_prenom_etudiant.value=Prenom; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  txt_sexe_etudiant.value=Sexe; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  

  
  // Initialisation de zonnes
  
  zone_sommeFA.textContent="";
  zone_sommeEnrol_Session.textContent="";
  zone_sommeEnrol_Mi_session.textContent="";
  zone_sommeEnrol_Deuxime_Session.textContent="";
  zone_sommeEnrol_Validation_Credit.textContent="";
  zone_sommeEnrol_Rattrapage_Sem_1.textContent="";
  


  // Cette partie lance contacte uniquement l'API pour le frais académique
  const xhr=new XMLHttpRequest();
  $type_frais="Frais Académiques";

  var url='API_PHP/Recup_situation_paie_etudiant.php'+
        '?matricule='+mat_etudiant
        +'&id_annee_acad='+Id_an_acad
        +'&type_frais='+$type_frais;
  xhr.open('GET',url,true);
  xhr.onload=function()
  {
    if(xhr.status===200)
    {
      // On recupere les infos de JSON
      var somm=JSON.parse(xhr.responseText);
      somm.forEach(element =>
      {
        var somme_FA=element.somme_paier;
        
        
        if(somme_FA!=null) zone_sommeFA.innerHTML =somme_FA+devise_fa ;
        else zone_sommeFA.innerHTML=" 0 ".devise_fa; 
        
      });
    }
  }
  xhr.send();
  /////////////////////////////////////





   // Cette partie lance contacte uniquement l'API pour le frais d' Enrôlement à la Mi-Session
   const xhr1=new XMLHttpRequest();
   $type_frais="Enrôlement à la Mi-Session";
   const url1='API_PHP/Recup_situation_paie_etudiant.php'+
         '?matricule='+mat_etudiant
         +'&id_annee_acad='+Id_an_acad
         +'&type_frais='+$type_frais;
   xhr1.open('GET',url1,true);
   xhr1.onload=function()
   {
     if(xhr1.status===200)
     {
       // On recupere les infos de JSON
      var somme=JSON.parse(xhr1.responseText);
       somme.forEach(element =>
       {
         var somme_Enrol_Mi_session=element.somme_paier;
         if(somme_Enrol_Mi_session!=null) 
         zone_sommeEnrol_Mi_session.innerHTML =somme_Enrol_Mi_session+devise_fa;
         else zone_sommeEnrol_Mi_session.innerHTML=" 0 "+devise_fa; 
       });
     }
   }
   xhr1.send();
   /////////////////////////////////////


   // Cette partie lance contacte uniquement l'API pour le frais d' Enrôlement à la Grande-Session
   const xhr2=new XMLHttpRequest();
   $type_frais="Enrôlement à la Grande-Session";
   const url2='API_PHP/Recup_situation_paie_etudiant.php'+
         '?matricule='+mat_etudiant
         +'&id_annee_acad='+Id_an_acad
         +'&type_frais='+$type_frais;
   xhr2.open('GET',url2,true);
   xhr2.onload=function()
   {
     if(xhr2.status===200)
     {
       // On recupere les infos de JSON
      var somme=JSON.parse(xhr2.responseText);
       somme.forEach(element =>
       {
         var somme_Enrol_Session=element.somme_paier;
         if(somme_Enrol_Session!=null) zone_sommeEnrol_Session.innerHTML =somme_Enrol_Session+devise_fa ;
         else zone_sommeEnrol_Session.innerHTML=" 0 "+devise_fa ; 
       });
     }
   }
   xhr2.send();
   /////////////////////////////////////


   // Cette partie lance contacte uniquement l'API pour le frais d' Enrôlement à la Grande-Session
   const xhr3=new XMLHttpRequest();
   var $type_frais="Enrôlement à la Deuxième-Session";
   const url3='API_PHP/Recup_situation_paie_etudiant.php'+
         '?matricule='+mat_etudiant
         +'&id_annee_acad='+Id_an_acad
         +'&type_frais='+$type_frais;
   xhr3.open('GET',url3,true);
   xhr3.onload=function()
   {
     if(xhr3.status===200)
     {
       // On recupere les infos de JSON
      var somme=JSON.parse(xhr3.responseText);
       somme.forEach(element =>
       {
         var somme_Enrol_2_Session=element.somme_paier;
         if(somme_Enrol_2_Session!=null) 
         zone_sommeEnrol_Deuxime_Session.innerHTML =somme_Enrol_2_Session+devise_fa ; 
         else zone_sommeEnrol_Deuxime_Session.innerHTML=" 0 "+devise_fa  ; 
       });
     }
   }
   xhr3.send();

   // Cette partie lance contacte uniquement l'API pour le frais d' Enrôlement pour la validation des crédits
   const xhr5=new XMLHttpRequest();
   var $type_frais="Enrôlement validation crédits";
   const url5='API_PHP/Recup_situation_paie_etudiant.php'+
         '?matricule='+mat_etudiant
         +'&id_annee_acad='+Id_an_acad
         +'&type_frais='+$type_frais;
   xhr5.open('GET',url5,true);
   xhr5.onload=function()
   {
     if(xhr5.status===200)
     {
       // On recupere les infos de JSON
      var somme=JSON.parse(xhr5.responseText);
       somme.forEach(element =>
       {
         var somme_Enrol_validatio_credit=element.somme_paier;
         if(somme_Enrol_validatio_credit!=null) 
         zone_sommeEnrol_Validation_Credit.innerHTML =somme_Enrol_validatio_credit+devise_fa ; 
         else zone_sommeEnrol_Validation_Credit.innerHTML=" 0 "+devise_fa  ; 
       });
     }
   }
   xhr5.send();

   /////////////////////////////////////
// Cette partie lance contacte uniquement l'API pour le frais d' Enrôlement pour la validation des crédits
   const xhr6=new XMLHttpRequest();
   var $type_frais="Enrôlement aux rattrapages sem 1";
   const url6='API_PHP/Recup_situation_paie_etudiant.php'+
         '?matricule='+mat_etudiant
         +'&id_annee_acad='+Id_an_acad
         +'&type_frais='+$type_frais;
   xhr6.open('GET',url6,true);
   xhr6.onload=function()
   {
     if(xhr5.status===200)
     {
       // On recupere les infos de JSON
      var somme=JSON.parse(xhr6.responseText);
       somme.forEach(element =>
       {
         var somme_Enrol_rattrap_sem_1=element.somme_paier;
         if(somme_Enrol_rattrap_sem_1!=null) 
         zone_sommeEnrol_Rattrapage_Sem_1.innerHTML =somme_Enrol_rattrap_sem_1+devise_fa ; 
         else zone_sommeEnrol_Rattrapage_Sem_1.innerHTML=" 0 "+devise_fa  ; 
       });
     }
   }
   xhr6.send();


   // Cette lance l'API pour recupere le rest à payer de FA
   var code_promo=cmb_promotion.value;
   const xhr4=new XMLHttpRequest();
   $type_frais="Enrôlement à la Deuxième-Session";
   const url4='API_PHP/Recup_reste_paie.php'+
         '?matricule='+mat_etudiant
         +'&id_annee_acad='+Id_an_acad
         +'&code_promo='+code_promo;
   xhr4.open('GET',url4,true);
   xhr4.onload=function()
   {
     if(xhr4.status===200)
     {
       // On recupere les infos de JSON
      var somme=JSON.parse(xhr4.responseText);
      //somme[0]
      //console.log(" regarde somme "+somme[1]);

      var text="( FA: "+somme[1]+devise_fa
        +" )  ( E.M.S./E-1-Sem : "+somme[2]+devise_fa
        +" )  ( E.G.S/E-2-Sem : "+somme[3]+devise_fa
        +" )  ( E.2.S/E-Ratt : "+somme[4]+devise_fa+" )";
      
      var div_reste_payer=document.getElementById("Reste_payer");        
      div_reste_payer.innerHTML=text; 

       };
     }
     xhr4.send();
   }
   
   /////////////////////////////////////