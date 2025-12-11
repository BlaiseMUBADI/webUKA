<?php
include("../Connexion_BDD/Connexion_1.php");

// === Récupération des données ===
$query = $con->query("SELECT * FROM autorisation_depense ORDER BY Num_pce, id");
$autorisation_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Groupement par numéro de pièce
$grouped = [];
foreach ($autorisation_data as $ligne) {
    $grouped[$ligne['Num_pce']][] = $ligne;
}

// États des autorisations
$status_query = $con->query("SELECT * FROM autoriser_depense");
$status_data = $status_query->fetchAll(PDO::FETCH_ASSOC);

$status_by_piece = [];
foreach ($status_data as $s) {
    $status_by_piece[$s['Num_pce']] = $s;
}

// === Filtrer : Niveau 2 autorisé ===
$filtered = [];
foreach ($grouped as $num_pce => $lignes) {
    if (!isset($status_by_piece[$num_pce])) continue;

    $etat = $status_by_piece[$num_pce];
    $niveau2 = strtolower(trim($etat['Niveau_2'] ?? ''));
    $niveau1 = strtolower(trim($etat['Niveau_1'] ?? ''));

    if ($niveau2 === 'autoriser') {
        $statut = ($niveau1 === '') ? 'en attente' :
                  (($niveau1 === 'rejeter') ? 'rejete' : 'autorise');

        $devise = (strpos(strtolower($num_pce), 'cdf') !== false) ? 'CDF' : 'USD';
        $filtered[] = [
            'num_pce' => $num_pce,
            'lignes' => $lignes,
            'statut' => $statut,
            'devise' => $devise
        ];
    }
}

// Tri initial
usort($filtered, function($a, $b) {
    return strcmp($a['devise'], $b['devise']);
});
?>

<style>
.card-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
.badge { font-size: 0.9em; }
.tri-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
.tri-container select, .tri-container input { width: 180px; }
.pagination-controls { text-align: center; margin-top: 20px; }

.tri-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.tri-container label {
    font-weight: 600;
    margin-right: 8px;
    color: #2c3e50;
}

.tri-container select,
.tri-container input {
    border-radius: 10px;
    border: 1px solid #ced4da;
    padding: 8px 12px;
    transition: all 0.2s ease-in-out;
}

.tri-container select:focus,
.tri-container input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.4);
    outline: none;
}

.tri-container #rechercheInput {
    width: 280px; /* Agrandit la zone de recherche */
}

@media (max-width: 768px) {
    .tri-container {
        flex-direction: column;
        align-items: stretch;
    }
    .tri-container > div {
        width: 100%;
    }
    .tri-container #rechercheInput {
        width: 100%;
    }
}
</style>

<div class="container">
   <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="text-primary text-center flex-grow-1 m-0">Suivi Autorisations</h2>
        <div style="width: 100px;"></div>
    </div>

    <!-- Filtres -->
    <div class="tri-container">
    <div>
        <label class="me-2 fw-bold">Filtrer devise :</label>
        <select id="filtreDevise" class="form-select form-select-sm d-inline-block">
            <option value="toutes">Toutes</option>
            <option value="CDF">CDF</option>
            <option value="USD">USD</option>
        </select>
    </div>

    <div>
        <label class="me-2 fw-bold">Filtrer statut :</label>
        <select id="filtreStatut" class="form-select form-select-sm d-inline-block">
            <option value="tous">Tous</option>
            <option value="autorise">Autorisé</option>
            <option value="en attente">En attente</option>
            <option value="rejete">Rejeté</option>
        </select>
    </div>

    <div>
        <label class="me-2 fw-bold">Recherche :</label>
        <input id="rechercheInput" type="text" class="form-control form-control-sm d-inline-block" placeholder="🔍 Bénéficiaire ou N° pièce...">
    </div>
