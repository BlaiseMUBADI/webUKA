console.log("NOUS SOMMES DANS SELECTION TOUS LES AGENTS");

document.addEventListener("DOMContentLoaded", function() {
    Gestion_prise_en_charge();
});

function Gestion_prise_en_charge() {
    let TabCharge = document.getElementById("TabCharge");
    TabCharge.classList.add("table", "table-bordered");
    while (TabCharge.firstChild) {
        TabCharge.removeChild(TabCharge.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Matricule", "Prénom", "Nom", "Post-nom", "Grade", "Base", "Prime"];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    TabCharge.appendChild(thead);

    var tbody = document.createElement("tbody");

    var url = 'D_Administratif/API/Selection_Agent_charge.php';
    fetch(url)
        .then(response => response.json())
        .then(donnees => {
            const gradeOrder = ["PE", "PO", "P", "PA", 
                "CT", "ASS2", "ASS1", "CPP2","CPP1",
                "DIR", "CD","CB", "ATB1", "ATB2", "AGB1", "AGB2"];

            // Compter le nombre de personnes par grade
            const gradeCounts = donnees.reduce((acc, agent) => {
                acc[agent.Grade] = (acc[agent.Grade] || 0) + 1;
                return acc;
            }, {});

            donnees.sort((a, b) => gradeOrder.indexOf(a.Grade) - gradeOrder.indexOf(b.Grade));

            let currentGrade = null;
            let i = 1; // Initialiser la numérotation

            donnees.forEach(tos => {
                if (tos.Grade !== currentGrade) {
                    currentGrade = tos.Grade;
                    i = 1; // Réinitialiser la numérotation

                    var trGrade = document.createElement("tr");
                    var tdGrade = document.createElement("td");
                    tdGrade.colSpan = headers.length;
                    tdGrade.textContent = ` ${currentGrade} (${gradeCounts[currentGrade]})`;
                    trGrade.appendChild(tdGrade);
                    tbody.appendChild(trGrade);
                    trGrade.style = "text-align:center;";
                }

                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                tdnum.textContent = i;

                var tdmatricule = document.createElement("td");
                var tdprenom = document.createElement("td");
                var tdnom = document.createElement("td");
                var tdpostnom = document.createElement("td");
                var tdgrade = document.createElement("td");
                var tdbase = document.createElement("td");
                var tdprime = document.createElement("td");

                if (/NU/.test(tos.Mat_agent)) {
                    tdmatricule.textContent = "NU";
                } else {
                    tdmatricule.textContent = tos.Mat_agent;
                }

                tdprenom.textContent = tos.Prenom;
                tdnom.textContent = tos.Nom_agent;
                tdpostnom.textContent = tos.Post_agent;
                tdgrade.textContent = tos.Grade;

                // Ajouter des cases à cocher dynamiques
                if (tos.Base === "base" && tos.Prime === "prime") {
                    tdbase.innerHTML = `<input type="checkbox" checked disabled name="base" value="${tos.Mat_agent}" class="checkbox-base">`;
                    tdprime.innerHTML = `<input type="checkbox" checked disabled name="prime" value="${tos.Mat_agent}" class="checkbox-prime">`;
                } else if (tos.Base === "base") {
                    tdbase.innerHTML = `<input type="checkbox" checked disabled name="base" value="${tos.Mat_agent}" class="checkbox-base">`;
                    tdprime.innerHTML = `<input type="checkbox" name="prime" id="2" value="${tos.Mat_agent}" class="checkbox-prime">`;
                } else if (tos.Prime === "prime") {
                    tdbase.innerHTML = `<input type="checkbox" name="base" id="1" value="${tos.Mat_agent}" class="checkbox-base">`;
                    tdprime.innerHTML = `<input type="checkbox" checked disabled name="prime" value="${tos.Mat_agent}" class="checkbox-prime">`;
                } else {
                    tdbase.innerHTML = `<input type="checkbox" name="base" id="1" value="${tos.Mat_agent}" class="checkbox-base">`;
                    tdprime.innerHTML =`<input type="checkbox" name="prime" id="2" value="${tos.Mat_agent}" class="checkbox-prime">`;
                }
                
                tr.appendChild(tdnum);
                tr.appendChild(tdmatricule);
                tr.appendChild(tdprenom);
                tr.appendChild(tdnom);
                tr.appendChild(tdpostnom);
                tr.appendChild(tdgrade);
                tr.appendChild(tdbase);
                tr.appendChild(tdprime);

                tbody.appendChild(tr);
                i++;
            });

            // Ajouter un écouteur d'événements pour les cases à cocher
            document.querySelectorAll('.checkbox-base, .checkbox-prime').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const type = this.name; // Le nom de la case à cocher est soit "base" soit "prime"
                    const Obs=this.id;
                    console.log("Le matricule est " + this.value + " et le type est " + type+" observation est "+Obs);  // Affichage correct ici
                    if (this.checked) {
                        // Envoyer le numéro matricule et le type (base ou prime) à l'API
                        fetch('D_Administratif/API/API_Gestion_Mecanisation.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ matricule: this.value, charge: type ,observ:Obs}) // Convertir les données en JSON
                        
                        })
                        .then(response => response.json()) // Analyser la réponse JSON
                        .then(data => {
                            console.log('Insertion réussie:', data); // Afficher un message de succès
                            Gestion_prise_en_charge();
                        })
                        .catch(error => {
                            console.error('Erreur lors de l\'insertion:', error); // Afficher un message d'erreur
                        });
                    }
                });
            });
            

        }).catch(error => {
            console.log("Erreur lors de contact de l'API Afficher Liste" + error);
        });
    TabCharge.appendChild(tbody);
}
