console.log(" je suis dans manip Jury")

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

var mat_agent="";
var tr_selectionner="";
// Les éléments du DOM sont initialisés seulement si la page contient
// l'élément parent `div_gen_Jury`. Cela évite que ce script lance des
// getElementById() au top-level et retourne `null` quand il est inclus
// sur d'autres pages.
let txt_login_user, txt_password, txt_password2, txt_zone_recherce_agent;
let cmb_etat_compte, cmb_fontion_compte_agent, cmb_promotion_juury;
let boite_alert_G_jury_UE, boite_Action_G_Jury;

//dboite_Action_G_Juryd
document.addEventListener("DOMContentLoaded",function(event)
  {
    const container = document.getElementById("div_gen_Jury");
    if (container !== null) {
      // initialiser les éléments en utilisant le conteneur pour éviter
      // toute sélection hors-contexte lorsque ce script est inclus sur
      // d'autres pages
      txt_login_user = container.querySelector('#txt_login_user') || document.getElementById('txt_login_user');
      txt_password = container.querySelector('#password_user') || document.getElementById('password_user');
      txt_password2 = container.querySelector('#retapez_password_user') || document.getElementById('retapez_password_user');
      txt_zone_recherce_agent = container.querySelector('#txt_recherch_user') || document.getElementById('txt_recherch_user');

      cmb_etat_compte = container.querySelector('#select_etat_compte') || document.getElementById('select_etat_compte');
      cmb_fontion_compte_agent = container.querySelector('#select_fonction_compte') || document.getElementById('select_fonction_compte');
      cmb_promotion_juury = container.querySelector('#promotion_jury') || document.getElementById('promotion_jury');

      // boîtes de dialogue peuvent être en dehors du conteneur
      boite_alert_G_jury_UE = document.getElementById('boite_alert_g_jury');
      boite_Action_G_Jury = document.getElementById('boite_confirmaion_action_SM_UE');

      Affichage_agent();

      if (cmb_fontion_compte_agent !== null) {
        cmb_fontion_compte_agent.addEventListener('change', (event) => {
          var fonction_compte = cmb_fontion_compte_agent.value;
          if (fonction_compte === "Président_jury" || fonction_compte === "Secrétaire_jury" || fonction_compte === "Membre_jury")
            Ouvrir_boite_dialog_promotion();
        });
      }

      if (txt_password !== null && txt_password2 !== null) {
        txt_password2.addEventListener("keyup", function(event) {
          if (Verification_password()) txt_password2.style.color = 'red';
          else txt_password2.style.color = 'white';
        });
      }

      // CE CODE NOUS PERMET DE FAIRE UNE RECHERCHE D'UN AGENT
      if (txt_zone_recherce_agent !== null) {
        txt_zone_recherce_agent.addEventListener("keyup", function(event) {
          if (txt_zone_recherce_agent.value === "") Affichage_agent();
          //else Affichage_agent2(txt_zone_recherce_agent.value)
        });
      }
    }



})
/*








if(cmb_fontion_compte_agent!==null)
{
    cmb_fontion_compte_agent.addEventListener('change',(event)=> {
    var fonction_compte=cmb_fontion_compte_agent.value;
    
    if(fonction_compte==="Doyen" 
    || fonction_compte==="Sécretaire Academique"
    || fonction_compte==="VD"
    || fonction_compte==="Sec_facultaire" )Ouvrir_boite_dialog_promotion();
      
  });
}






/**********************************************************************************************
******************* CE CODE PERMET D'AFFICHER TOUT LES AGENT DE L'UNIVERSITE DANS LE tab_agent 
***************************************************************************************/
function Affichage_agent()
{

   
    let tab_agent = document.getElementById("table_agent");

    while (tab_agent.firstChild) {
      tab_agent.removeChild(tab_agent.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
      

    td1.textContent = "N°";
    td2.textContent = "Matricule";
    td3.textContent = "Agent";
    td4.textContent = "Sexe";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);

      
    thead.appendChild(tr1);
    tab_agent.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    
    

    var url='API_PHP/Liste_Enseignants.php';
        
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
              var tdagent = document.createElement("td");
              var tdsexe = document.createElement("td");
              

              tdmatricule.textContent =infos.mat_agent;
              //mat_agent=infos.mat;

              tdagent.textContent=infos.enseignant
              tdsexe.textContent=infos.sexe;

             
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdagent);
              tr.appendChild(tdsexe);
              
              
              
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la ligne appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {
                var nom_agent=infos.identite;
                mat_agent=infos.mat_agent;
                tr_selectionner=tr;
                Recuperation_Compte_agent();
                
                
              });

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tab_agent.appendChild(tbody);
          tab_agent.classList.add("table-striped");
}
/*****************  FIN DE LA METHODE D'AFFICHAGE DES AGENTS *************************************/
/*
function Affichage_agent2(mot_recherche)
{

   
    let tab_agent = document.getElementById("table_agent");

    while (tab_agent.firstChild) {
      tab_agent.removeChild(tab_agent.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
      

    td1.textContent = "N°";
    td2.textContent = "Matricule";
    td3.textContent = "Agent";
    td4.textContent = "Sexe";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);

      
    thead.appendChild(tr1);
    tab_agent.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    
    

    var url='API_PHP/Liste_agent.php?mot_recherche='+mot_recherche;
        
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
              var tdagent = document.createElement("td");
              var tdsexe = document.createElement("td");
              

              tdmatricule.textContent =infos.mat;
              //mat_agent=infos.mat;

              tdagent.textContent=infos.identite
              tdsexe.textContent=infos.sexe;

             
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdagent);
              tr.appendChild(tdsexe);
              
              
              
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la ligne appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {
                var nom_agent=infos.identite;
                Recuperation_Compte_agent(infos.mat,tr);
                
              });

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tab_agent.appendChild(tbody);
          tab_agent.classList.add("table-striped");
}
/*****************  FIN DE LA METHODE D'AFFICHAGE DES AGENTS *************************************










/**********************************************************************************************
******************* CE CODE PERMET D'AFFICHER LE COMPTE D'UN AGENT SELECTIONNER *****************
***************************************************************************************/

