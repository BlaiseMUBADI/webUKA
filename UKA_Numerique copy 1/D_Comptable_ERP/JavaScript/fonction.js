function afficher_resultat(){
    var recette = document.getElementById("montant1").value;
    var depense = document.getElementById("montant2").value;
    var resultat_resultat = (recette - depense);
    console.log('le resultat est :' + (recette - depense));

    if (resultat_resultat < 0) {
        document.getElementById('resultat_final').textContent = resultat_resultat.toLocaleString('fr-FR') + ' $';
        document.getElementById('text_resultat').textContent = "Il y a déficit, parce que le résultat est négatif";
        document.getElementById('text_resultat').classList.add('text-danger');

    } else if (resultat_resultat == 0) {
        document.getElementById('resultat_final').textContent = resultat_resultat.toLocaleString('fr-FR') + ' $';
        document.getElementById('text_resultat').textContent = "Le résultat est équilibré ou sans perte.";
        document.getElementById('text_resultat').classList.add('text-primary');

    } else if (resultat_resultat > 0) {
        document.getElementById('resultat_final').textContent = resultat_resultat.toLocaleString('fr-FR') + ' $';
        document.getElementById('text_resultat').textContent = "Il y a un excédent ou bénéfice.";
        document.getElementById('text_resultat').classList.add('text-success');
    }

    document.getElementById('bouton_resultat').style.display = 'none';
}

////////////////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Choisissez une option",
        allowClear: true,
        width: 'resolve'
    });

    $('#faculte_rubrique').on('select2:select', function (e) {
        const valeurFiliere = e.params.data.id;
        const anneeAcademique = $('#annee_academique_rubrique').val();
        affichage_promotion_select(valeurFiliere, anneeAcademique);
    });

    function affichage_promotion_select(valeur_Filiere, anneeAcademique) {
        const url = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=affiche_promotion&valeurFiliere=' + valeur_Filiere;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                var select = document.getElementById("promotion_rubrique");
                select.innerHTML = "";
                data.forEach(promo => {
                    var option = document.createElement("option");
                    option.value = promo.Code_Promotion;
                    option.textContent = promo.Promotion;
                    select.appendChild(option);
                });
            });
    }
