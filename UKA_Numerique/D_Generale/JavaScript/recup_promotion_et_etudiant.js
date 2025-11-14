

console.log(" Le script recup_promotion_et_etudiant s'est lancé")



/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DECLARATIONS DE COMPOSANT HTML  +++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

const txt_mat_etudiant=document.getElementById("mat_etudiant");
const txt_nom_etudiant=document.getElementById("nom_etudiant_1");
const txt_postnom_etudiant=document.getElementById("postnom_etudiant");
const txt_prenom_etudiant=document.getElementById("prenom_etudiant");
const txt_sexe_etudiant=document.getElementById("sexe_etudiant");
const txt_zone_recherche_etudiant=document.getElementById('txt_recherch_etudiant');


const cmb_filiere=document.getElementById("filiere");
const cmb_promotion=document.getElementById("promo");
const cmb_annee_academique=document.getElementById("Id_an_acad");
const zone_etudiant=document.getElementById("nom_etudiant");

const zone_sommeFA=document.getElementById("sommeFA");
const zone_sommeEnrol_Mi_session=document.getElementById("sommeEnrolement_mi_session");
const zone_sommeEnrol_Session=document.getElementById("sommeEnrolement_Session");
const zone_sommeEnrol_Deuxime_Session=document.getElementById("sommeEnrolement_Deuxieme_session");
const zone_sommeEnrol_Rattrapage_Sem_1=document.getElementById("sommeRattrapage_Sem_1");
const zone_sommeEnrol_Validation_Credit=document.getElementById("sommeValidation_Credit");


var tr_globale_ligne_select_etudiant=""; // Est une varibale qui doit contenir un objet de la ligne selection sur le tableau qui affiche les étudiants




/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ La partie d'ajout des évenements à chaque composant +++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

 // Lorque le combo box de filiere  change
 // on test si l'élement existe vraiment sur la page html
 if(cmb_filiere!==null)
 {
  cmb_filiere.addEventListener('change',(event) => {
    var id_filiere=cmb_filiere.value;
    Affichage_promotion(id_filiere);
  });

 }





// Lorsque le combo de promoton change
// on test si l'élement existe vraiment sur la page html
if(cmb_promotion!==null)
{
  cmb_promotion.addEventListener('change',(event)=> {
    var code_promo=cmb_promotion.value;
    var Id_annee=cmb_annee_academique.value;
    Affichage_etudiant(code_promo,Id_annee);
  
  
  });

}


// Lorsque le combo de des années academique a changé
// on test si l'élement existe vraiment sur la page html
if(cmb_annee_academique!==null)
{
  cmb_annee_academique.addEventListener('change',(event)=> {
    var code_promo=cmb_promotion.value;
    var Id_annee=cmb_annee_academique.value;
  
    Affichage_etudiant(code_promo,Id_annee)
  
  });

}


// Lorsque on est entrain de sair un text dans la zone de rehecher


// on test si l'élement existe vraiment sur la page html
if(txt_zone_recherche_etudiant!==null)
{
  txt_zone_recherche_etudiant.addEventListener("keyup", function(event) {
    var code_promo=cmb_promotion.value;
    var Id_annee=cmb_annee_academique.value;
    
    var txt_nom=txt_zone_recherche_etudiant.value;
    Affichage_etudiant_2(code_promo,Id_annee,txt_nom)
  });

}




/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DEFINITION DE FONCTIONS +++++++++++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

