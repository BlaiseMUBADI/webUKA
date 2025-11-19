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
  let verfi_=true;

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

    } 
  })

  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES UEs D'UNE FILIERE ********************
  *****************************************************************************************
  */

  function Affichage_Enseignant_Aligner() {
    let table_aligne_enseignant = document.getElementById("table_aligne_enseignant");
    
    // NE PAS SUPPRIMER LE THEAD - Seulement vider le tbody
    let tbody = table_aligne_enseignant.querySelector("tbody");
    if (!tbody) {
        tbody = document.createElement("tbody");
        table_aligne_enseignant.appendChild(tbody);
    }
    
    // Vider uniquement le tbody
    tbody.innerHTML = "";

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
                    Selectionner_Enseignant(infos.mat_agent, tr);
                    Affichage_ECs_Par_Filiere() ;
                });
                
                // Ajouter l'événement de clic droit pour afficher le menu contextuel
                tr.addEventListener("contextmenu", function (event) {
                  afficherMenuContextuel(event, infos);
                });
                
                i++;
            });
            
            // Mettre à jour le badge avec le nombre d'enseignants de la filière
            const badgeEnseignants = document.getElementById('badge_enseignants');
            if (badgeEnseignants) {
                // Compter les enseignants dont l'id_filiere correspond à la filière de l'utilisateur
                let enseignantsFiliere = data.filter(e => e.id_filiere == idFiliereUser);
                
                badgeEnseignants.textContent = enseignantsFiliere.length + ' / ' + data.length;
                
                // Message console pour information
                console.log(`📊 Statistiques Enseignants:`);
                console.log(`   - Filière actuelle: ${enseignantsFiliere.length}`);
                console.log(`   - Total université: ${data.length}`);
                console.log(`   - ID Filière: ${idFiliereUser}`);
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
        mat_agent: mat_agent_,
        id_annee_acad: cmb_annee_academique_aligne.value,
        id_semestre: cmb_semestre_alignre.value,
        code_prom: cmb_promotion_FAC.value
    })
  })
  .then(response => response.json())
  .then(data => 
  {
      data.forEach(ec => {
        // Création de TR
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

        

       


        if(ec.etat_ec===1 && ec.etat_ec_pris===1 )
        {
          case_cocher.disabled=false;
          case_cocher.checked=true;
        }
        if((ec.etat_ec!==1 && ec.etat_ec_pris===1 ))case_cocher.disabled=true;
        if(ec.etat_ec_pris === 1)case_cocher.checked=true;
        
        
        
        // Ajouter l'événement pour ajouter ou supprimer EC aligné
        case_cocher.addEventListener('change', function() 
        {
          
          if (case_cocher.checked) {
              Ajouter_EC_Aligne(ec.id_ec, mat_agent_); 
              Affichage_ECs_Par_Filiere();
          } else {
              Supprimer_EC_Aligne(ec.id_ec,mat_agent_);
              Affichage_ECs_Par_Filiere();

          }
        });


        div.appendChild(case_cocher);
        td_action.appendChild(div);         
        
        tr.appendChild(tdnum);          
        tr.appendChild(td_action);
        tr.appendChild(td_intitule);
        tr.appendChild(td_credits);

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
  ************  CETTE FONCTION PERMET D'AFFCIHER LES ECs D'UNE UE ********************
  *****************************************************************************************
  */
  function Selectionner_Enseignant(mat_agent,tr1)
  {
    // Ce bout de code permet de faire une selection de ligne en utilisant une classe CSS
    var table_aligne_enseignant= document.getElementById("table_aligne_enseignant");
    var rows = table_aligne_enseignant.querySelectorAll('tbody tr');  
    
    // Retirer la classe 'selected' de toutes les lignes
    rows.forEach(row => row.classList.remove('selected'));
    
    // Ajouter la classe 'selected' à la ligne cliquée
    tr1.classList.add('selected');
    tr_selectionner=tr1;
    

  }
  //

  
  
  /*
  *****************************************************************************************
  ************  CETTE FONCTION D'AJOUTER UN NOUVEL EC  ************************************
  *****************************************************************************************
  */

  function Ajouter_EC_Aligne(ec, mat_agent) 
  {
    //console.log("Je suis dans ajouter")
    var url = 'API_PHP/Ajout_EC_Aligne.php';

    const data = {
        idAnnee_Acad: cmb_annee_academique_aligne.value,
        id_ec: ec,
        Id_Semestre: cmb_semestre_alignre.value,
        Code_Promotion: cmb_promotion_FAC.value,
        Mat_agent: mat_agent
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



  function Supprimer_EC_Aligne(ec, mat_agent) {
    var url = 'API_PHP/Supprimer_EC_Aligner.php';

    const data = {
        idAnnee_Acad: cmb_annee_academique_aligne.value,
        id_ec: ec,
        Id_Semestre: cmb_semestre_alignre.value,
        Code_Promotion: cmb_promotion_FAC.value,
        Mat_agent: mat_agent
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
  
  // Empêcher le menu contextuel du navigateur sur le tableau
  const tableEnseignant = document.getElementById('table_aligne_enseignant');
  if (tableEnseignant) {
    tableEnseignant.addEventListener('contextmenu', function(e) {
      e.preventDefault();
    });
  }
});

// Fonction pour afficher le menu contextuel
function afficherMenuContextuel(event, enseignantData) {
  event.preventDefault();
  event.stopPropagation();
  
  selectedEnseignant = enseignantData;
  
  console.log('🖱️ Menu contextuel ouvert pour l\'enseignant:', enseignantData.enseignant, '- Matricule:', enseignantData.mat_agent);
  
  if (contextMenu) {
    contextMenu.style.display = 'block';
    contextMenu.style.left = event.pageX + 'px';
    contextMenu.style.top = event.pageY + 'px';
  }
}

// Fonction pour afficher les informations de l'enseignant
function afficherInfosEnseignant() {
  if (!selectedEnseignant) {
    console.error('❌ Aucun enseignant sélectionné');
    return;
  }
  
  // Masquer le menu contextuel
  if (contextMenu) {
    contextMenu.style.display = 'none';
  }
  
  console.log('📋 Affichage des informations pour:', selectedEnseignant.enseignant);
  console.log('✅ Données complètes:', selectedEnseignant);
  
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
  
  // TODO: Implémenter la fonctionnalité de modification
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