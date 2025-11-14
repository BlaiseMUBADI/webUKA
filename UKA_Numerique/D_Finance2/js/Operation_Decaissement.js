console.log("✅ JS Décaissement actif");

// 📌 Champs USD
const numeroPieceDecUSD = document.getElementById("numeroPieceDecUSD");
const beneficiaireUSD = document.getElementById("beneficiaireUSD");
const imputationUSD = document.getElementById("ImputationUSD");
const motifUSD = document.getElementById("motifDecaissementUSD");
const montantDecUSD = document.getElementById("montantDecUSD");
const datedec = document.getElementById("dateDecaissementUSD");

// 📌 Champs CDF
const numeroPieceDecCDF = document.getElementById("numeroPieceDecCDF");
const beneficiaireCDF = document.getElementById("beneficiaireCDF");
const imputationCDF = document.getElementById("ImputationCDF");
const motifCDF = document.getElementById("motifDecaissementCDF");
const montantDecCDF = document.getElementById("montantDecCDF");
const dateCDF = document.getElementById("dateDecaissementCDF");

let operation = "";
// 🔁 Gestion des décaissements
document.getElementById("BtnDecaisserUSD").addEventListener("click", function() {
        let Solde_dispo_usd = nettoyerMontant(document.getElementById("solde_USD").innerText);
         operation = "Dec_USD";

        let Num_Pce = numeroPieceDecUSD.value;
        let beneficiaireusd = beneficiaireUSD.value;
        let imputationusd = imputationUSD.value;
        let motifdecusd = motifUSD.value;
        let montantdecusd = montantDecUSD.value;
        let datedecusd = datedec.value;
        

        Swal.fire({
            title: "Voulez-vous enregistrer cette opération ?",
            text: "Une fois enregistrée, elle ne pourra pas être modifiée.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, enregistrer",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                EnregistrementDecaissementUSD(
                    Num_Pce,beneficiaireusd,imputationusd,motifdecusd,montantdecusd,
                    datedecusd,Solde_dispo_usd,operation
                );
            }
        });
    
});


function EnregistrementDecaissementUSD( Num_Pce,beneficiaireusd,imputationusd,motifdecusd,
    montantdecusd,datedecusd,Solde_dispo_usd,operation) 
    {
    const lien = 'D_Finance/API/API_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&beneficiaire=' + encodeURIComponent(beneficiaireusd) +
        '&imputation=' + encodeURIComponent(imputationusd) +
        '&motif=' + encodeURIComponent(motifdecusd) +
        '&montant=' + encodeURIComponent(montantdecusd)+
        '&Date_op=' + encodeURIComponent(datedecusd)+
        '&solde=' + encodeURIComponent(Solde_dispo_usd)+
        '&operation='+encodeURIComponent(operation);

    fetch(lien)
        .then(response => response.json())
        .then(data => {
            console.log("✅ Réponse API :", data);
            if (data.success) {
                Swal.fire("✅ Succès", "Décaissement effectué avec succès", "success")
                .then(() => {
                // Mise à jour du numéro de pièce
                if (data.NumeroPieceSuivant) {
                    document.getElementById("numeroPieceDecUSD").innerText=data.NumeroPieceSuivant;
                    //num_pce.value = data.NumeroPieceSuivant;
                    console.log("🔢 Prochain numéro de pièce appliqué :", data.NumeroPieceSuivant);
                }

                // Vider les champs après l'enregistrement
                ImpressionBondeSortie(Num_Pce, montantdecusd, devise, motifdecusd, datedecusd,
                    beneficiaireusd,imputationusd);

              AfficherSoldes();
            });
            } else if (data.error) {
                Swal.fire("❌ Erreur: Décaissement non effectué", data.message || "Une erreur est survenue.", "error");
            } else {
                Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
            }
        })
        .catch(error => {
            console.error("❌ Erreur de requête :", error);
            Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
        });
}
 

document.getElementById("BtnDecaisserCDF").addEventListener("click", function() {
    
    let Solde_dispo_cdf = nettoyerMontant(document.getElementById("solde_CDF").innerText);
    operation = "Dec_CDF";
    let Num_Pcecdf = numeroPieceDecCDF.value;
    let beneficiairecdf = beneficiaireCDF.value;
    let imputationcdf = imputationCDF.value;
    let motifdeccdf = motifCDF.value;
    let montantdeccdf = montantDecCDF.value;
    let datedeccdf = dateCDF.value;

    Swal.fire({
        title: "Voulez-vous enregistrer cette opération ?",
        text: "Une fois enregistrée, elle ne pourra pas être modifiée.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, enregistrer",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            EnregistrementDecaissementUSD(
                Num_Pcecdf,beneficiairecdf,imputationcdf,motifdeccdf,montantdeccdf,
                datedeccdf,Solde_dispo_cdf,operation
            );
            console.log("opération "+operation);
        }
    });

});

