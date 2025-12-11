console.log("nous sommes dans selection liste agent par catégorie");


var cat = document.getElementById("Categorielisteagent");
var critere = document.getElementById("Critere");
cat.addEventListener('change',(event) => {
    var idCat = cat.value;
    var Criteres=critere.value;
    console.log("LE CODE CATEGORIE EST"+idCat);
    console.log("LE CODE CATEGORIE EST"+Criteres);
    AfficherAgent(idCat,Criteres);
    
  });
  critere.addEventListener('change',(event) => {
    var idCat = cat.value;
    var Criteres=critere.value;
    console.log("LE CODE CATEGORIE EST"+idCat);
    console.log("LE CODE CATEGORIE EST"+Criteres);
    AfficherAgent(idCat,Criteres);
  });
  function AfficherAgent(idCat,Criteres)
  {
  
  
     let TabListeAgent_categorie = document.getElementById("TabListeAgent_cat");
  
      while (TabListeAgent_categorie.firstChild) {
        TabListeAgent_categorie.removeChild(TabListeAgent_categorie.firstChild);
      }

      var thead = document.createElement("thead");
      thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl
  
      var tr = document.createElement("tr");
                  tr.style="";
      tr.style="background-color:midnightblue; color:white;"
  
      var td1 = document.createElement("td");      
      var td2 = document.createElement("td");
      var td3 = document.createElement("td");
      var td4 = document.createElement("td");
      var td5 = document.createElement("td");
      var td6 = document.createElement("td");
      var td7 = document.createElement("td");
      var td8 = document.createElement("td");
      var td9 = document.createElement("td");
      var td10 = document.createElement("td");
      var td11 = document.createElement("td");
    
    
      td1.textContent = "N°";
      td2.textContent = "Matricule";
      td3.textContent = "Nom";
      td4.textContent = "Postnom";
      td5.textContent = "Prenom";
      td6.textContent = "Sexe";
      td7.textContent = "Grade";
      td8.textContent = "Adresse";
      td9.textContent = "Etat civil";
      td10.textContent = "Date de Naiss";
      td11.textContent = "Lieu de Naiss";
      td9.style.display = "none";

  
      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);
      tr.appendChild(td4);
      tr.appendChild(td5);
      tr.appendChild(td6);
      tr.appendChild(td7);
      tr.appendChild(td8);
      tr.appendChild(td9);
      tr.appendChild(td10);
      tr.appendChild(td11);

      thead.appendChild(tr);
      TabListeAgent_categorie.appendChild(thead);
        
      var tbody = document.createElement("tbody");
      
  
      var url='D_Administratif/API/Selection_Agent_par_Categorie.php?codeCat='+idCat+'&critere='+Criteres;  
  
       
      var i=1;
      fetch(url) 
      .then(response => response.text()) // Changer .json() en .text() 
      .then(text => 
        { console.log(text); // Afficher la réponse brute 
        return JSON.parse(text); // Parser en JSON 
        })
     
      .then(data => 
      {

        data.forEach(infos =>
          {
            // Création de TR
                var tr = document.createElement("tr");
                var tdnum = document.createElement("td");
                tdnum.textContent = i;
  
                var tdmatricule= document.createElement("td");
                var tdnom= document.createElement("td");
                var tdpostnom = document.createElement("td");
                var tdprenom = document.createElement("td");
                var tdsexe = document.createElement("td");
                var tdgrade = document.createElement("td");
                var tdadresse = document.createElement("td");
                var tdetat = document.createElement("td");
                var tddatenaiss = document.createElement("td");
                var tdlieu = document.createElement("td");
               
               /* if (/NU/.test(infos.Mat_agent)) 
                  {tdmatricule.textContent ="NU"; }
                else{tdmatricule.textContent = infos.Mat_agent;}*/
                tdmatricule.textContent = infos.Mat_agent;
                  tdnom.textContent = infos.Nom_agent;
                  tdpostnom.textContent = infos.Post_agent;
                  tdprenom.textContent = infos.Prenom;
                  tdsexe.textContent = infos.Sexe;
                  tdgrade.textContent = infos.Grade;
                  tdadresse.textContent = infos.AdressePhysique;
                  tdetat.textContent = infos.EtatCivil;
                  tddatenaiss.textContent = infos.DateNaissance;
                  tdlieu.textContent = infos.Lieu;
                  tdetat.style.display = "none";

                tr.appendChild(tdnum);
                tr.appendChild(tdmatricule);
                tr.appendChild(tdnom);
                tr.appendChild(tdpostnom);
                tr.appendChild(tdprenom);
                tr.appendChild(tdsexe);
                tr.appendChild(tdgrade);
                tr.appendChild(tdadresse);
                tr.appendChild(tdetat);
                tr.appendChild(tddatenaiss);
                tr.appendChild(tdlieu);

                tr.addEventListener('mouseenter', function() {
                  tr.style.cursor = 'pointer'; // Change le curseur au survol
                  tr.style.backgroundColor = 'rgba(9, 241, 160, 0.5)'; // Optionnel: changer la couleur de fond
              });

              tr.addEventListener('mouseleave', function() {
                  tr.style.cursor = ''; // Réinitialise le curseur à sa valeur par défaut
                  tr.style.backgroundColor = ''; // Réinitialise la couleur de fond
              });

                tbody.appendChild(tr);
                i++;
                tr.addEventListener("contextmenu", function(e) {
                  e.preventDefault();
              
                  // Supprimer un menu existant s’il y en a un
                  let existingMenu = document.getElementById("contextMenu");
                  if (existingMenu) existingMenu.remove();
              
                  // Créer le menu
                  const menu = document.createElement("div");
                  menu.id = "contextMenu";
                  menu.style.position = "absolute";
                  menu.style.top = `${e.pageY}px`;
                  menu.style.left = `${e.pageX}px`;
                  menu.style.backgroundColor = "#fff";
                  menu.style.border = "1px solid #ccc";
                  menu.style.zIndex = "1000";
                  menu.style.boxShadow = "2px 2px 5px rgba(0,0,0,0.2)";
                  menu.style.minWidth = "120px";
              
                  // Ajouter une bordure entre les éléments
                  const addOption = (text, callback) => {
                      const item = document.createElement("div");
                      item.textContent = text;
                      item.style.padding = "8px";
                      item.style.cursor = "pointer";
                      item.style.borderBottom = "1px solid #eee";
                      item.addEventListener("click", () => {
                          callback();
                          menu.remove();
                      });
                      item.addEventListener("mouseenter", () => item.style.backgroundColor = "#f0f0f0");
                      item.addEventListener("mouseleave", () => item.style.backgroundColor = "#fff");
                      menu.appendChild(item);
                  };
              
                  // Option 1 : Modifier
                  addOption("Personnel", () => {
                      const tdmat = tr.querySelector("td:nth-child(2)").textContent;
                      const tdNom = tr.querySelector("td:nth-child(3)").textContent;
                      const tdpost = tr.querySelector("td:nth-child(4)").textContent;
                      const tdprenom = tr.querySelector("td:nth-child(5)").textContent;
              
                      const pageParams = {
                          'page': 'espacepersoagent',
                          'mat': tdmat,
                          'nom': tdNom,
                          'post': tdpost,
                          'prenom': tdprenom
                      };
                      const params = new URLSearchParams(pageParams);
                      window.location.href = `Page_Principale.php?${params.toString()}`;
                  });
              
                  // Option 2 : Personnel
                  addOption("Modifier", () => {
                    const data = {
                      matricule: tr.querySelector("td:nth-child(2)").textContent,
                      nom: tr.querySelector("td:nth-child(3)").textContent,
                      postnom: tr.querySelector("td:nth-child(4)").textContent,
                      prenom: tr.querySelector("td:nth-child(5)").textContent,
                      sexe: tr.querySelector("td:nth-child(6)").textContent,
                      grade: tr.querySelector("td:nth-child(7)").textContent,
                      adresse: tr.querySelector("td:nth-child(8)").textContent,
                      etatCivil: tr.querySelector("td:nth-child(9)").textContent,
                      dateNaissance: tr.querySelector("td:nth-child(10)").textContent,
                      lieuNaissance: tr.querySelector("td:nth-child(11)").textContent
                    };
                  
                    ouvrirModalAvecDonnees(data);
                  });
                  
              
                  document.body.appendChild(menu);
              });
              
              // Cacher le menu si on clique ailleurs
              document.addEventListener("click", function(e) {
                  const menu = document.getElementById("contextMenu");
                  if (menu) menu.remove();
              });
              
                
          });
          
  
        
      }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
            TabListeAgent_cat.appendChild(tbody);

                   
  }
