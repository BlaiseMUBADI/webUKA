console.log(" je suis dans manip Jury")

/*
*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*+++++++++++++++++++ C'est un script qui se charge de la manipulation des comptes agents+++++++++
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*
*/



var mat_agent_selectionne="";
var tr_agent_selectionne=null;
var id_jury_selectionne=null;
var nom_jury_selectionne="";
var promotion_jury_selectionne="";
let boite_alert_G_jury_UE;

//dboite_Action_G_Juryd
document.addEventListener("DOMContentLoaded",function(event)
  {
    const container = document.getElementById("div_gen_Jury");
    if (container !== null) 
    {
      // Initialiser la boîte d'alerte
      boite_alert_G_jury_UE = document.getElementById('boite_alert_g_jury');

      //Affichage_agent();
      Affichage_Jurys();
      Affichage_Membres_Jury(null); // Afficher l'état vide au démarrage

      // Bouton Ajouter Membre
      const btn_ajout_membre = document.getElementById('btn_ajout_membre');
      if (btn_ajout_membre) {
        btn_ajout_membre.addEventListener('click', function() {
          Ouvrir_Boite_Ajout_Membre();
        });
      }

      // Gestion du changement de rôle
      const select_role = document.getElementById('select_role_membre');
      if (select_role) {
        select_role.addEventListener('change', function() {
          Gerer_Affichage_Champs_Membre();
        });
      }

      // Recherche d'agent dans le modal
      const txt_recherche_agent_jury = document.getElementById('txt_recherche_agent_jury');
      if (txt_recherche_agent_jury) {
        txt_recherche_agent_jury.addEventListener('keyup', function() {
          Affichage_Agents_Jury(txt_recherche_agent_jury.value);
        });
      }

      // Actualiser la liste des jurys si une année académique est sélectionnée
      const cmb_annee_academique = container.querySelector('#id_fac_annee') || document.getElementById('id_fac_annee');
      if (cmb_annee_academique) {
        cmb_annee_academique.addEventListener('change', function() {
          if (cmb_annee_academique.value && cmb_annee_academique.value !== 'rien') {
            Affichage_Jurys();
          }
        });
      }

    }
})