var texte_promotion;
var anneeAcademique_text;
var faculte;
    $('#promotion_rubrique').on('select2:select', function (e) {
        const valeur_promotion = e.params.data.id;
        texte_promotion = e.params.data.text;
        const anneeAcademique = $('#annee_academique_rubrique').val();
        anneeAcademique_text = $('#annee_academique_rubrique option:selected').text();
        faculte = $('#faculte_rubrique option:selected').text();
        affichage_rubrique(valeur_promotion, anneeAcademique,);
    });

    function affichage_rubrique(valeur_promotion, annee_academique) {
        const tableau = document.getElementById("table_rubrique");
        var tbody = document.createElement("tbody");

        while (tableau.rows.length > 1) {
            tableau.deleteRow(1);
        }

        const url1 = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=tableau_rubrique&valeur_promotion=' + valeur_promotion + '&annee_academique=' + annee_academique;
        var i = 1;

        fetch(url1)
            .then(response => response.json())
            .then(data => {
                data.forEach(infos => {
                    var tr = document.createElement("tr");
                    var tdnum = document.createElement("td");
                    tdnum.textContent = i;

                    var tdlibelle = document.createElement("td");
                    var tdPeriodicite = document.createElement("td");
                    var tdAnnee = document.createElement("td");
                    var tdbouton = document.createElement("td");

                    tdlibelle.textContent = infos.Libelle_Rubrique;
                    tdPeriodicite.textContent = infos.Pourcentage;
                    tdAnnee.textContent = infos.Montant;

                    document.getElementById('total_frais').textContent = infos.TotalMontant + " $";

                    var bouton = document.createElement('button');
                    bouton.className = 'btn btn-success';
                    bouton.id = 'supprimer_rubrique';
                    bouton.title = 'supprimier';

                    bouton.addEventListener("click", function () {
                        supprimer(infos.Id_repartition, "supprimer_rubrique");
                        affichage_rubrique(valeur_promotion, annee_academique);
                    });

                    var icone = document.createElement('i');
                    icone.className = 'fas fa-trash';
                    bouton.appendChild(icone);
                    tdbouton.appendChild(bouton);

                    tr.appendChild(tdnum);
                    tr.appendChild(tdlibelle);
                    tr.appendChild(tdPeriodicite);
                    tr.appendChild(tdAnnee);
                    tr.appendChild(tdbouton);
                    tbody.appendChild(tr);
                    i++;
                });
            });

        tableau.appendChild(tbody);

        $('#imprimer_prevue').on('click', function () {
    const total = document.getElementById('total_frais').textContent || "0 $";
    imprimerTableau("table_rubrique", total, texte_promotion, faculte, anneeAcademique_text);
});
    }

    function enregistrer_ajout_rubrique() {
        const anneeAcademique = $('#annee_academique_rubrique').val();
        const promotion = $('#promotion_rubrique').val();
        const rubrique = $('#rubrique').val();
        const montant_rubrique = $('#montant_rubrique').val();

        const url = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=enregistrer_ajout_rubrique&anneeAcademique=' + anneeAcademique + '&promotion=' + promotion + '&rubrique=' + rubrique + '&montant_rubrique=' + montant_rubrique;

        fetch(url)
            .then(() => {
                affichage_rubrique(promotion, anneeAcademique);
                bootstrap.Modal.getInstance(document.getElementById('exampleModal_ajout_rubrique')).hide();
                // Réinitialisation des champs
                $('#rubrique').val(null).trigger('change');
                $('#montant_rubrique').val('');
            });
    }

    function enregistrer_nouvelle_rubrique() {
        const libelle_rubrique = $('#libelle_rubrique').val();
        const categorie_rubrique = $('#categorie_rubrique').val();
	const id_rubrique = $('#id_rubrique').val();

        const url = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=enregistrer_nouvelle_rubrique&libelle_rubrique=' + libelle_rubrique + '&categorie_rubrique=' + categorie_rubrique+'&id_rubrique='+id_rubrique;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let select = document.getElementById("rubrique");
                select.innerHTML = "";
                data.forEach(promo => {
                    var option = document.createElement("option");
                    option.value = promo.Id_rubrique;
                    option.textContent = promo.Libelle;
                    select.appendChild(option);
                });

                bootstrap.Modal.getInstance(document.getElementById('exampleModal_nouvelle_rubrique')).hide();
                $('#libelle_rubrique').val('');
                $('#categorie_rubrique').val(null).trigger('change');
            });
    }

    function supprimer(id, action) {
        const url1 = 'D_Comptable_ERP/API_PHP/supprimer.php?id=' + id + '&action=' + action;
        fetch(url1);
    }

    $('#rubrique_reelle').on('change', function () {
    const valeurRubrique = $(this).val();
    var rubrique = $('#rubrique_reelle option:selected').text();
    const valeurFiliere = $('#faculte_rubrique_reelle').val();
    const anneeAcademique = $('#annee_academique_rubrique_reelle').val();
    var anneeAcademique_text=$('#annee_academique_rubrique_reelle option:selected').text();
    const tableau = document.getElementById("table_rubrique_reelle");
    var tbody = document.createElement("tbody");

    // Réinitialiser le tableau sauf l'en-tête
    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    const url1 = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=tableau_rubrique_reelle&valeurRubrique=' + valeurRubrique + '&valeurFiliere=' + valeurFiliere + '&anneeAcademique=' + anneeAcademique;
    let i = 1;
    let total_rubrique = 0;
    var mention;
    fetch(url1)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
                $('#bouton_imprimer').prop('disabled', true);
                document.getElementById('total_frais_reel').textContent = "0 $";
                return;
            }

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const tdnum = document.createElement("td");
                tdnum.textContent = i;

                const tdlibelle = document.createElement("td");
                const tdPeriodicite = document.createElement("td");
                const tdAnnee = document.createElement("td");
                const tdbouton = document.createElement("td");
                mention = infos.Libelle_Mention;
                tdlibelle.textContent = infos.Libelle_promotion_mention;
                tdPeriodicite.textContent = infos.Montant_total_paye + " / " + infos.Pourcentage_utilise;
                tdAnnee.textContent = infos.Montant_total_rubrique;
                total_rubrique += infos.Montant_total_rubrique;

                const bouton = document.createElement('button');
                bouton.className = 'btn btn-success';
                bouton.id = 'supprimer_rubrique';
                bouton.title = 'supprimer';

                bouton.addEventListener("click", function () {
                    supprimer(infos.Id_repartition, "supprimer_rubrique");
                });

                const icone = document.createElement('i');
                icone.className = 'fas fa-trash';
                bouton.appendChild(icone);
                tdbouton.appendChild(bouton);

                tr.appendChild(tdnum);
                tr.appendChild(tdlibelle);
                tr.appendChild(tdPeriodicite);
                tr.appendChild(tdAnnee);
                //tr.appendChild(tdbouton);
                tbody.appendChild(tr);

                i++;
            });

            tableau.appendChild(tbody);
            document.getElementById('total_frais_reel').textContent = total_rubrique.toFixed(2) + " $";

            // Activer le bouton d'impression
            $('#bouton_imprimer').prop('disabled', false);

            // Connexion du bouton d'impression
            $('#bouton_imprimer').off('click').on('click', function () {
                imprimerTableau("table_rubrique_reelle", total_rubrique.toFixed(2), mention, anneeAcademique_text, rubrique);
            });
        });
});




