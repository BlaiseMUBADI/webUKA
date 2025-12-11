

<script>
    

    function imprimer() {
    var contenu = document.getElementById('TabCharge').innerHTML;
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
    fenetreImpression.document.write(contenu);
    fenetreImpression.document.write('</body></html>');
    fenetreImpression.document.close();
    fenetreImpression.print();
    fenetreImpression.close();
}
</script>

<section class="home-section " style="height: auto;">
      <?php
        require_once 'D_Generale/Profil_Sec_Administratif.php';
      ?>

  <div class="home-content me-3 ms-3 "id=""style="height:auto;"  >
    <div class="sales-boxes m-0 p-0 " >
      <div class="recent-sales box " style="width:100%; margin:0px;">
        
        <div  class="row g-3 align-items-center"style="margin:auto;">
        
        <table id="TabCharge">
                <thead>
                
                </thead>
                <tbody>
                
                </tbody>
              </table>
        </div>
        <div class="mb-3 row">
            
             
        </div>
      </div>
    </div>
  </div>
</section>
    
       



