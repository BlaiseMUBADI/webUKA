console.log('✅ Nous sommes dans le JS Encaissement');

//*********************  DES OPERATION D'ENCAISSEMENT EN DOLLARS******************* */

const num_pce = document.getElementById("numeroPieceUSD");
const motifVersementUSD = document.getElementById("motifVersementUSD");
const idServiceUSD = document.getElementById("libelleServiceUSD");
const montantUSD = document.getElementById("montantUSD");
const date_vers = document.getElementById("dateVersementUSD");
const solde_usd= document.getElementById("solde_USD");
const deposant_usd= document.getElementById("Deposant_usd");
const imputationusd= document.getElementById("ImputationEncUSD");
//*********************************FIN************************************ */

//********************* DES OPERATION D'ENCAISSEMENT EN FRANCS CONGOLAIS *****************/
const num_pceCDF = document.getElementById("numeroPieceCDF");
const motifVersementCDF = document.getElementById("motifVersementCDF");
const idServiceCDF = document.getElementById("libelleServiceCDF");
const montantCDF = document.getElementById("montantCDF");
const solde_cdf= document.getElementById("solde_CDF");
const deposant_cdf= document.getElementById("Deposant_cdf");
const date_versCDF = document.getElementById("dateVersementCDF");
const imputationcdf= document.getElementById("ImputationEncCDF");

//detection des boutons ayant la même classe
const boutons = document.querySelectorAll(".btn-Action");
// TESTER LE TEXTE DU BOUTON POUR ENVOYER LES DONNEES A L'API PAR LES DIFFERENTES FONCTIONS
console.log("🔍 le Total de boutons détectés :", boutons.length);
boutons.forEach(bouton => {
    bouton.addEventListener("click", function () {
        let texteBouton = this.innerText.trim();

        console.log("🖱️ Texte du bouton cliqué :", texteBouton);

        if (texteBouton === "Encaisser USD") {
            let Num_Pce = num_pce.value;
            let MotifVersementUSD = motifVersementUSD.value;
            let IdServiceUSD = idServiceUSD.value;
            let Montant_USD = montantUSD.value;
            let Date_vers_USD = date_vers.value;
            let Deposant_usd = deposant_usd.value;
            let Imputationusd = imputationusd.value;

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
                    Enregistrement_Encaissement_USD(
                        Num_Pce,
                        texteBouton,
                        MotifVersementUSD,
                        IdServiceUSD,
                        Montant_USD,
                        Date_vers_USD,Deposant_usd,Imputationusd
                    );
                }
            });
        } 
        else if (texteBouton === "Encaisser CDF") 
            {
                let Num_PceCDF = num_pceCDF.value;
                let MotifVersementCDF = motifVersementCDF.value;
                let IdServiceCDF = idServiceCDF.value;
                let Montant_CDF = montantCDF.value;
                let Date_vers_CDF = date_versCDF.value;
                let Deposant_cdf = deposant_cdf.value;
                let Imputationcdf = imputationcdf.value;
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
                Enregistrement_Encaissement_CDF(Num_PceCDF, 
                    texteBouton, 
                    MotifVersementCDF, 
                    IdServiceCDF, 
                    Montant_CDF, 
                    Date_vers_CDF,Deposant_cdf,Imputationcdf)
                }
            });
            }
    });
});
// FIN_____________________________DEBUT DE LA FONCTION QUI ENVOIE LES DONNEES POUR L'ENCAISSEMENT EN $
    var solde_usd_montant="";
    var montant_usd_decaisse="";
