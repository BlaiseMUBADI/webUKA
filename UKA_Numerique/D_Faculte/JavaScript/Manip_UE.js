console.log(" je suis dans Manip_UE_EC")

/*
*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*+++++++++++++++++++ C'est un script qui se charge de la manipulation des comptes agents+++++++++
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*
*/

/*
*********************************************************************************************
* ***************************** Déclaration des composants HTML *****************************
*********************************************************************************************
*/

var id_semestre=" ";
var tr_selectionner="";
var verfi=true;
var code_ue_ec="";

// Les éléments du DOM sont initialisés seulement si la page contient
// l'élément parent `div_gen_UE`. Cela évite que ce script lance des
// getElementById() au top-level et retourne `null` quand il est inclus
// sur d'autres pages.
let txt_code_ue;
let txt_libelle_ue;
let cmb_categorie_ue;
let txt_nom_ec;
let txt_nb_credit;
let txt_cmi;
let txt_hr_td;
let txt_hr_tp;
let txt_tpe;
let txt_vht;
let boite_form_UEs;
let boite_form_EC;
let boite_alert_SM_UE;
let boite_confirmation_action_SM_UE;

/************************************************************************************
******************* CE CODE PERMET D'AFFICHER LES SEMESTRES **************************
***************************************************************************************/
document.addEventListener("DOMContentLoaded",function(event)
{
  const container = document.getElementById("div_gen_UE");
  if(container !== null) {
    // initialiser les éléments en utilisant le conteneur pour éviter
    // toute sélection hors-contexte lorsque ce script est inclus sur
    // d'autres pages
    txt_code_ue = container.querySelector('#txt_code_ue') || document.getElementById('txt_code_ue');
    txt_libelle_ue = container.querySelector('#txt_libelle_ue') || document.getElementById('txt_libelle_ue');
    cmb_categorie_ue = container.querySelector('#categorie_ue') || document.getElementById('categorie_ue');
    txt_nom_ec = container.querySelector('#txt_nom_ec') || document.getElementById('txt_nom_ec');
    txt_nb_credit = container.querySelector('#txt_nb_credit') || document.getElementById('txt_nb_credit');
    txt_cmi = container.querySelector('#txt_cmi') || document.getElementById('txt_cmi');
    txt_hr_td = container.querySelector('#txt_hr_td') || document.getElementById('txt_hr_td');
    txt_hr_tp = container.querySelector('#txt_hr_tp') || document.getElementById('txt_hr_tp');
    txt_tpe = container.querySelector('#txt_tpe') || document.getElementById('txt_tpe');
    txt_vht = container.querySelector('#txt_vht') || document.getElementById('txt_vht');

    // boîtes de dialogue peuvent être en dehors du conteneur
    boite_form_UEs = document.getElementById('boite_Form_UE');
    boite_form_EC = document.getElementById('boite_Form_EC');
    boite_alert_SM_UE = document.getElementById('boite_alert_SM_UE');
    boite_confirmation_action_SM_UE = document.getElementById('boite_confirmaion_action_SM_UE');

    // Ce code nous permet de mettre en rouge le texte saisi dans la zone de text de code ue si
    // ce dernier est déjà utilisé 
    if(txt_code_ue !== null)
    {
        txt_code_ue.addEventListener("keyup", function(event)
        {
          Verification_code_ue(txt_code_ue.value);        
        });
    }

    Affichage_UEs();
  }
})



