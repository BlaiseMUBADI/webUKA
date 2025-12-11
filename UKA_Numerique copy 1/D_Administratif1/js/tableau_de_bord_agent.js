
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

            // Vérification et affectation des attributs data-target
            document.getElementById("academicPersonnel").setAttribute('data-target', nombreAgentAcad);
            document.getElementById("scientificPersonnel").setAttribute('data-target', nombreAgentScient);
            document.getElementById("PersonnelAdmin").setAttribute('data-target', nombreAgentAdmin);
            document.getElementById("PersonnelPato").setAttribute('data-target', nombreAgentPato);
            document.getElementById("total").setAttribute('data-target', nombreTotal);

            console.log("Attribut data-target défini:", document.getElementById("academicPersonnel").getAttribute('data-target'));

            // Démarrer les compteurs après avoir défini data-target
            initCompteurs();
        })
        .catch(error => {
            console.log("Erreur lors de contact de l'API Afficher Liste : " + error);
        });
}

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
});
