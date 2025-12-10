
console.log('nous sommes dans le js select total paiement');  

const IdFiliere=document.getElementById("filiere");
const Idlieu_paie=document.getElementById("Lieu_paiement");
const idAnnee_Ac=document.getElementById("annee");
const Code_promo=document.getElementById("promotion");

if (Idlieu_paie) {  // Vérifie si l'élément existe
    Idlieu_paie.addEventListener('change', (event) => {
        var idAnnee_Acad = idAnnee_Ac.value;
        var idFiliere = IdFiliere.value;
        var lieu = Idlieu_paie.value;
        var code_promo = Code_promo.value;

        console.log("Lieu sélectionné :", lieu);
        AfficherTotal_Paie_Fac_Lieu(idAnnee_Acad, idFiliere, lieu, code_promo);
    });
} else {
    //console.warn("⚠️ L'élément 'Lieu_paiement' est introuvable !");
}
if (Idlieu_paie) {  // Vérifie si l'élément existe
  Code_promo.addEventListener('change',(event) => {
    var idAnnee_Acad=idAnnee_Ac.value;
    var idFiliere=IdFiliere.value;
    var lieu=Idlieu_paie.value;
    var code_promo=Code_promo.value;

    console.log("la valeur de filiere est :::: "+idFiliere)
    console.log("la valeur de annee est :::: "+idAnnee_Acad)
    console.log("la valeur du lieu est :::: "+lieu)
    console.log("la valeur du filtre est :::: "+code_promo)
    AfficherTotal_Paie_Fac_Lieu(idAnnee_Acad,idFiliere,lieu,code_promo);
  });
} else {
    //console.warn("⚠️ L'élément 'Lieu_paiement' est introuvable !");
}
if (Idlieu_paie) {  // Vérifie si l'élément existe
  IdFiliere.addEventListener('change',(event) => {
    var idAnnee_Acad=idAnnee_Ac.value;
    var idFiliere=IdFiliere.value;
    var lieu=Idlieu_paie.value;
    var code_promo=Code_promo.value;

    console.log("la valeur de filiere est :::: "+idFiliere)
    console.log("la valeur de annee est :::: "+idAnnee_Acad)
    console.log("la valeur du lieu est :::: "+lieu)
    
    AfficherTotal_Paie_Fac_Lieu(idAnnee_Acad,idFiliere,lieu,code_promo);
  });
} else {
    //console.warn("⚠️ L'élément 'Lieu_paiement' est introuvable !");
}
  function AfficherTotal_Paie_Fac_Lieu(idAnnee_Acad, idFiliere, lieu,code_promo) {
    var lien = 'D_Finance/API/API_Select_Total_Paiement.php?idfiliere=' + idFiliere +
               '&Id_annee_acad=' + idAnnee_Acad + '&Id_lieu=' + lieu+'&Code_promo='+code_promo;

    fetch(lien)
    .then(response => {
        if (!response.ok) {
            throw new Error("Erreur de réponse du serveur : " + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (!data || data.length === 0) {
            console.warn("Aucune donnée disponible !");
            return;
        }

        // Initialisation des valeurs par défaut
        let fraisAcademiques = { Lib_frais: "Frais Académiques", Montant: 0 };
        let enrollementMiSession = { Lib_frais: "Enrôlement à la Mi-Session", Montant: 0 };
        let enrollementGdeSession = { Lib_frais: "Enrôlement à la Grande-Session", Montant: 0 };
        let enrollementDeuxSession = { Lib_frais: "Enrôlement à la Deuxième-Session", Montant: 0 };

        let fraisAcademiquesCDF = { Lib_frais_FC: "Frais Académiques", Montant_FC: 0 };
        let enrollementMiSessionCDF = { Lib_frais_FC: "Enrôlement à la Mi-Session", Montant_FC: 0 };
        let enrollementGdeSessionCDF = { Lib_frais_FC: "Enrôlement à la Grande-Session", Montant_FC: 0 };
        let enrollementDeuxSessionCDF = { Lib_frais_FC: "Enrôlement à la Deuxième-Session", Montant_FC: 0 };

        // Vérification des données retournées
        data.forEach(info => {
            if (info.Lib_frais === "Frais Académiques") {
                fraisAcademiques = info;
            } else if (info.Lib_frais === "Enrôlement à la Mi-Session") {
                enrollementMiSession = info;
            } else if (info.Lib_frais === "Enrôlement à la Grande-Session") {
                enrollementGdeSession = info;
            } else if (info.Lib_frais === "Enrôlement à la Deuxième-Session") {
                enrollementDeuxSession = info;
            }

            // Franc Congolais (CDF)
            if (info.Lib_frais_FC === "Frais Académiques") {
                fraisAcademiquesCDF = info;
            } else if (info.Lib_frais_FC === "Enrôlement à la Mi-Session") {
                enrollementMiSessionCDF = info;
            } else if (info.Lib_frais_FC === "Enrôlement à la Grande-Session") {
                enrollementGdeSessionCDF = info;
            } else if (info.Lib_frais_FC === "Enrôlement à la Deuxième-Session") {
                enrollementDeuxSessionCDF = info;
            }
        });

        // Mise à jour de l'affichage en dollars ($)
        document.getElementById("titreFA_USD").innerText = fraisAcademiques.Lib_frais;
        document.getElementById("Total_FA_USD").innerText = "$ " + fraisAcademiques.Montant;

        document.getElementById("titre_Enrol_S1_USD").innerText = enrollementMiSession.Lib_frais;
        document.getElementById("Total_Enrol_S1_USD").innerText = "$ " + enrollementMiSession.Montant;

        document.getElementById("titre_Enrol_S2_USD").innerText = enrollementGdeSession.Lib_frais;
        document.getElementById("Total_Enrol_S2_USD").innerText = "$ " + enrollementGdeSession.Montant;

        document.getElementById("titre_Enrol_S3_USD").innerText = enrollementDeuxSession.Lib_frais;
        document.getElementById("Total_Enrol_S3_USD").innerText = "$ " + enrollementDeuxSession.Montant;

        // Mise à jour de l'affichage en Francs Congolais (CDF)
        document.getElementById("titre_FA_CDF").innerText = fraisAcademiquesCDF.Lib_frais_FC;
        document.getElementById("Total_FA_CDF").innerText = fraisAcademiquesCDF.Montant_FC + " CDF";

        document.getElementById("titre_Enrol_S1_CDF").innerText = enrollementMiSessionCDF.Lib_frais_FC;
        document.getElementById("Total_Enrol_S1_CDF").innerText = enrollementMiSessionCDF.Montant_FC + " CDF";

        document.getElementById("titre_Enrol_S2_CDF").innerText = enrollementGdeSessionCDF.Lib_frais_FC;
        document.getElementById("Total_Enrol_S2_CDF").innerText = enrollementGdeSessionCDF.Montant_FC + " CDF";

        document.getElementById("titre_Enrol_S3_CDF").innerText = enrollementDeuxSessionCDF.Lib_frais_FC;
        document.getElementById("Total_Enrol_S3_CDF").innerText = enrollementDeuxSessionCDF.Montant_FC + " CDF";
    })
    .catch(error => {
        // Gestion des erreurs améliorée
        console.error("Erreur lors de la récupération des données :", error);
    });

}

function convertirMontantEnLettres(nombre) {
    const unite = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf"];
    const dizaine = ["", "dix", "vingt", "trente", "quarante", "cinquante", "soixante", "soixante-dix", "quatre-vingt", "quatre-vingt-dix"];
    const dizaineSpeciale = ["dix", "onze", "douze", "treize", "quatorze", "quinze", "seize", "dix-sept", "dix-huit", "dix-neuf"];

    // Cas du nombre zéro
    if (nombre === 0) return "zéro dollar";

    let resultat = "";

    // Séparer la partie entière et les centimes
    let partieEntiere = Math.floor(nombre);
    let centimes = Math.round((nombre - partieEntiere) * 100); // Prendre les 2 premiers chiffres après la virgule

    // Conversion des milliers (pas de récursion ici)
    let milliers = Math.floor(partieEntiere / 1000);
    let reste = partieEntiere % 1000;

    if (milliers > 0) {
        if (milliers > 1) {
            resultat += convertirMontantEnLettresPartielle(milliers) + " mille";
        } else {
            resultat += "mille";
        }
        if (reste > 0) resultat += " "; // Ajout d'un espace si il y a un reste
    }

    // Conversion des centaines
    let centaines = Math.floor(reste / 100);
    reste = reste % 100;

    if (centaines > 0) {
        // Ajout du mot "cent" (pas de "s" si exact)
        resultat += (centaines > 1 ? unite[centaines] + " cents" : "cent");
        if (reste > 0) resultat += " "; // Ajout d'un espace si il y a un reste
    }

    // Conversion des dizaines et unités
    if (reste > 0) {
        if (reste < 10) {
            resultat += unite[reste];
        } else if (reste < 20) {
            resultat += dizaineSpeciale[reste - 10];
        } else {
            let dix = Math.floor(reste / 10);
            let unites = reste % 10;

            resultat += dizaine[dix];
            if (unites > 0) resultat += "-" + unite[unites];
        }
    }

    // Ajout de "dollars"
    resultat = resultat.trim() + (partieEntiere === 1 ? " dollar" : " dollars");

    // Gestion des centimes
    if (centimes > 0) {
        let centimesEnLettres = convertirMontantEnLettresPartielle(centimes);
        resultat += " et " + centimesEnLettres + " centime" + (centimes === 1 ? "" : "s");
    }

    return resultat;
}

// Fonction pour convertir la partie des milliers sans récursion
function convertirMontantEnLettresPartielle(nombre) {
    const unite = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf"];
    const dizaine = ["", "dix", "vingt", "trente", "quarante", "cinquante", "soixante", "soixante-dix", "quatre-vingt", "quatre-vingt-dix"];
    const dizaineSpeciale = ["dix", "onze", "douze", "treize", "quatorze", "quinze", "seize", "dix-sept", "dix-huit", "dix-neuf"];
    
    let resultat = "";
    let dizainePart = Math.floor(nombre / 10);
    let unitePart = nombre % 10;

    // Conversion des dizaines
    if (dizainePart > 0) {
        resultat += dizaine[dizainePart];
        if (unitePart > 0) resultat += "-";
    }

    // Conversion des unités
    if (unitePart > 0) {
        resultat += unite[unitePart];
    }

    return resultat.trim();
}

// 🖱️ Appliquer l'effet au survol des montants
document.addEventListener("DOMContentLoaded", function() {
    const elementsMontant = document.querySelectorAll(".compteur");

    elementsMontant.forEach(element => {
        element.addEventListener("mouseover", function() {
            let montantText = element.innerText.replace(/[^0-9.,]/g, "");
            let montant = parseFloat(montantText.replace(',', '.'));

            // Vérification si le montant est valide (pas NaN)
            if (!isNaN(montant)) {
                let enLettres = convertirMontantEnLettres(montant);
                element.setAttribute("title", enLettres); // Affiche en tooltip
            } else {
                console.error("Montant invalide:", montantText); // Ajout d'un message d'erreur dans la console pour vérifier
            }
        });
    });


});


