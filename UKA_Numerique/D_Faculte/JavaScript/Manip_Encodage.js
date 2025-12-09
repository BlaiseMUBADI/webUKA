console.log("🎓 Module Encodage chargé")

// ==================== Variables Module Encodage ====================
let cmb_semestre_encodage_enc;
let selectedStudentMatricule_enc = null;
let selectedStudentNom_enc = null;

// ==================== Initialisation ====================
document.addEventListener("DOMContentLoaded", function(event) {
    const container = document.getElementById("div_gen_encodage");
    if (container !== null) {
        cmb_semestre_encodage_enc = container.querySelector('#id_semestre_encodage') || document.getElementById('id_semestre_encodage');

        Liste_Etudiants();
        Afficher_EC_aligne_encodage();
        
        if (cmb_semestre_encodage_enc !== null) {
            cmb_semestre_encodage_enc.addEventListener('change', (event) => {
                var id_semetre = cmb_semestre_encodage_enc.value;
                Liste_Ec_Aligne(id_semetre); 
                Afficher_EC_aligne_encodage();
            });
        }

        // Initialiser le menu contextuel
        initializeContextMenu();
        
        // Restaurer la préférence du menu
        restoreMenuPreference();
    }
});

// ==================== Toggle Menu Sidebar ====================
function toggleMenuEncodage() {
    const sidebar = document.querySelector('.sidebar');
    const container = document.getElementById('encodage-container');
    const btn = document.querySelector('.toggle-menu-btn i');
    
    if (!sidebar || !container) return;
    
    sidebar.classList.toggle('active');
    container.classList.toggle('fullscreen');
    
    // Changer l'icône selon l'état
    const indicator = document.getElementById('fullscreen-indicator');
    if (sidebar.classList.contains('active')) {
        btn.className = 'fas fa-angle-double-right';
        if (indicator) indicator.style.display = 'flex';
    } else {
        btn.className = 'fas fa-bars';
        if (indicator) indicator.style.display = 'none';
    }
    
    // Sauvegarder la préférence
    localStorage.setItem('menuEncodageCollapsed', sidebar.classList.contains('active'));
}

function restoreMenuPreference() {
    const isCollapsed = localStorage.getItem('menuEncodageCollapsed') === 'true';
    if (isCollapsed) {
        const sidebar = document.querySelector('.sidebar');
        const container = document.getElementById('encodage-container');
        const btn = document.querySelector('.toggle-menu-btn i');
        
        if (sidebar && container) {
            sidebar.classList.add('active');
            container.classList.add('fullscreen');
            if (btn) btn.className = 'fas fa-angle-double-right';
            
            const indicator = document.getElementById('fullscreen-indicator');
            if (indicator) indicator.style.display = 'flex';
        }
    }
}

// ==================== Boîte d'Alerte Moderne ====================
function Ouvrir_Boite_Alert_Encodage(text_a_afficher, type = 'info') {
    const boite = document.getElementById('boite_alert_encodage');
    const texte = document.getElementById('text_alert_boite_encodage');
    const icon = document.getElementById('alert_icon_type_encodage');
    
    if (!boite || !texte || !icon) return;
    
    texte.innerText = text_a_afficher;
    
    // Changer l'icône selon le type
    if (type === 'success') {
        icon.className = 'fas fa-check-circle';
    } else if (type === 'error') {
        icon.className = 'fas fa-exclamation-circle';
    } else if (type === 'warning') {
        icon.className = 'fas fa-exclamation-triangle';
    } else {
        icon.className = 'fas fa-info-circle';
    }
    
    boite.showModal();
}

function Fermer_Boite_Alert_Encodage() {
    const boite = document.getElementById('boite_alert_encodage');
    if (boite) boite.close();
}