/**********************************************************************************************
******************* CE CODE PERMET D'AFFICHER TOUT LES AGENT DE L'UNIVERSITE DANS LE tab_semestre 
***************************************************************************************/
function Affichage_semestre()
{
   
    let tab_semestre = document.getElementById("table_semestre");

    while (tab_semestre.firstChild) {
      tab_semestre.removeChild(tab_semestre.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold", "text-center"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");      
    var td3 = document.createElement("td");      

    td1.textContent = "Semestres";
    td2.textContent = "Niveau";
    td3.textContent = "Action";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);

      
    thead.appendChild(tr1);
    tab_semestre.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    
    

    var url='API_PHP/Liste_semestre.php';
        
    var i=1;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
              var tr = document.createElement("tr");
              

              var td_semestre= document.createElement("td");
              var td_niveau = document.createElement("td");
              var td_Action = document.createElement("td");
              

              td_semestre.textContent =infos.lib_semestre;
              td_niveau.textContent=infos.niveau
              



               // Ici on crée deux boutons pour l'impressionet la suppression
              // On commence par créer un contenaire qui vas accceuillir nos deux poubont

              var div = document.createElement("div");
              div.classList.add("row", "text-center", "p-0", "m-0");
              td_Action.appendChild(div);
              // Créer le deuxième bouton de la modification
              var div1 = document.createElement("div");
              div1.classList.add("col","p-0", "m-0");
              div.appendChild(div1);

              var btn_modification = document.createElement("button");
              btn_modification.setAttribute("type", "button");
              btn_modification.classList.add("btn", "btn-primary");

              //Ajout de l'évenement au boutton de modification
              btn_modification.addEventListener("click", function(event) {              
                Modification_Semestre(infos.IdCompte_Agent,tr1);
              });

              var i1 = document.createElement("i");
              i1.classList.add("fas", "fa-pencil-alt");
              btn_modification.appendChild(i1);

              div1.appendChild(btn_modification);


              // Créer le deuxième bouton de la suppression
              var div2 = document.createElement("div");
              div2.classList.add("col","p-0", "m-0");
              div.appendChild(div2);

              var btn_suppression = document.createElement("button");
              btn_suppression.setAttribute("type", "button");
              btn_suppression.classList.add("btn", "btn-primary");

              //Ajout de l'évenement au boutton d'impression
              btn_suppression.addEventListener("click", function(event) {              
                //Suppression_semestre();
              });

              var i2 = document.createElement("i");
              i2.classList.add("fas", "fa-trash-alt");
              btn_suppression.appendChild(i2);
              div2.appendChild(btn_suppression);
             
              tr.appendChild(td_semestre);
              tr.appendChild(td_niveau);  
              tr.appendChild(td_Action);            
              
              
              tbody.appendChild(tr);

              // Ajout de l'évenement sur la ligne appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {
                var nom_agent=infos.identite;
                Affichage_UEs(infos.id_semestre,tr);
                
              });

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tab_semestre.appendChild(tbody);
          tab_semestre.classList.add("table-striped");
}
/*****************  FIN DE LA METHODE D'AFFICHAGE DES AGENTS *************************************/

/**********************************************************************************************
******************* CE CODE PERMET D'AFFICHER LE COMPTE D'UN AGENT SELECTIONNER *****************
***************************************************************************************/

function Affichage_UEs()
{
  // Récupérer le tableau et son tbody
  var tab_UEs = document.getElementById("table_ues");
  
  // NE PAS SUPPRIMER LE THEAD - Seulement vider le tbody
  let tbody = tab_UEs.querySelector("tbody");
  if (!tbody) {
      tbody = document.createElement("tbody");
      tab_UEs.appendChild(tbody);
  }
  
  // Vider uniquement le tbody
  tbody.innerHTML = "";

  var url='API_PHP/Liste_UE_Filiere.php'

    var i=1;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
          var tr = document.createElement("tr");
          tr.id="tr_"+i;
          
          var tdnum = document.createElement("td");
          tdnum.textContent = i;
          tdnum.classList.add("text-center");
  
          
          var td_code_ue= document.createElement("td");
          var td_nom_ue= document.createElement("td");
          var td_categorie= document.createElement("td");
          var td_Action = document.createElement("td"); // La cellule qui contient nos deux btns d'actions
          
  
          td_code_ue.textContent =infos.Code_ue;
          td_nom_ue.textContent=infos.nom_ue;
          td_categorie.textContent=infos.categorie_ue;
         
          // Ici on crée deux boutons pour l'impressionet la suppression
          // On commence par créer un contenaire qui vas accceuillir nos deux poubont

          var div = document.createElement("div");
          div.classList.add("row", "text-center", "p-0", "m-0");
          td_Action.appendChild(div);


          // Créer le deuxième bouton de la suppression
          var div2 = document.createElement("div");
          div2.classList.add("col","p-0", "m-0");
          div.appendChild(div2);

          var btn_suppression = document.createElement("button");
          btn_suppression.setAttribute("type", "button");
          btn_suppression.classList.add("btn", "btn-primary");

          //Ajout de l'évenement au boutton d'impression
          btn_suppression.addEventListener("click", function(event) {
            
            Ouvrir_Boite_Confirmation_Action_SM_UE("Attention !!! Cette opération est irreversible"+
            "\nVoulez-vous vraiment suprimer cette UE ?",infos.Code_ue,tr,"supp_ue");
           /*Suppression_UE(id_semestre,
            infos.Code_ue,tr1);*/
          });

          var i2 = document.createElement("i");
          i2.classList.add("fas", "fa-trash-alt");
          btn_suppression.appendChild(i2);

          div2.appendChild(btn_suppression);

          tr.appendChild(tdnum);
          tr.appendChild(td_code_ue);
          tr.appendChild(td_nom_ue);
          tr.appendChild(td_categorie);
          tr.appendChild(td_Action);

          tr.addEventListener("click", function() 
          {
            code_ue_ec=infos.Code_ue;
            Recuperation_ECs(infos.Code_ue,tr);
            
          });

          
          tbody.appendChild(tr);
          i++;
        });
      
      }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de la selection des transactions "+error);});
          // tbody déjà dans le DOM, pas besoin de l'ajouter
          tab_UEs.classList.add("table-striped");




}
// *********** FIN AFFICHAGE DE UE **************************

