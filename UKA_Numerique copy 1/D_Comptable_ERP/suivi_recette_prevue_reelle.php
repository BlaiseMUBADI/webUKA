<select id="annee_academique_graphique" class="form-select">
    <?php
    $annees = $con->query("SELECT DISTINCT idAnnee_Acad FROM annee_academique ORDER BY idAnnee_Acad DESC");
    while ($annee = $annees->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="'.$annee['idAnnee_Acad'].'">'.$annee['idAnnee_Acad'].'</option>';
    }
    ?>
</select>

<div class="container-fluid pt-4 px-4" id="graphique_1">
    <div class="row g-4"></div>
</div>
