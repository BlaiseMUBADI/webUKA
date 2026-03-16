console.log('✅ Nous sommes dans le JS Encaissement');

// Variables globales pour solde et total cumulé
let soldeUSD = 0;
let soldeCDF = 0;
let totalEncUSD = 0;
let totalEncCDF = 0;
let imputationsCumuléesUSD = "";
let imputationsCumuléesCDF = "";
let motifCumuléesCDF = "";
let motifCumuléesUSD = "";
let montantEnLettres = "";
let montantTotalEnLettres = "";
const Annee = document.getElementById("annee");

//*********************  DES OPERATION D'ENCAISSEMENT EN DOLLARS******************* */
const num_pce = document.getElementById("numeroPieceUSD");
const motifVersementUSD = document.getElementById("motifVersementUSD");
const idServiceUSD = document.getElementById("libelleServiceUSD");
const montantUSD = document.getElementById("montantUSD");
const dateInput  = document.getElementById("dateVersementUSD");
const solde_usd = document.getElementById("soldeUSD");
const deposant_usd = document.getElementById("Deposant_usd");
//const date_vers =document.getElementById('dateVersementUSD').value.replace('T', ' ')
const imputationusd = document.getElementById("ImputationEncUSD");
const TotalEncUSD = document.getElementById("totalEncUSD");
const champSoldeFinalUSD = document.getElementById("soldeFinalUSD");
//*********************************FIN************************************ */

//********************* DES OPERATION D'ENCAISSEMENT EN FRANCS CONGOLAIS *****************/
const num_pceCDF = document.getElementById("numeroPieceCDF");
const motifVersementCDF = document.getElementById("motifVersementCDF");
const idServiceCDF = document.getElementById("libelleServiceCDF");
const montantCDF = document.getElementById("montantCDF");
const solde_cdf = document.getElementById("soldeCDF");
const deposant_cdf = document.getElementById("Deposant_cdf");
const date_versCDF = document.getElementById("dateVersementCDF");
const imputationcdf = document.getElementById("ImputationEncCDF");
const TotalEncCDF = document.getElementById("totalEncCDF");
const champSoldeFinalCDF = document.getElementById("soldeFinalCDF");

//********************* DETECTION DES BOUTONS *********************/
const boutons = document.querySelectorAll(".btn-Action");
console.log("🔍 le Total de boutons détectés :", boutons.length);

