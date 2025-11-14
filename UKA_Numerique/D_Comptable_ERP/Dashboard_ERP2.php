<!-- Sale & Revenue Start -->
   <hr style="color:white;">
<h5 style="color:white;" >Rapports des dépenses</h5> 
<div class="container-fluid pt-4 px-4 " id="block_depenses2" style="display: none;">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Vue du budget par faculté</h6>
                                <a href="">Actualiser <i class="fa fa-bell bell"  >  </i></a>
                            </div>
                            <canvas id="worldwide-sales"></canvas>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Vue du Budget Annuel Universitaire </h6>
                                <a href="">Actualiser <i class="fa fa-bell bell">  </i></a>
                            </div>
                            <canvas id="salse-revenue"></canvas>
                        </div>
                    </div>
                </div>
            </div>


<div class="container-fluid pt-4 px-4" style="display: block;" id="graphique_1">
    <div class="row g-4 text-primary">
        <?php
        // Connexion déjà ouverte avec $con
        $anneeQuery = $con->query("SELECT MAX(idAnnee_Acad) AS last_year FROM annee_academique");
        $anneeRow = $anneeQuery->fetch(PDO::FETCH_ASSOC);
        $lastAnnee = $anneeRow['last_year'];
        $annee_debut = 2025; // à adapter dynamiquement si nécessaire

        // Requête pour récupérer les montants réalisés par rubrique
        $sql_realise = "SELECT 
                            ru.Libelle AS Libelle_rubrique,
                            ru.Id_rubrique,
                            ROUND(SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)), 2) AS Montant_rubrique_global,
                            ROUND(SUM(pf.Montant_paie), 2) AS Montant_total_paye
                        FROM 
                            repartition r
                        JOIN rubrique ru ON ru.Id_rubrique = r.Id_rubrique
                        JOIN promotion p ON p.Code_Promotion = r.Code_Promotion
                        JOIN passer_par pp ON pp.Code_Promotion = p.Code_Promotion AND pp.idAnnee_academique = r.idAnnee_Acad
                        JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
                        JOIN frais fr ON fr.idFrais = pf.idFrais 
                                    AND fr.Code_Promotion = pp.Code_Promotion 
                                    AND fr.idAnnee_Acad = pp.idAnnee_academique
                        JOIN (
                            SELECT 
                                Code_Promotion,
                                idAnnee_Acad,
                                SUM(Montant) AS TotalMontant
                            FROM 
                                repartition
                            WHERE 
                                idAnnee_Acad = $lastAnnee
                            GROUP BY 
                                Code_Promotion, idAnnee_Acad
                        ) AS repartition_total 
                            ON repartition_total.Code_Promotion = r.Code_Promotion 
                            AND repartition_total.idAnnee_Acad = r.idAnnee_Acad
                        WHERE 
                            r.idAnnee_Acad = $lastAnnee
                            AND pf.Motif_paie = 'Frais Académiques'
                        GROUP BY 
                            ru.Id_rubrique, ru.Libelle
                        ORDER BY 
                            ru.Libelle";
        $stmt_realise = $con->query($sql_realise);
        $donnees_realisation = $stmt_realise->fetchAll(PDO::FETCH_ASSOC);

        // Requête pour récupérer les montants prévus
        $sql_prevu = "SELECT 
                        rg.Id_rubrique,
                        SUM(rg.Montant) AS montant_prevu
                     FROM 
                        recette_generale rg
                     JOIN 
                        t_budget tb ON rg.Ref_budget = tb.Ref_budget
                     JOIN 
                        rubrique ru ON ru.Id_rubrique = rg.Id_rubrique
                     WHERE 
                        tb.Annee_debut = $annee_debut
                     GROUP BY 
                        rg.Id_rubrique";
        $stmt_prevu = $con->query($sql_prevu);
        $donnees_prevision = $stmt_prevu->fetchAll(PDO::FETCH_ASSOC);

        // Organisation des données des montants prévus par Id_rubrique
        $rubriques_prevues = [];
        foreach ($donnees_prevision as $prevue) {
            $rubriques_prevues[$prevue['Id_rubrique']] = $prevue['montant_prevu'];
        }

        // Fusion des données et affichage
        foreach ($donnees_realisation as $row) {
            $Id_rubrique = htmlspecialchars($row['Id_rubrique']);
            $libelle = htmlspecialchars($row['Libelle_rubrique']);
            $montant_realise = $row['Montant_rubrique_global'];
            $montant_paye = $row['Montant_total_paye'];

            // Récupérer le montant prévu s’il existe
            $montant_prevu = isset($rubriques_prevues[$Id_rubrique]) ? $rubriques_prevues[$Id_rubrique] : null;

            // Affichage
            echo '<div class="col-12">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-1">
                        <div class="w-100 me-3">
                            <p id="Rubrique" class="mb-1 fw-bold">'.$Id_rubrique .' &nbsp;&nbsp;&nbsp; '. $libelle . '</p>';

            if (!is_null($montant_prevu)) {
                $diff = round($montant_prevu - $montant_realise, 2);
                $pourc_prevu = ($montant_prevu > 0) ? round(($montant_realise / $montant_prevu) * 100, 2) : 0;
                $pourc_realise = ($montant_realise > 0) ? round(($montant_prevu / $montant_realise) * 100, 2) : 0;

                echo '<h6 id="Montant" class="mb-2">
                        Montant prévu : ' . number_format($montant_prevu, 2, ',', ' ') . ' $ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ; 
                        Montant Sorti : ' . number_format($montant_realise, 2, ',', ' ') . ' $ &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 
                        Reste : ' . number_format($diff, 2, ',', ' ') . ' $
                      </h6>
                      <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: ' . $pourc_prevu . '%;" 
                             aria-valuenow="' . $pourc_prevu . '" 
                             aria-valuemin="0" aria-valuemax="100">
                            ' . $pourc_prevu . '%
                        </div>
                      </div>';
            } else {
                echo '<h6 id="Montant" class="mb-2">
                        Montant prévu : <em>Non défini</em> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                        Montant Sorti : ' . number_format($montant_realise, 2, ',', ' ') . ' $ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                        Reste : <em>Non calculée</em>
                      </h6>
                      <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-secondary" role="progressbar" 
                             style="width: 0%;" 
                             aria-valuenow="0" 
                             aria-valuemin="0" aria-valuemax="100">
                            0%
                        </div>
                      </div>';
            }

            echo '      </div>
                    </div>
                </div>';
        }
        ?>
    </div>