function Ouvrir_Boite_Alert_G_Jury(text_a_afficher)
{
    document.getElementById("text_alert_boite").innerText=text_a_afficher;
    boite_alert_G_jury_UE.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Boite_Alert_G_jury() {
  boite_alert_G_jury_UE.close();
}

// Nouvelle boîte de confirmation moderne
var callback_confirmation = null;

function Ouvrir_Boite_Confirmation(message, onConfirm) {
  var boite = document.getElementById('boite_confirmation_jury');
  var texte = document.getElementById('text_confirmation_boite');
  var btnConfirmer = document.getElementById('btn_confirmer');
  
  if (boite && texte && btnConfirmer) {
    // Utiliser innerHTML pour gérer les sauts de ligne
    texte.innerHTML = message.replace(/\n/g, '<br>');
    callback_confirmation = onConfirm;
    
    // Supprimer les anciens événements et attacher le nouveau
    btnConfirmer.onclick = null;
    btnConfirmer.onclick = function() 
    {
      // Sauvegarder le callback avant de fermer
      var callback = callback_confirmation;
      Fermer_Boite_Confirmation();
      if (callback) {
        callback();
      }
    };
    
    boite.showModal();
  } else {
    console.error('Éléments de confirmation manquants:', { boite, texte, btnConfirmer });
  }
}

function Fermer_Boite_Confirmation() {
  var boite = document.getElementById('boite_confirmation_jury');
  if (boite) {
    boite.close();
    callback_confirmation = null;
  }
}


  function Ouvrir_Form_Jury() {
    var dialog = document.getElementById('boite_Form_Jury');
    if (dialog) dialog.showModal();
  }
  function Fermer_Form_Jury() {
    var dialog = document.getElementById('boite_Form_Jury');
    if (dialog) {
      dialog.close();
      
      // Réinitialiser le formulaire
      document.getElementById('jury_nom').value = '';
      document.getElementById('jury_date').value = '';
      document.getElementById('jury_promotion').value = 'rien';
      
      // Réinitialiser le bouton pour l'ajout
      var btnValider = dialog.querySelector('button[onclick*="jury"]');
      if (btnValider) {
        btnValider.onclick = function() {
          Ajouter_Jury();
        };
        btnValider.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>Valider';
      }
      
      // Réinitialiser l'ID du jury sélectionné
      id_jury_selectionne = null;
    }
  }



  function Ajouter_Jury() 
  {
    var nom_jury = document.getElementById('jury_nom')?.value || '';
    var date_jury = document.getElementById('jury_date')?.value || '';
    var code_promotion = document.getElementById('jury_promotion')?.value || '';
    var id_annee_acad = document.getElementById('id_fac_annee')?.value || '';

    if (!nom_jury || !date_jury || !code_promotion || code_promotion === 'rien' || !id_annee_acad) {
      Ouvrir_Boite_Alert_G_Jury('Veuillez remplir tous les champs, sélectionner une promotion et une année académique.');
      return;
    }

    var data = {
      nom_jury: nom_jury,
      date_jury: date_jury,
      code_promotion: code_promotion,
      id_annee_acad: id_annee_acad
    };

    fetch('API_PHP/Ajout_Nouvel_Jury.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(async response => {
      // Debug : afficher la réponse brute
      const text = await response.text();
      let json;
      try {
        json = JSON.parse(text);
      } catch (e) {
        Ouvrir_Boite_Alert_G_Jury('Erreur de parsing JSON : ' + e);
        return;
      }
      if (json.success) {
        Ouvrir_Boite_Alert_G_Jury('Jury ajouté avec succès !');
        Affichage_Jurys();
        Fermer_Form_Jury();
        
      } else {
        Ouvrir_Boite_Alert_G_Jury('Erreur : ' + json.message);
        // Debug : afficher la trace si présente
        if (json.trace) {
          console.error('[AJOUT JURY] Trace erreur PHP :', json.trace);
        }
      }
    })
    .catch(error => {
      console.error('[AJOUT JURY] Erreur de connexion à l’API :', error);
      Ouvrir_Boite_Alert_G_Jury('Erreur de connexion à l’API : ' + error);
    });
  }


  
  function Affichage_Jurys() {
    var tab_jury = document.getElementById("table_jury");
    let tbody = tab_jury.querySelector("tbody");
    if (!tbody) {
      tbody = document.createElement("tbody");
      tab_jury.appendChild(tbody);
    }
    tbody.innerHTML = "";

    // Variable pour garder la ligne sélectionnée
    let tr_selectionner = null;

    var id_annee_acad = document.getElementById('id_fac_annee')?.value || '';
    var url = 'API_PHP/Liste_Jurys.php';
    var i = 1;
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        id_annee_acad: id_annee_acad
      })
    })
      .then(response => response.json())
      .then(data => {
        data.forEach(jury => {
          var tr = document.createElement("tr");
          var tdnum = document.createElement("td");
          tdnum.classList.add("text-center");
          tdnum.style.position = "relative";
          tdnum.style.padding = "8px";
          tdnum.textContent = i;

          var td_libele = document.createElement("td");
          td_libele.textContent = jury.nom_jury;
          td_libele.style.padding = "8px";
          var td_promotion = document.createElement("td");
          td_promotion.textContent = jury.promotion;
          td_promotion.style.padding = "8px";
          var td_date = document.createElement("td");
          td_date.textContent = jury.date_jury;
          td_date.style.padding = "8px";
          
          // Colonne Actions
          var td_actions = document.createElement("td");
          td_actions.style.padding = "8px";
          td_actions.style.whiteSpace = "nowrap";
          
          // Bouton Modifier
          var btnModifier = document.createElement("button");
          btnModifier.className = "btn btn-sm btn-warning me-1";
          btnModifier.style.padding = "4px 10px";
          btnModifier.style.borderRadius = "6px";
          btnModifier.innerHTML = '<i class="fas fa-edit"></i>';
          btnModifier.title = "Modifier";
          btnModifier.onclick = function(e) {
            e.stopPropagation();
            Modifier_Jury(jury.id_jury, jury.nom_jury, jury.date_jury, jury.code_promotion);
          };
          
          // Bouton Supprimer
          var btnSupprimer = document.createElement("button");
          btnSupprimer.className = "btn btn-sm btn-danger";
          btnSupprimer.style.padding = "4px 10px";
          btnSupprimer.style.borderRadius = "6px";
          btnSupprimer.innerHTML = '<i class="fas fa-trash-alt"></i>';
          btnSupprimer.title = "Supprimer";
          btnSupprimer.onclick = function(e) {
            e.stopPropagation();
            Supprimer_Jury(jury.id_jury, jury.nom_jury);
          };
          
          td_actions.appendChild(btnModifier);
          td_actions.appendChild(btnSupprimer);

          tr.appendChild(tdnum);
          tr.appendChild(td_libele);
          tr.appendChild(td_promotion);
          tr.appendChild(td_date);
          tr.appendChild(td_actions);

          // Ajout de l'événement de sélection de ligne
          tr.addEventListener("click", function() {
            // Retirer la sélection sur toutes les lignes
            Array.from(tbody.querySelectorAll("tr")).forEach(row => {
              row.classList.remove("selected");
              let icon = row.querySelector('.fa-check');
              if (icon) icon.remove();
            });
            // Ajouter la sélection sur la ligne cliquée
            tr.classList.add("selected");
            tr_selectionner = tr;
            
            // Stocker les informations du jury sélectionné
            id_jury_selectionne = jury.id_jury;
            nom_jury_selectionne = jury.nom_jury;
            promotion_jury_selectionne = jury.promotion;
            
            // Ajouter une icône sur la première cellule
            let checkIcon = document.createElement("i");
            checkIcon.className = "fas fa-check text-success ms-2";
            checkIcon.style.position = "absolute";
            checkIcon.style.right = "4px";
            checkIcon.style.top = "50%";
            checkIcon.style.transform = "translateY(-50%)";
            tdnum.appendChild(checkIcon);
            
            // Charger les membres de ce jury
            Affichage_Membres_Jury(jury.id_jury);
          });

          tbody.appendChild(tr);
          i++;
        });
      })
      .catch(error => {
        console.log("Erreur lors de la récupération des jurys : " + error);
      });
    tab_jury.classList.add("table-striped");
}