// ==================== Menu Contextuel Étudiant ====================
function initializeContextMenu() {
    const table = document.getElementById('table_encodage');
    const contextMenu = document.getElementById('contextMenuStudent');
    
    if (!table || !contextMenu) return;
    
    // Événement clic droit sur les lignes étudiants
    table.addEventListener('contextmenu', function(e) {
        const row = e.target.closest('tbody tr');
        if (!row) return;
        
        e.preventDefault();
        
        // Récupérer les données de l'étudiant
        const nomCell = row.querySelector('.cell-editable[data-matricule]');
        if (!nomCell) return;
        
        selectedStudentMatricule_enc = nomCell.dataset.matricule;
        
        // Extraire le nom
        const nameSpan = nomCell.querySelector('.student-name');
        selectedStudentNom_enc = nameSpan ? nameSpan.textContent.trim() : 'Étudiant';
        
        // Positionner le menu
        contextMenu.style.left = e.pageX + 'px';
        contextMenu.style.top = e.pageY + 'px';
        contextMenu.style.display = 'block';
    });
    
    // Fermer le menu si clic ailleurs
    document.addEventListener('click', function(e) {
        if (!contextMenu.contains(e.target)) {
            contextMenu.style.display = 'none';
        }
    });
    
    // Empêcher fermeture si clic dans le menu
    contextMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

function afficherInfosEtudiant() {
    if (!selectedStudentMatricule_enc) return;
    
    document.getElementById('contextMenuStudent').style.display = 'none';
    
    fetch('API_PHP/Recup_infos_etudiant.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ matricule: selectedStudentMatricule_enc })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateStudentModal(data.student);
            document.getElementById('modal_Infos_Etudiant').showModal();
        } else {
            Ouvrir_Boite_Alert_Encodage('Erreur: ' + (data.message || 'Impossible de récupérer les informations'), 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        Ouvrir_Boite_Alert_Encodage('Erreur lors de la récupération des informations', 'error');
    });
}

function populateStudentModal(student) {
    // Photo
    const photoImg = document.getElementById('student_photo');
    if (photoImg) {
        photoImg.src = student.photo_url || '../Fichiers/Images/Profil.jpg';
        photoImg.onerror = function() { this.src = '../Fichiers/Images/Profil.jpg'; };
    }
    
    // Identité
    document.getElementById('student_matricule').textContent = student.Matricule || '-';
    document.getElementById('student_sexe').textContent = student.Sexe || '-';
    document.getElementById('student_nom_complet').textContent = student.ident_etudiant || '-';
    document.getElementById('student_date_naissance').textContent = student.date_naissance || '-';
    document.getElementById('student_lieu_naissance').textContent = student.lieu_naissance || '-';
    
    // Contact
    document.getElementById('student_telephone').textContent = student.telephone || '-';
    document.getElementById('student_email').textContent = student.email || '-';
    document.getElementById('student_adresse').textContent = student.adresse || '-';
    
    // Académique
    document.getElementById('student_promotion').textContent = student.promotion || '-';
    document.getElementById('student_annee_academique').textContent = student.annee_academique || '-';
    document.getElementById('student_faculte').textContent = student.faculte || '-';
    document.getElementById('student_filiere').textContent = student.filiere || '-';
}

function closeStudentModal() {
    document.getElementById('modal_Infos_Etudiant').close();
}

function afficherHistoriqueNotes() {
    document.getElementById('contextMenuStudent').style.display = 'none';
    Ouvrir_Boite_Alert_Encodage('Fonctionnalité en cours de développement: Historique des notes pour ' + selectedStudentNom_enc, 'info');
}

function genererBulletin() {
    document.getElementById('contextMenuStudent').style.display = 'none';
    Ouvrir_Boite_Alert_Encodage('Génération du bulletin pour ' + selectedStudentNom_enc + ' en cours...', 'info');
}

/* ******************** LA FONCTION QUI R2CUPERE LMES ETUDIANTS DANS UNE PROMOTION ***********/
/*async function Liste_Etudiants() {
  const response = await fetch('/api/ecAlignes', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ promotion, anneeAcad })
  });
  return response.json();
}*/

async function Liste_Etudiants() {
  const response = await fetch('API_PHP/Liste_etudiant_delib.php');
  return response.json();
}

async function Liste_Ec_Aligne(id_semestre) {
  const response = await fetch('API_PHP/Liste_EC_aligne_delibe.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({
          id_semestre: id_semestre,
      })
  });
  return response.json();
}

async function Liste_Cotes(id_semestre) {
  const response = await fetch('API_PHP/Liste_Cotes.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        id_semestre: id_semestre
      })
  });
  return response.json();
}