function Enregistrement_Encaissement_USD(Num_Pce, texteBouton, MotifVersementUSD, IdServiceUSD, Montant_USD, Date_vers_USD,Deposant_usd,Imputationusd) 
{
    let champSoldeFinalUSD = document.getElementById("soldeFinalUSD");
    const lien = 'D_Finance/API/API_Encaissement_USD.php?Num_Pce=' + encodeURIComponent(Num_Pce) +
        '&Text_Btn=' + encodeURIComponent(texteBouton) +
        '&Motif_USD=' + encodeURIComponent(MotifVersementUSD) +
        '&Idser_USD=' + encodeURIComponent(IdServiceUSD) +
        '&Montant_USD=' + encodeURIComponent(Montant_USD) +
        '&Date_op_usd=' + encodeURIComponent(Date_vers_USD)+
        '&Deposant_usd=' + encodeURIComponent(Deposant_usd)+
        '&Imputation_usd=' + encodeURIComponent(Imputationusd);
        fetch(lien)
        .then(response => response.json())
        .then(data => {
            console.log("✅ Réponse API :", data);
    
            if (data.success) {
                Swal.fire("✅ Succès", "Encaissement effectué avec succès", "success")
                .then(() => {
                    // Mise à jour du numéro de pièce
                    if (data.NumeroPieceSuivant) {
                        num_pce.value = data.NumeroPieceSuivant;
                        console.log("🔢 Prochain numéro de pièce appliqué :", data.NumeroPieceSuivant);
                        console.log("🔢 le solde en usd sans différence est là :", data.SOLDE_usd);
    
                        // Extraction et nettoyage du montant
                        let montant_usd_decaisse = document.getElementById("DecaissementUSD").innerText.trim().replace(/[^\d.-]/g, '');
                        console.log("Montant nettoyé :", montant_usd_decaisse);
    
                        if (!isNaN(montant_usd_decaisse) && montant_usd_decaisse !== '') {
                            montant_usd_decaisse = parseFloat(montant_usd_decaisse);
                            let solde_usd_montant = data.SOLDE_usd - montant_usd_decaisse;
                            console.log("Solde DISPO :", montant_usd_decaisse);
                            console.log("Solde après déduction :", solde_usd_montant);
                            document.getElementById("solde_USD").innerText = solde_usd_montant + " $";
                            champSoldeFinalUSD.value = solde_usd_montant;
                            console.log("✅ Champ soldeFinalUSD mis à jour :", solde_usd_montant);
                        } else {
                            console.log("Erreur : valeur invalide pour montant_usd_decaisse");
                        }
                    }
    
                    // Appel de l'impression après confirmation
                    ImpressionReçuVersement(Num_Pce, Montant_USD, "USD", MotifVersementUSD, Date_vers_USD,Deposant_usd,Imputationusd);
    
                    // Nettoyage des champs
                    motifVersementUSD.value = "";
                    idServiceUSD.selectedIndex = 0;
                    montantUSD.value = "";
                    deposant_usd.value = "";
                });
    
            } else if (data.error) {
                Swal.fire("❌ Erreur: Encaissement non effectué", data.message || "Une erreur est survenue.", "error");
    
            } else {
                Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
            }
        })
        .catch(error => {
            console.error("❌ Erreur de requête :", error);
            Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
        });
    
   
}