boutons.forEach(bouton => {
    bouton.addEventListener("click", function () {
        let texteBouton = this.innerText.trim();
        console.log("🖱️ Texte du bouton cliqué :", texteBouton);

        if (texteBouton === "Encaisser USD") 
            {
                
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
                        // Appel de la fonction d'enregistrement
                         const date_vers = dateInput.value.replace('T', ' ');

                        console.log("📅 Date envoyée :", date_vers);
                     
                        Enregistrement_Encaissement_USD(
                            num_pce.value,
                            texteBouton,
                            motifVersementUSD.value,
                            idServiceUSD.value,
                            montantUSD.value,
                            date_vers,
                            deposant_usd.value,
                            imputationusd.value,
                            Annee.value
                        ).then((response) => {
                            if (response.success) {
                                // ✅ Cumul des motifs et imputations après l'enregistrement
                                if (motifVersementUSD.value.trim() !== "") {
                                    if (motifCumuléesUSD !== "") motifCumuléesUSD += ", ";
                                    motifCumuléesUSD += motifVersementUSD.value.trim();
                                }
                                if (imputationusd.value.trim() !== "") {
                                    if (imputationsCumuléesUSD !== "") imputationsCumuléesUSD += " | ";
                                    imputationsCumuléesUSD += imputationusd.value.trim();
                                }

                                // ✅ Si l'impression est demandée, appeler ImpressionReçuVersement
                                if (response.impression) {
                                   

                                    ImpressionReçuVersement(
                                        num_pce.value,
                                        totalEncUSD.toFixed(2),
                                        "USD",
                                        motifCumuléesUSD,
                                        date_vers.split(' ')[0],
                                        deposant_usd.value,
                                        imputationsCumuléesUSD
                                    );
                                 
                                 viderChampsEncaissement("USD");
                                
                                }else {
                                    // L'utilisateur a choisi une nouvelle opération, rien n'est réinitialisé ici
                                    console.log("Nouvelle opération choisie, champs non réinitialisés");
                                }
                                 resetChampsEncaissement("USD");
                                      
                            }
                          
                        });
                    }
                });
            }

        else if (texteBouton === "Encaisser CDF") 
            {
           
            Swal.fire({
                title: "Voulez-vous enregistrer cette opération ?",
                text: "Vous effectuez un encaissement en CDF.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, enregistrer",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Appel de la fonction d'enregistrement
                    const date_vers = date_versCDF.value.replace('T', ' ');

                        console.log("📅 Date envoyée :", date_vers);
                    Enregistrement_Encaissement_CDF(
                        num_pceCDF.value,
                        texteBouton,
                        motifVersementCDF.value,
                        idServiceCDF.value,
                        montantCDF.value,
                        date_vers,
                        deposant_cdf.value,
                        imputationcdf.value,
                        Annee.value
                    ).then((response) => {
                        if (response.success) {
                            // ✅ Cumul des motifs et imputations après l'enregistrement
                            if (motifVersementCDF.value.trim() !== "") {
                                if (motifCumuléesCDF !== "") motifCumuléesCDF += ", ";
                                motifCumuléesCDF += motifVersementCDF.value.trim();
                            }
                            if (imputationcdf.value.trim() !== "") {
                                if (imputationsCumuléesCDF !== "") imputationsCumuléesCDF += " | ";
                                imputationsCumuléesCDF += imputationcdf.value.trim();
                            }

                            // ✅ Si l'impression est demandée, appeler ImpressionReçuVersement
                            if (response.impression) {
                                ImpressionReçuVersement(
                                    num_pceCDF.value,
                                    totalEncCDF.toFixed(2),
                                    "CDF",
                                    motifCumuléesCDF,
                                    date_vers.split(' ')[0],
                                    deposant_cdf.value,
                                    imputationsCumuléesCDF
                                );

                             
                             
                                 viderChampsEncaissement("CDF");
                               
                            }else {
                                    // L'utilisateur a choisi une nouvelle opération, rien n'est réinitialisé ici
                                    console.log("Nouvelle opération choisie, champs non réinitialisés");
                                }
                                 resetChampsEncaissement("CDF");
                            
                        }
                        
                    });
                }
            });
        }

    });
});


//************************ FONCTION ENCAISSEMENT USD ************************/
function Enregistrement_Encaissement_USD(
    Num_Pce, texteBouton, MotifVersementUSD, IdServiceUSD, 
    Montant_USD, Date_vers_USD, Deposant_usd, Imputationusd, Annee
) {
    const lien = 'D_Finance/API/API_Encaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&Text_Btn=' + encodeURIComponent(texteBouton) +
        '&Motif_USD=' + encodeURIComponent(MotifVersementUSD) +
        '&Idser_USD=' + encodeURIComponent(IdServiceUSD) +
        '&Montant_USD=' + encodeURIComponent(Montant_USD) +
        '&Date_op_usd=' + encodeURIComponent(Date_vers_USD) +
        '&Deposant_usd=' + encodeURIComponent(Deposant_usd) +
        '&Imputation_usd=' + encodeURIComponent(Imputationusd) +
        '&AnneeAcad=' + encodeURIComponent(Annee);

    // Retourne la promesse pour pouvoir utiliser .then() à l’extérieur
    return fetch(lien)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || "Erreur inconnue lors de l’encaissement");
            }

            return Swal.fire({
                icon: "success",
                title: "✅ Succès",
                text: "Encaissement effectué avec succès",
                confirmButtonText: "OK"
            }).then(() => true); // Retourne true quand tout est bon
            
        })
        .then(() => {
            // Mise à jour des montants (après enregistrement)
            let montant = parseFloat(Montant_USD);
            if (!isNaN(montant)) {
                totalEncUSD = parseFloat(totalEncUSD || 0) + montant;
                soldeUSD += montant;

                solde_usd.innerText = soldeUSD.toFixed(2) + " $";
                TotalEncUSD.textContent = totalEncUSD.toFixed(2) + " $";
                champSoldeFinalUSD.value = soldeUSD.toFixed(2);

                let val = parseFloat(TotalEncUSD.textContent);
                console.log("Valeur avant conversion en lettres :", val);
                 
                if (!isNaN(val)) {
                    document.getElementById("en-lettresTotal").innerText = enLettresMontant(val, "USD");
                    montantTotalEnLettres = document.getElementById("en-lettresTotal").innerText;
                     console.log("Montant en lettres :", montantTotalEnLettres);
                } else {
                    document.getElementById("en-lettresTotal").innerText = "";
                }
            }

            // Deuxième Swal pour choisir l'action après enregistrement
            return Swal.fire({
                title: "Opération réussie 🎉",
                text: "Souhaitez-vous imprimer le reçu ou effectuer une autre opération ?",
                icon: "question",
                showDenyButton: true,
                confirmButtonText: "🖨️ Imprimer le reçu",
                denyButtonText: "➕ Nouvelle opération"
            }).then((result) => {
                return { success: true, impression: result.isConfirmed };
            });
        })
        .catch(error => {
            console.error("❌ Erreur :", error);
            Swal.fire("Erreur", error.message, "error");
            return { success: false }; // Retourne false en cas d'erreur
        });
}



