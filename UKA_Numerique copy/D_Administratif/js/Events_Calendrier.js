

// ENREGISTREMENT DES ENFANTS

function enregistrementEvents() 
        {

          const txtTitre = document.getElementById("titre").value;
          const txtDateDebut = document.getElementById("date-deb").value;
          const txtDateFin = document.getElementById("date-fi").value;
          const txtdetails = document.getElementById("descrtiption").value;
          

         //console.log("details"+txtdetails);
          var url = 'D_Administratif/API/API_Ajout_Events.php?titre=' + txtTitre+'&datedebut='+txtDateDebut+'&datefin='+txtDateFin+'&details='+txtdetails;

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
        document.getElementById('Save_Events').addEventListener('click', function() {
           console.log("tos");
           enregistrementEvents();
          });



        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById('Ajouter_evenement_au_calendrier')!==null)
                {
                    calendrier_mise_a_jr();
                }
           
       
        });

  
        
  function calendrier_mise_a_jr() {
    var calendarEl = document.getElementById('calendrier');
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
        }
    });
    calendar.render();
}


    