async function Afficher_EC_aligne_encodage() {
  // Récuperation des données envoyées par les API et les stockées dans un tableau
  let tab_ECs_aligne = await Liste_Ec_Aligne(cmb_semestre_encodage_enc.value);
  let tab_etudiants_aligne = await Liste_Etudiants();
  let tab_Cotes = await Liste_Cotes(cmb_semestre_encodage_enc.value); // Renommer la variable locale

  // Vérification que les données sont bien des tableaux
  if (!Array.isArray(tab_ECs_aligne)) {
    console.error("Erreur: tab_ECs_aligne n'est pas un tableau:", tab_ECs_aligne);
    tab_ECs_aligne = [];
  }
  if (!Array.isArray(tab_etudiants_aligne)) {
    console.error("Erreur: tab_etudiants_aligne n'est pas un tableau:", tab_etudiants_aligne);
    tab_etudiants_aligne = [];
  }
  if (!Array.isArray(tab_Cotes)) {
    console.error("Erreur: tab_Cotes n'est pas un tableau:", tab_Cotes);
    tab_Cotes = [];
  }

  let table_encodage = document.getElementById("table_encodage");
  
  // Optimisation : Utiliser innerHTML pour meilleure performance avec 40+ ECs
  table_encodage.innerHTML = '';

  var thead = document.createElement("thead");
  thead.classList.add("sticky-sm-top", "m-0", "fw-bold", "text-center"); // Pour ajouter la classe à un element HTMl

  // Création de la permière ligne qui contien les ECs
  var tr1 = document.createElement("tr"); // Entete 1
  tr1.style = "background-color:white; color:black;"

  var tr2 = document.createElement("tr"); // Entete 2
  tr2.style = "background-color:midnightblue; color:white;"

  var tr3 = document.createElement("tr"); //Entete 3
  tr3.style = "background-color:midnightblue; color:white;"

  var td1 = document.createElement("td");
  td1.rowSpan = 3;
  td1.textContent = "N°";
  td1.classList.add("text-center", "header-fixed");
  td1.style.minWidth = "50px";
  td1.style.maxWidth = "50px";
  td1.style.width = "50px";

  var td2 = document.createElement("td");
  td2.rowSpan = 3;
  td2.textContent = "NOM, POST, PRÉNOM";
  td2.classList.add("text-center", "header-fixed");
  td2.style.minWidth = "280px";
  td2.style.maxWidth = "280px";
  td2.style.width = "280px";

  var td3 = document.createElement("td");
  td3.textContent = "EC";
  td3.classList.add("text-center", "header-label", "header-label-sticky");
  td3.style.backgroundColor = "#2c3e50";
  td3.style.color = "white";
  td3.style.fontWeight = "700";
  td3.style.fontSize = "14px";
  td3.style.letterSpacing = "1px";
  td3.style.position = "sticky";
  td3.style.left = "330px";
  td3.style.zIndex = "15";
  td3.style.minWidth = "60px";
  td3.style.maxWidth = "60px";
  td3.style.width = "60px"

  tr1.appendChild(td1);
  tr1.appendChild(td2);
  tr1.appendChild(td3);

  var td4 = document.createElement("td");
  td4.textContent = "CEC";
  td4.classList.add("text-center", "header-label", "header-label-sticky");
  td4.style.backgroundColor = "#34495e";
  td4.style.color = "white";
  td4.style.fontWeight = "600";
  td4.style.fontSize = "13px";
  td4.style.position = "sticky";
  td4.style.left = "330px";
  td4.style.zIndex = "15";
  td4.style.minWidth = "60px";
  td4.style.maxWidth = "60px";
  td4.style.width = "60px";
  tr2.appendChild(td4);

  var td5 = document.createElement("td");
  td5.textContent = "MAX";
  td5.classList.add("text-center", "header-label", "header-label-sticky");
  td5.style.backgroundColor = "#3498db";
  td5.style.color = "white";
  td5.style.fontWeight = "600";
  td5.style.fontSize = "13px";
  td5.style.position = "sticky";
  td5.style.left = "330px";
  td5.style.zIndex = "15";
  td5.style.minWidth = "60px";
  td5.style.maxWidth = "60px";
  td5.style.width = "60px";
  tr3.appendChild(td5);

  // Boucle pour récuperer touts les ECs (Aligner dans un semestre ) qui sont dans la base de données
  // Création du tableau qui contiendra tout les ecs séléctionnés
  let tab_ec = [];
  tab_ECs_aligne.forEach(ec_s_aligne => {
      tab_ec.push(ec_s_aligne.id_ec_aligne);

      const td_ec = document.createElement('td');
      td_ec.textContent = ec_s_aligne.Intutile_ec;
      td_ec.classList.add("text-start", "ec-header-modern");
      td_ec.style.writingMode = "vertical-rl";
      td_ec.style.transform = "rotate(180deg)";
      td_ec.style.minWidth = "45px";
      td_ec.style.maxWidth = "45px";
      td_ec.style.width = "45px";
      td_ec.style.padding = "15px 8px";
      td_ec.style.height = "200px";
      td_ec.style.whiteSpace = "nowrap";
      td_ec.style.overflow = "hidden";
      td_ec.style.textOverflow = "ellipsis";
      td_ec.title = ec_s_aligne.Intutile_ec; // Tooltip pour voir le nom complet

      const td_ec_credit = document.createElement('td');
      td_ec_credit.textContent = ec_s_aligne.Credit;
      td_ec_credit.classList.add("text-center", "credit-cell-modern");
      td_ec_credit.style.minWidth = "45px";
      td_ec_credit.style.maxWidth = "45px";
      td_ec_credit.style.width = "45px";
      td_ec_credit.style.fontWeight = "700";
      td_ec_credit.style.fontSize = "14px";

      const td_ec_max = document.createElement('td');
      td_ec_max.textContent = 20;
      td_ec_max.classList.add("text-center", "max-cell-modern");
      td_ec_max.style.minWidth = "45px";
      td_ec_max.style.maxWidth = "45px";
      td_ec_max.style.width = "45px";
      td_ec_max.style.fontWeight = "700";
      td_ec_max.style.fontSize = "14px";

      tr1.appendChild(td_ec);
      tr2.appendChild(td_ec_credit);
      tr3.appendChild(td_ec_max);
  });

  thead.appendChild(tr1);
  thead.appendChild(tr2);
  thead.appendChild(tr3);

  /* Affichage des étudiants */
  var tbody = document.createElement("tbody");
  
  // Optimisation pour 40+ ECs : 
  // - DocumentFragment pour batch insert (réduit les reflows)
  // - table-layout: fixed en CSS (améliore le rendu)
  // - Événements attachés individuellement (préserve la réactivité)
  const fragment = document.createDocumentFragment();

  var i = 1;
  tab_etudiants_aligne.forEach(etudiant => {
      var tr = document.createElement("tr");
      const tdnum = document.createElement("td");
      tdnum.textContent = i;
      tdnum.classList.add("text-center", "col-md-auto");
      tdnum.style.minWidth = "50px";
      tdnum.style.maxWidth = "50px";
      tdnum.style.width = "50px";

      const td_etudiant = document.createElement('td');
      td_etudiant.classList.add("text-start");
      td_etudiant.style.minWidth = "280px";
      td_etudiant.style.maxWidth = "280px";
      td_etudiant.style.width = "280px";
      
      // Conteneur pour nom + matricule avec retour à la ligne
      const nameDiv = document.createElement('div');
      nameDiv.style.display = "flex";
      nameDiv.style.flexDirection = "column";
      nameDiv.style.gap = "4px";
      nameDiv.style.cursor = "context-menu";
      
      // Info-bulle complète pour l'étudiant
      nameDiv.title = `${etudiant.ident_etudiant}\nMatricule: ${etudiant.Matricule}\n\n💡 Clic droit pour plus d'options`;
      
      // Créer le nom
      const nameSpan = document.createElement('span');
      nameSpan.textContent = etudiant.ident_etudiant;
      nameSpan.classList.add("student-name");
      nameSpan.style.display = "block";
      
      // Créer le matricule avec style moderne (sur une ligne séparée)
      const matriculeSpan = document.createElement('span');
      matriculeSpan.textContent = etudiant.Matricule;
      matriculeSpan.classList.add("student-matricule");
      
      nameDiv.appendChild(nameSpan);
      nameDiv.appendChild(matriculeSpan);
      td_etudiant.appendChild(nameDiv);

      const td_vide = document.createElement('td');
      td_vide.classList.add("separator-column-sticky");
      td_vide.style.backgroundColor = "midnightblue";
      td_vide.style.color = "white";
      td_vide.style.position = "sticky";
      td_vide.style.left = "330px";
      td_vide.style.zIndex = "5";
      td_vide.style.minWidth = "60px";
      td_vide.style.maxWidth = "60px";
      td_vide.style.width = "60px";

      tr.appendChild(tdnum);
      tr.appendChild(td_etudiant);
      tr.appendChild(td_vide);

      // Boucle pour afficher les cellules éditables (style Google Sheets)
      tab_ECs_aligne.forEach((ec_s_aligne, index) => {
          const td_cell = document.createElement('td');
          td_cell.classList.add("cell-wrapper");
          td_cell.style.padding = "0";
          td_cell.style.position = "relative";
          td_cell.style.minWidth = "45px";
          td_cell.style.maxWidth = "45px";
          td_cell.style.width = "45px";

          // Créer la div éditable (comme Google Sheets)
          const editableCell = document.createElement("div");
          editableCell.contentEditable = "true";
          editableCell.spellcheck = false;
          editableCell.classList.add("cell-editable");
          editableCell.dataset.matricule = etudiant.Matricule;
          editableCell.dataset.ecAligne = ec_s_aligne.id_ec_aligne;
          editableCell.dataset.valeurOriginale = "";

          // Vérifier si une cote existe pour cet étudiant et cet EC
          let cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec_s_aligne.id_ec_aligne);
          
          if (cote) {
            editableCell.textContent = cote.Cote;
            editableCell.dataset.valeurOriginale = cote.Cote;
            editableCell.dataset.coteExiste = "true";
            applyCellColor(editableCell, cote.Cote);
          } else {
            editableCell.dataset.coteExiste = "false";
          }

          // Focus : sélectionner tout le contenu et surligner ligne/colonne
          editableCell.addEventListener('focus', (e) => 
          {
            e.target.classList.add('focused');
            
            // Surligner la ligne et la colonne
            highlightRowAndColumn(e.target);
            
            // Sélectionner le contenu
            const range = document.createRange();
            range.selectNodeContents(e.target);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
          });

          // Blur : valider et sauvegarder
          editableCell.addEventListener('blur', (e) => 
          {
            e.target.classList.remove('focused');
            
            // Retirer la surbrillance ligne/colonne
            removeHighlights();
            
            // Ne pas traiter si c'est une navigation en cours
            if (e.target.dataset.navigating === 'true') {
              e.target.dataset.navigating = 'false';
              return;
            }
            
            const valeurOriginale = e.target.dataset.valeurOriginale;
            let nouvelleValeur = e.target.textContent.trim().replace(',', '.');
            
            // Validation
            if (nouvelleValeur !== "") {
              const nombre = parseFloat(nouvelleValeur);
              if (isNaN(nombre)) {
                e.target.textContent = valeurOriginale;
                return;
              }
              nouvelleValeur = nombre.toString();
              e.target.textContent = nouvelleValeur;
            }
            
            // Appliquer couleur SEULEMENT si la valeur n'est pas vide
            if (nouvelleValeur !== "") {
              applyCellColor(e.target, nouvelleValeur);
            } else {
              // Si vide, retirer toutes les couleurs
              applyCellColor(e.target, "");
            }
            
            // Sauvegarder si changement
            if (nouvelleValeur !== valeurOriginale) {
              const matricule = e.target.dataset.matricule;
              const ecAligne = e.target.dataset.ecAligne;
              const coteExiste = e.target.dataset.coteExiste === "true";
              
              if (coteExiste && nouvelleValeur === "") {
                Suppression(matricule, ecAligne);
                e.target.dataset.coteExiste = "false";
              } else if (coteExiste && nouvelleValeur !== "") {
                Modifier_cote(matricule, ecAligne, nouvelleValeur);
              } else if (!coteExiste && nouvelleValeur !== "") {
                Ajout_point_Obtenu(matricule, ecAligne, nouvelleValeur);
                e.target.dataset.coteExiste = "true";
              }
              e.target.dataset.valeurOriginale = nouvelleValeur;
            }
          });

          // Navigation clavier
          editableCell.addEventListener('keydown', (e) => {
            const cell = e.target;
            const td = cell.parentElement;
            const row = td.parentElement;
            const rowIndex = Array.from(row.parentElement.children).indexOf(row);
            const colIndex = Array.from(row.children).indexOf(td);

            // Enter : valider et descendre
            if (e.key === 'Enter') {
              e.preventDefault();
              cell.blur();
              navigateCell(row.parentElement, rowIndex + 1, colIndex);
              return;
            }

            // Tab : valider et aller à droite/gauche
            if (e.key === 'Tab') {
              e.preventDefault();
              cell.blur();
              navigateCell(row.parentElement, rowIndex, colIndex + (e.shiftKey ? -1 : 1));
              return;
            }

            // Escape : annuler
            if (e.key === 'Escape') {
              e.preventDefault();
              cell.textContent = cell.dataset.valeurOriginale;
              cell.blur();
              return;
            }

            // Flèches : TOUJOURS sauvegarder et naviguer (comme Excel)
            if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
              e.preventDefault();
              
              // Sauvegarder manuellement avant de naviguer
              const valeurOriginale = cell.dataset.valeurOriginale;
              let nouvelleValeur = cell.textContent.trim().replace(',', '.');
              
              // Validation
              if (nouvelleValeur !== "") {
                const nombre = parseFloat(nouvelleValeur);
                if (isNaN(nombre) || nombre < 0) {
                  cell.textContent = valeurOriginale;
                  return;
                }
                nouvelleValeur = nombre.toString();
                cell.textContent = nouvelleValeur;
              }
              
              // Appliquer couleur (y compris orange pour notes > 20)
              if (nouvelleValeur !== "") {
                applyCellColor(cell, nouvelleValeur);
              } else {
                applyCellColor(cell, "");
              }
              
              // Sauvegarder si changement
              if (nouvelleValeur !== valeurOriginale) {
                const matricule = cell.dataset.matricule;
                const ecAligne = cell.dataset.ecAligne;
                const coteExiste = cell.dataset.coteExiste === "true";
                
                if (coteExiste && nouvelleValeur === "") {
                  Suppression(matricule, ecAligne);
                  cell.dataset.coteExiste = "false";
                } else if (coteExiste && nouvelleValeur !== "") {
                  Modifier_cote(matricule, ecAligne, nouvelleValeur);
                } else if (!coteExiste && nouvelleValeur !== "") {
                  Ajout_point_Obtenu(matricule, ecAligne, nouvelleValeur);
                  cell.dataset.coteExiste = "true";
                }
                cell.dataset.valeurOriginale = nouvelleValeur;
              }
              
              // Naviguer vers la cellule suivante
              const dir = {
                'ArrowUp': [-1, 0], 
                'ArrowDown': [1, 0],
                'ArrowLeft': [0, -1], 
                'ArrowRight': [0, 1]
              }[e.key];
              
              // Retirer le focus et naviguer immédiatement
              cell.blur();
              setTimeout(() => {
                navigateCell(row.parentElement, rowIndex + dir[0], colIndex + dir[1]);
              }, 50);
            }
          });

          // Validation en temps réel
          editableCell.addEventListener('input', (e) => {
            let texte = e.target.textContent;
            texte = texte.replace(/[^0-9.,]/g, '').replace(',', '.');
            const parts = texte.split('.');
            if (parts.length > 2) texte = parts[0] + '.' + parts.slice(1).join('');
            if (parts[1] && parts[1].length > 2) texte = parts[0] + '.' + parts[1].substring(0, 2);
            
            if (e.target.textContent !== texte) {
              const sel = window.getSelection();
              const offset = sel.getRangeAt(0).startOffset;
              e.target.textContent = texte;
              if (e.target.childNodes[0]) {
                const range = document.createRange();
                range.setStart(e.target.childNodes[0], Math.min(offset, texte.length));
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
              }
            }
          });

          // Copier-coller
          editableCell.addEventListener('paste', (e) => {
            e.preventDefault();
            const texte = (e.clipboardData || window.clipboardData).getData('text');
            const nombre = parseFloat(texte.replace(',', '.'));
            if (!isNaN(nombre) && nombre >= 0) {
              document.execCommand('insertText', false, nombre.toString());
            }
          });

          td_cell.appendChild(editableCell);
          tr.appendChild(td_cell);
      });

      fragment.appendChild(tr);
      i++;
  });

  // Insérer toutes les lignes en une seule opération (optimisation 40+ ECs)
  tbody.appendChild(fragment);
  
  table_encodage.appendChild(thead);
  table_encodage.appendChild(tbody);
  table_encodage.classList.add("table-bordered");
  
  // Vérifier si le tableau nécessite un défilement horizontal (40+ ECs)
  checkTableOverflow();
  
  // Mettre à jour les statistiques
  updateStats(tab_etudiants_aligne.length, tab_ECs_aligne.length, tab_Cotes.length);
}

