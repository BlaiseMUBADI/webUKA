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

      // Boucle pour afficher les td contenant les INPUT pour la saisi de côtes
      tab_ECs_aligne.forEach((ec_s_aligne, index) => {
          const td_input = document.createElement('td');
          td_input.classList.add("text-center");

          var div = document.createElement("div");
          div.classList.add("row", "text-center", "p-0", "m-0");
          div.style.display = "flex";
          div.style.justifyContent = "center";
          div.style.alignItems = "center";

          var input = document.createElement("input");
          input.style.textAlign = "center"; // Correction de l'orthographe
          input.style.width = "50px"; // Définir la largeur de l'input
          input.style.height = "30px"; // Définir la hauteur de l'input
          input.style.fontSize = "18px"; // Définir la taille de la police
          input.style.fontFamily = "Arial, sans-serif"; // Définir la police
          input.style.fontWeight = "bold"; // Mettre le texte en gras

          // Vérifier si une cote existe pour cet étudiant et cet EC
          let cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec_s_aligne.id_ec_aligne);
          
          
          if (cote) {
            //console.log(" matr :"+etudiant.Matricule+" cote "+cote);
            let numericCote = parseFloat(cote.Cote);
            input.value = cote.Cote;
             // Convert cote to a numeric value
          
            if (numericCote < 10) {
              input.style.backgroundColor = "rgb(238, 96, 96)";
            } else if (numericCote > 20) {
              input.style.backgroundColor = "rgb(106, 221, 94)";
            } 
          }

          // Attacher l'événement blur
          input.addEventListener('blur', (event) => {
             
            let numeric_input = parseFloat(input.value); // Convert cote to a numeric value          
            if (numeric_input < 10) {
              input.style.backgroundColor = "rgb(238, 96, 96)";
            } else if (numeric_input > 20) {
              input.style.backgroundColor = "rgb(106, 221, 94)";
            } 


            // Suppression de la cote 
            if(cote && input.value==="")  Suppression(etudiant.Matricule, ec_s_aligne.id_ec_aligne);
            if(cote && input.value!=="")  Modifier_cote(etudiant.Matricule, ec_s_aligne.id_ec_aligne, input.value)
            if(!cote && input.value!=="")  Ajout_point_Obtenu(etudiant.Matricule, ec_s_aligne.id_ec_aligne, input.value)

        
              //Ajout_point_Obtenu(etudiant.Matricule, ec_s_aligne.id_ec_aligne, input.value)
          });

          // Attacher les événements pour les touches de direction
          input.addEventListener('keydown', (event) => {
              const row = div.parentElement.parentElement;
              const rowIndex = Array.from(row.parentElement.children).indexOf(row);
              const colIndex = Array.from(row.children).indexOf(td_input);

              // Il nous permet de naviguer dans nos INPUTS
              switch (event.key) {
                  case 'ArrowLeft':
                      if (colIndex > 0) {
                          row.children[colIndex - 1].querySelector('input').focus();
                      }
                      break;
                  case 'ArrowRight':
                      if (colIndex < row.children.length - 1) {
                          row.children[colIndex + 1].querySelector('input').focus();
                      }
                      break;
                  case 'ArrowUp':
                      if (rowIndex > 0) {
                          row.parentElement.children[rowIndex - 1].children[colIndex].querySelector('input').focus();
                      }
                      break;
                  case 'ArrowDown':
                      if (rowIndex < row.parentElement.children.length - 1) {
                          row.parentElement.children[rowIndex + 1].children[colIndex].querySelector('input').focus();
                      }
                      break;
              }
          });

          div.appendChild(input);
          td_input.appendChild(div);

          tr.appendChild(td_input);
      });

      tbody.appendChild(tr);
      i++;
  });

  table_encodage.appendChild(thead);
  table_encodage.appendChild(tbody);
  table_encodage.classList.add("table-bordered");
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
  



  