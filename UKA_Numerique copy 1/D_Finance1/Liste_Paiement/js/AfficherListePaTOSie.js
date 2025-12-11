console.log('nous sommes dans le js afficher liste paie des frais');  

const prom=document.getElementById("promotion");
const idAnnee_Ac=document.getElementById("annee");
var MiSession = document.getElementById("MiSession");
var GdeSession = document.getElementById("GdeSession");
var d4 = document.getElementById("d4");

prom.addEventListener('change',(event) => {
    var idAnnee_Acad=annee.value;
    var code_promo=prom.value;
    console.log("la valeur de promotion est :::: "+code_promo)
    console.log("la valeur de annee est :::: "+idAnnee_Acad)
    AfficherListePaiement(code_promo,idAnnee_Acad);
    AfficherFAFixés(code_promo,idAnnee_Acad);
  
  });
  var info="";
  function AfficherFAFixés(code_prom,idAnnee_Ac)
  {
    var lien='Liste_Paiement/API/APISelect_FA_Fixe.php?code_promo='+code_prom+'&Id_annee_acad='+idAnnee_Ac;  

    //console.log('le code envoyé est :'+idpromotion);  
 
    fetch(lien) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(info =>
        {
          if(info.Libelle_Frais=="Frais Académiques")
          {
            document.getElementById("FA").innerText=info.Montant;
            document.getElementById("tranche").innerText=info.Tranche;

          }
            
            else document.getElementById("Enrolement").innerText=info.Montant;
          
        });
    }
    ).catch(error => {
      // Traitez l'erreur ici
      console.log("Erreur lor de contacte de l'API Afficher le FA Fixé"+error);});

  }
  function AfficherListePaiement(code_prom,idAnnee_Ac)
{


   let TabListe = document.getElementById("TabListePaie");

    while (TabListe.firstChild) {
      TabListe.removeChild(TabListe.firstChild);
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
    var td9 = document.createElement("td");
    var td10 = document.createElement("td");
  
    td1.textContent = "N°";
    td2.textContent = "Matr";
    td3.textContent = "Nom";
    td4.textContent = "Postnom";
    td5.textContent = "Prenom";
    td6.textContent = "Montant FA";
    td7.textContent = "Enrol Miss";
    td8.textContent = "Enrol Gde Sess";
    td9.textContent = "Enrol 2e Sess";
    td10.textContent = "Observation";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);
    tr1.appendChild(td7);
    tr1.appendChild(td8);
    tr1.appendChild(td9);
    tr1.appendChild(td10);

      
    thead.appendChild(tr1);
    TabListe.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    

    var url='Liste_Paiement/API/API_Select_Liste_Paie.php?code_promo='+code_prom+'&Id_annee_acad='+idAnnee_Ac;  

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

              var tdmatricule= document.createElement("td");
              var tdnom= document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprenom = document.createElement("td");
              var tdmontantFa = document.createElement("td");
              var tdEnrolMiss = document.createElement("td");
              var tdEnrolGdSess = document.createElement("td");
              var tdEnrol2eSess = document.createElement("td");
              var tdObs = document.createElement("td");
              
              tdmatricule.textContent =infos.Matricule;
              tdnom.textContent =infos.Nom;
              tdpostnom.textContent=infos.Postnom;
              tdprenom.textContent=infos.Prenom;
              tdmontantFa.textContent=infos.Montant;
              tdEnrolMiss.textContent=infos.Enrol_S_1;
              tdEnrolGdSess.textContent=infos.Enrol_S_2;
              tdEnrol2eSess.textContent=infos.Enrol_S_3;
              //Gestion des observation en ordre et pas en ordre
              var montanpaye=0;
              var enrolement=0;
            
              console.log("le montant est "+montanpaye);
              var session="";
           //.............................................
              if (MiSession.checked) {session="Mi-Session"; }
              else if (GdeSession.checked)  {session="Gde-Session";}
              else if (d4.checked)  {session="D4";}
              else  { session="Deuxième-Session";}
              
             
              if(session="Mi-Session" && MiSession.checked)
                {
                  enrolement=document.getElementById("Enrolement").innerText;
                  montanpaye= parseFloat(document.getElementById("tranche").innerText);
                  if (infos.Montant >= montanpaye && infos.Enrol_S_1 == enrolement)
                    {
                      tdObs.textContent="En ordre";
                    } 
                  else
                  {
                    tdObs.textContent="Pas en ordre";
                  } 
                }
              else if(session="D4" && d4.checked)
                  {
                    enrolement=document.getElementById("Enrolement").innerText;
                    montanpaye= parseFloat(document.getElementById("tranche").innerText);
                    if (infos.Montant >= montanpaye && infos.Enrol_S_1 == enrolement)
                      {
                        tdObs.textContent="En ordre";
                      } 
                    else
                    {
                      tdObs.textContent="Pas en ordre";
                    } 
                  }
              else if (session="Gde-Session" && GdeSession.checked)
                {
                  enrolement=document.getElementById("Enrolement").innerText;
                  montanpaye= parseFloat(document.getElementById("FA").innerText);
                  if (infos.Montant >= montanpaye && infos.Enrol_S_1 == enrolement && infos.Enrol_S_2 == enrolement)
                    {
                      tdObs.textContent="En ordre";
                    } 
                  else
                  {
                    tdObs.textContent="Pas en ordre";
                  } 
                }
              else if (session="Deuxième-Session" && DeuxièmeSession.checked)
                { 
                  enrolement=document.getElementById("Enrolement").innerText;
                  montanpaye= parseFloat(document.getElementById("FA").innerText);
                    if  (infos.Montant >= montanpaye && infos.Enrol_S_1 == enrolement && infos.Enrol_S_2 == enrolement && infos.Enrol_S_3 == enrolement)
                      {
                        tdObs.textContent="En ordre";
                      } 
                    else
                    {
                      tdObs.textContent="Pas en ordre";
                    } 
                }
                 






             tr.appendChild(tdnum);
              tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprenom);
              tr.appendChild(tdmontantFa);
              tr.appendChild(tdEnrolMiss);
              tr.appendChild(tdEnrolGdSess);
              tr.appendChild(tdEnrol2eSess);
              tr.appendChild(tdObs);
 
              tbody.appendChild(tr);
              i++;
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de contacte de l'API Afficher Liste Paie"+error);});
          TabListe.appendChild(tbody);
{
  
  //var url='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre;
 // window.location.href='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre+'&Code_Promotion='+Code_Promo+'&Libellepromo='+libelle;
}
                 
}