function Recuperation_Compte_agent()
{
  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tab_agent = document.getElementById("table_agent");
  var rows = tab_agent.getElementsByTagName('tr');  
  for(var j = 0; j < rows.length; j++) 
  {
    if(j!=0) rows[j].style.backgroundColor = '';
  }
  tr_selectionner.style.backgroundColor = 'red';

  var tab_compte_agent = document.getElementById("table_compte_agent");

  while (tab_compte_agent .firstChild) {
    tab_compte_agent .removeChild(tab_compte_agent .firstChild);
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
  var td7 = document.createElement("td");
    

  td1.textContent = "N°";
  td2.textContent = "Login";
  td3.textContent = "Password";
  td4.textContent = "Fonction"; 
  td5.textContent = "Promotion";   
  td6.textContent = "Etat";  
  td7.textContent = "Action";

  tr1.appendChild(td1);
  tr1.appendChild(td2);
  tr1.appendChild(td3);
  tr1.appendChild(td4);
  tr1.appendChild(td5);
  tr1.appendChild(td6);
  tr1.appendChild(td7);
  

    
  thead.appendChild(tr1);
  tab_compte_agent.appendChild(thead);
    
  var tbody = document.createElement("tbody");
  var i = 1;

  var url = 'API_PHP/Liste_Membre_jury.php';
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        mat_agent: mat_agent,
    })
  })
  .then(response => response.json())
  .then(data => 
  {
      data.forEach(infos => {
        // Création de TR
          var tr = document.createElement("tr");
          tr.id="tr_"+i;
          
          var tdnum = document.createElement("td");
          tdnum.textContent = i;
          tdnum.classList.add("text-center");
  
          var tdlogin= document.createElement("td");
          var tdpassword= document.createElement("td");
          var tdfonction = document.createElement("td");
          var tdetat = document.createElement("td");
          var td_promotion = document.createElement("td");          
          var tdAction = document.createElement("td"); // La cellule qui contient nos deux btns d'actions
          
  
          tdlogin.textContent =infos.Login;
          tdpassword.textContent=infos.Mot_passe;
          tdfonction.textContent=infos.Categorie;
          tdetat.textContent=infos.Etat;
          td_promotion.textContent=infos.prom;
          

         
          // Ici on crée deux boutons pour l'impressionet la suppression
          // On commence par créer un contenaire qui vas accceuillir nos deux poubont

          var div = document.createElement("div");
          div.classList.add("row", "text-center", "p-0", "m-0");
          tdAction.appendChild(div);



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
              "\nVoulez-vous vraiment supprimer ce compte ?",mat_agent,infos.IdCompte_Agent,tr1);
           //Suppression_compte_agent(mat_agent,
             // infos.IdCompte_Agent,tr1);
          });

          var i2 = document.createElement("i");
          i2.classList.add("fas", "fa-trash-alt");
          btn_suppression.appendChild(i2);

          div2.appendChild(btn_suppression);

          tr.appendChild(tdnum);
          tr.appendChild(tdlogin);
          tr.appendChild(tdpassword);
          tr.appendChild(tdfonction);
          tr.appendChild(td_promotion);
          tr.appendChild(tdetat);     
          tr.appendChild(tdAction);
          
          tbody.appendChild(tr);
          i++;
        });
      
      }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de la selection des transactions "+error);});
          tab_compte_agent.appendChild(tbody);




}


