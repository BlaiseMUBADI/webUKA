
  function Affichage_etudiant()
  {

    /*console.log(" Je suis dans la methode affichage etudiant code promo = "+
    +code_promo+" id_annee_academiqe = "+Id_an_acad);*/
    
    var cmb_promotion=document.getElementById("promo");
    var cmb_annee_academique=document.getElementById("Id_an_acad");

    var code_promo=cmb_promotion.value;
    var Id_an_acad=cmb_annee_academique.value;

    console.log("code promo = "
    +code_promo+" id_annee_academiqe = "+Id_an_acad);


      
    var tableau = document.querySelector("table");

    while (tableau .firstChild) {
      tableau .removeChild(tableau .firstChild);
    }

    
    /*var tbody = tableau.getElementsByTagName("tbody")[0];
      while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
      }*/
      var thead = document.createElement("thead");
      thead.style="sticky-sm-top m-0";

      var tr1 = document.createElement("tr");
      tr1.style="background-color:blue;"

      var td1 = document.createElement("td");      
      var td2 = document.createElement("td");
      var td3 = document.createElement("td");
      var td4 = document.createElement("td");
      var td5 = document.createElement("td");
      var td6 = document.createElement("td");
      

      td1.textContent = "N°";
      td2.textContent = "Matricule";
      td3.textContent = "Nom";
      td4.textContent = "Postnom";
      td5.textContent = "Prenom";
      td6.textContent = "Sexe";

      tr1.appendChild(td1);
      tr1.appendChild(td2);
      tr1.appendChild(td3);
      tr1.appendChild(td4);
      tr1.appendChild(td5);
      tr1.appendChild(td6);

      
      thead.appendChild(tr1);
      tableau.appendChild(thead);
      
      var tbody = document.createElement("tbody");

      //var tbody = tableau.getElementsByTagName("tbody")[0];

        // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
      var url='JavaScript/liste_etudiant.php?Id_annee_acad='+Id_an_acad+'&code_promo='+code_promo;
         
      
        var i=1;
        fetch(url) 
        .then(response =>{
                  if (!response.ok) {
                     throw new Error("La réponse n'est pas OK");
                     console.log( " nous avons une erreur");
                  }
                  else
                  {
                    console.log( " nous an'avons une");
                  }
                  return response.json();
                   })


        .then(data => {
          data.forEach(infos => 
            {

              /*if (Array.isArray(data) && data.length === 0) {
                console.log('Le tableau est vide');
              } else {
                console.log('Le tableau n\'est pas vide');
              
              console.log(" La fonction bouge ");*/
    

              // Création de TR
              var tr = document.createElement("tr");


              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              var tdsexe = document.createElement("td");
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
             
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              
              
              
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la lign appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {
                Enoi_dans_dive_coter(infos);
                
              });

              
              
              
            }
            
          );
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tableau.appendChild(tbody);

        
      }