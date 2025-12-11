
//console.log("Le code promo est"+promotion);


  
  /*promo.addEventListener('change',(event) => {
    var idAnnee_Acad=anne.value;
    var code_promo=promo.value;
    console.log("la valeur de promotion est --- "+code_promo)
    console.log("la valeur de annee est ---- "+idAnnee_Acad)
    Affichage_FA(code_promo,idAnnee_Acad);
  
  });*/

  //la foction qui envoies les données à l'API et affiche le tableau de données renvoyées par l'API

function Affichage_FA(code_promo,idAnnee_Acad) 
{
  console.log("Nous sommes dans le js qui afficher le FA Fixé");

  const promo=document.getElementById("promotion");
  const anne=document.getElementById("annee");
//alert("Selectionner la mention d'affectation avant de cocher un etudiant à affecter !!");
  
  
  
  const lien='API_PHP/APISelect_FA_Fixe.php?code_prom='+code_promo+'&Id_annee_aca='+idAnnee_Acad;  
  fetch(lien) 
    .then(response => response.json())
    .then(data2 => 
    {
      data2.forEach(infos =>
        {
          var fa="";
          fa=infos.Montant;
          document.getElementById("FA").innerText = fa+" $";
          document.getElementById("Tranche").innerText = infos.Tranche+" $";
          document.getElementById("Enrol").innerText = "10 $";
          

        });
       
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte de l'API FA "+error);

        });
        
         

}