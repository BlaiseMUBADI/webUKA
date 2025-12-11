
var type_paie = document.getElementById("Quinzaine");


console.log("NOUS SOMMES DANS LA GESTION DE LA PAIE LOCALE");

document.addEventListener("DOMContentLoaded", function() {
 
        const radioButtons = document.querySelectorAll('input[name="option"]');

 // Ajouter un écouteur d'événement à chaque bouton radio
        radioButtons.forEach(button => {
            button.addEventListener("change", function() {
                // Afficher la valeur du bouton activé dans la console
                console.log(`Button activated: ${button.value}`);
                val=button.value;
                if(val==="Quinzaine")
                {
                    var enteteQ = document.getElementById('quinze'); enteteQ.style.display='block';
                    var enteteP = document.getElementById('prime'); enteteP.style.display='none';
                    var enteteP = document.getElementById('prime1'); enteteP.style.display='block';
                    
                }
                else {
                    var enteteQ = document.getElementById('quinze'); enteteQ.style.display='none';
                    var enteteP = document.getElementById('prime'); enteteP.style.display='block';
                    var enteteP = document.getElementById('prime1'); enteteP.style.display='block';
                }
                console.log("le"+val);AfficherAgent_Quinzaine(val);
            });

            });
    Gestion_paie_locale();
 
});

function Gestion_paie_locale() {
    let tablePaieLocale = document.getElementById("TablePaieLocale");
    tablePaieLocale.classList.add("table", "table-bordered");
    while (tablePaieLocale.firstChild) {
        tablePaieLocale.removeChild(tablePaieLocale.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Matricule", "Prénom", "Nom", "Post-nom", "Grade", "Quinzaine", "Prime inst."];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    tablePaieLocale.appendChild(thead);

    var tbody = document.createElement("tbody");

    var url = 'D_Administratif/API/Selection_Agent_Paie_Locale.php';
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
                var tdquinzaine = document.createElement("td");
                var tdprimeInst = document.createElement("td");
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
                if (tos.Quinzaine === "Quinzaine" && tos.Prime_Inst === "Prime_Inst") {
                    tdquinzaine.innerHTML = `<input type="checkbox" checked name="Quinzaine" id="1" value="${tos.Mat_agent}" class="checkbox-quinzaine">`;
                    tdprimeInst.innerHTML =`<input type="checkbox" checked name="Prime_Inst" id="2" value="${tos.Mat_agent}" class="checkbox-prime_inst">`;
                } else if (tos.Quinzaine === "Quinzaine") {
                    tdquinzaine.innerHTML = `<input type="checkbox" checked name="Quinzaine" id="1" value="${tos.Mat_agent}" class="checkbox-quinzaine">`;
                    tdprimeInst.innerHTML =`<input type="checkbox"  name="Prime_Inst" id="2" value="${tos.Mat_agent}" class="checkbox-prime_inst">`;
                } else if (tos.Prime_Inst === "Prime_Inst") {
                    tdquinzaine.innerHTML = `<input type="checkbox"  name="Quinzaine" id="1" value="${tos.Mat_agent}" class="checkbox-quinzaine">`;
                    tdprimeInst.innerHTML =`<input type="checkbox" checked name="Prime_Inst" id="2" value="${tos.Mat_agent}" class="checkbox-prime_inst">`;
                } else {
                    tdquinzaine.innerHTML = `<input type="checkbox" name="Quinzaine" id="1" value="${tos.Mat_agent}" class="checkbox-quinzaine">`;
                    tdprimeInst.innerHTML =`<input type="checkbox" name="Prime_Inst" id="2" value="${tos.Mat_agent}" class="checkbox-prime_inst">`;
                }


                  
             
                tr.appendChild(tdnum);
                tr.appendChild(tdmatricule);
                tr.appendChild(tdprenom);
                tr.appendChild(tdnom);
                tr.appendChild(tdpostnom);
                tr.appendChild(tdgrade);
                tr.appendChild(tdquinzaine);
                tr.appendChild(tdprimeInst);

                tbody.appendChild(tr);
                i++;
            });

            // Ajouter un écouteur d'événements pour les cases à cocher
            document.querySelectorAll('.checkbox-quinzaine, .checkbox-prime_inst').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const type = this.name; 
                    const Obs = this.id;
            
                    console.log("Le matricule est " + this.value + " observation est " + Obs);  // Affichage correct ici
                    
                    // Déterminer si la case est cochée ou décochée
                    const action = this.checked ? 'insert' : 'delete'; // Si coché, action 'insert', sinon 'delete'
                    
                    // Envoi des données à l'API
                    fetch('D_Administratif/API/API_Gestion_Paie_Locale.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            matricule: this.value,
                            libelle: type,
                            observ: Obs,
                            action: action // Ajouter l'action pour savoir si c'est une insertion ou une suppression
                        })
                    })
                    .then(response => response.json()) // Analyser la réponse JSON
                    .then(data => {
                        console.log(action === 'insert' ? 'Insertion réussie:' : 'Suppression réussie:', data); // Afficher un message de succès
                        Gestion_paie_locale();
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'opération:', error); // Afficher un message d'erreur
                    });
                });
            });
            
            

        }).catch(error => {
            console.log("Erreur lors de contact de l'API Afficher Liste" + error);
        });
        tablePaieLocale.appendChild(tbody);
}

