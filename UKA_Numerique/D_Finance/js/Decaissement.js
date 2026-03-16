// Decaissement.js
console.log('✅ Decaissement.js chargé');
// Création d'un identifiant unique pour cet onglet
// Création d'un identifiant unique pour cet onglet
function genererUUID() {
    // Exemple simple : 16 caractères alphanumériques
    return 'xxxx-xxxx-xxxx-xxxx'.replace(/[x]/g, function() {
        return Math.floor(Math.random() * 16).toString(16);
    });
}

if (!sessionStorage.getItem("onglet_session_id")) {
    sessionStorage.setItem("onglet_session_id", genererUUID());
}

const ongletSessionID = sessionStorage.getItem("onglet_session_id");
console.log("✅ Onglet session ID :", ongletSessionID);



// -------------------- Variables globales --------------------
let soldeUSD = 0;
let soldeCDF = 0;

let totalDecUSD = 0;
let totalDecCDF = 0;

let imputationsCumuléesUSD = "";
let imputationsCumuléesCDF = "";

let motifCumuléesUSD = "";
let motifCumuléesCDF = "";

let montantEnLettres = "";
let montantTotalEnLettres = "";

let soldeCDFChamp = "";
let soldeUSDChamp = "";
const Annee = document.getElementById("annee");

// -------------------- Sélecteurs DOM (USD) --------------------
const numPceDecUSD = document.getElementById("numeroPieceDecUSD");
const beneficiaireUSD = document.getElementById("beneficiaireUSD");
const montantDecUSD = document.getElementById("montantDecUSD");
const motifDecUSD = document.getElementById("motifDecaissementUSD");
const dateDecUSD = document.getElementById("dateDecaissementUSD");
const imputationUSD = document.getElementById("ImputationUSD");
const TotalDecUSD = document.getElementById("totalDecUSD");
const erreurMontantUSD = document.getElementById("erreurMontant"); // présent dans HTML
const enLettresUSD = document.getElementById("en-lettres");
const enLettresTotalUSD = document.getElementById("en-lettresTotal");

// -------------------- Sélecteurs DOM (CDF) --------------------
const numPceDecCDF = document.getElementById("numeroPieceDecCDF");
const beneficiaireCDF = document.getElementById("beneficiaireCDF");
const montantDecCDF = document.getElementById("montantDecCDF");
const motifDecCDF = document.getElementById("motifDecaissementCDF");
const dateDecCDF = document.getElementById("dateDecaissementCDF");
const imputationCDF = document.getElementById("ImputationCDF");
const btnDecCDF = document.getElementById("BtnDecaisserCDF");
const TotalDecCDF = document.getElementById("totalDecCDF");
const erreurMontantCDF = document.getElementById("erreurCDF");
const enLettresCDF = document.getElementById("en-lettresCDF");
const enLettresTotalCDF = document.getElementById("en-lettresTotalCDF");


const boutons = document.querySelectorAll(".btn-Action");
console.log("🔍 le Total de boutons détectés :", boutons.length);