/**********************************************************************************************
******************* GESTION DU MODAL D'AJOUT DE MEMBRE AU JURY ******************************
***********************************************************************************************/

// Ouvrir la boîte de dialogue pour ajouter un membre
function Ouvrir_Boite_Ajout_Membre() {
  // Vérifier qu'un jury est sélectionné
  if (!id_jury_selectionne) {
    Ouvrir_Boite_Alert_G_Jury('Veuillez d\'abord sélectionner un jury dans la liste.');
    return;
  }
  
  if (boite_Ajout_Membre_Jury) {
    // Mettre à jour le titre avec les infos du jury
    const titre_modal = document.querySelector('#boite_Ajout_Membre_Jury h5');
    if (titre_modal) {
      titre_modal.innerHTML = `<i class="fas fa-user-plus"></i> Ajouter un Membre au Jury<br><small style="font-size: 0.85em; font-weight: 500; opacity: 0.9;">${nom_jury_selectionne} - ${promotion_jury_selectionne}</small>`;
    }
    
    boite_Ajout_Membre_Jury.showModal();
    Affichage_Agents_Jury(""); // Charger tous les agents
    Reinitialiser_Form_Membre();
  }
}

// Fermer la boîte de dialogue
function Fermer_Boite_Ajout_Membre() {
  if (boite_Ajout_Membre_Jury) {
    boite_Ajout_Membre_Jury.close();
    Reinitialiser_Form_Membre();
  }
}

// Réinitialiser le formulaire de membre
function Reinitialiser_Form_Membre() {
  mat_agent_selectionne = "";
  tr_agent_selectionne = null;
  document.getElementById('form_config_membre').style.display = 'none';
  document.getElementById('select_role_membre').selectedIndex = 0;
  document.getElementById('txt_login_membre').value = '';
  document.getElementById('txt_password_membre').value = '';
  document.getElementById('txt_password_membre').placeholder = 'Mot de passe';
  document.getElementById('select_statut_compte').selectedIndex = 0;
  
  // Restaurer le bouton d'ajout normal
  var btnAjout = document.getElementById('btn_ajout_membre');
  if (btnAjout) {
    btnAjout.innerHTML = '<i class="fas fa-user-plus me-2"></i>Ajouter un Membre';
    btnAjout.onclick = function() { Ouvrir_Modale_Ajout_Membre(); };
  }
  
  // Réinitialiser l'ID de modification
  id_membre_en_modification = null;
  
  // Retirer la sélection de toutes les lignes
  const table = document.getElementById('table_agents_jury');
  if (table) {
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => row.classList.remove('agent-selected'));
  }
}

