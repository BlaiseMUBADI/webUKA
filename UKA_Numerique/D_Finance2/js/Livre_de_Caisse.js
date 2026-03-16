let totalEncaisse = 0;
let totalDecaisse = 0;
let dernierSolde = 0;

document.addEventListener("DOMContentLoaded", function () {
    const dateA = document.getElementById("date_A");
    const dateB = document.getElementById("date_B");
    const deviseSelect = document.getElementById("Select");
    const table = document.getElementById("tableEncaissement_Dec");

    function ajusterSiWeekend(input) {
        const date = new Date(input.value);
        const jour = date.getDay(); // 0 = Dimanche, 6 = Samedi
    
        if (jour === 6) { // Samedi
            date.setDate(date.getDate() - 1); // Reculer d'un jour → Vendredi
            input.value = date.toISOString().split('T')[0];
            alert("Le samedi n'est pas autorisé. Date ajustée au vendredi précédent.");
        } else if (jour === 0) { // Dimanche
            date.setDate(date.getDate() - 2); // Reculer de deux jours → Vendredi
            input.value = date.toISOString().split('T')[0];
            alert("Le dimanche n'est pas autorisé. Date ajustée au vendredi précédent.");
        }
    }
    
    dateA.addEventListener("change", function () {
        ajusterSiWeekend(dateA);
        loadData();
    });
    
    dateB.addEventListener("change", function () {
        ajusterSiWeekend(dateB);
        loadData();
    });
    
    // 👉 Fonction pour récupérer le dernier jour ouvré
    function getDernierJourOuvre() {
        const date = new Date();
        let day = date.getDay(); // 0 = dimanche, 6 = samedi

        if (day === 0) {
            date.setDate(date.getDate() - 2); // dimanche → vendredi
        } else if (day === 1) {
            date.setDate(date.getDate() - 3); // lundi → vendredi
        } else {
            date.setDate(date.getDate()); // les autres jours → veille
        }

        return date.toISOString().split('T')[0];
    }

    // 📅 Initialiser les dates à la dernière date ouvrée
    const dernierJourOuvre = getDernierJourOuvre();
    dateA.value = dernierJourOuvre;
    dateB.value = dernierJourOuvre;

    deviseSelect.addEventListener("change", loadData);

    // Chargement initial
    loadData();

    function Afficher_date_au_format_jjmmaa(dateStr) {
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
    const formattedStart = Afficher_date_au_format_jjmmaa(d1);
    const formattedEnd = Afficher_date_au_format_jjmmaa(d2);
    
    const tableHeader = document.querySelector(".table-header u");
    if (tableHeader) {
        tableHeader.textContent = `Tableau des Encaissements et Décaissements du ${formattedStart} au ${formattedEnd}`;
    }

    const url = `D_Finance/API/API_Livre_Caisse.php?type=${type}&date1=${d1}&date2=${d2}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Vérifiez si l'API a renvoyé un avertissement
            if (data.warning) {
                alert(data.warning);  // Affiche l'avertissement dans une alerte
            }

            const thead = table.querySelector("thead");
            const tbody = table.querySelector("tbody");
            thead.innerHTML = "";
            tbody.innerHTML = "";

            const trHead = document.createElement("tr");
            const headers = ["N°", "Date", "Noms", "N° pièce", "Imputation", "Libellé", "Débit", "Crédit", "Solde"];
            headers.forEach(header => {
                const th = document.createElement("th");
                th.textContent = header;
                trHead.appendChild(th);
            });
            thead.appendChild(trHead);

            totalEncaisse = 0;
            totalDecaisse = 0;
            let solde = 0;
            let dateSolde = null;  // Déclaration de la variable pour la date du solde

            // Vérification et traitement du solde
            if (data.solde_periode !== undefined && data.solde_periode !== null) {
                solde = parseFloat(data.solde_periode.replace(/,/g, '')) || 0;
                dateSolde = data.date_solde || null;  // Récupère la date du solde
            } else if (data.error) {
                alert("Erreur de l'API : " + data.error);
                console.warn("Réponse API : ", data);
                return; // on arrête ici pour éviter d’afficher un tableau incomplet
            } else {
                alert("Erreur inattendue : données manquantes.");
                console.warn("Réponse API : ", data);
                return;
            }

            dernierSolde = 0;

            const trSoldeInitial = document.createElement("tr");
            const tdInitLabel = document.createElement("td");
            tdInitLabel.colSpan = 8;
            tdInitLabel.style.fontWeight = "bold";
            tdInitLabel.textContent = "Solde initial de la période";

            const tdInitSolde = document.createElement("td");
            tdInitSolde.style.fontWeight = "bold";
            tdInitSolde.textContent = solde.toLocaleString(undefined, { minimumFractionDigits: 2 });

            trSoldeInitial.appendChild(tdInitLabel);
            trSoldeInitial.appendChild(tdInitSolde);
            tbody.appendChild(trSoldeInitial);

            // Affichage de la date du solde dans l'interface
            if (dateSolde) {
                const soldeDateSpan = document.getElementById("soldeDateSpan");
                if (soldeDateSpan) {
                    soldeDateSpan.textContent = `Solde pris à la date du ${new Date(dateSolde).toLocaleDateString('fr-FR')}`;
                }
            }

            data.operations.forEach((item, index) => {
                if (item.Motif && item.Motif.toLowerCase() === "report") {
                    return;
                }

                const tr = document.createElement("tr");

                const tdIndex = document.createElement("td");
                tdIndex.textContent = index + 1;

                const tdDate = document.createElement("td");
                tdDate.textContent = item.Date_Oper.split(' ')[0];

                const tdBen = document.createElement("td");
                tdBen.textContent = item.Deposant;

                const tdNum = document.createElement("td");
                tdNum.textContent = item.Numero.replace(/\D/g, '');

                const tdImputation = document.createElement("td");
                tdImputation.textContent = item.Imputation;

                const tdMotif = document.createElement("td");
                tdMotif.textContent = item.Motif;

                const tdEncaisse = document.createElement("td");
                const montantEncaisse = parseFloat(item.Montant_Encaisse) || 0;
                tdEncaisse.textContent = montantEncaisse ? montantEncaisse.toLocaleString() : "";
                totalEncaisse += montantEncaisse;
                tdEncaisse.style.textAlign = "right";

                const tdDecaisse = document.createElement("td");
                const montantDecaisse = parseFloat(item.Montant_Decaisse) || 0;
                tdDecaisse.textContent = montantDecaisse ? montantDecaisse.toLocaleString() : "";
                totalDecaisse += montantDecaisse;
                tdDecaisse.style.textAlign = "right";;

                if (item.Type_Operation === "Encaissement") {
                    solde += montantEncaisse;
                } else if (item.Type_Operation === "Décaissement") {
                    solde -= montantDecaisse;
                }
                dernierSolde = solde;

                const tdSolde = document.createElement("td");
                tdSolde.textContent = solde.toLocaleString(undefined, { minimumFractionDigits: 2 });
                tdSolde.style.textAlign = "right";;
                tr.appendChild(tdIndex);
                tr.appendChild(tdDate);
                tr.appendChild(tdBen);
                tr.appendChild(tdNum);
                tr.appendChild(tdImputation);
                tr.appendChild(tdMotif);
                tr.appendChild(tdEncaisse);
                tr.appendChild(tdDecaisse);
                tr.appendChild(tdSolde);

                tbody.appendChild(tr);
            });

            const trTotal = document.createElement("tr");
            trTotal.style.backgroundColor = "black";
            trTotal.style.color = "white";
            trTotal.style.fontWeight = "bold";

            const tdTotalLabel = document.createElement("td");
            tdTotalLabel.colSpan = 6;
            tdTotalLabel.style.textAlign = "right";
            tdTotalLabel.textContent = "Totaux";

            const tdEncTotal = document.createElement("td");
            tdEncTotal.textContent = totalEncaisse.toLocaleString(undefined, { minimumFractionDigits: 2 }) +" "+ type;

            const tdDecTotal = document.createElement("td");
            tdDecTotal.textContent = totalDecaisse.toLocaleString(undefined, { minimumFractionDigits: 2 }) +" "+ type;

            const tdSoldeTotal = document.createElement("td");
            tdSoldeTotal.textContent = dernierSolde.toLocaleString(undefined, { minimumFractionDigits: 2 }) +" "+ type;

            trTotal.appendChild(tdTotalLabel);
            trTotal.appendChild(tdEncTotal);
            trTotal.appendChild(tdDecTotal);
            trTotal.appendChild(tdSoldeTotal);

            tbody.appendChild(trTotal);
        })
        .catch(error => {
            console.error("❌ Erreur API :", error);
            alert("Erreur lors du chargement des données.");
        });
}


    const aujourdhui = new Date();
    if (Object.prototype.toString.call(aujourdhui) === '[object Date]' && !isNaN(aujourdhui)) {
        const formattedDate = aujourdhui.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        const dateSpan = document.getElementById('dateSpan');
        if (dateSpan) {
            dateSpan.textContent = formattedDate;
        } else {
            console.error("L'élément <span> avec l'ID 'dateSpan' n'a pas été trouvé.");
        }
    } else {
        console.error("Erreur: today n'est pas un objet Date valide.");
    }
});

// EXPORTATION EN EXCEL
document.getElementById('btn-action').addEventListener('click', function () {
    const table = document.getElementById('tableEncaissement_Dec');
    const tbody = table.querySelector("tbody");

    const headers = ["N°", "Date", "Noms", "Numéro pièce", "Imputation", "Libellé", "Débit", "Crédit", "Solde"];
    const rows = [headers];

    tbody.querySelectorAll("tr").forEach((row) => {
        const rowData = [];
        row.querySelectorAll("td").forEach((cell) => {
            rowData.push(cell.textContent.trim());
        });
        rows.push(rowData);
    });

    const totalRow = [
        "Totaux", "", "", "", "", "",
        totalEncaisse.toLocaleString(undefined, { minimumFractionDigits: 2 }),
        totalDecaisse.toLocaleString(undefined, { minimumFractionDigits: 2 }),
        dernierSolde.toLocaleString(undefined, { minimumFractionDigits: 2 })
    ];
    rows.push(totalRow);

    const ws = XLSX.utils.aoa_to_sheet(rows);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Encaissements et Décaissements");

    XLSX.writeFile(wb, "Encaissements_et_Decaissements.xlsx");
});

function imprimer_livre_caisse() {
    var btn1 = document.getElementById('btn-action'); btn1.style.display = 'none';
    var btn2 = document.getElementById('btn-print'); btn2.style.display = 'none';
    var Sign = document.getElementById('sign'); Sign.style.display = 'block';

    var contenus = document.getElementById('entetepage').innerHTML;
    var contenu = document.getElementById('Print_Livre_Caisse').innerHTML;

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

    btn1.style.display = 'block';
    btn2.style.display = 'block';
}