var solde_cdf_montant="";
var montant_cdf_decaisse="";
function Enregistrement_Encaissement_CDF(Num_PceCDF, texteBouton, MotifVersementCDF,
    IdServiceCDF, Montant_CDF, Date_vers_CDF,Deposant_cdf,Imputationcdf) 
{
    let champSoldeFinalCDF = document.getElementById("soldeFinalCDF");

   const lien = 'D_Finance/API/API_Encaissement_USD.php?Num_PceCDF=' + encodeURIComponent(Num_PceCDF) +
   '&Text_Btn=' + encodeURIComponent(texteBouton) +
   '&Motif_CDF=' + encodeURIComponent(MotifVersementCDF) +
   '&Idser_CDF=' + encodeURIComponent(IdServiceCDF) +
   '&Montant_CDF=' + encodeURIComponent(Montant_CDF) +
   '&Date_op_CDF=' + encodeURIComponent(Date_vers_CDF)+
   '&Deposant_cdf=' + encodeURIComponent(Deposant_cdf)+
   '&Imputation_cdf=' + encodeURIComponent(Imputationcdf);

   fetch(lien)
   .then(response => response.json())
   .then(data => {
       console.log("✅ Réponse API :", data);
       if (data.success) {
           Swal.fire("✅ Succès", data.success, "success")
           .then(() => {
           if (data.next_numero_pce) {
               // Met à jour automatiquement le champ numéro de pièce
               num_pceCDF.value = data.next_numero_pce;
               console.log("🔢 Prochain numéro de pièce appliqué :", data.next_numero_pce);

                // Extraire la valeur numérique en nettoyant les caractères non numériques (comme $ et les espaces)
                let montant_cdf_decaisse = document.getElementById("DecaissementCDF").innerText.trim().replace(/[^\d.-]/g, '');

                // Vérifie le contenu après nettoyage
                console.log("Montant nettoyé :", montant_cdf_decaisse);  // Pour diagnostiquer la valeur

                // Si c'est une valeur valide, effectue le calcul
                if (!isNaN(montant_cdf_decaisse) && montant_cdf_decaisse !== '') {
                    montant_cdf_decaisse = parseFloat(montant_cdf_decaisse);

                    // Effectuer ton calcul
                    let solde_cdf_montant = data.SOLDE_cdf - montant_cdf_decaisse;
                    console.log("Solde DISPO :", montant_cdf_decaisse);

                    console.log("Solde après déduction :", solde_cdf_montant);
                    document.getElementById("solde_CDF").innerText=solde_cdf_montant;
                    champSoldeFinalCDF.value=solde_cdf_montant;
                } else {
                    console.log("Erreur : valeur invalide pour montant_cdf_decaisse");
                }  
           }          
           ImpressionReçuVersement(Num_PceCDF, Montant_CDF, "CDF", MotifVersementCDF, Date_vers_CDF,Deposant_cdf,Imputationcdf);

           // 🔄 Vider les autres champs
               motifVersementCDF.value = "";idServiceCDF.selectedIndex = 0; montantCDF.value = "";
                deposant_cdf.value = "";
        });  
       } else if (data.error) {
           Swal.fire("❌ Erreur: Encaissement non éffectué", data.message || "Une erreur est survenue.", "error");
       } else {
           Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
       }
   })
   .catch(error => {
       console.error("❌ Erreur de requête :", error);
       Swal.fire("❌ Erreur réseau", "Impossible de contacter le serveur.", "error");
   });
}

            //GESTION DE LA SAISIE DU MONTANT POUR EVITER LES LETTRES
            const champMontant = document.getElementById('montantUSD');
            const messageErreur = document.getElementById('erreurMontant');

            champMontant.addEventListener('input', function () {
            const valeurActuelle = this.value;

            // Vérification si la saisie contient des lettres ou caractères non autorisés
            const containsInvalidChars = /[^0-9.]/.test(valeurActuelle);

            if (containsInvalidChars) {
                messageErreur.style.display = 'block';
            } else {
                messageErreur.style.display = 'none';
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
            const ChampMont = document.getElementById('montantCDF');
            const MsgeErreur = document.getElementById('erreurCDF');

            ChampMont.addEventListener('input', function () {
            const valeurActuelle = this.value;

                // Vérification si la saisie contient des lettres ou caractères non autorisés
                const containsInvalidChars = /[^0-9.]/.test(valeurActuelle);

                if (containsInvalidChars) {
                    MsgeErreur.style.display = 'block';
                } else {
                    MsgeErreur.style.display = 'none';
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

            function ImpressionReçuVersement(num_pce, montant, devise, motif, date,deposant,imputation) 
            {
                    const imagePrechargee = new Image();
                        imagePrechargee.src = "D_Finance/img/fond-recu.jpg";
                        imagePrechargee.onload = function () {
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
                            font-size: 40px;
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
                                        margin-top: 20px;
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
        
            

            let devise = null;

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

            let montantEnLettres="";
            // Écouteur d'événement sur l'input
            document.getElementById("montantUSD").addEventListener("input", function () {
                let val = parseFloat(this.value); // Utilisation de parseFloat pour permettre des montants avec des décimales
                if (!isNaN(val)) {
                    // Utilisation de la fonction avec la devise et conversion en lettres
                    document.getElementById("en-lettres").innerText = enLettresMontant(val, devise); // Changez "USD" en fonction de la devise
                montantEnLettres= document.getElementById("en-lettres").innerText;
                console.log("le montant en lettre est ",montantEnLettres);
                
                } else {
                    document.getElementById("en-lettres").innerText = "";
                }
            });
            document.getElementById("montantCDF").addEventListener("input", function () {
                let val = parseFloat(this.value); // Utilisation de parseFloat pour permettre des montants avec des décimales
                if (!isNaN(val)) {
                    // Utilisation de la fonction avec la devise et conversion en lettres
                    document.getElementById("en-lettresCDF").innerText = enLettresMontant(val, devise); // Changez "USD" en fonction de la devise
                montantEnLettres= document.getElementById("en-lettresCDF").innerText;
                console.log("le montant en lettre est ",montantEnLettres);
                
                } else {
                    document.getElementById("en-lettresCDF").innerText = "";
                }
            });
            
            
// ➜ "un billion dollars américains et quatre-vingt-dix-neuf centimes"