// Afficher les agents dans le tableau du modal
function Affichage_Agents_Jury(recherche) {
  var table_agents_jury = document.getElementById("table_agents_jury");
  
  // NE PAS SUPPRIMER LE THEAD - Seulement vider le tbody
  let tbody = table_agents_jury.querySelector("tbody");
  if (!tbody) {
    tbody = document.createElement("tbody");
    table_agents_jury.appendChild(tbody);
  }
  tbody.innerHTML = "";

  var url = 'API_PHP/Liste_Enseignants.php';
  var i = 1;
  
  fetch(url)
    .then(response => response.json())
    .then(data => {
      
      
      // Si les données sont dans une propriété (ex: data.enseignants ou data.data)
      const listeAgents = Array.isArray(data) ? data : (data.enseignants || data.data || []);
      
      listeAgents.forEach(infos => {
        // Filtre de recherche
        if (recherche !== "") {
          const searchLower = recherche.toLowerCase();
          const nomComplet = (infos.enseignant || "").toLowerCase();
          const matricule = (infos.mat_agent || "").toLowerCase();
          
          if (!nomComplet.includes(searchLower) && !matricule.includes(searchLower)) {
            return; // Skip cette ligne
          }
        }

        var tr = document.createElement("tr");
        
        var tdnum = document.createElement("td");
        tdnum.textContent = i;
        tdnum.classList.add("text-center");

        var tdmatricule = document.createElement("td");
        tdmatricule.textContent = infos.mat_agent;

        var tdnom = document.createElement("td");
        tdnom.textContent = infos.enseignant;

        var tdgrade = document.createElement("td");
        tdgrade.textContent = infos.Grade || "-";

        var tdsexe = document.createElement("td");
        tdsexe.textContent = infos.sexe;
        tdsexe.classList.add("text-center");

        tr.appendChild(tdnum);
        tr.appendChild(tdmatricule);
        tr.appendChild(tdnom);
        tr.appendChild(tdgrade);
        tr.appendChild(tdsexe);

        // Événement de clic sur la ligne
        tr.addEventListener("click", function() {
          // Retirer la sélection des autres lignes
          const rows = tbody.querySelectorAll('tr');
          rows.forEach(row => row.classList.remove('agent-selected'));
          
          // Sélectionner cette ligne
          tr.classList.add('agent-selected');
          mat_agent_selectionne = infos.mat_agent;
          tr_agent_selectionne = tr;
          
          // Afficher le formulaire de configuration
          document.getElementById('form_config_membre').style.display = 'block';
          document.getElementById('nom_agent_selectionne').textContent = infos.enseignant;
          
          // Gérer l'affichage des champs
          Gerer_Affichage_Champs_Membre();
        });

        tbody.appendChild(tr);
        i++;
      });
    })
    .catch(error => {
      console.log("Erreur lors du chargement des agents : " + error);
    });
}

// Gérer l'affichage des champs selon le rôle sélectionné
function Gerer_Affichage_Champs_Membre() {
  const role = document.getElementById('select_role_membre').value;
  const zone_credentials = document.getElementById('zone_credentials');
  const zone_statut = document.getElementById('zone_statut_compte');
  
  if (role === 'Président' || role === 'Secrétaire') {
    zone_credentials.style.display = 'block';
    zone_statut.style.display = 'block';
  } else {
    zone_credentials.style.display = 'none';
    zone_statut.style.display = 'none';
  }
}

// Toggle pour afficher/masquer le mot de passe
function Toggle_Password_Visibility() {
  const passwordInput = document.getElementById('txt_password_membre');
  const toggleIcon = document.getElementById('toggle_password_icon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleIcon.classList.remove('fa-eye');
    toggleIcon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
  }
}

