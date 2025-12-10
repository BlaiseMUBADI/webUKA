console.log("✅ JS Décaissement multiple actif");

// === Fonction principale : Décaissement de toutes les lignes ===
document.addEventListener("DOMContentLoaded", () => {
    chargerSoldes();
    rafraichirNumeros();

    // Ajoute un listener sur chaque bouton "Décaisser"
    document.querySelectorAll("#btnDecaisser").forEach(btn => {
        btn.addEventListener("click", async function () {
            const card = this.closest(".card");
            const numPieceText = card.querySelector(".card-header h5").textContent;
            const numPiece = numPieceText.split(":")[1].split("|")[0].trim();
            const beneficiaire = card.querySelector(".text-warning").textContent.trim();
            const devise = card.dataset.devise.trim();

            const lignes = [];
            card.querySelectorAll("tbody tr").forEach(tr => {
                const tds = tr.querySelectorAll("td");
                if (tds.length >= 5 && !tr.classList.contains("table-secondary")) {
                    lignes.push({
                        motif: tds[1].textContent.trim(),
                        montant: parseFloat(tds[2].textContent.replace(/[^0-9.]/g, "")),
                        imputation: tds[3].textContent.trim(),
                        date: tds[4].textContent.trim()
                    });
                }
            });

            if (lignes.length === 0) {
                Swal.fire({ icon: "info", title: "Aucune ligne", text: "Aucune donnée à décaisser pour cette pièce." });
                return;
            }

            const soldeEl = document.getElementById(devise === "CDF" ? "solde_CDF" : "solde_USD");
            const solde = parseFloat(soldeEl.textContent.replace(/[^0-9.]/g, "")) || 0;

            const numPceDecaissement = document.getElementById(
                devise === "CDF" ? "numeroPieceCDF" : "numeroPieceUSD"
            ).innerText.trim();

            const anneeAcad = document.getElementById("annee").value.trim();

            // Vérifier le total
            const total = lignes.reduce((sum, l) => sum + l.montant, 0);
            if (total > solde) {
                Swal.fire({
                    icon: "warning",
                    title: "Solde insuffisant",
                    text: `Le solde disponible (${solde.toLocaleString()} ${devise}) est inférieur au montant total (${total.toLocaleString()} ${devise}).`
                });
                return;
            }

           Swal.fire({
                        title: "Confirmer le décaissement",
                        html: `<b>Numéro de pièce :</b> ${numPiece}<br>
                            <b>Bénéficiaire :</b> ${beneficiaire}<br>
                            <b>Total :</b> ${total.toLocaleString()} ${devise}<br>
                            <small>(${lignes.length} lignes)</small>`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Oui, décaisser",
                        cancelButtonText: "Annuler",
                        confirmButtonColor: "#28a745",
                        cancelButtonColor: "#d33"
                    }).then(async (res) => {
                        if (!res.isConfirmed) return;

                        try {
                            // 🔹 Numéro de pièce unique pour toutes les lignes (non incrémenté)
                            const numPceDecaissement = document.getElementById(
                                devise === "CDF" ? "numeroPieceCDF" : "numeroPieceUSD"
                            ).innerText.trim();

                            // 🔹 Envoi ligne par ligne à l’API
                            // 🔹 Envoi ligne par ligne à l’API
                            for (const ligne of lignes) {
                                const params = new URLSearchParams({
                                    Num_Pce: numPceDecaissement, // même numéro pour toutes
                                    beneficiaire: beneficiaire,
                                    imputation: ligne.imputation,
                                    motif: ligne.motif,
                                    montant: ligne.montant,
                                    operation: devise === "CDF" ? "Dec_CDF" : "Dec_USD",
                                    solde: solde,
                                    Num_Autoriz: numPiece,
                                    Id_Anne_Acad: anneeAcad
                                });

                                const url = "D_Finance/API/API_Servir.php?" + params.toString();
                                const response = await fetch(url);
                                const text = await response.text();

                                try {
                                    const data = JSON.parse(text);

                                    if (data.error) {
                                        // ❌ Autorisation déjà utilisée ou autre erreur
                                        Swal.fire({
                                            icon: "error",
                                            title: "Décaissement refusé",
                                            text: data.message
                                        });
                                        // Stoppe tout traitement immédiatement
                                        return;
                                    } else if (data.success) {
                                        console.log("✅ Ligne décaissement OK :", data.num_piece);
                                    }

                                } catch (err) {
                                    console.error("Réponse non JSON :", text);
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erreur système",
                                        text: "Impossible de traiter la réponse de l'API."
                                    });
                                    return; // Stoppe en cas d'erreur JSON
                                }
                            }


                           Swal.fire({
                                        icon: "success",
                                        title: "Décaissement effectué",
                                        text: `Toutes les lignes de la pièce ${numPiece} ont été enregistrées avec le même numéro de pièce (${numPceDecaissement}).`,
                                        confirmButtonText: "🧾 Imprimer le bon",
                                        confirmButtonColor: "#28a745"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const date = new Date().toLocaleDateString("fr-FR");
                                            const imputationsConcat = lignes.map(l => l.imputation).join(" | ");
                                            // 🧾 Impression du bon une fois confirmée
                                            ImpressionBondeSortie(
                                                numPceDecaissement,     // Numéro de pièce
                                                total,                  // Total brut (nombre)
                                                devise,                 // Devise (CDF ou USD)
                                                lignes.map(l => l.motif).join(", "), // Tous les motifs
                                                date,                   // Date du jour
                                                beneficiaire,           // Nom du bénéficiaire
                                                imputationsConcat       // Toutes les imputations séparées
                                            );

                                            // 🔄 Mise à jour après impression
                                            chargerSoldes();
                                            rafraichirNumeros();
                                        }
                                    });



                        } catch (err) {
                            console.error("Erreur lors du décaissement :", err);
                            Swal.fire({
                                icon: "error",
                                title: "Erreur système",
                                text: "Impossible d’enregistrer le décaissement."
                            });
                        }
                    });

        });
    });
});

