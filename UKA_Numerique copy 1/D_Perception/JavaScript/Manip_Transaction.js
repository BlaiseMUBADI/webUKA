console.log(" Je suis dans JS Transact");
/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DECLARATIONS DE COMPOSANT HTML  +++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/


// DOM nodes: initialize on DOMContentLoaded to avoid errors when script loaded on other pages
let cmb_filiere_1;
let cmb_promoion_1;
let cmb_annee_acade_1;
let date_paiement_1;

let txt_mat_etudiant_1;
let txt_zone_recherche_etudiant_1;

document.addEventListener('DOMContentLoaded', function(event) {
  const container = document.getElementById('div_gen_Transact') || document;
  cmb_filiere_1 = container.querySelector('#filiere_transact') || document.getElementById('filiere_transact');
  cmb_promoion_1 = container.querySelector('#promo_tansact') || document.getElementById('promo_tansact');
  cmb_annee_acade_1 = container.querySelector('#Id_an_acad_1') || document.getElementById('Id_an_acad_1');
  date_paiement_1 = container.querySelector('#date_paiement_1') || document.getElementById('date_paiement_1');

  txt_mat_etudiant_1 = container.querySelector('#mat_etudiant_transact') || document.getElementById('mat_etudiant_transact');
  txt_zone_recherche_etudiant_1 = container.querySelector('#txt_recherch_etudiant_1') || document.getElementById('txt_recherch_etudiant_1');

  // Attachements d'événements (seulement si présents)
  if(cmb_filiere_1!==null)
  {
    cmb_filiere_1.addEventListener('change', (event) => {
      var id_filiere=cmb_filiere_1.value;
      // Utilisation de la fonction globale avec l'ID spécifique
      Affichage_promotion(id_filiere, "promo_tansact");
    });
  }

  if(cmb_promoion_1!==null)
  {
    cmb_promoion_1.addEventListener('change',(event)=> {
      var code_promo=cmb_promoion_1.value;
      var Id_annee=cmb_annee_acade_1.value;
      var date_paiement=date_paiement_1.value;
      Affichage_etudiant_Transact(code_promo,Id_annee,date_paiement);
    });
  }

  if(cmb_annee_acade_1!==null)
  {
    cmb_annee_acade_1.addEventListener('change',(event)=> {
      var code_promo=cmb_promoion_1.value;
      var Id_annee=cmb_annee_acade_1.value;
      var date_paiement=date_paiement_1.value;
      Affichage_etudiant_Transact(code_promo,Id_annee,date_paiement);
    });
  }

  if(date_paiement_1!==null)
  {
    date_paiement_1.addEventListener('change',(event)=> {
      var code_promo=cmb_promoion_1.value;
      var Id_annee=cmb_annee_acade_1.value;
      var date_paiement=date_paiement_1.value;
      Affichage_etudiant_Transact(code_promo,Id_annee,date_paiement);
    });
  }

  if(txt_zone_recherche_etudiant_1!==null)
  {
    txt_zone_recherche_etudiant_1.addEventListener("keyup", function(event) {
      var code_promo=cmb_promoion_1.value;
      var Id_annee=cmb_annee_acade_1.value;    
      var txt_nom=txt_zone_recherche_etudiant_1.value;
      var date_paiement=date_paiement_1.value;
      Affichage_etudiant_Transact_2(code_promo,Id_annee,date_paiement,txt_nom)
    });
  }

}); // FIN DOMContentLoaded






/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DEFINITION DE FONCTIONS +++++++++++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

// FONCTION SUPPRIMÉE - On utilise Affichage_promotion() du fichier D_Generale/JavaScript/recup_promotion_et_etudiant.js
// Appeler avec: Affichage_promotion(idFiliere, "promo_1")
  ////////////////////////////////////////////////////////////////////////////////////////////





// LA FONCTION D'AFFIHAGE DES ETUDIANTS D'UNE PROMOTION DANS UNE ANNEE ACADEMIQUE CHOISIE
function Affichage_etudiant_Transact(code_promo,Id_an_acad,date_paiement)
{
    let tab_etudiant = document.getElementById("table_etudiant1");
    let tab_transact = document.getElementById("table_transact");

    // Préserver le thead, ne modifier que tbody
    let tbody = tab_etudiant.querySelector("tbody");
    if (!tbody) {
        tbody = document.createElement("tbody");
        tab_etudiant.appendChild(tbody);
    }
    tbody.innerHTML = "";
    
    // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
    var date_actuelle = new Date();
    // Obtenir la date au format YYYY-MM-DD
    var formattedDate = date_actuelle.toISOString().substr(0, 10);
    var verifi_date_transact=false;

    if(date_paiement!==formattedDate)
      verifi_date_transact=true;
    else verifi_date_transact=false;

    var url='API_PHP/liste_etudiant_transact.php?Id_annee_acad='+Id_an_acad+'&code_promo='+code_promo+'&verifi_date='+verifi_date_transact;
        
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
              
              
              
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la lign appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {
                var nom_etudiant=infos.Nom+" "+infos.Postnom+" "+infos.Prenom;
                Recuperation_Transactions(infos.Matricule,Id_an_acad,code_promo,nom_etudiant,tr);
                
              });
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          // tbody déjà dans le DOM, pas besoin de l'ajouter
          tab_etudiant.classList.add("table-striped");
}



