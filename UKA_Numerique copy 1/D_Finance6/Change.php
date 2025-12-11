

<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-synthese {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 15px 20px;
    margin-bottom: 20px;
}

.container-synthese .info-box {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 10px 15px;
    background: #fdfdfd;
    flex: 1;
    text-align: center;
}

.container-synthese strong {
    color: #343a40;
}

#solde_CDF, #solde_USD {
    color: green;
    font-weight: bold;
    font-size: 20px;
}

#numeroPieceCDF, #numeroPieceUSD {
    color: red;
    font-weight: bold;
}

h2.section-title {
    font-size: 22px;
    font-weight: 600;
    color: #212529;
    margin-bottom: 15px;
}

.accordion-button {
    background-color: #ffffff;
    color: #212529;
    font-weight: 600;
}

.accordion-button:not(.collapsed) {
    color: #dc3545;
    background-color: #f8f9fa;
}

.accordion-body {
    background-color: #ffffff;
    border-top: 1px solid #dee2e6;
}

.form_decaissement {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.btn-danger {
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 6px;
}

small {
    font-size: 13px;
}
</style>

<div class="container mt-1">

    <!-- 🔹 Titre principal -->
    <div class="text-center mb-2">
        <h2 class="fw-bold text-danger">Opérations de Change</h2>
        <hr class="w-50 mx-auto">
    </div>

    <!-- 🔹 Bloc de synthèse -->
    <div class="container-synthese d-flex flex-wrap gap-3 justify-content-around">
        <div class="info-box">
            <strong>Solde CDF </strong><br>
            <span id="solde_CDF">0</span>
        </div>
        <!--div class="info-box" Hidden>
            <strong>N° Pièce CDF </strong><br>
            <span id="numeroPieceCDF">0</span>
        </div -->
        <div class="info-box">
            <strong>Solde USD </strong><br>
            <span id="solde_USD">0</span>
        </div>
        <!--div class="info-box" Hidden>
            <strong>N° Pièce USD </strong><br>
            <span id="numeroPieceUSD">0</span>
        </div -->
        <div class="info-box">
            <strong>Année Académique </strong><br>
            <select id="annee" class="form-select form-select-sm w-auto mx-auto">
                <?php 
                $reponse = $con->query("SELECT * FROM annee_academique ORDER BY Annee_debut DESC LIMIT 2");
                while ($ligne = $reponse->fetch()) {
                    echo '<option value="'.$ligne['idAnnee_Acad'].'">'.$ligne['Annee_debut'].' - '.$ligne['Annee_fin'].'</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <!-- 🔹 Accordéon principal -->
    <div class="accordion" id="accordionExample">

        <!-- 💲 Décaissement USD -->
        <div class="accordion-item mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUSD" aria-expanded="false">
                    Décaissement/Opérations de change en USD
                </button>
            </h2>
            <div id="collapseUSD" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <div class="form_decaissement">
                        <h2 class="section-title text-danger">Change USD ($) en CDF</h2>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="numeroPieceDecUSD" class="form-label">N° Pièce</label>
                                    <?php
                                        $prefix = 'Dec_usd_';
                                        $reponse = $con->query("
                                            SELECT numero_pce 
                                            FROM numero_piece 
                                            WHERE numero_pce LIKE '$prefix%' 
                                            ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                            LIMIT 1
                                        ");
                                        if ($ligne = $reponse->fetch()) {
                                            $lastNumero = $ligne['numero_pce'];
                                            $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
                                        } else {
                                            $numericPart = 1;
                                        }
                                    ?>
                                    <input type="text" class="form-control" id="numeroPieceDecUSD" value="<?php echo $numericPart; ?>" disabled>
                                </div>

                                <div class="col-md-3">
                                    <label for="tauxUSD" class="form-label">Taux de change</label>
                                    <input type="text" class="form-control" id="tauxUSD" placeholder="Saisir le taux de change">
                                </div>
                                <div class="col-md-3">
                                    <label for="MontantUSD" class="form-label">Montant</label>
                                    <input type="text" class="form-control" id="MontantUSD" placeholder="Montant en $">
                                </div>

                                <div class="col-md-3">
                                    <label for="montantDecUSD" class="form-label">Montant Net en CDF</label>
                                    <input type="text" class="form-control" id="montantDecUSD" disabled>
                                    <small id="erreurUSD" class="text-danger d-none">Saisir uniquement des chiffres et un point (.)</small>
                                    <span id="lettresDecUSD"></span>
                                </div>

                                <div class="col-md-3">
                                    <label for="dateDecaissementUSD" class="form-label">Date</label>
                                    <input type="date" class="form-control date-decaissement" data-type="USD" id="dateDecaissementUSD">
                                </div>
                                <div class="col-md-3">
                                    <label for="ImputationUSD" class="form-label">Imputation</label>
                                    <select class="form-select" id="ImputationUSD">
                                        <?php 
                                            $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                            $data1 = $con->query($req1);
                                            while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="'.$ligne1['Num_imputation'].'">'.$ligne1['Num_imputation'].' - '.$ligne1['Lib'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="motifDecaissementUSD" class="form-label">Motif du décaissement</label>
                                    <input type="text" class="form-control" id="motifDecaissementUSD" rows="3" disabled value="Opération de change">
                                </div>

                                <div class="col-12 text-center">
                                    <button type="button" id="BtnDecaisserUSD" class="btn btn-danger mt-3">Valider change USD vers CDF</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- 💰 Décaissement CDF -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCDF" aria-expanded="false">
                   Décaissement/Opérations de change en CDF
                </button>
            </h2>
            <div id="collapseCDF" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <div class="form_decaissement">
                        <h2 class="section-title text-danger">Change CDF en USD</h2>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="numeroPieceDecCDF" class="form-label">N° Pièce</label>
                                    <?php
                                        $prefix = 'Dec_cdf_';
                                        $reponse = $con->query("
                                            SELECT numero_pce 
                                            FROM numero_piece 
                                            WHERE numero_pce LIKE '$prefix%' 
                                            ORDER BY CAST(SUBSTRING(numero_pce, LENGTH('$prefix') + 1) AS UNSIGNED) DESC 
                                            LIMIT 1
                                        ");
                                        if ($ligne = $reponse->fetch()) {
                                            $lastNumero = $ligne['numero_pce'];
                                            $numericPart = intval(substr($lastNumero, strlen($prefix))) + 1;
                                        } else {
                                            $numericPart = 1;
                                        }
                                    ?>
                                    <input type="text" class="form-control" id="numeroPieceDecCDF" value="<?php echo $numericPart; ?>" disabled>
                                </div>

                                <div class="col-md-3">
                                    <label for="tauxCDF" class="form-label">Taux de change</label>
                                    <input type="text" class="form-control" id="tauxCDF" placeholder="Saisir le taux de change">
                                </div>
                                <div class="col-md-3">
                                    <label for="MontantCDF" class="form-label">Montant en CDF</label>
                                    <input type="text" class="form-control" id="MontantCDF" placeholder="">
                                    <small id="erreurCDF" class="text-danger d-none">Saisir uniquement des chiffres et un point (.)</small>
                                    <span id="lettresDecCDF"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="montantDecCDF" class="form-label">Montant Net en USD</label>
                                    <input type="text" class="form-control" id="montantDecCDF" placeholder="" disabled>
                                    <small id="erreurCDF" class="text-danger d-none">Saisir uniquement des chiffres et un point (.)</small>
                                    <span id="lettresDecCDF"></span>
                                </div>
                                    

                                <div class="col-md-3">
                                    <label for="ImputationCDF" class="form-label">Imputation</label>
                                    <select class="form-select" id="ImputationCDF">
                                        <?php 
                                            $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                            $data1 = $con->query($req1);
                                            while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="'.$ligne1['Num_imputation'].'">'.$ligne1['Num_imputation'].' - '.$ligne1['Lib'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>

                                

                                <div class="col-md-3">
                                    <label for="dateDecaissementCDF" class="form-label">Date</label>
                                    <input type="date" class="form-control date-decaissement" data-type="CDF" id="dateDecaissementCDF">
                                </div>

                                <div class="col-3">
                                    <label for="motifDecaissementCDF" class="form-label">Motif du décaissement</label>
                                    <input type="text" class="form-control" id="motifDecaissementCDF" rows="3" value="Opération de change"disabled>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="button" id="BtnDecaisserCDF" class="btn btn-danger mt-3">Valider change CDF vers USD</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll(".date-decaissement").forEach(input => input.value = today);

const tauxInput = document.getElementById("tauxCDF");
    const montantInput = document.getElementById("MontantCDF");
    const montantNetInput = document.getElementById("montantDecCDF");

    function calculMontantNet() {
        const taux = parseFloat(tauxInput.value.replace(',', '.')) || 0;
        const montant = parseFloat(montantInput.value.replace(',', '.')) || 0;
        const resultat = montant / taux;
        montantNetInput.value = resultat ? resultat.toFixed(2) : '';

    }

    // Calcul à chaque modification
    tauxInput.addEventListener("input", calculMontantNet);
    montantInput.addEventListener("input", calculMontantNet);
     // 💲 Calcul Montant Net USD
    const tauxUSD = document.getElementById("tauxUSD");
    const montantUSD = document.getElementById("MontantUSD");
    const netUSD = document.getElementById("montantDecUSD");

    function calcUSD() {
        const t = parseFloat(tauxUSD.value.replace(',', '.')) || 0;
        const m = parseFloat(montantUSD.value.replace(',', '.')) || 0;
        netUSD.value = (t*m).toFixed(2);
    }
    tauxUSD.addEventListener("input", calcUSD);
    montantUSD.addEventListener("input", calcUSD);

});

// Vérifie la clôture (code existant conservé)

</script>

<!--script src="D_Finance/js/Servir_Autorisation.js"></script-->

<script>


 async function chargerSoldes() {
    try {
        const res = await fetch('D_Finance/API/API_Select_Solde.php');
        const data = await res.json();
        const soldeCDF = parseFloat(data.Solde_cdf.replace(/,/g, '')) - parseFloat(data.Solde__dec_cdf.replace(/,/g, ''));
        const soldeUSD = parseFloat(data.Solde_usd.replace(/,/g, '')) - parseFloat(data.Solde__dec_usd.replace(/,/g, ''));

        document.getElementById('solde_CDF').innerText = soldeCDF.toLocaleString() + " CDF";
        document.getElementById('solde_USD').innerText = soldeUSD.toLocaleString() + " USD";
    } catch (err) {
        console.error("Erreur lors du chargement des soldes :", err);
    }

}
function rafraichirNumeros() {
    fetch("D_Finance/API/Actualiser_Num_pieces.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("numeroPieceCDF").innerText = data.numDecCDF || '-';
                document.getElementById("numeroPieceUSD").innerText = data.numDecUSD || '-';
            }
        })
        .catch(err => console.error("Erreur lors du rafraîchissement des numéros :", err));
}

   document.addEventListener("DOMContentLoaded", () => {
    chargerSoldes();
    rafraichirNumeros();
   });