const date = new Date();
const formatFr = date.getDate().toString().padStart(2, '0') + '/' +
                 (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
                 date.getFullYear();
function imprimerTableau(idTableau, total, texte_promotion, texte_filiere, anneeAcademique_text) {
    const table = document.getElementById(idTableau);
    const w = window.open('', '', 'width=900,height=700');
    w.document.write('<html><head><title>cellule informatique</title>');
    w.document.write('<style>table { width:100%; border-collapse: collapse; } th, td { padding: 10px; border: 1px solid #000; text-align: left; } h2 { text-align: center; }</style>');
    w.document.write('</head><body>');
    w.document.write('<h2>Minister de l\'enseignement Supérieur et Universitaire </br>Université Notre-Dame du Kasayi </br> Administration du Budget</h2>');
    w.document.write(`<h2>${texte_promotion}/${texte_filiere}/${anneeAcademique_text}</h2>`);
    w.document.write(table.outerHTML);
    w.document.write(`<p><strong>Total :</strong> ${total} $</p>`);
    w.document.write(`<p style="float:right;"><strong>Fait à Kananga, le </strong> ${formatFr} </br>L'Administrateur du Budget</p>`);
    w.document.write('</body></html>');
    w.document.close();
    w.print();
}



$('#affiche_global').on('click', function () {
    const anneeAcademique = $('#anne_academique_global').val();
    const Id_rubrique_general = $('#Id_rubrique_general').val();
    const rubrique_generale = $('#Id_rubrique_general option:selected').text();
    var anneeAcademique_generale= $('#anne_academique_global option:selected').text();

    const tableau = document.getElementById("table_rubrique_reelle");
    var tbody = document.createElement("tbody");

    // Réinitialiser le tableau sauf l'en-tête
    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    const url1 = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=tableau_general&anneeAcademique=' + anneeAcademique + '&Id_rubrique_general=' + Id_rubrique_general;
    let i = 1;
    let total_rubrique_general = 0;

    fetch(url1)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
                $('#bouton_imprimer').prop('disabled', true);
                document.getElementById('total_frais_reel').textContent = "0 $";
                return;
            }

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const tdnum = document.createElement("td");
                tdnum.textContent = i;

                const tdlibelle = document.createElement("td");
                const tdPeriodicite = document.createElement("td");
                const tdAnnee = document.createElement("td");
                const tdbouton = document.createElement("td");

                tdlibelle.textContent = infos.Libelle_filiere;
                tdPeriodicite.textContent = infos.Montant_total_paye + " / " + infos.Pourcentage_rubrique;
                tdAnnee.textContent = infos.Montant_rubrique;
                total_rubrique_general += infos.Montant_rubrique;

                const bouton = document.createElement('button');
                bouton.className = 'btn btn-success';
                bouton.id = 'supprimer_rubrique';
                bouton.title = 'supprimer';

                bouton.addEventListener("click", function () {
                    supprimer(infos.Id_repartition, "supprimer_rubrique");
                });

                const icone = document.createElement('i');
                icone.className = 'fas fa-trash';
                bouton.appendChild(icone);
                tdbouton.appendChild(bouton);

                tr.appendChild(tdnum);
                tr.appendChild(tdlibelle);
                tr.appendChild(tdPeriodicite);
                tr.appendChild(tdAnnee);
                //tr.appendChild(tdbouton);
                tbody.appendChild(tr);

                i++;
            });

            tableau.appendChild(tbody);
            document.getElementById('total_frais_reel').textContent = total_rubrique_general.toFixed(2) + " $";
            
            bootstrap.Modal.getInstance(document.getElementById('formulaire_choix')).hide();

            // Activer le bouton d'impression
            $('#bouton_imprimer').prop('disabled', false);

            // ⚡ Important : Quand on clique sur #bouton_imprimer, on imprime
            $('#bouton_imprimer').off('click').on('click', function () {
                imprimerTableau("table_rubrique_reelle", total_rubrique_general.toFixed(2), "Situation Générale", anneeAcademique_generale, rubrique_generale);
            });
        });
});







