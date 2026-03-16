// Rapport de caisse
console.log("✅ le JS Rapport caisse est lancé");

let dernierSolde = 0;

document.addEventListener("DOMContentLoaded", function () {
    const dateA = document.getElementById("date_A");
    const dateB = document.getElementById("date_B");
    const deviseSelect = document.getElementById("Select");
    const table = document.getElementById("tableRapport");

    const today = new Date().toISOString().split('T')[0];
    dateA.value = today;
    dateB.value = today;

    dateA.addEventListener("change", loadData);
    dateB.addEventListener("change", loadData);
    deviseSelect.addEventListener("change", loadData);

    loadData();

    function formatDateToDMY(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function loadData() {
        const d1 = dateA.value;
        const d2 = dateB.value;
        const type = deviseSelect.value;
        const formattedStart = formatDateToDMY(d1);
        const formattedEnd = formatDateToDMY(d2);

        const tableHeader = document.querySelector(".table-header u");
        if (tableHeader) {
            tableHeader.textContent = `Tableau des Opérations du ${formattedStart} au ${formattedEnd}`;
        }

        const url = `D_Finance/API/API_Rapport_Caisse.php?type=${type}&date1=${d1}&date2=${d2}`;

        fetch(url)
            .then(response => response.json())
            .then(result => {
                console.log("Réponse API =", result);
            
                console.log("TYPE DE RESULT =", typeof result, Array.isArray(result));

                let encaissements = [];
                let decaissements = [];

                if (type === "USD") {
                    encaissements = result.encaissements_usd || [];
                    decaissements = result.decaissements_usd || [];
                }

                if (type === "CDF") {
                    encaissements = result.encaissements_cdf || [];
                    decaissements = result.decaissements_cdf || [];
                }


               // 👉 Trier séparément
                    encaissements.sort((a, b) => a.Imputation - b.Imputation);
                    decaissements.sort((a, b) => a.Imputation - b.Imputation);



                const thead = table.querySelector("thead");
                const tbody = table.querySelector("tbody");
                thead.innerHTML = "";
                tbody.innerHTML = "";
            
                // Ligne 1 : Recettes / Dépenses
                const trHead1 = document.createElement("tr");
                trHead1.innerHTML = `
                    <th colspan="3">Recettes</th>
                    <th colspan="3">Dépenses</th>
                `;
                thead.appendChild(trHead1);
            
                // Ligne 2 : Titres colonnes
                const trHead2 = document.createElement("tr");
                trHead2.innerHTML = `
                    <th>Compte</th><th>Description</th><th>Montant</th>
                    <th>Compte</th><th>Description</th><th>Montant</th>
                `;
                thead.appendChild(trHead2);
            
                let recettesTotal = 0;
                let depensesTotal = 0;
            
                if (encaissements.length === 0) {
            
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td colspan="6" style="text-align:center; font-weight:bold;">
                            Aucune opération trouvée pour cette période
                        </td>
                    `;
                    tbody.appendChild(tr);
            
                } else {
                        // ---------------------------
                        // 1️⃣ AFFICHAGE ALIGNÉ (Recettes + Dépenses)
                        // ---------------------------

                        // Plus grande longueur entre recettes et dépenses
                        const max = Math.max(encaissements.length, decaissements.length);

                        for (let i = 0; i < max; i++) {

                            const rec = encaissements[i] || null;
                            const dep = decaissements[i] || null;

                            const tr = document.createElement("tr");

                            // bloc recettes
                            const recetteHTML = rec ? `
                                <td>${rec.Imputation}</td>
                                <td>${rec.Intitul_compte}</td>
                                <td style="text-align:right;">${parseFloat(rec.MontantTotal).toLocaleString()}</td>
                            ` : `
                                <td></td><td></td><td></td>
                                
                            `;

                            // bloc dépenses
                            const depenseHTML = dep ? `
                                <td>${dep.Imputation}</td>
                                <td>${dep.Intitul_compte}</td>
                                <td style="text-align:right;">${parseFloat(dep.MontantTotal).toLocaleString()}</td>
                            ` : `
                                <td></td><td></td><td></td>
                            `;

                            tr.innerHTML = recetteHTML + depenseHTML;
                            tbody.appendChild(tr);

                            if (rec) recettesTotal += parseFloat(rec.MontantTotal);
                            if (dep) depensesTotal += parseFloat(dep.MontantTotal);
                        }

                  
                }
            
                // Totaux
                const trTotal = document.createElement("tr");
                trTotal.style.backgroundColor = "OLIVE";
                trTotal.style.color = "white";
                trTotal.style.fontWeight = "bold";
            
                trTotal.innerHTML = `
                    <td></td><td style="text-align:right;">Total Recettes</td>
                    <td style="text-align:right;">${recettesTotal.toLocaleString()}</td>
            
                    <td></td><td style="text-align:right;">Total Dépenses</td>
                    <td style="text-align:right;">${depensesTotal.toLocaleString()}</td>
                `;
            
                tbody.appendChild(trTotal);
            
               
                const trReport = document.createElement("tr");
                trReport.style.backgroundColor = "OLIVE";
                trReport.style.color = "white";
                trReport.style.fontWeight = "bold";
            
                trReport.innerHTML = `
                    <td></td><td style="text-align:right;">Report</td>
                    <td style="text-align:right;">${(recettesTotal - depensesTotal).toLocaleString()}</td>
                    <td></td><td></td><td></td>
                `;
            
                tbody.appendChild(trReport);
            

                 // Solde CDF
                 const trSolde = document.createElement("tr");
                 trSolde.style.backgroundColor = "OLIVE";
                 trSolde.style.color = "white";
                 trSolde.style.fontWeight = "bold";
             
                 trSolde.innerHTML = `
                     <td></td><td style="text-align:right;">Solde</td>
                     <td></td>
                     <td></td><td></td><td style="text-align:right;">${(recettesTotal - depensesTotal).toLocaleString()}</td>
                 `;
             
                 tbody.appendChild(trSolde);
            })
            
            .catch(error => {
                console.error("❌ Erreur API :", error);
                alert("Erreur lors du chargement des données.");
            });
    }
});

// ---------------- EXPORT EXCEL ----------------
document.getElementById('btn-action').addEventListener('click', function () {
    const table = document.getElementById('tableRapport');
    const tbody = table.querySelector("tbody");

    const headers = ["N°", "Compte", "Montant"];
    const rows = [];
    rows.push(headers);

    let montantTotal = 0;

    tbody.querySelectorAll("tr").forEach((row, index, rowList) => {
        const rowData = [];
        row.querySelectorAll("td").forEach((cell, i) => {
            const text = cell.textContent.trim();
            rowData.push(text);

            if (i === 2 && index < rowList.length - 1) {
                const value = parseFloat(text.replace(/\s|\$/g, '')) || 0;
                montantTotal += value;
            }
        });
        rows.push(rowData);
    });

    const totalRow = ["Totaux", "", montantTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) + " $"];
    rows.push(totalRow);

    const ws = XLSX.utils.aoa_to_sheet(rows);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Rapport Caisse");
    XLSX.writeFile(wb, "Rapport_Caisse.xlsx");
});

// ---------------- IMPRESSION ----------------
function imprimer() {
    const btn1 = document.getElementById('btn-action'); btn1.style.display = 'none';
    const btn2 = document.getElementById('btn-print'); btn2.style.display = 'none';

    const contenus = document.getElementById('entetepage').innerHTML;
    const contenu = document.getElementById('tablePrint').innerHTML;

    const fenetreImpression = window.open('', '', 'height=600,width=800');
    fenetreImpression.document.write('<html><head><title>Impression Rapport de Caisse</title>');
    fenetreImpression.document.write('<style>');
    fenetreImpression.document.write('body { font-family: Arial, sans-serif; }');
    fenetreImpression.document.write('table { width: 100%; border-collapse: collapse; }');
    fenetreImpression.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
    fenetreImpression.document.write('thead { background-color: midnightblue; color: white; }');
    fenetreImpression.document.write('</style>');
    fenetreImpression.document.write('</head><body>');
    fenetreImpression.document.write(contenus);
    fenetreImpression.document.write(contenu);
    fenetreImpression.document.write('</body></html>');
    fenetreImpression.document.close();
    fenetreImpression.print();
    fenetreImpression.close();

    btn1.style.display = 'block';
    btn2.style.display = 'block';
}
