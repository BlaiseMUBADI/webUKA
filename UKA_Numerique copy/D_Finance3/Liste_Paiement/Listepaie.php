

<link rel="stylesheet" type="text/css" href="bootstrap\dist\css\bootstrap.min.css" >      
<style>
    @media screen and (max-width: 768px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
        max-width: 200px
    }
}

</style>
<script>
    
function imprimer() {
    var contenu = document.getElementById('TabListePaie').outerHTML;
    var entete = document.getElementById('entete').innerHTML;
    var fenetreImpression = window.open('', '', 'height=600,width=800');
    fenetreImpression.document.write('<html><head><title>Impression de contrôle</title>');
    fenetreImpression.document.write('<link rel="stylesheet" type="text/css" href="bootstrap/dist/css/bootstrap.min.css">');
    fenetreImpression.document.write('<style>');
    fenetreImpression.document.write('body { font-family: Arial, sans-serif; color: black; }');
    fenetreImpression.document.write('table { width: 100%; border-collapse: collapse; font-size: 12px; }');
    fenetreImpression.document.write('th, td { border: 1px solid black; padding: 6px; text-align: left; color: black; }');
    fenetreImpression.document.write('thead { background-color: #003366; color: white; }');
    fenetreImpression.document.write('tbody tr:nth-child(odd) { background-color: #f2f2f2; }');
    fenetreImpression.document.write('</style>');
    fenetreImpression.document.write('</head><body>');
    fenetreImpression.document.write(entete);
    fenetreImpression.document.write(contenu);
    fenetreImpression.document.write('</body></html>');
    fenetreImpression.document.close();
    fenetreImpression.print();
    fenetreImpression.close();
}

</script>
<div class="container" style="display:none;" id="entete">

    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
               <img src="image/logo.png" style="float:left; width:5em; ">
                <center>

                <b class="font-family " style="font-family:Times New Roman; font-size: 22px; ">République Démocratique du Congo</b><br>
                Ministère de l'Enseignement Supérieur et Universitaire <br>
                Université Notre dame du Kasayi <br>
                <b style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">ADMINISTRATEUR DE BUDGET</b>
                <hr style=" border: 2px solid red;">

                <p style="font-family:Times New Roman;  font-size: 15px;">Liste de paiement des frais académiques pour la promotion <span id="promot"></span> <span id="affiche_promoton" style="font-weight: bold"></span></p>
                </center>


            </div>
        </div>
    </div>