$('#affiche_global_tout').on('click', function () {
    const anne_academique_global_tout = $('#anne_academique_global_tout').val();
    const Id_filiere_general_tout = $('#Id_filiere_general_tout').val();
    const Id_filiere_general_tout_text = $('#Id_filiere_general_tout option:selected').text();
    var anne_academique_global_tout_text= $('#anne_academique_global_tout option:selected').text();

    const tableau = document.getElementById("table_rubrique_reelle");
    var tbody = document.createElement("tbody");

    // Réinitialiser le tableau sauf l'en-tête
    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    const url1 = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=tableau_general_tout&anne_academique_global_tout=' + anne_academique_global_tout + '&Id_filiere_general_tout=' + Id_filiere_general_tout;
    let i = 1;
    let total_rubrique_general_tout = 0;

    fetch(url1)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
                $('#bouton_imprimer').prop('disabled', true);
                document.getElementById('total_frais_reel').textContent = "0 $";
                return;
            }

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const tdnum = document.createElement("td");
                tdnum.textContent = i;

                const tdlibelle = document.createElement("td");
                const tdPeriodicite = document.createElement("td");
                const tdAnnee = document.createElement("td");
                const tdbouton = document.createElement("td");

                tdlibelle.textContent = infos.Libelle_rubrique;
                tdPeriodicite.textContent = infos.Montant_total_paye;
                tdAnnee.textContent = infos.Montant_rubrique_global;
                total_rubrique_general_tout += infos.Montant_rubrique_global;

                const bouton = document.createElement('button');
                bouton.className = 'btn btn-success';
                bouton.id = 'supprimer_rubrique';
                bouton.title = 'supprimer';

                bouton.addEventListener("click", function () {
                    supprimer(infos.Id_repartition, "supprimer_rubrique");
                });

                const icone = document.createElement('i');
                icone.className = 'fas fa-trash';
                bouton.appendChild(icone);
                tdbouton.appendChild(bouton);

                tr.appendChild(tdnum);
                tr.appendChild(tdlibelle);
                tr.appendChild(tdPeriodicite);
                tr.appendChild(tdAnnee);
                //tr.appendChild(tdbouton);
                tbody.appendChild(tr);

                i++;
            });

            tableau.appendChild(tbody);
            document.getElementById('total_frais_reel').textContent = total_rubrique_general_tout.toFixed(2) + " $";
            
            bootstrap.Modal.getInstance(document.getElementById('formulaire_choix_toutes_rubrique')).hide();

            // Activer le bouton d'impression
            $('#bouton_imprimer').prop('disabled', false);

            // ⚡ Important : Quand on clique sur #bouton_imprimer, on imprime
            $('#bouton_imprimer').off('click').on('click', function () {
                imprimerTableau("table_rubrique_reelle", total_rubrique_general_tout.toFixed(2), "Situation Générale", anne_academique_global_tout_text, Id_filiere_general_tout_text);
            });
        });
});










