<style>
  body {
    background-color: #f2f4f8;
    padding: 0px;
  }
  .card {
    border-radius: 15px;
  }
  .form-select, .form-control {
    border-radius: 10px;
  }
</style>

<body>
  <div class="container mt-2">
    <div class="card shadow p-4">
     <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0">Autorisation de sortie caisse</h2>
        <a href="Page_Principale_Finance.php?page=Autoriser" class="btn btn-info">Suivi de demandes</a>
      </div>

      <!-- Infos générales -->
      <div class="row mb-3">
        <!--<div class="col-md-4">
          <label for="libelleServiceUSD" class="form-label">Service</label>
          <select class="form-control" id="libelleServiceUSD">
            <?php /*
              $req1 = "SELECT Libelle AS Lib, concat('serv ',IdService) AS Id FROM service";
              $req2 = "SELECT concat('Fac. ', Libelle_Filiere) AS Lib, concat('fac ',IdFiliere) AS Id FROM filiere";
              $data1 = $con->query($req1);
              $data2 = $con->query($req2);
              while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
            ?>
              <option value="<?php echo $ligne1['Id']; ?>"><?php echo $ligne1['Lib']; ?></option>
            <?php } while ($ligne2 = $data2->fetch(PDO::FETCH_ASSOC)) { ?>
              <option value="<?php echo $ligne2['Id']; ?>"><?php echo $ligne2['Lib']; ?></option>
            <?php }*/ ?>
          </select>
        </div>-->
        <div class="col-md-4">
          <label for="beneficiaire" class="form-label">Bénéficiaire</label>
          <input type="text" class="form-control" id="beneficiaire" placeholder="Nom du bénéficiaire">
        </div>
        <div class="col-md-3">
          <label for="dateSaisie" class="form-label">Date de saisie</label>
          <input type="date" class="form-control" id="dateSaisie">
        </div>
      
        <div class="col-md-2">
          <label for="devise" class="form-label">Devise</label>
          <select class="form-select" id="devise">
            <option value="USD">USD ($)</option>
            <option value="CDF">CDF (FC)</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="numpc" class="form-label">Numéro de la pièce</label>
        <input type="text" class="form-control objet" id="numero_autorisation"  disabled>

          
        </div>
      </div>

      <!-- Zone dynamique de décaissements -->
      <form id="commandeForm">
        <div id="articlesContainer"></div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-success" onclick="ajouterLigne()">➕ Ajouter Ligne</button>
          <h4>Total : <span id="totalMontant">0.00</span> <span id="deviseAffichage">USD</span></h4>
          <input type="button" class="btn btn-success" value="Enregistrer" onclick="enregistrerAutorisation()">

        </div>
      </form>
    </div>
  </div>

  <script>
    let compteur = 0;

    function ajouterLigne() {
      compteur++;

      const container = document.getElementById('articlesContainer');
      const ligne = document.createElement('div');
      ligne.className = 'row g-3 mt-3 align-items-end ligne-decaissement';
      ligne.setAttribute('data-id', compteur);

      ligne.innerHTML = `
        <div class="col-md-6">
          <label class="form-label">Objet de décaissement</label>
          <input type="text" id="Motif" class="form-control objet" placeholder="Ex. Achat fournitures">
        </div>
        <div class="col-md-3">
          <label class="form-label">Imputation</label>
          
          <select class="form-control imputation" id="Imputation">
                                                    <?php 
                                                        $req1 = "SELECT Num_imputation, Intitul_compte AS Lib FROM t_imputation";
                                                        $data1 = $con->query($req1);
                                                        while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $ligne1['Num_imputation']; ?>"><?php echo $ligne1['Num_imputation']." - ". $ligne1['Lib']; ?></option>
                                                    <?php } ?>
                                                </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Montant</label>
          <input type="number" id="Montant" class="form-control montant" min="0" step="0.01" oninput="calculerTotal()">
        </div>
        <div class="col-md-1 text-end">
          <button type="button" class="btn btn-danger" onclick="supprimerLigne(this)">✖</button>
        </div>
      `;

      container.appendChild(ligne);
    }

    function supprimerLigne(button) {
      const ligne = button.closest('.ligne-decaissement');
      ligne.remove();
      calculerTotal();
    }

    function calculerTotal() {
      let total = 0;
      const montants = document.querySelectorAll('.montant');
      montants.forEach(input => {
        total += parseFloat(input.value) || 0;
      });
      document.getElementById('totalMontant').textContent = total.toFixed(2);
    }

    document.getElementById('devise').addEventListener('change', function () {
      document.getElementById('deviseAffichage').textContent = this.value;
    });

    // Ligne par défaut au chargement
    window.onload = () => {
      ajouterLigne();
     const devise = document.getElementById('devise').value;
document.getElementById('deviseAffichage').textContent = devise;
fetch(`D_Finance/API/Generer_num_Autorisation.php?devise=${devise}`)
  .then(response => response.json())
  .then(data => {
    if (data.numero) {
      document.getElementById('numero_autorisation').value = data.numero.replace(/\D/g, '');
    }
  });

    };


    document.getElementById('devise').addEventListener('change', function () {
  const devise = this.value;
  document.getElementById('deviseAffichage').textContent = devise;

  fetch(`D_Finance/API/Generer_num_Autorisation.php?devise=${devise}`)
    .then(response => response.json())
    .then(data => {
      if (data.numero) {
        document.getElementById('numero_autorisation').value = data.numero.replace(/\D/g, '');
      } else {
        alert("Erreur : " + (data.error || "Numéro non généré"));
      }
    })
    .catch(error => {
      console.error("Erreur AJAX :", error);
    });
});


function enregistrerAutorisation() {
  const devise = document.getElementById('devise').value;
  const beneficiaire = document.getElementById('beneficiaire').value;
  const dateSaisie = document.getElementById('dateSaisie').value;
  const numero = document.getElementById('numero_autorisation').value;

  const lignes = [];

  document.querySelectorAll(".ligne-decaissement").forEach(ligne => {
    lignes.push({
      Motif: ligne.querySelector(".objet").value,        // objet de décaissement
      Imputation: ligne.querySelector(".imputation").value, 
      Montant: ligne.querySelector(".montant").value
    });
  });

  // Préparer les données à envoyer
  const data = {
    numero: numero,
    devise: devise,
    beneficiaire: beneficiaire,
    dateSaisie: dateSaisie,
    lignes: lignes
  };

  fetch("D_Finance/API/Enregistrer_Autorisation.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("✅ Autorisation enregistrée avec le numéro : " + data.numero);
    } else {
      alert("❌ Erreur : " + data.error);
    }
  })
  .catch(err => console.error("Erreur AJAX :", err));
}


document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split('T')[0];

    const date = document.getElementById("dateSaisie");
    if (date) date.value = today;

    
});
setTimeout(function() {
    $('#Imputation').select2({
      width: '100%' // Ajuste automatiquement la largeur à celle du parent
    });
  }, 500);
  </script>
</body>
