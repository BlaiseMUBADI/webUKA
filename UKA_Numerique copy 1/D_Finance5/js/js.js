console.log("✅ JS Décaissement actif");

// 📌 Champs USD
const numeroPieceDecUSD = document.getElementById("numeroPieceDecUSD");
const compteUSD = document.getElementById("CompteUSD");
const beneficiaireUSD = document.getElementById("beneficiaireUSD");
const imputationUSD = document.getElementById("ImputationUSD");
const motifUSD = document.getElementById("motifDecaissementUSD");
const montantDecUSD = document.getElementById("montantDecUSD");
const dateUSD = document.getElementById("dateDecaissementUSD");


// 📌 Champs CDF
const numeroPieceDecCDF = document.getElementById("numeroPieceDecCDF");
const compteCDF = document.getElementById("CompteCDF");
const beneficiaireCDF = document.getElementById("beneficiaireCDF");
const imputationCDF = document.getElementById("ImputationCDF");
const motifCDF = document.getElementById("motifDecaissementCDF");
const montantDecCDF = document.getElementById("montantDecCDF");
const dateCDF = document.getElementById("dateDecaissementCDF");

// 🔁 Gestion des décaissements
document.querySelectorAll(".btn-Action").forEach(button => {
    button.addEventListener("click", () => {
        const texteBouton = button.innerText.trim();
        let lien = "";

        if (texteBouton === "Décaisser USD") {
            const bouton="decaissement_usd";
            const Solde_dispo_usd = nettoyerMontant(document.getElementById("solde_USD").innerText);
            lien = `D_Finance/API/API_Decaissement.php?Num_Pce=${encodeURIComponent(numeroPieceDecUSD.value)}
                &Compte=${encodeURIComponent(compteUSD.value)}
                &Beneficiaire=${encodeURIComponent(beneficiaireUSD.value)}
                &Imputation=${encodeURIComponent(imputationUSD.value)}
                &Motif=${encodeURIComponent(motifUSD.value)}
                &Montant=${encodeURIComponent(montantDecUSD.value)}
                &Date=${encodeURIComponent(dateUSD.value)}
                &Text_Btn=${encodeURIComponent(bouton)}
                &Solde=${encodeURIComponent(Solde_dispo_usd)}`;
            console.log("📡 Lien appelé :", lien);
        
            Swal.fire({
                title: "Voulez-vous vraiment décaisser en USD ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui, décaisser",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Décaissement en cours...',
                        html: 'Veuillez patienter',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
        
                    fetch(lien)
                        .then(async response => {
                            const text = await response.text();
        
                            if (!response.ok) {
                                throw new Error(`Erreur HTTP : ${response.status}`);
                            }
        
                            if (!text) {
                                throw new Error("Réponse vide du serveur");
                            }
        
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error("❌ Erreur de parsing JSON :", e, "\nContenu brut :", text);
                                throw new Error("La réponse du serveur n'est pas un JSON valide.");
                            }
                        })
                        .then(data => {
                            console.log("✅ Réponse API :", data);
                            if (data.success) {
                                Swal.fire("✅ Succès", data.message, "success");
                                if (data.num_suivant) {
                                    numeroPieceDecUSD.value = data.num_suivant;
                                    console.log("🔢 Prochain numéro de pièce appliqué :", data.num_suivant);
                                }
        
                                AfficherSoldes();
                                afficherSolde_decaissement();
                            } else if (data.error) {
                                Swal.fire("❌ Erreur: Encaissement non effectué", data.message || "Une erreur est survenue.", "error");
                            } else {
                                Swal.fire("❗ Réponse inattendue", "Le serveur n'a pas renvoyé de message clair.", "warning");
                            }
                        })
                        .catch(error => {
                            console.error("❌ Erreur de requête :", error);
                            Swal.fire("❌ Erreur réseau", error.message || "Impossible de contacter le serveur.", "error");
                        });
                }
            });
        }
        

        if (texteBouton === "Décaisser CDF") {
            const bouton="decaissement_usd";
            const Solde_dispo_usd = nettoyerMontant(document.getElementById("solde_USD").innerText);
            lien = `D_Finance/API/API_Decaissement.php?
            Num_Pce=${encodeURIComponent(numeroPieceDecCDF.value)}
            &Compte=${encodeURIComponent(compteCDF.value)}
            &Beneficiaire=${encodeURIComponent(beneficiaireCDF.value)}
            &Imputation=${encodeURIComponent(imputationCDF.value)}
            &Motif=${encodeURIComponent(motifCDF.value)}&
            Montant=${encodeURIComponent(montantDecCDF.value)}
            &Date=${encodeURIComponent(dateCDF.value)}
            &Solde=${encodeURIComponent(Solde_dispo_usd)}
            &Text_Btn=${encodeURIComponent(bouton)}`;

            Swal.fire({
                title: "Voulez-vous vraiment décaisser en CDF ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui, décaisser",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Décaissement en cours...',
                        html: 'Veuillez patienter',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(lien)
                    .then(response => response.json())
                    .then(data => {
                        console.log("✅ Réponse API :", data);
                        if (data.success) {
                            Swal.fire("✅ Succès", data.message, "success");
                            if (data.num_suivant) {
                                // Met à jour automatiquement le champ numéro de pièce
                                numeroPieceDecCDF.value = data.num_suivant;
                                
                                console.log("🔢 Prochain numéro de pièce appliqué :", data.num_suivant);
                                console.log("✅ Réponse API :", data);

                            }
                            afficherSolde();
                            AfficherSoldess_decaissement();
                            // 🔄 Vider les autres champs
                           
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
            });
        }
    });
});




    let solde="";
    let solde__enc_cdf="";
    let montant_dec_usd="";
    let montant_dec_cdf="";

function afficherSolde_decaissement() {
    fetch("D_Finance/API/API_Solde_Decaisse.php")
        .then(response => response.json())
        .then(data => {
            console.log("🔍 Résultat JSON brut :", data); // 👈 Ajoute ceci
            
            document.getElementById("DecaissementUSD").innerText = data.Solde__dec_usd + " $";
            document.getElementById("DecaissementCDF").innerText = data.Solde__dec_cdf + " Fc";
            montant_dec_usd=data.Solde__dec_usd;
            montant_dec_cdf=data.Solde__dec_cdf;
            
            calculerMontantPermanent();
        })
        .catch(error => {
            console.error("❌ Erreur lors du chargement du solde CDF :", error);
        });
}


document.addEventListener("DOMContentLoaded", () => {
    AfficherSoldes();
    afficherSolde_decaissement(); // Chargement automatique au démarrage
    
    
});

document.getElementById("dateDecaissementUSD").addEventListener("change", function () {
    let selectedDate = this.value;
    
    if (!selectedDate) {
        console.error("Aucune date sélectionnée !");
        return;
    }
    envoyerDateAPI(selectedDate);
});
    
//selection du montant décaissé selon la date envoyéee
function envoyerDateAPI(formattedDate) {
    fetch("D_Finance/API/API_Solde_Decaisse.php", { 
        method: "POST", 
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ date: formattedDate }) 
    })
    .then(response => response.json())
    .then(data => {
        console.log("Solde récupéré :", data.Solde__dec_usd);
        document.getElementById("DecaissementUSD").innerText = data.Solde__dec_usd + " $";
        document.getElementById("DecaissementCDF").innerText = data.Solde__dec_cdf + " $";
       montant_dec_usd=data.Solde__dec_usd;
       montant_dec_cdf=data.Solde__dec_cdf;

       calculerMontantPermanent();
        //alert(`Solde mis à jour pour la date : ${data.date_reçue}`);
    })
    .catch(error => console.error("Erreur lors de l'envoi de la date :", error));
}
 
