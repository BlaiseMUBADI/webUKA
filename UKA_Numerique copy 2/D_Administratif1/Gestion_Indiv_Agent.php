  
 

<section class="home-section " style="height: auto;">
<script>
    
</script>

<div class="containe " style="display:none;font-family:perpetua;" id="entetepage">
                  <div style="float:left; width:5em;position:absolute;margin-top: 7%;margin-left: 2%; "id="logo">
                    <img src="D_Administratif/images/logo.png" >
                  </div>
              <div class="row">
                <div class="col-md-12 ">
                  <div class="mon_bloc ">

                                <center>
                              <br>
                                <P  style=" font-size: 16px; ">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE</P>
                                <P  style=" font-size: 15px; ">UNIVERSITE NOTRE-DAME DU KASAYI</P>
                               
                                <P  style=" font-size: 14px; font-weight: bold;display:block;">SECRETARIAT GENERAL ADMINISTRATIF</P>
                                <P  style=" font-size: 14px; font-weight: bold;display:block;" >Situation familiale de l'Agent</P>
                                
                                  <hr>
                              </center>
                  <b><u>  I. IDENTITE AGENT</u></b><br><br>
                  Nom, post-nom, prénom : <?php  echo "<b>". $_GET['nom']." ".$_GET['post']." ".$_GET['prenom']."</b>"?><br>
                  Lieu et date de naissance : <span id="LieuNais"></span><br>
                  Etat civil: <span id="Etatciv"> </span><br>
                  Nombre enfant : &nbsp &nbsp<span id="Nombre_enfant_fiche"></span><br><br>

                  <table class="TableEnfant"style="width: 100%; border-collapse: collapse; height: auto;">
                          <thead>
                            <th>N°</th>
                            <th>Nom complet</th>
                            <th>Lieu de naissance</th>
                            <th>Date de naissance</th>
                          </thead>
                          <tbody>
                          
                          </tbody>
                  </table>
                 <p style="line-height: 1.6;"> 
                Date d'engagement : <span id="DateEnga" style="font-weight: bold;"> </span> &nbsp &nbsp Niveau d'étude : <span id="Niveau" style="font-weight: bold;"> </span>
                Année de l'obt. de diplôme : <span id="AnneDiplome"style="font-weight: bold;"> </span><br>
                Institution : <span id="Institution"style="font-weight: bold;"> </span>
                &nbsp Domaine : <span id="domaine"style="font-weight: bold;"> </span> <br>
                Adresse physique : <span id="Adr"style="font-weight: bold;"> </span> <br>
                Tél : <span id="tel"style="font-weight: bold;"> </span> &nbsp E-mail: <span id="mail"style="font-weight: bold;"> </span>&nbsp Grade : <span id="grade"style="font-weight: bold;"> </span><br>
                Fonction : <span id="fonction"style="font-weight: bold;"> </span>&nbsp Matricule : <span id="matr"style="font-weight: bold;"> </span>
                </p>

                  </div>
                </div>
              </div>
            </div>



      <?php
        require_once 'D_Generale/Profil_Sec_Administratif.php';
       
      ?>

  <div class="home-content me-3 ms-3"  >
    <div class="sales-boxes m-0 p-0 " >
    <div class="row "style="width:100%;">
      <!-- Box 1 à gauche -->
      <div class="col-lg-9">
        <div class="recent-sales box" style="width:100%; height:200px; margin:0px;">
          <div class="mb-3 row">
            <div class="col-sm-4" style="width:100%; font-size:2em; margin:0px; color: white;">
              <input type="text" value="<?php echo $_GET['mat']; ?>" id="MatAgent" hidden>
              <?php
                echo "<b>". $_GET['nom']." ".$_GET['post']." ".$_GET['prenom']."</b><br>";
                $matricule = $_GET['mat']."<br>";
                if (strpos($matricule, 'NU') !== false) {
                  echo "Matriculé provisoirement : ".$_GET['mat']."<br>";
                } else {
                  echo "Matriculé : ".$_GET['mat']."<br>";
                }
              ?>
              <button type="button" id="FormAjoutParent" title="Enregistrez les parents" class="btn btn-primary mb-3" style="font-family:Perpetua;"><i class="fas fa-user-plus"></i>&nbsp Parents</button>
              <button type="button" id="FormAjoutEnfant" title="Ajouter les enfants" class="btn btn-primary mb-3" style="font-family:Perpetua; color:yellow;"><i class="fas fa-user-plus"></i>&nbsp Enfants</button>
              <button type="button" id="imprimerFiche" class="btn btn-primary mb-3" style="font-family:Perpetua;"><i class="fas fa-print icon-style"></i>&nbsp Fiche Fam.</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Box 2 à droite -->
      <div class="col-lg-3">
        <div class="recent-sales box" style="width:100%;height:200px; margin:0px;">
          <div class="mb-3 row">
            <div class="col-sm-4" style="width:100%; font-size:2em; margin:0px; color: white;">
              
            </div>
          </div>
        </div>
      </div>
    </div>


      <div id="fondTransparent"></div>
      <div id="fondTransparentEnfant"></div>

  <!-- Formulaire -- pour les parents-->
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

        
        <button type="button" id="EnregistrerParent" title="Enregistrez" class="btn btn-primary mb-3" style="font-family:Palatino Linotype;"><i class="fas fa-user-plus"></i> &nbsp Enregistrer</button>         
      </form>
      <table id="TableParent"style="width: 100%; border-collapse: collapse; height: auto;">
            <thead>
              <th>N°</th>
              <th>Nom complet</th>
              <th>Statut</th>
              <th>Année décès</th>
            </thead>
            <tbody>
            
            </tbody>
      </table>

  </div>
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

  <!-- Formulaire -- pour les enfants -->
   <div id="FormEnfants">
      <form >
      <table class="table table-no-border" style="">
      <legend style="color: white;">Enregistrement des enfants</legend>
      <tr>
        <button type="button" onclick="fermerFormulaireEnfants()" style="position: absolute; top: 10px; right: 10px; padding: 10px; background-color: red; color: white; border: none; border-radius: 10%; font-size: 18px;">X</button>
      </tr>
        <tr style="background-color: transparent;">
          <td><label for="" style="color: white;">Nom complet de l'enfant</label></td><td><input type="text" id="nomEnfant" name="" style="width:100%;font-family:Palatino Linotype;"></td>
        </tr>
        <tr style="background-color: transparent;">
          <td><label for="" style="color: white;">Lieu de naissance</label></td><td><input type="text" id="LieuNaisse" name="" style="width:100%;font-family:Palatino Linotype;"></td>
        </tr>
        <tr style="background-color: transparent;">
          <td><label for="" style="color: white;">Date de naissance</label></td><td><input type="date" id="Datenaisse" name="" style="width:100%;font-family:Palatino Linotype;"></td>
        </tr>
        <tr style="background-color: transparent;">
          <td></td>
          <td>
            <button type="button" id="enregistrerEnfant" title="Enregistrez" class="btn btn-primary mb-3" style="font-family:Palatino Linotype;"><i class="fas fa-user-plus"></i>Enregistrer</button>         
          </td>
        </tr>
        </table>
      </form> 
      <table class="TableEnfant"style="width: 100%; border-collapse: collapse; height: auto;">
            <thead>
              <th>N°</th>
              <th>Nom complet</th>
              <th>Lieu de naissance</th>
              <th>Date de naissance</th>
            </thead>
            <tbody>
            
            </tbody>
      </table>
    </div>
     
    </div>
    <div class="overview-boxes m-0 p-0 mt-3" style="font-family:perpetua; margin:0px; display: flex; flex-wrap: wrap; gap: 20px;">
    
    <!-- Box 1 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#2d87f0;"> <!-- Bleu clair -->
        <div class="right-side">
            <span class="fas fa-user" style="color:#ffcc00; font-size:2em;"></span>
            <i style="color:white; font-size:2em; margin-right:10px; text-decoration:underline; cursor:pointer;" id="famille">Famille</i>  
            <div class="box-topic">Nombre parent : <span id="Nombre_parent" style="color:#f1f1f1;"></span></div>
            <div class="box-topic"> 
                &nbsp;&nbsp;&nbsp; En vie : <span id="Nombre_parent_en_Vie">2</span><br>
                &nbsp;&nbsp;&nbsp; Décédé(s) : <span id="Nombre_parent_decede">2</span>
            </div>
            <div class="box-topic">Nombre Enfant : <span id="Nombre_enfant">2</span></div>
        </div>
    </div>

    <!-- Box 2 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#f8a400;"> <!-- Jaune orangé -->
        <span class="fas fa-check-circle" style="color:#2d87f0; font-size:2em;"></span>
        <div class="right-side">
            <div class="box-topic">&nbsp; Evaluation<span id="Eva" style="color:#ffffff;"></span></div>
            <div class="box-topic"></div>
        </div>
    </div>

    <!-- Box 3 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#ff6f61;"> <!-- Rouge corail -->
        <div class="right-side">
            <span class="fas fa-dollar-sign" style="color:#ffffff; font-size:2em;"></span>
            <i style="color:white; font-size:2em; margin-right:10px; text-decoration:underline; cursor:pointer;">Paie locale</i>  
            <div class="box-topic">Prime institutionnelle : <span id="Nombre_parent_en_Vie">2</span><br>
                Quinzaine : <span id="Nombre_parent_decede">2</span>
            </div>
            <div class="box-topic">Autres: <span id="Nombre_enfant">2</span></div>
        </div>
    </div>

    <!-- Box 4 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#28a745;"> <!-- Vert -->
        <span class="fas fa-coins" style="color:#ffd700; font-size:2em;"></span>
        <div class="right-side">
            <div class="box-topic">&nbsp; Mécanisation<span id="Mecanisation" style="color:#ffffff;"></span></div>
            <div class="box-topic"></div>
        </div>
    </div>

    <!-- Box 5 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#d63384;"> <!-- Rose fuchsia -->
        <span class="fas fa-gavel-plus" style="color:#ffffff; font-size:2em;"></span>
        <div class="right-side">
            <div class="box-topic">&nbsp; Actions disciplinaires<span id="Action" style="color:#ffffff;"></span></div>
            <div class="box-topic"></div>
        </div>
    </div>
    <!-- Box 6 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#2d87f0;"> <!-- Bleu clair -->
        <div class="right-side">
            <span class="fas fa-bed" style="color:white; font-size:2em;"></span>
            <i style="color:white; font-size:2em; margin-right:10px; text-decoration:underline; cursor:pointer;" id="conge">Congé</i>  
            <div class="box-topic"><i class="fas fa-calendar-day" ></i> Congé payé : <span id="Nombre_parent" style="color:#f1f1f1;"></span></div>
            <div class="box-topic"><i class="fas fa-sick" ></i> Congé maladie : <span id="Nombre_parent" style="color:#f1f1f1;"></span></div>
            <div class="box-topic"><i class="fas fa-baby" ></i> Congé matérnité : <span id="Nombre_parent" style="color:#f1f1f1;"></span></div>
            <div class="box-topic"><i class="fas fa-users" ></i> Congé paternité : <span id="Nombre_parent" style="color:#f1f1f1;"></span></div>
            <div class="box-topic"><i class="fas fa-gift" ></i> Congé paternité : <span id="Nombre_parent" style="color:#f1f1f1;"></span></div>
           
           
        </div>
    </div>
      <!-- Box 7 -->
    <div class="box" style="flex: 0 0 250px; min-height: 200px; background-color:#2d87f0;"> <!-- Bleu clair -->
        <div class="right-side">
            
           
        </div>
    </div>