</div>

    <div class="container">
        <div class="row">
            <div class="col-5 m-2 bloc1"style="background-color: rgb(5, 23, 47); color: white;min-width:300px;min-width:500px;">
                <h6>Sélectionnez une option : </h6>
                <div class="form-check form-switch d-flex  align-items-center">
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-2" name="option" type="radio" role="switch" id="LMD" value="LMD" checked>
                        <label class="form-check-label" for="LMD">Système LMD</label>
                    </div> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-2" name="option" type="radio" role="switch" id="PADEM" value="Ancien systeme">
                        <label class="form-check-label" for="PADEM">Ancien système</label>
                    </div>
                </div>
                <h6>Précisez la Tranche :</h6>

                <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 0px; top:0px;">
                    <table class="responsive">
                        <tr>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input float-start " name="choix" type="radio" role="switch" id="MiSession" value="MiSession" checked >
                                    <label class="form-check-label float-start"  for="case_ems">Mi-Séssion</label> 
                                </div>
                                 <div class="form-check form-switch">
                        <input class="form-check-input float-start " name="choix" type="radio" role="switch" id="RatSem1" value="RatSem1" >
                        <label class="form-check-label float-start"  for="case_ems">Rattrapage Sem 1</label> 
                    </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input float-start " name="choix" type="radio" role="switch" id="GdeSession" value="GdeSession" >
                                    <label class="form-check-label float-start"  for="case_ems">Première Session </label> 
                                </div>
                            </td>
                            <td>...</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input float-start " name="choix" type="radio" role="switch" id="DeuxièmeSession" value="DeuxièmeSession">
                                    <label class="form-check-label float-start"  for="case_ems">Deuxième_Session </label> 
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input float-start " name="choix" type="radio" role="switch" id="d4" value="d4">
                                    <label class="form-check-label float-start"  for="case_ems">D4 </label> 
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                   
                    
                </div>

            </div>
           
            <div class="col-6 m-2 bloc1"style="background-color: rgb(5, 23, 47); color: white;min-width:500px;">
               <table class="responsive">
                <tr>
                    <td>
                        Année Académique 
                    </td>
                    <td>
                        <select id="annee">
                            <?php 
                            $reponse = $con->query ('SELECT * FROM annee_academique order by Annee_debut desc' );
                                while ($ligne = $reponse->fetch()) {?>

                            <option value="<?php echo $ligne['idAnnee_Acad'];?>"><?php echo $ligne['Annee_debut']; echo " - "; echo $ligne['Annee_fin'];?></option> <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                         Faculté
                    </td>
                    <td>
                        <select size="" id="filiere"  onchange="Selection_promo()" >
                            <option value="">Sélectionner</option>
                            <?php
                                
                            $reponse = $con->query ('SELECT * FROM filiere order by IdFiliere' );
                                while ($ligne = $reponse->fetch()) {?>
                            <option value="<?php echo $ligne['IdFiliere'];?>"><?php echo $ligne['Libelle_Filiere'];?></option> <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Promotion
                    </td>
                    <td>
                        <select id="promotion" style="width:160px;" >
                            <option value="">Sélectionner</option>
                        </select>
                    </td>
                </tr>
                
               </table>
              <br>
                    <div class="d-flex align-items-center mb-3">
                        <input type="text" class="form-control me-2" id="rechercher" placeholder="Recherchez par nom">
                        <button type="button" id="PrintListe" title="Imprimer" class="btn btn-primary fas fa-print" style="font-family:Palatino Linotype;" onclick="imprimer()">Imprimer</button>
                    </div>


            </div>
       
        <div class="row mt-0">
            <div class="col-12 m-2 table-responsive"   style="height: 370px; overflow:auto; min-width:600px;background-color: rgb(199, 208, 218); color: white;">
            <table style="display:none;">

                <td>
                    <span class="input-group-text">FA fixés :</span>
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
                <td>
                    <span class="input-group-text"id="TotalEnOrdre"></span>
                </td>
                <td>
                    <span class="input-group-text"id="TotalPasEnOrdre"></span>
                </td>
                <td>
                    <span class="input-group-text"id="TotalRienPayé"></span>
                </td>
            </table>
            
                <table class="table table-bordered mt-2 table-striped table-sm"id="TabListePaie" >
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>PostNom</th>
                            <Th>Prenom</Th>
                            <th>Libellé</th>
                            <th>Montant payé</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>

            </div>
        </div>
    </div>




 <script type="text/javascript" src="D_Finance/Liste_Paiement/js/select_promo.js"></script>
 <script type="text/javascript" src="D_Finance/Liste_Paiement/js/AfficherListePaie.js"></script>

 <script>
    document.getElementById("filiere").addEventListener("change", function() {
    let selectedText = this.options[this.selectedIndex].text;
    let tempSpan = document.createElement("span");
    tempSpan.style.visibility = "hidden";
    tempSpan.style.whiteSpace = "nowrap";
    tempSpan.innerText = selectedText;
    document.body.appendChild(tempSpan);
    this.style.width = tempSpan.offsetWidth + 20 + "px"; // Ajustement avec marge
    document.body.removeChild(tempSpan);
});
document.getElementById("promotion").addEventListener("change", function() {
    let selectedText = this.options[this.selectedIndex].text;
    let tempSpan = document.createElement("span");
    tempSpan.style.visibility = "hidden";
    tempSpan.style.whiteSpace = "nowrap";
    tempSpan.innerText = selectedText;
    document.body.appendChild(tempSpan);
    this.style.width = tempSpan.offsetWidth + 20 + "px"; // Ajustement avec marge
    document.body.removeChild(tempSpan);
});

 </script>