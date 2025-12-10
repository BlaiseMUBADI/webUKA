(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });

    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });

    // Progress Bar
    $('.pg-bar').waypoint(function () {
        $('.progress .progress-bar').each(function () {
            $(this).css("width", $(this).attr("aria-valuenow") + '%');
        });
    }, { offset: '80%' });

    // Calender
    $('#calender').datetimepicker({
        inline: true,
        format: 'L'
    });

    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        items: 1,
        dots: true,
        loop: true,
        nav: false
    });

    ///////////////////////////////////////////////////////////////
    // Variables pour les pourcentages par filière
    ///////////////////////////////////////////////////////////////
    var b_med = 0, r_med = 8, d_med = 12;
    var b_info = 0, r_info = 35, d_info = 25;
    var b_droit = 0, r_droit = 40, d_droit = 45;
    var b_eco = 0, r_eco = 60, d_eco = 55;
    var b_archi = 0, r_archi = 70, d_archi = 100;
    var b_commu = 0, r_commu = 55, d_commu = 70;
    var b_poly = 0, r_poly = 75, d_poly = 60;
    var b_agro = 0, r_agro = 56, d_agro = 43;

    document.addEventListener('DOMContentLoaded', function () {
        console.log("DOM chargé, on exécute les fonctions !");
        actualise_graphique();
        actualisation();
    });

    // Fonction pour actualiser le graphique des filières
    function actualise_graphique() {
        const url1 = 'D_Comptable_ERP/API_PHP/graphique.php';

        fetch(url1)
            .then(response => response.json())
            .then(data => {
                data.forEach(infos => {
                    const libelle = infos.Libelle_Filiere;
                    switch (libelle) {
                        case "Agronomie et Environnement":
                            b_agro = infos.Pourcentage_Paye;
                            r_agro = infos.Pourcentage_Recettes;
                            d_agro = infos.Pourcentage_Depenses;
                            break;
                        case "Architecture et Construction":
                            b_archi = infos.Pourcentage_Paye;
                            r_archi = infos.Pourcentage_Recettes;
                            d_archi = infos.Pourcentage_Depenses;
                            break;
                        case "Culture et Communication":
                            b_commu = infos.Pourcentage_Paye;
                            r_commu = infos.Pourcentage_Recettes;
                            d_commu = infos.Pourcentage_Depenses;
                            break;
                        case "Droit":
                            b_droit = infos.Pourcentage_Paye;
                            r_droit = infos.Pourcentage_Recettes;
                            d_droit = infos.Pourcentage_Depenses;
                            break;
                        case "Médecine":
                            b_med = infos.Pourcentage_Paye;
                            r_med = infos.Pourcentage_Recettes;
                            d_med = infos.Pourcentage_Depenses;
                            break;
                        case "Polytechnique":
                            b_poly = infos.Pourcentage_Paye;
                            r_poly = infos.Pourcentage_Recettes;
                            d_poly = infos.Pourcentage_Depenses;
                            break;
                        case "Sciences Economiques et de Gestion":
                            b_eco = infos.Pourcentage_Paye;
                            r_eco = infos.Pourcentage_Recettes;
                            d_eco = infos.Pourcentage_Depenses;
                            break;
                        case "Sciences Informatiques":
                            b_info = infos.Pourcentage_Paye;
                            r_info = infos.Pourcentage_Recettes;
                            d_info = infos.Pourcentage_Depenses;
                            break;
                    }
                });

                updateGraphique();
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données :", error);
            });
    }

    function updateGraphique() {
        var ctx1 = $("#worldwide-sales").get(0).getContext("2d");
        new Chart(ctx1, {
            type: "bar",
            data: {
                labels: ["Médec.", "Info.", "Droit", "Eco.", "Archi.", "Commu.", "Polyt.", "Agro."],
                datasets: [
                    {
                        label: "Entrée réelle",
                        data: [b_med, b_info, b_droit, b_eco, b_archi, b_commu, b_poly, b_agro],
                        backgroundColor: "red"
                    },
                    {
                        label: "Recettes prévues",
                        data: [r_med, r_info, r_droit, r_eco, r_archi, r_commu, r_poly, r_agro],
                        backgroundColor: "rgba(0, 156, 255, .5)"
                    },
                    {
                        label: "Dépenses prévues",
                        data: [d_med, d_info, d_droit, d_eco, d_archi, d_commu, d_poly, d_agro],
                        backgroundColor: "rgba(0, 156, 255, .3)"
                    }
                ]
            },
            options: {
                responsive: true
            }
        });
    }

    ///////////////////////////////////////////////////////////////
    // Graphique par année académique (exemple statique)
    ///////////////////////////////////////////////////////////////
    var ctx2 = $("#salse-revenue").get(0).getContext("2d");
    new Chart(ctx2, {
        type: "line",
        data: {
            labels: ["2021-2022", "2022-2023", "2023-2024", "2024-2025", "2025-2026"],
            datasets: [
                {
                    label: "Recettes prévues",
                    data: [15, 30, 55, 75],
                    backgroundColor: "rgba(0, 156, 255, .5)",
                    fill: true
                },
                {
                    label: "Dépenses prévues",
                    data: [99, 135, 170, 30],
                    backgroundColor: "rgba(0, 156, 255, .3)",
                    fill: true
                }
            ]
        },
        options: {
            responsive: true
        }
    });

    ///////////////////////////////////////////////////////////////
    // Graphiques divers (ligne, barre, camembert)
    ///////////////////////////////////////////////////////////////
    var ctx3 = $("#line-chart").get(0).getContext("2d");
    new Chart(ctx3, {
        type: "line",
        data: {
            labels: [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150],
            datasets: [{
                label: "Salse",
                fill: false,
                backgroundColor: "rgba(0, 156, 255, .3)",
                data: [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 15]
            }]
        },
        options: {
            responsive: true
        }
    });

    var ctx4 = $("#bar-chart").get(0).getContext("2d");
    new Chart(ctx4, {
        type: "bar",
        data: {
            labels: ["Italy", "France", "Spain", "USA", "Argentina"],
            datasets: [{
                backgroundColor: [
                    "rgba(0, 156, 255, .7)",
                    "rgba(0, 156, 255, .6)",
                    "rgba(0, 156, 255, .5)",
                    "rgba(0, 156, 255, .4)",
                    "rgba(0, 156, 255, .3)"
                ],
                data: [55, 49, 44, 24, 15]
            }]
        },
        options: {
            responsive: true
        }
    });

    var ctx5 = $("#pie-chart").get(0).getContext("2d");
    new Chart(ctx5, {
        type: "pie",
        data: {
            labels: ["Italy", "France", "Spain", "USA", "Argentina"],
            datasets: [{
                backgroundColor: [
                    "rgba(0, 156, 255, .7)",
                    "rgba(0, 156, 255, .6)",
                    "rgba(0, 156, 255, .5)",
                    "rgba(0, 156, 255, .4)",
                    "rgba(0, 156, 255, .3)"
                ],
                data: [55, 49, 44, 24, 15]
            }]
        },
        options: {
            responsive: true
        }
    });

    ///////////////////////////////////////////////////////////////
    // Générer des couleurs automatiquement pour le donut
    ///////////////////////////////////////////////////////////////
    function generateColors(baseColor, count) {
        const base = {
            r: baseColor[0],
            g: baseColor[1],
            b: baseColor[2],
            a: baseColor[3] || 1
        };

        const colors = [];
        for (let i = 0; i < count; i++) {
            const offset = i * 15;
            const r = (base.r + offset) % 256;
            const g = (base.g + offset * 2) % 256;
            const b = (base.b + offset * 3) % 256;
            colors.push(`rgba(${r}, ${g}, ${b}, ${base.a})`);
        }
        return colors;
    }

    ///////////////////////////////////////////////////////////////
    // Affichage du graphique donut des rubriques
    ///////////////////////////////////////////////////////////////
    function actualisation() {
        const url = 'D_Comptable_ERP/API_PHP/fonction_rubrique_1.php?action=tableau_general_tout_graphique';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const labels = data.labels;
                const amounts = data.data;

                const couleurs = generateColors([0, 156, 255, 0.7], labels.length);

              const ctx6 = $("#doughnut-chart").get(0).getContext("2d");
new Chart(ctx6, {
    type: "doughnut",
    data: {
        labels: labels,
        datasets: [{
            backgroundColor: couleurs,
            data: amounts
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        let label = context.label || '';
                        let value = context.raw || 0;
                        return `${label}: ${value.toLocaleString()} $`;
                    }
                }
            }
        }
    }
});
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données :', error);
            });
    }

})(jQuery); // ✅ FIN de la fonction auto-invoquée