//************************ FONCTION ENCAISSEMENT CDF ************************/

function Enregistrement_Encaissement_CDF(
    Num_PceCDF,
    texteBouton,
    MotifVersementCDF,
    IdServiceCDF,
    Montant_CDF,
    Date_vers_CDF,
    Deposant_cdf,
    Imputationcdf,
    Annee
) {
    const lien = 'D_Finance/API/API_Encaissement.php?Num_PceCDF=' + encodeURIComponent(Num_PceCDF) +
        '&Text_Btn=' + encodeURIComponent(texteBouton) +
        '&Motif_CDF=' + encodeURIComponent(MotifVersementCDF) +
        '&Idser_CDF=' + encodeURIComponent(IdServiceCDF) +
        '&Montant_CDF=' + encodeURIComponent(Montant_CDF) +
        '&Date_op_CDF=' + encodeURIComponent(Date_vers_CDF) +
        '&Deposant_cdf=' + encodeURIComponent(Deposant_cdf) +
        '&Imputation_cdf=' + encodeURIComponent(Imputationcdf) +
        '&AnneeAcad=' + encodeURIComponent(Annee);

    return fetch(lien)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || "Erreur inconnue lors de l’encaissement");
            }

            return Swal.fire({
                icon: "success",
                title: "✅ Succès",
                text: "Encaissement effectué avec succès",
                confirmButtonText: "OK"
            }).then(() => true); // Retourne true quand tout est bon
            
        })
        .then(() => {
            // ✅ Mise à jour des montants après enregistrement
            let montant = parseFloat(Montant_CDF);
            if (!isNaN(montant)) {
                totalEncCDF = parseFloat(totalEncCDF || 0) + montant;
                soldeCDF += montant;

                solde_cdf.innerText = soldeCDF.toFixed(2) + " Fc";
                TotalEncCDF.textContent = totalEncCDF.toFixed(2) + " Fc";
                champSoldeFinalCDF.value = soldeCDF.toFixed(2);

                let val = parseFloat(TotalEncCDF.textContent);

                 
                if (!isNaN(val)) {
                    document.getElementById("en-lettresTotalCDF").innerText = enLettresMontant(val, "CDF");
                    montantTotalEnLettres = document.getElementById("en-lettresTotalCDF").innerText;
                } else {
                    document.getElementById("en-lettresTotalCDF").innerText = "";
                }
            }
           
            // Deuxième Swal pour choisir l'action après enregistrement
            return Swal.fire({
                title: "Opération réussie 🎉",
                text: "Souhaitez-vous imprimer le reçu ou effectuer une autre opération ?",
                icon: "question",
                showDenyButton: true,
                confirmButtonText: "🖨️ Imprimer le reçu",
                denyButtonText: "➕ Nouvelle opération"
            }).then((result) => {
                return { success: true, impression: result.isConfirmed };
            });
        })
        .catch(error => {
            console.error("❌ Erreur :", error);
            Swal.fire("Erreur", error.message, "error");
            return { success: false }; // Retourne false en cas d'erreur
        });
}






//********************* GESTION DE LA SAISIE DES MONTANTS *********************/
function gererSaisieMontant(champ, messageErreur) {
    champ.addEventListener('input', function () {
        const valeurActuelle = this.value;
        const containsInvalidChars = /[^0-9.]/.test(valeurActuelle);
        messageErreur.style.display = containsInvalidChars ? 'block' : 'none';

        let valeurNettoyee = valeurActuelle.replace(/[^0-9.]/g, '');
        const parts = valeurNettoyee.split('.');
        if(parts.length > 2) valeurNettoyee = parts[0] + '.' + parts.slice(1).join('');
        this.value = valeurNettoyee;
    });
}

