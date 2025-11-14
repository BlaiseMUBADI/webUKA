<?php
include("../Connexion_BDD/Connexion_1.php");

// Récupération des données
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

// Tri initial par devise
usort($filtered, function($a, $b) {
    return strcmp($a['devise'], $b['devise']);
});
?>

<style>
.card-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
.badge { font-size: 0.9em; }
.tri-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
.tri-container select { width: 200px; }
.pagination-controls { text-align: center; margin-top: 20px; }
</style>

<div class="container">
   <div class="d-flex align-items-center justify-content-between mb-4">
   
            <a href="Page_Principale_Finance.php?page=Autoriser" class="btn btn-info mt-1">← Retour</a>

        <!-- Titre centré -->
        <h2 class="text-primary text-center flex-grow-1 m-0">
            Suivi Autorisations
        </h2>

        <!-- Espace vide pour équilibrer le flex -->
        <div style="width: 100px; color:red;"></div>
    </div>

    <!-- Filtres -->
    <div class="tri-container">
        <div>
            <label class="me-2 fw-bold">Trier par :</label>
            <select id="triSelect" class="form-select form-select-sm d-inline-block" style="width:180px;">
                <option value="devise">Devise (CDF → USD)</option>
                <option value="statut">Statut</option>
            </select>
        </div>
        <div>
            <label class="me-2 fw-bold">Filtrer par devise :</label>
            <select id="filtreDevise" class="form-select form-select-sm d-inline-block" style="width:150px;">
                <option value="toutes">Toutes</option>
                <option value="CDF">CDF</option>
                <option value="USD">USD</option>
            </select>
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

            $badgeClass = ($statut === 'en attente') ? 'bg-warning text-dark' :
                        (($statut === 'rejete') ? 'bg-danger' : 'bg-success');
        ?>
        <div class="carte-autorisation card mb-4 shadow p-3" 
             data-devise="<?php echo $devise; ?>" 
             data-statut="<?php echo $statut; ?>"
             data-index="<?php echo $index; ?>">
            
            <div class="card-header">
                <h5>
                    Pièce : <?php echo htmlspecialchars($num_pce); ?> |
                    Bénéficiaire :
                    <span class="text-primary"><?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?></span>
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

function afficherPage(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    cartes.forEach((carte, i) => {
        carte.style.display = (i >= start && i < end) ? 'block' : 'none';
    });
    document.getElementById('pageInfo').textContent = `Page ${page} / ${Math.ceil(cartes.length / itemsPerPage)}`;
    document.getElementById('prevBtn').disabled = (page === 1);
    document.getElementById('nextBtn').disabled = (end >= cartes.length);
}

document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; afficherPage(currentPage); }
});
document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentPage < Math.ceil(cartes.length / itemsPerPage)) { currentPage++; afficherPage(currentPage); }
});

afficherPage(currentPage);

// === Tri dynamique ===
document.getElementById('triSelect').addEventListener('change', function() {
    const value = this.value;
    const container = document.getElementById('listeAutorisations');
    const cartesSorted = [...cartes].sort((a, b) => {
        if (value === 'devise') return a.dataset.devise.localeCompare(b.dataset.devise);
        if (value === 'statut') {
            const ordre = { 'en attente': 1, 'autorise': 2, 'rejete': 3 };
            return (ordre[a.dataset.statut] || 99) - (ordre[b.dataset.statut] || 99);
        }
    });
    cartesSorted.forEach(c => container.appendChild(c));
    afficherPage(1);
});

// === Filtre devise ===
document.getElementById('filtreDevise').addEventListener('change', function() {
    const value = this.value;
    cartes.forEach(carte => {
        if (value === 'toutes' || carte.dataset.devise === value) {
            carte.style.display = '';
        } else {
            carte.style.display = 'none';
        }
    });
    currentPage = 1;
    afficherPage(currentPage);
});
</script>