document.addEventListener("DOMContentLoaded", function () {


    










    function nettoyerSolde(valeur) {
    if (!valeur) return 0;
    // Supprime tout sauf chiffres, points et virgules
    let propre = valeur.replace(/[^\d.,-]/g, '');
    // Remplace les virgules par des points
    propre = propre.replace(',', '.');
    // Convertit en nombre flottant
    return parseFloat(propre) || 0;
}
    // ======== FONCTION GÉNÉRALE POUR ENVOYER LES DONNÉES ==========
    async function envoyerDecaissement(data) {
        const params = new URLSearchParams(data);
        try {
            const response = await fetch(`D_Finance/API/decaissement_change.php?${params.toString()}`);
            const result = await response.json();

            if (result.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: result.message,
                    confirmButtonColor: '#dc3545'
                });
            } else if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: result.message,
                    confirmButtonColor: '#198754',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Remettre le formulaire à zéro
                if (data.operation === "Dec_USD") {
                    document.getElementById("MontantUSD").value = "";
                    document.getElementById("montantDecUSD").value = "";
                    document.getElementById("tauxUSD").value = "";
                    document.getElementById("numeroPieceDecUSD").value = result.num_suivant;
                } else if (data.operation === "Dec_CDF") {
                    document.getElementById("MontantCDF").value = "";
                    document.getElementById("montantDecCDF").value = "";
                    document.getElementById("tauxCDF").value = "";
                    document.getElementById("numeroPieceDecCDF").value = result.num_suivant;
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: "Erreur inconnue lors de l'enregistrement.",
                    confirmButtonColor: '#ffc107'
                });
            }
        } catch (error) {
            console.error("Erreur API:", error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur Serveur',
                text: '⚠️ Une erreur est survenue lors de la communication avec le serveur.',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    // ===============================================================
    //  💵 BOUTON DÉCAISSEMENT USD
    // ===============================================================
   
document.getElementById("BtnDecaisserUSD").addEventListener("click", function () {
    const soldeBrutUSD = document.getElementById("solde_CDF").textContent || "0";
    const data = {
        operation: "Dec_USD",
        Num_Pce: document.getElementById("numeroPieceDecUSD").value,
        beneficiaire: "Change interne",
        imputation: document.getElementById("ImputationUSD").value,
        motif: document.getElementById("motifDecaissementUSD").value,
        montant: document.getElementById("montantDecUSD").value,
        solde: nettoyerSolde(soldeBrutUSD),
        Id_Anne_Acad: document.getElementById("annee").value
    };

    if (!data.montant || parseFloat(data.montant) <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Montant invalide',
            text: 'Veuillez saisir un montant valide.',
            confirmButtonColor: '#ffc107'
        });
        return;
    }
    if (!data.imputation) {
        Swal.fire({
            icon: 'warning',
            title: 'Imputation requise',
            text: 'Veuillez sélectionner une imputation.',
            confirmButtonColor: '#ffc107'
        });
        return;
    }

    // ✅ Confirmation avant envoi
    Swal.fire({
        title: 'Confirmer le décaissement ?',
        html: `Montant: <b>${data.montant}</b><br>Solde restant: <b>${data.solde}</b>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
             envoyerDecaissement(data).then(() => {
                // ⚡ Actualiser les soldes après succès
                chargerSoldes();
            });
        }
    });
});

// 💶 DÉCAISSEMENT CDF
document.getElementById("BtnDecaisserCDF").addEventListener("click", function () {
    const soldeBrutCDF = document.getElementById("solde_USD").textContent || "0";
    const data = {
        operation: "Dec_CDF",
        Num_Pce: document.getElementById("numeroPieceDecCDF").value,
        beneficiaire: "Change interne",
        imputation: document.getElementById("ImputationCDF").value,
        motif: document.getElementById("motifDecaissementCDF").value,
        montant: document.getElementById("montantDecCDF").value,
        solde: nettoyerSolde(soldeBrutCDF),
        Id_Anne_Acad: document.getElementById("annee").value
    };

    if (!data.montant || parseFloat(data.montant) <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Montant invalide',
            text: 'Veuillez saisir un montant valide.',
            confirmButtonColor: '#ffc107'
        });
        return;
    }
    if (!data.imputation) {
        Swal.fire({
            icon: 'warning',
            title: 'Imputation requise',
            text: 'Veuillez sélectionner une imputation.',
            confirmButtonColor: '#ffc107'
        });
        return;
    }

    // ✅ Confirmation avant envoi
    Swal.fire({
        title: 'Confirmer le décaissement ?',
        html: `Montant: <b>${data.montant}</b><br>Solde restant: <b>${data.solde}</b>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
             envoyerDecaissement(data).then(() => {
                // ⚡ Actualiser les soldes après succès
                chargerSoldes();
            });
        }
    });
});
});

</script>