// LA FONCTION D'AFFIHAGE DES ETUDIANTS D'UNE PROMOTION DANS UNE ANNEE ACADEMIQUE CHOISIE
// AVEC RECHERCHE PAR NOM
function Affichage_etudiant_Transact_2(code_promo,Id_an_acad,date_paiement,txt_nom)
{
    let tab_etudiant = document.getElementById("table_etudiant1");
    let tab_transact = document.getElementById("table_transact");

    // Préserver le thead, ne modifier que tbody
    let tbody = tab_etudiant.querySelector("tbody");
    if (!tbody) {
        tbody = document.createElement("tbody");
        tab_etudiant.appendChild(tbody);
    }
    tbody.innerHTML = "";
    
    // Vérification de la date
    var date_actuelle = new Date();
    var formattedDate = date_actuelle.toISOString().substr(0, 10);
    var verifi_date_transact = false;

    if(date_paiement !== formattedDate)
      verifi_date_transact = true;
    else 
      verifi_date_transact = false;

    // Contacter l'API avec le paramètre de recherche
    var url = 'API_PHP/liste_etudiant_transact.php?Id_annee_acad=' + Id_an_acad + 
              '&code_promo=' + code_promo + 
              '&verifi_date=' + verifi_date_transact + 
              '&Mot_recherche=' + encodeURIComponent(txt_nom);
        
    var i = 1;
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

          var tdmatricule = document.createElement("td");
          var tdnom = document.createElement("td");
          var tdpostnom = document.createElement("td");
          var tdprnom = document.createElement("td");
          var tdsexe = document.createElement("td");

          tdmatricule.textContent = infos.Matricule;
          tdnom.textContent = infos.Nom;
          tdpostnom.textContent = infos.Postnom;
          tdprnom.textContent = infos.Prenom;
          tdsexe.textContent = infos.Sexe;
         
          tr.appendChild(tdnum);
          tr.appendChild(tdmatricule);
          tr.appendChild(tdnom);
          tr.appendChild(tdpostnom);
          tr.appendChild(tdprnom);
          tr.appendChild(tdsexe);
          
          tbody.appendChild(tr);
          i++;

          // Ajout de l'événement de clic pour afficher les transactions
          tr.addEventListener("click", function() {
            var nom_etudiant = infos.Nom + " " + infos.Postnom + " " + infos.Prenom;
            Recuperation_Transactions(infos.Matricule, Id_an_acad, code_promo, nom_etudiant, tr);
          });
        });
          
    }).catch(error => {
      console.log("Erreur lors de la recherche des étudiants " + error);
    });
    
    // tbody déjà dans le DOM, pas besoin de l'ajouter
    tab_etudiant.classList.add("table-striped");
}
//////////////////////////////////////////////////////////////////////////////////////








