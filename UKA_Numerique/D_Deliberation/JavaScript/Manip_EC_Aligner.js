  console.log(" je suis dans Manip_EC_Aligne")

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
if(document.getElementById("div_gen_Aligne_Enseignant")!==null)
{
  var tr_selectionner="";
  var matricule_agent="null";
  var code_prom_aligne="";
  var id_annee_acad_aligne="";

 
  /*var code_ue_ec="";
  var verfi=true;

  const cmb_semestre=document.getElementById('id_semestre_FAC');


  const txt_nom_ec=document.getElementById('txt_nom_ec');
  const txt_cmi=document.getElementById('txt_cmi');
  const txt_hr_td=document.getElementById('txt_hr_td');
  const txt_hr_tp=document.getElementById('txt_hr_tp');
  const txt_tpe=document.getElementById('txt_cmi');
  const txt_vht=document.getElementById('txt_vht');

  const txt_nb_credit=document.getElementById('txt_nb_credit');



  const boite_form_EC = document.getElementById('boite_Form_EC');
  const boite_alert_SM_EC= document.getElementById('boite_alert_SM_EC');
  const boite_confirmation_action_SM_EC= document.getElementById('boite_confirmaion_action_SM_EC');
  */



  // Ce code permet de capter l'évenement sur le combo_smestre afin d'afficher les UES

 /* if(cmb_semestre!==null)
  {
      cmb_semestre.addEventListener('change',(event)=> {
        var id_semetre=cmb_semestre.value;
        Affichage_UEs_FAC(id_semetre);  
      });
    
  }*/



  // Pour la selection de code aligne
  if(document.getElementById('code_prom_Align_EC')!==null)
  {
    document.getElementById('code_prom_Align_EC').addEventListener('change',(event)=> 
    {
      code_prom_aligne=document.getElementById('code_prom_Align_EC').value;
      Affichage_ECs();
    });
  }

  // Pour la selection id_fac_annee
  if(document.getElementById('id_fac_annee')!==null)
  {
    id_annee_acad_aligne=document.getElementById('id_fac_annee').value;
    document.getElementById('id_fac_annee').addEventListener('change',(event)=> 
    {
      id_annee_acad_aligne=document.getElementById('id_fac_annee').value;
      Affichage_ECs();
    });
  }

  document.addEventListener("DOMContentLoaded",function(event)
  {
    
        Affichage_Enseignant_Aligner();
        Affichage_ECs();

     
  })

  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES UEs D'UNE FILIERE ********************
  *****************************************************************************************
  */

  function Affichage_Enseignant_Aligner()
  {
    let table_aligne_enseignant = document.getElementById("table_aligne_enseignant");

      while (table_aligne_enseignant.firstChild) {
        table_aligne_enseignant.removeChild(table_aligne_enseignant.firstChild);
      }
      
      
      var thead = document.createElement("thead");
      thead.classList.add("sticky-sm-top","m-0","fw-bold", "text-center"); // Pour ajouter la classe à un element HTMl

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
      td2.textContent = "Enseignant";
      td3.textContent = "Sexe";
      td4.textContent = "Domaine";
      td5.textContent = "Titre";
      td6.textContent = "Institution";
      td7.textContent = "Filière";
      td8.textContent = "Phone";
      //td9.textContent = "Email";

      tr1.appendChild(td1);
      tr1.appendChild(td2);
      tr1.appendChild(td3);
      tr1.appendChild(td4);
      tr1.appendChild(td5);
      tr1.appendChild(td6);
      tr1.appendChild(td7);
      tr1.appendChild(td8);
      //tr1.appendChild(td9);

        
      thead.appendChild(tr1);
      table_aligne_enseignant.appendChild(thead);
        
      var tbody = document.createElement("tbody");
      
      

      var url='API_PHP/Liste_Enseignants.php';

          
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
              tdnum.classList.add("text-center");

              
    
              var td_enseignant= document.createElement("td");
              td_enseignant.classList.add("text-center,w-auto");

              var td_sexe = document.createElement("td");
              var td_domaine = document.createElement("td");
              var td_titre_academique = document.createElement("td");
              var td_institution= document.createElement("td");
              var td_filire= document.createElement("td");
              var td_phone= document.createElement("td");
              var td_email= document.createElement("td");
              

              
              td_enseignant.textContent=infos.enseignant;
              td_sexe.textContent=infos.sexe;
              td_titre_academique.textContent=infos.titre_academque;
              td_domaine.textContent=infos.domaine;              
              td_institution.textContent=infos.institut_attache;
              td_filire.textContent=infos.filiere;
              td_phone.textContent=infos.phone;
              td_email.textContent=infos.email;
              
              tr.appendChild(tdnum);
              tr.appendChild(td_enseignant);  
              tr.appendChild(td_sexe);   
              tr.appendChild(td_titre_academique);            
              tr.appendChild(td_domaine);            
              tr.appendChild(td_institution);            
              tr.appendChild(td_filire);            
              tr.appendChild(td_phone);            
              //tr.appendChild(td_email);             
              
              
              tbody.appendChild(tr);

              // Ajouter l'événement de clic pour afficher les infos de la ligne
              tr.addEventListener("click", function() 
              {   
                matricule_agent=infos.mat_agent;             
                Selectionner_Enseignant(infos.mat_agent,tr);                
              });
              i++;
                
          });
            
          }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte des UEs "+error);});
            table_aligne_enseignant.appendChild(tbody);
            table_aligne_enseignant.classList.add("table-striped");

  }

