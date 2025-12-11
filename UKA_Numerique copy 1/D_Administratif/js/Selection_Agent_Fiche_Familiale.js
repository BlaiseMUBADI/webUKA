
// Fonction pour afficher les informations de l'agent
function AfficherInfoAgent() {
  return new Promise((resolve, reject) => {
    const txtMatricule = document.getElementById("MatAgent").value;
    var urlParams = new URLSearchParams();
    urlParams.append('matricule', txtMatricule);

    console.log("mat" + txtMatricule);

    fetch('D_Administratif/API/API_Select_Agent_Fiche_Familiale.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: urlParams
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            reject(data.error);
        } else {
            document.getElementById("LieuNais").innerText = data[0].Lieu + ", le " + data[0].DateNaissance;
            document.getElementById("Etatciv").innerText = data[0].EtatCivil;
            document.getElementById("DateEnga").innerText = data[0].Date_Engagement	;
            document.getElementById("Niveau").innerText = data[0].Niveau_Etude	;
            document.getElementById("AnneDiplome").innerText = data[0].Annee_Obt	;
            document.getElementById("Institution").innerText = data[0].Institution	;
            document.getElementById("domaine").innerText = data[0].Domaine	;
            document.getElementById("Adr").innerText = data[0].AdressePhysique	;
            document.getElementById("tel").innerText = data[0].Tel	;
            document.getElementById("mail").innerText = data[0].Mail	;
            document.getElementById("grade").innerText = data[0].Grade	;

            if (/PO|P|PA|CT|ASS2|ASS1|ASSR1|CPP/.test(data[0].Grade)) {
             
              document.getElementById("fonction").innerText = "Enseigant";
  
            } else {
              document.getElementById("fonction").innerText = "-"	;
  
            }

            //document.getElementById("fonction").innerText = data[0].Fonction	;

            if (/NU/.test(data[0].Mat_agent)) {
             
            document.getElementById("matr").innerText = "NU";

          } else {
            document.getElementById("matr").innerText = data[0].Mat_agent	;

          }
            resolve(); // Résoudre la promesse une fois les données chargées
        }
    })
    .catch(error => {
        reject("Erreur lors de la communication avec l'API Afficher Liste: " + error);
    });
  });
}

// Fonction pour afficher les enfants
function AfficherEnfant() {
  return new Promise((resolve, reject) => {
    const txtMatricule = document.getElementById("MatAgent").value;
    let tables = document.querySelectorAll(".tableEnfant"); // Sélectionner tous les tableaux ayant la classe "tableEnfant"
    
    tables.forEach(table => {
      table.classList.add("table", "table-bordered");

      // Suppression de tout contenu existant dans le tableau
      while (table.firstChild) {
        table.removeChild(table.firstChild);
      }

      // Création du header
      var thead = document.createElement("thead");
      thead.classList.add("sticky-sm-top", "m-0", "fw-bold");
      var tr = document.createElement("tr");
      tr.style = "background-color:midnightblue; color:white; text-align:center;";
      var headers = ["N°", "Nom complet", "Lieu de naissance", "Date de naissance"];
      headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
      });
      thead.appendChild(tr);
      table.appendChild(thead);

      var tbody = document.createElement("tbody");

      var url = 'D_Administratif/API/API_Select_Enfant.php?mat=' + txtMatricule;

      fetch(url)
        .then(response => {
          if (!response.ok) {
            throw new Error('Erreur lors de la récupération des données');
          }
          return response.json();
        })
        .then(data => {
          if (data && Array.isArray(data)) {
            let i = 1;
            data.forEach(tos => {
              var tr = document.createElement("tr");
              var tdnum = document.createElement("td");
              tdnum.textContent = i;
              var tdnom = document.createElement("td");
              var tdlieu = document.createElement("td");
              var tddate = document.createElement("td");

              // Vérification des propriétés de chaque élément
              tdnom.textContent = tos.Noms ? tos.Noms : 'Inconnu';
              tdlieu.textContent = tos.Lieu_Naissance ? tos.Lieu_Naissance : 'Inconnu';
              tddate.textContent = tos.DateNaissance ? tos.DateNaissance : 'Inconnu';

              tr.appendChild(tdnum);
              tr.appendChild(tdnom);
              tr.appendChild(tdlieu);
              tr.appendChild(tddate);
              tbody.appendChild(tr);

              i++;
            });
          } else {
            console.log("Aucune donnée valide reçue.");
          }
          resolve(); // Résoudre la promesse une fois les données chargées
        })
        .catch(error => {
          reject("Erreur lors de la récupération des données: " + error);
        })
        .finally(() => {
          table.appendChild(tbody);
        });
    });
  });
}