// Vérifier si le tableau déborde horizontalement
function checkTableOverflow() {
  const table = document.getElementById('table_encodage');
  const wrapper = document.querySelector('.table-wrapper-encodage');
  
  if (table && wrapper) {
    // Détecter si le tableau est plus large que son conteneur
    const hasOverflow = table.scrollWidth > wrapper.clientWidth;
    table.dataset.hasOverflow = hasOverflow;
    
    // Log pour débogage (peut être retiré en production)
    if (hasOverflow) {
      console.log(`✅ Tableau large détecté: ${table.scrollWidth}px > ${wrapper.clientWidth}px`);
    }
  }
}

// Fonction pour mettre à jour les statistiques
function updateStats(nbEtudiants, nbECs, nbCotes) {
  const countEtudiants = document.getElementById('count-etudiants');
  const countEcs = document.getElementById('count-ecs');
  const countCotesElement = document.getElementById('count-cotes');
  
  if (countEtudiants) countEtudiants.textContent = nbEtudiants;
  if (countEcs) {
    countEcs.textContent = nbECs;
    // Alerte visuelle si beaucoup d'ECs (40+)
    if (nbECs >= 40) {
      countEcs.parentElement.style.background = 'rgba(255, 152, 0, 0.3)';
      countEcs.parentElement.title = `⚠️ ${nbECs} ECs - Utilisez le défilement horizontal`;
    } else if (nbECs >= 30) {
      countEcs.parentElement.style.background = 'rgba(255, 193, 7, 0.3)';
    } else {
      countEcs.parentElement.style.background = 'rgba(255, 255, 255, 0.2)';
    }
  }
  if (countCotesElement) countCotesElement.textContent = nbCotes;
}

