console.log("Nous sommes dans le js qui afficher la liste de paiement ");

const promotion=document.getElementById("promotion");
const annee=document.getElementById("annee");
//console.log("Le code promo est"+promotion);


  
  promotion.addEventListener('change',(event) => {
    var idAnnee_Acad=annee.value;
    var code_promo=promotion.value;
    console.log("la valeur de promotion est :::: "+code_promo)
    console.log("la valeur de annee est :::: "+idAnnee_Acad)
    Affichage_Liste(code_promo,idAnnee_Acad);
  
  });

  //la foction qui envoies les données à l'API et affiche le tableau de données renvoyées par l'API

  function Affichage_Liste(code_promo,idAnnee_Acad) 
{
//alert("Selectionner la mention d'affectation avant de cocher un etudiant à affecter !!");

  var tableau = document.getElementById("table");
  var tbody = document.createElement("tbody");


  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
    
  }
  
  console.log(tableau.rows.length);
  // Contacte de l'API PHP
  const url='API_PHP/API_Select_Liste_Paie.php?code_promo='+code_promo+'&Id_annee_acad='+idAnnee_Acad;  
  var i=1;

    fetch(url) 
    .then(response => response.json())
    .then(data1 => 
    {
      data1.forEach(infos =>
        {
          
              var tr = document.createElement("tr");

            
              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdmontant = document.createElement("td");
              var tdlibele = document.createElement("td");
              var tdtranche = document.createElement("td");
              var tdfixe = document.createElement("td");
             
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom_étudiant
              tdlibele.textContent=infos.motif;
              tdmontant.textContent=infos.Montant;
              tdtranche.textContent=infos.Tranche;
              tdfixe.textContent=infos.Argent_fixé;
            

              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdlibele);
              tr.appendChild(tdmontant);
              tr.appendChild(tdtranche);
              tr.appendChild(tdfixe);
              

              
              
              
              tbody.appendChild(tr);

              
              i++;
            //}  
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);

        });
        
          tableau.appendChild(tbody);

}