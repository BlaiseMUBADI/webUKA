
var pieChart = null;

function AfficherNbreAgent() {
    var url = 'D_Administratif/API/API_Tableau_de_Bord.php';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log("Données reçues de l'API:", data);

            // Vérifiez les données reçues et utilisez une valeur par défaut de 0 si les données sont manquantes
            var nombreAgentAcad = parseInt(data.find(item => item.idCategorie === 1)?.nombre_agent, 10) || 0;
            var nombreAgentScient = parseInt(data.find(item => item.idCategorie === 2)?.nombre_agent, 10) || 0;
            var nombreAgentAdmin = parseInt(data.find(item => item.idCategorie === 3)?.nombre_agent, 10) || 0;
            var nombreAgentPato = parseInt(data.find(item => item.idCategorie === 4)?.nombre_agent, 10) || 0;

            // Calcul du nombre total d'agents
            var nombreTotal = nombreAgentAcad + nombreAgentAdmin + nombreAgentScient + nombreAgentPato;
            console.log("Nombre d'agents après conversion:", nombreAgentAcad);

            // Calcul des pourcentages
            var pourcentageAcad = ((nombreAgentAcad+nombreAgentScient) / nombreTotal * 100).toFixed(2);
            //var pourcentageScient = (nombreAgentScient / nombreTotal * 100).toFixed(2);
            var pourcentageAdmin = (nombreAgentAdmin / nombreTotal * 100).toFixed(2);
            var pourcentagePato = (nombreAgentPato / nombreTotal * 100).toFixed(2);

            // Vérification et affectation des attributs data-target
            document.getElementById("academicPersonnel").setAttribute('data-target', nombreAgentAcad);
            document.getElementById("scientificPersonnel").setAttribute('data-target', nombreAgentScient);
            document.getElementById("PersonnelAdmin").setAttribute('data-target', nombreAgentAdmin);
            document.getElementById("PersonnelPato").setAttribute('data-target', nombreAgentPato);
            document.getElementById("total").setAttribute('data-target', nombreTotal);

            console.log("Attributs data-target définis avec des pourcentages.");

            // Démarrer les compteurs après avoir défini data-target
            initCompteurs();

            // Mettre à jour le graphique avec les nouvelles données
            graphique(pourcentageAcad, pourcentageAdmin, pourcentagePato);
        })
        .catch(error => {
            console.log("Erreur lors de contact de l'API Afficher Liste : " + error);
        });
}

function graphique(pourcentageAcad, pourcentageAdmin, pourcentagePato) {
    // Détruisez le graphique existant s'il existe
    if (pieChart) {
        pieChart.destroy();
    }
    var ctx = document.getElementById('pieChart').getContext('2d');
    pieChart = new Chart(ctx, {
        type: 'pie',  // Type de graphique (secteur)
        data: {
            labels: ['Personnel Académique et Scientifique', 'Personnel Administratif', 'PATO'],  // Labels (noms des catégories)
            datasets: [{
                label: 'Répartition en %',
                data: [pourcentageAcad, pourcentageAdmin, pourcentagePato],  // Données dynamiques
                backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A6'],  // Couleurs des secteurs
                borderColor: '#ffffff',  // Couleur de la bordure des secteurs
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,  // Graphique réactif
            maintainAspectRatio: false,  // Ne pas maintenir le ratio d'aspect
            animation: {
                duration: 2000,  // Durée de l'animation
                easing: 'easeOutBounce',  // Type d'animation
                animateRotate: true,  // Animation de rotation activée
                animateScale: true  // Animation de mise à l'échelle activée
            },
            plugins: {
                legend: {
                    position: 'top',  // Position de la légende (en haut)
                    labels: {
                        color: '#ffffff'  // Couleur du texte des labels de la légende
                    }
                },
                tooltip: {
                    enabled: true,  // Tooltip activé pour afficher les valeurs
                },
                datalabels: {
                    formatter: function(value) {
                        return value + '%';
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',size: 16 
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}
document.addEventListener("DOMContentLoaded", function() {
    AfficherNbreAgent();
});


function initCompteurs() {
    let compteurElements = document.querySelectorAll(".compteur");

    compteurElements.forEach(compteurElement => {
        let target = parseInt(compteurElement.getAttribute("data-target"));
        console.log("Valeur de data-target dans la fonction de mise à jour du compteur:", target); // Vérifiez la valeur de data-target

        if (!isNaN(target)) {
            let count = 0;

            function updateCounter() {
                if (count < target) {
                    count++;
                    compteurElement.textContent = count;
                    // Vitesse de mise à jour du compteur (ici toutes les 20 millisecondes)
                    setTimeout(updateCounter, 300);
                } else {
                    compteurElement.textContent = target;
                }
            }

            // Démarre le compteur pour chaque élément
            updateCounter();
        } else {
            console.log("Erreur : data-target n'est pas un nombre valide");
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    AfficherNbreAgent();
    graphique();
});
