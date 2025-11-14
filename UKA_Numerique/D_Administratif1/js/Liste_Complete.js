console.log("NOUS SOMMES DANS SELECTION TOUS LES AGENTS de L'UKA");

document.addEventListener("DOMContentLoaded", function() {
    AfficherListe();
});

function AfficherListe() {
    let tabListeAgent = document.getElementById("TabListeAgent");
    tabListeAgent.classList.add("table", "table-bordered");
    while (tabListeAgent.firstChild) {
        tabListeAgent.removeChild(tabListeAgent.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Matricule", "Prénom", "Nom", "Post-nom", "Niveau", "Grade", "Domaine", "DateN", "Lieu", "Tél", "E-Mail", "EtatC"];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    tabListeAgent.appendChild(thead);

    var tbody = document.createElement("tbody");

    var url = 'D_Administratif/API/Selection_Agent.php';
    fetch(url)
        .then(response => response.json())
        .then(donnees => {
            const gradeOrder = ["PE", "PO", "P", "PA", "CT", "ASS2", "ASS1","ASSR1", "CPP2", "CPP1", "DIR", "CB", "ATB1", "ATB2", "AGB1", "AGB2"];
            const academicGrades = ["PE", "PO", "P", "PA"];
            const scientiGrades = ["CT", "ASS2", "ASS1","ASSR1", "CPP2", "CPP1"];
            const adminGrades = ["DIR", "CB", "ATB1", "ATB2"];
            const patoGrades = ["AGB1", "AGB2"];
            // Compter le nombre de personnes par grade
            const gradeCounts = donnees.reduce((acc, agent) => {
                acc[agent.Grade] = (acc[agent.Grade] || 0) + 1;
                return acc;
            }, {});

            donnees.sort((a, b) => gradeOrder.indexOf(a.Grade) - gradeOrder.indexOf(b.Grade));

            let currentGrade = null;
            let i = 1; // Initialiser la numérotation

            let academicAdded = false;
            let scientiAdded = false;
            let adminAdded = false;
            let patoAdded = false;

            donnees.forEach(tos => {
                if (tos.Grade !== currentGrade) {
                    currentGrade = tos.Grade;
                    i = 1; // Réinitialiser la numérotation

                    if (academicGrades.includes(currentGrade) && !academicAdded) {
                        var trAcad = document.createElement("tr");
                        var tdAcad = document.createElement("td");
                        tdAcad.colSpan = headers.length;
                        tdAcad.textContent = "I. PERSONNEL ACADEMIQUE ";
                        trAcad.appendChild(tdAcad);
                        trAcad.style = "background-color:rgba(194, 185, 185, 0.5); color:black; text-align:left;";
                        tbody.appendChild(trAcad);
                        academicAdded = true;
                    }
                    if (scientiGrades.includes(currentGrade) && !scientiAdded) {
                        var trScien = document.createElement("tr");
                        var tdScien = document.createElement("td");
                        tdScien.colSpan = headers.length;
                        tdScien.textContent = "II. PERSONNEL SCIENTIFIQUE";
                        trScien.appendChild(tdScien);
                        trScien.style = "background-color:rgba(194, 185, 185, 0.5); color:black; text-align:left;";

                        tbody.appendChild(trScien);
                        scientiAdded = true;
                    }

                    if (adminGrades.includes(currentGrade) && !adminAdded) {
                        var trAdmin = document.createElement("tr");
                        var tdAdmin = document.createElement("td");
                        tdAdmin.colSpan = headers.length;
                        tdAdmin.textContent = "III. PERSONNEL ADMINISTRATIF";
                        trAdmin.appendChild(tdAdmin);
                        trAdmin.style = "background-color:rgba(194, 185, 185, 0.5); color:black; text-align:left;";
                        
                        tbody.appendChild(trAdmin);
                        adminAdded = true;
                    }
                    if (patoGrades.includes(currentGrade) && !patoAdded) {
                        var trpto = document.createElement("tr");
                        var tdpto = document.createElement("td");
                        tdpto.colSpan = headers.length;
                        tdpto.textContent = "IV. PERSONNEL TECHNIQUE ET OUVRIER (PTO)";
                        trpto.appendChild(tdpto);
                        trpto.style = "background-color:rgba(194, 185, 185, 0.5); color:black; text-align:left;";

                        tbody.appendChild(trpto);
                        patoAdded = true;
                    }
                    var trGrade = document.createElement("tr");
                    var tdGrade = document.createElement("td");
                    tdGrade.colSpan = headers.length;
                    tdGrade.textContent = ` ${currentGrade} (${gradeCounts[currentGrade]})`;
                    trGrade.appendChild(tdGrade);
                    trGrade.style = "background-color:midnightblue; color:white; text-align:left;";
                    tbody.appendChild(trGrade);
                }

                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                tdnum.textContent = i;

                var tdmatricule = document.createElement("td");
                var tdprenom = document.createElement("td");
                var tdnom = document.createElement("td");
                var tdpostnom = document.createElement("td");
                var tdniveau = document.createElement("td");
                var tdgrade = document.createElement("td");
                var tddomaine = document.createElement("td");
                var tddatenaiss = document.createElement("td");
                var tdlieunaiss = document.createElement("td");
                var tdtel = document.createElement("td");
                var tdmail = document.createElement("td");
                var tdetatciv = document.createElement("td");

                if (/NU/.test(tos.Mat_agent)) {
                    tdmatricule.textContent = "NU";
                } else {
                    tdmatricule.textContent = tos.Mat_agent;
                }

                tdprenom.textContent = tos.Prenom;
                tdnom.textContent = tos.Nom_agent;
                tdpostnom.textContent = tos.Post_agent;
                tdniveau.textContent = tos.Niveau_Etude;
                tdgrade.textContent = tos.Grade;
                tddomaine.textContent = tos.Domaine;
                tddatenaiss.textContent = tos.DateNaissance;
                tdlieunaiss.textContent = tos.Lieu;
                tdtel.textContent = tos.Tel;
                tdmail.textContent = tos.Mail;
                tdetatciv.textContent = tos.EtatCivil;

                tr.appendChild(tdnum);
                tr.appendChild(tdmatricule);
                tr.appendChild(tdprenom);
                tr.appendChild(tdnom);
                tr.appendChild(tdpostnom);
                tr.appendChild(tdniveau);
                tr.appendChild(tdgrade);
                tr.appendChild(tddomaine);
                tr.appendChild(tddatenaiss);
                tr.appendChild(tdlieunaiss);
                tr.appendChild(tdtel);
                tr.appendChild(tdmail);
                tr.appendChild(tdetatciv);

                tbody.appendChild(tr);
                i++;
            });
        }).catch(error => {
            console.log("Erreur lors de contact de l'API Afficher Liste" + error);
        });
    tabListeAgent.appendChild(tbody);
}
