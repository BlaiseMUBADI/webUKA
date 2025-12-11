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
    
    
      td1.textContent = "N°";
      td2.textContent = "Matricule";
      td3.textContent = "Nom";
      td4.textContent = "Postnom";
      td5.textContent = "Prenom";
      td6.textContent = "Sexe";
      td7.textContent = "Grade";
    
  
      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);
      tr.appendChild(td4);
      tr.appendChild(td5);
      tr.appendChild(td6);
      tr.appendChild(td7);

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
               
               /* if (/NU/.test(infos.Mat_agent)) 
                  {tdmatricule.textContent ="NU"; }
                else{tdmatricule.textContent = infos.Mat_agent;}*/
                tdmatricule.textContent = infos.Mat_agent;
                  tdnom.textContent = infos.Nom_agent;
                  tdpostnom.textContent = infos.Post_agent;
                  tdprenom.textContent = infos.Prenom;
                  tdsexe.textContent = infos.Sexe;
                  tdgrade.textContent = infos.Grade;
          
                tr.appendChild(tdnum);
                tr.appendChild(tdmatricule);
                tr.appendChild(tdnom);
                tr.appendChild(tdpostnom);
                tr.appendChild(tdprenom);
                tr.appendChild(tdsexe);
                tr.appendChild(tdgrade);

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
                tr.addEventListener("click", function() {
                  // Ce bout de code permet de faire une sélection de ligne en fixant une couleur de fond
                  var rows = TabListeAgent_cat.getElementsByTagName('tr');  
                  for (var j = 0; j < rows.length; j++) {
                    if (j !== 0) rows[j].style.backgroundColor = '';
                  }
                  this.style.backgroundColor = 'red';
                
                  // Obtenir les données de la ligne sélectionnée
                  const row = this;
                  const tdmat = row.querySelector("td:nth-child(2)");
                  const tdNom = row.querySelector("td:nth-child(3)");
                  const tdpost = row.querySelector("td:nth-child(4)"); // Assurez-vous que cela correspond à la bonne colonne
                  const tdprenom = row.querySelector("td:nth-child(5)"); // Assurez-vous que cela correspond à la bonne colonne
      
                
                  const pageParams = {
                    'page': 'espacepersoagent',
                    'mat': tdmat.textContent,
                    'nom': tdNom.textContent,
                    'post': tdpost.textContent,
                    'prenom': tdprenom.textContent // Assurez-vous d'obtenir la valeur correcte
                    
                  };
                  
                  // Redirection avec les paramètres GET
                  const params = new URLSearchParams(pageParams);
                  const baseUrl = 'Page_Principale.php'; // Assurez-vous que c'est un .php si nécessaire
                  const url = `${baseUrl}?${params.toString()}`;
                  window.location.href = url;
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