<?php
// Récupérer toutes les pièces avec leurs lignes
echo "<!-- Page Autorisations_Validees chargée -->";

$query = $con->query("SELECT * FROM autorisation_depense ORDER BY Num_pce desc");
$autorisation_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Grouper par Num_pce
$grouped = [];
foreach ($autorisation_data as $ligne) {
    $grouped[$ligne['Num_pce']][] = $ligne;
}

// Récupérer l'état des autorisations par pièce
$status_query = $con->query("SELECT * FROM autoriser_depense");
$status_data = $status_query->fetchAll(PDO::FETCH_ASSOC);

$status_by_piece = []; 
foreach ($status_data as $s) {
    $status_by_piece[$s['Num_pce']] = $s;
}

$valides = [];
$rejetes = [];

foreach ($grouped as $num_pce => $lignes) {
    if (isset($status_by_piece[$num_pce])) {
        $niveau1 = strtolower($status_by_piece[$num_pce]['Niveau_1'] ?? '');
        $niveau2 = strtolower($status_by_piece[$num_pce]['Niveau_2'] ?? '');
        
        if ($niveau1 === 'autoriser' && $niveau2 === 'autoriser') {
            $valides[$num_pce] = $lignes;
        } elseif ($niveau1 === 'rejeter' || $niveau2 === 'rejeter') {
            $rejetes[$num_pce] = $lignes;
        }
    }
}
?>

<style>
.card-header { display: flex; justify-content: space-between; align-items: center; }
.slider-buttons { margin: 15px 0; }
.btn-tab { margin-right: 10px; }


#solde_CDF, #solde_USD {
    font-size: 28px;  /* Taille du texte */
    font-weight: bold; /* Gras */
    color: green;      /* Couleur */
}
#numeroPieceCDF, #numeroPieceUSD {
    font-size: 28px;  /* Taille du texte */
    font-weight: bold; /* Gras */
    color: red;      /* Couleur */
}

</style>