gererSaisieMontant(montantUSD, document.getElementById('erreurMontant'));
gererSaisieMontant(montantCDF, document.getElementById('erreurCDF'));

//********************* MONTANT EN LETTRES *********************/
const libellesDevise = {
    USD: { singulier: "dollar américain", pluriel: "dollars américains", centime: "centime", centimes: "centimes" },
    CDF: { singulier: "franc congolais", pluriel: "francs congolais", centime: "centime", centimes: "centimes" },
    EUR: { singulier: "euro", pluriel: "euros", centime: "centime", centimes: "centimes" },
};
let devise = "USD"; // défaut

function enLettresMontant(nombre, devise) {
    const entier = Math.floor(nombre);
    const decimal = Math.round((nombre - entier) * 100);
    const unit = (entier === 1) ? libellesDevise[devise].singulier : libellesDevise[devise].pluriel;
    const centime = (decimal === 1) ? libellesDevise[devise].centime : libellesDevise[devise].centimes;
    let texte = enLettres(entier) + " " + unit;
    if(decimal > 0) texte += " et " + enLettres(decimal) + " " + centime;
    return texte;
}

function enLettres(n) {
    const ones = ["","un","deux","trois","quatre","cinq","six","sept","huit","neuf","dix","onze","douze","treize","quatorze","quinze","seize","dix-sept","dix-huit","dix-neuf"];
    const tens = ["","","vingt","trente","quarante","cinquante","soixante"];
    const scales = ["","mille","million","milliard"];
    if(n===0) return "zéro";
    let parts=[], scaleIndex=0;
    while(n>0){
        let chunk = n%1000;
        if(chunk){
            let chunkText = convertChunk(chunk);
            if(scaleIndex>0) chunkText += " " + scales[scaleIndex] + (chunk>1 && scaleIndex>1?"s":"");
            parts.unshift(chunkText.trim());
        }
        n=Math.floor(n/1000); scaleIndex++;
    }
    return parts.join(" ").replace(/\s+/g," ");
    function convertChunk(n){
        let str=""; let hundreds=Math.floor(n/100); let remainder=n%100;
        if(hundreds){ str += (hundreds===1?"cent":ones[hundreds]+" cent") + " "; if(remainder===0 && hundreds>1) str+="s"; }
        if(remainder<20) str += ones[remainder];
        else { let ten=Math.floor(remainder/10), one=remainder%10;
            if(ten===8) str+="quatre-vingt"+(one>0?"-"+ones[one]:"");
            else if(ten===9) str+="quatre-vingt-"+ones[10+one];
            else if(ten===7) str+="soixante-"+ones[10+one];
            else { str+=tens[ten]; if(one===1&&(ten===1||ten>1)) str+="-et-un"; else if(one>0) str+="-"+ones[one];}
        }
        return str;
    }
}

montantUSD.addEventListener("input", function(){
    let val = parseFloat(this.value);
    if(!isNaN(val)){
        document.getElementById("en-lettres").innerText = enLettresMontant(val, "USD");
      
        montantEnLettres = document.getElementById("en-lettres").innerText;
    } 
    else document.getElementById("en-lettres").innerText = "";
            
});

montantCDF.addEventListener("input", function(){
    let val = parseFloat(this.value);
    if(!isNaN(val)){
        document.getElementById("en-lettresCDF").innerText = enLettresMontant(val, "CDF");
        montantEnLettres = document.getElementById("en-lettresCDF").innerText;
    } 
    else document.getElementById("en-lettresCDF").innerText = "";
});