// Fonction de recherche
function rechercherAgent(texteRecherche) {
  const table = document.querySelector("#TabListeAgent_cat");
  if (table) {
    const tbody = table.querySelector("tbody");
    if (tbody) {
      const lignes = tbody.querySelectorAll("tr");

      lignes.forEach(ligne => {
        const tdNom = ligne.querySelector("td:nth-child(3)"); // Le nom est dans la 3ème colonne
        if (tdNom) {
          const nom = tdNom.textContent.toLowerCase();
          // Si le nom contient le texte de recherche, on affiche la ligne, sinon on la cache
          if (nom.includes(texteRecherche.toLowerCase())) {
            ligne.style.display = ""; // Affiche la ligne
          } else {
            ligne.style.display = "none"; // Cache la ligne
          }
        }
      });
    } else {
      console.error("Aucun tbody trouvé dans #TabListeAgent_cat.");
    }
  } else {
    console.error("Aucun élément trouvé avec l'ID #TabListeAgent_cat.");
  }
}

document.addEventListener('DOMContentLoaded', function() {
  // Écouteur d'événements sur le champ de recherche
  const searchInput = document.getElementById("rechercher");
  if (searchInput) {
    searchInput.addEventListener("input", function() {
      rechercherAgent(searchInput.value); // Recherche à chaque saisie
    });
  } else {
    console.error("Aucun élément trouvé avec l'ID #rechercher.");
  }
});