</div>


<!-- Style personnalisé pour orange clair -->
<style>
.bg-orange-clair {
    background-color: #fcb04d !important;
}
</style>

<!-- JavaScript d'animation avec changement de couleur -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const progressBars = document.querySelectorAll(".progress-bar");

    progressBars.forEach(bar => {
        let max = parseFloat(bar.getAttribute("aria-valuenow"));
        let valeur = 0;
        let interval = setInterval(() => {
            if (valeur >= max) {
                clearInterval(interval);
                valeur = max;
            }

            bar.style.width = valeur + "%";
            bar.textContent = valeur + "%";

            // Supprimer les anciennes classes Bootstrap
            bar.classList.remove("bg-success", "bg-warning", "bg-orange-clair", "bg-danger");

            // Appliquer la couleur selon le pourcentage
            if (valeur < 50) {
                bar.classList.add("bg-danger");
            } else if (valeur < 70) {
                bar.classList.add("bg-orange-clair");
            } else if (valeur < 80) {
                bar.classList.add("bg-warning");
            } else {
                bar.classList.add("bg-success");
            }

            valeur += 1;
        }, 15); // Vitesse d'animation
    });
});
</script>


<div class="container-fluid pt-4 px-4" style="display: none;" id="graphique_2">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Graphique 1</h6>
                <canvas id="pie-chart"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Graphique 2</h6>
                <canvas id="doughnut-chart"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6" style="display: none;">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Single Line Chart</h6>
                <canvas id="line-chart"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6" style="display: none;">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Multiple Line Chart</h6>
                <canvas id="salse-revenue"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6" style="display: none;">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Single Bar Chart</h6>
                <canvas id="bar-chart"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4" style="display: none;">
                <h6 class="mb-4">Multiple Bar Chart</h6>
                <canvas id="worldwide-sales"></canvas>
            </div>
        </div>
                    
    </div>
</div>