$('#affiche_global_tout_1').on('click', function (){
    const anne_academique_global_tout_1 = $('#anne_academique_global_tout_1').val();
    var anne_academique_global_tout_text_1= $('#anne_academique_global_tout_1 option:selected').text();
    console.log(anne_academique_global_tout_1);
    const tableau = document.getElementById("table_rubrique_reelle");
    var tbody = document.createElement("tbody");

    // Réinitialiser le tableau sauf l'en-tête
    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    const url1 = 'D_Comptable_ERP/API_PHP/fonction_rubrique.php?action=tableau_general_tout_1&anne_academique_global_tout_1=' + anne_academique_global_tout_1;
    let i = 1;
    let total_rubrique_general_tout = 0;

    fetch(url1)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
                $('#bouton_imprimer').prop('disabled', true);
                document.getElementById('total_frais_reel').textContent = "0 $";
                return;
            }

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const tdnum = document.createElement("td");
                tdnum.textContent = i;

                const tdlibelle = document.createElement("td");
                const tdPeriodicite = document.createElement("td");
                const tdAnnee = document.createElement("td");
                const tdbouton = document.createElement("td");

                tdlibelle.textContent = infos.Libelle_rubrique;
                tdPeriodicite.textContent = infos.Montant_total_paye;
                tdAnnee.textContent = infos.Montant_rubrique_global;
                total_rubrique_general_tout += infos.Montant_rubrique_global;

                const bouton = document.createElement('button');
                bouton.className = 'btn btn-success';
                bouton.id = 'supprimer_rubrique';
                bouton.title = 'supprimer';

                bouton.addEventListener("click", function () {
                    supprimer(infos.Id_repartition, "supprimer_rubrique");
                });

                const icone = document.createElement('i');
                icone.className = 'fas fa-trash';
                bouton.appendChild(icone);
                tdbouton.appendChild(bouton);

                tr.appendChild(tdnum);
                tr.appendChild(tdlibelle);
                tr.appendChild(tdPeriodicite);
                tr.appendChild(tdAnnee);
                //tr.appendChild(tdbouton);
                tbody.appendChild(tr);

                i++;
            });

            tableau.appendChild(tbody);
            document.getElementById('total_frais_reel').textContent = total_rubrique_general_tout.toFixed(2) + " $";
            
            bootstrap.Modal.getInstance(document.getElementById('formulaire_choix_toutes_rubrique_1')).hide();

            // Activer le bouton d'impression
            $('#bouton_imprimer').prop('disabled', false);

            // ⚡ Important : Quand on clique sur #bouton_imprimer, on imprime
            $('#bouton_imprimer').off('click').on('click', function () {
                imprimerTableau("table_rubrique_reelle", total_rubrique_general_tout.toFixed(2), "Situation Générale", anne_academique_global_tout_text_1, "Toutes les filière");
            });
        });
});




    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function message_confirme(url) {
        Swal.fire({
            title: 'Confirmer l\'enregistrement',
            text: 'Êtes-vous sûr de vouloir enregistrer ces informations ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler',
            reverseButtons: true,
            willOpen: () => { Swal.showLoading(); },
            didOpen: () => { setTimeout(() => { Swal.hideLoading(); }, 1500); }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url);
                const inputs = document.querySelectorAll('#formEnregistrement input, #formEnregistrement textarea');
                inputs.forEach(input => { input.value = ''; });

                Swal.fire('Enregistré !', 'Les informations ont été enregistrées.', 'success');
            } else {
                Swal.fire('Annulé', 'L\'enregistrement a été annulé.', 'error');
            }
        });
    }

    // Rend les fonctions accessibles globalement si appelées depuis HTML
    window.enregistrer_ajout_rubrique = enregistrer_ajout_rubrique;
    window.enregistrer_nouvelle_rubrique = enregistrer_nouvelle_rubrique;
});




///////////////////////////////////////////////////////////////////////////////////////////////
////////////////////fonction globale pour afficher tout en général////////////////////////////
///////////////////////////////0978377473//////////////////////////////////////////////////////////////