/*************************************************************************************
********************    ICI C'EST POUR OUVRIR LA BOITE DE DIALOGUE ********************
***************************************************************************************/
const maBoiteDeDialogue = document.getElementById('maBoiteDeDialogue');
function Ouvrir_boite_dialog_promotion()
{
    maBoiteDeDialogue.showModal();
}
// Fermer la boîte de dialogue
function fermerBoiteDialogue() {
    maBoiteDeDialogue.close();
}


function Ouvrir_Boite_Alert_G_Jury(text_a_afficher)
{
    document.getElementById("text_alert_boite").innerText=text_a_afficher;
    boite_alert_G_jury_UE.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Boite_Alert_G_jury() {
  boite_alert_G_jury_UE.close();
}






function Ouvrir_Boite_Confirmation_Action_SM_UE(text_a_afficher,mat_agent,id_compte,tr)
{
  
  let btn_action_oui=document.getElementById("btn_action_oui");
  let btn_action_non=document.getElementById("btn_action_non");
  
  document.getElementById("text_confirm_afficher").innerText=text_a_afficher;
  boite_Action_G_Jury.showModal();

  btn_action_oui.addEventListener("click", function(event)
  {
      
      boite_Action_G_Jury.close();
      Suppression_compte_agent(mat_agent,id_compte,tr);

  });

  btn_action_non.addEventListener("click", function(event)
  {
      boite_Action_G_Jury.close();
      Ouvrir_Boite_Alert_G_Jury("Action annulée  !");  

  });

}
/******************************  FIN MANIPULATION DE LA BBOITE E DIALOGUE********************** */

/******************************  FIN MANIPULATION DE LA BBOITE E DIALOGUE********************** */

function Nouveau_Compte_agent()
{
 
  
  
    if(!Verification_password())
    {
      let data = {
        Mat_agent: mat_agent,
        Code_promotion: cmb_promotion_juury.value,
        Login: txt_login_user.value,
        Password: txt_password.value,
        Fonction: cmb_fontion_compte_agent.value,
        Etat:cmb_etat_compte.value
        };
    
        
    
        fetch('API_PHP/Ajout_Membre_jury.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
              Recuperation_Compte_agent();
              Ouvrir_Boite_Alert_G_Jury(result.message);

            } else {
              Ouvrir_Boite_Alert_G_Jury(result.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            Ouvrir_Boite_Alert_G_Jury('Erreur lors de l\'ajout du membre du jury.');
        });

       
    }
    else Ouvrir_Boite_Alert_G_Jury(" Mot de passe ne corresponde pas ");
            
    



}

// ICI la fonction pour réinitialiser toutes les zones de saisies

function Initialisation_zone_compte_agent()
{
    txt_login_user.value="";
    txt_password.value="1234";
    txt_password2.value="1234";

    cmb_etat_compte.selectedIndex=0;
    cmb_promotion_juury.selectedIndex=0;
    cmb_fontion_compte_agent.selectedIndex=0;
}

function Verification_password()
{
    if(txt_password.value!==txt_password2.value) return true;
    else return false;
}


/******************************************************************************************
 ********* CETTE FONCTION PERMET DE SUPPRIMER UN COMPTES QUI n'est pas utiliser ***********
 *****************************************************************************************/
function Suppression_compte_agent(mat_agent,
  id_compte_agent,tr1)
{
  const url = 'API_PHP/Suppression_compte_agent.php';

        // Création de l'objet XMLHttpRequest
        const xhr = new XMLHttpRequest();
        
        // Préparation de la requête
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        // Gestionnaire d'événement pour la réponse de la requête
        xhr.onload = function() {
          if (xhr.status === 200) 
          {
            Ouvrir_Boite_Alert_G_Jury("Suppression du compte réussie");
            Recuperation_Compte_agent(mat_agent,tr_selectionner);
          } 
          else Ouvrir_Boite_Alert_G_Jury("Impossible de supprimer ce compte !");
        };

        // Envoi de la requête avec les données nécessaires
        xhr.send("id_compte_agent="+id_compte_agent);    
    
    
  }



  