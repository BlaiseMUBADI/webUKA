
console.log(" je suis dans js de fixation de frais");


/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DECLARATIONS DE COMPOSANT HTML  +++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/


const cmb_filiere_frais=document.getElementById("filiere_frais_fixer");
const cmb_promotion_frais=document.getElementById("promo_frais_fixer");
const cmb_annee_academique_frais=document.getElementById("Id_an_acad_frais_fixer");

/*const zone_sommeFA=document.getElementById("sommeFA");
const zone_sommeEnrol_Mi_session=document.getElementById("sommeEnrolement_mi_session");
const zone_sommeEnrol_Session=document.getElementById("sommeEnrolement_Session");
const zone_sommeEnrol_Deuxime_Session=document.getElementById("sommeEnrolement_Deuxieme_session");*/




/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ La partie d'ajout des évenements à chaque composant +++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

 // Lorque le combo box de filiere  change
 // on test si l'élement existe vraiment sur la page html
 if(cmb_filiere_frais!==null)
 {
  cmb_filiere_frais.addEventListener('change',(event) => {
    var id_filiere=cmb_filiere_frais.value;
    Affichage_promotion_fixe(id_filiere);
  });

 }





// Lorsque le combo de promoton change
// on test si l'élement existe vraiment sur la page html
if(cmb_promotion_frais!==null)
{
  cmb_promotion_frais.addEventListener('change',(event)=> {
    var code_promo=cmb_promotion_frais.value;
    var Id_annee=cmb_annee_academique_frais.value;
  
    Affichage_Frais_par_promotion(code_promo,Id_annee);
  
  
  });

}


// Lorsque le combo de des années academique a changé
// on test si l'élement existe vraiment sur la page html
if(cmb_annee_academique_frais!==null)
{
  cmb_annee_academique_frais.addEventListener('change',(event)=> {
    var code_promo=cmb_promotion_frais.value;
    var Id_annee=cmb_annee_academique_frais.value;
  
    console.log("code promo = "
      +code_promo+" id_annee_academiqe = "+Id_annee);
    Affichage_Frais_par_promotion(code_promo,Id_annee)
  
  });

}

/*
*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
* ++++++++++++++++++++++++ LA PARTIE DE LA DEFINITION DE FONCTIONS +++++++++++++++++++++++++++++++
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

// ICI LA FONCTION POUR LA RECUPERATIONS DES PROMOTIONS EN FONCTION DE LA FILIERE CHOISIE
function Affichage_promotion_fixe(Idfiliere ) {

    // Réinitialiser le contenu de la balise select des promotions
    var cmb_promotion=document.getElementById("promo_frais_fixer");
    cmb_promotion.innerHTML = "";
  
    
    // Contacte de l'API PHP
    const url='D_Budget/API_PHP/Recup_prom_filiere?idFiliere='+Idfiliere;
          
    fetch(url) 
    .then(response => response.json())
    .then(data => {
      data.forEach(infos => {
        

        const option = document.createElement("option");
        option.value = infos.cd_prom;
        option.textContent = infos.abv+" - "+infos.lib_mention;
    
        // Ajouter l'option à la balise select
        cmb_promotion.appendChild(option);

        //cmb_promotion.innerHTML += "<option style='width:100%;'value='"+infos.cd_prom+"'>"+infos.abv+" - "+infos.lib_mention+"</option>";
        //console.log("Code promo est "+infos.cd_prom+" la promo "+infos.abv+" - "+infos.lib_mention);
        
      });
    })
    .catch(error => console.error('Erreur lors de la récupération des promotions :', error));
  
  }
  ////////////////////////////////////////////////////////////////////////////////////////////





// LA FONCTION D'AFFIHAGE DES ETUDIANTS D'UNE PROMOTION DANS UNE ANNEE ACADEMIQUE CHOISIE
function Affichage_Frais_par_promotion(code_promo,Id_an_acad)
{
   
    var tableau = document.getElementById("table_frais");

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
    
      

    td1.textContent = "N°";
    td2.textContent = "Motif Frais";
    td3.textContent = "Montant";
    td4.textContent = "Tranche";
    td5.textContent = "Devise";
    td6.textContent = "Actions";
    td7.textContent = "Id_frais";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);
    tr1.appendChild(td7);

      
    thead.appendChild(tr1);
    tableau.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    
    // Contacter l'API pour avoir les étudiants// Contacte de l'API PHP
    var url='D_Budget/API_PHP/liste_frais_fixer.php?Id_annee_acad='+Id_an_acad+'&code_promo='+code_promo;
        
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

              var td_lib_frais= document.createElement("td");
              var td_montant_frais = document.createElement("td");
              var td_tranche_frais = document.createElement("td");
              var td_devise = document.createElement("td");
              var td_action=document.createElement("td");

              

              var div = document.createElement("div");
              div.classList.add("row", "text-center", "p-0", "m-0");
              td_action.appendChild(div);

              // Créer le premier bouton de la suppression
              var div2 = document.createElement("div");
              div2.classList.add("col","p-0", "m-0");
              div.appendChild(div2);

              var btn_suppression = document.createElement("button");
              btn_suppression.setAttribute("type", "button");
              btn_suppression.classList.add("btn", "btn-primary");

              //Ajout de l'évenement au boutton d'impression
              btn_suppression.addEventListener("click", function(event)
              {
              
                /*Suppression_Transaction(mat_etudiant,
                  code_promo,
                  infos.date_paie,
                  Id_an_acad,nom_etudiant,tr1);*/
              });

              var i2 = document.createElement("i");
              i2.classList.add("fas", "fa-trash-alt");
              btn_suppression.appendChild(i2);
              div2.appendChild(btn_suppression);






              

              
              var td_id_frais = document.createElement("td");
              
              td_lib_frais.textContent =infos.Lib_frais;
              td_montant_frais.textContent=infos.montant_fixe
              td_tranche_frais.textContent=infos.tranche_frais;
              td_devise.textContent=infos.devise;              
              td_id_frais.textContent=infos.id_frais;
             
              


              tr.appendChild(tdnum);
              tr.appendChild(td_lib_frais);
              tr.appendChild(td_montant_frais);
              tr.appendChild(td_tranche_frais);
              tr.appendChild(td_devise);
              
              tr.appendChild(td_action);
              tr.appendChild(td_id_frais);
              
              
              
              
              tbody.appendChild(tr);
              i++;

              // Ajout de l'évenement sur la lign appellant
              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() {
                
                Recuperation_Frais_fixer(infos.id_frais,Id_an_acad,code_promo);
                
              });

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lor de contacte des etudiants "+error);});
          tableau.appendChild(tbody);
}

