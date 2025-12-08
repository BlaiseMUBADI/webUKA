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

  let tr_="";
  let mat_agent_="";
  let mat_assistant_=""; // Matricule de l'assistant sélectionné
  let verfi_=true;
  let enseignantSelected = false; // Flag pour vérifier si un enseignant est sélectionné



  // Les éléments du DOM sont initialisés seulement si la page contient
  // l'élément parent `div_gen_Aligne_Enseignant`. Cela évite que ce script lance des
  // getElementById() au top-level et retourne `null` quand il est inclus
  // sur d'autres pages.
  let cmb_semestre_alignre;
  let cmb_promotion_FAC;
  let cmb_annee_academique_aligne;

  document.addEventListener("DOMContentLoaded",function(event)
  {
    const container = document.getElementById("div_gen_Aligne_Enseignant");
    if(container !== null)
    {
      // initialiser les éléments en utilisant le conteneur pour éviter
      // toute sélection hors-contexte lorsque ce script est inclus sur
      // d'autres pages
      cmb_semestre_alignre = container.querySelector('#id_semestre') || document.getElementById('id_semestre');
      cmb_promotion_FAC = container.querySelector('#code_prom_Align_EC') || document.getElementById('code_prom_Align_EC');
      cmb_annee_academique_aligne = container.querySelector('#id_fac_annee') || document.getElementById('id_fac_annee');

      if(cmb_semestre_alignre !== null)
      {
        cmb_semestre_alignre.addEventListener('change',(event)=> 
          {
            Affichage_ECs_Par_Filiere();
            
          });

          if (cmb_annee_academique_aligne !== null) {
            cmb_annee_academique_aligne.addEventListener('change',(event)=> 
              {
                Affichage_ECs_Par_Filiere();
                
              });
          }

          if (cmb_promotion_FAC !== null) {
            cmb_promotion_FAC.addEventListener('change',(event)=> 
              {
                Affichage_ECs_Par_Filiere();
                
              });
          }
      }
      Affichage_Enseignant_Aligner();
      Affichage_ECs_Par_Filiere();
      Charger_Assistants_();

    } 
  })

  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES UEs D'UNE FILIERE ********************
  *****************************************************************************************
  */

  function Affichage_Enseignant_Aligner() {
    let table_aligne_enseignant = document.getElementById("table_aligne_enseignant");
    let table_aligne_assistant = document.getElementById("table_aligne_assistant");
    
    // NE PAS SUPPRIMER LE THEAD - Seulement vider le tbody
    let tbody = table_aligne_enseignant.querySelector("tbody");
    if (!tbody) {
        tbody = document.createElement("tbody");
        table_aligne_enseignant.appendChild(tbody);
    }
    
    // Vider uniquement le tbody
    tbody.innerHTML = "";

    // Préparer aussi le tableau des assistants (si présent)
    let tbodyAssist = null;
    if (table_aligne_assistant) {
      tbodyAssist = table_aligne_assistant.querySelector("tbody");
      if (!tbodyAssist) {
        tbodyAssist = document.createElement("tbody");
        table_aligne_assistant.appendChild(tbodyAssist);
      }
      tbodyAssist.innerHTML = ""; // vider
    }

    var url = 'API_PHP/Liste_Enseignants.php';

    var i = 1;
    fetch(url)
        .then(response => response.json())
        .then(response => {
            // Extraire l'ID de la filière de l'utilisateur et la liste des enseignants
            const idFiliereUser = response.id_filiere_user;
            const data = response.enseignants;
            
            data.forEach(infos => {
                // Création de TR
                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                tdnum.textContent = i;
                tdnum.classList.add("text-center");

                var td_enseignant = document.createElement("td");
                td_enseignant.classList.add("text-center", "w-auto");
                var td_domaine = document.createElement("td");
                var td_titre_academique = document.createElement("td");

                td_enseignant.textContent = infos.enseignant;
                td_titre_academique.textContent = infos.titre_academique;
                td_domaine.textContent = infos.domaine;

                tr.appendChild(tdnum);
                tr.appendChild(td_enseignant);
                tr.appendChild(td_titre_academique);
                tr.appendChild(td_domaine);

                tbody.appendChild(tr);

                // Ajouter l'événement de clic pour afficher les infos de la ligne
                tr.addEventListener("click", function () {
                  mat_agent_=infos.mat_agent;
                  mat_assistant_=""; // Réinitialiser l'assistant quand on sélectionne un enseignant
                    Selectionner_Enseignant(tr, 'enseignant');
                    Affichage_ECs_Par_Filiere() ;
                });
                
                // Ajouter l'événement de clic droit pour afficher le menu contextuel
                tr.addEventListener("contextmenu", function (event) {
                  afficherMenuContextuel(event, infos);
                 
                });
                
                i++;
            });
            
            // Mettre à jour le badge avec le nombre total d'agents (enseignants + assistants)
            const badgeEnseignants = document.getElementById('badge_enseignants');
            if (badgeEnseignants) {
                // Compter tous les agents de la filière vs total université
                let agentsFiliere = data.filter(e => e.id_filiere == idFiliereUser);                
                badgeEnseignants.textContent = agentsFiliere.length + ' / ' + data.length;
            }






            // ====== Remplissage du tableau des assistants via nouvelle API ======
            // Au chargement initial, afficher tous les assistants ASS1/ASS2
            if (table_aligne_assistant && tbodyAssist) 
            {
              // Mettre à jour uniquement le badge assistants (filière / total)
              const badgeAssistants = document.getElementById('badge_assistants');
              if (badgeAssistants) {
                // Filtrer tous les assistants dont le titre_academique contient 'ASS' (insensible à la casse)
                let assistants = Array.isArray(data) ? data.filter(a => {
                  const titre = (a.titre_academique || '').toString().toUpperCase();
                  return titre.includes('ASS');
                }) : [];
                let assistantsFiliere = assistants.filter(x => x.id_filiere == idFiliereUser);
                badgeAssistants.textContent = assistantsFiliere.length + ' / ' + assistants.length;
              }
            }
        })
        .catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de la récupération des enseignants: " + error);
        });

    // Le tbody est déjà dans la table, pas besoin de l'ajouter
    //table_aligne_enseignant.classList.add("table-striped");
}


