

<script>
    function imprimer() {
         // Vérifie si un bouton radio est sélectionné
    var radios = document.getElementsByName('option'); 
    var estCoche = false;

    // Parcours tous les boutons radio pour vérifier si l'un est coché
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            estCoche = true;
            break; // Si un bouton est coché, on peut sortir de la boucle
        }
    }

    if (!estCoche) {
        // Si aucun bouton radio n'est coché, affiche un message
        alert("Veuillez sélectionner un bouton radio avant d'imprimer.");
        return; // Stoppe l'exécution de la fonction
    }



        var contenu = document.getElementById('Tablepaie').innerHTML;
      var entete = document.getElementById('entetepage').innerHTML;
      //var Logo = document.getElementById('logo').innerHTML;

        var fenetreImpression = window.open('', '', 'height=600,width=800');
        fenetreImpression.document.write('<html><head><title>Impression Rapport de Paie</title>');
        
        // Ajoutez ici vos styles inline
        fenetreImpression.document.write('<style>');
        fenetreImpression.document.write('body { font-family: Arial, sans-serif; }'); // Exemple de style inline
        fenetreImpression.document.write('table { width: 100%; border-collapse: collapse; }'); // Exemple de style inline
        fenetreImpression.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }'); // Exemple de style inline
        fenetreImpression.document.write('thead { background-color: midnightblue; color: white; }'); // Exemple de style inline
        
        
        
        fenetreImpression.document.write('</style>');
        fenetreImpression.document.write('</head><body>');
      
        // Afficher le contenu de la table avec les colonnes masquées
        
        fenetreImpression.document.write(entete);
        //fenetreImpression.document.write(Logo);
        fenetreImpression.document.write(contenu);
        fenetreImpression.document.write('</body></html>');
        fenetreImpression.document.close();
        fenetreImpression.print();
        fenetreImpression.close();
    }
</script>


  <section class="home-section mt-3" style="height: auto;">
        <?php
          require_once 'D_Generale/Profil_Sec_Administratif.php';
        ?>

      <div class="home-content me-3 ms-3 "id=""style="height:auto;"  >
        <div class="sales-boxes m-0 p-0 " >
          <div class="recent-sales box " style="width:100%; margin:0px;">
            <div class="col fs-7 fw-bolder font-weight-bold p-0" style="position: relative; left: 20px; color:white;">
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input float-start " name="option" type="radio" role="switch" id="Quinzaine" value="Quinzaine"  >

                        <label class="form-check-label float-start"  for="case_ems">Quinzaine </label> 
                    </div>
                    <div class="form-check form-switch">

                        <input class="form-check-input float-start  " name="option" type="radio" role="switch" id="Prime_Inst" value="Prime_Inst" >  
                        <label class="form-check-label float-start" for="case_ems" >    Prime institutionnelle </label>

                       <center> <button type="button" id="PrintListe" title="Imprimer" class="btn btn-primary mb-3 fas fa-print" style="font-family:Palatino Linotype;" onclick="imprimer()">Enregistrer</button>         
                       </center>
                      </div>

            </div>
            <div  class="row g-3 align-items-center"style="margin:auto;"id="">
            
              <table id="TablePaieLocale">
                      <thead>
                      
                      </thead>
                      <tbody>
                      
                      </tbody>
              </table>

            </div>
            

            <div class="container " style="display:none;" id="entetepage">
                  <div style="float:left; width:5em;display:non; position:absolute;margin-top: 7%;margin-left: 2%; "id="logo">
                    <img src="D_Administratif/images/logo.png" >
                  </div>
              <div class="row">
                <div class="col-md-12 ">
                  <div class="mon_bloc ">

                                <center>
                              <br>
                                <P  style="font-family:Times New Roman; font-size: 16px; ">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE</P>
                                <P  style="font-family:Times New Roman; font-size: 15px; ">UNIVERSITE NOTRE-DAME DU KASAYI</P>
                               
                                <P  style="font-family:Times New Roman; font-size: 14px; font-weight: bold;display:block;" id="prime1">SECRETARIAT GENERAL ADMINISTRATIF</P>
                                <P  style="font-family:Times New Roman; font-size: 14px; font-weight: bold;display:block;" id="quinze">LISTE DU PERSONNEL POUR LA QUINZAINE</P>
                                <P  style="font-family:Times New Roman; font-size: 14px; font-weight: bold; display:block;" id="prime">LISTE DU PERSONNEL POUR LA PRIME INSTITUTIONNELE</P>
                                
                                  <hr>
                              
                                
                              </center>


                  </div>
                </div>
              </div>
            </div>
            <div  class="row g-3 align-items-center"style="margin:auto; display:none;"id="Tablepaie">
            
            
              <table id="TableQuinzaine">
                      <thead>
                      </thead>
                      <tbody>
                      
                      </tbody>
              </table>
            </div>
           
            
          </div>
        </div>
      </div>
  </section>
    
       