// === Rafraîchir les soldes ===
async function chargerSoldes() {
    try {
        const res = await fetch('D_Finance/API/API_Select_Solde.php');
        const data = await res.json();
        const soldeCDF = parseFloat(data.Solde_cdf.replace(/,/g, '')) - parseFloat(data.Solde__dec_cdf.replace(/,/g, ''));
        const soldeUSD = parseFloat(data.Solde_usd.replace(/,/g, '')) - parseFloat(data.Solde__dec_usd.replace(/,/g, ''));

        document.getElementById('solde_CDF').innerText = soldeCDF.toLocaleString() + " CDF";
        document.getElementById('solde_USD').innerText = soldeUSD.toLocaleString() + " USD";
    } catch (err) {
        console.error("Erreur lors du chargement des soldes :", err);
    }
}

// === Rafraîchir les numéros de pièces ===
function rafraichirNumeros() {
    fetch("D_Finance/API/Actualiser_Num_pieces.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("numeroPieceCDF").innerText = data.numDecCDF || '-';
                document.getElementById("numeroPieceUSD").innerText = data.numDecUSD || '-';
            }
        })
        .catch(err => console.error("Erreur lors du rafraîchissement des numéros :", err));
}


function ImpressionBondeSortie(num_pce, montant, devise, motif, date, ben, imputation) {
    // Conversion du montant en lettres
    const montantEnLettre = MontantenLettres(montant, devise);

    const contenus = `
        <html>
        <head>
            <title>Reçu de décaissement</title>
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

                .montant-fond {
                    background-image: url('D_Finance/img/fond-recu.jpg');
                    background-size: contain;
                    background-repeat: repeat-x;
                    background-position: right center;
                    padding: 0px 0px;
                    font-weight: bold;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .montant-fond-chiffre {
                    background-image: url('D_Finance/img/fond-recu.jpg');
                    background-size: contain;
                    background-repeat: repeat-x;
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
                    margin: 0;
                    padding: 5px 20px 20px 20px;
                }

                h4 {
                    margin-top: 5px;
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

            <h4>BON DE SORTIE EN - ${devise} - N°: ${num_pce}</h4>

            <div class="border border-secondary" style="text-align: right;">
                <span class="montant-fond-chiffre">${montant.toLocaleString()} ${devise}</span>
            </div></br>

            Je soussigné <b>${ben}</b>, reconnais avoir reçu de la caisse U.KA. la somme de (toutes lettres) :
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

    const fenetreImpression = window.open('', '', 'width=700,height=500');
    fenetreImpression.document.open();
    fenetreImpression.document.write(contenus);
    fenetreImpression.document.close();
    fenetreImpression.focus();
    fenetreImpression.print();
}

const imgePrechargee = new Image();
imgePrechargee.src = "D_Finance/img/fond-recu.jpg"; 


const libellesDevises = {
    USD: { singulier: "dollar américain", pluriel: "dollars américains", centime: "centime", centimes: "centimes" },
    CDF: { singulier: "franc congolais", pluriel: "francs congolais", centime: "centime", centimes: "centimes" },
    //EUR: { singulier: "euro", pluriel: "euros", centime: "centime", centimes: "centimes" },
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