function AfficherSoldes() {
    fetch("D_Finance/API/API_Select_Solde.php")
        .then(response => response.json())
        .then(data => {
            console.log("🔍 Résultat JSON brut :", data); // 👈 Ajoute ceci
            document.getElementById("solde_CDF").innerText = data.Solde_cdf + " Fc";
            document.getElementById("solde_USD").innerText = data.Solde_usd + " $";
            solde=data.Solde_usd;
            solde__enc_cdf=data.Solde_cdf;
            calculerMontantPermanent();
        })
        .catch(error => {
            console.error("❌ Erreur lors du chargement du solde CDF :", error);
        });
}


function calculerMontantPermanent() {
    const encaissement = nettoyerMontant(solde);
    const decaissement = nettoyerMontant(montant_dec_usd);

    const encaissement_cdf = nettoyerMontant(solde__enc_cdf);
    const decaissement_cdf = nettoyerMontant(montant_dec_cdf);

console.log("encaissement USD"+encaissement);
console.log("decaissement USD"+decaissement);
console.log("encaissement CDF"+encaissement_cdf);
console.log("decaissement CDF"+decaissement_cdf);

    if (!isNaN(encaissement) && !isNaN(decaissement)) {
        const montantPermanent = encaissement - decaissement;
        console.log("💰 Montant Permanent USD :", montantPermanent.toFixed(2));

        // Par exemple, l'afficher quelque part :
        document.getElementById("solde_USD").innerText = montantPermanent.toFixed(2) + " $";
    } else {
        console.warn("❗ Impossible de calculer : valeurs non numériques");
    }
    if (!isNaN(encaissement_cdf) && !isNaN(decaissement_cdf)) {
        const montantPermanent_cdf = encaissement_cdf - decaissement_cdf;
        console.log("💰 Montant Permanent CDF :", montantPermanent_cdf.toFixed(2));

        // Par exemple, l'afficher quelque part :
        document.getElementById("solde_CDF").innerText = montantPermanent_cdf.toFixed(2) + " Fc";
    } else {
        console.warn("❗ Impossible de calculer : valeurs non numériques");
    }
}
