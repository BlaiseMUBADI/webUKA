// CLOTURE CDF
console.log('CLOTIRE OPERATION');
document.getElementById("btnCloturerCDF").addEventListener("click", function () {
    const dateNow = new Date();
    const dateSolde = dateNow.toISOString().slice(0, 19).replace("T", " "); // Format : "YYYY-MM-DD HH:mm:ss"

    const montant = document.getElementById("soldeFinalCDF").value;
    const observations = document.getElementById("observationsClotureCDF").value.trim();
    const devise = "CDF"; // Devise fixée ici

    console.log("le montant"+montant);
    console.log("obs"+observations);
    console.log("devise"+devise);


    if (!montant) {
        alert("Veuillez renseigner le montant.");
        return;
    }

    fetch("D_Finance/API/enregistrer_solde.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            date_solde: dateSolde,
            devise: devise,
            montant: montant,
            observations: observations
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire("Succès ✅", "Clôture CDF enregistrée avec succès !", "success");
            localStorage.setItem("cdfClotureDate", dateNow.toISOString().split("T")[0]);
            desactiverFormulairesCDF();
        } else {
            Swal.fire("Erreur ❌", "Erreur lors de l'enregistrement : " + data.message, "error");
        }
    })

    .catch(error => {
        console.error("Erreur fetch :", error);
        Swal.fire("Erreur ❌", "Erreur réseau ou serveur. Veuillez réessayer.", "error");
    });

});

// Désactivation des formulaires CDF
function desactiverFormulairesCDF() {
    const formElements = document.querySelectorAll(
        "#collapseBoxCDF input, #collapseBoxCDF select, #collapseBoxCDF button," +
        "#btnCloturerCDF"
    );

    formElements.forEach(el => {
        el.disabled = true;
    });
}

// CLOTURE USD
document.getElementById("btnCloturerUSD").addEventListener("click", function () {
    const dateNow = new Date();
    const dateSolde = dateNow.toISOString().slice(0, 19).replace("T", " "); // Format : "YYYY-MM-DD HH:mm:ss"

    const montant = document.getElementById("soldeFinalUSD").value;
    const observations = document.getElementById("observationsClotureUSD").value.trim();
    const devise = "USD"; // Devise fixée ici

 console.log("le montant "+montant);
    console.log("obs "+observations);
    console.log("devise "+devise);
    console.log("date "+dateSolde);

    if (!montant) {
        alert("Veuillez renseigner le montant.");
        return;
    }

    fetch("D_Finance/API/enregistrer_solde.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            date_solde: dateSolde,
            devise: devise,
            montant: montant,
            observations: observations
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire("Succès ✅", "Clôture USD enregistrée avec succès !", "success");
            localStorage.setItem("usdClotureDate", dateNow.toISOString().split("T")[0]);
            desactiverFormulairesUSD();
        } else {
            Swal.fire("Erreur ❌", "Erreur lors de l'enregistrement : " + data.message, "error");
        }
    })


    .catch(error => {
        console.error("Erreur fetch :", error);
        Swal.fire("Erreur ❌", "Erreur réseau ou serveur. Veuillez réessayer.", "error");
    }); 
});

// Désactivation des formulaires USD
function desactiverFormulairesUSD() {
    const formElements = document.querySelectorAll(
        "#collapseBoxUSD input, #collapseBoxUSD select, #collapseBoxUSD button," +
        "#btnCloturerUSD"
    );

    formElements.forEach(el => {
        el.disabled = true;
    });
}

// Vérification des clôtures existantes au chargement de la page
window.addEventListener("DOMContentLoaded", function () {
    const aujourdHui = new Date().toISOString().split("T")[0];

    // Vérifier la clôture CDF
    fetch("D_Finance/API/verifier_cloture.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ devise: "CDF", date: aujourdHui })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.clotureExiste) {
            desactiverFormulairesCDF();
        }
    });

    // Vérifier la clôture USD
    fetch("D_Finance/API/verifier_cloture.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ devise: "USD", date: aujourdHui })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.clotureExiste) {
            desactiverFormulairesUSD();
        }
    });
});