// Fonction pour mettre à jour le compteur de côtes (sans recharger le tableau)
function updateCotesCount(increment = 0) {
  const countCotesElement = document.getElementById('count-cotes');
  if (countCotesElement) {
    const currentCount = parseInt(countCotesElement.textContent) || 0;
    const newCount = currentCount + increment;
    countCotesElement.textContent = newCount;
    
    // Animation de mise à jour
    countCotesElement.parentElement.style.transform = 'scale(1.1)';
    countCotesElement.parentElement.style.background = 'rgba(76, 175, 80, 0.4)';
    setTimeout(() => {
      countCotesElement.parentElement.style.transform = 'scale(1)';
      countCotesElement.parentElement.style.background = 'rgba(255, 255, 255, 0.2)';
    }, 300);
  }
}

// Appliquer les couleurs selon la note (style Google Sheets)
function applyCellColor(cell, valeur) {
  // Retirer toutes les classes de couleur
  cell.classList.remove('note-echec', 'note-normal', 'note-excellent');
  
  // Ne rien faire si la cellule est vide - retirer tous les styles
  if (valeur === "" || valeur === null || valeur === undefined) {
    cell.style.backgroundColor = "";
    cell.style.border = "";
    cell.style.animation = "";
    return;
  }
  
  const note = parseFloat(valeur);
  if (isNaN(note)) {
    cell.style.backgroundColor = "";
    cell.style.border = "";
    cell.style.animation = "";
    return;
  }
  
  // Logique corrigée: notes valides entre 0 et 20
  if (note < 0 || note > 20) {
    cell.classList.add('note-excellent'); // ERREUR: note invalide (< 0 ou > 20) - orange clignotant
    // Forcer le style inline pour être sûr
    cell.style.backgroundColor = "#ffa94d";
    cell.style.color = "white";
    cell.style.border = "2px solid #fd7e14";
  } else if (note < 10) {
    cell.classList.add('note-echec'); // ÉCHEC: note < 10 - rouge
    cell.style.backgroundColor = "";
    cell.style.color = "";
    cell.style.border = "";
  } else {
    cell.classList.add('note-normal'); // RÉUSSITE: note entre 10 et 20 - blanc
    cell.style.backgroundColor = "";
    cell.style.color = "";
    cell.style.border = "";
  }
}

