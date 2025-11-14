<style>
        /* Style pour le formulaire */
        #nouveauForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color:rgb(94, 60, 195);
            padding: 20px;
            z-index: 1001; /* Plus grand que le fond semi-transparent */
            border-radius: 10px;
            width: auto;
            box-sizing: border-box;
        }
        /* Style pour le fond semi-transparent */
        #fondTransparent {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000; /* Moins grand que le formulaire */
          }
          </style>
          
<section class="home-section " style="height: auto;">
      <?php
      require_once 'D_Generale/Profil_Sec_Administratif.php';
      ?>
       <script>
        function convertirEnMajuscule(element) {
            element.value = element.value.toUpperCase();
        }
    </script>
  <div class="home-content me-3 ms-3"  >

    
    <br>
    <script>
        function convertirPremiereLettreEnMajuscule(element) {
            let valeur = element.value;
            element.value = valeur.charAt(0).toUpperCase() + valeur.slice(1);
          }
    </script>
 

    <div class="sales-boxes m-0 p-0" >
      <div class="recent-sales box " style="width:100%; margin:0px; font-family:Times New Roman; font-size: 20px">
        <div class="row g-3 align-items-center "style="width:80%; margin:auto;">
        
       
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Matricule</label>
            <div class="col-sm-8">
              <input name="" id="matricule" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;" onblur="convertirEnMajuscule(this)" style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Nom</label>
            <div class="col-sm-8">
              <input name="" id="Nom" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;" onblur="convertirEnMajuscule(this)" style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Postnom</label>
            <div class="col-sm-8">
              <input onblur="convertirPremiereLettreEnMajuscule(this)" name="" id="Postnom" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;" style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Prènom</label>
            <div class="col-sm-8">
              <input onblur="convertirPremiereLettreEnMajuscule(this)" name="" id="Prenom" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;" style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Sexe</label>
            <div class="col-sm-8">  
              <select id="sexe" name="" class="form-control" style="width:50%;font-family:Palatino Linotype;">
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Lieu de Naissance</label>
            <div class="col-sm-8">
              <input onblur="convertirPremiereLettreEnMajuscule(this)" name="" id="LieuNaissance" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;" style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Date de Naissance</label>
            <div class="col-sm-8">
              <input type="date" name="" id="DateNaissance" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;" style="border-radius: 15%;">
            </div>
          </div>
          
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Etat civil</label>
            <div class="col-sm-8">
              <select id="EtatCivil" name="" class="form-control" style="width:50%;font-family:Palatino Linotype;" onchange="toggleMarriedFields()">
                <option value="Célibataire">Célibataire</option>
                <option value="Marié(e)">Marié(e)</option>
                <option value="Veuve">Veuve</option>
                <option value="Veuf">Veuf</option>
              </select>
            </div>
          </div>
        <div id="marriedFields" style="display:none;">
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Nom du conjoint</label>
            <div class="col-sm-8">
              <input name="" id="NomConjoint" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Lieu de naissance</label>
            <div class="col-sm-8">
              <input type="text" name="" id="LieuNaissanceCong" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;">
            </div>
            
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Date de naissance</label>
            <div class="col-sm-8">
              <input type="date" name="" id="DateNaissanceCong" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;">
            </div>
            
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Nombre enfant</label>
            <div class="col-sm-8">
              <input type="number" name="" id="NombreEnfant" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;"onchange="addChildFields()">
            </div>
            
          </div>
          <div id="childrenNamesFields"></div>
        </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Adresse physique</label>
            <div class="col-sm-8">
              <input name="" id="AdressePhysique" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Adresse Mail</label>
            <div class="col-sm-8">
              <input name=""type="mail" id="AdresseMail" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">N° Télephone</label>
            <div class="col-sm-8">
              <input name="" id="NumTel" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Date d'engagement</label>
            <div class="col-sm-8">
              <input type="date" id="DateEngagement" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Niveau d'étude</label>
            <div class="col-sm-8">
              <input name="" id="NiveauEtude" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Année obtention du diplôme</label>
            <div class="col-sm-8">
              <input name="" id="AnneeObtDiplome" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Institution/Ecole</label>
            <div class="col-sm-8">
              <input name="" id="Institution" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Domaine</label>
            <div class="col-sm-8">
              <input name="" id="Domaine" class="text-center form-control" style="width:100%; font-weight: bold; font-family:Palatino Linotype;">
            </div>
          </div>
         
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Catégorie</label>
            <div class="col-sm-8">  

              <select id="Categorie" name="" class="form-control " style="width:100%;font-family:Palatino Linotype;">
              <option value="" selected>-</option>
              <?php 
                    //Requette de sélection de catégorie agent
                    $req="select * from categorie order by IdCategorie Asc";
                    $data= $con-> query($req);
                    while ($ligne=$data->fetch())
                    {
                    ?>
                    <option value="<?php echo $ligne['IdCategorie']?>"><?php echo $ligne['Libelle'];?></option>
                    <?php 
                      }
                    ?>     
              </select>
            </div>
          </div>

          
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Grade</label>
            <div class="col-sm-8">
              <select id="Grade" name="" class="form-control" style="width:100%;font-family:Palatino Linotype;" onchange="toggleMarriedFields()">
                
              </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Service</label>
            <div class="col-sm-8">  
            <select id="Idservice" name="" class="form-control" style="width:100%;font-family:Palatino Linotype;">
                    <?php 
                    // Requêtes de sélection
                    $req1 = "SELECT Libelle AS Lib, concat('serv ',IdService) AS Id FROM service";
                    $req2 = "SELECT concat('Fac. ', Libelle_Filiere) AS Lib, concat('fac ',IdFiliere) AS Id FROM filiere";
                    
                    // Exécution des requêtes
                    $data1 = $con->query($req1);
                    $data2 = $con->query($req2);
                    
                    // Combinaison des deux ensembles de résultats
                    while ($ligne1 = $data1->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <option value="<?php echo $ligne1['Id']; ?>"><?php echo $ligne1['Lib']; ?></option>
                        <?php
                    }
                    
                    while ($ligne2 = $data2->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <option value="<?php echo $ligne2['Id']; ?>"><?php echo $ligne2['Lib']; ?></option>
                        <?php
                    }
                    ?>
            </select>

            </div>
          </div>


          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"  style="color: white;">Date affectation</label>
            <div class="col-sm-8"> 
                <input type="date" name="" id="dateAffectation"   class="text-center form-control"  style="width:100%;  font-weight: bold;font-family:Palatino Linotype;"  style="border-radius: 15%;">
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label" style="color: white;">Fonction </label>
            <div class="col-sm-8">
              <input type="text" name="" id="Fonction" class="text-center form-control" style="width:100%; font-weight: bold;font-family:Palatino Linotype;">
            </div>
            <br>
            <br>
            <br>
                <button type="button" id="enregistrer" class="btn btn-primary mb-3"style="font-family:Palatino Linotype;">Enregistrer</button>         
             
          </div>
      <!--    <div id="fondTransparent"></div>

 Formulaire 