/*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES ECs D'UNE UE ********************
  *****************************************************************************************
  */
  function Recuperation_ECs(code_ue,tr1)
  {
    // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
    var table_ue_fac= document.getElementById("table_ues");
    var rows = table_ue_fac.querySelectorAll('tbody tr');  
    for(var j = 0; j < rows.length; j++) 
    {
      rows[j].classList.remove('selected');
    }
    // Ajouter la classe 'selected' à la ligne cliquée
    tr1.classList.add('selected');
    tr_selectionner=tr1;
    
    // Récupérer le tableau EC et son tbody
    var table_ecs_fac = document.getElementById("table_ecs");
    
    // NE PAS SUPPRIMER LE THEAD - Seulement vider le tbody
    let tbody = table_ecs_fac.querySelector("tbody");
    if (!tbody) {
        tbody = document.createElement("tbody");
        table_ecs_fac.appendChild(tbody);
    }
    
    // Vider uniquement le tbody
    tbody.innerHTML = "";
    var url='API_PHP/Liste_ECs_Filiere_donnee.php?code_ue='+code_ue;
    
    var i=1;
      fetch(url) 
      .then(response => response.json())
      .then(data => 
      {
        data.forEach(infos =>
          {
            // Création de TR
            var tr = document.createElement("tr");
            tr.id="tr_"+i;
            
            var tdnum = document.createElement("td");
            tdnum.textContent = i;
            tdnum.classList.add("text-center");
    
            var td_nom_ec= document.createElement("td");
            var td_cmi= document.createElement("td");
            var td_hr_td= document.createElement("td");
            var td_hr_tp= document.createElement("td");
            var td_tpe= document.createElement("td");
            var td_vht= document.createElement("td");
            var td_credit= document.createElement("td");
            var td_Action = document.createElement("td"); // La cellule qui contient nos deux btns d'actions
            
    
            td_nom_ec.textContent =infos.nom_ec;
            td_cmi.textContent=infos.cmi;
            td_hr_td.textContent=infos.hr_td;
            td_hr_tp.textContent=infos.hr_tp;
            td_tpe.textContent=infos.tpe;
            td_vht.textContent=infos.vht;
            td_credit.textContent=infos.credit;

          
            // Ici on crée deux boutons pour l'impressionet la suppression
            // On commence par créer un contenaire qui vas accceuillir nos deux poubont

            var div = document.createElement("div");
            div.classList.add("row", "text-center", "p-0", "m-0");
            td_Action.appendChild(div);


            // Créer le deuxième bouton de la suppression
            var div2 = document.createElement("div");
            div2.classList.add("col","p-0", "m-0");
            div.appendChild(div2);

            var btn_suppression = document.createElement("button");
            btn_suppression.setAttribute("type", "button");
            btn_suppression.classList.add("btn", "btn-primary");

            //Ajout de l'évenement au boutton Supresiion
            btn_suppression.addEventListener("click", function(event) {
              
              Ouvrir_Boite_Confirmation_Action_SM_UE("Attention !!! Cette opération est irreversible"+
              "\nVoulez-vous vraiment supprimer cet EC ?",infos.id_ec,tr1,"supp_ec");
              
            /*Suppression_UE(id_semestre_FAC,
              infos.Code_ue,tr1);*/
            });

            var i2 = document.createElement("i");
            i2.classList.add("fas", "fa-trash-alt");
            btn_suppression.appendChild(i2);

            div2.appendChild(btn_suppression);

            tr.appendChild(tdnum);
            tr.appendChild(td_nom_ec);
            tr.appendChild(td_cmi);
            tr.appendChild(td_hr_td);
            tr.appendChild(td_hr_tp);
            tr.appendChild(td_tpe);
            tr.appendChild(td_vht);
            tr.appendChild(td_credit);
            tr.appendChild(td_Action);

            tbody.appendChild(tr);
            i++;
          });
        
        }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de la selection des transactions "+error);});
            // tbody déjà dans le DOM, pas besoin de l'ajouter
            table_ecs_fac.classList.add("table-striped");




  }



// *********** FIN AFFICHAGE DE ECS **************************















/**********************************************************************************************
******************* Cette méthode permet d'ajouter une nouvelle unité d'enseignement *****************
************************************************************************************************/

function Ajout_UE() {
  Verification_code_ue(txt_code_ue.value);
  if (verfi) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "API_PHP/Ajout_UE.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
              var response = JSON.parse(xhr.responseText);
              // Tester la valeur envoyée par l'API PHP
              if (response.message === "UE ajoutée avec succès !") {
                  Affichage_UEs(id_semestre, tr_selectionner);
                  Ouvrir_Boite_Alert_G_UE(response.message);
              } else {
                  Ouvrir_Boite_Alert_G_UE(response.message);
              }
          }
      };
      xhr.send("code_ue=" + encodeURIComponent(txt_code_ue.value) +
               "&libelle_ue=" + encodeURIComponent(txt_libelle_ue.value) +
               "&categorie_ue=" + encodeURIComponent(cmb_categorie_ue.value));
  } else {
      Ouvrir_Boite_Alert_G_UE("Le code UE saisi est déjà utilisé ou une zone est vide");
  }

  Fermer_Form_UE();
}
// *************  FIN DE LA METHODE AJOUT  ************************

