



<link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      
<div class="" style="display:none;" id="entete">
    <img src="image/logo.png" style="float:left; width:5em;">
    <div style="text-align: center;">
        <h6 style="font-family:'Times New Roman'; font-size: 18px; margin: 0;">République Démocratique du Congo</h6>
        <p style="margin: 0;">Ministère de l'Enseignement Supérieur et Universitaire</p>
        <p style="margin: 0;">Université Notre-Dame du Kasayi</p>
        <p style="font-family:'Times New Roman'; font-size: 16px; font-weight: bold; margin: 5px 0 2px 0;">FICHE DE SCOLARITÉ</p>
        <hr style="border: 1.5px solid red; margin: 5px auto;">
    </div>


  <h5> I. IDENTITE</h5>
  <table class="table-sans-bordure">
  <tr>
    <td><i> Nom, Postnom, Pénom</i></td> <td>:</td><td><span class="fw-bold" id="nom"></span></td>
    
  </tr>
  <tr>
    <td><i>Lieu et date de naissance</i></td> <td>:</td><td><span id="lieu"></span></td>
    <td><i>Sexe</i></td> <td>:</td><td><span id="sexe"></span></td>
  </tr>
  <tr>
    <td><i>Nationalité</i></td> <td>:</td><td><span id="nationalite"></span></td>
    <td><i>Etat civil</i></td> <td>:</td><td><span id="Etat"></span></td>
  </tr>
  <tr>
    <td><i>Nom du père</i></td> <td>:</td><td><span id="pere"></span></td>
    <td><i>Nom de la mère</i></td> <td>:</td><td><span id="mere"></span></td>
  </tr>
  <tr>
    <td><i>Prov. d'Org.</i></td> <td>:</td><td><span id="provorg"></span></td>
    <td><i>Territoire/Commune</i></td> <td>:</td><td><span id="territ"></span></td>
  </tr>
  <tr>
    <td><i>Adresse physique</i></td> <td>:</td><td><span id="adresse"></span></td>
    <td><i>Contact d'urgence</i></td> <td>:</td><td><span id="contact"></span></td>
  </tr>
  </table> 
  <h5> II. ETUDES SECONDAIRES</h5>
  <table class="table-sans-bordure">
  <tr>
    <td><i> N° Diplôme</i></td> <td>:</td><td><span id="num"></span></td>
    <td><i> Mention/%</i></td> <td>:</td><td><span id="pourc"></span></td>
    
  </tr>
  <tr>
    <td><i>Option</i></td> <td>:</td><td><span id="option"></span></td>
    <td><i>Section</i></td> <td>:</td><td><span id="section"></span></td>
  </tr>
  <tr>
    <td><i>Délivré à</i></td> <td>:</td><td><span id="lieudeliv"></span></td>
    <td><i>Date</i></td> <td>:</td><td><span id="datedeliv"></span></td>
  </tr>
  <tr>
    <td><i>Ecole</i></td> <td>:</td><td><span id="ecole"></span></td>
    <td><i>Province</i></td> <td>:</td><td><span id="prov"></span></td>
  </tr>

  </table>  