<div id="nouveauForm">
      <form id="">
        <legend style="color: white;">Enregistrement des parents</legend>
        <button type="button" onclick="fermerFormulaire()" style="position: absolute; top: 10px; right: 10px; padding: 10px; background-color: red; color: white; border: none; border-radius: 50%; font-size: 18px;">X</button>
        
        <label for="noms" style="color: white;">Nom complet :</label>
        <input type="text" id="noms" name="noms" style="width:60%;font-family:Palatino Linotype;"><br><br>
        <label for="statut" style="color: white;">Statut :</label>
        <select id="statut" name="statut" style="width:20%;font-family:Palatino Linotype;" onchange="afficherChampAnnee('statut', 'anneeDeces')">
          <option value="En vie">En vie</option>
          <option value="Décédé">Décédé(e)</option>
        </select>
        <input type="text" id="anneeDeces" name="anneeDeces" placeholder="Année du décès" style="width:20%; display:none;" /><br><br>

        
        <button type="button" id="SaveParent" title="Enregistrez" class="btn btn-primary mb-3" style="font-family:Palatino Linotype;"><i class="fas fa-user-plus"></i> &nbsp Enregistrer</button>         
      </form>
      <table id="TableauParent"style="width: 100%; border-collapse: collapse; height: auto;">
            <thead>
              <th>N°</th>
              <th>Nom complet</th>
              <th>Statut</th>
              <th>Année décès</th>
            </thead>
            <tbody>
            
            </tbody>
      </table>
      <span id="Nombre_parent" hidden></span>
  </div>-->