/*

* la méthode pour récupere la situation financière donc toutes 
* les transaction effectuées d'un etudiant âsser  en parametre
*/
function Recuperation_Transactions(mat_etudiant,Id_an_acad,code_promo,nom_etudiant,tr1)
{
  // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  var tableau = document.getElementById("table_etudiant1");
  var rows = tableau.querySelectorAll('tbody tr');  
  rows.forEach(row => row.classList.remove('selected'));
  tr1.classList.add('selected');


  txt_mat_etudiant_1.value=mat_etudiant; // Ici on met dans la zone cachée hidden pour s'en servir ulterieuement
  var tab_etudiant_transact = document.getElementById("table_transact");

  // Préserver le thead, ne modifier que tbody
  let tbody = tab_etudiant_transact.querySelector("tbody");
  if (!tbody) {
      tbody = document.createElement("tbody");
      tab_etudiant_transact.appendChild(tbody);
  }
  tbody.innerHTML = "";

  var url='API_PHP/Recup_Transact_etudiant.php'+
  '?matricule='+mat_etudiant
  +'&id_annee_acad='+Id_an_acad
  +'&code_promo='+code_promo;

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

          /*payer_frais.Date_paie as date_paie,
          GROUP_CONCAT(payer_frais.Motif_paie) as motif, 
          SUM(payer_frais.Montant_paie) as montant_paie,
          lieu_paiement.Libelle_lieu as lieu_paiement,
          CONCAT(agent.Nom_agent,' ',agent.Post_agent) as nom_agent*/
  
          var tdnum = document.createElement("td");
          tdnum.textContent = i;
          tdnum.classList.add("text-center");
  
          var td_date_paie= document.createElement("td");
          var td_motif= document.createElement("td");
          var td_montant_paie = document.createElement("td");
          var lieu_paie = document.createElement("td");
          
          var td_Actions = document.createElement("td"); // La cellule qui contient nos deux btns d'actions
          //var nom_agent = document.createElement("td");
          
  
          td_date_paie.textContent =infos.date_paie;
          td_motif.textContent=infos.motif;
          td_montant_paie.textContent=infos.montant_paie;
          lieu_paie.textContent=infos.Libelle_lieu;
         
          // Ici on crée deux boutons pour l'impressionet la suppression
          // On commence par créer un contenaire qui vas accceuillir nos deux poubont

          var div = document.createElement("div");
          div.classList.add("row", "text-center", "p-0", "m-0");
          td_Actions.appendChild(div);

          // Créer le premier bouton (d'impression)
          var div1 = document.createElement("div");
          div1.classList.add("col", "p-0", "m-0","mb-2");
          div.appendChild(div1);

          var btns_impression = document.createElement("button");
          btns_impression.setAttribute("type", "button");
          btns_impression.classList.add("btn", "btn-primary");

          //Ajout de l'évenement au boutton d'impression
          btns_impression.addEventListener("click", function(event) {
            Ree_Impression_Recu(
              mat_etudiant
              ,nom_etudiant
              ,Id_an_acad
              ,code_promo
              ,infos.date_paie
              ,infos.montant_paie
              ,infos.motif
              ,infos.Id_lieu
              ,infos.nom_agent
              ,infos.devise
              ,infos.Mat_agent,
              infos.numero_recu);
          });

          var i1 = document.createElement("i");
          i1.classList.add("fas", "fa-print");
          btns_impression.appendChild(i1);

          div1.appendChild(btns_impression);


          // Créer le deuxième bouton de la suppression
          var div2 = document.createElement("div");
          div2.classList.add("col","p-0", "m-0");
          div.appendChild(div2);

          var btn_suppression = document.createElement("button");
          btn_suppression.setAttribute("type", "button");
          btn_suppression.classList.add("btn", "btn-primary");

          //Ajout de l'évenement au boutton d'impression
          btn_suppression.addEventListener("click", function(event) {
          
           Suppression_Transaction(mat_etudiant,
              code_promo,
              infos.date_paie,
              Id_an_acad,nom_etudiant,tr1);
          });

          var i2 = document.createElement("i");
          i2.classList.add("fas", "fa-trash-alt");
          btn_suppression.appendChild(i2);

          div2.appendChild(btn_suppression);

          tr.appendChild(tdnum);
          tr.appendChild(td_date_paie);
          tr.appendChild(td_motif);
          tr.appendChild(td_montant_paie);
          tr.appendChild(lieu_paie);          
          tr.appendChild(td_Actions);
          
          tbody.appendChild(tr);
          i++;
        });
      
      }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de la selection des transactions "+error);});
          // tbody déjà dans le DOM, pas besoin de l'ajouter




}

/******************************************************************************************
 * ******** CETTE FONCTION NOUS PERMET DE FAIRE UNE REINITIALISATION DANS YTRANSACTIONS **********************
 **************************************************************************************** */
function Initialisation(mat_etudiant,code_promo,Id_an_acad,Nom_etudiant,tr1)
{
  Recuperation_Transactions(mat_etudiant,Id_an_acad,code_promo,Nom_etudiant,tr1);
   
}


/******************************************************************************************
 * ******** CETT FONCTION NOUS PERMET DE FAIRE LA REIMPRESSION DE RECU **********************
 **************************************************************************************** */
