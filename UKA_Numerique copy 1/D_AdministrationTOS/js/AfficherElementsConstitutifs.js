
var spans = document.querySelectorAll('span');
console.log("je suis au debut");

// Parcourir tous les <span> et ajouter un écouteur d'événements pour chaque
spans.forEach(function(span) {
  span.addEventListener('window.onload', function() {
    
   console.log("Nous sommes dans sur la page EC");
   //console.log("clic son ID est "+this.id);
  //  if(this.id=="Spanlibelle") Affichage_Semestre_Promo(span.innerText);
   
  });
});



/*function Affichage_Semestre_Promo(libellepromo)
{
    var idpromotion=document.getElementById("promo").value;
    var lib_abrev_promo=document.getElementById("lib_abrev_promo").value;
 
    var libelle_promotion=libellepromo;

    var divtab=document.getElementById('DivTab').classList.add("card-body", "table-responsive-lg","visible");


   //console.log("regarde "+lib_abrev_promo);
   //console.log("la promo est :"+libelle_promotion);

   let TabSemestre = document.getElementById("TabSemestre");

    while (TabSemestre.firstChild) {
      TabSemestre.removeChild(TabSemestre.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
  
      

    td1.textContent = "N°";
    td2.textContent = "Libellé semestre";
    td3.textContent = "Niveau";
    td4.textContent = "";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);

      
    thead.appendChild(tr1);
    TabSemestre.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    

    var url="APISemestre.php?codepromo="+idpromotion+"&lib_abrev_promo="+lib_abrev_promo;
      //console.log('le code envoyé est :'+idpromotion);  
    var i=1;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
              var tr = document.createElement("tr");

              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdlibelle= document.createElement("td");
              var tdniveau = document.createElement("td");
              var tdbtn = document.createElement("td");
              
              tdlibelle.textContent =infos.libelle_semestre;
              tdniveau.textContent=infos.Niveau_semestre
             //bouton
             var btn=document.createElement('input');
              btn.setAttribute('type','button');
              btn.setAttribute('class','btn btn-primary float-end');
              btn.setAttribute('value','Ajouter UE');

              tdbtn.appendChild(btn);
              btn.addEventListener("click", function() 
              {
                //données envoyées lors du clic sur le bouton
                Btn_Ajout_UE(infos.Id_Semestre,"UE",idpromotion,libelle_promotion);
                console.log("le code promotion est"+idpromotion);
              });

             tr.appendChild(tdnum);
              tr.appendChild(tdlibelle);
              tr.appendChild(tdniveau);
              tr.appendChild(tdbtn);
 
              tbody.appendChild(tr);
              i++;
              console.log(" semestre "+infos.libelle_semestre);
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte de l'API "+error);});
          TabSemestre.appendChild(tbody);
          function Btn_Ajout_UE(Id_Semestre, page, Code_Promo, libelle)
{
  
  //var url='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre;
  window.location.href='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre+'&Code_Promotion='+Code_Promo+'&Libellepromo='+libelle;
  console.log('nous sommes dans la fonction btn ajout ue');  
}
                 
}*/