/*
*************  AFFICHAGE DE TOUS CES ECS EN TENANT AUSSI COMPTE DES ATTRIBUTIONS **********
*/
function Affichage_ECs_Par_Filiere() 
{
  let table_ecs = document.getElementById("table_aligne_EC");
  
  // NE PAS SUPPRIMER LE THEAD - Seulement vider le tbody
  let tbody = table_ecs.querySelector("tbody");
  if (!tbody) {
      tbody = document.createElement("tbody");
      table_ecs.appendChild(tbody);
  }
  
  // Vider uniquement le tbody
  tbody.innerHTML = "";
  var i = 1;

  var url = 'API_PHP/Liste_EC_Aligne.php';
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        mat_agent: (mat_agent_ === '' || mat_agent_ === undefined) ? null : mat_agent_,
        id_annee_acad: (cmb_annee_academique_aligne.value === '' || cmb_annee_academique_aligne.value === undefined || cmb_annee_academique_aligne.value === 'rien') ? null : cmb_annee_academique_aligne.value,
        id_semestre: (cmb_semestre_alignre.value === '' || cmb_semestre_alignre.value === undefined || cmb_semestre_alignre.value === 'rien') ? null : cmb_semestre_alignre.value,
        code_prom: (cmb_promotion_FAC.value === '' || cmb_promotion_FAC.value === undefined || cmb_promotion_FAC.value === 'rien') ? null : cmb_promotion_FAC.value
    })
  })
  .then(response => response.json())
  .then(data => 
  {
      data.forEach(ec => {
      var tdnum = document.createElement("td");
        var tr = document.createElement("tr");

        tdnum = document.createElement("td");
        tdnum.textContent = i;
        tdnum.classList.add("text-center");

        var td_intitule = document.createElement("td");
        var td_credits = document.createElement("td");
        var td_action = document.createElement("td");

        td_intitule.textContent = ec.Intutile_ec;
        td_credits.textContent = ec.Credit;


        var div = document.createElement("div");
        div.classList.add("d-flex", "justify-content-center", "align-items-center", "p-0", "m-0");

        var case_cocher = document.createElement("input");
        case_cocher.type = "checkbox";
        case_cocher.classList.add("form-check-input", "m-0");
        case_cocher.classList.add("form-check-input")

        // Lorsque Un EC appartient au SM selectionné et qu'il a appartient à l'enseignant selectionné
        // On donne la possibilité a l'utilisateur de décocher la case
        // et la case est cochée par défaut ( ce qui veu dire que c'est EC est aligné  avec cet enseignant)
        if(ec.etat_ec_pris_sm_envoyer===1 && ec.etat_ec_pris_enseignant===1 )
        {
          case_cocher.disabled=false;
          case_cocher.checked=true;
          
          // Style visuel moderne vert pour EC aligné avec cet enseignant (modifiable)
          tr.style.backgroundColor = '#d1fae5'; // Vert très clair (green-100)
          tr.style.borderLeft = '4px solid #10b981'; // Bordure verte moderne (green-500)
          tr.style.opacity = '1';
          
          // Titre infobulle
          tr.title = `✅ Cet EC est aligné avec vous dans ce semestre. Vous pouvez le décocher pour le retirer.`;
          
          // Ajouter une icône de succès dans l'intitulé
          const iconSuccess = document.createElement('span');
          iconSuccess.textContent = ' ✓';
          iconSuccess.style.color = '#10b981';
          iconSuccess.style.fontWeight = 'bold';
          iconSuccess.style.marginLeft = '8px';
          iconSuccess.title = `Aligné avec vous`;
          td_intitule.appendChild(iconSuccess);
        }

        // Lorsque Un EC appartient au SM selectionné et qu'il n'a pas appartient à l'enseignant selectionné
        // Ce qui veut dire que cet EC est aligné avec un autre enseignant dans cette promotion 
        // pour cette année académique et ce semestre
        // On doit bloquer la case à cocher et la case doite etre cochée par défaut
        if((ec.etat_ec_pris_sm_envoyer===1 && ec.etat_ec_pris_enseignant!==1 ))
        {
            case_cocher.disabled=true;
            case_cocher.checked=true;
            
            // Style visuel moderne orange pour EC pris par un autre enseignant dans CE semestre
            tr.style.backgroundColor = '#fef3c7'; // Orange très clair (amber-100)
            tr.style.borderLeft = '4px solid #f59e0b'; // Bordure orange moderne (amber-500)
            tr.style.opacity = '0.85';
            
            // Titre infobulle avec nom complet de l'agent
            const semestreActuel = cmb_semestre_alignre.value || 'actuel';
            const titreAgent = ec.Titre_Academique_Agent || '';
            const nomCompletAgent = ec.Nom_Complet_Agent || ec.Mat_agent || 'un autre enseignant';
            const agentComplet = titreAgent ? `${titreAgent}. ${nomCompletAgent}` : nomCompletAgent;
            tr.title = `⚠️ Cet EC est déjà aligné dans ce semestre (S${semestreActuel}) avec ${agentComplet}`;
            
            // Ajouter une icône d'avertissement dans l'intitulé
            const iconWarning = document.createElement('span');
            iconWarning.textContent = ' ⚠️';
            iconWarning.style.color = '#f59e0b';
            iconWarning.style.fontWeight = 'bold';
            iconWarning.style.marginLeft = '8px';
            iconWarning.title = `Pris par ${agentComplet}`;
            td_intitule.appendChild(iconWarning);
        }
        
        // Lorsque Un EC n'appartient pas au SM selectionné 
        // Mais il est aligné dans cette promotion pour cette année académique dans un autre semestre
        // On doit bloquer la case et appliquer un style visuel mauve moderne

        if((ec.etat_ec_pris_sm_envoyer!==1 && ec.etat_ec_pris_dans_promotion ===1 ))
        {
            case_cocher.disabled=true;
            case_cocher.checked=true;
            
            // Style visuel moderne mauve pour EC déjà pris dans un autre semestre de cette promotion
            tr.style.backgroundColor = '#f3e8ff'; // Mauve très clair (purple-100)
            tr.style.borderLeft = '4px solid #a855f7'; // Bordure mauve moderne (purple-500)
            tr.style.opacity = '0.85';
            
            // Titre infobulle avec nom complet de l'agent
            const semestreAligne = ec.Id_Semestre || 'inconnu';
            const titreAgent = ec.Titre_Academique_Agent || '';
            const nomCompletAgent = ec.Nom_Complet_Agent || ec.Mat_agent || 'un enseignant';
            const agentComplet = titreAgent ? `${titreAgent}. ${nomCompletAgent}` : nomCompletAgent;
            tr.title = `⚠️ Cet EC est déjà aligné dans le Semestre ${semestreAligne} de cette promotion et attribué à ${agentComplet}`;
            
            // Ajouter une icône d'avertissement dans l'intitulé
            const iconWarning = document.createElement('span');
            iconWarning.textContent = ' 🔒';
            iconWarning.style.color = '#a855f7';
            iconWarning.style.fontWeight = 'bold';
            iconWarning.style.marginLeft = '8px';
            iconWarning.title = `Déjà Aligné en S${semestreAligne} par ${agentComplet}`;
            td_intitule.appendChild(iconWarning);
        }

        // Lorsque Un EC appartient à l'année académique sélectionnée
        // Donc il est aligné dans une autre promotion (cours cyclique)
        // L'EC est disponible pour être aligné dans cette promotion

        if((ec.etat_ec_pris_dans_annee===1 && ec.etat_ec_pris_sm_envoyer!==1 && ec.etat_ec_pris_dans_promotion!==1 ))
        {
            case_cocher.disabled=false;
            case_cocher.checked=false;
            
            // Style visuel moderne bleu pour EC utilisé dans une autre promotion (disponible ici)
            tr.style.backgroundColor = '#dbeafe'; // Bleu très clair (blue-100)
            tr.style.borderLeft = '4px solid #3b82f6'; // Bordure bleue moderne (blue-500)
            tr.style.opacity = '0.90';
            
            // Titre infobulle avec nom complet de l'agent
            const semestreAligne = ec.Id_Semestre || 'inconnu';
            const titreAgent = ec.Titre_Academique_Agent || '';
            const nomCompletAgent = ec.Nom_Complet_Agent || ec.Mat_agent || 'un enseignant';
            const agentComplet = titreAgent ? `${titreAgent}. ${nomCompletAgent}` : nomCompletAgent;
            tr.title = `ℹ️ Cet EC est utilisé dans une autre promotion (Semestre ${semestreAligne}) par ${agentComplet}. Il est disponible pour cette promotion.`;
            
            // Ajouter une icône d'information dans l'intitulé
            const iconInfo = document.createElement('span');
            iconInfo.textContent = ' ℹ️';
            iconInfo.style.color = '#3b82f6';
            iconInfo.style.fontWeight = 'bold';
            iconInfo.style.marginLeft = '8px';
            iconInfo.title = `Cours cyclique - Disponible`;
            td_intitule.appendChild(iconInfo);
        }

        //if(ec.etat_ec_pris_enseignant === 1)case_cocher.checked=true;
        
        
        
        // Ajouter l'événement pour ajouter ou supprimer EC aligné
        case_cocher.addEventListener('change', function(e) 
        {
          // Vérifier qu'un enseignant est sélectionné et que tous les filtres sont remplis
          if (!mat_agent_ || 
              !cmb_annee_academique_aligne || !cmb_annee_academique_aligne.value || 
              !cmb_semestre_alignre || !cmb_semestre_alignre.value || cmb_semestre_alignre.value === 'rien' ||
              !cmb_promotion_FAC || !cmb_promotion_FAC.value || cmb_promotion_FAC.value === 'rien') {
            
            // Bloquer l'action et remettre la checkbox à son état précédent
            e.preventDefault();
            case_cocher.checked = !case_cocher.checked;
            
            // Afficher la boîte de dialogue d'alerte
            const dialog = document.getElementById('boite_alert_SM_EC');
            const textAlert = document.getElementById('text_alert_boite_EC');
            
            if (dialog && textAlert) {
              let messagesManquants = [];
              if (!mat_agent_) messagesManquants.push('<strong>Enseignant</strong>');
              if (!cmb_annee_academique_aligne.value) messagesManquants.push('<strong>Année Académique</strong>');
              if (!cmb_semestre_alignre.value || cmb_semestre_alignre.value === 'rien') messagesManquants.push('<strong>Semestre</strong>');
              if (!cmb_promotion_FAC.value || cmb_promotion_FAC.value === 'rien') messagesManquants.push('<strong>Promotion</strong>');
              
              textAlert.innerHTML = '⚠️ <strong>ATTENTION!</strong><br><br>Veuillez sélectionner les éléments suivants avant de cocher un EC:<br><br>' + messagesManquants.join(', ');
              dialog.showModal();
            }
            
            return;
          }
          
          if (case_cocher.checked) {
              Ajouter_EC_Aligne(ec.id_ec); 
              Affichage_ECs_Par_Filiere();
          } else {
              Supprimer_EC_Aligne(ec.id_ec);
              Affichage_ECs_Par_Filiere();

          }
        });


        div.appendChild(case_cocher);
        td_action.appendChild(div);         
        
        tr.appendChild(tdnum);          
        tr.appendChild(td_action);
        tr.appendChild(td_intitule);
        tr.appendChild(td_credits);

        // Ajouter gestionnaire de clic pour sélection EC (active les assistants)
        tr.addEventListener('click', function(e) {
          // Ne pas interférer avec le clic sur checkbox
          if (e.target === case_cocher) return;
          
          // Retirer la classe de sélection et les icônes des autres lignes EC
          tbody.querySelectorAll('tr.ec-selected').forEach(r => {
            r.classList.remove('ec-selected');
            // Retirer l'icône si présente
            const icon = r.querySelector('.ec-selected-icon');
            if (icon) icon.remove();
          });
          
          // Ajouter la classe à cette ligne
          tr.classList.add('ec-selected');
          
          // Ajouter l'icône checkmark à l'intitulé
          if (!td_intitule.querySelector('.ec-selected-icon')) {
            const icon = document.createElement('span');
            icon.className = 'ec-selected-icon';
            icon.textContent = '✓';
            td_intitule.appendChild(icon);
          }
          
          // Stocker l'ID de l'EC sélectionné et son id_ec_aligne
          window.id_ec_selectionner = ec.id_ec;
          window.id_ec_aligne_selectionner = ec.id_ec_aligne; // ID de la ligne d'alignement
          
          // Recharger les assistants en mode EC
          //Charger_Assistants_(true);
        });

        tbody.appendChild(tr);
        i++;
    });
  })
  .catch((error) => 
  {
    console.log("Erreur lors de la récupération des ECs: " + error);
  });
  
  // Le tbody est déjà dans la table, pas besoin de l'ajouter
  table_ecs.classList.add("table-striped");
}



