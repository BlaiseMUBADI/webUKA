document.addEventListener("DOMContentLoaded", function () {
    const anneeSelect = document.getElementById("annee");
    const agentSelect = document.getElementById("agent");
    const dateInput = document.getElementById("dateperc");

    const containerResults = document.querySelector(".results");
    const totalBox = document.querySelector(".total-box");

    const today = new Date().toISOString().split('T')[0];
    if (dateInput) dateInput.value = today;

    function chargerAgentsParAnnee(idAnnee) {
        const formData = new FormData();
        formData.append("idAnnee", idAnnee);

        fetch("D_Finance/API/API_Rapport_Guichet.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            agentSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            if (data.agents && data.agents.length > 0) {
                data.agents.forEach(agent => {
                    const opt = document.createElement("option");
                    opt.value = agent.Mat_agent;
                    opt.textContent = `${agent.Nom_agent} - ${agent.Prenom}`;
                    agentSelect.appendChild(opt);
                });
            }
        });
    }

    function chargerMontantsParAgent() {
        const matAgent = agentSelect.value;
        const datePaie = dateInput.value;
        const idLieu = 3; // fixe ou récupérable

        if (!matAgent || !datePaie) return;

        const formData = new FormData();
        formData.append("matAgent", matAgent);
        formData.append("datePaie", datePaie);
        formData.append("idLieu", idLieu);

        fetch("D_Finance/API/API_Rapport_Guichet.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            containerResults.innerHTML = `
                <div class="column column-cdf"></div>
                <div class="column column-usd"></div>
            `;
            let totalCDF = 0, totalUSD = 0;

            // Vérifier si les données existent
            if (data.operations && Array.isArray(data.operations)) {
                data.operations.forEach(op => {
                    const box = document.createElement("div");
                    box.className = "box" + (op.devise === "USD" ? " usd" : "");
                    box.innerHTML = `<h4>${op.motif} : ${parseFloat(op.montant).toLocaleString()} ${op.devise}</h4>`;

                    if (op.devise === "USD") {
                        containerResults.querySelector(".column-usd").appendChild(box);
                        totalUSD += parseFloat(op.montant);
                    } else {
                        containerResults.querySelector(".column-cdf").appendChild(box);
                        totalCDF += parseFloat(op.montant);
                    }
                });
            } else {
                // Si pas de données valides
                alert("Aucune donnée trouvée pour cet agent et cette date.");
            }

            totalBox.innerHTML = `Total perçu : ${totalCDF.toLocaleString()} CDF + ${totalUSD.toLocaleString()} $`;
        });
    }

    if (anneeSelect.value) chargerAgentsParAnnee(anneeSelect.value);
    anneeSelect.addEventListener("change", e => chargerAgentsParAnnee(e.target.value));
    agentSelect.addEventListener("change", chargerMontantsParAgent);
    dateInput.addEventListener("change", chargerMontantsParAgent);
});
