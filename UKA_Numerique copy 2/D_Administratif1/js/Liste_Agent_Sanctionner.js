console.log("NOUS SOMMES DANS SELECTION AGENT POUR APPLIQUER SANCTION");

document.addEventListener("DOMContentLoaded", function() {
  AfficherAgent_a_Sanctionner();
  // Écouteur d'événements sur le champ de recherche
  const searchInput = document.getElementById("Rechercher");
  searchInput.addEventListener("input", function() {
    rechercherAgents(searchInput.value); // Recherche à chaque saisie
  });
});
  function AfficherAgent_a_Sanctionner()
  {
    let TabSanction = document.getElementById("TableAgentSanction");
    TabSanction.classList.add("table", "table-bordered");
    while (TabSanction.firstChild) {
        TabSanction.removeChild(TabSanction.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Matricule", "Nom", "Postnom", "Prénom", "Sexe"];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    TabSanction.appendChild(thead);

    var tbody = document.createElement("tbody");

    var url = 'D_Administratif/API/API_Select_Agent_Sanctionner.php';
    fetch(url)
        .then(response => response.json())
        .then(donnees => {
          let i = 1; // Initialiser la numérotation

          donnees.forEach(tos => {
          var tr = document.createElement("tr");

          var tdnum = document.createElement("td");
          tdnum.textContent = i;

          var tdmatricule = document.createElement("td");
          var tdnom = document.createElement("td");
          var tdpostnom = document.createElement("td");
          var tdprenom = document.createElement("td");
          var tdsexe = document.createElement("td");
          

          if (/NU/.test(tos.Mat_agent)) {
              tdmatricule.textContent = "NU";
          } else {
              tdmatricule.textContent = tos.Mat_agent;
          }

          
          tdnom.textContent = tos.Nom_agent;
          tdpostnom.textContent = tos.Post_agent;
          tdprenom.textContent = tos.Prenom;
          tdsexe.textContent = tos.Sexe;


          tr.appendChild(tdnum);
          tr.appendChild(tdmatricule);
          tr.appendChild(tdnom);
          tr.appendChild(tdpostnom);
          tr.appendChild(tdprenom);
          tr.appendChild(tdsexe);
     

          tbody.appendChild(tr);
          i++;
    
        });
  
        
      }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
            TableAgentSanction.appendChild(tbody);

                   
  }

  // Fonction de recherche
  function rechercherAgents(texteRecherche) {
  const tbody = document.querySelector("#TableAgentSanction tbody");
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
}