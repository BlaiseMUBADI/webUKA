<?php


// Récupérer toutes les pièces avec leurs lignes
$query = $con->query("SELECT * FROM autorisation_depense ORDER BY id DESC");
$autorisation_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Grouper par Num_pce
$grouped = [];
foreach ($autorisation_data as $ligne) {
    $grouped[$ligne['Num_pce']][] = $ligne;
}

$pieces = array_keys($grouped);
?>

<style>
.card-header { display: flex; justify-content: space-between; align-items: center; }
.slider-buttons { margin: 15px 0; }
</style>

<body>
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="text-primary text-center flex-grow-1 m-0">
          BON DE COMMANDE
        </h2>
    </div>

    <!-- Zone de recherche -->
    <div class="mb-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par Numéro de Pièce, Bénéficiaire, Motif..." onkeyup="filterTable()">
    </div>

    <div id="sliderContainer">
        <?php foreach($pieces as $index => $num_pce): 
            $lignes = $grouped[$num_pce];
            $devise = strpos(strtolower($num_pce), 'cdf') !== false ? 'CDF' : 'USD';
        ?>
        <div class="card mb-4 shadow p-3 slider-item" data-num-pce="<?php echo $num_pce; ?>" data-beneficiaire="<?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?>" data-motif="<?php echo htmlspecialchars($lignes[0]['Motif']); ?>" style="display: <?php echo $index < 3 ? 'block' : 'none'; ?>;">
                <div class="card-header">
                    <h5>
                        Numéro de pièce : <?php echo ($num_pce); ?>  
                        | Bénéficiaire : <span class="text-primary">
                            <?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?>
                        </span>
                    </h5>
                </div>

                <table class="table table-bordered table-striped mt-2">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Motif</th>
                            <th>Montant</th>
                            <th>Imputation</th>
                            <th>Date ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach($lignes as $ligne): ?>
                        <tr>
                            <td><?php echo $ligne['id']; ?></td>
                            <td><?php echo htmlspecialchars($ligne['Motif']); ?></td>
                            <td><?php echo number_format($ligne['Montant'], 2) . ' ' . $devise; ?></td>
                            <td><?php echo $ligne['Imputation']; ?></td>
                            <td><?php echo $ligne['Date_ajout']; ?></td>
                        </tr>
                        <?php $total += $ligne['Montant']; ?>
                        <?php endforeach; ?>
                        <tr class="table-secondary">
                            <td colspan="2" class="text-end"><strong>Total</strong></td>
                            <td><strong><?php echo number_format($total, 2) . ' ' . $devise; ?></strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <!-- Boutons Slider -->
        <div class="slider-buttons text-center">
            <button class="btn btn-secondary" onclick="precedent()">Précédent</button>
            <button class="btn btn-secondary" onclick="suivant()">Suivant</button>
        </div>
    </div>
</div>

<script>
let currentIndex = 0;
const items = document.querySelectorAll('.slider-item');
const itemsPerPage = 5;  // Afficher 3 éléments à la fois
let filteredItems = Array.from(items); // Liste des éléments filtrés

