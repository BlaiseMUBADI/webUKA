
console.log('nous sommes dans le js afficher liste etudiant');  
const prom=document.getElementById("promotion");
const idAnnee_Ac=document.getElementById("annee");
const idfiliere=document.getElementById("filiere");

//recupération de l'indentifiant de la faculté
var idFiliere="";
idfiliere.addEventListener('change',(event) => {
  idFiliere=idfiliere.value;


  console.log('iD Filière est :'+idFiliere);

});
//recuperation de la promotion selection pour recupération de la mention ou option


var Option="";
prom.addEventListener('change',(event) => {
    var idAnnee_Acad=annee.value;
    var code_promo=prom.value;
  const optionSelectionnee = prom.options[prom.selectedIndex];
  Option = optionSelectionnee.textContent;

    console.log("la valeur de promotion est :::: "+code_promo)
    console.log("la valeur de annee est :::: "+idAnnee_Acad)
    console.log('la mentions selectionnée est :'+Option.substring(9));


    Affichage_Liste_Etudiant(code_promo,idAnnee_Acad);
  
  });

function Affichage_Liste_Etudiant(code_prom,idAnnee_Ac)
{


   let TabListe = document.getElementById("tableListe");

    while (TabListe.firstChild) {
      TabListe.removeChild(TabListe.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    //var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
    var td6 = document.createElement("td");
  
    td1.textContent = "N°";
    //td2.textContent = "Matricule";
    td3.textContent = "Nom";
    td4.textContent = "Postnom";
    td5.textContent = "Prenom";
    td6.textContent = "Sexe";

    tr1.appendChild(td1);
    //tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);

      
    thead.appendChild(tr1);
    TabListe.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    

    var url='API_PHP/APISelectListeEtud.php?code_pro='+code_prom+'&Id_annee='+idAnnee_Ac;  

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

              //var tdmatricule= document.createElement("td");
              var tdnom= document.createElement("td");
              var tdpostnom = document.createElement("td");
              var tdprenom = document.createElement("td");
              var tdsexe = document.createElement("td");
              var tdbtn = document.createElement("td");
              
              //tdmatricule.textContent =infos.Matricule;
              tdnom.textContent =infos.Nom;
              tdpostnom.textContent=infos.Postnom;
              tdprenom.textContent=infos.Prenom;
              tdsexe.textContent=infos.Sexe;
             tr.appendChild(tdnum);
             // tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprenom);
              tr.appendChild(tdsexe);
 
              tbody.appendChild(tr);
              i++;
            

              //selection ligne
              tr.addEventListener("click", function() 
              {
                // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
  
                var rows = TabListe.getElementsByTagName('tr');  
                for(var j = 0; j < rows.length; j++)
                {
                  if(j!=0) rows[j].style.backgroundColor = '';
                }
                tr.style.backgroundColor = 'red';
                // date signature fiche
                const option={year:'numeric', month:'long',day:'numeric'};
                const date = new Date();
                const today = date.toLocaleDateString('fr-FR',option);

                //données envoyées lors du clic sur le bouton
            
             
                console.log("le matricule de l'etudiant est-------"+infos.Matricule);
                document.getElementById("nom").innerText=infos.Nom+" "+infos.Postnom+" "+infos.Prenom;
                document.getElementById("name").innerText=infos.Nom+" "+infos.Postnom+" "+infos.Prenom;
                document.getElementById("lieu").innerText=infos.LieuNaissance+",  le  "+infos.DateNaissance;
                document.getElementById("sexe").innerText=infos.Sexe;
                document.getElementById("nationalite").innerText=infos.Nationalite;
                document.getElementById("Etat").innerText=infos.EtatCiv;
                document.getElementById("pere").innerText=infos.NomPere;
                document.getElementById("mere").innerText=infos.NomMere;
                document.getElementById("provorg").innerText=infos.ProvinceOrigine;
                document.getElementById("territ").innerText=infos.Territoire;
                document.getElementById("adresse").innerText=infos.AdresseActuelle;
                document.getElementById("contact").innerText=infos.TelResponsable;
                document.getElementById("num").innerText=infos.NumDiplom;
                document.getElementById("pourc").innerText=infos.PourceDiplome;
                document.getElementById("option").innerText=infos.OptionEtude;
                document.getElementById("section").innerText=infos.SetionEtude;
                document.getElementById("lieudeliv").innerText=infos.Lieudelivrance;
                document.getElementById("lieudeliv").innerText=infos.Datedelivrance;
                document.getElementById("ecole").innerText=infos.Ecole;
                document.getElementById("prov").innerText=infos.Province;
                document.getElementById("datesigne").innerText="Fait à Kananga, le "+today+"           ";
                document.getElementById("acad").innerText="Secrétaire Général Académique ";
                document.getElementById("academ").innerText="Professeur Martin BAYAMBA Kasonga ";

                var MatEtudiant=infos.Matricule;
               
                Affichage_Imprimer(MatEtudiant);
                
                Affichage_Cursus(MatEtudiant);

                console.log('Click');

                //La fonction qui affiche et imprime le cursus académique de l'Etudiant
                                
               
              });
        });
        
      }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte de l'API "+error);});
          
          TabListe.appendChild(tbody);
      
        /*  function Btn_Ajout_UE(Id_Semestre, page, Code_Promo, libelle)
{
  
  //var url='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre;
 // window.location.href='Principal.php?page='+page+'&Id_Semestre='+Id_Semestre+'&Code_Promotion='+Code_Promo+'&Libellepromo='+libelle;
}*/
                 
}
var idannee="";
var codepromo="";
function Affichage_Cursus(MatEtudiant)
{
  console.log('click2');         
                
                   let TabFiche = document.getElementById("tableFiche");
                
                    while (TabFiche.firstChild) {
                      TabFiche.removeChild(TabFiche.firstChild);
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

                    //var td7 = document.createElement("td");
                    var systeme1 = document.getElementById("LMD");
                    if (systeme1.checked)
                      {
                        td1.textContent = "Année Ac";
                        td2.textContent = "Promotion";
                        td3.textContent = "Moy S1";
                        td4.textContent = "Mention";
                        td5.textContent = "Moy Ratt";
                        td6.textContent = "Mention";
                        //td7.textContent = "Décision du Jury";
                      }
                      else
                      {
                        td1.textContent = "Année Ac";
                        td2.textContent = "Promotion";
                        td3.textContent = "Pourc S1";
                        td4.textContent = "Mention";
                        td5.textContent = "Pourc S2";
                        td6.textContent = "Mention";
                      }
                    tr1.appendChild(td1);
                    tr1.appendChild(td2);
                    tr1.appendChild(td3);
                    tr1.appendChild(td4);
                    tr1.appendChild(td5);
                    tr1.appendChild(td6);
                    //tr1.appendChild(td7);
                
                      
                    thead.appendChild(tr1);
                    TabFiche.appendChild(thead);
                      
                    var tbody = document.createElement("tbody");
                    
                
                 
                    var lien='API_PHP/APISelect_Cursus.php?matEtud='+MatEtudiant+'&IdFil='+idFiliere;  
                    console.log('la filiere choisi est'+idFiliere);
                    var i=1;
                    fetch(lien) 
                    .then(response => response.json())
                    .then(data => 
                    {
                      data.forEach(infos =>
                        {
                          // Création de TR
                              var tr = document.createElement("tr");
                
                              //var tdnum = document.createElement("td");
                              //tdnum.textContent = i;
                
                              var tdanne= document.createElement("td");
                              var tdpromo= document.createElement("td");
                              var tdmoys1 = document.createElement("td");
                              var tdmention1 = document.createElement("td");
                              var tdmoys2 = document.createElement("td");
                              var tdtdmention2 = document.createElement("td");
                              //var tddecision = document.createElement("td");
                              var tdbtn = document.createElement("td");
                              
                              tdanne.style.whiteSpace = 'nowrap';
                              tdanne.textContent = infos.Annee_debut + "-" + infos.Annee_fin;
                              tdpromo.textContent =infos.Abréviation;
                              tdmoys1.textContent=infos.Session1;
                              tdmention1.textContent=infos.Mention1;
                              tdmoys2.textContent=infos.Session2;
                              tdtdmention2.textContent=infos.Mention2;
                              //tddecision.textContent=infos.Decision_jury;
                              var btn=document.createElement('input');
                              btn.setAttribute('type','button');
                              //btn.setAttribute('class','float-end');
                              
                              btn.setAttribute('value','Del');
                
                              tdbtn.appendChild(btn);
                              btn.addEventListener("click", function() 
                              {
                                SuppressionCursus(MatEtudiant,infos.idAnnee_Acad,infos.Code_Promotion);
                                Affichage_Imprimer(MatEtudiant);
                                //Affichage_Cursus(MatEtudiant);
                                

                              });
                             // tr.appendChild(tdnum);
                              tr.appendChild(tdanne);
                              tr.appendChild(tdpromo);
                              tr.appendChild(tdmoys1);
                              tr.appendChild(tdmention1);
                              tr.appendChild(tdmoys2);
                              tr.appendChild(tdtdmention2);
                              tr.appendChild(tdbtn);
                              //tr.appendChild(tddecision);
                 
                              tbody.appendChild(tr);
                              //i++;
                            });

                            
                var Btn = document.getElementById('btn'); Btn.style.display='block';
                             
                       }).catch(error => {
                        // Traitez l'erreur ici
                        console.log("Erreur lor de contacte de l'API "+error);});
                        TabFiche.appendChild(tbody);
                    
                       
           

}
//le tableau à imprimer
function Affichage_Imprimer(MatEtudiant)
{
                
                
                   let TabImprimer = document.getElementById("tableImprimer");
                
                    while (TabImprimer.firstChild) {
                      TabImprimer.removeChild(TabImprimer.firstChild);
                    }
                    var thead = document.createElement("thead");
                    thead.classList.add("sticky-sm-top","m-0","fw-bold"); // Pour ajouter la classe à un element HTMl
                
                    var tr1 = document.createElement("tr");
                    tr1.style="border:2px solid black; color:black";
                    tr1.style.fontSize="15px";

                    var systeme1 = document.getElementById("LMD");
              if (systeme1.checked)
                {
                       
                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");
                    var td3 = document.createElement("td");
                    var td4 = document.createElement("td");
                    var td5 = document.createElement("td");
                    var td6 = document.createElement("td");
                    //var td7 = document.createElement("td");
                    td1.textContent = "Année";
                    td2.textContent = "Etab.";
                    td3.textContent = "Faculté";
                    td4.textContent = "Option";
                    td5.textContent = "Promotion";
                    td6.textContent = "RESULTATS";
                    td6.style.textAlign = "center";

                    td1.style="border:2px solid black; color:black;"
                    td2.style="border:2px solid black; color:black;"
                    td3.style="border:2px solid black; color:black;"
                    td4.style="border:2px solid black; color:black;"
                    td5.style="border:2px solid black; color:black;"
                    td6.style="border:2px solid black; color:black;"

                    //fusion
                    td6.setAttribute("colspan", "5");
                    td1.setAttribute("rowspan", "3");
                    td2.setAttribute("rowspan", "3");
                    td3.setAttribute("rowspan", "3");
                    td4.setAttribute("rowspan", "3");
                    td5.setAttribute("rowspan", "3");

                    td1.style.textAlign ="Center";
                    
                    td3.style.textAlign ="Center";
                    td4.style.textAlign ="Center";
                    td6.style.textAlign ="Center";
              

  
                
                    tr1.appendChild(td1);
                    tr1.appendChild(td2);
                    tr1.appendChild(td3);
                    tr1.appendChild(td4);
                    tr1.appendChild(td5);
                    tr1.appendChild(td6);
                  
                  
                    
                    //Création de la deuxième ligne
                    var tr2 = document.createElement("tr");
                    tr2.style="border:2px solid black; color:black;"
                    tr2.style.fontSize="15px";

                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");
                    var td3 = document.createElement("td");

                    td1.textContent = "1ère Session";
                    td1.setAttribute("colspan", "2");
                   
                    td2.textContent = "Rattrapage";
                    td2.setAttribute("colspan", "2");
                    td3.textContent = "Annuelle";
                    
                    td1.style="border:2px solid black; color:black;"
                    td2.style="border:2px solid black; color:black;"
                    td1.style.textAlign ="Center";
                    td2.style.textAlign ="Center";
                    //var systeme1 = document.getElementById("PADEM");
                    tr2.appendChild(td1);
                    tr2.appendChild(td2);
                    tr2.appendChild(td3);
                 
                  //troisième ligne
                    var tr3 = document.createElement("tr");
                    tr3.style="border:2px solid black; color:black;"
                    tr3.style.fontSize="15px";

                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");
                    var td3 = document.createElement("td");
                    var td4 = document.createElement("td");
                    var td5 = document.createElement("td");

                      td1.textContent = "Moy";
                      td2.textContent = "Mention";
                      td3.textContent = "Moy";
                      td4.textContent = "Mention";
                      td5.textContent = "Décision";
                    
                      td1.style="border:2px solid black; color:black;"
                      td2.style="border:2px solid black; color:black;"
                      td3.style="border:2px solid black; color:black;"
                      td4.style="border:2px solid black; color:black;"
                      td5.style="border:2px solid black; color:black;"
                    tr3.appendChild(td1);
                    tr3.appendChild(td2);
                    tr3.appendChild(td3);
                    tr3.appendChild(td4);
                    tr3.appendChild(td5);

                    thead.appendChild(tr1);
                    thead.appendChild(tr2);
                    thead.appendChild(tr3);
                  }
                  else{
                    //tr1.style="background-color:midnightblue; color:black;"
                
                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");
                    var td3 = document.createElement("td");
                    var td4 = document.createElement("td");
                    var td5 = document.createElement("td");
                    var td6 = document.createElement("td");
                    //var td7 = document.createElement("td");
                    td1.textContent = "Année";
                    td2.textContent = "Etab.";
                    td3.textContent = "Faculté";
                    td4.textContent = "Option";
                    td5.textContent = "Promotion";
                    td6.textContent = "RESULTATS";
                    td6.style.textAlign = "center";

                    td1.style.textAlign = "center";
                    td2.style.textAlign = "center";
                    td3.style.textAlign = "center";
                    td4.style.textAlign = "center";
                    td6.style.textAlign = "center";
                    td5.style.textAlign = "center";


                    //fusion
                    td6.setAttribute("colspan", "4");
                    td1.setAttribute("rowspan", "3");
                    td2.setAttribute("rowspan", "3");
                    td3.setAttribute("rowspan", "3");
                    td4.setAttribute("rowspan", "3");
                    td5.setAttribute("rowspan", "3");

                    td1.style="border:2px solid black; color:black;"
                    td2.style="border:2px solid black; color:black;"
                    td3.style="border:2px solid black; color:black;"
                    td4.style="border:2px solid black; color:black;"
                    td5.style="border:2px solid black; color:black;"
                    td6.style="border:2px solid black; color:black;"

                    tr1.appendChild(td1);
                    tr1.appendChild(td2);
                    tr1.appendChild(td3);
                    tr1.appendChild(td4);
                    tr1.appendChild(td5);
                    tr1.appendChild(td6);
                    
                    //Création de la deuxième ligne
                    var tr2 = document.createElement("tr");
                    tr2.style.fontSize="15px";

                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");

                    td1.textContent = "1ère Session";
                    td1.setAttribute("colspan", "2");
                   
                    td2.textContent = "2ère Session";
                    td2.setAttribute("colspan", "2");
                    
                    td1.style.textAlign = "center";
                    td2.style.textAlign = "center";

                    td1.style="border:2px solid black; color:black;"
                    td2.style="border:2px solid black; color:black;"
                    tr2.appendChild(td1);
                    tr2.appendChild(td2);
                  
                    //troisième ligne
                    var tr3 = document.createElement("tr");

                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");
                    var td3 = document.createElement("td");
                    var td4 = document.createElement("td");
                    td1.textContent = "Pourc";
                    td2.textContent = "Mention";
                   
                    td3.textContent = "Pourc";
                    td4.textContent = "Mention";

                    td1.style.textAlign="center";
                    td2.style.textAlign="center";
                    td3.style.textAlign="center";
                    td4.style.textAlign="center";


                    td1.style="border:2px solid black; color:black;"
                    td2.style="border:2px solid black; color:black;"
                    td3.style="border:2px solid black; color:black;"
                    td4.style="border:2px solid black; color:black;"

                    tr3.appendChild(td1);
                    tr3.appendChild(td2);
                    tr3.appendChild(td3);
                    tr3.appendChild(td4);

                    thead.appendChild(tr1);
                    thead.appendChild(tr2);
                    thead.appendChild(tr3);
                  }

                    TabImprimer.appendChild(thead);
                      
                    var tbody = document.createElement("tbody");
                    tbody.classList.add("font-sm");
                
                    var lien='API_PHP/APISelect_Cursus.php?matEtud='+MatEtudiant+'&IdFil='+idFiliere;  
                
                    var i=1;
                    fetch(lien) 
                    .then(response => response.json())
                    .then(data => 
                    {
                      data.forEach(infos =>
                        {
                          // Création de TR
                              var tr = document.createElement("tr");
                              tr.style.fontSize="15px";
                              var systeme1 = document.getElementById("LMD");
                             
                              
                    if (systeme1.checked)
                      {
                            
                                var tdanne= document.createElement("td");
                                var tdEtab= document.createElement("td");
                                var tdFac= document.createElement("td");
                                var tdOption= document.createElement("td");
                                var tdpromo= document.createElement("td");
                                var tdmoys1 = document.createElement("td");
                                var tdmention1 = document.createElement("td");
                                var tdmoys2 = document.createElement("td");
                                var tdtdmention2 = document.createElement("td");
                                var tddecision = document.createElement("td");

                                
                                tdanne.innerHTML = "<nobr>" + infos.Annee_debut + " - " + infos.Annee_fin + "</nobr>";

                                tdanne.style="border:2px solid black; color:black;"
                                tdEtab.style="border:2px solid black; color:black;"
                                tdFac.style="border:2px solid black; color:black;"
                                tdOption.style="border:2px solid black; color:black;"
                                tdpromo.style="border:2px solid black; color:black;"
                                tdmoys1.style="border:2px solid black; color:black;"
                                tdmention1.style="border:2px solid black; color:black;"
                                tdmoys2.style="border:2px solid black; color:black;"
                                tdtdmention2.style="border:2px solid black; color:black;"
                                tddecision.style="border:2px solid black; color:black;"

                              tdEtab.textContent="U.KA.";
                              var fac=infos.Libelle_Filiere;
                              if(fac=="Sciences Economiques et de Gestion")
                                {
                                  fac="Economie";
                                }
                                else if(fac=="Sciences Informatiques")
                                  {fac="Informatique"; }
                                else if(fac=="Architecture et Construction")
                                  {fac="Architecture"; }
                                else if(fac=="Culture et Communication")
                                  {fac="Communication"; }
                                else if(fac=="	Agronomie et Environnement")
                                  {fac="Agronomie"; }
                                else fac=infos.Libelle_Filiere;

                              tdFac.textContent=fac;
                              tdFac.style.fontSize="13px";
                              //tdFac.textContent="Info";
                              var Opt=Option.substring(9);
                              if(Opt=="Réseaux Informatiques")
                                {
                                  Opt="Réseaux";
                                }
                                else if(Opt=="Gestion Informatique")
                                  {Opt="Gestion"; }
                                else if(Opt=="Architecture et Construction")
                                  {Opt="Architecture"; }
                                else if(Opt=="Culture et Communication")
                                  {Opt="Communication"; }
                                else if(Opt=="Agronomie et Environnement")
                                  {Opt="Agronomie"; }
                                else if(Opt=="Gestion financière")
                                  {Opt="Financière"; }
                                else if(Opt=="Economie monétaire")
                                  {Opt="Monétaire"; }
                                else if(Opt=="Economie rurale")
                                  {Opt="Rurale"; }
                                else if(Opt=="Droit public")
                                  {Opt="Public"; }
                                else if(Opt=="Droit économique et social")
                                  {Opt="Economique"; }
                                else if(Opt=="Droit privé judiciaire")
                                  {Opt="Privé"; }
                                else Opt=Option.substring(9); 

                               console.log("l'option est"+Opt);
                              tdOption.textContent=Opt;
                              tdpromo.textContent =infos.Abréviation;
                              tdmoys1.textContent=infos.Session1;
                              tdmention1.textContent=infos.Mention1;
                              tdmoys2.textContent=infos.Session2;
                              tdtdmention2.textContent=infos.Mention2;
                              var decision=infos.Decision_jury;
                                if(decision=="Admis sous condition")
                                  {
                                    decision="Admis s.c";
                                  }
                               
                                  else decision=infos.Decision_jury;
                              tddecision.textContent=decision;

                             // tr.appendChild(tdnum);
                              tr.appendChild(tdanne);
                              tr.appendChild(tdEtab);
                              tr.appendChild(tdFac);
                              tr.appendChild(tdOption);
                              tr.appendChild(tdpromo);
                              tr.appendChild(tdmoys1);
                              tr.appendChild(tdmention1);
                              tr.appendChild(tdmoys2);
                              tr.appendChild(tdtdmention2);
                       
                                tr.appendChild(tddecision);
                      }
                      else{
                        var tdanne= document.createElement("td");
                              var tdEtab= document.createElement("td");
                              var tdFac= document.createElement("td");
                              var tdOption= document.createElement("td");
                              var tdpromo= document.createElement("td");


                              var tdmoys1 = document.createElement("td");
                              var tdmention1 = document.createElement("td");
                              var tdmoys2 = document.createElement("td");
                              var tdtdmention2 = document.createElement("td");
                              //var tddecision = document.createElement("td");
                              tdEtab.textContent="U.KA.";
                              var fac=infos.Libelle_Filiere;
                              if(fac=="Sciences Economiques et de Gestion")
                                {
                                  fac="Economie";
                                }
                                else if(fac=="Sciences Informatiques")
                                  {fac="Informatique"; }
                                else if(fac=="Architecture et Construction")
                                  {fac="Architecture"; }
                                else if(fac=="Culture et Communication")
                                  {fac="Communication"; }
                                else if(fac=="	Agronomie et Environnement")
                                  {fac="Agronomie"; }
                                else fac=infos.Libelle_Filiere;
                                tdFac.textContent=fac;
                                tdFac.style.fontSize="13px";

                               var option=Option.substring(5);
                                if(option=="Réseaux Informatiques")
                                  {
                                    option="Réseaux";
                                  }
                                  else if(option=="Gestion Informatique")
                                    {option="Gestion"; }
                                  else if(option=="Architecture et Construction")
                                    {option="Architecture"; }
                                  else if(option=="Culture et Communication")
                                    {option="Communication"; }
                                  else if(option=="Agronomie et Environnement")
                                    {option="Agronomie"; }
                                  else if(option=="Gestion financière")
                                    {option="Financière"; }
                                  else if(option=="Economie monétaire")
                                    {option="Monétaire"; }
                                  else if(option=="Economie rurale")
                                    {option="Rurale"; }
                                  else if(option=="Droit public")
                                    {option="Public"; }
                                  else if(option=="Droit économique et social")
                                    {option="Economique"; }
                                  else if(option=="Droit privé judiciaire")
                                    {option="Privé"; }
                                  else option=Option.substring(5);

                                  
                                  
                              tdOption.textContent=option;
                              tdanne.innerHTML = "<nobr>" + infos.Annee_debut + " - " + infos.Annee_fin + "</nobr>";
                              tdpromo.textContent =infos.Abréviation;
                              tdmoys1.textContent=infos.Session1;
                              tdmention1.textContent=infos.Mention1;
                              tdmoys2.textContent=infos.Session2;
                              tdtdmention2.textContent=infos.Mention2;
                              //tddecision.textContent=infos.Decision_jury;
                              
                              tdEtab.style="border:2px solid black; color:black;"
                              tdFac.style="border:2px solid black; color:black;"
                              tdOption.style="border:2px solid black; color:black;"
                              tdpromo.style="border:2px solid black; color:black;"
                              tdanne.style="border:2px solid black; color:black;"
                              tdpromo.style="border:2px solid black; color:black;"
                              tdmoys1.style="border:2px solid black; color:black;"
                              tdmention1.style="border:2px solid black; color:black;"
                              tdmoys2.style="border:2px solid black; color:black;"
                              tdtdmention2.style="border:2px solid black; color:black;"
                              
                             // tr.appendChild(tdnum);
                              tr.appendChild(tdanne);
                              tr.appendChild(tdEtab);
                              tr.appendChild(tdFac);
                              tr.appendChild(tdOption);
                              tr.appendChild(tdpromo);


                              tr.appendChild(tdmoys1);
                              tr.appendChild(tdmention1);
                              tr.appendChild(tdmoys2);
                              tr.appendChild(tdtdmention2);

                      }

                              //tdmoys1.rowSpan = 2;
                              //tdmention1.rowSpan = 2;
                              tbody.appendChild(tr);
                              //i++;
                            });

                            
                
                       }).catch(error => {
                        // Traitez l'erreur ici
                        console.log("Erreur lor de contacte de l'API "+error);});
                        TabImprimer.appendChild(tbody);
                    
                        
           

}
function SuppressionCursus(MatEtudiant,idanne,codepromo)
{
  

  var lien='API_PHP/API_Sup_Cursus.php?matEtud='+MatEtudiant+'&Anne='+idanne+'&promo='+codepromo; 

  console.log(' sup :'+MatEtudiant);  
  console.log('sup:'+idanne);  
  console.log('sup:'+codepromo);  
  console.log('sup---------------');

  fetch(lien) ;
 
  console.log(" Je suis avantr Afficher ");
  Affichage_Cursus(MatEtudiant);
                        
}