boutons.forEach(bouton => {
    bouton.addEventListener("click", function () {
        let texteBouton = this.innerText.trim();
        console.log("🖱️ Texte du bouton cliqué :", texteBouton);

        if (texteBouton === "Valider le décaissement en USD") 
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
                         const date_vers = dateDecUSD.value.replace('T', ' ');
                         soldeUSDChamp = nettoyerMontant(document.getElementById("soldeUSD").innerText);
                        

                        console.log("📅 Date envoyée :", date_vers);
                     
                        Enregistrement_Decaissement_USD(
                           numPceDecUSD.value,
                            motifDecUSD.value,
                            montantDecUSD.value,
                            date_vers,
                            beneficiaireUSD.value,
                            imputationUSD.value,
                            Annee.value,
                            soldeUSDChamp,ongletSessionID

                        ).then((response) => {
                            if (response.success) {
                                // ✅ Cumul des motifs et imputations après l'enregistrement
                                if (motifDecUSD.value.trim() !== "") {
                                    if (motifCumuléesUSD !== "") motifCumuléesUSD += ", ";
                                    motifCumuléesUSD += motifDecUSD.value.trim();
                                }
                                if (imputationUSD.value.trim() !== "") {
                                    if (imputationsCumuléesUSD !== "") imputationsCumuléesUSD += " | ";
                                    imputationsCumuléesUSD += imputationUSD.value.trim();
                                }

                                // ✅ Si l'impression est demandée, appeler ImpressionBonSortie
                                if (response.impression) {
                                   

                                    ImpressionBonSortie(
                                        numPceDecUSD.value,
                                        totalDecUSD.toFixed(2),
                                        "USD",
                                        motifCumuléesUSD,
                                        date_vers.split(' ')[0],
                                        beneficiaireUSD.value,
                                        imputationsCumuléesUSD
                                    );
                                 
                                 viderChampsEncaissement("USD");
                                
                                }else {
                                    // L'utilisateur a choisi une nouvelle opération, rien n'est réinitialisé ici
                                    console.log("Nouvelle opération choisie, champs non réinitialisés");
                                }
                                 resetChampsEncaissement("USD");
                                      AfficherSoldes();
                                   
                            }
                          
                        });
                    }
                });
            }

        else if (texteBouton === "Valider le décaissement en CDF") 
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
                    console.log("Solde USD lu :", soldeCDFChamp)
                        soldeCDFChamp = nettoyerMontant(document.getElementById("soldeCDF").innerText);
                    const date_vers = dateDecCDF.value.replace('T', ' ');

                        console.log("📅 Date envoyée :", date_vers);
                    Enregistrement_Decaissement_CDF(
                        numPceDecCDF.value,
                        motifDecCDF.value,
                        montantDecCDF.value,
                        date_vers,
                        beneficiaireCDF.value,
                        imputationCDF.value,
                        Annee.value,
                        soldeCDFChamp,
                        ongletSessionID
                    ).then((response) => {
                        if (response.success) {
                            // ✅ Cumul des motifs et imputations après l'enregistrement
                            if (motifDecCDF.value.trim() !== "") {
                                if (motifCumuléesCDF !== "") motifCumuléesCDF += ", ";
                                motifCumuléesCDF += motifDecCDF.value.trim();
                            }
                            if (imputationCDF.value.trim() !== "") {
                                if (imputationsCumuléesCDF !== "") imputationsCumuléesCDF += " | ";
                                imputationsCumuléesCDF += imputationCDF.value.trim();
                            }

                            // ✅ Si l'impression est demandée, appeler ImpressionBonSortie
                            if (response.impression) {
                                ImpressionBonSortie(
                                    numPceDecCDF.value,
                                    totalDecCDF.toFixed(2),
                                    "CDF",
                                    motifCumuléesCDF,
                                    date_vers.split(' ')[0],
                                    beneficiaireCDF.value,
                                    imputationsCumuléesCDF
                                );

                                 viderChampsEncaissement("CDF");
                               
                            }else {
                                    // L'utilisateur a choisi une nouvelle opération, rien n'est réinitialisé ici
                                    console.log("Nouvelle opération choisie, champs non réinitialisés");
                                }
                                 resetChampsEncaissement("CDF");
                                 AfficherSoldes();
                                 
                                 
                            
                        }
                        
                    });
                }
            });
        }

    });
});