/*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET DE SÉLECTIONNER UN ENSEIGNANT OU ASSISTANT **********
  *****************************************************************************************
  */
  function Selectionner_Enseignant(tr1, type = 'enseignant')
  {
    var table_aligne_enseignant = document.getElementById("table_aligne_enseignant");
    var table_aligne_assistant = document.getElementById("table_aligne_assistant");
    
    if (type === 'enseignant') 
    {
      // Si on sélectionne un enseignant, désélectionner tous les enseignants et tous les assistants
      if (table_aligne_enseignant) {
        var rowsEnseignant = table_aligne_enseignant.querySelectorAll('tbody tr');  
        rowsEnseignant.forEach(row => row.classList.remove('selected'));
      }
      
      if (table_aligne_assistant) {
        var rowsAssistant = table_aligne_assistant.querySelectorAll('tbody tr');  
        rowsAssistant.forEach(row => row.classList.remove('selected'));
      }
      
      enseignantSelected = true;
    } 
    else if (type === 'assistant') 
    {
      // Si on sélectionne un assistant, désélectionner uniquement les autres assistants
      // (garder l'enseignant sélectionné)
      if (table_aligne_assistant) {
        var rowsAssistant = table_aligne_assistant.querySelectorAll('tbody tr');  
        rowsAssistant.forEach(row => row.classList.remove('selected'));
      }
      
    }
    
    // Ajouter la classe 'selected' à la ligne cliquée
    tr1.classList.add('selected');
    tr_selectionner = tr1;
    
    // Si un enseignant est sélectionné, charger les assistants disponibles avec checkboxes
    if (type === 'enseignant') {
      Charger_Assistants_();
    }
  }
  //

  
  /*
  *****************************************************************************************
  ************  CHARGER LES ASSISTANTS DISPONIBLES AVEC CHECKBOXES ************************
  *****************************************************************************************
  */
  function Charger_Assistants_() {
    const table_aligne_assistant = document.getElementById("table_aligne_assistant");
    if (!table_aligne_assistant) return;
    
    let tbodyAssist = table_aligne_assistant.querySelector("tbody");
    if (!tbodyAssist) {
      tbodyAssist = document.createElement("tbody");
      table_aligne_assistant.appendChild(tbodyAssist);
    }

    /*// Vérifier que les filtres sont sélectionnés
    if (!cmb_annee_academique_aligne || !cmb_annee_academique_aligne.value ||
        !cmb_semestre_alignre || !cmb_semestre_alignre.value || cmb_semestre_alignre.value === 'rien' ||
        !cmb_promotion_FAC || !cmb_promotion_FAC.value || cmb_promotion_FAC.value === 'rien') {
      console.log("Filtres non complets, impossible de charger les assistants");
      return;
    }*/

    // Déterminer l'ID d'EC aligné sélectionné
    const id_ec_aligne_selectionner = (window.id_ec_aligne_selectionner && window.id_ec_aligne_selectionner !== '' && window.id_ec_aligne_selectionner !== 'rien') ? window.id_ec_aligne_selectionner : null;
    const modeEC = id_ec_aligne_selectionner ? true : false;
    const id_ec_aligner = id_ec_aligne_selectionner;

    const url = 'API_PHP/Liste_Assistants_Disponibles.php';
    fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        mat_agent: mat_agent_,
        id_ec_aligne: id_ec_aligner,
        id_annee_acad: (!cmb_annee_academique_aligne || !cmb_annee_academique_aligne.value || cmb_annee_academique_aligne.value === 'rien') ? null : cmb_annee_academique_aligne.value,
        id_semestre: (!cmb_semestre_alignre || !cmb_semestre_alignre.value || cmb_semestre_alignre.value === 'rien') ? null : cmb_semestre_alignre.value,
        code_prom: (!cmb_promotion_FAC || !cmb_promotion_FAC.value || cmb_promotion_FAC.value === 'rien') ? null : cmb_promotion_FAC.value
      })
    })
    .then(response => response.json())
    .then(assistants => {
      if (assistants.status === 'error') 
      {
        console.error('Erreur API:', assistants.message);
        return;
      }
      tbodyAssist.innerHTML = '';


      assistants.forEach((a, j) => {
        const tr = document.createElement('tr');

        // Style selon ordre_tri
        let couleurBordure = '#6c757d';
        let styleBordure = 'solid';
        let opacite = 1;
        switch (a.ordre_tri) {
          case 1: couleurBordure = '#10b981'; styleBordure = 'solid'; break;
          case 2: couleurBordure = '#10b981'; styleBordure = 'solid'; break;
          case 3: couleurBordure = '#f59e0b'; styleBordure = 'dashed'; opacite = 0.6; break;
          case 4: couleurBordure = '#3b82f6'; styleBordure = 'solid'; break;
          case 5: couleurBordure = '#9ca3af'; styleBordure = 'solid'; opacite = 0.8; break;
          case 6: couleurBordure = '#6c757d'; styleBordure = 'dotted'; opacite = 0.4; break;
          case 7: couleurBordure = '#e5e7eb'; styleBordure = 'dotted'; opacite = 0.3; break;
        }
        tr.style.borderLeft = `4px ${styleBordure} ${couleurBordure}`;
        tr.style.opacity = opacite;
        tr.title = `Statut: ${a.titre_academique}\nAffecté à cet enseignant: ${a.est_assigne_a_cet_enseignant ? 'Oui' : 'Non'}\nAffecté à cet EC: ${a.est_attache_a_cet_ec ? 'Oui' : 'Non'}\nAffecté globalement: ${a.est_assigne_globalement ? 'Oui' : 'Non'}\nEC avec enseignant: ${a.nombre_ecs_avec_enseignant}\nEC total: ${a.nombre_ecs_total}`;

        // N°
        const tdnum = document.createElement('td');
        tdnum.textContent = j + 1;
        tdnum.classList.add('text-center');

        // Nom complet
        const tdAssistant = document.createElement('td');
        tdAssistant.classList.add('text-center', 'w-auto');
        tdAssistant.textContent = a.nom_complet || `${a.Nom_agent || ''} ${a.Post_agent || ''} ${a.Prenom || ''}`.trim();

        // Titre académique
        const tdTitre = document.createElement('td');
        tdTitre.textContent = a.titre_academique || '-';

        // Domaine
        const tdDomaine = document.createElement('td');
        tdDomaine.textContent = a.Domaine || '-';

        // Nombre EC avec enseignant
        const tdECEns = document.createElement('td');
        tdECEns.textContent = a.nombre_ecs_avec_enseignant || 0;

        // Nombre EC total
        const tdECTotal = document.createElement('td');
        tdECTotal.textContent = a.nombre_ecs_total || 0;

        // Statut
        const tdStatut = document.createElement('td');
        let statut = '';
        if (a.est_attache_a_cet_ec) statut = 'Affecté à cet EC';
        else if (a.est_assigne_a_cet_enseignant) statut = 'Affecté à cet enseignant';
        else if (a.est_assigne_globalement) statut = 'Pris';
        else statut = 'Libre';
        tdStatut.textContent = statut;

        // Action (checkbox)
        const tdAction = document.createElement('td');
        tdAction.classList.add('text-center');
        const divCheck = document.createElement('div');
        divCheck.classList.add('d-flex', 'justify-content-center', 'align-items-center');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('form-check-input', 'm-0');
        checkbox.dataset.matAssistant = a.mat_assistant;
        // Gérer l'état selon le mode
        if (modeEC) {
          if (a.est_assigne_globalement && !a.est_attache_a_cet_ec) {
            checkbox.disabled = true;
          } else {
            checkbox.disabled = false;
          }
          if (a.est_attache_a_cet_ec) {
            checkbox.checked = true;
          }
        } else {
          checkbox.disabled = true;
          if (a.est_assigne_a_cet_enseignant) {
            checkbox.checked = true;
          }
        }
        checkbox.addEventListener('change', function(e) {
          if (!modeEC) {
            e.preventDefault();
            return;
          }
          Attacher_ou_Detacher_Assistant(window.id_ec_aligne_selectionner, a.mat_assistant, e.target.checked);
        });
        divCheck.appendChild(checkbox);
        tdAction.appendChild(divCheck);

        tr.appendChild(tdnum);
        tr.appendChild(tdAssistant);
        tr.appendChild(tdTitre);
        tr.appendChild(tdDomaine);
        tr.appendChild(tdECEns);
        tr.appendChild(tdECTotal);
        tr.appendChild(tdStatut);
        tr.appendChild(tdAction);

        // Menu contextuel
        tr.addEventListener('contextmenu', function(event) {
          afficherMenuContextuel(event, a);
        });

        tbodyAssist.appendChild(tr);
      });
      // Badge assistants
      const badgeAssistants = document.getElementById('badge_assistants');
      if (badgeAssistants) {
        if (modeEC) {
          const attachesEC = assistants.filter(a => a.est_attache_a_cet_ec).length;
          badgeAssistants.textContent = `${attachesEC} EC`;
        } else {
          const assignes = assistants.filter(a => a.est_assigne_a_cet_enseignant).length;
          badgeAssistants.textContent = `${assignes} / ${assistants.length}`;
        }
      }
    })
    .catch(error => {
      console.error('Erreur lors du chargement des assistants:', error);
    });
  }
  
  



  /*
  *****************************************************************************************
  ************  ATTACHER OU DÉTACHER UN ASSISTANT À UN EC *********************************
  *****************************************************************************************
  */
  function Attacher_ou_Detacher_Assistant(id_ec_aligne, mat_assistant, attacher) {
    const url = 'API_PHP/Attacher_Assistant_EC.php';
    
    fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id_ec_aligne: id_ec_aligne,
        mat_assistant: attacher ? mat_assistant : null, // null pour détacher
        id_annee_acad: cmb_annee_academique_aligne.value,
        id_semestre: cmb_semestre_alignre.value,
        code_prom: cmb_promotion_FAC.value
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        console.log(attacher ? 'Assistant attaché' : 'Assistant détaché');
        // Recharger les assistants et les ECs pour refléter les changements
        Charger_Assistants_(true);
        Affichage_ECs_Par_Filiere();
      } else {
        console.error('Erreur:', data.message);
        alert('Erreur: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Erreur lors de l\'attachement/détachement:', error);
      alert('Erreur de communication avec le serveur');
    });
  }
  
  /*
  *****************************************************************************************
  ************  CETTE FONCTION D'AJOUTER UN NOUVEL EC  ************************************
  *****************************************************************************************
  */

  function Ajouter_EC_Aligne(ec) 
  {
    
    var url = 'API_PHP/Ajout_EC_Aligne.php';

    const data = {
      idAnnee_Acad: (cmb_annee_academique_aligne.value === '' || cmb_annee_academique_aligne.value === undefined) ? null : cmb_annee_academique_aligne.value,
      id_ec: ec,
      Id_Semestre: (cmb_semestre_alignre.value === '' || cmb_semestre_alignre.value === undefined) ? null : cmb_semestre_alignre.value,
      Code_Promotion: (cmb_promotion_FAC.value === '' || cmb_promotion_FAC.value === undefined) ? null : cmb_promotion_FAC.value,
      Mat_agent: (mat_agent_ === '' || mat_agent_ === undefined) ? null : mat_agent_,
      Mat_assistant: (mat_assistant_ === '' || mat_assistant_ === undefined) ? null : mat_assistant_
    };
    fetch(url, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            console.log(result.message);
        } else {
          console.log('Erreur : ' + result.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        console.log('Erreur lors de l\'ajout de l\'élément constitutif aligné.');
    });

    
}


  /******************************************************************** */



  function Supprimer_EC_Aligne(ec) {
    var url = 'API_PHP/Supprimer_EC_Aligner.php';

    const data = {
      idAnnee_Acad: (cmb_annee_academique_aligne.value === '' || cmb_annee_academique_aligne.value === undefined) ? null : cmb_annee_academique_aligne.value,
      id_ec: ec,
      Id_Semestre: (cmb_semestre_alignre.value === '' || cmb_semestre_alignre.value === undefined) ? null : cmb_semestre_alignre.value,
      Code_Promotion: (cmb_promotion_FAC.value === '' || cmb_promotion_FAC.value === undefined) ? null : cmb_promotion_FAC.value,
      Mat_agent: (mat_agent_ === '' || mat_agent_ === undefined) ? null : mat_agent_,
      Mat_assistant: (mat_assistant_ === '' || mat_assistant_ === undefined) ? null : mat_assistant_
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
          console.log(result.message);
        } else {
          console.log('Erreur : ' + result.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        console.log('Erreur lors de la suppression de l\'élément constitutif aligné.');
    });
}

