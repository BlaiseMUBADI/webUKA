
var spans = document.querySelectorAll('span');
console.log("je suis au debut");

// Parcourir tous les <span> et ajouter un écouteur d'événements pour chaque
spans.forEach(function(span) {
  span.addEventListener('click', function() {
    
   console.log("clic sur libellé"+span.innerText);
   console.log("clic son ID est "+this.id);
    if(this.id=="Spanlibelle") Affichage_Semestre_Promo(span.innerText);
   
  });
});



function Affichage_Semestre_Promo(libellepromo)
{
    var idpromotion=document.getElementById("promo").value;
 
    var motif=libellepromo;

    var divtab=document.getElementById('DivTable').classList.add("card-body", "table-responsive-lg","visible");


   console.log("code promo "+idpromotion);
   console.log("promo est :"+motif);

   let TabUE = document.getElementById("TabUE");

    while (TabUE.firstChild) {
      TabUE.removeChild(TabUE.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
  
      

    td1.textContent = "N°";
    td2.textContent = "Code_UE";
    td3.textContent = "Intitulé UE";
    td4.textContent = "Semestre";
    td5.textContent = "";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);

      
    thead.appendChild(tr1);
    TabUE.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    

    var url='Afficher_UE_Promo_Sem.php?codepromo='+idpromotion;
      console.log('le code envoyé est :'+idpromotion);  
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

              var tdcodeue= document.createElement("td");
              var tdintitule = document.createElement("td");
              var tdsemestre = document.createElement("td");
              var tdbtn = document.createElement("td");
              
              tdcodeue.textContent =infos.Code_ue;
              tdintitule.textContent=infos.Intitule_ue
              tdsemestre.textContent=infos.libelle_semestre
             //bouton
             var btn=document.createElement('input');
              btn.setAttribute('type','button');
              btn.setAttribute('class','btn btn-primary float-end');
              btn.setAttribute('value','Ajouter UE');

              tdbtn.appendChild(btn);
              btn.addEventListener("click", function() {
                Btn_Ajout_UE(infos.Id_Semestre,"UE",infos.Code_Promotion);
              });



        
              tr.appendChild(tdnum);
              tr.appendChild(tdcodeue);
              tr.appendChild(tdintitule);
              tr.appendChild(tdbtn);
              
  
              tbody.appendChild(tr);
              i++;

    

              
              console.log(" semestre "+infos.libelle_semestre);
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte de l'API "+error);});
          TabUE.appendChild(tbody);
                 
}
function Btn_Ajout_UE(Id_Semestre, page, Code_Promo)
{
  var url='Afficher_UE_Promo_Sem.php?codepromo='+idpromotion;
  console.log('le code envoyé est :'+idpromotion);  

fetch(url) 
  //var url='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre;
  window.location.href='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre+'&Code_Promotion='+Code_Promo;
  console.log('nous sommes dans la fonction btn ajout ue');  
}