</div>



    <div class="col-md-12 table-responsive"id="tableau">
               
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-start " name="option" type="radio" role="switch" id="LMD" value="LMD" checked >

                        <label class="form-check-label float-start"  for="case_ems">Système LMD </label> 
                    </div>
                    <div class="form-check form-switch">

                        <input class="form-check-input float-start  " name="option" type="radio" role="switch" id="PADEM" value="Ancien systeme" >  
                        <label class="form-check-label float-start" for="case_ems" >Ancien système </label>
                    </div>
              

   

            <table class="table table-sm">
                <tr>
                    <td> <span class="input-group-text" style="height: 35px;">Année</span></td>
                    <td>
                        <select id="annee" style="height: 35px;" class="form-select-lg mb-3">
                        <?php 
                            $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                            while ($ligne = $reponse->fetch()) {?>

                            <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                        </select>
                    </td>
                    <td>
                    <span class="input-group-text" style="height: 35px;">Faculté </span>
                    </td>
                    <td>
                    <select class="form-select-lg mb-3" id="filiere" style="width:250px;height: 35px;" onchange="Selection_promo()" >
                        <option value="">Sélectionner</option>
                        <?php
                        
                        $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                        while ($ligne = $reponse->fetch()) {?>
                        <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                    </select>
                    </td>
                    <td>
                    <span class="input-group-text"style="height: 35px;">Promotion</span>
                    </td>
                    <td>
                    <select class="form-select-lg mb-3" id="promotion" style="width:250px;height: 35px;" >
                        <option value="">Sélectionner</option>
                    </select>
                    </td>
                </tr>
            </table>
    
  </div>
      
  <div class="container" > 
   
        <div class="row"> 
            <div class="col-md-6" id="block1"style="height: 370px; overflow:auto;">
              <div class="card">
                <div class="card-header">
           

                  <table class="table table-bordered table-striped table-sm" id="tableListe" style="cursor:pointer;">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>Matricule</th>
                        <th>Noms</th>
                        <Th>Postnom</Th>
                        <th>Prénom</th>
                        <th>Sexe</th>

                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>

                </div>
              </div>                      
            </div>
        
            <div class="col-md-6" id="block8">
              <div class="card"id="cursus">
                <div class="card-header">
                  <table>
                    <tr>
                      <td>
                        <span class="border" id="name"></span>
                      </td>
                      <td>
                        <button id="btn"type="button"  class="btn btn-primary btn-effet" style="display:none" onclick="imprimer()"> Imprimer</button>
                      </td>
                    
                    </tr>
                  </table> 

                  <table class="table table-bordered table-striped table-sm" id="tableFiche">
                    
                    <thead>
                  
                    </thead>
                    <tbody>
                          
                    </tbody>
                    <tr>
                          </tr>
                  </table>

                </div>
              </div>                      
            </div>
        </div>
        <div class="row"> 
            
          <div class="col-md-12"id="cursusImprimer"style="display:none">
         
                  
            <div class="card ">

                <div class="card-header">
           
                  <table class="table table-bordered table-sm text-body-emphasis" id="tableImprimer">
                    <thead>
                     
               
                    </thead>
                    <tbody>
                          
                    </tbody>
                  </table>
                 
                  
                </div>
                <div class="container" style="margin-top: 20px;">
  <div class="signature-container" style="text-align: center; margin-top: 20px;">
    <!-- Première ligne avec la date -->
    <div>
      <span id="datesigne" style="font-size: 12pt; font-weight: bold;"></span>
    </div>
    <!-- Deuxième ligne avec l'académie -->
    <div>
      <span id="acad" style="font-size: 12pt;"></span>
      
    </div>
    <!-- Dernière ligne avec "Signature" -->
    <div style="margin-top: 10px;">
      <span id="academ" style="font-size: 12pt; font-weight: bold;"></span>
    </div>
  </div>
</div>

              </div>
          </div>
        </div>      
      <div>

 <script type="text/javascript" src="js/select_promo.js"></script>
 <script type="text/javascript" src="js/AfficherEtudiant.js"></script>

 <script>
function imprimer() {
    var btn = document.getElementById('btn');
    if (btn) btn.style.display = 'none';

    var contenus = document.getElementById('entete').innerHTML;
    var contenu = document.getElementById('cursusImprimer').innerHTML;

    var fenetreImpression = window.open('', '', 'height=800,width=1000');
    fenetreImpression.document.write('<html><head><title>Impression Fiche Étudiant</title>');
    fenetreImpression.document.write('<style>');

    fenetreImpression.document.write(`
        @page {
            size: A4 portrait;
            margin: 2cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        #entete {
            text-align: center;
            margin: 0;
            padding: 0;
        }

      #entete h6, #entete p {
                margin: 0;
                padding: 0;
                line-height: 1.2;
                font-size: 14px;
            }


        #entete img {
            float: left;
            width: 5em;
            margin-right: 10px;
            margin-top: 5px;
        }

        hr {
            border: 1px solid red;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        thead {
            background-color: midnightblue;
            color: white;
        }
    `);

    fenetreImpression.document.write('</style></head><body>');
    fenetreImpression.document.write(contenus);
    fenetreImpression.document.write(contenu);
    fenetreImpression.document.write('</body></html>');
    fenetreImpression.document.close();

    fenetreImpression.focus();
    fenetreImpression.print();
    fenetreImpression.close();

    if (btn) btn.style.display = 'block';
}

</script>
<style>
.table-sans-bordure, .table-sans-bordure td {
  border: none !important;
  padding: 4px 8px;
  font-size: 14px;
}
</style>