// Valider l'ajout du membre au jury
function Ajout_Membre() {
  if (!mat_agent_selectionne) {
    Ouvrir_Boite_Alert_G_Jury('Veuillez sélectionner un agent.');
    return;
  }

  const role = document.getElementById('select_role_membre').value;
  
  // Vérifier que toutes les données nécessaires sont présentes
  if (!id_jury_selectionne || !mat_agent_selectionne || !role) {
    Ouvrir_Boite_Alert_G_Jury('Données manquantes : id_jury, mat_agent et role sont requis.');
    return;
  }

  let login = '';
  let password = '';
  let statut = 'Actif';

  // Si Président ou Secrétaire, récupérer les credentials
  if (role === 'Président' || role === 'Secrétaire') {
    login = document.getElementById('txt_login_membre').value.trim();
    password = document.getElementById('txt_password_membre').value.trim();
    statut = document.getElementById('select_statut_compte').value;

    // Validation obligatoire pour Président et Secrétaire
    if (!login || !password) {
      Ouvrir_Boite_Alert_G_Jury('Le login et le mot de passe sont obligatoires pour les Présidents et Secrétaires.');
      return;
    }

    // Vérifier la longueur minimale du mot de passe
    /*if (password.length < 6) {
      Ouvrir_Boite_Alert_G_Jury('Le mot de passe doit contenir au moins 6 caractères.');
      return;
    }*/
  }

  const data = {
    id_jury: id_jury_selectionne,
    mat_agent: mat_agent_selectionne,
    role: role,
    login: login,
    password: password,
    statut: statut
  };

  // TODO: Créer l'API PHP pour ajouter un membre au jury
  fetch('API_PHP/Ajout_membre_Jury.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      Ouvrir_Boite_Alert_G_Jury('Membre ajouté avec succès !');
      Fermer_Boite_Ajout_Membre();
      // Rafraîchir la liste des membres du jury
      Affichage_Membres_Jury(id_jury_selectionne);
    } else {
      Ouvrir_Boite_Alert_G_Jury('Erreur : ' + result.message);
    }
  })
  .catch(error => {
    console.error('Erreur lors de l\'ajout du membre :', error);
    Ouvrir_Boite_Alert_G_Jury('Erreur de connexion à l\'API.');
  });
}
// Afficher les membres d'un jury
function Affichage_Membres_Jury(id_jury) {
  var tab_membres = document.getElementById("table_membres_jury");
  let tbody = tab_membres.querySelector("tbody");
  if (!tbody) {
    tbody = document.createElement("tbody");
    tab_membres.appendChild(tbody);
  }
  tbody.innerHTML = "";
  
  const badge = document.getElementById('badge_membres');
  
  if (!id_jury) {
    var tr = document.createElement("tr");
    tr.className = "empty-state";
    var td = document.createElement("td");
    td.colSpan = 4;
    td.className = "text-center text-muted fst-italic";
    td.style.padding = "40px 20px";
    td.style.background = "rgba(148, 163, 184, 0.05)";
    td.innerHTML = `
      <div style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;">
        <i class="fas fa-hand-pointer"></i>
      </div>
      <div style="font-size: 0.9rem;">Sélectionnez un jury</div>`;
    tr.appendChild(td);
    tbody.appendChild(tr);
    badge.textContent = '0';
    return;
  }

  fetch(`API_PHP/Liste_Membres_Jury.php?id_jury=${id_jury}`)
    .then(response => response.json())
    .then(data => {
      tbody.innerHTML = '';
      
      if (data.success && data.membres && data.membres.length > 0) {
        badge.textContent = data.count;
        
        var i = 1;
        data.membres.forEach((membre) => {
          var tr = document.createElement('tr');
          tr.style.transition = 'all 0.2s ease';
          
          // Colonne N°
          var tdNum = document.createElement('td');
          tdNum.className = "text-center";
          tdNum.style.padding = "12px";
          tdNum.textContent = i;
          
          // Colonne Membre (Nom + Grade)
          var tdNom = document.createElement('td');
          tdNom.style.padding = "12px";
          tdNom.style.fontWeight = "600";
          tdNom.innerHTML = `
            <i class="fas fa-user me-2" style="color: #10b981;"></i>
            ${membre.nom_complet}
            <br>
            <small class="text-muted">${membre.Grade || 'N/A'}</small>`;
          
          // Colonne Fonction (Rôle)
          var tdRole = document.createElement('td');
          tdRole.style.padding = "12px";
          
          // Couleur selon le rôle
          let roleColor = '#6b7280';
          let roleBg = '#f3f4f6';
          let roleIcon = 'fa-user';
          if (membre.role === 'Président') {
            roleColor = '#dc2626';
            roleBg = '#fee2e2';
            roleIcon = 'fa-crown';
          } else if (membre.role === 'Secrétaire') {
            roleColor = '#2563eb';
            roleBg = '#dbeafe';
            roleIcon = 'fa-pen';
          }
          
          var spanRole = document.createElement('span');
          spanRole.style.background = roleBg;
          spanRole.style.color = roleColor;
          spanRole.style.padding = "4px 12px";
          spanRole.style.borderRadius = "20px";
          spanRole.style.fontSize = "13px";
          spanRole.style.fontWeight = "600";
          spanRole.innerHTML = `<i class="fas ${roleIcon}"></i> ${membre.role}`;
          tdRole.appendChild(spanRole);
          
          if (membre.Login) {
            var brTag = document.createElement('br');
            tdRole.appendChild(brTag);
            var smallLogin = document.createElement('small');
            smallLogin.className = "text-muted mt-1";
            smallLogin.innerHTML = `<i class="fas fa-user-circle me-1"></i>${membre.Login}`;
            tdRole.appendChild(smallLogin);
          }
          
          // Colonne Action
          var tdAction = document.createElement('td');
          tdAction.style.padding = "12px";
          
          // Bouton Modifier
          var btnModifier = document.createElement('button');
          btnModifier.className = "btn btn-sm btn-primary me-1";
          btnModifier.style.padding = "6px 12px";
          btnModifier.style.borderRadius = "8px";
          btnModifier.innerHTML = '<i class="fas fa-edit"></i>';
          btnModifier.onclick = function() {
            Modifier_Membre_Jury(membre.ID_jury_membre);
          };
          tdAction.appendChild(btnModifier);
          
          // Bouton Supprimer
          var btnSupprimer = document.createElement('button');
          btnSupprimer.className = "btn btn-sm btn-danger";
          btnSupprimer.style.padding = "6px 12px";
          btnSupprimer.style.borderRadius = "8px";
          btnSupprimer.innerHTML = '<i class="fas fa-trash-alt"></i>';
          btnSupprimer.onclick = function() {
            Supprimer_Membre_Jury(membre.ID_jury_membre, membre.nom_complet, membre.role);
          };
          tdAction.appendChild(btnSupprimer);
          
          tr.appendChild(tdNum);
          tr.appendChild(tdNom);
          tr.appendChild(tdRole);
          tr.appendChild(tdAction);
          
          // Événements hover
          tr.addEventListener('mouseenter', function() {
            tr.style.backgroundColor = '#f0fdf4';
            tr.style.transform = 'translateX(3px)';
          });
          
          tr.addEventListener('mouseleave', function() {
            tr.style.backgroundColor = '';
            tr.style.transform = 'translateX(0)';
          });
          
          tbody.appendChild(tr);
          i++;
        });
      } else {
        badge.textContent = '0';
        var tr = document.createElement("tr");
        var td = document.createElement("td");
        td.colSpan = 4;
        td.className = "text-center text-muted fst-italic";
        td.style.padding = "40px 20px";
        td.style.background = "rgba(148, 163, 184, 0.05)";
        td.innerHTML = `
          <div style="font-size: 2rem; opacity: 0.3; margin-bottom: 10px;">
            <i class="fas fa-users-slash"></i>
          </div>
          <div style="font-size: 0.9rem;">Aucun membre dans ce jury</div>`;
        tr.appendChild(td);
        tbody.appendChild(tr);
      }
    })
    .catch(error => {
      console.error('Erreur lors du chargement des membres :', error);
      var tr = document.createElement("tr");
      var td = document.createElement("td");
      td.colSpan = 4;
      td.className = "text-center text-danger";
      td.style.padding = "20px";
      td.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Erreur de chargement';
      tr.appendChild(td);
      tbody.appendChild(tr);
      badge.textContent = '0';
    });
  
  tab_membres.classList.add("table-striped");
}

