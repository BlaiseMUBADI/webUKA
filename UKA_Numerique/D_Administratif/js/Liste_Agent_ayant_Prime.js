console.log("NOUS SOMMES DANS SELECTION TOUS LES AGENTS AVEC PRIME");

document.addEventListener("DOMContentLoaded", function() {
    AfficherListePrimé();
});

function AfficherListePrimé() {
    let tabNonPrime = document.getElementById("TabPrime");
    tabNonPrime.classList.add("table", "table-bordered");
    while (tabNonPrime.firstChild) {
        tabNonPrime.removeChild(tabNonPrime.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Matricule", "Nom", "Post-nom", "Prénom","Sexe", "Grade"];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    tabNonPrime.appendChild(thead);

    var tbody = document.createElement("tbody");

    var url = 'D_Administratif/API/API_Select_Agent_Avec_Prime.php';
    fetch(url)
        .then(response => response.json())
        .then(donnees => {
            const gradeOrder = ["PE", "PO", "P", "PA", "CT", "ASS2", "ASS1", "DIR", "CB", "ATB1", "ATB2", "AGB1", "AGB2"];

            // Compter le nombre de personnes par grade
            const gradeCounts = donnees.reduce((acc, agent) => {
                acc[agent.Grade] = (acc[agent.Grade] || 0) + 1;
                return acc;
            }, {});

            donnees.sort((a, b) => gradeOrder.indexOf(a.Grade) - gradeOrder.indexOf(b.Grade));

            let currentGrade = null;
            let i = 1; // Initialiser la numérotation

            donnees.forEach(data => {
                if (data.Grade !== currentGrade) {
                    currentGrade = data.Grade;
                    i = 1; // Réinitialiser la numérotation

                    var trGrade = document.createElement("tr");
                    var tdGrade = document.createElement("td");
                    tdGrade.colSpan = headers.length;
                    tdGrade.textContent = ` ${currentGrade} (${gradeCounts[currentGrade]})`;
                    trGrade.appendChild(tdGrade);
                    tbody.appendChild(trGrade);
                    //trGrade.style="text-align:center;"
                }

                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                tdnum.textContent = i;

                var tdmatricule = document.createElement("td");
                var tdnom = document.createElement("td");
                var tdpostnom = document.createElement("td");
                var tdprenom = document.createElement("td");
                var tdsexe = document.createElement("td");
                var tdgrade = document.createElement("td");
               

                if (/NU/.test(data.Mat_agent)) {
                    tdmatricule.textContent = "NU";
                } else {
                    tdmatricule.textContent = data.Mat_agent;
                }

                tdprenom.textContent = data.Prenom;
                tdnom.textContent = data.Nom_agent;
                tdpostnom.textContent = data.Post_agent;
                tdsexe.textContent = data.Niveau_Etude;
                tdgrade.textContent = data.Grade;
                

                tr.appendChild(tdnum);
                tr.appendChild(tdmatricule);
                tr.appendChild(tdprenom);
                tr.appendChild(tdnom);
                tr.appendChild(tdpostnom);
                tr.appendChild(tdsexe);
                tr.appendChild(tdgrade);
                

                tbody.appendChild(tr);
                i++;
            });
        }).catch(error => {
            console.log("Erreur lors de contact de l'API Afficher Liste" + error);
        });
    tabNonPrime.appendChild(tbody);
}
