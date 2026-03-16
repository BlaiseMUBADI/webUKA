
// ================== JS ENCAISSEMENT (SANS CUMUL & SANS IMPRESSION) ==================
console.log('✅ JS Encaissement chargé (version nettoyée)');

// ================== VARIABLES GLOBALES ==================
let soldeUSD = 0;
let soldeCDF = 0;





// ================== BOUTONS ==================
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-Action');
    if (!btn) return;
        e.preventDefault(); // ⛔ empêche submit
        e.stopPropagation();
    const action = btn.innerText.trim();
    console.log('🖱️ Bouton détecté :', action);

    if (action === 'Encaisser USD') {
        confirmerEncaissement('USD');
    }

    if (action === 'Encaisser CDF') {
        confirmerEncaissement('CDF');
    }
});



// ================== CONFIRMATION ==================
function confirmerEncaissement(devise) {
    Swal.fire({
        title: 'Confirmer l\'encaissement',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, enregistrer',
        cancelButtonText: 'Annuler'
    }).then(result => {
        if (!result.isConfirmed) return;

        if (devise === 'USD') enregistrerUSD();
        if (devise === 'CDF') enregistrerCDF();
    });
}

// ================== ENREGISTREMENT USD ==================
function enregistrerUSD() {

    const Annee = document.getElementById("annee");

    const num_pceUSD    = document.getElementById("numeroPieceUSD");
    const motifUSD      = document.getElementById("motifVersementUSD");
    const serviceUSD    = document.getElementById("libelleServiceUSD");
    const montantUSD    = document.getElementById("montantUSD");
    const dateUSD       = document.getElementById("dateVersementUSD");
    const deposantUSD   = document.getElementById("Deposant_usd");
    const imputationUSD = document.getElementById("ImputationEncUSD");

    // Date automatique
    const now = new Date();
    dateUSD.value = now.toISOString().slice(0, 16);

    // Objet à envoyer
    const payload = {
        Num_Pce: num_pceUSD.value,
        Text_Btn: "Encaisser USD",
        Motif_USD: motifUSD.value,
        Idser_USD: serviceUSD.value,
        Montant_USD: montantUSD.value,
        Date_op_usd: dateUSD.value,
        Deposant_usd: deposantUSD.value,
        Imputation_usd: imputationUSD.value,
        AnneeAcad: Annee.value
    };

    fetch('D_Finance/API/API_Encaissement_Post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {

        // ✅ CETTE PARTIE NE CHANGE PAS
        if (!data.success) throw new Error(data.message);

        Swal.fire('✅ Succès', 'Encaissement ajouté à la pièce n° '+ num_pceUSD.value+ ' avec succès', 'success');
        resetChamps('USD');
        rafraichirNumeros();
        chargerSolde();
    })
    .catch(err => {
        Swal.fire('❌ Erreur', err.message, 'error');
    });
}


// ================== ENREGISTREMENT CDF ==================
// ================== ENREGISTREMENT CDF ==================
function enregistrerCDF() {

    const Annee = document.getElementById("annee");

    const num_pceCDF     = document.getElementById("numeroPieceCDF");
    const motifCDF       = document.getElementById("motifVersementCDF");
    const serviceCDF     = document.getElementById("libelleServiceCDF");
    const montantCDF     = document.getElementById("montantCDF");
    const dateCDF        = document.getElementById("dateVersementCDF");
    const deposantCDF    = document.getElementById("Deposant_cdf");
    const imputationCDF  = document.getElementById("ImputationEncCDF");

    // Date automatique
    const now = new Date();
    dateCDF.value = now.toISOString().slice(0, 16);

    // Données à envoyer
    const payload = {
        Num_Pce: num_pceCDF.value,
        Text_Btn: "Encaisser CDF",
        Motif_CDF: motifCDF.value,
        Idser_CDF: serviceCDF.value,
        Montant_CDF: montantCDF.value,
        Date_op_CDF: dateCDF.value,
        Deposant_cdf: deposantCDF.value,
        Imputation_cdf: imputationCDF.value,
        AnneeAcad: Annee.value
    };

    fetch('D_Finance/API/API_Encaissement_Post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {

        // ✅ CETTE PARTIE NE CHANGE PAS
        if (!data.success) throw new Error(data.message);

        Swal.fire('✅ Succès', 'Encaissement ajouté à la pièce n° '+num_pceCDF.value+ ' avec succès', 'success');
        resetChamps('CDF');
        rafraichirNumeros();
        chargerSolde();
    })
    .catch(err => {
        Swal.fire('❌ Erreur', err.message, 'error');
    });
}

