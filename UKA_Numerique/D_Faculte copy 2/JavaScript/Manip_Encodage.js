console.log(" je suis dans Manip_encodage")

// Déclaration de variable et des composants
// Les éléments du DOM sont initialisés seulement si la page contient
// l'élément parent `div_gen_encodage`. Cela évite que ce script lance des
// getElementById() au top-level et retourne `null` quand il est inclus
// sur d'autres pages.
let cmb_semestre_encodage;

document.addEventListener("DOMContentLoaded",function(event)
  {
    const container = document.getElementById("div_gen_encodage");
    if(container !== null)
    {
      // initialiser les éléments en utilisant le conteneur pour éviter
      // toute sélection hors-contexte lorsque ce script est inclus sur
      // d'autres pages
      cmb_semestre_encodage = container.querySelector('#id_semestre_encodage') || document.getElementById('id_semestre_encodage');

      Liste_Etudiants();
      Afficher_EC_aligne_delibe();
      
      if (cmb_semestre_encodage !== null) {
        cmb_semestre_encodage.addEventListener('change',(event)=> {
          var id_semetre=cmb_semestre_encodage.value;
          Liste_Ec_Aligne(id_semetre); 
          Afficher_EC_aligne_delibe();
        });
      }


    }



})

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

async function Afficher_EC_aligne_delibe() {
  // Récuperation des données envoyées par les API et les stockées dans un tableau
  let tab_ECs_aligne = await Liste_Ec_Aligne(cmb_semestre_encodage.value);
  let tab_etudiants_aligne = await Liste_Etudiants();
  let tab_Cotes = await Liste_Cotes(cmb_semestre_encodage.value); // Renommer la variable locale

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
  while (table_encodage.firstChild) {
      table_encodage.removeChild(table_encodage.firstChild);
  }

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
  td1.classList.add("text-center");
  td1.style = "background-color:midnightblue; color:white;"

  var td2 = document.createElement("td");
  td2.rowSpan = 3;
  td2.textContent = "Mat & Nom ,Post, Prénom";
  td2.classList.add("text-center");
  td2.style = "background-color:midnightblue; color:white;"

  var td3 = document.createElement("td");
  td3.textContent = "EC";
  td3.classList.add("text-center");
  td3.style = "background-color:midnightblue; color:white;"

  tr1.appendChild(td1);
  tr1.appendChild(td2);
  tr1.appendChild(td3);

  var td4 = document.createElement("td");
  td4.textContent = "CEC";
  td4.classList.add("text-center");
  tr2.appendChild(td4);

  var td5 = document.createElement("td");
  td5.textContent = "MAX";
  td5.classList.add("text-center");
  tr3.appendChild(td5);

  // Boucle pour récuperer touts les ECs (Aligner dans un semestre ) qui sont dans la base de données
  // Création du tableau qui contiendra tout les ecs séléctionnés
  let tab_ec = [];
  tab_ECs_aligne.forEach(ec_s_aligne => {
      tab_ec.push(ec_s_aligne.id_ec_aligne);

      const td_ec = document.createElement('td');
      td_ec.textContent = ec_s_aligne.Intutile_ec;
      td_ec.classList.add("text-start"); // Centrer le texte
      td_ec.style.writingMode = "vertical-rl"; // Texte vertical
      td_ec.style.transform = "rotate(180deg)"; // Rotation du texte

      const td_ec_credit = document.createElement('td');
      td_ec_credit.textContent = ec_s_aligne.Credit;
      td_ec_credit.classList.add("text-center"); // Centrer le texte

      const td_ec_max = document.createElement('td');
      td_ec_max.textContent = 20;
      td_ec_max.classList.add("text-center"); // Centrer le texte

      tr1.appendChild(td_ec);
      tr2.appendChild(td_ec_credit);
      tr3.appendChild(td_ec_max);
  });

  thead.appendChild(tr1);
  thead.appendChild(tr2);
  thead.appendChild(tr3);

  /* Affichage des étudiants */
  var tbody = document.createElement("tbody");

  var i = 1;
  tab_etudiants_aligne.forEach(etudiant => {
      var tr = document.createElement("tr");
      const tdnum = document.createElement("td");
      tdnum.textContent = i;
      tdnum.classList.add("text-center", "col-md-auto");

      const td_etudiant = document.createElement('td');
      td_etudiant.textContent = etudiant.ident_etudiant;
      td_etudiant.classList.add("text-start");

      const td_vide = document.createElement('td');
      td_vide.style = "background-color:midnightblue; color:white;"

      tr.appendChild(tdnum);
      tr.appendChild(td_etudiant);
      tr.appendChild(td_vide);

      // Boucle pour afficher les cellules éditables (style Google Sheets)
      tab_ECs_aligne.forEach((ec_s_aligne, index) => {
          const td_cell = document.createElement('td');
          td_cell.classList.add("cell-wrapper");
          td_cell.style.padding = "0";
          td_cell.style.position = "relative";

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
          editableCell.addEventListener('focus', (e) => {
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
              if (isNaN(nombre) || nombre < 0) {
                e.target.textContent = valeurOriginale;
                alert('⚠️ Veuillez entrer un nombre valide (≥ 0)');
                return;
              }
              nouvelleValeur = nombre.toString();
              e.target.textContent = nouvelleValeur;
            }
            
            // Appliquer couleur
            applyCellColor(e.target, nouvelleValeur);
            
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
              
              // Appliquer couleur
              applyCellColor(cell, nouvelleValeur);
              
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

      tbody.appendChild(tr);
      i++;
  });

  table_encodage.appendChild(thead);
  table_encodage.appendChild(tbody);
  table_encodage.classList.add("table-bordered");
  
  // Mettre à jour les statistiques
  updateStats(tab_etudiants_aligne.length, tab_ECs_aligne.length, tab_Cotes.length);
}

// Fonction pour mettre à jour les statistiques
function updateStats(nbEtudiants, nbECs, nbCotes) {
  const countEtudiants = document.getElementById('count-etudiants');
  const countEcs = document.getElementById('count-ecs');
  const countCotesElement = document.getElementById('count-cotes');
  
  if (countEtudiants) countEtudiants.textContent = nbEtudiants;
  if (countEcs) countEcs.textContent = nbECs;
  if (countCotesElement) countCotesElement.textContent = nbCotes;
}

// Appliquer les couleurs selon la note (style Google Sheets)
function applyCellColor(cell, valeur) {
  cell.classList.remove('note-echec', 'note-normal', 'note-excellent');
  
  if (valeur === "" || valeur === null) return;
  
  const note = parseFloat(valeur);
  if (isNaN(note)) return;
  
  if (note < 10) {
    cell.classList.add('note-echec'); // Rouge
  } else if (note >= 10 && note <= 20) {
    cell.classList.add('note-normal'); // Blanc
  } else if (note > 20) {
    cell.classList.add('note-excellent'); // Vert
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
                Afficher_EC_aligne_delibe();
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
                // Après l'ajout, réaffichez la table tout en gardant le focus sur le bon input
                const activeElement = document.activeElement; // Sauvegarder l'élément actif
                Afficher_EC_aligne_delibe(); // Réafficher la table
                console.log(" element actif "+activeElement)
                // Rétablir le focus sur l'input actif
                if (activeElement) {
                    const newInput = document.querySelector(`input[value="${activeElement.value}"]`);
                    console.log(" nouv focus "+newInput);
                    if (newInput) {
                        newInput.focus();
                    }
                }
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
                Afficher_EC_aligne_delibe();
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
  



  