/*
  *****************************************************************************************
  ********* CETTE FONCTION PERMET D'AFFICHER LES ECS ET LES CASE À COCHER *****************
  *****************************************************************************************
*/


  function Affichage_ECs()
  {
    // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
    
    var table_ec_aligne= document.getElementById("table_aligne_EC");

    while (table_ec_aligne .firstChild) {
      table_ec_aligne.removeChild(table_ec_aligne.firstChild);
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
    td2.textContent = "Action";
    td3.textContent = "Nom E.C.";
    td4.textContent = "CMI";
    td5.textContent = "TD";
    td6.textContent = "TP";
    td7.textContent = "TPE";
    td8.textContent = "VHT"; 
    td9.textContent = "Crédit"; 

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
    table_ec_aligne.appendChild(thead);
      
    var tbody = document.createElement("tbody");

    var url='API_PHP/Liste_ECs_aligne.php'+
    '?code_prom='+code_prom_aligne+
    '&mat_agent='+matricule_agent+
    '&annee_acad='+id_annee_acad_aligne;

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
            
            // Ici on crée une div
            var div = document.createElement("div");
            div.classList.add("p-0", "m-0");
            td_Action.appendChild(div);
            
            var case_attribution = document.createElement("input");
            case_attribution.setAttribute("type", "checkbox");
            case_attribution.classList.add("form-check-input");
            // Ici nous desactivons si un cours a été deja attribuer à un autre Enseignant
            if(infos.etat_ec==="true") case_attribution.checked = true;
            
            if(infos.appartenance_enseignant==="false" && infos.etat_ec==="true")
            {
                case_attribution.setAttribute("disabled", "true");
                case_attribution.style.background = "red";
            }


            //case_attribution.classList.add("btn", "btn-primary");

            // On attache l'evenenement à la case à coche
            case_attribution.addEventListener("change", function(event) {
              
              
              if (case_attribution.checked) 
              {
                Aligner_EC(infos.id_ec,"ajout");
              } else if(!case_attribution.checked)
              {
                Aligner_EC(infos.id_ec,"supp");
              }
          });
          
            div.appendChild(case_attribution);



            tr.appendChild(tdnum);
            tr.appendChild(td_Action);
            tr.appendChild(td_nom_ec);
            tr.appendChild(td_cmi);
            tr.appendChild(td_hr_td);
            tr.appendChild(td_hr_tp);
            tr.appendChild(td_tpe);
            tr.appendChild(td_vht);
            tr.appendChild(td_credit);

            tbody.appendChild(tr);
            i++;
          });
        
        }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de la selection des transactions "+error);});
            table_ec_aligne.appendChild(tbody);




  }

  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'ATTACHER UN EC A UN ENSEIGNANT  ********************
  *****************************************************************************************
  */
 function Aligner_EC(code_ec,action)
 {

  if(action==="ajout")
  {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "API_PHP/Aligner_EC.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            console.log(xhr.responseText)
            // Réponse du serveur
            if(xhr.responseText!="Ok") 
            {
                //Recuperation_ECs(code_ue_ec,tr_selectionner)
                //Initialisation_zone_compte_agent()
                //Ouvrir_Boite_Alert_EC_Aligne("EC ajouté avec succè !");              
            }
                
            else Ouvrir_Boite_Alert_EC_Aligne( " Echec d'eregistrement ");
        }
    };
    
    xhr.send("code_ec=" + code_ec
            + "&mat_enseignant=" + matricule_agent
            + "&code_prom=" + code_prom_aligne
            + "&annee_acad=" + id_annee_acad_aligne
        );
    
    Affichage_ECs();

  }
  if(action==="supp")
  {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "API_PHP/Sup_Aligner_EC.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            console.log(xhr.responseText)
            // Réponse du serveur
            if(xhr.responseText!="Ok") 
            {
                //Recuperation_ECs(code_ue_ec,tr_selectionner)
                //Initialisation_zone_compte_agent()
                //Ouvrir_Boite_Alert_EC_Aligne("EC ajouté avec succè !");              
            }
                
            else Ouvrir_Boite_Alert_EC_Aligne( " Echec d'eregistrement ");
        }
    };
    
    xhr.send("code_ec=" + code_ec
            + "&mat_enseignant=" + matricule_agent
            + "&code_prom=" + code_prom_aligne
            + "&annee_acad=" + id_annee_acad_aligne
        );
    Affichage_ECs();

  }
  

 }

  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES ECs D'UNE UE ********************
  *****************************************************************************************
  */
  function Selectionner_Enseignant(mat_agent,tr1)
  {
    // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
    var table_aligne_enseignant= document.getElementById("table_aligne_enseignant");
    var rows = table_aligne_enseignant.getElementsByTagName('tr');  
    for(var j = 0; j < rows.length; j++) 
    {
      if(j!=0) rows[j].style.backgroundColor = '';
    }
    tr1.style.backgroundColor = 'red';
    tr_selectionner=tr1;
    Affichage_ECs();
    

  }
  // *********** FIN AFFICHAGE DE UE **************************

  /*
  *****  LA METHODE POUR AJOUTER LES ECS
  */





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


  function Ouvrir_Boite_Alert_EC_Aligne(text_a_afficher)
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
        Ouvrir_Boite_Alert_EC_Aligne("Action annulée  !");  

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
         Ouvrir_Boite_Alert_EC_Aligne("EC Supprimée avec succè !");  
         // Puis on fait l'initialisation du tableau
         Recuperation_ECs(code_ue_ec,tr_selectionner);
       } 
       
       else
       {
         // La suppression a échoué
         Ouvrir_Boite_Alert_EC_Aligne("Impossible de supprimer cette UE  !");  
       }
     };
     
     // Envoi de la requête avec les données nécessaires
     xhr.send("code_ec="+code_EC);
   } 
   
 
 
 /*********************************FIN SUPPRESSION UE ******************************************* */
} 