</div>

    <!-- Conteneur cartes -->
    <div id="listeAutorisations">
        <?php if (empty($filtered)): ?>
            <div class="alert alert-info text-center">Aucune pièce trouvée.</div>
        <?php endif; ?>

        <?php foreach($filtered as $index => $data): 
            $num_pce = $data['num_pce'];
            $lignes = $data['lignes'];
            $statut = $data['statut'];
            $devise = $data['devise'];
            $benef = htmlspecialchars($lignes[0]['Beneficiaire']);
            $badgeClass = ($statut === 'en attente') ? 'bg-warning text-dark' :
                        (($statut === 'rejete') ? 'bg-danger' : 'bg-success');
        ?>
        <div class="carte-autorisation card mb-4 shadow p-3"
             data-devise="<?php echo $devise; ?>"
             data-statut="<?php echo $statut; ?>"
             data-beneficiaire="<?php echo strtolower($benef); ?>"
             data-piece="<?php echo strtolower($num_pce); ?>">
            
            <div class="card-header">
                <h5>
                    Pièce : <span class="fw-bold"><?php echo htmlspecialchars($num_pce); ?></span> |
                    Bénéficiaire :
                    <span class="text-primary"><?php echo $benef; ?></span>
                    | <span class="fw-bold"><?php echo $devise; ?></span>
                </h5>
                <span class="badge <?php echo $badgeClass; ?>">
                    <?php 
                        echo ($statut === 'rejete') ? 'Rejetée par le Recteur' : 
                            (($statut === 'en attente') ? 'En attente du Recteur' : 'Autorisé');
                    ?>
                </span>
            </div>

            <table class="table table-bordered mt-2">
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
                        <td><?php echo number_format($ligne['Montant'], 2).' '.$devise; ?></td>
                        <td><?php echo htmlspecialchars($ligne['Imputation']); ?></td>
                        <td><?php echo htmlspecialchars($ligne['Date_ajout']); ?></td>
                    </tr>
                    <?php $total += $ligne['Montant']; ?>
                    <?php endforeach; ?>
                    <tr class="table-secondary">
                        <td colspan="2" class="text-end"><strong>Total</strong></td>
                        <td><strong><?php echo number_format($total, 2).' '.$devise; ?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination-controls">
        <button id="prevBtn" class="btn btn-secondary btn-sm me-2">⬅ Précédent</button>
        <span id="pageInfo"></span>
        <button id="nextBtn" class="btn btn-secondary btn-sm ms-2">Suivant ➡</button>
    </div>
</div>

<script>
const cartes = Array.from(document.querySelectorAll('.carte-autorisation'));
const itemsPerPage = 8;
let currentPage = 1;

// Retourne uniquement les cartes visibles
function getCartesVisibles() {
    return cartes.filter(c => c.style.display !== 'none');
}

function afficherPage(page) {
    const cartesVisibles = getCartesVisibles();
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    cartes.forEach(c => c.style.display = 'none');
    cartesVisibles.forEach((carte, i) => {
        if (i >= start && i < end) carte.style.display = 'block';
    });

    const totalPages = Math.max(1, Math.ceil(cartesVisibles.length / itemsPerPage));
    document.getElementById('pageInfo').textContent = `Page ${page} / ${totalPages}`;
    document.getElementById('prevBtn').disabled = (page === 1);
    document.getElementById('nextBtn').disabled = (page >= totalPages);
}

// Pagination
document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; afficherPage(currentPage); }
});

document.getElementById('nextBtn').addEventListener('click', () => {
    const totalPages = Math.ceil(getCartesVisibles().length / itemsPerPage);
    if (currentPage < totalPages) { currentPage++; afficherPage(currentPage); }
});

afficherPage(currentPage);

// === Tri dynamique ===

// === Filtres combinés ===
function appliquerFiltres() {
    const filtreDevise = document.getElementById('filtreDevise').value;
    const filtreStatut = document.getElementById('filtreStatut').value;
    const recherche = document.getElementById('rechercheInput').value.trim().toLowerCase();

    cartes.forEach(carte => {
        const matchDevise = (filtreDevise === 'toutes' || carte.dataset.devise === filtreDevise);
        const matchStatut = (filtreStatut === 'tous' || carte.dataset.statut === filtreStatut);
        const matchRecherche = (
            carte.dataset.beneficiaire.includes(recherche) ||
            carte.dataset.piece.includes(recherche)
        );
        carte.style.display = (matchDevise && matchStatut && matchRecherche) ? '' : 'none';
    });

    currentPage = 1;
    afficherPage(currentPage);
}

document.getElementById('filtreDevise').addEventListener('change', appliquerFiltres);
document.getElementById('filtreStatut').addEventListener('change', appliquerFiltres);
document.getElementById('rechercheInput').addEventListener('input', appliquerFiltres);
</script>
