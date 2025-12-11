<?php
include("../../../Connexion_BDD/Connexion_1.php");

// Même code pour récupérer $valides et $rejetes
$query = $con->query("SELECT * FROM autorisation_depense ORDER BY Num_pce desc");
$autorisation_data = $query->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($autorisation_data as $ligne) {
    $grouped[$ligne['Num_pce']][] = $ligne;
}

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

// Générer le HTML pour le slider validé
ob_start();
foreach($valides as $num_pce => $lignes) {
    ?>
    <div class="card mb-4 shadow p-3 slider-item">
        <div class="card-header bg-success text-white">
            Numéro de pièce : <?php echo $num_pce; ?> | Bénéficiaire : <?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?>
        </div>
        <table class="table table-bordered table-striped mt-2">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Motif</th><th>Montant</th><th>Imputation</th><th>Date ajout</th>
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
            </tbody>
        </table>
    </div>
    <?php
}
$htmlValide = ob_get_clean();

// Même chose pour rejeté
ob_start();
foreach($rejetes as $num_pce => $lignes) {
    ?>
    <div class="card mb-4 shadow p-3 slider-item">
        <div class="card-header bg-danger text-white">
            Numéro de pièce : <?php echo $num_pce; ?> | Bénéficiaire : <?php echo htmlspecialchars($lignes[0]['Beneficiaire']); ?>
        </div>
        <table class="table table-bordered table-striped mt-2">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Motif</th><th>Montant</th><th>Imputation</th><th>Date ajout</th>
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
            </tbody>
        </table>
    </div>
    <?php
}
$htmlRejete = ob_get_clean();

// Retourner JSON
echo json_encode([
    'valide' => $htmlValide,
    'rejete' => $htmlRejete
]);