// Fonction d'impression
function imprimerFiche() {
  var entete = document.getElementById('entetepage').innerHTML;
  var fenetreImpression = window.open('', '', 'height=600,width=800');
  fenetreImpression.document.write('<html><head><title>Impression Rapport de Paie</title>');
  fenetreImpression.document.write('<style>');
  fenetreImpression.document.write('body { font-family: Arial, sans-serif; }');
  fenetreImpression.document.write('table { width: 100%; border-collapse: collapse; }');
  fenetreImpression.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
  fenetreImpression.document.write('thead { background-color: midnightblue; color: white; }');
  fenetreImpression.document.write('</style>');
  fenetreImpression.document.write('</head><body>');
  fenetreImpression.document.write(entete);
  fenetreImpression.document.write('</body></html>');
  fenetreImpression.document.close();
  fenetreImpression.print();
  fenetreImpression.close();
}

// Gestionnaire d'événement pour le bouton 'imprimerFiche'
document.getElementById('imprimerFiche').addEventListener('click', function() {
  
      imprimerFiche(); // Une fois que les deux fonctions sont terminées, imprimer
      document.getElementById("Datesig").innerText=new Date(document.getElementById("datesign").value).toLocaleDateString('fr-FR');
});

// ENREGISTREMENT DES ENFANTS

