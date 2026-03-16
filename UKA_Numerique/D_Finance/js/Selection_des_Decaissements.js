document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ JS sélection des décaissements");
  
    // 📌 Champs USD
    const date1 = document.getElementById("dateDec1");
    const date2 = document.getElementById("dateDec2");
    const decaissement = document.getElementById("decaissementSelect");
  
    // 🔧 Champs du modal
    const editId = document.getElementById("editId");
    const editBeneficiaire = document.getElementById("editBen");
    const editMotif = document.getElementById("editMotif");
    const editMontant = document.getElementById("editMontant");
    const editDate = document.getElementById("editDate");
    const editNumeroPce = document.getElementById("editNumeroPce");
    const modalEdit = document.getElementById("modalEdit");
    modalEditInstance = new bootstrap.Modal(modalEdit);
    let rowBeingEdited = null;
  

    // Initialiser les dates à aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    if (date1) date1.value = today;
    if (date2) date2.value = today;
  
    
  
    // Écouteurs
      document.getElementById('decaissementSelect').addEventListener('change', function () {
        AfficherDecaissement(); 
      });

      date1.addEventListener("change", function () {
        AfficherDecaissement(); 
      });
      date2.addEventListener("change", function () {
        AfficherDecaissement(); 
      });

    function formatForDateTimeLocal(mysqlDateTime) {
    // mysqlDateTime = "2026-02-09 14:35:00"
    return mysqlDateTime.replace(' ', 'T').slice(0, 16);
}
  
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
  
          
          rowBeingEdited = null;
        }
      });
    }
  
    document.getElementById('editer').addEventListener('click', function () {
        let Num_Pce = editNumeroPce.value;
        let Id_op = editId.value;
        let MotifDecaissement = editMotif.value;
        let Montant_Decaissement = editMontant.value;
        //let Date_Decaissement = editDate.value;
        let Date_Decaissement = editDate.value.replace('T', ' ') + ':00';

        let Beneficiaire = editBeneficiaire.value;
        let Dev=decaissement.value;
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
            ModidfierDecaissement(Num_Pce,Id_op, MotifDecaissement, Montant_Decaissement, Date_Decaissement, Beneficiaire, Dev, type_oper);
          }
        });
      });
    
  
    // Afficher les décaissements au chargement
    AfficherDecaissement();
  
    // FONCTIONS
  
    function AfficherDecaissement() {
      
      let Date1 = date1.value;
      let Date2 = date2.value;
      let type_oper = decaissement.value;

      let TabListeDecaissement = document.getElementById("tableDecaissement");

      // Nettoyer le tableau avant d'afficher les nouvelles données
      while (TabListeDecaissement.firstChild) {
        TabListeDecaissement.removeChild(TabListeDecaissement.firstChild);
      }

      // Création de l'en-tête du tableau
      var thead = document.createElement("thead");
      thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

      var tr1 = document.createElement("tr");
      tr1.style = "background-color:darkred; color:white;";

      var headers = ["N°","Id", "Imputation", "Bénéficiaire", "Motif", "Montant", "Date opération", "Numéro pièce", "Bon de sortie", "Modifier", "Supprimer"];
      headers.forEach(headerText => {
        var th = document.createElement("th");
        th.textContent = headerText;
        tr1.appendChild(th);
      });

      thead.appendChild(tr1);
      TabListeDecaissement.appendChild(thead);

      var tbody = document.createElement("tbody");

      var url = 'D_Finance/API/API_Select_Operation_Decaissement.php?date1=' + Date1 + '&date2=' + Date2 + '&type=' + type_oper;

  var i = 1;
  fetch(url)
    .then(response => response.json())
    .then(data => {
      // ✅ Regrouper les opérations par numéro de pièce
      const groupes = {};
      data.forEach(infos => {
        const numPce = infos.Num_piece.replace(/[^\d]/g, '');
        if (!groupes[numPce]) groupes[numPce] = [];
        groupes[numPce].push(infos);
      });

      // ✅ Parcourir chaque groupe de numéro de pièce
      Object.keys(groupes).forEach(numPce => {
        const lignes = groupes[numPce];
        const nbLignes = lignes.length;

        lignes.forEach((infos, index) => {
          const tr = document.createElement("tr");

          const tdnum = document.createElement("td");
          tdnum.textContent = i++;

          const id = document.createElement("td");
          const tdimputation = document.createElement("td");
          const tdbenef = document.createElement("td");
          const tdmotif = document.createElement("td");
          const tdmontant = document.createElement("td");
          const tddate = document.createElement("td");
          const tdnumpce = document.createElement("td");

          id.textContent = infos.Id;
          tdimputation.textContent = infos.Imputation;
          tdbenef.textContent = infos.Beneficiaire;
          tdmotif.textContent = infos.Motif;
          tdmontant.textContent = infos.Montant;
          tddate.textContent = infos.Date_Oper;//.split(' ')[0];  🔹 Enlever l'heure
          tdnumpce.textContent = numPce;

          tr.appendChild(tdnum);
          tr.appendChild(id);
          tr.appendChild(tdimputation);
          tr.appendChild(tdbenef);
          tr.appendChild(tdmotif);
          tr.appendChild(tdmontant);
          tr.appendChild(tddate);
          tr.appendChild(tdnumpce);

          // ✅ Fusionner uniquement la cellule "Bon de sortie"
          if (index === 0) {
            const tdrecu = document.createElement("td");
            tdrecu.rowSpan = nbLignes;
            tdrecu.style.verticalAlign = "middle";
            tdrecu.style.textAlign = "center";

            const recuBtn = document.createElement("button");
            recuBtn.innerHTML = "🖨️";
            recuBtn.title = "Imprimer le bon de sortie";
            recuBtn.classList.add("btn", "btn-light", "btn-sm");
            recuBtn.style.cursor = "pointer";

            recuBtn.addEventListener("click", function () {
              // 🧩 Prendre toutes les lignes du même groupe
              const premier = lignes[0];
              const devise = type_oper === "USD" ? "USD" : type_oper === "CDF" ? "CDF" : "EUR";

              // 🧮 Calcul du montant total cumulé
              const totalMontant = lignes.reduce((somme, ligne) => {
                const m = parseFloat(String(ligne.Montant).replace(/[^\d.-]/g, '')) || 0;
                return somme + m;
              }, 0);

              // 🧾 Concaténer les motifs et imputations sans doublon
              const motifsUniques = [...new Set(lignes.map(l => l.Motif))];
              const motifGlobal = motifsUniques.join(", ");

              const imputationsUniques = [...new Set(lignes.map(l => l.Imputation))];
              const imputationGlobal = imputationsUniques.join(" | ");

              // ✅ Variables principales
              const date = premier.Date_Oper.split(' ')[0];
              const beneficiaire = premier.Beneficiaire;

              // 📝 Conversion en lettres
              const montantLettres = MontantenLettres(totalMontant, devise);
              montantEnLettre = montantLettres;

              // 🖨️ Impression du bon global
              ImpressionBondeSortie(numPce, totalMontant, devise, motifGlobal, date, beneficiaire, imputationGlobal);
            });

            tdrecu.appendChild(recuBtn);
            tr.appendChild(tdrecu);
          }

              // ✅ Boutons Modifier et Supprimer
              const tdEdit = document.createElement("td");
              const editBtn = document.createElement("button");
              editBtn.innerHTML = "✏️";
              editBtn.disabled = false;
              editBtn.classList.add("btn", "btn-secondary", "btn-sm");
              tdEdit.appendChild(editBtn);
              tr.appendChild(tdEdit);

              editBtn.addEventListener("click", function () {
                    rowBeingEdited = tr;

                    editId.value = infos.Id;
                    editBeneficiaire.value = infos.Beneficiaire;
                    editMotif.value = infos.Motif;
                    editMontant.value = infos.Montant;
                    editDate.value = formatForDateTimeLocal(infos.Date_Oper);
                    editNumeroPce.value = numPce;
                    modalEditInstance.show();
                    });



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
          console.log("Erreur lors de la récupération des décaissements :", error);
        });

      TabListeDecaissement.appendChild(tbody);
    }
  
    //********************* ENVOYER LES NOUVELLES DONNEES POUR MODIFICATION */

function ModidfierDecaissement(Num_Pce,Id,MotifDecaissement,Montant_Decaissement,Date_Decaissement,Beneficiaire_Dec,Dev,type_oper) 
  {
  
    const lien = 'D_Finance/API/Modifier_Op_Dec.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
      
      '&Motif=' + encodeURIComponent(MotifDecaissement) +
      '&Montant=' + encodeURIComponent(Montant_Decaissement) +
      '&Date_op=' + encodeURIComponent(Date_Decaissement)+
      '&Ben=' + encodeURIComponent(Beneficiaire_Dec)+
      '&dev=' + encodeURIComponent(Dev)+
      '&type=' + encodeURIComponent(type_oper)+
      '&id_op=' + encodeURIComponent(Id);

    fetch(lien)
      .then(response => response.json())
      .then(data => {
          console.log("✅ Réponse API :", data);
          if (data.success) {
              Swal.fire("✅ Succès", "Modification effectué avec succès", "success");
              AfficherDecaissement(); 
           
              
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
  
    function Annuler_Operation(Num_Pce, type_oper) {
      const lien = 'D_Finance/API/API_Select_Operation_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&type=' + encodeURIComponent(type_oper);
  
      fetch(lien)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire("✅ Succès", "Opération annulée avec succès", "success");
            AfficherDecaissement();
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