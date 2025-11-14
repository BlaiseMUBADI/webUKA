 console.log("✅ JS sélection des encaissements");

// 📌 Champs USD
//const critere = document.getElementById("critere");
const date1 = document.getElementById("date1");
const date2 = document.getElementById("date2");
const encaissement = document.getElementById("encaissementSelect");

// 🔧 Champs du modal
const modalEdit = document.getElementById("modalEdit");
const editForm = document.getElementById("editForm");
const editDeposant = document.getElementById("editDeposant");
const editMotif = document.getElementById("editMotif");
const editMontant = document.getElementById("editMontant");
const editDate = document.getElementById("editDate");
const editNumeroPce = document.getElementById("editNumeroPce");
const cancelEdit = document.getElementById("cancelEdit");

let rowBeingEdited = null;

// Bouton Annuler
cancelEdit.addEventListener("click", function () {
  modalEdit.style.display = "none";
  rowBeingEdited = null;
});

// Écouteur d'événement pour Encaissement USD
document.getElementById('encaissementSelect').addEventListener('change', function () {
  AfficherEncaissement(); 
});

date1.addEventListener("change", function () {
  AfficherEncaissement(); 
});
date2.addEventListener("change", function () {
  AfficherEncaissement(); 
});



function AfficherEncaissement() {
  //let Critere = critere.value;
  let Date1 = date1.value;
  let Date2 = date2.value;
  let type_oper = encaissement.value;

  let TabListeEncaissement = document.getElementById("tableEncaissement");

  while (TabListeEncaissement.firstChild) {
    TabListeEncaissement.removeChild(TabListeEncaissement.firstChild);
  }

  var thead = document.createElement("thead");
  thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

  var tr1 = document.createElement("tr");
  tr1.style = "background-color:midnightblue; color:white;";

  var headers = ["N°", "Déposant","Compte", "Motif", "Montant", "Date opération", "Numéro pce","Reçu","Modifier","Supprimer"];
  headers.forEach(headerText => {
    var th = document.createElement("th");
    th.textContent = headerText;
    tr1.appendChild(th);
  });

  thead.appendChild(tr1);
  TabListeEncaissement.appendChild(thead);

  var tbody = document.createElement("tbody");

  var url = 'D_Finance/API/API_Select_Operation_Encaissement.php?date1=' 
  + Date1 + '&date2=' + Date2 + '&type=' + type_oper;

  var i = 1;
  fetch(url)
    .then(response => response.json())
    .then(data => {
        // ✅ Regrouper les opérations par numéro de pièce
        const groupes = {};
        data.forEach(infos => {
        const numPce = infos.Numero_pce.replace(/[^\d]/g, '');
        if (!groupes[numPce]) groupes[numPce] = [];
        groupes[numPce].push(infos);
        });

        // ✅ Parcourir chaque groupe (chaque numéro de pièce unique)
        Object.keys(groupes).forEach(numPce => {
        const lignes = groupes[numPce];
        const nbLignes = lignes.length;

        lignes.forEach((infos, index) => {
            const tr = document.createElement("tr");

            const tdnum = document.createElement("td");
            tdnum.textContent = i++;

            const tddeposant = document.createElement("td");
            const tdcompte = document.createElement("td");
            const tdmotif = document.createElement("td");
            const tdmontant = document.createElement("td");
            const tddate = document.createElement("td");
            const tdnumpce = document.createElement("td");

            tddeposant.textContent = infos.Deposant;
            tdcompte.textContent = infos.Imputation;
            tdmotif.textContent = infos.Motif;
            tdmontant.textContent = infos.Montant;
            tddate.textContent = infos.Date_Oper.split(' ')[0];
            tdnumpce.textContent = numPce;

            tr.appendChild(tdnum);
            tr.appendChild(tddeposant);
            tr.appendChild(tdcompte);
            tr.appendChild(tdmotif);
            tr.appendChild(tdmontant);
            tr.appendChild(tddate);
            tr.appendChild(tdnumpce);

            // ✅ Fusionner uniquement la cellule "Reçu"
            if (index === 0) {
            const tdrecu = document.createElement("td");
            tdrecu.rowSpan = nbLignes;
            tdrecu.style.verticalAlign = "middle";
            tdrecu.style.textAlign = "center";

            const recuBtn = document.createElement("button");
            recuBtn.innerHTML = "🖨️";
            recuBtn.title = "Réimprimer le reçu";
            recuBtn.classList.add("btn", "btn-light", "btn-sm");
            recuBtn.style.cursor = "pointer";

            recuBtn.addEventListener("click", function () {
                // 🧩 Prendre toutes les lignes du même groupe (même numéro de pièce)
                const premier = lignes[0];
                const devise = type_oper === "USD" ? "USD" : type_oper === "CDF" ? "CDF" : "EUR";

                // 🧮 Calcul du montant total cumulé
                const totalMontant = lignes.reduce((somme, ligne) => {
                    const m = parseFloat(String(ligne.Montant).replace(/[^\d.-]/g, '')) || 0;
                    return somme + m;
                }, 0);

                // 🧾 Concaténer les motifs (éliminer doublons)
                const motifsUniques = [...new Set(lignes.map(l => l.Motif))];
                const motifGlobal = motifsUniques.join(", ");
                // 🧾 Concaténer les motifs (éliminer doublons)
                const CompteUniques = [...new Set(lignes.map(l => l.Imputation))];
                const CompteGlobal = CompteUniques.join("|");
                // ✅ Variables principales
                const date = premier.Date_Oper.split(' ')[0];
                const deposant = premier.Deposant;
               

                // ✍️ Montant en lettres
                const montantLettres = enLettresMontant(totalMontant, devise);
                montantEnLettres = montantLettres;

                // 🖨️ Impression du reçu global
                ImpressionReçuVersement(numPce, totalMontant, devise, motifGlobal, date, deposant, CompteGlobal);
            });


            tdrecu.appendChild(recuBtn);
            tr.appendChild(tdrecu);
            }

            // ✅ Colonnes Modifier et Supprimer séparées à chaque ligne
            const tdEdit = document.createElement("td");
            const editBtn = document.createElement("button");
            editBtn.innerHTML = "✏️";
            editBtn.disabled = true;
            editBtn.classList.add("btn", "btn-secondary", "btn-sm");
            tdEdit.appendChild(editBtn);
            tr.appendChild(tdEdit);

            const tdDelete = document.createElement("td");
            const deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "❌";
            deleteBtn.disabled = true;
            deleteBtn.classList.add("btn", "btn-danger", "btn-sm");
            tdDelete.appendChild(deleteBtn);
            tr.appendChild(tdDelete);

            tbody.appendChild(tr);
        });
        });

    })
    .catch(error => {
      console.log("Erreur lors de contacte de l'API Afficher Liste Paie" + error);
    });

  TabListeEncaissement.appendChild(tbody);
}