<script>
function afficherChampAnnee(selectId, inputId) {
  var selectElement = document.getElementById(selectId);
  var inputElement = document.getElementById(inputId);
  if (selectElement.value === "Décédé") {
    inputElement.style.display = "inline";
  } else {
    inputElement.style.display = "none";
  }
}
</script>


    <!-- Script pour afficher et masquer le formulaire -->
  <!-- Script pour afficher et masquer le formulaire -->
  <script>
     

        function fermerFormulaire() {
            document.getElementById('nouveauForm').style.display = 'none';
            document.getElementById('fondTransparent').style.display = 'none';
        }

       

  function toggleMarriedFields() 
    { 
      var EtatCivil = document.getElementById('EtatCivil').value;
      var marriedFields = document.getElementById('marriedFields'); 
      if (EtatCivil === 'Marié(e)') { marriedFields.style.display = 'block'; } 
      else { marriedFields.style.display = 'none'; 
              document.getElementById('childrenNamesFields').innerHTML = ''; 
            } 
    } 

    function addChildFields() {
    var nombreEnfant = document.getElementById('NombreEnfant').value;
    var childrenNamesFields = document.getElementById('childrenNamesFields');
    childrenNamesFields.innerHTML = '';

    for (var i = 1; i <= nombreEnfant; i++) {
        var row = document.createElement('div');
        row.className = 'mb-3 row';
        var label = document.createElement('label');
        label.className = 'col-sm-4 col-form-label';
        label.style.color = 'white';
        label.textContent = 'Nom de l\'enfant ' + i;
        var div = document.createElement('div');
        div.className = 'col-sm-8';
        var input = document.createElement('input');
        input.className = 'text-center form-control';
        input.style.width = '100%';
        input.style.fontWeight = 'bold';
        input.style.fontFamily = 'Palatino Linotype';
        input.name = 'NomEnfant' + i;
        input.id = 'NomEnfant' + i; // Ajout d'un id unique pour chaque enfant
        row.appendChild(label);
        row.appendChild(div);
        div.appendChild(input);
        childrenNamesFields.appendChild(row);

        var row = document.createElement('div');
        row.className = 'mb-3 row';
        var label = document.createElement('label');
        label.className = 'col-sm-4 col-form-label';
        label.style.color = 'white';
        label.textContent = 'Lieu de naissance de l\'enfant ' + i;
        var div = document.createElement('div');
        div.className = 'col-sm-8';
        var input = document.createElement('input');
        input.className = 'text-center form-control';
        input.style.width = '100%';
        input.style.fontWeight = 'bold';
        input.style.fontFamily = 'Palatino Linotype';
        input.name = 'LieuNaisEnfant' + i;
        input.id = 'LieuNaisEnfant' + i; // Ajout d'un id unique pour chaque enfant
        row.appendChild(label);
        row.appendChild(div);
        div.appendChild(input);
        childrenNamesFields.appendChild(row);

        var dateRow = document.createElement('div');
        dateRow.className = 'mb-3 row';
        var dateLabel = document.createElement('label');
        dateLabel.className = 'col-sm-4 col-form-label';
        dateLabel.style.color = 'white';
        dateLabel.textContent = 'Date de naissance de l\'enfant ' + i;
        var dateDiv = document.createElement('div');
        dateDiv.className = 'col-sm-8';
        var dateInput = document.createElement('input');
        dateInput.type = 'date';
        dateInput.className = 'text-center form-control';
        dateInput.style.width = '100%';
        dateInput.style.fontWeight = 'bold';
        dateInput.style.fontFamily = 'Palatino Linotype';
        dateInput.name = 'DateNaissanceEnfant' + i;
        dateInput.id = 'DateNaissanceEnfant' + i; // Ajout d'un id unique pour chaque date de naissance
        dateRow.appendChild(dateLabel);
        dateRow.appendChild(dateDiv);
        dateDiv.appendChild(dateInput);
        childrenNamesFields.appendChild(dateRow);
    }
}


        // Fonction pour afficher la date actuelle au format YYYY-MM-DD
        function setCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
            const day = String(today.getDate()).padStart(2, '0');

            // Formater la date au format YYYY-MM-DD
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById('DateNaissance').value = formattedDate; // Affecter la valeur à l'input
        }

        // Appeler la fonction lors du chargement de la page
        window.onload = setCurrentDate;
     </script>

    </div>

  </div>
</section>
    
       