//********************* IMPRESSION RECU *********************/
function ImpressionReçuVersement(num_pce, montant, devise, motif, date, deposant, imputation) {
    const imagePrechargeeLogo = new Image();
    const imagePrechargeeFond = new Image();

    // Définir les chemins des images
    imagePrechargeeLogo.src = "D_Finance/img/logo.png";
    imagePrechargeeFond.src = "D_Finance/img/fond-recu.jpg";

    // Attendre que les images soient bien chargées
    Promise.all([
        new Promise(resolve => imagePrechargeeLogo.onload = resolve),
        new Promise(resolve => imagePrechargeeFond.onload = resolve)
    ]).then(() => {
        // Convertir les images chargées en base64 (DataURL)
        const canvasLogo = document.createElement("canvas");
        canvasLogo.width = imagePrechargeeLogo.width;
        canvasLogo.height = imagePrechargeeLogo.height;
        const ctxLogo = canvasLogo.getContext("2d");
        ctxLogo.drawImage(imagePrechargeeLogo, 0, 0);
        const logoDataURL = canvasLogo.toDataURL("image/png");

        const canvasFond = document.createElement("canvas");
        canvasFond.width = imagePrechargeeFond.width;
        canvasFond.height = imagePrechargeeFond.height;
        const ctxFond = canvasFond.getContext("2d");
        ctxFond.drawImage(imagePrechargeeFond, 0, 0);
        const fondDataURL = canvasFond.toDataURL("image/jpeg");

        // Contenu HTML avec images en base64 intégrées
        const contenu = `
            <html>
            <head>
                <title>Reçu de versement</title>
                <style>
                    body { font-family: Perpetua, sans-serif; margin: 0; padding: 5px 20px 20px 20px; }
                    h4 { text-align: center; margin-top: 5px; margin-bottom: 10px; }
                    p { text-align: center; }

                    .header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        border-bottom: 1px solid #000;
                        margin-bottom: 5px;
                    }
                    .header img {
                        height: 70px;
                        margin-right: 20px;
                    }
                    .header .text { flex: 1; text-align: right; }

                    .montant-fond {
                        background-image: url('${fondDataURL}');
                        background-size: contain;
                        background-repeat: repeat-x;
                        font-weight: bold;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                    .montant-fond-chiffre {
                        background-image: url('${fondDataURL}');
                        background-size: contain;
                        background-repeat: repeat-x;
                        text-align: right;
                        font-size: 30px;
                        font-weight: bold;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                    .signature-section {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 15px;
                        text-align: center;
                    }
                    .signature-section .column { flex: 1; }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="${logoDataURL}" alt="Logo">
                    <div class="text">
                        <p>
                            République Démocratique du Congo<br>
                            Ministère de l'Enseignement Supérieur et Universitaire<br>
                            Université Notre-Dame du Kasayi (U.KA.)
                        </p>
                    </div>
                </div>

                <h4>REÇU DE VERSEMENT EN - ${devise} - N°: ${num_pce}</h4>

                <div style="text-align: right;">
                    <span class="montant-fond-chiffre"> ${montant} ${devise}</span>
                </div><br>
                Je soussigné <b>${deposant}</b>, reconnais avoir versé dans la caisse U.KA. la somme de (toutes lettres) :
                <span class="montant-fond">${montantTotalEnLettres}</span><br>
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

        // Attendre que tout soit bien rendu avant d'imprimer
        fenetreImpression.onload = () => {
            fenetreImpression.focus();
            fenetreImpression.print();
        };

        // Après impression → on rafraîchit les soldes dans la page principale
        fenetreImpression.onafterprint = function() {
            try {
                rafraichirNumeros();
                AfficherSoldes();
            } catch (e) {
                console.error("Erreur post impression :", e);
            }
        };
    }).catch(() => {
        Swal.fire('Erreur', 'Une erreur est survenue lors du chargement des images.', 'error');
    });
}      
            

            devise = null;

            const mapDevises = {
                collapseEncaissementUSD: "USD",
                collapseEncaissementCDF: "CDF",
                collapseEncaissementEUR: "EUR"
            };

            Object.keys(mapDevises).forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener("shown.bs.collapse", function () {
                        devise = mapDevises[id];
                        console.log("Devise active :", devise);
                    });
                }
    });
// Rafraîchir uniquement les numéros de pièce
function rafraichirNumeros() {
    fetch("D_Finance/API/rafraichirPieces.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("numeroPieceUSD").value = data.numUSD;
                document.getElementById("numeroPieceCDF").value = data.numCDF;
                
            }
        })
        .catch(err => console.error("Erreur lors du rafraîchissement des numéros :", err));
}
async function AfficherSoldes() {
    try {
        let champSoldeFinalCDF = document.getElementById("soldeFinalCDF");
        let champSoldeFinalUSD = document.getElementById("soldeFinalUSD");

        // 🟢 Attendre la réponse du serveur
        const response = await fetch("D_Finance/API/API_Select_Solde.php");

        // 🟢 Vérifier si la réponse est correcte
        if (!response.ok) {
            throw new Error("Erreur HTTP : " + response.status);
        }

        // 🟢 Attendre la conversion en JSON
        const data = await response.json();
        console.log("🔍 Résultat JSON brut :", data);

        // 🟢 Conversion et calcul des soldes
        let solde_usd = parseFloat(data.Solde_usd?.replace(/,/g, '') || 0);
        let solde_dec_usd = parseFloat(data.Solde__dec_usd?.replace(/,/g, '') || 0);
        let solde_usd_montant = solde_usd - solde_dec_usd;

        let solde_cdf = parseFloat(data.Solde_cdf?.replace(/,/g, '') || 0);
        let solde_dec_cdf = parseFloat(data.Solde__dec_cdf?.replace(/,/g, '') || 0);
        let solde_cdf_montant = solde_cdf - solde_dec_cdf;

        // 🧾 Logs utiles pour debug
        console.log("💰 Solde USD brut :", solde_usd);
        console.log("💸 Décaissement USD :", solde_dec_usd);
        console.log("✅ Solde final USD :", solde_usd_montant);
        console.log("💰 Solde CDF brut :", solde_cdf);
        console.log("💸 Décaissement CDF :", solde_dec_cdf);
        console.log("✅ Solde final CDF :", solde_cdf_montant);

        // 🟢 Affichage dans les champs HTML
        document.getElementById("soldeCDF").innerText = solde_cdf_montant.toLocaleString() + " Fc";
        document.getElementById("soldeUSD").innerText = solde_usd_montant.toLocaleString() + " $";

        soldeUSD = solde_usd_montant;
       soldeCDF = solde_cdf_montant;
        champSoldeFinalCDF.value=solde_cdf_montant;
        champSoldeFinalUSD.value=solde_usd_montant;

    } catch (error) {
        console.error("❌ Erreur lors du chargement du solde CDF :", error);
    }
}

// Après impression → rafraîchir les champs

 document.addEventListener("DOMContentLoaded", function () {
    

    const now = new Date();
    
    // Format YYYY-MM-DDTHH:MM
    const formatted = now.toISOString().slice(0,16);

    const dateUSD = document.getElementById("dateVersementUSD");
    if (dateUSD) dateUSD.value = formatted;

    const dateCDF = document.getElementById("dateVersementCDF");
    if (dateCDF) dateCDF.value = formatted;
     const dateclotureCDF = document.getElementById("dateClotureCDF");
    if (dateclotureCDF) dateclotureCDF.value = formatted;
     const dateclotureUSD = document.getElementById("dateClotureUSD");
    if (dateclotureUSD) dateclotureUSD.value = formatted;

    AfficherSoldes();
    rafraichirNumeros();
});
function resetChampsEncaissement(devise) {
    if (devise === "USD") {
       
        motifVersementUSD.value = "";
        montantUSD.value = "";
        //deposant_usd.value = "";
        $('#libelleServiceUSD').val(null).trigger('change');
        $('#ImputationEncUSD').val(null).trigger('change');
   
    }

    if (devise === "CDF") {
     
        $('#libelleServiceCDF').val(null).trigger('change');
        $('#ImputationEncCDF').val(null).trigger('change');

        //num_pceCDF.value = "";
        motifVersementCDF.value = "";
        montantCDF.value = "";
        //deposant_cdf.value = "";   
      
    }
}
function viderChampsEncaissement(devise) {
    if (devise === "USD") {
       
        motifVersementUSD.value = "";
        montantUSD.value = "";
        deposant_usd.value = "";
        $('#libelleServiceUSD').val(null).trigger('change');
        $('#ImputationEncUSD').val(null).trigger('change');

        document.getElementById("en-lettres").innerText = "";
        document.getElementById("en-lettresTotal").innerText = "";
        TotalEncUSD.textContent = "0.00 $";

        totalEncUSD = 0;
        motifCumuléesUSD = "";
        imputationsCumuléesUSD = "";  
    }

    if (devise === "CDF") {
       
        $('#libelleServiceCDF').val(null).trigger('change');
        $('#ImputationEncCDF').val(null).trigger('change');

        motifVersementCDF.value = "";
        montantCDF.value = "";
        deposant_cdf.value = "";
        document.getElementById("en-lettresCDF").innerText = "";
       document.getElementById("en-lettresTotalCDF").innerText = "";
        TotalEncCDF.textContent = "0.00 Fc";

        totalEncCDF = 0;
        motifCumuléesCDF = "";
        imputationsCumuléesCDF = "";
   
    }
}