// Navigation entre cellules (style Google Sheets)
function navigateCell(tbody, rowIndex, colIndex) {
  // Ignorer les 3 premières colonnes (N°, Nom, Séparateur)
  const minCol = 3;
  
  if (rowIndex < 0 || rowIndex >= tbody.children.length) return;
  
  const targetRow = tbody.children[rowIndex];
  if (colIndex < minCol || colIndex >= targetRow.children.length) return;
  
  const targetCell = targetRow.children[colIndex].querySelector('.cell-editable');
  if (targetCell) {
    targetCell.focus();
  }
}

// Surligner la ligne et la colonne de la cellule active
function highlightRowAndColumn(cell) {
  // Retirer toutes les surbrillances existantes
  removeHighlights();
  
  const td = cell.parentElement;
  const row = td.parentElement;
  const table = row.closest('table');
  const tbody = table.querySelector('tbody');
  
  // Surligner TOUTE la ligne (tous les TD)
  Array.from(row.children).forEach(cell => {
    cell.classList.add('row-highlight');
  });
  
  // Calculer l'index de colonne réel en tenant compte des cellules fusionnées
  let realColIndex = 0;
  for (let i = 0; i < row.children.length; i++) {
    if (row.children[i] === td) {
      realColIndex = i;
      break;
    }
  }
  
  // Surligner la colonne dans TOUTES les lignes du tbody
  if (tbody) {
    tbody.querySelectorAll('tr').forEach(bodyRow => {
      const targetCell = bodyRow.children[realColIndex];
      if (targetCell) {
        targetCell.classList.add('col-highlight');
      }
    });
  }
  
  // Marquer la cellule active (intersection)
  td.classList.add('cell-active');
}