<body>
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-2 p-2 gap-3 bg-dark text-white rounded">
        <button class="btn btn-success" onclick="showTab('valide')">✅ Validées</button>
        <button class="btn btn-danger" onclick="showTab('dejaServie')">🚫 Déjà servie</button>
        <h2 class="m-0 text-center flex-grow-1">Autorisations de dépenses</h2>
        
        <button class="btn btn-danger" onclick="showTab('rejete')">Rejetées</button>
    </div>

    <!-- Barre de recherche + numéro pièce -->
        <div class="mb-2 d-flex align-items-center gap-2">
            <input type="text" 
                id="searchInput" 
                class="form-control" 
                placeholder="Rechercher par numéro de pièce ou bénéficiaire..." 
                onkeyup="filterAutorisation()">

            <select id="filtreDevise" class="form-select" style="max-width: 180px;" disabled>
                <option value="">-- Toutes les devises --</option>
                <option value="CDF">CDF</option>
                <option value="USD">USD</option>
            </select>
        </div>

    <!-- ================= SOLDE ================= -->
    <div class="mb-2 p-1 bg-light rounded d-flex gap-3 flex-wrap justify-content-center">
        <div class="border p-1 rounded">
            <strong>Solde CDF :</strong> <span id="solde_CDF">0</span>
        </div>
        <div class="border p-1 rounded">
            <strong>N° Pce:</strong> <span id="numeroPieceCDF">0</span>
        </div>
        <div class="border p-1 rounded">
            <strong>Solde USD :</strong> <span id="solde_USD">0</span>
        </div>
        <div class="border p-1 rounded">
            <strong>N° Pce:</strong> <span id="numeroPieceUSD">0</span>
        </div>
        <div class="border p-1 rounded">
            <strong>Année:</strong> 
            <select id="annee">
                                <?php 
                                $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc limit 2' );
                                    while ($ligne = $reponse->fetch()) {?>

                                <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                            </select>
        </div>

    
    </div>


    <!-- ================= AUTORISATIONS VALIDÉES ================= -->
    <div id="sliderValide" class=" text-primary">
        <?php 
        echo"nous sommes ici";

        $piecesValides = array_keys($valides);
        if (empty($piecesValides)) {
            echo '<p class="alert alert-warning">Aucune autorisation validée pour le moment.</p>';
        }
        foreach($piecesValides as $index => $num_pce): 
            $lignes = $valides[$num_pce];
            $devise = strpos(strtolower($num_pce), 'cdf') !== false ? 'CDF' : 'USD';
            $beneficiaire = htmlspecialchars($lignes[0]['Beneficiaire']);
            $total = array_sum(array_column($lignes,'Montant'));
        ?>
        <div class="card mb-4 shadow p-3 slider-item" data-devise="<?php echo $devise; ?>" 
                style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                
                <?php
                    // 🔹 Vérifier si cette autorisation a déjà été servie
                    $verif = $con->prepare("SELECT COUNT(*) FROM decaissement_caisse WHERE Num_Autoriz = ?");
                    $verif->execute([$num_pce]);
                    $deja_servie = $verif->fetchColumn() > 0;

                    // 🔹 Déterminer la couleur du header
                    $classHeader = $deja_servie ? "bg-danger text-white" : "bg-success text-white";
                ?>

                <div class="card-header <?php echo $classHeader; ?>">
                    <h5>
                        Numéro de pièce : <?php echo htmlspecialchars($num_pce); ?> |
                        Bénéficiaire : <span class="text-warning"><?php echo htmlspecialchars($beneficiaire); ?></span>
                        <?php if ($deja_servie): ?>
                            <span class="badge bg-light text-danger ms-3">Déjà servie</span>
                        <?php endif; ?>
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
                        <?php $id=0; foreach($lignes as $ligne): $id++; ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo htmlspecialchars($ligne['Motif']); ?></td>
                            <td><?php echo number_format($ligne['Montant']); ?></td>
                            <td><?php echo htmlspecialchars($ligne['Imputation']); ?></td>
                            <td><?php echo htmlspecialchars($ligne['Date_ajout']); ?></td>
                        </tr>
                        <?php endforeach; ?>

                        <tr class="table-secondary">
                            <td colspan="2" class="text-end"><strong>Total</strong></td>
                            <td><strong><?php echo number_format($total,2).' '.$devise; ?></strong></td>
                            <td colspan="2">
                                <button id="btnDecaisser" class="btn btn-success"
                                    <?php echo $deja_servie ? 'disabled' : ''; ?>>
                                    <?php echo $deja_servie ? '🚫 Déjà servie' : '💸 Décaisser'; ?>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <?php endforeach; ?>

        <!-- Boutons Slider Validées -->
        <div class="slider-buttons text-center">
            <button class="btn btn-secondary" onclick="precedent('valide')">Précédent</button>
            <button class="btn btn-secondary" onclick="suivant('valide')">Suivant</button>
        </div>
    </div>

    
    <!-- ================= AUTORISATIONS REJETÉES (INCHANGÉES) ================= -->
    <div id="sliderRejete" style="display:none">
        <?php 
        $piecesRejetees = array_keys($rejetes);
        if (empty($piecesRejetees)) {
            echo '<p class="alert alert-warning">Aucune autorisation rejetée pour le moment.</p>';
        }
        foreach($piecesRejetees as $index => $num_pce): 
            $lignes = $rejetes[$num_pce];
            $devise = strpos(strtolower($num_pce), 'cdf') !== false ? 'CDF' : 'USD';
        ?>
        <div class="card mb-4 shadow p-3 slider-item"data-devise="<?php echo $devise; ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
            <div class="card-header bg-danger text-white">
                <h5>Numéro de pièce : <?php echo $num_pce; ?> | Bénéficiaire : <span class="text-warning"><?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?></span></h5>
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
                    <?php $total=0; $id=0; foreach($lignes as $ligne): $id++; ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo htmlspecialchars($ligne['Motif']); ?></td>
                        <td><?php echo number_format($ligne['Montant'],2).' '.$devise; ?></td>
                        <td><?php echo $ligne['Imputation']; ?></td>
                        <td><?php echo $ligne['Date_ajout']; ?></td>
                    </tr>
                    <?php $total += $ligne['Montant']; endforeach; ?>
                    <tr class="table-secondary">
                        <td colspan="2" class="text-end"><strong>Total</strong></td>
                        <td><strong><?php echo number_format($total,2).' '.$devise; ?></strong></td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endforeach; ?>

        <!-- Boutons Slider Rejetées -->
        <div class="slider-buttons text-center">
            <button class="btn btn-secondary" onclick="precedent('rejete')">Précédent</button>
            <button class="btn btn-secondary" onclick="suivant('rejete')">Suivant</button>
        </div>
    </div>
</div>



<script>
let currentIndexValide = 0;
let currentIndexRejete = 0;

const itemsValide = document.querySelectorAll('#sliderValide .slider-item');
const itemsRejete = document.querySelectorAll('#sliderRejete .slider-item');

function showTab(tab) {
    document.getElementById('sliderValide').style.display = (tab==='valide') ? 'block' : 'none';
    document.getElementById('sliderRejete').style.display = (tab==='rejete') ? 'block' : 'none';
}

function showSlide(index, tab) {
    const items = (tab==='valide') ? itemsValide : itemsRejete;
    items.forEach((item, i) => item.style.display = (i===index) ? 'block' : 'none');
}

function precedent(tab){
    if(tab==='valide' && currentIndexValide>0) { 
        currentIndexValide--; 
        showSlide(currentIndexValide,'valide'); 
    }
    if(tab==='rejete' && currentIndexRejete>0) { 
        currentIndexRejete--; 
        showSlide(currentIndexRejete,'rejete'); 
    }
}

function suivant(tab){
    if(tab==='valide' && currentIndexValide<itemsValide.length-1) { 
        currentIndexValide++; 
        showSlide(currentIndexValide,'valide'); 
    }
    if(tab==='rejete' && currentIndexRejete<itemsRejete.length-1) { 
        currentIndexRejete++; 
        showSlide(currentIndexRejete,'rejete'); 
    }
}

// Initialisation
showTab('valide');
showSlide(currentIndexValide,'valide');
showSlide(currentIndexRejete,'rejete');

function filterAutorisation() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const items = document.querySelectorAll('.slider-item');

    items.forEach(item => {
        const numPce = item.querySelector('.card-header h5').textContent.toLowerCase();
        if (numPce.includes(input)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// JS pour gérer le clic sur Servir (par ligne)

</script>







// Rafraîchir automatiquement toutes les 30s (optionnel)





<script src="D_Finance/js/Cloture_Caisse.js"></script>
<script src="D_Finance/js/Servir_Autorisation.js"></script>
