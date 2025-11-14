<?php
include("../Connexion_BDD/Connexion_1.php");

// Récupérer toutes les pièces avec leurs lignes
$query = $con->query("SELECT * FROM autorisation_depense ORDER BY Num_pce, id");
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

$agent = $_SESSION['MatriculeAgent'];
$fonctionUser = $_SESSION['Categorie'];

// === Nouvelle logique d’affichage ===
$filtered = [];

foreach ($grouped as $num_pce => $lignes) {
    $etat = $status_by_piece[$num_pce] ?? null;

    if ($fonctionUser === 'Administrateur de Budget') {
        // → Voir uniquement les pièces sans Niveau_2 (non encore autorisées)
        if (!$etat || $etat['Niveau_2'] === '' || is_null($etat['Niveau_2'])) {
            $filtered[$num_pce] = $lignes;
        }
    } elseif ($fonctionUser === 'Recteur') {
        // → Voir uniquement les pièces déjà autorisées au Niveau_2 mais pas encore au Niveau_1
        if ($etat && strtolower($etat['Niveau_2']) === 'autoriser' && ($etat['Niveau_1'] === '' || is_null($etat['Niveau_1']))) {
            $filtered[$num_pce] = $lignes;
        }
    } else {
        // → Autres rôles : voir tout (si besoin)
        $filtered[$num_pce] = $lignes;
    }
}

$grouped = $filtered;
$pieces = array_keys($grouped);
?>



<style>
.card-header { display: flex; justify-content: space-between; align-items: center; }
.select-action { width: 150px; margin-right: 5px; }
.slider-buttons { margin: 15px 0; }
</style>
<?php

$agent = $_SESSION['MatriculeAgent'];
$fonctionUser = $_SESSION['Categorie'];
?>
<script>
const agentCourant = "<?php echo addslashes($agent); ?>";
</script>
<body>
<div class="container">


    <div class="d-flex align-items-center justify-content-between mb-4">
   
            <a href="Page_Principale_Finance.php?page=autorisation" class="btn btn-info mt-1">← Retour</a>

        <!-- Titre centré -->
        <h2 class="text-primary text-center flex-grow-1 m-0">
          Liste des dépenses qui attendent l'autorisation
        </h2>

        <!-- Espace vide pour équilibrer le flex -->
        <div style="width: 100px; color:red;">
            <a href="Page_Principale_Finance.php?page=Suivi_autorisation" class="btn btn-info mt-1">Continuer</a>

        </div>
    </div>
    <div id="sliderContainer">
        <?php foreach($pieces as $index => $num_pce): 
            $lignes = $grouped[$num_pce];
            $autorisation_status = $status_by_piece[$num_pce] ?? null;
            $devise = strpos(strtolower($num_pce), 'cdf') !== false ? 'CDF' : 'USD';
        ?>
        <div class="card mb-4 shadow p-3 slider-item" style="display: <?php echo $index === count($pieces)-1 ? 'block' : 'none'; ?>;">
                <div class="card-header">
                    <h5>
                        Numéro de pièce : <?php echo ($num_pce); ?>  
                        | Bénéficiaire : <span class="text-primary">
                            <?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?>
                        </span>
                    </h5>
                    <div class="d-flex">
                        <!-- Niveau 2 -->
                        <select id="niveau2_<?php echo $num_pce; ?>" class="form-select form-select-sm select-action"
                            <?php 
                                echo (($autorisation_status && $autorisation_status['Niveau_2'] != '') || $fonctionUser != 'Administrateur de Budget') ? 'disabled' : ''; 
                            ?>>
                            <option value="autoriser">Autoriser</option>
                            <option value="rejeter">Annuler</option>
                        </select>
                        <button class="btn btn-primary btn-sm me-2" 
                            onclick="autoriser('<?php echo $num_pce; ?>', 2)"
                            <?php echo ($fonctionUser != 'Administrateur de Budget') ? 'hidden' : ''; ?>>
                            Valider l'action
                        </button>

                        <!-- Niveau 1 -->
                        <select id="niveau1_<?php echo $num_pce; ?>" class="form-select form-select-sm select-action"
                            <?php 
                                echo (!$autorisation_status || $autorisation_status['Niveau_2'] == '' || $autorisation_status['Niveau_1'] != '' || $fonctionUser != 'Recteur') ? 'hidden' : ''; 
                            ?>>
                            <option value="">-- Niveau 1 --</option>
                            <option value="autoriser">Autoriser</option>
                            <option value="rejeter">Rejeter</option>
                        </select>
                        <button class="btn btn-success btn-sm" 
                            onclick="autoriser('<?php echo $num_pce; ?>', 1)"
                            <?php echo ($fonctionUser != 'Recteur') ? 'hidden' : ''; ?>>
                            Valider le choix
                        </button>
                    </div>
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
let currentIndex = <?php echo count($pieces)-1; ?>;
const items = document.querySelectorAll('.slider-item');

function showSlide(index) {
    items.forEach((item, i) => item.style.display = (i === index) ? 'block' : 'none');
}

function precedent() {
    if (currentIndex > 0) {
        currentIndex--;
        showSlide(currentIndex);
    }
}

function suivant() {
    if (currentIndex < items.length - 1) {
        currentIndex++;
        showSlide(currentIndex);
    }
}

function autoriser(num_pce, niveau) {
    let action;
    if (niveau === 2) {
        action = document.getElementById('niveau2_' + num_pce).value;
    } else {
        action = document.getElementById('niveau1_' + num_pce).value;
    }

    if (!action) {
        alert("Veuillez choisir une action avant de valider !");
        return;
    }

    fetch("D_Finance/API/Autoriser_Niveaux.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            Num_pce: String(num_pce).trim(),
            niveau: niveau,
            action: action,
            agent: agentCourant   // <-- On envoie l'agent courant
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("✅ Action appliquée : " + action + " pour Niveau " + niveau);
            location.reload();
        } else {
            alert("❌ Erreur : " + data.error);
        }
    })
    .catch(err => console.error("Erreur AJAX :", err));
}

</script>