function ouvrirModalAvecDonnees(data) {
  document.getElementById("matricule").value = data.matricule || '';
  document.getElementById("nom").value = data.nom || '';
  document.getElementById("postnom").value = data.postnom || '';
  document.getElementById("prenom").value = data.prenom || '';
  document.getElementById("sexe").value = data.sexe || '';
  document.getElementById("Etatciv").value = data.etatCivil || '';
  document.getElementById("lieuNaissance").value = data.lieuNaissance || '';
  document.getElementById("dateNaissance").value = data.dateNaissance || '';

  // Affiche l'étape 1 et cache la 2 par défaut
  afficherEtape1();

  // Affiche le modal
  document.getElementById("modifierModal").style.display = "block";
}

document.getElementById("formModifierAgent").addEventListener("submit", function(e) {
  e.preventDefault(); // Empêche le formulaire de se soumettre de manière classique

  // Récupérer les données du formulaire
  const matricule = document.getElementById("matricule").value;
  const nom = document.getElementById("nom").value;
  const postnom = document.getElementById("postnom").value;
  const prenom = document.getElementById("prenom").value;
  const sexe = document.getElementById("sexe").value;
  const etatCivil = document.getElementById("Etatciv").value;
  const lieuNaissance = document.getElementById("lieuNaissance").value;
  const dateNaissance = document.getElementById("dateNaissance").value;

  // Créer l'objet des données à envoyer
  const agentData = {
    matricule: matricule,
    nom: nom,
    postnom: postnom,
    prenom: prenom,
    sexe: sexe,
    etatCivil: etatCivil,
    lieuNaissance: lieuNaissance,
    dateNaissance: dateNaissance
  };

  console.log("Données à envoyer :", agentData);

  // Envoyer les données à l'API (exemple avec `fetch` et requête `PUT`)
  fetch('D_Administratif/API/modifier-agent.php', {
    method: 'PUT', // Ou 'PATCH' selon ce que l'API attend
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer TON_JETON_D_AUTHENTIFICATION' // Si l'API nécessite une authentification
    },
    body: JSON.stringify(agentData) // Convertir les données en JSON
  })
  .then(response => response.json()) // Convertir la réponse de l'API en JSON
  .then(data => {
    console.log('Réponse de l\'API:', data); // Afficher la réponse de l'API
    if (data.success) {
      alert('Agent modifié avec succès !');
      fermerModal(); // Fermer le modal
    } else {
      alert('Erreur lors de la modification de l\'agent.');
    }
  })
  .catch(error => {
    console.error('Erreur:', error);
    alert('Une erreur est survenue lors de l\'envoi des données.');
  });
});



function afficherEtape2() {
  document.getElementById("formStep1").style.display = "none";
  document.getElementById("formStep2").style.display = "block";
}

function afficherEtape1() {
  document.getElementById("formStep1").style.display = "block";
  document.getElementById("formStep2").style.display = "none";
}

function fermerModal() {
  document.getElementById("modifierModal").style.display = "none";
  afficherEtape1(); // Réinitialise à l'étape 1 pour la prochaine ouverture
}

