
console.log('nous sommes dans le js afficher liste etudiant');  

const prom=document.getElementById("promotion");
const idAnnee_Ac=document.getElementById("annee");


    


prom.addEventListener('change',(event) => {
    var idAnnee_Acad=annee.value;
    var code_promo=prom.value;
    console.log("la valeur de promotion est :::: "+code_promo)
    console.log("la valeur de annee est :::: "+idAnnee_Acad)
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
    var td7 = document.createElement("td");
  
    td1.textContent = "N°";
    //td2.textContent = "Matricule";
    td3.textContent = "Nom";
    td4.textContent = "Postnom";
    td5.textContent = "Prenom";
    td6.textContent = "Sexe";
    td7.textContent = "Action";

    tr1.appendChild(td1);
    //tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);
    tr1.appendChild(td7);

      
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
             
             var btn=document.createElement('input');
              btn.setAttribute('type','button');
              //btn.setAttribute('class','float-end');
              
              btn.setAttribute('value','Cursus');

              tdbtn.appendChild(btn);
              btn.addEventListener("click", function() 
              {
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
                document.getElementById("datesigne").innerText="Fait à Kananga, le "+today+"               ";
                document.getElementById("acad").innerText="Secrétaire Général Académique ";
                document.getElementById("academ").innerText="Professeur Martin BAYAMBA Kasonga ";
                var MatEtudiant=infos.Matricule;
                Affichage_Imprimer(MatEtudiant);
                
                Affichage_Cursus(MatEtudiant);

                //La fonction qui affiche et imprime le cursus académique de l'Etudiant
                                
               
              });
             tr.appendChild(tdnum);
             // tr.appendChild(tdmatricule);
              tr.appendChild(tdnom);
              tr.appendChild(tdpostnom);
              tr.appendChild(tdprenom);
              tr.appendChild(tdsexe);
              tr.appendChild(tdbtn);
 
              tbody.appendChild(tr);
              i++;
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
function Affichage_Cursus(MatEtudiant)
{
                
                
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
                        td3.textContent = "Moyenne S1";
                        td4.textContent = "Mention";
                        td5.textContent = "Moyenne Ratt";
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
                    
                
                    var lien='API_PHP/APISelect_Cursus.php?matEtud='+MatEtudiant;  
                
                      console.log('le Matricule envoyé est :'+MatEtudiant);  
                      console.log('nous sommes ici---------------');
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
                              
                              tdanne.textContent =infos.Annee_debut+"-"+infos.Annee_fin;
                              tdpromo.textContent =infos.Abréviation;
                              tdmoys1.textContent=infos.Session1;
                              tdmention1.textContent=infos.Mention1;
                              tdmoys2.textContent=infos.Session2;
                              tdtdmention2.textContent=infos.Mention2;
                              //tddecision.textContent=infos.Decision_jury;
                              
                             // tr.appendChild(tdnum);
                              tr.appendChild(tdanne);
                              tr.appendChild(tdpromo);
                              tr.appendChild(tdmoys1);
                              tr.appendChild(tdmention1);
                              tr.appendChild(tdmoys2);
                              tr.appendChild(tdtdmention2);
                              //tr.appendChild(tddecision);
                 
                              tbody.appendChild(tr);
                              //i++;
                            });

                            
                var Btn = document.getElementById('btn'); Btn.style.display='block';
                var BtnAju = document.getElementById('btnAjuster'); BtnAju.style.display='block';
                             
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
                    thead.classList.add("sticky-sm-top","m-0","fw-bold","font-sm"); // Pour ajouter la classe à un element HTMl
                
                    var tr1 = document.createElement("tr");
                    //tr1.style="background-color:midnightblue; color:white;"
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
                    td1.textContent = "Année Ac.";
                    td2.textContent = "Etab.";
                    td3.textContent = "Faculté";
                    td4.textContent = "Option";
                    td5.textContent = "Promotion";
                    td6.textContent = "RESULTATS";
                    td6.style.textAlign = "center";

                    //fusion
                    td6.setAttribute("colspan", "5");
                    td1.setAttribute("rowspan", "3");
                    td2.setAttribute("rowspan", "3");
                    td3.setAttribute("rowspan", "3");
                    td4.setAttribute("rowspan", "3");
                    td5.setAttribute("rowspan", "3");


  
                
                    tr1.appendChild(td1);
                    tr1.appendChild(td2);
                    tr1.appendChild(td3);
                    tr1.appendChild(td4);
                    tr1.appendChild(td5);
                    tr1.appendChild(td6);
                  
                  
                    
                    //Création de la deuxième ligne
                    var tr2 = document.createElement("tr");

                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");

                    td1.textContent = "1ère Session";
                    td1.style.textAlign = "center";
                    td1.setAttribute("colspan", "2");
                   
                    td2.textContent = "Rattrapage";
                    td2.style.textAlign = "center";
                    td2.setAttribute("colspan", "2");
                    //var systeme1 = document.getElementById("PADEM");
                    tr2.appendChild(td1);
                    tr2.appendChild(td2);
                 
                  //troisième ligne
                    var tr3 = document.createElement("tr");

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
                    //tr1.style="background-color:midnightblue; color:white;"
                
                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");
                    var td3 = document.createElement("td");
                    var td4 = document.createElement("td");
                    var td5 = document.createElement("td");
                    var td6 = document.createElement("td");
                    //var td7 = document.createElement("td");
                    td1.textContent = "Année Ac.";
                    td2.textContent = "Etab.";
                    td3.textContent = "Faculté";
                    td4.textContent = "Option";
                    td5.textContent = "Promotion";
                    td6.textContent = "RESULTATS";
                    td6.style.textAlign = "center";

                    //fusion
                    td6.setAttribute("colspan", "4");
                    td1.setAttribute("rowspan", "3");
                    td2.setAttribute("rowspan", "3");
                    td3.setAttribute("rowspan", "3");
                    td4.setAttribute("rowspan", "3");
                    td5.setAttribute("rowspan", "3");

                    tr1.appendChild(td1);
                    tr1.appendChild(td2);
                    tr1.appendChild(td3);
                    tr1.appendChild(td4);
                    tr1.appendChild(td5);
                    tr1.appendChild(td6);
                    
                    //Création de la deuxième ligne
                    var tr2 = document.createElement("tr");

                    var td1 = document.createElement("td");      
                    var td2 = document.createElement("td");

                    td1.textContent = "1ère Session";
                    td1.style.textAlign = "center";
                    td1.setAttribute("colspan", "2");
                   
                    td2.textContent = "2ère Session";
                    td2.style.textAlign = "center";
                    td2.setAttribute("colspan", "2");
                    
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
                
                    var lien='API_PHP/APISelect_Cursus.php?matEtud='+MatEtudiant;  
                
                      console.log('le Matricule envoyé est :'+MatEtudiant);  
                      console.log('nous sommes ici---------------');
                    var i=1;
                    fetch(lien) 
                    .then(response => response.json())
                    .then(data => 
                    {
                      data.forEach(infos =>
                        {
                          // Création de TR
                              var tr = document.createElement("tr");

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
                                tdanne.textContent =infos.Annee_debut+"-"+infos.Annee_fin;
                              //tdEtab.textContent="UKA";
                              //tdFac.textContent="Info";
                              //tdOption.textContent="gestion";
                              tdpromo.textContent =infos.Abréviation;
                              tdmoys1.textContent=infos.Session1;
                              tdmention1.textContent=infos.Mention1;
                              tdmoys2.textContent=infos.Session2;
                              tdtdmention2.textContent=infos.Mention2;
                              tddecision.textContent=infos.Decision_jury;
 
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
                              
                              tdanne.textContent =infos.Annee_debut+"-"+infos.Annee_fin;
                              tdpromo.textContent =infos.Abréviation;
                              tdmoys1.textContent=infos.Session1;
                              tdmention1.textContent=infos.Mention1;
                              tdmoys2.textContent=infos.Session2;
                              tdtdmention2.textContent=infos.Mention2;
                              //tddecision.textContent=infos.Decision_jury;
                              
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