// ================== RESET CHAMPS ==================
function resetChamps(devise) {

    if (devise === 'USD') {
        document.getElementById("motifVersementUSD").value = '';
        document.getElementById("montantUSD").value = '';

        $('#libelleServiceUSD').val(null).trigger('change');
        $('#ImputationEncUSD').val(null).trigger('change');
    }

    if (devise === 'CDF') {
        document.getElementById("motifVersementCDF").value = '';
        document.getElementById("montantCDF").value = '';

        $('#libelleServiceCDF').val(null).trigger('change');
        $('#ImputationEncCDF').val(null).trigger('change');
    }
}


// ================== SOLDES ==================


// ================== NUMÉROS DE PIÈCES ==================
function rafraichirNumeros() {
    fetch('D_Finance/API/rafraichirPieces.php')
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                //num_pceUSD.value = d.numUSD;
                //num_pceCDF.value = d.numCDF;
            }
        });
}








//GESTION DU MODALE

document.getElementById("btn-usd").addEventListener("click", function () {
    const modalEl = document.getElementById("modalEncaissementUSD");
    const modal = new bootstrap.Modal(modalEl);

    modal.show();

    // Quand le modal est complètement visible
    modalEl.addEventListener('shown.bs.modal', function () {
        $('#libelleServiceUSD').select2({
            dropdownParent: $('#modalEncaissementUSD'),
            width: '100%'
        });
        
        $('#ImputationEncUSD').select2({
            dropdownParent: $('#modalEncaissementUSD'),
            width: '100%'
        });
    }, { once: true }); // pour éviter double initialisation
});

document.getElementById("btn-cdf").addEventListener("click", function () {
    const modalEl = document.getElementById("modalEncaissementCDF");
    const modal = new bootstrap.Modal(modalEl);

    modal.show();

    // Quand le modal est complètement visible
    modalEl.addEventListener('shown.bs.modal', function () {
        $('#libelleServiceUSD').select2({
            dropdownParent: $('#modalEncaissementCDF'),
            width: '100%'
        });
        
        $('#ImputationEncUSD').select2({
            dropdownParent: $('#modalEncaissementCDF'),
            width: '100%'
        });
    }, { once: true }); // pour éviter double initialisation
});

 

   

async function chargerSolde() {
    const soldecdf = document.getElementById('solde_CDF');
    const soldeusd = document.getElementById('solde_USD');
    try {
        const res = await fetch('D_Finance/API/API_Select_Solde.php');
        const data = await res.json();

        const soldeCDF =
            parseFloat(data.Solde_cdf.replace(/,/g, '')) -
            parseFloat(data.Solde__dec_cdf.replace(/,/g, ''));

        const soldeUSD =
            parseFloat(data.Solde_usd.replace(/,/g, '')) -
            parseFloat(data.Solde__dec_usd.replace(/,/g, ''));

        console.log("le solde est " + soldeUSD);

        // ✅ ON MODIFIE LE TEXTE, PAS LA VARIABLE
        soldecdf.textContent = soldeCDF.toLocaleString() + " CDF";
        soldeusd.textContent = soldeUSD.toLocaleString() + " USD";

    } catch (err) {
        console.error("Erreur lors du chargement des soldes :", err);
    }
}


document.addEventListener("DOMContentLoaded", function () {
   
    chargerSolde();
   
    rafraichirNumeros();
    const dateCDF = document.getElementById("dateVersementCDF");
    const dateUSD = document.getElementById("dateVersementUSD");

    const now = new Date();
    
    // Format YYYY-MM-DDTHH:MM
    const formatted = now.toISOString().slice(0,16);

    
    if (dateUSD) dateUSD.value = formatted;
    if (dateCDF) dateCDF.value = formatted;
  
});

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
const libelleDevise = {
    USD: { singulier: "dollar américain", pluriel: "dollars américains", centime: "centime", centimes: "centimes" },
    CDF: { singulier: "franc congolais", pluriel: "francs congolais", centime: "centime", centimes: "centimes" },
    EUR: { singulier: "euro", pluriel: "euros", centime: "centime", centimes: "centimes" },
};
let devise = "USD"; // défaut

function enLettresMontant(nombre, devise) {
    const entier = Math.floor(nombre);
    const decimal = Math.round((nombre - entier) * 100);
    const unit = (entier === 1) ? libelleDevise[devise].singulier : libelleDevise[devise].pluriel;
    const centime = (decimal === 1) ? libelleDevise[devise].centime : libelleDevise[devise].centimes;
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