function EnregistrementDecaissementCDF(  Num_Pcecdf,beneficiairecdf,imputationcdf,motifdeccdf,montantdeccdf,
    datedeccdf,Solde_dispo_cdf,operation) 
    {

    const lien = 'D_Finance/API/API_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pcecdf) +
        '&beneficiaire=' + encodeURIComponent(beneficiairecdf) +
        '&imputation=' + encodeURIComponent(imputationcdf) +
        '&motif=' + encodeURIComponent(motifdeccdf) +
        '&montant=' + encodeURIComponent(montantdeccdf)+
        '&Date_op=' + encodeURIComponent(datedeccdf)+
        '&solde=' + encodeURIComponent(Solde_dispo_cdf)+
        '&operation='+encodeURIComponent(operation);

    fetch(lien)
        .then(response => response.json())
        .then(data => {
            console.log("✅ Réponse API :", data);
            if (data.success) {
                Swal.fire("✅ Succès", "Décaissement effectué avec succès", "success")
                .then(() => {
                // Mise à jour du numéro de pièce
                if (data.NumeroPieceSuivant) {
                    document.getElementById("numeroPieceDecCDF").innerText=data.NumeroPieceSuivant;
                    //num_pce.value = data.NumeroPieceSuivant;
                    console.log("🔢 Prochain numéro de pièce appliqué :", data.NumeroPieceSuivant);
                }
           
                // Vider les champs après l'enregistrement
                ImpressionBondeSortie(Num_Pcecdf, montantdeccdf, devise, motifdeccdf, datedeccdf,beneficiairecdf,imputationcdf);
                AfficherSoldes();
                viderChamps();

            });
            } else if (data.error) {
                Swal.fire("❌ Erreur: Décaissement non effectué", data.message || "Une erreur est survenue.", "error");
            } else {
                Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
            }
        })
        .catch(error => {
            console.error("❌ Erreur de requête :", error);
            Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
        });
}
function viderChamps() {
    // Réinitialiser les champs textuels
    document.getElementById("numeroPieceDecCDF").value = "";
    document.getElementById("beneficiaireCDF").value = "";
    document.getElementById("motifDecaissementCDF").value = "";
    document.getElementById("montantDecCDF").value = "";

    // Réinitialiser les selects
    document.getElementById("ImputationCDF").selectedIndex = 0; // Première option
    
    // Appels des fonctions de manière asynchrone avec Promise
   
    }

function nettoyerMontant(montantString) {
    if (!montantString) return 0;
    return parseFloat(montantString.replace(/[$,\s]/g, ""));
}

async function AfficherSoldes() {
    try {
        let champSoldeFinalCDF = document.getElementById("soldeFinalCDF");
        let champSoldeFinalUSD = document.getElementById("soldeFinalUSD");

        const response = await fetch("D_Finance/API/API_Select_Solde.php");
        const data = await response.json();
        console.log("🔍 Résultat JSON brut :", data);
        
        let solde_usd = parseFloat(data.Solde_usd.replace(/,/g, '')) || 0;
        let solde_dec_usd = parseFloat(data.Solde__dec_usd.replace(/,/g, '')) || 0;
        let solde_usd_montant = solde_usd - solde_dec_usd;

        let solde_cdf = parseFloat(data.Solde_cdf.replace(/,/g, '')) || 0;
        let solde_dec_cdf = parseFloat(data.Solde__dec_cdf.replace(/,/g, '')) || 0;
        let solde_cdf_montant = solde_cdf - solde_dec_cdf;

        console.log("le solde après différence est de ",solde_dec_usd);
        console.log("le solde usd ",solde_usd);
        console.log("le solde dec est usd",solde_usd_montant);
        document.getElementById("solde_CDF").innerText = solde_cdf_montant + " Fc";
        document.getElementById("solde_USD").innerText = solde_usd_montant + " $";
        document.getElementById("DecaissementUSD").innerText = data.Solde__dec_usd + " $";
        document.getElementById("DecaissementCDF").innerText = data.Solde__dec_cdf + " Fc";
        document.getElementById("DecaissementUSD_jour").innerText = data.Solde__dec_usd_jr + " $";
        document.getElementById("DecaissementCDF_jour").innerText = data.Solde__dec_cdf_jr + " Fc";
        champSoldeFinalCDF.value=solde_cdf_montant;
        champSoldeFinalUSD.value=solde_usd_montant;
        
    } catch (error) {
        console.error("❌ Erreur lors du chargement du solde CDF :", error);
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    AfficherSoldes();
    //afficherSolde_decaissement();
});