/*
*********************************************************************************************
* ******************** Menu contextuel et affichage des infos enseignant *******************
*********************************************************************************************
*/

let contextMenu;
let selectedEnseignant = null;

// Initialiser le menu contextuel une fois le DOM chargé
document.addEventListener("DOMContentLoaded", function() {
  contextMenu = document.getElementById('contextMenu');
  
  // Masquer le menu contextuel lors d'un clic ailleurs
  document.addEventListener('click', function(e) {
    if (contextMenu && !contextMenu.contains(e.target)) {
      contextMenu.style.display = 'none';
    }
  });
  
  // Empêcher le menu contextuel du navigateur sur les tableaux
  const tableEnseignant = document.getElementById('table_aligne_enseignant');
  if (tableEnseignant) {
    tableEnseignant.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  }
  
  const tableAssistant = document.getElementById('table_aligne_assistant');
  if (tableAssistant) {
    tableAssistant.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  }
});

// Fonction pour afficher le menu contextuel
function afficherMenuContextuel(event, enseignantData) 
{
  event.preventDefault();
  event.stopPropagation();
  
  selectedEnseignant = enseignantData;
  if (contextMenu) {
    contextMenu.style.display = 'block';
    contextMenu.style.left = event.pageX + 'px';
    contextMenu.style.top = event.pageY + 'px';
  }
}

