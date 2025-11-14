  console.log(" je suis dans Manip_EC")

  /*
  *++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  *+++++++++++++++++++ C'est un script qui se charge de la manipulation des comptes agents+++++++++
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  *
  */

  /*
  *********************************************************************************************
  * ***************************** Déclaration des composants HTML *****************************
  *********************************************************************************************
  */

  var code_ue_ec="";
  var tr_selectionner="";
  var verfi=true;

  // Les éléments du DOM sont initialisés seulement si la page contient
  // l'élément parent `div_gen_EC`. Cela évite que ce script lance des
  // getElementById() au top-level et retourne `null` quand il est inclus
  // sur d'autres pages.
  let cmb_semestre;
  let cmb_promotion_FAC;
  let txt_nom_ec;
  let txt_cmi;
  let txt_hr_td;
  let txt_hr_tp;
  let txt_tpe;
  let txt_vht;
  let txt_nb_credit;
  let boite_form_EC;
  let boite_alert_SM_EC;
  let boite_confirmation_action_SM_EC;

  document.addEventListener("DOMContentLoaded",function(event)
  {
    const container = document.getElementById("div_gen_EC");
    if (container !== null) {
      // initialiser les éléments en utilisant le conteneur pour éviter
      // toute sélection hors-contexte lorsque ce script est inclus sur
      // d'autres pages
      cmb_semestre = container.querySelector('#id_semestre_FAC') || document.getElementById('id_semestre_FAC');
      cmb_promotion_FAC = container.querySelector('#code_prom_FAC') || document.getElementById('code_prom_FAC');
      txt_nom_ec = container.querySelector('#txt_nom_ec') || document.getElementById('txt_nom_ec');
      txt_cmi = container.querySelector('#txt_cmi') || document.getElementById('txt_cmi');
      txt_hr_td = container.querySelector('#txt_hr_td') || document.getElementById('txt_hr_td');
      txt_hr_tp = container.querySelector('#txt_hr_tp') || document.getElementById('txt_hr_tp');
      txt_tpe = container.querySelector('#txt_cmi') || document.getElementById('txt_cmi');
      txt_vht = container.querySelector('#txt_vht') || document.getElementById('txt_vht');
      txt_nb_credit = container.querySelector('#txt_nb_credit') || document.getElementById('txt_nb_credit');

      // boîtes de dialogue peuvent être en dehors du conteneur
      boite_form_EC = document.getElementById('boite_Form_EC');
      boite_alert_SM_EC = document.getElementById('boite_alert_SM_EC');
      boite_confirmation_action_SM_EC = document.getElementById('boite_confirmaion_action_SM_EC');

      // Ce code permet de capter l'évenement sur le combo_smestre afin d'afficher les UES
      if(cmb_semestre !== null) {
        cmb_semestre.addEventListener('change',(event)=> {
          var id_semetre=cmb_semestre.value;
          Affichage_UEs_FAC(id_semetre);  
        });
      }
    }
  })



  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES UEs D'UNE FILIERE ********************
  *****************************************************************************************
  */

  function Affichage_UEs_FAC(id_semestre_fac)
  {
    let tab_ue_fac = document.getElementById("table_ue_fac");

      while (tab_ue_fac.firstChild) {
        tab_ue_fac.removeChild(tab_ue_fac.firstChild);
      }
      
      
      var thead = document.createElement("thead");
      thead.classList.add("sticky-sm-top","m-0","fw-bold", "text-center"); // Pour ajouter la classe à un element HTMl

      var tr1 = document.createElement("tr");
      tr1.style="background-color:midnightblue; color:white;"

      var td1 = document.createElement("td");      
      var td2 = document.createElement("td");      
      var td3 = document.createElement("td");      

      td1.textContent = "N°";
      td2.textContent = "UE";
      td3.textContent = "Catégorie";

      tr1.appendChild(td1);
      tr1.appendChild(td2);
      tr1.appendChild(td3);

        
      thead.appendChild(tr1);
      tab_ue_fac.appendChild(thead);
        
      var tbody = document.createElement("tbody");
      
      

      var url='API_PHP/Liste_UE_SM_Filiere_donnee.php'+
      '?id_semestre='+id_semestre_fac;;
          
      var i=1;
      fetch(url) 
      .then(response => response.json())
      .then(data => 
      {
        data.forEach(infos =>
          {
            // Création de TR
                var tr = document.createElement("tr");
                

                var td_num= document.createElement("td");
                var td_ue = document.createElement("td");
                var td_categorie = document.createElement("td");
                

                td_num.textContent =i;
                td_ue.textContent=infos.nom_ue
                td_categorie.textContent=infos.categorie_ue
                
              
                tr.appendChild(td_num);
                tr.appendChild(td_ue);             
                tr.appendChild(td_categorie);             
                
                
                tbody.appendChild(tr);

                // Ajout de l'évenement sur la ligne appellant
                // Ajouter l'événement de clic pour afficher les infos de la ligne
                tr.addEventListener("click", function() {
                  //var nom_agent=infos.identite;
                  code_ue_ec=infos.Code_ue;
                  Recuperation_ECs(infos.Code_ue,tr);
                  
                });

                
                i++;
                
          });
            
          }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte des UEs "+error);});
            tab_ue_fac.appendChild(tbody);
            tab_ue_fac.classList.add("table-striped");

  }


  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES ECs D'UNE UE ********************
  *****************************************************************************************
  */
  function Recuperation_ECs(code_ue,tr1)
  {
    // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
    var table_ue_fac= document.getElementById("table_ue_fac");
    var rows = table_ue_fac.getElementsByTagName('tr');  
    for(var j = 0; j < rows.length; j++) 
    {
      if(j!=0) rows[j].style.backgroundColor = '';
    }
    tr1.style.backgroundColor = 'red';
    tr_selectionner=tr1;
    
    var table_ecs_fac = document.getElementById("table_ecs_fac");

    while (table_ecs_fac .firstChild) {
      table_ecs_fac.removeChild(table_ecs_fac.firstChild);
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
      

    td1.textContent = "N°";
    td2.textContent = "Nom E.C.";
    td3.textContent = "CMI";
    td4.textContent = "TD";
    td5.textContent = "TP";
    td6.textContent = "TPE";
    td7.textContent = "VHT"; 
    td8.textContent = "Crédit"; 
    td9.textContent = "Action"; 

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);
    tr1.appendChild(td7);
    tr1.appendChild(td8);
    tr1.appendChild(td9);
    

      
    thead.appendChild(tr1);
    table_ecs_fac.appendChild(thead);
      
    var tbody = document.createElement("tbody");

    var url='API_PHP/Liste_ECs_SM_Filiere_donnee.php'+
    '?code_ue='+code_ue+'&code_prom='+cmb_promotion_FAC.value;

      var i=1;
      fetch(url) 
      .then(response => response.json())
      .then(data => 
      {
        data.forEach(infos =>
          {
            // Création de TR
            var tr = document.createElement("tr");
            tr.id="tr_"+i;
            
            var tdnum = document.createElement("td");
            tdnum.textContent = i;
            tdnum.classList.add("text-center");
    
            var td_nom_ec= document.createElement("td");
            var td_cmi= document.createElement("td");
            var td_hr_td= document.createElement("td");
            var td_hr_tp= document.createElement("td");
            var td_tpe= document.createElement("td");
            var td_vht= document.createElement("td");
            var td_credit= document.createElement("td");
            var td_Action = document.createElement("td"); // La cellule qui contient nos deux btns d'actions
            
    
            td_nom_ec.textContent =infos.nom_ec;
            td_cmi.textContent=infos.cmi;
            td_hr_td.textContent=infos.hr_td;
            td_hr_tp.textContent=infos.hr_tp;
            td_tpe.textContent=infos.tpe;
            td_vht.textContent=infos.vht;
            td_credit.textContent=infos.credit;

          
            // Ici on crée deux boutons pour l'impressionet la suppression
            // On commence par créer un contenaire qui vas accceuillir nos deux poubont

            var div = document.createElement("div");
            div.classList.add("row", "text-center", "p-0", "m-0");
            td_Action.appendChild(div);


            // Créer le deuxième bouton de la suppression
            var div2 = document.createElement("div");
            div2.classList.add("col","p-0", "m-0");
            div.appendChild(div2);

            var btn_suppression = document.createElement("button");
            btn_suppression.setAttribute("type", "button");
            btn_suppression.classList.add("btn", "btn-primary");

            //Ajout de l'évenement au boutton Supresiion
            btn_suppression.addEventListener("click", function(event) {
              
             Ouvrir_Boite_Confirmation_Action_SM_EC("Attention !!! Cette opération est irreversible"+
              "\nVoulez-vous vraiment Cet EC ?",code_ue_ec,infos.id_ec,tr1);
            /*Suppression_UE(id_semestre_FAC,
              infos.Code_ue,tr1);*/
            });

            var i2 = document.createElement("i");
            i2.classList.add("fas", "fa-trash-alt");
            btn_suppression.appendChild(i2);

            div2.appendChild(btn_suppression);

            tr.appendChild(tdnum);
            tr.appendChild(td_nom_ec);
            tr.appendChild(td_cmi);
            tr.appendChild(td_hr_td);
            tr.appendChild(td_hr_tp);
            tr.appendChild(td_tpe);
            tr.appendChild(td_vht);
            tr.appendChild(td_credit);
            tr.appendChild(td_Action);

            tbody.appendChild(tr);
            i++;
          });
        
        }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de la selection des transactions "+error);});
            table_ecs_fac.appendChild(tbody);




  }
  // *********** FIN AFFICHAGE DE UE **************************

  /*
  *****  LA METHODE POUR AJOUTER LES ECS
  */


  function Ajout_EC()
  {
    console.log(" Regarde retunr "+verification_info_EC());

    if(verification_info_EC())
    {
     
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "API_PHP/Ajout_EC.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() 
          {
              if (xhr.readyState === 4 && xhr.status === 200)
              {
                  console.log(xhr.responseText)
                  // Réponse du serveur
                  if(xhr.responseText!="Ok") 
                  {
                      Recuperation_ECs(code_ue_ec,tr_selectionner)
                      //Initialisation_zone_compte_agent()
                      Ouvrir_Boite_Alert_SM_EC("EC ajouté avec succè !");              
                  }
                      
                  else Ouvrir_Boite_Alert_SM_EC( " Echec d'eregistrement ");
              }
          };
          
          xhr.send("code_ue=" + code_ue_ec
                  + "&code_prom=" + cmb_promotion_FAC.value
                  + "&nom_ec=" + txt_nom_ec.value
                  + "&credit=" + txt_nb_credit.value
                  + "&hr_td=" + txt_hr_td.value
                  + "&hr_tp=" + txt_hr_tp.value
                  + "&CMI=" + txt_cmi.value
                  + "&VHT=" + txt_vht.value
                  + "&TPE=" + txt_tpe.value
              );

    }
    else Ouvrir_Boite_Alert_SM_EC(" Le code UE saisi est déjà utilisé ou une zone est vide");
  
    Fermer_Form_EC();
  }
  // *************  FIN DE LA METHODE AJOUT  ************************





  /*
  * LA METHODE POUR VERIFIER LES INFOS SAISIES SUR LE FORMULAIRE
  */

  function verification_info_EC()
  {
    if(cmb_promotion_FAC.value==="rien"
      || txt_nom_ec.value===""
      || txt_nb_credit.value===""
      || txt_hr_td.value===""
      || txt_hr_tp.value===""
      || code_ue_ec===""
    )
    return false;
    else return true;
  }




  /*************************************************************************************
  ********************    ICI C'EST POUR OUVRIR LA BOITE DE DIALOGUE ********************
  ***************************************************************************************/

  function Ouvrir_Form_EC()
  {
      boite_form_EC.showModal();
  }
  // Fermer la boîte de dialogue
  function Fermer_Form_EC() {
      boite_form_EC.close();
  }


  function Ouvrir_Boite_Alert_SM_EC(text_a_afficher)
  {
      document.getElementById("text_alert_boite_EC").innerText=text_a_afficher;
      boite_alert_SM_EC.showModal();
  }
  // Fermer la boîte de dialogue
  function Fermer_Boite_Alert_SM_EC() {
    boite_alert_SM_EC.close();
  }
  //id_semestre_FAC,infos.Code_ue,tr1

  function Ouvrir_Boite_Confirmation_Action_SM_EC(text_a_afficher,code_ue_ec,code_EC,tr1)
  {
    
    let btn_action_oui=document.getElementById("btn_action_EC_oui");
    let btn_action_non=document.getElementById("btn_action_EC_non");
    
    document.getElementById("text_confirm_EC_afficher").innerText=text_a_afficher;
    boite_confirmation_action_SM_EC.showModal();

    btn_action_oui.addEventListener("click", function(event)
    {
        
        boite_confirmation_action_SM_EC.close();
        Suppression_EC(code_EC);

    });

    btn_action_non.addEventListener("click", function(event)
    {
        boite_confirmation_action_SM_EC.close();
        Ouvrir_Boite_Alert_SM_EC("Action annulée  !");  

    });

  }
  /******************************  FIN MANIPULATION DE LA BBOITE E DIALOGUE********************** */


  /******************************************************************************************
 ********* CETTE FONCTION PERMET DE SUPPRIMER UNE EC *************************************
 *****************************************************************************************/
 function Suppression_EC(code_EC)
 {
    console.log("Je suis dans SUPP semFAC = "+code_ue_ec+"; codeEC = "+code_EC);

 
    const url = 'API_PHP/Suppression_EC.php'; 
     // Création de l'objet XMLHttpRequest
    const xhr = new XMLHttpRequest();         
     // Préparation de la requête
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');         
     // Gestionnaire d'événement pour la réponse de la requête
    xhr.onload = function()
     {
       if (xhr.status === 200) 
       {
         Ouvrir_Boite_Alert_SM_EC("EC Supprimée avec succè !");  
         // Puis on fait l'initialisation du tableau
         Recuperation_ECs(code_ue_ec,tr_selectionner);
       } 
       
       else
       {
         // La suppression a échoué
         Ouvrir_Boite_Alert_SM_EC("Impossible de supprimer cette UE  !");  
       }
     };
     
     // Envoi de la requête avec les données nécessaires
     xhr.send("code_ec="+code_EC);
   } 
   
 
 
 /*********************************FIN SUPPRESSION UE ******************************************* */
 