// Retirer toutes les surbrillances
function removeHighlights() {
  const table = document.getElementById('table_encodage');
  if (!table) return;
  
  // Retirer les classes de surbrillance
  table.querySelectorAll('.row-highlight').forEach(el => el.classList.remove('row-highlight'));
  table.querySelectorAll('.col-highlight').forEach(el => el.classList.remove('col-highlight'));
  table.querySelectorAll('.cell-active').forEach(el => el.classList.remove('cell-active'));
}

async function Ajout_point_Obtenu(mat_etudiant,id_ec,cote) 
{
  
  if(cote!=="")
  {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "API_PHP/Ajout_Cote.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            // Tester la valeur envoyée par l'API PHP
            if (response.status === "success") {
                console.log(response.message)
                // Mettre à jour le compteur de côtes (+1)
                updateCotesCount(1);
            } else {
              console.log(response.message)
            }
        }
    };
    var data = JSON.stringify({
        "matricule": mat_etudiant,
        "id_ec_aligne": id_ec,
        "cote": cote
    });
    xhr.send(data);
    
  }    
}

async function Suppression(mat_etudiant,id_ec_aligne) 
{
  
  var xhr = new XMLHttpRequest();
    xhr.open("POST", "API_PHP/Suppression_Cote.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            // Tester la valeur envoyée par l'API PHP
            if (response.status === "success") {
                console.log(response.message)
                // Mettre à jour le compteur de côtes (-1)
                updateCotesCount(-1);
            } else {
              console.log(response.message)
            }
        }
    };
    var data = JSON.stringify({
        "matricule": mat_etudiant,
        "id_ec_aligne": id_ec_aligne
    });
    xhr.send(data);
    
}

