document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ JS sélection des décaissements");
  
    // 📌 Champs USD
    const date1 = document.getElementById("dateDec1");
    const date2 = document.getElementById("dateDec2");
    const encaissement = document.getElementById("decaissementSelect");
  
    // 🔧 Champs du modal
    const modalEdit = document.getElementById("modalEdit");
    const editForm = document.getElementById("editForm");
    const editBeneficiaire = document.getElementById("editBeneficiaire");
    const editMotif = document.getElementById("editMotif");
    const editMontant = document.getElementById("editMontant");
    const editDate = document.getElementById("editDate");
    const editNumeroPce = document.getElementById("editNumeroPce");
    const cancelEdit = document.getElementById("cancelEdi");
    const editBtnGlobal = document.getElementById("editer");
  
    let rowBeingEdited = null;
  
    // Initialiser les dates à aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    if (date1) date1.value = today;
    if (date2) date2.value = today;
  
    // Bouton Annuler
    if (cancelEdit) {
        cancelEdit.addEventListener("click", function () {
          if (modalEdit) modalEdit.style.display = "none";
          rowBeingEdited = null;
        });
      }
  
    // Écouteurs
    if (encaissement) {
      encaissement.addEventListener('change', AfficherDecaissement);
    }
    if (date1) date1.addEventListener("change", AfficherDecaissement);
    if (date2) date2.addEventListener("change", AfficherDecaissement);
  
    if (editForm) {
      editForm.addEventListener("submit", function (e) {
        e.preventDefault();
        if (rowBeingEdited) {
          const tds = rowBeingEdited.getElementsByTagName("td");
          tds[1].textContent = editBeneficiaire.value;
          tds[2].textContent = editMotif.value;
          tds[3].textContent = editMontant.value;
          tds[4].textContent = editDate.value;
          tds[5].textContent = editNumeroPce.value;
  
          modalEdit.style.display = "none";
          rowBeingEdited = null;
        }
      });
    }
  
    if (editBtnGlobal) {
      editBtnGlobal.addEventListener('click', function () {
        let Num_Pce = editNumeroPce.value;
        let MotifVersementUSD = editMotif.value;
        let Montant_USD = editMontant.value;
        let Date_vers_USD = editDate.value;
        let Beneficiaire_usd = editBeneficiaire.value;
        let type_oper = "modifier";
  
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
            editionEncaissement(Num_Pce, MotifVersementUSD, Montant_USD, Date_vers_USD, Beneficiaire_usd, type_oper);
          }
        });
      });
    }
  
    // Afficher les décaissements au chargement
    AfficherDecaissement();
  
    // FONCTIONS
  
    function AfficherDecaissement() {
      let Date1 = date1.value;
      let Date2 = date2.value;
      let type_oper = encaissement.value;
      let TabListeEncaissement = document.getElementById("tableEncaissement");
  
      while (TabListeEncaissement.firstChild) {
        TabListeEncaissement.removeChild(TabListeEncaissement.firstChild);
      }
  
      let thead = document.createElement("thead");
      thead.classList.add("sticky-sm-top", "m-0", "fw-bold");
  
      let tr1 = document.createElement("tr");
      tr1.style = "background-color:midnightblue; color:white;";
  
      let headers = ["N°", "Imputation","Béneficiaire", "Motif décaissement", "Montant", "Date opération", "Numéro pce","Bon de sortie","Modifier","Supprimer"];
      headers.forEach(headerText => {
        let th = document.createElement("th");
        th.textContent = headerText;
        tr1.appendChild(th);
      });
  
      thead.appendChild(tr1);
      TabListeEncaissement.appendChild(thead);
  
      let tbody = document.createElement("tbody");
      let url = 'D_Finance/API/API_Select_Operation_Decaissement.php?date1=' + Date1 + '&date2=' + Date2 + '&type=' + type_oper;
      let totalMontant = 0;
      let i = 1;
  
      fetch(url)
        .then(response => response.json())
        .then(data => {
          data.forEach(infos => {
            let tr = document.createElement("tr");
            let tdnum = document.createElement("td");
            let tdimputation = document.createElement("td");
            let tdben = document.createElement("td");
            let tdmotif = document.createElement("td");
            let tdmontant = document.createElement("td");
            let tddate = document.createElement("td");
            let tdnumpce = document.createElement("td");
  
            tdnum.textContent = i;
            tdimputation.textContent = infos.Imputation;
            tdben.textContent = infos.Beneficiaire;
            tdmotif.textContent = infos.Motif;
            tdmontant.textContent = infos.Montant;
            tddate.textContent = infos.Date_Oper;
            tdnumpce.textContent = infos.Num_piece.replace(/\D/g, '');
  
            totalMontant += parseFloat(infos.Montant);
  
 // 🔧 Réimpression du reçu de versement
        var tdrecu = document.createElement("td");
        var recuBtn = document.createElement("button");
        recuBtn.innerHTML = "🖨️"; // ou utilise une image : <img src='img/print-icon.png' width='20'>
        recuBtn.title = "Réimprimer le bon de sortie";
        recuBtn.style.cursor = "pointer";
        recuBtn.classList.add("btn", "btn-light", "btn-sm");
        recuBtn.classList.add("edit-btn");
        recuBtn.addEventListener("click", function () {
          recuBtn.addEventListener("click", function () {
              const num_pce = tdnumpce.textContent;
              const montant = parseFloat(tdmontant.textContent.replace(/[^\d.-]/g, ''));
              const devise = type_oper === "USD" ? "USD" : type_oper === "CDF" ? "CDF" : "EUR";
              const motif = tdmotif.textContent;
              const date = tddate.textContent.split(' ')[0];
              const ben = tdben.textContent;
              const imputation = tdimputation.textContent;

              const montantLettres = MontantenLettres(montant, devise);
              montantEnLettre = montantLettres; // met à jour la variable globale utilisée dans ImpressionReçuVersement

              ImpressionBondeSortie(num_pce, montant, devise, motif, date, ben, imputation);
            });

          
        });
        tdrecu.appendChild(recuBtn);
/////////////////////////////////////

            // Modifier
            let tdEdit = document.createElement("td");
            let editBtn = document.createElement("button");
            editBtn.innerHTML = "✏️";
            // Désactiver le bouton
            editBtn.disabled = true;
            editBtn.classList.add("edit-btn");
            editBtn.addEventListener("click", function () {
              editBeneficiaire.value = tdben.textContent;
              editMotif.value = tdmotif.textContent;
              editMontant.value = tdmontant.textContent;
              editDate.value = tddate.textContent;
              editNumeroPce.value = tdnumpce.textContent;
              rowBeingEdited = tr;
              modalEdit.style.display = "flex";
            });
            tdEdit.appendChild(editBtn);
  
            // Supprimer
            let tdDelete = document.createElement("td");
            let deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "❌";
            // Désactiver le bouton
            deleteBtn.disabled = true;
            deleteBtn.classList.add("delete-btn");
            deleteBtn.addEventListener("click", function () {
              let Num_Pce = tdnumpce.textContent;
              let type_oper = "statut";
  
              Swal.fire({
                title: "Voulez-vous vraiment Annuler cette opération ?",
                text: "Je m'engage",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, Confirmer",
                cancelButtonText: "Abandonner"
              }).then((result) => {
                if (result.isConfirmed) {
                  Annuler_Operation(Num_Pce, type_oper);
                }
              });
            });
            tdDelete.appendChild(deleteBtn);
  
            tr.appendChild(tdnum);
            tr.appendChild(tdimputation);
            tr.appendChild(tdben);
            tr.appendChild(tdmotif);
            tr.appendChild(tdmontant);
            tr.appendChild(tddate);
            tr.appendChild(tdnumpce);
            tr.appendChild(tdrecu);
            tr.appendChild(tdEdit);
            tr.appendChild(tdDelete);
  
            tbody.appendChild(tr);
            i++;
          });
          // ➕ Ajout de la ligne du total dans la colonne "Montant" uniquement
const totalRow = document.createElement("tr");
totalRow.style.backgroundColor = "black";
totalRow.style.fontWeight = "bold";
totalRow.style.color = "white";

// Créer des cellules vides pour toutes les colonnes sauf "Montant"
headers.forEach((header, index) => {
  const td = document.createElement("td");
  
  if (header === "Montant") {
    td.textContent = totalMontant.toLocaleString(undefined, { minimumFractionDigits: 2 }) + " " + type_oper;
    td.style.textAlign = "right";
  } else {
    td.textContent = "";
  }

  totalRow.appendChild(td);
});

tbody.appendChild(totalRow);

        })
        .catch(error => {
          console.log("Erreur lors de contacte de l'API Afficher Liste Paie: " + error);
        });
  
      TabListeEncaissement.appendChild(tbody);
    }
  
    function editionEncaissement(Num_Pce, MotifVersementUSD, Montant_USD, Date_vers_USD, Beneficiaire_usd, type_oper) {
      const lien = 'D_Finance/API/API_Select_Operation_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&Motif=' + encodeURIComponent(MotifVersementUSD) +
        '&Montant=' + encodeURIComponent(Montant_USD) +
        '&Date_op=' + encodeURIComponent(Date_vers_USD) +
        '&Beneficiaire=' + encodeURIComponent(Beneficiaire_usd) +
        '&type=' + encodeURIComponent(type_oper);
  
      fetch(lien)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire("✅ Succès", "Modification effectuée avec succès", "success");
            AfficherDecaissement();
            modalEdit.style.display = "none";
          } else {
            Swal.fire("❌ Erreur", data.message || "Une erreur est survenue", "error");
          }
        })
        .catch(error => {
          console.error("❌ Erreur de requête :", error);
          Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
        });
    }
  
    function Annuler_Operation(Num_Pce, type_oper) {
      const lien = 'D_Finance/API/API_Select_Operation_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&type=' + encodeURIComponent(type_oper);
  
      fetch(lien)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire("✅ Succès", "Opération annulée avec succès", "success");
            AfficherDecaissement();
            modalEdit.style.display = "none";
          } else {
            Swal.fire("❌ Erreur", data.message || "Une erreur est survenue", "error");
          }
        })
        .catch(error => {
          console.error("❌ Erreur de requête :", error);
          Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
        });
    }
  
  });
  
  function ImpressionBondeSortie(num_pce, montant, devise, motif, date, ben, imputation) {
               
    const contenus = `
        <html>
        <head>
            <title>Reçu de versement</title>
            <style>
                body { font-family: Perpetua, sans-serif; padding: 20px; }
                h4 { text-align: center; }
                p { text-align: center; }
               
                .signature { margin-top: 40px; text-align: right; }

                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #000;
                    padding-bottom: 0px;
                    margin-bottom: 0px;
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
            font-size: 40px;
            font-weight: bold;
            padding: 0px 0px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
            .signature-section {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                text-align: center;
            }

            .signature-section .column {
                flex: 1;
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

            <h4>BON DE SORTIE EN - ${devise} - N°: ${num_pce} </h4>
           
            <div class="border border-secondary" style="text-align: right;">
                <span class="montant-fond-chiffre"> ${montant} ${devise}</span>
            </div></br>
            Je sousigné<b> ${ben}</b>, reconnais avoir reçu de la caisse U.KA. la somme de (toutes lettres):
           <span class="montant-fond">${montantEnLettre}</span></br>
           Motif : ${motif}

            <div class="signature-section">
                <div class="column">
                    <p><strong>Signature Bénéficiaire</strong><br>${ben}</p>
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

    const fenetreImpressionBondeSortie = window.open('', '', 'width=700,height=500');
    fenetreImpressionBondeSortie.document.open();
    fenetreImpressionBondeSortie.document.write(contenus);
    fenetreImpressionBondeSortie.document.close();
    fenetreImpressionBondeSortie.print();
}

const imgePrechargee = new Image();
imgePrechargee.src = "D_Finance/img/fond-recu.jpg"; 



 devise = null;

const mapDevise = {
    collapseDecaissementUSD: "USD",
    collapseDecaissementCDF: "CDF",
    collapseDecaissementEUR: "EUR"
};

Object.keys(mapDevise).forEach(id => {
const el = document.getElementById(id);
if (el) {
el.addEventListener("shown.bs.collapse", function () {
    devise = mapDevise[id];
    console.log("Devise active :", devise);
});
}
});


const libellesDevises = {
    USD: { singulier: "dollar américain", pluriel: "dollars américains", centime: "centime", centimes: "centimes" },
    CDF: { singulier: "franc congolais", pluriel: "francs congolais", centime: "centime", centimes: "centimes" },
    EUR: { singulier: "euro", pluriel: "euros", centime: "centime", centimes: "centimes" },
};

function MontantenLettres(nombre, devise) {
    const entier = Math.floor(nombre);
    const decimal = Math.round((nombre - entier) * 100);

    // Choisir l'unité en fonction de la devise et du montant
    const unit = (entier === 1) ? libellesDevises[devise].singulier : libellesDevises[devise].pluriel;
    const centime = (decimal === 1) ? libellesDevises[devise].centime : libellesDevises[devise].centimes;

    let texte = en_Lettres(entier) + " " + unit;
    if (decimal > 0) {
        texte += " et " + en_Lettres(decimal) + " " + centime;
    }
    return texte;
}

function en_Lettres(n) {
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
            if (ten === 7 || ten === 9) {
                str += tens[ten - 1] + "-" + ones[10 + one];
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