// Fonction pour afficher les informations de l'enseignant
function afficherInfosEnseignant() 
{
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  
  // Remplir les champs de la boîte de dialogue avec les données déjà disponibles
  document.getElementById('info_mat_agent').textContent = selectedEnseignant.mat_agent || '-';
  document.getElementById('info_nom_complet').textContent = selectedEnseignant.enseignant || '-';
  document.getElementById('info_sexe').textContent = selectedEnseignant.sexe === 'M' ? 'Masculin' : (selectedEnseignant.sexe === 'F' ? 'Féminin' : '-');
  document.getElementById('info_telephone').textContent = selectedEnseignant.phone || '-';
  document.getElementById('info_email').textContent = selectedEnseignant.email || '-';
  document.getElementById('info_adresse').textContent = selectedEnseignant.adresse || '-';
  document.getElementById('info_titre_academique').textContent = selectedEnseignant.titre_academique || '-';
  document.getElementById('info_domaine').textContent = selectedEnseignant.domaine || '-';
  document.getElementById('info_categorie').textContent = selectedEnseignant.categorie || '-';
  document.getElementById('info_niveau_etude').textContent = selectedEnseignant.niveau_etude || '-';
  document.getElementById('info_institut_attache').textContent = selectedEnseignant.institut_attache || '-';
  document.getElementById('info_filiere').textContent = selectedEnseignant.filiere || '-';
  
  // Afficher la boîte de dialogue
  const dialog = document.getElementById('boite_Infos_Enseignant');
  if (dialog) {
    dialog.showModal();
  }
}

