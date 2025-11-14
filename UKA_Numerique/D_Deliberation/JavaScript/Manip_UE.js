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

const txt_code_ue=document.getElementById('txt_code_ue');
const txt_libelle_ue=document.getElementById('txt_libelle_ue');
const cmb_categorie_ue=document.getElementById('categorie_ue');


const boite_form_UEs = document.getElementById('boite_Form_UE');
const boite_alert_SM_UE= document.getElementById('boite_alert_SM_UE');
const boite_confirmation_action_SM_UE= document.getElementById('boite_confirmaion_action_SM_UE');


// Ce code nous permet de mettre en rouge le texte saisi dans la zone de text de code ue si
// ce dernier est déjà utilisé 
if(txt_code_ue!==null)
{
    txt_code_ue.addEventListener("keyup", function(event)
    {
      Verification_code_ue(txt_code_ue.value);        
    });

}

/************************************************************************************
******************* CE CODE PERMET D'AFFICHER LES SEMESTRES **************************
***************************************************************************************/
document.addEventListener("DOMContentLoaded",function(event)
{
  if(document.getElementById("div_gen_UE")!==null) Affichage_semestre();
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
                Suppression_semestre();
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
                Recuperation_UEs(infos.id_semestre,tr);
                
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

function Recuperation_UEs(id_sm,tr1)
{
  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tab_semestre = document.getElementById("table_semestre");
  var rows = tab_semestre.getElementsByTagName('tr');  
  for(var j = 0; j < rows.length; j++) 
  {
    if(j!=0) rows[j].style.backgroundColor = '';
  }
  tr1.style.backgroundColor = 'red';
  tr_selectionner=tr1;
  
  id_semestre=id_sm;
  
  //txt_id_semestreent_1.value=id_semestreent; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  
  var tab_UEs = document.getElementById("table_ues");

  while (tab_UEs .firstChild) {
    tab_UEs .removeChild(tab_UEs .firstChild);
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
    

  td1.textContent = "N°";
  td2.textContent = "Code UE";
  td3.textContent = "Nom UE";
  td4.textContent = "Catégorie"; 
  td5.textContent = "Action"; 

  tr1.appendChild(td1);
  tr1.appendChild(td2);
  tr1.appendChild(td3);
  tr1.appendChild(td4);
  tr1.appendChild(td5);
  

    
  thead.appendChild(tr1);
  tab_UEs.appendChild(thead);
    
  var tbody = document.createElement("tbody");

  var url='API_PHP/Liste_UE_SM_Filiere_donnee.php'+
  '?id_semestre='+id_semestre;

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
            "\nVoulez-vous vraiment cette UE ?",id_semestre,infos.Code_ue,tr1);
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
          
          tbody.appendChild(tr);
          i++;
        });
      
      }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de la selection des transactions "+error);});
          tab_UEs.appendChild(tbody);




}
// *********** FIN AFFICHAGE DE UE **************************

/**********************************************************************************************
******************* Cette méthode permet d'ajouter une nouvelle unité d'enseignement *****************
************************************************************************************************/

function Ajout_UE()
{
  Verification_code_ue(txt_code_ue.value);
  if(verfi)
  {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "API_PHP/Ajout_UE.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState === 4 && xhr.status === 200)
            {
                console.log(xhr.responseText)
                // Réponse du serveur
                if(xhr.responseText!="Ok") 
                {
                    Recuperation_UEs(id_semestre,tr_selectionner)
                    //Initialisation_zone_compte_agent()
                    Ouvrir_Boite_Alert_SM_UE("UE ajoutée avec succè !");              
                }
                    
                else Ouvrir_Boite_Alert_SM_UE( " Echec d'eregistrement ");
            }
        };
        xhr.send("id_semestre=" + id_semestre
                + "&code_ue=" + txt_code_ue.value
                + "&libelle_ue=" + txt_libelle_ue.value
                + "&categorie_ue=" + cmb_categorie_ue.value);

  }
  else Ouvrir_Boite_Alert_SM_UE(" Le code UE saisi est déjà utilisé ou une zone est vide");

  Fermer_Form_UE();
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


function Ouvrir_Boite_Alert_SM_UE(text_a_afficher)
{
    document.getElementById("text_alert_boite").innerText=text_a_afficher;
    boite_alert_SM_UE.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Boite_Alert_SM_UE() {
  boite_alert_SM_UE.close();
}


function Ouvrir_Boite_Confirmation_Action_SM_UE(text_a_afficher,id_semestre,code_ue,tr1)
{
  
  btn_action_oui=document.getElementById("btn_action_oui");
  btn_action_non=document.getElementById("btn_action_non");
  
  document.getElementById("text_confirm_afficher").innerText=text_a_afficher;
  boite_confirmation_action_SM_UE.showModal();

  btn_action_oui.addEventListener("click", function(event)
  {
      
      boite_confirmation_action_SM_UE.close();
      Suppression_UE(id_semestre,code_ue,tr1);

  });

  btn_action_non.addEventListener("click", function(event)
  {
      boite_confirmation_action_SM_UE.close();
      Ouvrir_Boite_Alert_SM_UE("Action annulée  !");  

  });

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


/******************************************************************************************
 ********* CETTE FONCTION PERMET DE SUPPRIMER UNE UE *************************************
 *****************************************************************************************/
 function Suppression_UE(id_semestre,code_ue,tr1)
{

    const url = 'API_PHP/Suppression_UE.php';

    // Création de l'objet XMLHttpRequest
    const xhr = new XMLHttpRequest();
        
    // Préparation de la requête
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
    // Gestionnaire d'événement pour la réponse de la requête
    xhr.onload = function()
    {
      if (xhr.status === 200) 
      {
        Ouvrir_Boite_Alert_SM_UE("UE Supprimée avec succè !");  
        // Puis on fait l'initialisation du tableau
        Recuperation_UEs(id_semestre,tr_selectionner);
      } 
      
      else
      {
        // La suppression a échoué
        Ouvrir_Boite_Alert_SM_UE("Impossible de supprimer cette UE  !");  
      }
    };
    
    // Envoi de la requête avec les données nécessaires
    xhr.send("code_ue="+code_ue);
  } 
  


/*********************************FIN SUPPRESSION UE ******************************************* */