// Exemple de modification pour afficherSolde_decaissement
function afficherSolde_decaissement() {
    fetch("D_Finance/API/API_Solde_Decaisse.php")
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            console.log("🔍 Résultat JSON brut :", data);

           // let solde_permanant=
            document.getElementById("DecaissementUSD").innerText = data.Solde__dec_usd + " $";
            document.getElementById("DecaissementCDF").innerText = data.Solde__dec_cdf + " Fc";

            console.log("💰 Le solde décaissement USD :", data.Solde__dec_usd);
        })
        .catch(function(error) {
            console.error("❌ Erreur lors du chargement du solde :", error);
        });
}

//GESTION DE LA SAISIE DU MONTANT POUR EVITER LES LETTRES
const champMontan = document.getElementById('montantDecUSD');
const msgErreur = document.getElementById('erreur');

champMontan.addEventListener('input', function () {
    const valeurActuelle = this.value;

    // Vérification si la saisie contient des lettres ou caractères non autorisés
    const containsInvalidChars = /[^0-9.]/.test(valeurActuelle);

    if (containsInvalidChars) {
        msgErreur.style.display = 'block';
    } else {
        msgErreur.style.display = 'none';
    }
        // Nettoyer la valeur (supprimer tous les caractères non numériques sauf le point)
        const valeurNettoyee = valeurActuelle.replace(/[^0-9.]/g, '');

        // Ne garder qu’un seul point décimal
        const parts = valeurNettoyee.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts.slice(1).join('');
        } else {
            this.value = valeurNettoyee;
        }
   
});
//GESTION DE LA SAISIE DU MONTANT POUR EVITER LES LETTRES
const champMontandec = document.getElementById('montantDecCDF');
const msgErreurdec = document.getElementById('erreurdDecCDF');

champMontandec.addEventListener('input', function () {
    const valeurActuelle = this.value;

    // Vérification si la saisie contient des lettres ou caractères non autorisés
    const containsInvalidChars = /[^0-9.]/.test(valeurActuelle);

    if (containsInvalidChars) {
        msgErreurdec.style.display = 'block';
    } else {
        msgErreurdec.style.display = 'none';
    }

        // Nettoyer la valeur (supprimer tous les caractères non numériques sauf le point)
        const valeurNettoyee = valeurActuelle.replace(/[^0-9.]/g, '');

        // Ne garder qu’un seul point décimal
        const parts = valeurNettoyee.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts.slice(1).join('');
        } else {
            this.value = valeurNettoyee;
        }
});

function ImpressionBondeSortie(num_pce, montant, devise, motif, date,ben,imputation) {
               
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





                let montantEnLettre="";
            // Écouteur d'événement sur l'input
            document.getElementById("montantDecCDF").addEventListener("input", function () {
                let val = parseFloat(this.value); // Utilisation de parseFloat pour permettre des montants avec des décimales
                if (!isNaN(val)) {
                    // Utilisation de la fonction avec la devise et conversion en lettres
                    document.getElementById("lettresDecCDF").innerText = MontantenLettres(val, devise); // Changez "USD" en fonction de la devise
                montantEnLettre= document.getElementById("lettresDecCDF").innerText;
                console.log("le montant en lettre est ",montantEnLettre);
                
                } else {
                    document.getElementById("lettresDecCDF").innerText = "";
                }
            });
            document.getElementById("montantDecUSD").addEventListener("input", function () {
                let val = parseFloat(this.value); // Utilisation de parseFloat pour permettre des montants avec des décimales
                if (!isNaN(val)) {
                    // Utilisation de la fonction avec la devise et conversion en lettres
                    document.getElementById("lettresDecUSD").innerText = MontantenLettres(val, devise); // Changez "USD" en fonction de la devise
                montantEnLettre= document.getElementById("lettresDecUSD").innerText;
                console.log("le montant en lettre est ",montantEnLettre);
                
                } else {
                    document.getElementById("lettresDecUSD").innerText = "";
                }
            });