async function Modifier_cote(mat_etudiant,id_ec,cote) 
{
  
  if(cote!=="")
  {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "API_PHP/Modifier_Cote.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            // Tester la valeur envoyée par l'API PHP
            if (response.status === "success") {
                console.log(response.message)
                // Ne pas recharger le tableau pour garder le focus et permettre la navigation
            } else {
              console.log(response.message)
            }
        }
    };
    var data = JSON.stringify({
        "matricule": mat_etudiant,
        "id_ec_aligne": id_ec,
        "cote": cote
    });
    xhr.send(data);
  }    
}

// ===== FONCTIONNALITÉ DE RECHERCHE D'ÉTUDIANTS =====
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search-student');
  const clearBtn = document.getElementById('clear-search');
  
  if (searchInput) {
    // Recherche en temps réel
    searchInput.addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase().trim();
      
      // Afficher/masquer le bouton clear
      if (searchTerm.length > 0) {
        clearBtn.style.display = 'flex';
      } else {
        clearBtn.style.display = 'none';
      }
      
      filterStudents(searchTerm);
    });
    
    // Bouton pour effacer
    clearBtn.addEventListener('click', function() {
      searchInput.value = '';
      clearBtn.style.display = 'none';
      filterStudents('');
      searchInput.focus();
    });
  }
});

// Filtrer les lignes d'étudiants
function filterStudents(searchTerm) {
  const tbody = document.querySelector('#table_encodage tbody');
  if (!tbody) return;
  
  const rows = tbody.querySelectorAll('tr');
  let visibleCount = 0;
  
  rows.forEach(row => {
    // Récupérer le nom et matricule de l'étudiant (2ème colonne)
    const nameCell = row.children[1];
    if (!nameCell) return;
    
    // Récupérer le texte complet (nom + matricule)
    const fullText = nameCell.textContent.toLowerCase();
    
    // Vérifier si le terme de recherche est dans le nom ou le matricule
    if (searchTerm === '' || fullText.includes(searchTerm)) {
      row.style.display = '';
      visibleCount++;
    } else {
      row.style.display = 'none';
    }
  });
  
  // Mettre à jour le compteur d'étudiants
  const countElement = document.getElementById('count-etudiants');
  if (countElement && searchTerm !== '') {
    countElement.textContent = visibleCount;
    countElement.parentElement.style.background = 'rgba(76, 175, 80, 0.3)';
  } else if (countElement) {
    countElement.parentElement.style.background = 'rgba(255, 255, 255, 0.2)';
  }
}