</div>
<div id="fondTransparentConge">

</div>

        <div class=" wrapper login-2 " id="FormConge">
            <div class="containe">
                <div class="col-left">
                    <div class="login-form">
                        <h2>Congé</h2>
                        <form>
                          Type de congé :
                            <p>
                              
                              <select id="typeConge" name="" class="form-control " style="width:100%;font-family:Palatino Linotype;">
                                  <option value="" selected>-</option>
                                  <?php 
                                        //Requette de sélection de catégorie agent
                                        $req="select * from type_conge order by IdTypeConge Asc";
                                        $data= $con-> query($req);
                                        while ($ligne=$data->fetch())
                                        {
                                        ?>
                                        <option value="<?php echo $ligne['IdTypeConge']?>"><?php echo $ligne['Libelle'];?></option>
                                        <?php 
                                          }
                                        ?>     
                                </select>
                            
                            </p>
                            Date-début :
                            <p>
                             
                              <input type="Date" id="date-debut" placeholder=" " required>
                            
                            </p>
                            Date-fin :
                            <p>
                              
                              <input type="Date" id="date-fin" placeholder=" " required>
                            
                            </p>
                            <p>
                                <input type="text" id="NbreJour" placeholder="Nombre de jours ouvrables" required>
                            </p>
                            <p>
                                <input class="btn" type="submit" value="Enregistrer" />
                            </p>
                           
                        </form>
                    </div>
                </div>
                    <button type="button" onclick="fermerFormConge()" style="position: absolute; top: 10px; right: -70px; padding: 10px; background-color: red; color: white; border: none; border-radius: 10%; font-size: 18px;">Fermer</button>
               
                <div class='col-right'>               
                    <div id='calendar'></div>              
                </div>
            </div>           
        </div>
  </div>
  
  
  
</section>
<script>  
 
  function fermerFormulaire() {
            document.getElementById('nouveauForm').style.display = 'none';
            document.getElementById('fondTransparent').style.display = 'none';
          
        }


        function fermerFormulaireEnfants() {
            document.getElementById('FormEnfants').style.display = 'none';
            document.getElementById('fondTransparentEnfant').style.display = 'none';
        }
        function fermerFormConge() {
            document.getElementById('FormConge').style.display = 'none';
            document.getElementById('fondTransparentConge').style.display = 'none';
        }
 


</script>