// Supprimer un membre du jury
function Supprimer_Membre_Jury(id_membre) {
  Ouvrir_Boite_Confirmation(
    'Êtes-vous sûr de vouloir retirer ce membre du jury ?',
    function() {
      // TODO: Créer l'API de suppression
      Ouvrir_Boite_Alert_G_Jury('Fonctionnalité de suppression à implémenter.');
    }
  );
}

/**********************************************************************************************
******************* GESTION DES ACTIONS SUR LES JURYS (MODIFIER/SUPPRIMER) *******************
***********************************************************************************************/

// Modifier un jury
function Modifier_Jury(id_jury, nom_jury, date_jury, code_promotion) {
  // Stocker l'ID du jury à modifier
  id_jury_selectionne = id_jury;
  
  // Remplir le formulaire avec les données existantes
  document.getElementById('jury_nom').value = nom_jury;
  document.getElementById('jury_date').value = date_jury;
  document.getElementById('jury_promotion').value = code_promotion;
  
  // Ouvrir le modal
  var boite_form = document.getElementById('boite_Form_Jury');
  if (boite_form) {
    boite_form.showModal();
  }
  
  // Changer le comportement du bouton pour mettre à jour au lieu d'ajouter
  var btnValider = boite_form.querySelector('button[onclick="Ajouter_Jury()"]');
  if (btnValider) {
    btnValider.onclick = function() {
      Mettre_A_Jour_Jury();
    };
    btnValider.innerHTML = '<i class="fas fa-save" style="margin-right: 8px;"></i>Mettre à jour';
  }
}

