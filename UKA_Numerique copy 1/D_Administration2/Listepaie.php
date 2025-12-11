<script>
  function fonction_imprimer() {

//var bloc = document.getElementById('liste_imprimer');
var menu3 = document.getElementById('menu3-1'); menu3.style.display='none';
var btn = document.getElementById('option1'); btn.style.display='none';
var Control = document.getElementById('controle'); Control.style.display='none';

var block3 = document.getElementById('fin'); block3.style.display='none';

var entete = document.getElementById('entete'); entete.style.display='block';
var block1 = document.getElementById('block1'); block1.style.display='block';
//var pied = document.getElementById('pied');
var pag=block1.children;
var pagecomptage = 1;

 for (var i = 0; i < pag ; i++) {
  var page = pag[i];
  console.log('la valeur de temps'+block8.children.length);
  //var pagenum= document.getElementById('numero');
  pagenum.textContent=pagecomptage;

 //page.insertBefore(pagenum, page.firstChild);
  
  pagecomptage++;

  //.textContent='page ' + (i+1);
  
}

/* var nbr=document.getElementsByClassName('numero');
for (var i = 0; i < nbr.length; i++) {
  nbr[i].textContent='page ' + (i+1);
}*/

window.print()


}

</script>
<link rel="stylesheet" type="text/css" href="../bootstrap\dist\css\bootstrap.min.css" >      
<div class="container"  id="entete" style="display: none;">
    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
               <img src="image/logo.jpg" style="float:left; width:5em; ">
                <center>

                <h6 class="font-family " style="font-family:Times New Roman; font-size: 22px; ">République Démocratique du Congo</h6>
                <h6>Ministère de l'Enseignement Supérieur et Universitaire</h6>
                <h6>Université Notre dame du Kasayi</h6>
                <h6 style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">ADMINISTRATEUR DE BUDGET</h6>
                <hr class="border border-primary">

                <p style="font-family:Times New Roman;  font-size: 15px;">Liste de paie des frais académiques <span id="affiche_promoton" style="font-weight: bold"></span></p>
                </center>


            </div>
        </div>
    </div>
</div>
<div class="" style="display:bloc" id="controle">
               
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
      </select>
  </div>
<div class="container "> 
    
        <div class="row "> 
            <div class="col-md-12" id="block1">
              <div class="card">
                <div class="card-header">
                       
                        <button type="buttom" id="option1" class="btn btn-primary btn-effet" style="display:bloc" onclick="fonction_imprimer()"> imprimer</button>

                  <table class="table table-bordered table-striped" id="table">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>Matricule</th>
                        <th>Noms</th>
                        <Th>Motif paie</Th>
                        <th>Montant payé</th>
                        <th>Tranche 1</th>
                        <th>Montant annuel</th>

                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
              </div>                      
            </div>
        </div>
  <div>
 <script type="text/javascript" src="js/select_promo.js"></script>
