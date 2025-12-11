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
            .then(data => {
                const thead = table.querySelector("thead");
                const tbody = table.querySelector("tbody");
                thead.innerHTML = "";
                tbody.innerHTML = "";

                const trHead = document.createElement("tr");
                const headers = ["N°", "Compte", "Montant"];
                headers.forEach(header => {
                    const th = document.createElement("th");
                    th.textContent = header;
                    trHead.appendChild(th);
                });
                thead.appendChild(trHead);

                const grouped = {};
                data.forEach(item => {
                    if (!grouped[item.Imputation]) {
                        grouped[item.Imputation] = [];
                    }
                    grouped[item.Imputation].push(item);
                });

                let index = 1;
                let montantTotal = 0;

                for (const items of Object.values(grouped)) {
                    items.forEach(item => {
                        const tr = document.createElement("tr");

                        const tdIndex = document.createElement("td");
                        tdIndex.textContent = index++;

                        const tdImputation = document.createElement("td");
                        tdImputation.textContent = item.Imputation+" - "+item.Intitul_compte;

                        const tdMontant = document.createElement("td");
                        const montant = parseFloat(item.MontantTotal) || 0;
                        tdMontant.textContent = montant.toLocaleString(undefined, { minimumFractionDigits: 2 }) + " $";

                        montantTotal += montant;

                        tr.appendChild(tdIndex);
                        tr.appendChild(tdImputation);
                        tr.appendChild(tdMontant);

                        tbody.appendChild(tr);
                    });
                }

                const trTotal = document.createElement("tr");
                trTotal.style.backgroundColor = "black";
                trTotal.style.color = "white";
                trTotal.style.fontWeight = "bold";

                const tdTotalLabel = document.createElement("td");
                tdTotalLabel.colSpan = 2;
                tdTotalLabel.style.textAlign = "right";
                tdTotalLabel.textContent = "Totaux généraux";

                const tdMontantTotal = document.createElement("td");
                tdMontantTotal.textContent = montantTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) + " $";

                trTotal.appendChild(tdTotalLabel);
                trTotal.appendChild(tdMontantTotal);
                tbody.appendChild(trTotal);
            })
            .catch(error => {
                console.error("❌ Erreur API :", error);
                alert("Erreur lors du chargement des données.");
            });
    }
});

// EXPORTATION EN EXCEL
document.getElementById('btn-action').addEventListener('click', function() {
    const table = document.getElementById('tableRapport');
    const tbody = table.querySelector("tbody");

    const headers = ["N°", "Imputation", "Montant"];
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

function imprimer() {
    var btn1 = document.getElementById('btn-action'); btn1.style.display='none';
    var btn2 = document.getElementById('btn-print'); btn2.style.display='none';

    var contenus = document.getElementById('entetepage').innerHTML;
    var contenu = document.getElementById('tablePrint').innerHTML;

    var fenetreImpression = window.open('', '', 'height=600,width=800');
    fenetreImpression.document.write('<html><head><title>Impression Rapport de Paie</title>');
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

    var btn1 = document.getElementById('btn-action'); btn1.style.display='block';
    var btn2 = document.getElementById('btn-print'); btn2.style.display='block';
}