//FONCTION POUR LES AGENTS AYANT DROIT A LA QUINZAINE et A LA PRIME INSTITUTIONNELLE
        var val="";

function AfficherAgent_Quinzaine(val)
{
  let tableQuinzaine = document.getElementById("TableQuinzaine");
  tableQuinzaine.classList.add("table", "table-bordered");
  while (tableQuinzaine.firstChild) {
      tableQuinzaine.removeChild(tableQuinzaine.firstChild);
  }

  var thead = document.createElement("thead");
  thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

  var tr = document.createElement("tr");
  tr.style = "background-color:midnightblue; color:white; text-align:center;";

  var headers = ["N°", "Matricule", "Nom", "Postnom", "Prénom", "Observation"];
  headers.forEach(header => {
      var td = document.createElement("td");
      td.textContent = header;
      tr.appendChild(td);
  });

  thead.appendChild(tr);
  tableQuinzaine.appendChild(thead);

  var tbody = document.createElement("tbody");

  var url = 'D_Administratif/API/Selection_Agent_Paie_Locale.php';
  fetch(url)
      .then(response => response.json())
      .then(donnees => {
        let i = 1; // Initialiser la numérotation

        donnees.forEach(data => {
        var tr = document.createElement("tr");

        var tdnum = document.createElement("td");
        tdnum.textContent = i;

        var tdmatricule = document.createElement("td");
        var tdnom = document.createElement("td");
        var tdpostnom = document.createElement("td");
        var tdprenom = document.createElement("td");
        var tdobs = document.createElement("td");
        

        if (/NU/.test(data.Mat_agent)) {
            tdmatricule.textContent = "NU";
        } else {
            tdmatricule.textContent = data.Mat_agent;
        }
if(val=="Quinzaine"){
        if(data.Quinzaine==="Quinzaine")
        {
            tdnom.textContent = data.Nom_agent;
            tdpostnom.textContent = data.Post_agent;
            tdprenom.textContent = data.Prenom;
            tdobs.textContent = " ";
            
        tr.appendChild(tdnum);
        tr.appendChild(tdmatricule);
        tr.appendChild(tdnom);
        tr.appendChild(tdpostnom);
        tr.appendChild(tdprenom);
        tr.appendChild(tdobs);
   

        tbody.appendChild(tr);
        i++;
  
        }
    }
   else if(val=="Prime_Inst"){
         if(data.Prime_Inst==="Prime_Inst")
            {
                tdnom.textContent = data.Nom_agent;
                tdpostnom.textContent = data.Post_agent;
                tdprenom.textContent = data.Prenom;
                tdobs.textContent = "";
                
            tr.appendChild(tdnum);
            tr.appendChild(tdmatricule);
            tr.appendChild(tdnom);
            tr.appendChild(tdpostnom);
            tr.appendChild(tdprenom);
            tr.appendChild(tdobs);
       
    
            tbody.appendChild(tr);
            i++;
      
            }
   }


      });

      
    }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
          tableQuinzaine.appendChild(tbody);

                 
}