function Ree_Impression_Recu(
  mat_etudiant,
  nom_etudiant,  
  Id_an_acad,
  code_promo,
  date_paie,
  montant_paie,
  motif,
  id_lieu,
  nom_agent,devise,mat_agent,numero_recu)
{
  
  devi="Fc";

  if(devise==="Dollar") devi="$";

  /*var url="Impression/Docs_a_imprimer/recu_ree_imprimer.php"
                    +"?Mat_etudiant="+mat_etudiant
                    +"&Nom_etudiant="+nom_etudiant
                    +"&Montant_payer="+montant_paie
                    +"&Date_paiement="+date_paie                
                    +"&Code_promo="+code_promo            
                    +"&motif_paiement="+motif
                    +"&Id_banque="+id_lieu
                    +"&Id_an_acad="+Id_an_acad
                    +"&nom_agent="+nom_agent
                    +"&devise="+devi;

  let parametres = "left=20,top=20,width=700,height=500"; // Les dimensions de la fenetres d'impression et la position                
  let fenetre_recu=window.open(url,"Impression Réçu",parametres);
  
  fenetre_recu.onload = function() {
    //alert("Enregistrment effectuer avec succès");
    //Intialisation_zone_paiement_banque();
  };*/
   
  var url = "../Impression/Docs_a_imprimer/recu_ree_imprimer.php" +
            "?Mat_etudiant=" + encodeURIComponent(mat_etudiant) +
            "&Nom_etudiant=" + encodeURIComponent(nom_etudiant) +
            "&Montant_payer="+encodeURIComponent(montant_paie) +
            "&Date_paiement=" + encodeURIComponent(date_paie) +
            "&Code_promo=" + encodeURIComponent(code_promo) +
            "&motif_paiement="+encodeURIComponent(motif) +
            "&Id_banque=" +encodeURIComponent(id_lieu)+
            "&Id_an_acad=" + encodeURIComponent(Id_an_acad) +
            "&nom_agent="+encodeURIComponent(nom_agent)+
            "&devise=" + encodeURIComponent(devi)+
            "&Mat_agent=" + encodeURIComponent(mat_agent)+
            "&numero_recu="+encodeURIComponent(numero_recu);
            
  // Paramètres de la fenêtre
  let parametres = "left=20,top=20,width=700,height=500";
  // Ouvrez la nouvelle fenêtre
  let fenetre_recu = window.open(url, "Impression Réçu", parametres);
  // Utilisez postMessage au lieu de onload
  window.addEventListener('message', function(event) 
  {
    // Vérifiez que l'événement provient de la fenêtre ouverte
    if (event.source === fenetre_recu) 
    {
      // Vérifiez que l'origine est celle attendue
      if (event.origin !== 'http://localhost') 
      {
        return;
      }
      // Traitez le message reçu
      console.log('Message reçu : ', event.data);
      // Vous pouvez appeler ici la fonction d'initialisation
      //Intialisation_zone_paiement_guichet();
    }
  }, false);
}


/***************************************************************************************** */



/******************************************************************************************
 * ******** CETT FONCTION NOUS PERMET DE FAIRE LA SUPPRESSION D'UNE TRANSACTION*************
 **************************************************************************************** */
function Suppression_Transaction(mat_etudiant,
  code_promo,
  date_paie,
  Id_an_acad,Nom_etudiant,tr1)
{


  // ici verification de date pour savoir si deux heures sont passées 

  const date_base = new Date(date_paie);

  // Créez un objet Date pour l'instant actuel
  const now = new Date();
  
  // Calculez la différence en millisecondes entre les deux dates
  const timeDifference = now.getTime() - date_base.getTime();
  
  // Convertissez la différence en heures
  //const hoursPassed = timeDifference / (1000 * 60 * 60); // Pour deux heures
  const hoursPassed = timeDifference / (1000 * 60 * 60); // Pour deux heures

  
  console.log("Reagarde  "+date_base+" base paie  "+date_paie+" maintenant "+now+ " regarde les secondes "+timeDifference);
  
  // Vérifiez si plus de deux heures se sont écoulées
  if (hoursPassed <= 2) 
  {
    
    
    console.log( " Je suis dans la méthode de la suppression de la transaction");
    var confirmation = confirm("Attention !!! Cette opération est irreversible"+
    "\nVoulez-vous vraiment Supprimer la Transaction de \n "+Nom_etudiant+" ?");
    
    /*
    if (confirmation)
    {
        // URL de l'API PHP
        const url = 'D_Perception/API_PHP/Suppresion_Transact.php';

        // Création de l'objet XMLHttpRequest
        const xhr = new XMLHttpRequest();
        
        // Préparation de la requête
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        // Gestionnaire d'événement pour la réponse de la requête
        xhr.onload = function() {
          if (xhr.status === 200) {
            // La suppression a réussi
            console.log('Enregistrement supprimé avec succès');
            // Puis on fait l'initialisation du tableau
            Initialisation(mat_etudiant,code_promo,Id_an_acad,Nom_etudiant,tr1);
          } else {
            // La suppression a échoué
            console.log('Erreur lors de la suppression de l\'enregistrement');
          }
        };

        // Envoi de la requête avec les données nécessaires
        xhr.send("mat_etudiant="+mat_etudiant+"&code_promo=" +code_promo
        +"&Id_annee_acad="+Id_an_acad+"&date_paie="+date_paie);
    } 
    
    else
    {
      // Action à effectuer si l'utilisateur répond "Annuler"
      console.log("Action annulée");
    }*/
  } 
  else 
  {
    console.log("Moins de deux heures se sont écoulées depuis la date de "+date_base);
  }


    
    
   
}


/***************************************************************************************** */