// Fonction pour modifier un enseignant (à développer)
function modifierEnseignant() {
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  console.log('✏️ Modification de l\'enseignant:', selectedEnseignant.enseignant);
  console.log('📋 Données:', selectedEnseignant);
  console.log('⚠️ Fonctionnalité à développer: Modification des données de l\'enseignant');
}

// Fonction pour afficher l'historique des cours (à développer)
function afficherHistoriqueCours() {
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  console.log('📚 Affichage de l\'historique des cours pour:', selectedEnseignant.enseignant);
  console.log('📋 Matricule:', selectedEnseignant.mat_agent);
  console.log('⚠️ Fonctionnalité à développer: Historique des cours attribués');
  
  // TODO: Implémenter la fonctionnalité d'historique des cours
}

// Fonction pour attribuer un nouveau cours (à développer)
function attribuerNouveauCours() {
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  console.log('➕ Attribution d\'un nouveau cours à:', selectedEnseignant.enseignant);
  console.log('📋 Matricule:', selectedEnseignant.mat_agent);
  console.log('⚠️ Fonctionnalité à développer: Attribution de cours');
  
  // TODO: Implémenter la fonctionnalité d'attribution de cours
}

// Fonction pour générer la fiche de l'enseignant en PDF (à développer)
function genererFicheEnseignant() {
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  console.log('📄 Génération de la fiche PDF pour:', selectedEnseignant.enseignant);
  console.log('📋 Données complètes:', selectedEnseignant);
  console.log('⚠️ Fonctionnalité à développer: Génération de fiche PDF');
  
  // TODO: Implémenter la génération de PDF
}