// Mettre à jour un jury existant
function Mettre_A_Jour_Jury() {
  const nom_jury = document.getElementById('jury_nom').value.trim();
  const date_jury = document.getElementById('jury_date').value;
  const code_promotion = document.getElementById('jury_promotion').value;

  if (!nom_jury || !date_jury || code_promotion === 'rien') {
    Ouvrir_Boite_Alert_G_Jury('Veuillez remplir tous les champs.');
    return;
  }

  if (!id_jury_selectionne) {
    Ouvrir_Boite_Alert_G_Jury('Erreur: Aucun jury sélectionné.');
    return;
  }

  const data = {
    id_jury: id_jury_selectionne,
    nom_jury: nom_jury,
    date_jury: date_jury,
    code_promotion: code_promotion,
    id_annee_acad: document.getElementById('id_fac_annee')?.value || ''
  };

  fetch('API_PHP/Modifier_Jury.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      Ouvrir_Boite_Alert_G_Jury('Jury modifié avec succès !');
      Fermer_Form_Jury();
      Affichage_Jurys();
    } else {
      Ouvrir_Boite_Alert_G_Jury('Erreur : ' + result.message);
    }
  })
  .catch(error => {
    console.error('Erreur lors de la modification :', error);
    Ouvrir_Boite_Alert_G_Jury('Erreur de connexion à l\'API.');
  });
}

// Supprimer un jury
function Supprimer_Jury(id_jury, nom_jury) 
{
  
  Ouvrir_Boite_Confirmation(
    `Êtes-vous sûr de vouloir supprimer le jury "${nom_jury}" ?\n\nCette action supprimera également tous les membres de ce jury.`,
    function() {
      Executer_Suppression_Jury(id_jury);
    }
  );
}

// Exécuter la suppression du jury
function Executer_Suppression_Jury(id_jury) {
  fetch('API_PHP/Supprimer_Jury.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id_jury: id_jury })
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      Ouvrir_Boite_Alert_G_Jury('Jury supprimé avec succès !');
      Affichage_Jurys();
      // Réinitialiser la sélection
      id_jury_selectionne = null;
      nom_jury_selectionne = "";
      promotion_jury_selectionne = "";
      // Vider le tableau des membres
      Affichage_Membres_Jury(null);
    } else {
      Ouvrir_Boite_Alert_G_Jury('Erreur : ' + result.message);
    }
  })
  .catch(error => {
    console.error('Erreur lors de la suppression :', error);
    Ouvrir_Boite_Alert_G_Jury('Erreur de connexion à l\'API.');
  });
}

// ========================================
// Suppression d'un Membre du Jury
// ========================================
function Supprimer_Membre_Jury(id_membre, nom_membre, role) {
  console.log('Supprimer_Membre_Jury appelé avec:', id_membre);
  
  var message = `Voulez-vous vraiment supprimer ce membre ?\n\n${nom_membre}\nRôle : ${role}`;
  
  Ouvrir_Boite_Confirmation(message, function() {
    Executer_Suppression_Membre(id_membre);
  });
}

