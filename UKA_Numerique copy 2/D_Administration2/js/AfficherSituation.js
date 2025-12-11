
var spans = document.querySelectorAll('span');
console.log("je suis au debut");

// Parcourir tous les <span> et ajouter un écouteur d'événements pour chaque
spans.forEach(function(span) {
  span.addEventListener('click', function() {
    
    console.log("clic sur span"+span.innerText);
    console.log("clic son ID est "+this.id);
    if(this.id==="FraisPayé") Affichage_Situation(span.innerText);
   
  });
});



function Affichage_Situation(motifpaie)
{
    var matricule=document.getElementById("Matricule").value;
    var idannee=document.getElementById("IdAnnee").value;
    var motif=motifpaie;

    var divtab=document.getElementById('DivTab').classList.add("card-body", "table-responsive-lg","visible");


   console.log("mat "+matricule+" motif "+motif);

   let tab_situationpaie = document.getElementById("TabSituation");

    while (tab_situationpaie.firstChild) {
      tab_situationpaie.removeChild(tab_situationpaie.firstChild);
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
    td2.textContent = "Date Opération";
    td3.textContent = "Motif";
    td4.textContent = "Montant";
    td5.textContent = "Lieu";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);

      
    thead.appendChild(tr1);
    tab_situationpaie.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    

    var url='APISituation.php?IdAnne='+idannee+'&Mat='+matricule+'&Motif='+motif;
        
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

              var tddate= document.createElement("td");
              var tdmotif = document.createElement("td");
              var tdmontant = document.createElement("td");
              var tdlieu = document.createElement("td");

              tddate.textContent =infos.Date_paie;
              tdmotif.textContent=infos.Motif_paie
              tdmontant.textContent=infos.Montant_paie+" $";
              tdlieu.textContent=infos.Libelle_lieu;
        
              tr.appendChild(tdnum);
              tr.appendChild(tddate);
              tr.appendChild(tdmotif);
              tr.appendChild(tdmontant);
              tr.appendChild(tdlieu);
  
              tbody.appendChild(tr);
              i++;

    

              
              console.log(" mont "+infos.Montant_paie);
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tab_situationpaie.appendChild(tbody);
                 
}