// Fonction pour envoyer un email à l'enseignant (à développer)
function envoyerEmailEnseignant() {
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  console.log('📧 Envoi d\'email à:', selectedEnseignant.enseignant);
  console.log('📧 Email:', selectedEnseignant.email);
  console.log('⚠️ Fonctionnalité à développer: Envoi d\'email à l\'enseignant');
  
  // TODO: Implémenter l'envoi d'email
}

// Fonction utilitaire pour formater les dates
function formatDate(dateString) {
  if (!dateString) return '-';
  
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return date.toLocaleDateString('fr-FR', options);
}

// Fonction pour fermer la boîte d'alerte
function Fermer_Boite_Alert_SM_EC() {
  const dialog = document.getElementById('boite_alert_SM_EC');
  if (dialog) {
    dialog.close();
  }
}

/*
*****************************************************************************************
************  FONCTIONNALITÉ DE RECHERCHE DANS LES TABLEAUX **************************
*****************************************************************************************
*/

// Fonction utilitaire pour normaliser les chaînes (enlever accents et mettre en minuscules)
function normalizeString(str) {
  if (!str) return '';
  return str.toString()
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");
}

// Fonction générique de filtrage de tableau
function filterTable(searchInputId, tableId) {
  const searchInput = document.getElementById(searchInputId);
  const table = document.getElementById(tableId);
  
  // Ne rien faire si les éléments n'existent pas (on n'est pas sur la bonne page)
  if (!searchInput || !table) {
    return;
  }

  searchInput.addEventListener('input', function() {
    const searchTerm = normalizeString(this.value);
    const tbody = table.querySelector('tbody');
    
    if (!tbody) {
      return;
    }

    const rows = tbody.querySelectorAll('tr');
    let visibleCount = 0;

    rows.forEach(row => {
      // Récupérer tout le texte de la ligne
      const rowText = normalizeString(row.textContent);
      
      if (rowText.includes(searchTerm)) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    // Log pour debug (optionnel)
    console.log(`🔍 Recherche dans ${tableId}: "${this.value}" - ${visibleCount} résultats`);
  });
}

// Initialisation des filtres de recherche au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
  // Vérifier d'abord si on est sur la page d'alignement des ECs
  const container = document.getElementById("div_gen_Aligne_Enseignant");
  if (!container) {
    // Pas sur la bonne page, ne rien faire
    return;
  }
  
  // Attendre un peu que les tableaux soient chargés
  setTimeout(() => {
    // Filtrage pour le tableau des enseignants
    filterTable('search_enseignant', 'table_aligne_enseignant');
    
    // Filtrage pour le tableau des ECs
    filterTable('search_ec', 'table_aligne_EC');
    
    // Filtrage pour le tableau des assistants
    filterTable('search_assistant', 'table_aligne_assistant');
    
    console.log('✅ Filtres de recherche initialisés pour les 3 tableaux');
  }, 500);
});