function Executer_Suppression_Membre(id_membre) {
  console.log('Exécution suppression membre:', id_membre);
  
  var formData = new FormData();
  formData.append('id_membre', id_membre);
  
  fetch('API_PHP/Supprimer_Membre_Jury.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      Ouvrir_Boite_Alert_G_Jury(result.message);
      // Recharger la liste des membres du jury actuel
      if (id_jury_selectionne) {
        Affichage_Membres_Jury(id_jury_selectionne);
      }
    } else {
      Ouvrir_Boite_Alert_G_Jury('Erreur : ' + result.message);
    }
  })
  .catch(error => {
    console.error('Erreur:', error);
    Ouvrir_Boite_Alert_G_Jury('Erreur lors de la suppression du membre');
  });
}

// ========================================
// Modification d'un Membre du Jury
// ========================================
var id_membre_en_modification = null;

function Modifier_Membre_Jury(id_membre) {
  console.log('Modifier_Membre_Jury appelé avec:', id_membre);
  id_membre_en_modification = id_membre;
  
  // Récupérer les informations du membre
  fetch(`API_PHP/Info_Membre_Jury.php?id_membre=${id_membre}`)
    .then(response => response.json())
    .then(result => {
      if (result.success && result.membre) {
        var membre = result.membre;
        
        // Pré-remplir le formulaire dans la modal d'ajout
        document.getElementById('select_role_membre').value = membre.role;
        
        // Déclencher le changement pour afficher/masquer les champs
        var event = new Event('change');
        document.getElementById('select_role_membre').dispatchEvent(event);
        
        // Si Président ou Secrétaire, remplir login et statut
        if (membre.role === 'Président' || membre.role === 'Secrétaire') {
          document.getElementById('txt_login_membre').value = membre.Login || '';
          document.getElementById('select_statut_compte').value = membre.Statut || 'Actif';
          document.getElementById('txt_password_membre').value = ''; // Vide pour modification
          document.getElementById('txt_password_membre').placeholder = 'Laisser vide pour garder l\'ancien';
        }
        
        // Afficher le nom de l'agent dans le formulaire
        var nomComplet = `${membre.Nom_agent} ${membre.Post_agent} ${membre.Prenom || ''}`.trim();
        document.getElementById('nom_agent_selectionne').textContent = nomComplet;
        document.getElementById('form_config_membre').style.display = 'block';
        
        // Changer le texte du bouton pour "Mettre à jour"
        var btnAjout = document.getElementById('btn_ajout_membre');
        btnAjout.innerHTML = '<i class="fas fa-save me-2"></i>Mettre à Jour le Membre';
        btnAjout.onclick = function() { Mettre_A_Jour_Membre(); };
        
        // Ouvrir la modal
        var boite = document.getElementById('boite_Ajout_Membre_Jury');
        boite.showModal();
        
      } else {
        Ouvrir_Boite_Alert_G_Jury('Erreur : ' + (result.message || 'Impossible de récupérer les informations'));
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      Ouvrir_Boite_Alert_G_Jury('Erreur lors de la récupération des informations');
    });
}

function Mettre_A_Jour_Membre() {
  var role = document.getElementById('select_role_membre').value;
  var login = document.getElementById('txt_login_membre').value.trim();
  var password = document.getElementById('txt_password_membre').value.trim();
  var statut = document.getElementById('select_statut_compte').value;
  
  // Validation
  if ((role === 'Président' || role === 'Secrétaire') && login === '') {
    Ouvrir_Boite_Alert_G_Jury('Le login est obligatoire pour un Président ou Secrétaire.');
    return;
  }
  
  var formData = new FormData();
  formData.append('id_membre', id_membre_en_modification);
  formData.append('role', role);
  formData.append('login', login);
  formData.append('password', password);
  formData.append('statut', statut);
  
  fetch('API_PHP/Modifier_Membre_Jury.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      Ouvrir_Boite_Alert_G_Jury('Membre modifié avec succès !');
      
      // Fermer la modal
      var boite = document.getElementById('boite_Ajout_Membre_Jury');
      boite.close();
      
      // Réinitialiser le formulaire
      Reinitialiser_Form_Membre();
      
      // Recharger la liste des membres
      if (id_jury_selectionne) {
        Affichage_Membres_Jury(id_jury_selectionne);
      }
      
      // Réinitialiser l'ID de modification
      id_membre_en_modification = null;
      
    } else {
      Ouvrir_Boite_Alert_G_Jury('Erreur : ' + result.message);
    }
  })
  .catch(error => {
    console.error('Erreur:', error);
    Ouvrir_Boite_Alert_G_Jury('Erreur lors de la modification du membre');
  });
}






