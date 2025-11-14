<?php
include("../../../Connexion_BDD/Connexion_1.php");
header("Content-Type: application/json");

try {
    // 🔹 1. Liste des agents selon année académique
    if (isset($_POST['idAnnee']) && !isset($_POST['matAgent'])) {
        $idAnnee = $_POST['idAnnee'];

        $req = "SELECT DISTINCT agent.Mat_agent, agent.Nom_agent, agent.Prenom 
                FROM agent
                INNER JOIN payer_frais ON agent.Mat_agent = payer_frais.Mat_agent
                INNER JOIN frais ON payer_frais.idFrais = frais.idFrais
                WHERE frais.idAnnee_Acad = ?";

        $stmt = $con->prepare($req);
        $stmt->execute([$idAnnee]);
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["agents" => $agents]);
        exit;
    }

    // 🔹 2. Détail des recettes perçues (groupées par motif et devise)
    if (isset($_POST['matAgent'], $_POST['datePaie'], $_POST['idLieu'])) {
        $matAgent = $_POST['matAgent'];
        $datePaie = $_POST['datePaie'];
        $idLieu = $_POST['idLieu'];

        $req = "SELECT 
                    payer_frais.Motif_paie AS motif,
                    COALESCE(SUM(CASE WHEN payer_frais.Fc IS NULL THEN payer_frais.Montant_paie ELSE 0 END), 0) AS montant_usd,
                    COALESCE(SUM(payer_frais.Fc), 0) AS montant_cdf
                FROM payer_frais
                INNER JOIN frais ON frais.idFrais = payer_frais.idFrais
                WHERE payer_frais.Mat_agent = ?
                  AND payer_frais.idLieu_paiement = ?
                  AND DATE(payer_frais.Date_paie) = ?
                GROUP BY payer_frais.Motif_paie";

        $stmt = $con->prepare($req);
        $stmt->execute([$matAgent, $idLieu, $datePaie]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $operations = [];
        $total_usd = 0;
        $total_cdf = 0;

        foreach ($rows as $row) {
            if ($row['montant_usd'] > 0) {
                $operations[] = [
                    "motif" => $row['motif'],
                    "montant" => $row['montant_usd'],
                    "devise" => "USD"
                ];
                $total_usd += $row['montant_usd'];
            }
            if ($row['montant_cdf'] > 0) {
                $operations[] = [
                    "motif" => $row['motif'],
                    "montant" => $row['montant_cdf'],
                    "devise" => "CDF"
                ];
                $total_cdf += $row['montant_cdf'];
            }
        }

        // Renvoie une structure JSON même si aucune donnée n'est trouvée
        echo json_encode([
            "operations" => $operations,
            "total_usd" => $total_usd,
            "total_cdf" => $total_cdf
        ]);
        exit;
    }

    echo json_encode(["error" => "Paramètres insuffisants."]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur BDD : " . $e->getMessage()]);
}
