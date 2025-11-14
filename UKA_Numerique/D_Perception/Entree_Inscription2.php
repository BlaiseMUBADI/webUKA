<?php

$Nom_agent=$_SESSION['Nom_user']." ".$_SESSION['Postnom_user'];

?>
<script>
    var nomAgent = "<?php echo $Nom_agent; ?>";

function imprimerBloc() {
  const date=document.getElementById("datepaie");
  document.getElementById("dateraport").innerText=date.value;

  document.getElementById("nomagent").innerText=nomAgent;

  var contenu = document.getElementById('bloc-imp-Rapport').innerHTML;
  var fenetreImpression = window.open('', '', 'height=600,width=800');
  fenetreImpression.document.write('<html><head><title>Impression Rapport de Paie</title>');
  fenetreImpression.document.write('</head><body >');
  fenetreImpression.document.write(contenu);
  fenetreImpression.document.write('</body></html>');
  fenetreImpression.document.close();
  fenetreImpression.print();
  fenetreImpression.close();
}
function fonction_imprimer_recu() {
  const date=document.getElementById("datepaie");
  document.getElementById("daterecu").innerText=date.value;
  const nom_etudiant=document.getElementById("Nom").value+" "+document.getElementById("Postnom").value
  +" "+document.getElementById("Prenom").value;
  document.getElementById("nometud").innerText=nom_etudiant;
  const fil=document.getElementById("filiereInscription");
  const selectedfiliere = fil.options[fil.selectedIndex].text;
  document.getElementById("filiere").innerText="Filière: "+selectedfiliere;




  document.getElementById("montantrecu").innerText=document.getElementById("montant").innerText;
  
  if(document.getElementById("montant").innerText=="0 $")
  {document.getElementById("Sommelettre").innerText="Zéro dollar";}
  else if(document.getElementById("montant").innerText=="10 $")
  {document.getElementById("Sommelettre").innerText="Dix dollars";}
  else( document.getElementById("Sommelettre").innerText="Cent dollars");


  document.getElementById("nomagentrecu").innerText=nomAgent;

 
}

</script>

<div class="row" id="bloc-imp-Rapport" style="display:none;">
  <div class="col-md-12">
    <div class=" "name="mon_bloc">
      <img src="images/logouka.jpg" style="float:left; width:5em; ">
      <center>

      <b> République Démocratique du Congo</b> <br>
        Ministère de l'Enseignement Supérieur et Universitaire <br>
      <b>  Université Notre dame du Kasayi (U.KA.)<br><br>
          ADMINISTRATION DE BUDGET</b>
        <hr style=" border: 2px solid red;">
        <p style="font-family:Times New Roman;  font-size: 15px;">Liste de paiement des frais d'inscription <span id="promot"></span> <span id="affiche_promoton" style="font-weight: bold"></span></p>
      </center>
      <div style="float:right;">
        <table>
          <tr>
            <td>
              <label for="dateraport" class="me-2" style="font-family: Palatino Linotype; font-size: 1em;">Kananga, le </label>
            </td>
            <td>
              <p id="dateraport" style="font-size:1em; font-family:Palatino Linotype;"></p>
            </td>
          </tr>
          <tr>
            <td>
              <label  class="me-2" style="font-family: Palatino Linotype; font-size: 1em;">Le Guichetier </label>
            </td>
            <td>
              <p id="nomagent" style="font-size:1em; font-family:Palatino Linotype;"></p>
            </td>
          </tr>
        </table>
      </div>      
    </div>
  </div>
  <br>
  <table   id="table_paiement_inscription" style="width:100%; height:auto;">              
    <thead class="sticky-sm-top m-0 fw-bold">
      <tr>
        <th>N°</th>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Postnom</th>
        <th>Prenom</th>
        <th>Sexe</th>
        <th>Montant</th>
        <th>Libellé</th>
      </tr>
    </thead>
    <tbody>
            
    </tbody>
  </table>
</div>

