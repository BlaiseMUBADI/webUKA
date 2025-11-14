

<link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      
<div class="" style="display:none;" id="entete">


               <img src="image/logo.png" style="float:left; width:5em; ">
                <center>

                <h6 class="font-family " style="font-family:Times New Roman; font-size: 22px; ">République Démocratique du Congo</h6>
                <h6>Ministère de l'Enseignement Supérieur et Universitaire</h6>
                <h6>Université Notre-Dame du Kasayi</h6>
                <h6 style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">FICHE DE SCOLARITE</h6>
                <hr style=" border: 2px solid red;">

                </center>

  <h5> I. IDENTITE</h5>
  <table>
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
  <table>
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
                        <Th>Motif paie</Th>
                        <th>Montant payé</th>

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
                        <button id="btn"type="button"  class="btn btn-primary btn-effet" style="display:none" onclick="fonction_imprimer()"> Imprimer</button>
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
                <span id="datesigne" class="text-end"></span>
                <span class="text-light">.</span>

                  <span id="acad"class="text-end"></span>
    
             <span class="text-light">.</span>
             <span class="text-light">.</span>
                  <span id="academ"class="text-end fw-bold "></span>
              </div>
          </div>
        </div>      
      <div>

 <script type="text/javascript" src="js/select_promo.js"></script>
 <script type="text/javascript" src="js/AfficherEtudiant.js"></script>

 <script>
function fonction_imprimer() {



var entete = document.getElementById('entete'); entete.style.display='block';
var Btn = document.getElementById('btn'); Btn.style.display='none';
var pied = document.getElementById('fin'); pied.style.display='none';
var entete = document.getElementById('tete'); entete.style.display='none';
var tab = document.getElementById('tableau'); tab.style.display='none';
var curs = document.getElementById('cursus'); curs.style.display='none';
var imprimer = document.getElementById('cursusImprimer'); imprimer.style.display='block';
var bloc1 = document.getElementById('block1'); bloc1.style.display='none';


var pag=block8.children;
var pagecomptage = 1;

 for (var i = 0; i < pag ; i++) {
  var page = pag[i];
  console.log('la valeur de temps'+block8.children.length);
  var pagenum= document.getElementById('numero');
  pagenum.textContent=pagecomptage;

  pagecomptage++;
  
}

window.print();
var Btn = document.getElementById('btn'); Btn.style.display='block';
var entete = document.getElementById('entete'); entete.style.display='none';
var curs = document.getElementById('cursus'); curs.style.display='none';
var pied = document.getElementById('fin'); pied.style.display='block';
var entete = document.getElementById('tete'); entete.style.display='block';
var tab = document.getElementById('tableau'); tab.style.display='block';
var bloc1 = document.getElementById('block1'); bloc1.style.display='block';
var curs = document.getElementById('cursus'); curs.style.display='block';
var imprimer = document.getElementById('cursusImprimer'); imprimer.style.display='none';




}

// la fonction pour afficher le tableau modifier le cursus d'un etudiant

  /*const boutonAfficherTableau = document.getElementById('btnAjuster');
const tableauContainer = document.getElementById('cursus');

boutonAfficherTableau.addEventListener('click', function() {
  console.log('nous sommes dans la fonction modifier cursus');

    tableauContainer.style.display = 'block';

});*/

</script>
<br>