/*
  *****  LA METHODE POUR AJOUTER LES ECS
  */


  function Ajout_EC()
  {
    
    if(verification_info_EC())
    {

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "API_PHP/Ajout_EC.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
              var response = JSON.parse(xhr.responseText);              
              // Tester la valeur envoyée par l'API PHP
              if (response.message === "Élément constitutif ajouté avec succès !") {
                  Recuperation_ECs(code_ue_ec,tr_selectionner)
                  Ouvrir_Boite_Alert_G_UE(response.message);
              } else {
                  Ouvrir_Boite_Alert_G_UE(response.message);
              }
          }
      };
      xhr.send("code_ue=" + encodeURIComponent(code_ue_ec)
        + "&nom_ec=" + encodeURIComponent(txt_nom_ec.value)
        + "&credit=" + encodeURIComponent(txt_nb_credit.value)
        + "&hr_td=" + encodeURIComponent(txt_hr_td.value)
        + "&hr_tp=" + encodeURIComponent(txt_hr_tp.value)
        + "&CMI=" + encodeURIComponent(txt_cmi.value)
        + "&VHT=" + encodeURIComponent(txt_vht.value)
        + "&TPE=" + encodeURIComponent(txt_tpe.value)
      );

    } 
    else 
    {
        Ouvrir_Boite_Alert_G_UE("Le code UE saisi est déjà utilisé ou une zone est vide");
    }
    Fermer_Form_EC();
  }
  // *************  FIN DE LA METHODE AJOUT  ************************




/*************************************************************************************
********************    ICI C'EST POUR OUVRIR LA BOITE DE DIALOGUE ********************
***************************************************************************************/