<div class="row" id="bloc-imp-recu" style="display:none;">
  <div class="col-md-12 border">
    
      <img src="images/logouka.jpg" style="float:left; width:5em; ">
      <center>

        <p style="font-family:Times New Roman;  font-size: 15px;">RECU D'INSCRIPTION</p>
      </center>
      <p id="filiere"></p>
      <span style=" border:solid 2px red; float:right; height:auto; width:auto;margin: top 50px; font-size:2em;width:5em; text-align:center;" id="montantrecu">Montant</span>
      
      <table style="margin-top:10%;">
          <tr>
            <td>
              <label style="font-family: Palatino Linotype; font-size: 1em;">Nom de l'étudiant(e) :</label>
            </td>
            <td>
            <b> <div id="nometud" style="font-size:1em; font-family:Palatino Linotype;"></div></b>
            </td>
          </tr>
          <tr>
            <td>
              <label style="font-family: Palatino Linotype; font-size: 1em;">Somme en lettre :</label>
            </td>
            <td>
              <p id="Sommelettre" style="font-size:1em; font-family:Palatino Linotype; text-align:italic;"></p>
            </td>
          </tr>
          <tr>
            <td>
              <label  style="font-family: Palatino Linotype; font-size: 1em;">Motif :</label>
            </td>
            <td>
              <p id="Motif" style="font-size:1em;font-family:Palatino Linotype;"><i>Frais d'inscription</i></p>
            </td>
          </tr>
        <table>   
           
      <div >
        <table style="float:right;">
          <tr>
            <td>
              <label   style="font-family: Palatino Linotype; font-size: 1em;">Kananga, le </label>
            </td>
            <td>
              <p id="daterecu" style="font-size:1em; font-family:Palatino Linotype;"></p>
            </td>
          </tr>
          <tr>
            <td>
              <label  class="me-2" style="font-family: Palatino Linotype; font-size: 1em;">Le Guichetier </label>
            </td>
            <td>
              <p id="nomagentrecu" style="font-size:1em; font-family:Palatino Linotype;"></p>
            </td>
          </tr>
        </table>
        
    </div>
  </div>
 
</div>