/*
  * la méthode pour récupere tous les frais fixés dans une promotion X
*/
function Recuperation_Frais_fixer(Id_frais,id_annee,code_prom)
{

 
  cmb_motif_frais_fixer=document.getElementById("select_motif_frais");
  txt_montant_frais_fixer=document.getElementById("txt_montant_frais");
  txt_tranche_frais_fixer=document.getElementById("txt_tranche_frais");
  txt_devise=document.getElementById("devise");
  
  // Initialisation de zonnes
  
  cmb_motif_frais_fixer.textContent="";
  txt_montant_frais_fixer.textContent="";
  txt_tranche_frais_fixer.textContent="";
  txt_devise=document.textContent="";


  // Cette partie lance contacte uniquement l'API pour le frais académique
  const xhr=new XMLHttpRequest();
  $type_frais="Frais Académiques";
/*
  var url='D_Perception/API_PHP/Recup_situation_paie_etudiant.php'+
        '?matricule='+mat_etudiant
        +'&id_annee_acad='+Id_an_acad
        +'&type_frais='+$type_frais;
  xhr.open('GET',url,true);
  xhr.onload=function()
  {
    if(xhr.status===200)
    {
      // On recupere les infos de JSON
      var somm=JSON.parse(xhr.responseText);
      somm.forEach(element =>
      {
        var somme_FA=element.somme_paier;
        if(somme_FA!=null) zone_sommeFA.innerHTML =somme_FA+" Fc" ;
        else zone_sommeFA.innerHTML=" 0 Fc" ; 
      });
    }
  }
  xhr.send();
  /////////////////////////////////////

*/

}

function Nouveau_Paiement()
{
  
  id_annee=document.getElementById("Id_an_acad_frais_fixer").value;
  cod_prom=document.getElementById("promo_frais_fixer").value;
  lib_frais=document.getElementById("select_motif_frais").value;
  montant_fixer=document.getElementById("txt_montant_frais").value;
  tranche_fixer=document.getElementById("txt_tranche_frais").value;
  devise= "Fc";//document.querySelector("dollar").value;

  if(document.getElementById("dollar").checked)
  {
    devise="Dollar"
  }
  



  var xhr = new XMLHttpRequest();
  xhr.open("POST", "D_Budget/API_PHP/Nouveau_frais_fixer.php", true);

  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() 
        {
            if (xhr.readyState === 4 && xhr.status === 200)
            {
                console.log(xhr.responseText)
                // Réponse du serveur

                if(xhr.responseText!="Ok") 
                {
                  
                  alert("Enregistrment effectuer avec succès");
                       // Intialisation_zone_paiement_banque();
                       //Affichage_Frais_par_promotion(code_promo,Id_annee);
                  Intialisation_frais_fixer(cod_prom,id_annee);
                }
                    
                else 
                {
                    alert( " Echec d'eregistrement ");
                }
            }
        };
        
        xhr.send("Id_an_acad=" + id_annee
                + "&code_promo=" + cod_prom                
                + "&motif_frais=" + lib_frais
                + "&montant_fixer=" + montant_fixer
                + "&tranche=" + tranche_fixer
                + "&devise=" + devise);

}

function Intialisation_frais_fixer(cod_prom,id_annee)
{
  // Appel de la fonction pour actualiser le tableau

  Affichage_Frais_par_promotion(cod_prom,id_annee);
  

  lib_frais=document.getElementById("select_motif_frais").selectedIndex = 0;
  montant_fixer=document.getElementById("txt_montant_frais").value="";
  tranche_fixer=document.getElementById("txt_tranche_frais").value="";

}