function Ouvrir_Form_UEs()
{
    boite_form_UEs.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Form_UE() {
    boite_form_UEs.close();
}

function Ouvrir_Form_EC()
{
    boite_form_EC.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Form_EC() {
    boite_form_EC.close();
}


function Ouvrir_Boite_Alert_G_UE(text_a_afficher)
{
    document.getElementById("text_alert_boite").innerText=text_a_afficher;
    boite_alert_SM_UE.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Boite_Alert_SM_UE() {
  boite_alert_SM_UE.close();
}


function Ouvrir_Boite_Confirmation_Action_SM_UE(text_a_afficher,id_element_a_supprimer,tr,sourcee)
{
  
  
  btn_action_oui=document.getElementById("btn_action_oui");
  btn_action_non=document.getElementById("btn_action_non");
  
  if(sourcee==="supp_ue")
  {
    document.getElementById("text_confirm_afficher").innerText=text_a_afficher;
    boite_confirmation_action_SM_UE.showModal();

    btn_action_oui.addEventListener("click", function(event)
    {
        
        boite_confirmation_action_SM_UE.close();
        Suppression_UE(id_element_a_supprimer);

    });

    btn_action_non.addEventListener("click", function(event)
    {
        boite_confirmation_action_SM_UE.close();
        Ouvrir_Boite_Alert_G_UE("Action annulée  !");  

    });

  }
  
  else if(sourcee === "supp_ec")
  {
    document.getElementById("text_confirm_afficher").innerText=text_a_afficher;
    boite_confirmation_action_SM_UE.showModal();

    btn_action_oui.addEventListener("click", function(event)
    {
        
        boite_confirmation_action_SM_UE.close();
        Suppression_EC(id_element_a_supprimer);

    });

    btn_action_non.addEventListener("click", function(event)
    {
        boite_confirmation_action_SM_UE.close();
        Ouvrir_Boite_Alert_G_UE("Action annulée  !");  

    });

  }

}
/******************************  FIN MANIPULATION DE LA BBOITE E DIALOGUE********************** */






/********************     LA METHODE POUR VERIFIER SI LE CODE UE N'EST PAS ENCORE ATTRIBUER  */

function Verification_code_ue(code_ue)
{
 
 // console.log(" voici le contenu code "+txt_code_ue.value===null +" et libelle " + txt_libelle_ue.value.length)
    // Contacte de l'API PHP
    const url='API_PHP/Verification_Code_UE.php?code_ue='+code_ue;
          
    fetch(url) 
    .then(response => response.json())
    .then(
      data => {
      data.forEach(infos => {
        
        
        var nb=infos.nb_ue;

        if (nb>0 || txt_code_ue.value.length==0 ||  txt_libelle_ue.value.length==0)
        {
            txt_code_ue.style.color = 'red';
            verfi=false;
        } 
        else
        {
          txt_code_ue.style.color = 'white';
            verfi=true;
        }
        
      });
    }
  
  )
    .catch(error => console.error('Erreur lors de la verification de code UE:', error));
}


 /*
  * LA METHODE POUR VERIFIER LES INFOS SAISIES SUR LE FORMULAIRE
  */

 function verification_info_EC()
 {
   if(txt_nom_ec.value===""
     || txt_nb_credit.value===""
     || txt_hr_td.value===""
     || txt_hr_tp.value===""
     || code_ue_ec===""
   )
   return false;
   else return true;
 }


/******************************************************************************************
 ********* CETTE FONCTION PERMET DE SUPPRIMER UNE UE *************************************
 *****************************************************************************************/
 function Suppression_UE(code_ue)
 {
  const url = 'API_PHP/Suppression_UE.php';
  const xhr = new XMLHttpRequest();
  
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  
  xhr.onload = function() {
      if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
              Ouvrir_Boite_Alert_G_UE("UE supprimée avec succès !");
              Affichage_UEs();
          } else {
              Ouvrir_Boite_Alert_G_UE("Impossible de supprimer cette UE: " + response.message);
          }
      } else {
          Ouvrir_Boite_Alert_G_UE("Erreur de communication avec le serveur.");
      }
  };
  
  xhr.onerror = function() {
      Ouvrir_Boite_Alert_G_UE("Erreur de communication avec le serveur.");
  };
  
  xhr.send("code_ue=" + code_ue);
}
  


/*********************************FIN SUPPRESSION UE ******************************************* */

/******************************************************************************************
 ********* CETTE FONCTION PERMET DE SUPPRIMER UNE EC *************************************
 *****************************************************************************************/
 function Suppression_EC(code_EC) 
 {
  
  const url = 'API_PHP/Suppression_EC.php'; 
  const xhr = new XMLHttpRequest();         
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');         
  
  xhr.onload = function() {
      if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
              Ouvrir_Boite_Alert_G_UE("EC supprimée avec succès !");
              Recuperation_ECs(code_ue_ec, tr_selectionner);
          } else {
              Ouvrir_Boite_Alert_G_UE("Impossible de supprimer cette EC: " + response.message);
          }
      } else {
          Ouvrir_Boite_Alert_G_UE("Erreur de communication avec le serveur.");
      }
  };
  
  xhr.onerror = function() {
      Ouvrir_Boite_Alert_G_UE("Erreur de communication avec le serveur.");
  };
  
  xhr.send("code_ec=" + code_EC);
}
   
 
 
 /*********************************FIN SUPPRESSION UE ******************************************* */
 



