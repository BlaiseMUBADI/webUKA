

<script>
    

    function imprimer() {
var entete = document.getElementById('imprimer'); entete.style.display='none';
var btnimp = document.getElementById('imprimer2'); btnimp.style.display='none';

    var contenus = document.getElementById('entetepage').innerHTML;
    var contenu = document.getElementById('TabListe').innerHTML;
    var Pied = document.getElementById('pied').innerHTML;

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
    fenetreImpression.document.write(contenus);
    fenetreImpression.document.write(contenu);
    fenetreImpression.document.write(Pied);
    fenetreImpression.document.write('</body></html>');
    fenetreImpression.document.close();
    fenetreImpression.print();
    fenetreImpression.close();
    var entete = document.getElementById('imprimer'); entete.style.display='block';
    var btnimp = document.getElementById('imprimer2'); btnimp.style.display='block';

}
</script>
<div class="container" style="display:none;" id="entetepage">

    <div class="row">
        <div class="col-md-12">
            <div class="mon_bloc ">
               <img src="D_Administratif/images/logo.png" style="float:left; width:10em; ">
                <center>
             <br>
                <P  style="font-family:Times New Roman; font-size: 16px; ">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE</P>
                <P  style="font-family:Times New Roman; font-size: 15px; ">UNIVERSITE NOTRE-DAME DU KASAYI</P>
                <P  style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">RECTORAT</P>
                <P  style="font-family:Times New Roman; font-size: 14px; font-weight: bold;">LISTE DECLARATIVE DU PERSONNEL</P>
                
                   <hr>
               
                
              </center>


            </div>
        </div>
    </div>
</div>
<section class="home-section " style="height: auto;width:150%;">
      <?php
        require_once 'D_Generale/Profil_Sec_Administratif.php';
      ?>
               

  <div class="home-content  me-3 ms-3 "id="TabListe"style="height:auto;"  >
    
    <div class="sales-boxes m-0 p-0 " >
      
      <div class="recent-sales box " style="width:100%; margin:0px;">
        
        <div  class="row g-3 align-items-center"style="margin:auto;">
          <button type="submit" id="imprimer" class="btn btn-primary mb-3 "style="font-family:Palatino Linotype;"onclick="imprimer()"><i class="fas fa-print icon-style"></i> Print</button>         
        
            <table id="TabListeAgent">
                <thead>
                
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
      
      </div>
      
    </div>
  <button type="submit" id="imprimer2" class="btn btn-primary mb-3 fas fa-print icon-style"style="font-family:Palatino Linotype;"onclick="imprimer()">Print</button>         

  </div>
</section>
    
<div class="container" style="display:none;" id="pied">

<div class="row">
    <div class="col-md-12">
        <div class="mon_bloc ">
           <img src="images/logo.png" style="float:left; width:10em; ">
            <center>

            <P class="font-family " style="font-family:Times New Roman; font-size: 16px; ">Fait à Kananga, <span id="date">,</span></P>
            <P class="font-family " style="font-family:Times New Roman; font-size: 15px; ">La Rectrice de l'Université Notre-Dame du Kasayi,</P>
            <br>
            <P style="font-family:Times New Roman; font-size: 14px; font-weight: bold;"> Professeure Joséphine BITOTA Muamba.</P>

          
          </center>


        </div>
    </div>
</div>
</div>  

<script>
  document.addEventListener('DOMContentLoaded', function() {
   var dateElement = document.getElementById('date');
   var currentDate = new Date();
   var options = { year: 'numeric', month: 'long', day: 'numeric' };
   dateElement.textContent = currentDate.toLocaleDateString('fr-FR', options);
});

</script>