<section class="home-section" style="height: 100%;"style="display:bloc;"id="identite">
      <?php
        require_once 'D_Generale/Profil_Guichet.php';
      ?>
       <script>
        function convertirEnMajuscule(element) {
            element.value = element.value.toUpperCase();
        }
    </script>
  <div class="home-content me-3 ms-3"  >

    <div class="sales-boxes m-0 p-0" style="height:5%;"style="display:bloc" id="btn">
      <div class="recent-sales box" style=" width:100%; margin:0px; ">
      
          <form class="m-0 p-0" method="POST" enctype="multipart/form-data" action="" style="width:100%;">  
          <label style="color: white;">Faculté:</label>     
          <select  name="filiereInscription" id="filiereInscription"   class="text-center"    style="border: 1px solid green;width:30%;">
            <option value=""></option>
              <?php 
                $req="select * from filiere order by LENGTH(Libelle_Filiere) asc ";
                $data= $con1-> query($req);
                while ($ligne=$data->fetch())
                {
              ?>
                  <option style=" width:100%;"value=<?php echo $ligne['IdFiliere'];?>><?php echo $ligne['Libelle_Filiere']?></option>
                  
                  <?php 
                }
                  ?>
          </select>


          
          <label style="color: white;">Promotion:</label>
          <select id="promoInscription"  class="text-center" name="Codepromo" style="width: 30%;">
            <option value="" style="border:1px solsid red;"></option>
          </select>


          <label style="color: white;">Année:</label>
          <select id="Id_an_acadInscription" class="text-center"  style="width: 12%;" >
                
                  <?php 
                    //Requette de sélection Année Académique
                    $req="select * from annee_academique order by Annee_debut desc";
                    $data= $con1-> query($req);
                    while ($ligne=$data->fetch())
                    {
                    ?>

                    <option value="<?php echo $ligne['idAnnee_Acad']?>"><?php echo $ligne['Annee_debut'];?>-<?php echo $ligne['Annee_fin'];?></option>

                    <?php 
                      }
                    ?>
          </select>
        </form>
        
   
            
      </div>
    </div>
    <br>
    <script>
        function convertirPremiereLettreEnMajuscule(element) {
            let valeur = element.value;
            element.value = valeur.charAt(0).toUpperCase() + valeur.slice(1);
          }
    </script>
    <div class="sales-boxes m-0 p-0 " >
      <div class="recent-sales box" style=" width:50%; margin:0px;" >    


        <div class="row g-3 align-items-center">
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Matricule</label>
            <div class="col-sm-10">  
              <input   name="" id="matricule" disabled  class="text-center form-control"  style="width:100%;  font-weight: bold; font-family:Palatino Linotype;"onblur="convertirEnMajuscule(this)"  style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Nom</label>
            <div class="col-sm-10">  
              <input   name="" id="Nom"   class="text-center form-control"  style="width:100%;  font-weight: bold; font-family:Palatino Linotype;"onblur="convertirEnMajuscule(this)"  style="border-radius: 15%;">
            </div>
          </div>
            
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Postnom</label>
            <div class="col-sm-10">  
              <input onblur="convertirPremiereLettreEnMajuscule(this)"  name="" id="Postnom"   class="text-center form-control"  style="width:100%;  font-weight: bold;font-family:Palatino Linotype;"  style="border-radius: 15%;">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Prènom</label>
            <div class="col-sm-10">  
              <input onblur="convertirPremiereLettreEnMajuscule(this)"  name="" id="Prenom"   class="text-center form-control"  style="width:100%;  font-weight: bold;font-family:Palatino Linotype;"  style="border-radius: 15%;">
            </div>
          </div>
          
        </div>
      </div> 

      <!-----------------------------  Deuxième sous bloc ----------------------------------->

      <div class="recent-sales box" style=" width:50%; margin:0px; ">    
        <div class="row g-3 align-items-center">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Sexe</label>
            <div class="col-sm-10">  
              <select id="sexe" name="sexe" class="form-control" style="width:50%;font-family:Palatino Linotype;">
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Critères</label>
            <div class="col-sm-10">  
              <select id="choix" name="" class="form-control" style="width:100%;font-family:Palatino Linotype;">
                        <option value="">Selection</option>
                        <option value="plus">Pourcentage plus ou égal à 60 %</option>
                        <option value="moins">Pourcentage moins de 60 %</option>
                        <option value="speciale">Inscription spéciale</option>
                    </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" style="color: white;">Montant</label>
            <div class="col-sm-10">  
              <input  name="" id="montantàinserer"   class="text-center form-control"  style="width:100%;  font-weight: bold;font-family:Palatino Linotype;"  style="border-radius: 15%;"> 
            </div>
          </div>


          <div class="mb-3 row">
          <label class="col-sm-2 col-form-label" id="montant"hidden style="color: white;">.</label>
          <label class="col-sm-2 col-form-label" id="" style="color: white;">...</label>
          <label class="col-sm-2 col-form-label" id="idfrais" hidden style="color: white;">.</label>
            <div class="col-sm-10"> 
            <input type="date" name="" id="datepaie"   class="text-center form-control"  style="width:100%;  font-weight: bold;font-family:Palatino Linotype;"  style="border-radius: 15%;">
              
            <button type="submit" id="enregistrer" class="btn btn-primary mb-3"style="font-family:Palatino Linotype;" onclick="fonction_imprimer_recu()">Enregistrer</button>
            <button type="submit" id="Rapport" class="btn btn-primary mb-3"style="font-family:Palatino Linotype;"onclick="imprimerBloc()">Imprimer le rapport</button>
                     
                      
            </div>
          </div>
        </div>
      </div>
    
      <script>
        // Fonction pour afficher la date actuelle au format YYYY-MM-DD
        function setCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
            const day = String(today.getDate()).padStart(2, '0');

            // Formater la date au format YYYY-MM-DD
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById('datepaie').value = formattedDate; // Affecter la valeur à l'input
        }

        // Appeler la fonction lors du chargement de la page
        window.onload = setCurrentDate;
    </script>
      
      
   
      
      
      
    </div>
    
    <div class="sales-boxes m-0 p-3 mt-3 border" 
          style="background-color:rgb(39,55,70);"style="display:bloc;"id="Afficheidentite">
      
      <div class="container table-responsive small p-0 m-0" 
          style="width: 60%; float: left; height:400px">

        <table  class="tab1" id="table_paiement" style="width:100%; height:100%;">              
          <thead class="sticky-sm-top m-0 fw-bold">
            <tr>
              <th>N°</th>
              <th>Matricule</th>
              <th>Nom</th>
              <th>Postnom</th>
              <th>Prenom</th>
              <th>Sexe</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
    
      <!-- Ici c'est le bloc pour léffichage en détail et faire un paiement-->
      
      <!-- Ici c'est pour stocker les infos de l'utilisateurs 
          (matricul,nom et autres) est qu'il soit invisible-->

      
      <div class="bloc2 shadow-lg bg-body-tertiary rounded border m-0 p-3 m-0" 
                  style="color:white; float: right; width: 39%;margin-left:7px;">
        <center><h5  id="nom_etudiant"class="text border"sytle="width:100%;"></h5> </center> 
        

     

      </div> 
    </div> 
  
    
    
    
    </div> 
  </div>
</section>
    
       



