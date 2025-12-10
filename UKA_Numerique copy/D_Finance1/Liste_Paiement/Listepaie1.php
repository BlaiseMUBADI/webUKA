

<link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      

<script>
function fonction_imprimer() {



var entete = document.getElementById('entete'); entete.style.display='block';
var Btn = document.getElementById('btn'); Btn.style.display='none';
var entete = document.getElementById('fin'); entete.style.display='none';
var entete = document.getElementById('tete'); entete.style.display='none';

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
var entete = document.getElementById('fin'); entete.style.display='block';
var entete = document.getElementById('tete'); entete.style.display='block';


}
</script>
<div class="container" style="display:none;" id="entete">

    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
               <img src="image/logo.png" style="float:left; width:5em; ">
                <center>

                <h6 class="font-family " style="font-family:Times New Roman; font-size: 22px; ">République Démocratique du Congo</h6>
                <h6>Ministère de l'Enseignement Supérieur et Universitaire</h6>
                <h6>Université Notre dame du Kasayi</h6>
                <h6 style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">ADMINISTRATEUR DE BUDGET</h6>
                <hr style=" border: 2px solid red;">

                <p style="font-family:Times New Roman;  font-size: 15px;">Liste de paiement des frais académiques <span id="affiche_promoton" style="font-weight: bold"></span></p>
                </center>


            </div>
        </div>
    </div>
</div>
<div class="" style="display:bloc" id="btn">
               
                <h6>Sélectionnez une option : </h6>
               
                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-start " name="option" type="radio" role="switch" id="LMD" value="LMD" checked >

                        <label class="form-check-label float-start"  for="case_ems">Système LMD </label> 
                    </div>
                    <div class="form-check form-switch">

                        <input class="form-check-input float-start  " name="option" type="radio" role="switch" id="PADEM" value="Ancien systeme" >  
                        <label class="form-check-label float-start" for="case_ems" >Ancien système </label>
                    </div>
                </div>


            
                <label>Année Académique : </label>
                <select id="annee">
                  <?php 
                    $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                              while ($ligne = $reponse->fetch()) {?>

                    <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                </select>
                              &nbsp
                <label>Faculté : </label>
                <select size="" id="filiere" style="width:160px;" onchange="Selection_promo()" >
                  <option value="">Sélectionner</option>
                    <?php
                              
                      $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                              while ($ligne = $reponse->fetch()) {?>
                    <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                </select>
                    &nbsp &nbsp

                <label>Promotion : </label>
                <select id="promotion" style="width:160px;" >
                  <option value="">Sélectionner</option>
                </select>&nbsp &nbsp
            <button type="button"  class="btn btn-primary btn-effet" style="display:block" onclick="fonction_imprimer()"> Imprimer</button>

  </div>

<div class="row "id="block8"> 
            <div class="col-md-12" id="numero">
              <div class="card">
                <div class="card-header">
           
  <div class="border" >
    
      <table>

          <td>
              <span class="input-group-text">Frais Académiques fixés :</span>
          </td>
          <td>
              <span class="input-group-text" id="FA"></span>
          </td>
          <td>
              <span class="input-group-text">Tranche 1 :</span>
          </td>
          <td>
              <span class="input-group-text" id="tranche"></span>
          </td>
          <td>
              <span class="input-group-text">Enrôlement:</span>
          </td>
          <td>
              <span class="input-group-text"id="Enrolement"></span>
          </td>
          
      </table>
 

  <table class="table table-bordered table-striped table-sm"id="TabListePaie">
      <thead>
          <tr>
              <th>N°</th>
              <th>Matricule</th>
              <th>Nom</th>
              <th>PostNom</th>
              <Th>Prenom</Th>
              <th>Motif</th>
              <th>Montant payé</th>
          </tr>
      </thead>
      <tbody>

      </tbody>

  </table>

             
            
        </div>





 <script type="text/javascript" src="js/select_promo.js"></script>
 <script type="text/javascript" src="Liste_Paiement/js/AfficherListePaie.js"></script>

 