// Enregistrer la modification
editForm.addEventListener("submit", function (e) {
  e.preventDefault();

  if (rowBeingEdited) {
    var tds = rowBeingEdited.getElementsByTagName("td");
    tds[1].textContent = editDeposant.value;
    tds[2].textContent = editMotif.value;
    tds[3].textContent = editMontant.value;
    tds[4].textContent = editDate.value;
    tds[5].textContent = editNumeroPce.value;

    modalEdit.style.display = "none";
    rowBeingEdited = null;
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const today = new Date().toISOString().split('T')[0];

  const date1 = document.getElementById("date1");
  if (date1) date1.value = today;

  const date2 = document.getElementById("date2");
  if (date2) date2.value = today;
  AfficherEncaissement(); 

  
});
//MODIFICATION DE DONNEES ENCAISSEMENT

document.getElementById('editer').addEventListener('click', function () {
            let Num_Pce = editNumeroPce.value;
            let MotifVersementUSD = editMotif.value;
            
            let Montant_USD = editMontant.value;
            let Date_vers_USD = editDate.value;
            let Deposant_usd = editDeposant.value;
                type_oper="modifier";
            Swal.fire({
                title: "Voulez-vous vraiment confirmer cette opération ?",
                text: "Je m'engage",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, enregistrer",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    editionEncaissement(Num_Pce,MotifVersementUSD,Montant_USD,Date_vers_USD,Deposant_usd,type_oper);
                }
            });
});
//********************* ENVOYER LES NOUVELLES DONNEES POUR MODIFICATION */
function editionEncaissement(Num_Pce,MotifVersementUSD,Montant_USD,Date_vers_USD,Deposant_usd,type_oper) {
  
  const lien = 'D_Finance/API/API_Select_Operation_Encaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
      
      '&Motif=' + encodeURIComponent(MotifVersementUSD) +
      '&Montant=' + encodeURIComponent(Montant_USD) +
      '&Date_op=' + encodeURIComponent(Date_vers_USD)+
      '&Deposant=' + encodeURIComponent(Deposant_usd)+
      '&type=' + encodeURIComponent(type_oper);

  fetch(lien)
      .then(response => response.json())
      .then(data => {
          console.log("✅ Réponse API :", data);
          if (data.success) {
              Swal.fire("✅ Succès", "Modification effectué avec succès", "success");
              AfficherEncaissement(); 
              // Mise à jour du numéro de pièce
              /*if (data.NumeroPieceSuivant) {
                  num_pce.value = data.NumeroPieceSuivant;
                  console.log("🔢 Prochain numéro de pièce appliqué :", data.NumeroPieceSuivant);
              }*/

              // Vider les champs après l'enregistrement
                modalEdit.style.display = "none";
              
          } else if (data.error) {
              Swal.fire("❌ Erreur: Modification non effectuée", data.message || "Une erreur est survenue.", "error");
          } else {
              Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
          }
      })
      .catch(error => {
          console.error("❌ Erreur de requête :", error);
          Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
      });
}
function Cancel_Operation(Num_Pce,type_oper) {
  
  const lien = 'D_Finance/API/API_Select_Operation_Encaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
              '&type=' + encodeURIComponent(type_oper);

  fetch(lien)
      .then(response => response.json())
      .then(data => {
          console.log("✅ Réponse API :", data);
          if (data.success) {
              Swal.fire("✅ Succès", "Opération Annulée Avec Succès", "success");
              AfficherEncaissement(); 
              // Mise à jour du numéro de pièce
              /*if (data.NumeroPieceSuivant) {
                  num_pce.value = data.NumeroPieceSuivant;
                  console.log("🔢 Prochain numéro de pièce appliqué :", data.NumeroPieceSuivant);
              }*/

              // Vider les champs après l'enregistrement
                modalEdit.style.display = "none";
              
          } else if (data.error) {
              Swal.fire("❌ Erreur: Modification non effectuée", data.message || "Une erreur est survenue.", "error");
          } else {
              Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
          }
      })
      .catch(error => {
          console.error("❌ Erreur de requête :", error);
          Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
      });
}

  //********************* FIN**************************************************** */
                function ImpressionReçuVersement(num_pce, montant, devise, motif, date,deposant,imputation) 
            {
                    const imagePrechargee = new Image();
                        imagePrechargee.src = "D_Finance/img/fond-recu.jpg";
                imagePrechargee.onload = function ()
                {
                    const contenu = `
                        <html>
                        <head>
                            <title>Reçu de versement</title>
                            <style>
                                body { font-family: Perpetua, sans-serif; padding: 20px; }
                                h4 { text-align: center; }
                                p { text-align: center; }
                            
                             
                
                                .header {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    border-bottom: 1px solid #000;
                                    padding-bottom: 0px;
                                    margin-bottom: 0px;
                                    margin-top:0px;
                                    
                                }
                
                                .header .text {
                                    flex: 1;
                                    text-align: right;
                                }
                
                                .header img {
                                    height: 70px;
                                    margin-right: 20px;
                                }
                                /* .num_pce {
                                        
                                        background-color:rgb(227, 104, 104);
                                        -webkit-print-color-adjust: exact;  Force la couleur en impression 
                                        print-color-adjust: exact; Fonctionne pour certains navigateurs 
                                    }*/
                                .montant-fond {
                                    background-image: url('D_Finance/img/fond-recu.jpg'); /* Remplace par le chemin correct */
                                    background-size: contain; /* Ajuste la taille pour bien cadrer */
                                    background-repeat: repeat x; /* Empêche la répétition de l’image */
                                    background-position: right center; /* Positionne l’image correctement */
                                    padding: 0px 0px; /* Ajuste l'espacement pour bien intégrer l'image */
                                    font-weight: bold; /* Met le texte en valeur */
                                    -webkit-print-color-adjust: exact; /* Force le fond en impression */
                                    print-color-adjust: exact; /* Fonctionne pour certains navigateurs */
                                }
                        .montant-fond-chiffre {
                            background-image: url('D_Finance/img/fond-recu.jpg');
                            background-size: contain;
                            background-repeat: repeat x;
                            background-position: right center;
                            text-align: right;
                            font-size: 20px;
                            font-weight: bold;
                            padding: 0px 0px;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                            body {
                                    font-family: Perpetua, sans-serif;
                                    margin: 0;
                                    padding: 5px 20px 20px 20px; /* Réduction du padding en haut */
                                }
                            h4 {
                                    text-align: center;
                                    margin-top: 5px; /* Réduit l'espace au-dessus du titre */
                                    margin-bottom: 10px;
                                }  
                                    .signature-section {
                                        display: flex;
                                        justify-content: space-between;
                                        margin-top: 10px;
                                        text-align: center;
                                    }

                                    .signature-section .column {
                                        flex: 1;
                                    }     
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <img src="D_Finance/img/logo.png" alt="Logo">
                                <div class="text">
                                    <p>
                                        République Démocratique du Congo<br>
                                        Ministère de l'Enseignement Supérieur et Universitaire<br>
                                        Université Notre-Dame du Kasayi (U.KA.)
                                    </p>
                                </div>
                            </div>
                
                            <h4>RECU DE VERSEMENT EN - ${devise} - N°: ${num_pce} </h4>
                        
                            <div class="border border-secondary" style="text-align: right;">
                                <span class="montant-fond-chiffre"> ${montant} ${devise}</span>
                            </div></br>
                            Je sousigné<b> ${deposant}</b>, reconnais avoir versé dans la caisse U.KA. la somme de (toutes lettres):
                        <span class="montant-fond">${montantEnLettres}</span></br>
                        Motif : ${motif}
                
                            
                            <div class="signature-section">
                                <div class="column">
                                    <p><strong>Signature déposant</strong><br>${deposant}</p>
                                </div>
                                <div class="column">
                                    <p><strong>Imputation</strong><br>${imputation}</p>
                                </div>
                                <div class="column">
                                    <p><strong>Fait à Kananga, le ${date}</strong><br>Visa du(de la) Caissier(é)</p>
                                </div>
                            </div>
                        </body>
                        </html>
                    `;
                
                    const fenetreImpression = window.open('', '', 'width=700,height=500');
                    fenetreImpression.document.open();
                    fenetreImpression.document.write(contenu);
                    fenetreImpression.document.close();
                    fenetreImpression.print();
                };
            }
        


             const libellesDevise = {
                USD: { singulier: "dollar américain", pluriel: "dollars américains", centime: "centime", centimes: "centimes" },
                CDF: { singulier: "franc congolais", pluriel: "francs congolais", centime: "centime", centimes: "centimes" },
                EUR: { singulier: "euro", pluriel: "euros", centime: "centime", centimes: "centimes" },
            };
    
            function enLettresMontant(nombre, devise) {
                const entier = Math.floor(nombre);
                const decimal = Math.round((nombre - entier) * 100);
    
                // Choisir l'unité en fonction de la devise et du montant
                const unit = (entier === 1) ? libellesDevise[devise].singulier : libellesDevise[devise].pluriel;
                const centime = (decimal === 1) ? libellesDevise[devise].centime : libellesDevise[devise].centimes;
    
                let texte = enLettres(entier) + " " + unit;
                if (decimal > 0) {
                    texte += " et " + enLettres(decimal) + " " + centime;
                }
                return texte;
            }
    
            function enLettres(n) {
    const ones = [
        "", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf",
        "dix", "onze", "douze", "treize", "quatorze", "quinze", "seize",
        "dix-sept", "dix-huit", "dix-neuf"
    ];
    const tens = ["", "", "vingt", "trente", "quarante", "cinquante", "soixante"];
    const scales = ["", "mille", "million", "milliard"];

    if (n === 0) return "zéro";

    let parts = [];
    let scaleIndex = 0;

    while (n > 0) {
        let chunk = n % 1000;
        if (chunk) {
            let chunkText = convertChunk(chunk);
            if (scaleIndex > 0) {
                if (chunk > 1 || scaleIndex === 1) {
                    chunkText += " " + scales[scaleIndex] + (chunk > 1 && scaleIndex > 1 ? "s" : "");
                } else {
                    chunkText += " " + scales[scaleIndex];
                }
            }
            parts.unshift(chunkText.trim());
        }
        n = Math.floor(n / 1000);
        scaleIndex++;
    }

    return parts.join(" ").replace(/\s+/g, " ");

    function convertChunk(n) {
        let str = "";

        let hundreds = Math.floor(n / 100);
        let remainder = n % 100;

        if (hundreds) {
            if (hundreds === 1) str += "cent";
            else str += ones[hundreds] + " cent";
            if (remainder === 0 && hundreds > 1) str += "s";
            str += " ";
        }

        if (remainder < 20) {
            str += ones[remainder];
        } else {
            let ten = Math.floor(remainder / 10);
            let one = remainder % 10;

            if (ten === 8) {
                str += "quatre-vingt" + (one > 0 ? "-" + ones[one] : "");
            } else if (ten === 9) {
                str += "quatre-vingt-" + ones[10 + one];
            } else if (ten === 7) {
                str += "soixante-" + ones[10 + one];
            } else {
                str += tens[ten];
                if (one === 1 && (ten === 1 || ten > 1)) {
                    str += "-et-un";
                } else if (one > 0) {
                    str += "-" + ones[one];
                }
            }
        }

        return str;
    }
}