//************************ FONCTION decaissement USD ************************/
function Enregistrement_Decaissement_USD(
    Num_Pce, 
    MotifdecUSD,  
    Montant_USD, 
    Date_dec_USD, beneficiaire, Imputationusd, Annee,solde,idsession
)

 {
    const lien = 'D_Finance/API/API_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&motif=' + encodeURIComponent(MotifdecUSD) +
        '&montant=' + encodeURIComponent(Montant_USD) +
        '&date=' + encodeURIComponent(Date_dec_USD) +
        '&beneficiaire=' + encodeURIComponent(beneficiaire) +
        '&imputation=' + encodeURIComponent(Imputationusd) +
        '&AnneeAcad=' + encodeURIComponent(Annee)+
        '&operation=' +"Dec_USD"+
        '&solde='+ encodeURIComponent(solde)+
        '&idsession='+ encodeURIComponent(idsession);

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
                totalDecUSD = parseFloat(totalDecUSD || 0) + montant;
                soldeUSD += montant;

                soldeUSDChamp.innerText = soldeUSD.toFixed(2) + " $";
                TotalDecUSD.textContent = totalDecUSD.toFixed(2) + " $";
               

                let val = parseFloat(TotalDecUSD.textContent);
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

//************************ FONCTION decaissement cdf ************************/
function Enregistrement_Decaissement_CDF(
    Num_Pce, 
    Motifdec,  
    Montant, 
    Date_dec, beneficiaire, Imputation, Annee,solde,ongletSession
)

 {
    const lien = 'D_Finance/API/API_Decaissement.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&motif=' + encodeURIComponent(Motifdec) +
        '&montant=' + encodeURIComponent(Montant) +
        '&date=' + encodeURIComponent(Date_dec) +
        '&beneficiaire=' + encodeURIComponent(beneficiaire) +
        '&imputation=' + encodeURIComponent(Imputation) +
        '&AnneeAcad=' + encodeURIComponent(Annee)+
        '&operation=' +"Dec_CDF"+
        '&solde='+ encodeURIComponent(solde)+
        '&idsession=' + encodeURIComponent(ongletSession);
    


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
            let montant = parseFloat(Montant);
            if (!isNaN(montant)) {
                totalDecCDF = parseFloat(totalDecCDF || 0) + montant;
                soldeCDF += montant;

                soldeCDFChamp.innerText = soldeCDF.toFixed(2) + " Fc";
                TotalDecCDF.textContent = totalDecCDF.toFixed(2) + " Fc";
                

                let val = parseFloat(TotalDecCDF.textContent);
                console.log("Valeur avant conversion en lettres :", val);
                 
                if (!isNaN(val)) {
                    document.getElementById("en-lettresTotalCDF").innerText = enLettresMontant(val, "CDF");
                    montantTotalEnLettres = document.getElementById("en-lettresTotalCDF").innerText;
                     console.log("Montant en lettres :", montantTotalEnLettres);
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

gererSaisieMontant(montantDecUSD, document.getElementById('erreurMontant'));
gererSaisieMontant(montantDecCDF, document.getElementById('erreurCDF'));

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

montantDecUSD.addEventListener("input", function(){
    let val = parseFloat(this.value);
    if(!isNaN(val)){
        document.getElementById("en-lettres").innerText = enLettresMontant(val, "USD");
      
        montantEnLettres = document.getElementById("en-lettres").innerText;
    } 
    else document.getElementById("en-lettres").innerText = "";
            
});

montantDecCDF.addEventListener("input", function(){
    let val = parseFloat(this.value);
    if(!isNaN(val)){
        document.getElementById("en-lettresCDF").innerText = enLettresMontant(val, "CDF");
        montantEnLettres = document.getElementById("en-lettresCDF").innerText;
    } 
    else document.getElementById("en-lettresCDF").innerText = "";
});

//********************* IMPRESSION RECU *********************/
function ImpressionBonSortie(num_pce, montant, devise, motif, date, ben, imputation) {
    const imagePrechargeLogo = new Image();
    const imagePrechargeFond = new Image();

    // Définir les chemins des images
    imagePrechargeLogo.src = "D_Finance/img/logo.png";
    imagePrechargeFond.src = "D_Finance/img/fond-recu.jpg";

    // Attendre que les images soient bien chargées
    Promise.all([
        new Promise(resolve => imagePrechargeLogo.onload = resolve),
        new Promise(resolve => imagePrechargeFond.onload = resolve)
    ]).then(() => {
        // Convertir les images chargées en base64 (DataURL)
        const canvasLogo = document.createElement("canvas");
        canvasLogo.width = imagePrechargeLogo.width;
        canvasLogo.height = imagePrechargeLogo.height;
        const ctxLogo = canvasLogo.getContext("2d");
        ctxLogo.drawImage(imagePrechargeLogo, 0, 0);
        const logoDataURL = canvasLogo.toDataURL("image/png");

        const canvasFond = document.createElement("canvas");
        canvasFond.width = imagePrechargeFond.width;
        canvasFond.height = imagePrechargeFond.height;
        const ctxFond = canvasFond.getContext("2d");
        ctxFond.drawImage(imagePrechargeFond, 0, 0);
        const fondDataURL = canvasFond.toDataURL("image/jpeg");

        // Contenu HTML avec images en base64 intégrées
        const contenu = `
            <html>
            <head>
                <title>Bon de sortie</title>
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

                <h4>BON DE SORTIE EN - ${devise} - N°: ${num_pce}</h4>

                <div style="text-align: right;">
                    <span class="montant-fond-chiffre"> ${montant} ${devise}</span>
                </div><br>
                Je soussigné <b>${ben}</b>, reconnais avoir reçu de la caisse U.KA. la somme de (toutes lettres) :
                <span class="montant-fond">${montantTotalEnLettres}</span><br>
                Motif : ${motif}

                <div class="signature-section">
                    <div class="column">
                        <p><strong>Signature déposant</strong><br>${ben}</p>
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
                document.getElementById("numeroPieceDecUSD").value = data.numDecUSD;
                document.getElementById("numeroPieceDecCDF").value = data.numDecCDF;
                
            }
        })
        .catch(err => console.error("Erreur lors du rafraîchissement des numéros :", err));
}

async function AfficherSoldes() {
    try {
        
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
       

    } catch (error) {
        console.error("❌ Erreur lors du chargement du solde CDF :", error);
    }
}

// Après impression → rafraîchir les champs

 document.addEventListener("DOMContentLoaded", function () {
   const now = new Date();
    
    // Format YYYY-MM-DDTHH:MM
    const formatted = now.toISOString().slice(0,16);

    const dateUSD = document.getElementById("dateDecaissementUSD");
    if (dateUSD) dateUSD.value = formatted;

    const dateCDF = document.getElementById("dateDecaissementCDF");
    if (dateCDF) dateCDF.value = formatted;

    AfficherSoldes();
    rafraichirNumeros();
});

//vider les champs

function resetChampsEncaissement(devise) {
    if (devise === "USD") {
       
        motifDecUSD.value = "";
        montantDecUSD.value = "";
       
        $('#ImputationUSD').val(null).trigger('change');
   
    }

    if (devise === "CDF") {
     
      
        $('#ImputationCDF').val(null).trigger('change');

        motifDecCDF.value = "";
        montantDecCDF.value = "";
      
    }
}

function viderChampsEncaissement(devise) {
    if (devise === "USD") {
       
        motifDecCDF.value = "";
        montantDecCDF.value = "";
        beneficiaireUSD.value = "";
        
        $('#ImputationUSD').val(null).trigger('change');

        document.getElementById("en-lettres").innerText = "";
        document.getElementById("en-lettresTotal").innerText = "";
        TotalDecUSD.textContent = "0.00 $";

        totalDecUSD = 0;
        motifCumuléesUSD = "";
        imputationsCumuléesUSD = "";  
    }

    if (devise === "CDF") {
       
        
        $('#ImputationCDF').val(null).trigger('change');

         motifDecCDF.value = "";
        montantDecCDF.value = "";
        beneficiaireCDF.value = "";
        document.getElementById("en-lettresCDF").innerText = "";
       document.getElementById("en-lettresTotalCDF").innerText = "";
        TotalDecCDF.textContent = "0.00 Fc";

        totalDecCDF = 0;
        motifCumuléesCDF = "";
        imputationsCumuléesCDF = "";
   
    }
}

function nettoyerMontant(montantString) {
    if (!montantString) return 0;
    const n = parseFloat(montantString.replace(/[$,\s]/g, ""));
    return isNaN(n) ? 0 : n;   // <--- très important
}