function enregistrementEnfant() 
        {

          const txtMatricule = document.getElementById("MatAgent").value;
          const txtnomEnfant = document.getElementById("nomEnfant").value;
          const txtlieu = document.getElementById("LieuNaisse").value;
          const txtdate = document.getElementById("Datenaisse").value;

         
          var url = 'D_Administratif/API/API_Ajout_Enfants.php?mat=' + txtMatricule+'&nom='+txtnomEnfant+'&lieu='+txtlieu+'&daten='+txtdate;

            fetch(url)
            .then(response => response.json())
            .then(data => {
                swal({
                    title: data.success ? "Succès" : "Erreur",
                    text: data.message,
                    icon: data.success ? "success" : "error",
                    button: "OK",
                    closeOnClickOutside: false,
                    closeOnEsc: false
                }).then(() => {
                    if (data.success) {
                      AfficherParent();
                    }
                });
            })
            .catch(error => {
                alert("Erreur lors de l'enregistrement : " + error);
            });
        }
        //********************************BOUTON****************************************************** */
        document.getElementById('enregistrerEnfant').addEventListener('click', function() {
          //console.log("Nous avons cliqué sur le bouton Enregistrer enfant");
          enregistrementEnfant();
          AfficherEnfant();
          AfficherNbreParent();
        });
        document.getElementById('FormAjoutEnfant').addEventListener('click', function() {
          document.getElementById('FormEnfants').style.display = 'block';
            document.getElementById('fondTransparentEnfant').style.display = 'block';
            AfficherEnfant();
        });
        document.getElementById('EnregistrerParent').addEventListener('click', function() {
          enregistrementParent();
          AfficherNbreParent();

        });
        document.getElementById('FormAjoutParent').addEventListener('click', function() {
          document.getElementById('nouveauForm').style.display = 'block';
          document.getElementById('fondTransparent').style.display = 'block';
          
        });
        document.addEventListener("DOMContentLoaded", (event) => {
          AfficherEnfant();
          AfficherParent();
          AfficherNbreParent();
          calendrier();
          AfficherInfoAgent();
          setCurrentDate();
          
        });
        function setCurrentDate() {
          let today = new Date().toISOString().split('T')[0];
          document.getElementById('datesign').value = today;
      }
      
        document.getElementById('famille').addEventListener('click',  AfficherEnfant);

        document.getElementById('conge').addEventListener('click',  AfficherFormulaireCongé);

        function AfficherFormulaireCongé() {
          document.getElementById('FormConge').style.display = 'block';
          document.getElementById('fondTransparentConge').style.display = 'block';
          console.log("tos");
      }


        //ENREGISTREMENT PARENTS
        function enregistrementParent() 
        {
          const txtMatricule = document.getElementById("MatAgent").value;

          const txtnom = document.getElementById("noms").value;
          const txtStatut = document.getElementById("statut").value;
          const txtannedec = document.getElementById("anneeDeces").value;
          const nbrparent = document.getElementById("Nombre_parent").innerText;
          //console.log("le nbre parents"+nbrparent);

          var url = 'D_Administratif/API/API_Ajouter_Parents_Agents.php?mat=' + txtMatricule+'&noms='+txtnom+'&statut='+txtStatut+
                      '&anneedeces='+txtannedec+'&NbrParent='+nbrparent;

            fetch(url)
            .then(response => response.json())
            .then(data => {
                swal({
                    title: data.success ? "Succès" : "Erreur",
                    text: data.message,
                    icon: data.success ? "success" : "error",
                    button: "OK",
                    closeOnClickOutside: false,
                    closeOnEsc: false
                }).then(() => {
                    if (data.success) {
                       
                        //AfficherParent();
                        //AfficherNbreParent();
                    }
                });
            })
            .catch(error => {
                alert("Erreur lors de l'enregistrement : " + error);
            });
        }
        //*********************************************AFFICHER LES PARENTS**************************************
        function AfficherParent()
  {
    
    let tableParent = document.getElementById("TableParent");
    tableParent.classList.add("table", "table-bordered");
    while (tableParent.firstChild) {
        tableParent.removeChild(tableParent.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Nom complet", "Statut", "Année décès"];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    tableParent.appendChild(thead);

    var tbody = document.createElement("tbody");
    
    const txtMatricule = document.getElementById("MatAgent").value;

    var url = 'D_Administratif/API/API_Select_Parents.php?mat=' + txtMatricule;

      fetch(url)
      
        .then(response => response.json())
        .then(donnees => {
          let i = 1; // Initialiser la numérotation

          donnees.forEach(tos => {
          var tr = document.createElement("tr");

          var tdnum = document.createElement("td");
          tdnum.textContent = i;

          var tdnom = document.createElement("td");
          var tdlieu = document.createElement("td");
          var tddate = document.createElement("td");
          

          
          tdnom.textContent = tos.Noms;
          tdlieu.textContent = tos.Statut;
          tddate.textContent = tos.annee_dec;


          tr.appendChild(tdnum);
          tr.appendChild(tdnom);
          tr.appendChild(tdlieu);
          tr.appendChild(tddate);
     

          tbody.appendChild(tr);
          i++;
    
        });
  
        
      }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
            tableParent.appendChild(tbody);
                
  }
  //*******************************AFFICHER LE NOMBRE DE PARENT ET D'ENFANTS POUR UN AGENT******************* */
  function AfficherNbreParent()
  {
    
    const txtMatricule = document.getElementById("MatAgent").value;

    var url = 'D_Administratif/API/API_Select_Nbre_Parent.php?mat=' + txtMatricule;

      fetch(url)
        .then(response => response.json())
        .then(data => {
          
         
            document.getElementById("Nombre_parent").innerText=data.nombre_de_parents;
            document.getElementById("Nombre_parent_en_Vie").innerText=data.nombre_de_parents-data.nombre_de_parents_decedes;
            document.getElementById("Nombre_parent_decede").innerText=data.nombre_de_parents_decedes;
            document.getElementById("Nombre_enfant").innerText=data.nombre_enfants;
            document.getElementById("Nombre_enfant_fiche").innerText=data.nombre_enfants;

    
  
        
      }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
  }
  //***************************CALENDRIER************************* */
 
  function calendrier() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr', // Localisation en français
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Agenda'
        },
        events: {
            url: 'D_Administratif/API/API_Select_Events.php', // URL de votre source d'événements
            method: 'GET', // Méthode HTTP à utiliser
            failure: function() {
                alert('Erreur de chargement des événements !'); // Message d'erreur en cas d'échec
            }
        },
        views: {
            dayGridMonth: {
                titleFormat: { year: 'numeric', month: 'long' }
            },
            dayGrid: {
                dayCellContent: function(e) {
                    e.dayNumberText = e.dayNumberText.replace(' ', '');
                }
            }
        },
        eventClick: function(info) {
            // Exemple de code pour afficher les détails de l'événement dans une fenêtre modale
          
            var eventDetails = 'Événement: ' + info.event.title + '\nDate: ' + info.event.start.toLocaleDateString() + '\nDétails: ' + info.event.extendedProps.details
           
            alert(eventDetails);
        }
    });
    calendar.render();
}


    