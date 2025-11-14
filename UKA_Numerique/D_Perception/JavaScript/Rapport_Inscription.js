


console.log("nous sommes dans rapport inscription"); 

const filier=document.getElementById("filiereInscription");
const promo=document.getElementById("promoInscription");
const annnee_ac=document.getElementById("Id_an_acadInscription");
const date=document.getElementById("datepaie");


document.getElementById('promoInscription').addEventListener('change', function() 
{
    var promo_filier=promo.value;
    var annee_acad=annnee_ac.value;
    var datepaie=date.value;
   

    
    var tableau = document.getElementById("table_paiement_inscription");

    while (tableau .firstChild) {
      tableau .removeChild(tableau .firstChild);
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
    var td6 = document.createElement("td");
    var td7 = document.createElement("td");
    var td8 = document.createElement("td");
      

    td1.textContent = "N°";
    td2.textContent = "Matricule";
    td3.textContent = "Nom";
    td4.textContent = "Postnom";
    td5.textContent = "Prenom";
    td6.textContent = "Sexe";
    td7.textContent = "Montant";
    td8.textContent = "Libellé";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);
    tr1.appendChild(td7);
    tr1.appendChild(td8);

      
    thead.appendChild(tr1);
    tableau.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
    var url='D_Perception/API_PHP/API_Rapport_Inscription.php?datepaie='+datepaie+'&promo='+promo_filier+'&annee_acad='+annee_acad;

        
    var i=1;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
        let TotalGen=0;
      data.forEach(infos =>
        {
          // Création de TR
              var tr = document.createElement("tr");
             


              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdmatricule= document.createElement("td");
              var tdnom = document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprnom = document.createElement("td");
              var tdsexe = document.createElement("td");
              var tdmontant = document.createElement("td");
              var tdlibelle = document.createElement("td");
              

              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent=infos.Nom
              tdpostnom.textContent=infos.Postnom;
              tdprnom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
              tdmontant.textContent=infos.Montant;
              tdlibelle.textContent=infos.libelle;
                TotalGen+=infos.Montant;
              
              tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprnom);
              tr.appendChild(tdsexe);
              tr.appendChild(tdmontant);
              tr.appendChild(tdlibelle);
              
              
              tbody.appendChild(tr);
              i++;
     
        });

        var trTotal = document.createElement("tr");
        trTotal.style="background-color:black; color:white;"

        var tdnu = document.createElement("td");
        var tdno= document.createElement("td");
        var tdpostno = document.createElement("td");
        var tdpreno = document.createElement("td");
        var tdTotalGen = document.createElement("td");
        var tdEnrolGen = document.createElement("td");
        var tdmontantF = document.createElement("td");

        tdnu.textContent = "";
        tdno.textContent ="";
        tdpostno.textContent="";
        tdpreno.textContent="";
        tdTotalGen.textContent=" ";
        tdEnrolGen.textContent="Total Général";
        tdmontantF.textContent=TotalGen+" $";

        trTotal.appendChild(tdnu);
        trTotal.appendChild(tdno);
        trTotal.appendChild(tdpostno);
        trTotal.appendChild(tdpreno);
        trTotal.appendChild(tdTotalGen);
        trTotal.appendChild(tdEnrolGen);
        trTotal.appendChild(tdmontantF);

        tbody.appendChild(trTotal);

          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tableau.appendChild(tbody);
});