// ICI LA FONCTION POUR LA RECUPERATIONS DES PROMOTIONS EN FONCTION DE LA FILIERE CHOISIE
function Affichage_promotion(Idfiliere ) {

    // Réinitialiser le contenu de la balise select des promotions
    var cmb_promotion=document.getElementById("promo");
    cmb_promotion.innerHTML = "";
  
    
    // Contacte de l'API PHP
    const url='D_Generale/API_PHP/Recup_prom_filiere.php?idFiliere='+Idfiliere;
          
    fetch(url) 
    .then(response => response.json())
    .then(data => {
      data.forEach(infos => {
        

        const option = document.createElement("option");
        option.value = infos.cd_prom;
        option.textContent = infos.abv+" - "+infos.lib_mention;
    
        // Ajouter l'option à la balise select
        cmb_promotion.appendChild(option);

        //cmb_promotion.innerHTML += "<option style='width:100%;'value='"+infos.cd_prom+"'>"+infos.abv+" - "+infos.lib_mention+"</option>";
        //console.log("Code promo est "+infos.cd_prom+" la promo "+infos.abv+" - "+infos.lib_mention);
        
      });
    })
    .catch(error => console.error('Erreur lors de la récupération des promotions :', error));
  
  }
  ////////////////////////////////////////////////////////////////////////////////////////////




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

  
  // Contacte de l'API PHP
  const url='D_Perception/API_PHP/modalite_paiement.php?code_promo='+code_promo+'&Id_annee_acad='+Id_an_acad;
        
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





// LA FONCTION D'AFFIHAGE DES ETUDIANTS D'UNE PROMOTION DANS UNE ANNEE ACADEMIQUE CHOISIE
function Affichage_etudiant(code_promo,Id_an_acad)
{

    // ici on appele la méthode pour l'affichage des modalités
    Affichage_modalite_paiemnt();
    
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
    var url='D_Generale/API_PHP/liste_etudiant.php?Id_annee_acad='+Id_an_acad+'&code_promo='+code_promo;
        
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
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
             
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              tr_globale_ligne_select_etudiant=tr;
              
              
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la lign appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              //tr.style.backgroundColor = '';
              tr.addEventListener("click", function() {
                
                Recuperation_situation_finaniere(infos.Matricule,infos.Nom,infos.Postnom,
                infos.Prenom,infos.Sexe,Id_an_acad,tr_globale_ligne_select_etudiant);
                //tr.style.backgroundColor = 'red';
                
              });

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tableau.appendChild(tbody);
}



// LA FONCTION D'AFFIHAGE DES ETUDIANTS D'UNE PROMOTION DANS UNE ANNEE ACADEMIQUE CHOISIE
function Affichage_etudiant_2(code_promo,Id_an_acad,txt_nom)
{
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
    var url='D_Generale/API_PHP/liste_etudiant.php'
    +'?Id_annee_acad='+Id_an_acad
    +'&code_promo='+code_promo
    +'&Mot_recherche='+txt_nom;
        
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
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
             
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              
              
              tr_globale_ligne_select_etudiant=tr;
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la lign appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {

                Recuperation_situation_finaniere(infos.Matricule,infos.Nom,infos.Postnom,
                  infos.Prenom,infos.Sexe,Id_an_acad,tr_globale_ligne_select_etudiant);
                
              });

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tableau.appendChild(tbody);
}
//////////////////////////////////////////////////////////////////////////////////////








      /*
      * la méthode pour récupere la situation financière de l'étudiant passer
      * en parametre
      */
function Recuperation_situation_finaniere(mat_etudiant,Nom,Postnom,
        Prenom,Sexe,Id_an_acad,tr)
{



  var devise_fa=document.getElementById("devise_fa").innerHTML;
  var devise_enrol=document.getElementById("devise_eronl").innerHTML;
  
  //console.log("regarde devise FA"+(devise_fa.innerHTML).trim()+"et sa taille est "+(devise_fa.innerHTML).length);

  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tableau = document.getElementById("table_paiement");
  var rows = tableau.getElementsByTagName('tr');  
  for(var j = 0; j < rows.length; j++) 
  {
    if(j!=0) rows[j].style.backgroundColor = '';
  }
  //rows.style="background-color:red;"
  tr.style.backgroundColor = 'red';
  //tr.style="background-color:red;";




  zone_etudiant.textContent = Nom
        +" - "+Postnom+" - "+Prenom;
  
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

  var url='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
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
   const url1='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
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
   const url2='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
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
   const url3='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
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
   const url5='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
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
   const url6='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
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
   const url4='D_Perception/API_PHP/Recup_reste_paie.php'+
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