console.log("nous sommes dans selection promotion toutes"); 
 
    
  
function Affichage_promotion_toutes( ) {
  var Idfiliere = document.getElementById("filiere").value;
  var Idannee = document.getElementById("annee").value;

  // Réinitialiser le contenu de la balise select des promotions
  var cmb_promotion=document.getElementById("promotion");
  cmb_promotion.innerHTML = "";

  
  // Contacte de l'API PHP
  const url='D_Finance/API/Recup_prom_toutes.php?IdFiliere=' + Idfiliere+'&idannee='+Idannee;
        
  fetch(url) 
  .then(response => response.json())
  .then(data => {
    // Ajouter une option personnalisée au début
    const optionManuelle = document.createElement("option");
    optionManuelle.value = "Toutes";
    optionManuelle.textContent = "Situation facultaire";
    cmb_promotion.prepend(optionManuelle); // Ajoute au début

    // Ajouter les options de l'API
    data.forEach(infos => {
      const option = document.createElement("option");
      option.value = infos.cd_prom;
      option.textContent = infos.abv+" - "+infos.lib_mention;
  
      // Ajouter l'option à la balise select
      cmb_promotion.appendChild(option);

    });
   
  })
  .catch(error => console.error('Erreur lors de la récupération des promotions :', error));

}