// Afficher les éléments à partir de l'index
function showSlide(index) {
    const start = index * itemsPerPage;
    const end = start + itemsPerPage;

    filteredItems.forEach((item, i) => {
        if (i >= start && i < end) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Précédent
function precedent() {
    if (currentIndex > 0) {
        currentIndex--;
        showSlide(currentIndex);
    }
}

// Suivant
function suivant() {
    if (currentIndex < Math.floor(filteredItems.length / itemsPerPage)) {
        currentIndex++;
        showSlide(currentIndex);
    }
}

// Fonction de filtrage
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    filteredItems = Array.from(items).filter(item => {
        const numPce = item.getAttribute('data-num-pce').toLowerCase();
        const beneficiaire = item.getAttribute('data-beneficiaire').toLowerCase();
        const motif = item.getAttribute('data-motif').toLowerCase();

        // Vérifier si l'élément correspond à la recherche
        return numPce.includes(input) || beneficiaire.includes(input) || motif.includes(input);
    });

    // Masquer tous les éléments
    items.forEach(item => item.style.display = 'none');

    // Afficher les éléments filtrés
    showSlide(0); // Réinitialiser à la première page des résultats filtrés

    // Réinitialiser l'index de navigation
    currentIndex = 0;
}

document.addEventListener("DOMContentLoaded", function () {
    showSlide(currentIndex);  // Initialiser avec les 3 premiers éléments
});
document.addEventListener("DOMContentLoaded", function () {
    showSlide(currentIndex);

    document.querySelectorAll("table tbody tr").forEach(row => {
        const id = row.cells[0]?.textContent?.trim();
        if (!id) return;

        for (let i = 1; i < row.cells.length - 1; i++) {
            const cell = row.cells[i];

            cell.addEventListener("dblclick", () => {
                if (cell.getAttribute("contenteditable") === "true") return;
                cell.setAttribute("contenteditable", "true");
                cell.focus();
                cell.dataset.oldValue = cell.textContent.trim();
            });

            cell.addEventListener("blur", () => {
                if (cell.getAttribute("contenteditable") !== "true") return;

                cell.removeAttribute("contenteditable");
                let newValue = cell.textContent.trim();
                const oldValue = cell.dataset.oldValue;
                const columnName = getColumnName(i);

                if (newValue === oldValue) return;

                if (columnName === "Montant") {
                    let numStr = newValue.replace(/[A-Za-z]/g, '').trim();
                    numStr = numStr.replace(/[^0-9,.-]/g, '');
                    if (numStr.indexOf(',') !== -1 && numStr.indexOf('.') !== -1) {
                        if (numStr.lastIndexOf(',') > numStr.lastIndexOf('.')) {
                            numStr = numStr.replace(/\./g, '').replace(/,/g, '.');
                        } else {
                            numStr = numStr.replace(/,/g, '');
                        }
                    } else if (numStr.indexOf(',') !== -1) {
                        numStr = numStr.replace(/,/g, '.');
                    }

                    const montant = parseFloat(numStr);
                    if (isNaN(montant)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Montant invalide',
                            text: 'Exemple : 500.00 ou 1 200,50'
                        });
                        cell.textContent = oldValue;
                        return;
                    }

                    fetch('D_Finance/API/Modif_Autorisation.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id: id,
                            field: "Montant",
                            value: montant
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const tr = cell.closest("tr");
                            const card = tr.closest(".slider-item");
                            const numPce = card.getAttribute("data-num-pce");
                            const devise = numPce.toLowerCase().includes("cdf") ? "CDF" : "USD";
                            cell.textContent = montant.toFixed(2) + " " + devise;

                            const table = tr.closest("table");
                            const rows = table.querySelectorAll("tbody tr");
                            let total = 0;
                            rows.forEach(r => {
                                const montantCell = r.cells[2];
                                if (montantCell && !r.classList.contains("table-secondary")) {
                                    let txt = montantCell.textContent.replace(/[A-Za-z]/g, '').trim();
                                    txt = txt.replace(/[^0-9,.-]/g, '');
                                    if (txt.indexOf(',') !== -1 && txt.indexOf('.') !== -1) {
                                        if (txt.lastIndexOf(',') > txt.lastIndexOf('.')) {
                                            txt = txt.replace(/\./g, '').replace(/,/g, '.');
                                        } else {
                                            txt = txt.replace(/,/g, '');
                                        }
                                    } else if (txt.indexOf(',') !== -1) {
                                        txt = txt.replace(/,/g, '.');
                                    }
                                    const val = parseFloat(txt);
                                    if (!isNaN(val)) total += val;
                                }
                            });

                            const totalCell = table.querySelector(".table-secondary td:nth-child(2)");
                            if (totalCell) {
                                totalCell.innerHTML = "<strong>" + total.toFixed(2) + " " + devise + "</strong>";
                            }

                            cell.style.backgroundColor = "#d4edda";
                            setTimeout(() => cell.style.backgroundColor = "", 800);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Action refusée',
                                text: data.message,
                                confirmButtonColor: '#3085d6'
                            });
                            cell.textContent = oldValue;
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur serveur',
                            text: 'Erreur de communication avec le serveur.'
                        });
                        cell.textContent = oldValue;
                    });

                } else {
                    fetch('D_Finance/API/Modif_Autorisation.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id: id,
                            field: columnName,
                            value: newValue
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            cell.style.backgroundColor = "#d4edda";
                            setTimeout(() => cell.style.backgroundColor = "", 800);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Action refusée',
                                text: data.message,
                                confirmButtonColor: '#3085d6'
                            });
                            cell.textContent = oldValue;
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur serveur',
                            text: 'Erreur de communication avec le serveur.'
                        });
                        cell.textContent = oldValue;
                    });
                }
            });
        }
    });
});

// Correspondance index → colonne
function getColumnName(index) {
    switch (index) {
        case 1: return "Motif";
        case 2: return "Montant";
        case 3: return "Imputation";
        default: return "";
    }
}

</script>
</body>
