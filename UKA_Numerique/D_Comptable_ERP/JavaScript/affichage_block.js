console.log("nous sommes dans le js AFFICHAGE BLOC ");
var blocs = document.querySelectorAll('.bloc');

// Ajouter un événement de clic à chaque bloc
blocs.forEach(function(bloc) {
  bloc.addEventListener('click', function() {
    // Désactiver tous les blocs
    blocs.forEach(function(b) {
      b.classList.remove('active');
    });

    // Activer le bloc cliqué
    bloc.classList.add('active');
  });
});

function accueil(){
	document.getElementById('block_tableau').style.display='block';
	document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block_depenses').style.display='none';
    document.getElementById('block_depenses2').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='none';
    document.getElementById('block_recette').style.display='none';
    document.getElementById('block_rubrique').style.display='none';

    document.getElementById('graphique_2').style.display='none';
    document.getElementById('graphique_1').style.display='none';
}

function comptabilite(){
    document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block_depenses').style.display='none';
    document.getElementById('block_depenses2').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='block';
    document.getElementById('block_recette').style.display='none';
    document.getElementById('block_rubrique').style.display='none';

    document.getElementById('graphique_2').style.display='none';
    document.getElementById('graphique_1').style.display='none';
}

function depenses(){
	console.log('je suis dans depense');
    document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='none';
    document.getElementById('block_depenses').style.display='block';
    document.getElementById('block_depenses2').style.display='block';
    document.getElementById('block_resultat').style.display='none';
    document.getElementById('block_recette').style.display='none';
    document.getElementById('block_rubrique').style.display='none';

    document.getElementById('graphique_2').style.display='none';
    document.getElementById('graphique_1').style.display='none';


}

function rubrique_prevue(){
  console.log('je suis dans rubrique');
    document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='none';
    document.getElementById('block_depenses').style.display='none';
    document.getElementById('block_depenses2').style.display='none';
    document.getElementById('block_resultat').style.display='none';
    document.getElementById('block_recette').style.display='none';
    document.getElementById('block_rubrique').style.display='block';
    document.getElementById('block_repartition').style.display='none';
    document.getElementById('graphique_2').style.display='none';
    document.getElementById('graphique_1').style.display='none';

}


function accueil(){
  console.log('je suis dans rubrique');
    document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='none';
    document.getElementById('block_depenses').style.display='none';
    document.getElementById('block_depenses2').style.display='none';
    document.getElementById('block_resultat').style.display='none';
    document.getElementById('block_recette').style.display='none';
    document.getElementById('block_rubrique').style.display='none';
    document.getElementById('block_repartition').style.display='none';
    document.getElementById('graphique_2').style.display='block';
    document.getElementById('graphique_1').style.display='block';


}
function repartition(){
  console.log('je suis dans repatition');
    document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='none';
    document.getElementById('block_depenses').style.display='none';
    document.getElementById('block_depenses2').style.display='none';
    document.getElementById('block_resultat').style.display='none';
    document.getElementById('block_recette').style.display='none';
    document.getElementById('block_repartition').style.display='block';
    document.getElementById('block_rubrique').style.display='none';

    document.getElementById('graphique_2').style.display='none';
    document.getElementById('graphique_1').style.display='none';

}


function affichage_compte(){
  console.log("je suis dans affichage compte");
  affichage_compte1();
    var block_budget = document.getElementById('block_budget'); block_budget.style.display='none';
    var block_compte = document.getElementById('block_compte'); block_compte.style.display='block';
    document.getElementById('block_resultat').style.display='none';
}

function affichage_resultat(){
  console.log("je suis dans affichage compte");
  affichage_compte1();
    var block_budget = document.getElementById('block_budget'); block_budget.style.display='none';
    var block_compte = document.getElementById('block_compte'); block_compte.style.display='none';
    document.getElementById('block_resultat').style.display='block';
    document.getElementById('block_depenses2').style.display='block';

}
function affichage_budget1(){
	affichage_budget();
  console.log("je suis dans affichage budget");
    var block_budget = document.getElementById('block_budget'); block_budget.style.display='block';
    var block_compte = document.getElementById('block_compte'); block_compte.style.display='none';

    document.getElementById('block_resultat').style.display='none';
}
function nouveau_budget(){
    document.getElementById('exampleModalLabel').textContent="Enregistrement du nouveau Budget";
}
function nouveau_compte(){
    document.getElementById('exampleModalLabel_compte').textContent="Enregistrement du nouveau Compte";
}
var id_budget;
function enregistrer_budget() {

 
      const libelle = document.getElementById("libelle").value;
      const description = document.getElementById("description").value;
      const Periodicite = document.getElementById("Periodicite").value;
      const Annee_debut = document.getElementById("Annee_debut").value;
      const Annee_fin = document.getElementById("Annee_fin").value;
      const service = document.getElementById("service").value;
      const service_libelle = document.getElementById("service").options[document.getElementById("service").selectedIndex].text;
      var extrait = service_libelle.substring(0, 4);
      const input = document.getElementById("id_budge").value;
      id_budget=input.value;

      if (libelle && description && Annee_fin && Annee_fin) {
        // Affiche un message avec les informations enregistrées (par exemple dans la console)
        console.log("Données enregistrées :");
        console.log("Zone 1 : " + libelle);
        console.log("Zone 2 : " + description);
        console.log("Zone 3 : " + Periodicite);
        console.log("extrait  : " + extrait);

        //enregistrement dans la base de budget

        const url='D_Comptable_ERP/API_PHP/enregistrement_budget.php?libelle='+libelle
        +'&description='+description+'&Periodicite='+Periodicite
        +'&Annee_debut='+Annee_debut+'&Annee_fin='+Annee_fin+'&service='+service
        +'&input='+input+'&Extrait='+extrait;  
  var i=1;
  console.log("je suis dans l enregistrement");
    
    ///¨¨¨¨£¨¨£££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££
    //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$===========	

     // Afficher un message de confirmation avec animation
    Swal.fire({
      title: 'Confirmer l\'enregistrement',
      text: 'Êtes-vous sûr de vouloir enregistrer ces informations ?',
      icon: 'question', // Icône à afficher
      showCancelButton: true, // Afficher un bouton "Annuler"
      confirmButtonText: 'Confirmer',
      cancelButtonText: 'Annuler',
      reverseButtons: true, // Inverser les boutons pour mettre "Annuler" à gauche
      willOpen: () => {
        // Animation d'apparition personnalisée
        Swal.showLoading();
      },
      didOpen: () => {
        // Animer avec un délai
        setTimeout(() => {
          Swal.hideLoading();
        }, 1500);
      }
    }).then((result) => {
      if (result.isConfirmed) {
      	//fetch(url);
      	//envoi dans la base de données 
      	if (fetch(url)) {
      		 affichage_budget();
      	}
      	

      	//effacer les donnees dans le formulaire
      const inputs = document.querySelectorAll('#formEnregistrement input, #formEnregistrement textarea');
    
    // Parcourir les éléments et vider leur valeur
    inputs.forEach(input => {
      input.value = '';  // Effacer la valeur de chaque champ
    });



        // Action après la confirmation
        Swal.fire(
          'Enregistré !',
          'Les informations ont été enregistrées.',
          'success'
        );
      } else {
        // Action après l'annulation
        Swal.fire(
          'Annulé',
          'L\'enregistrement a été annulé.',
          'error'
        );
      }
    });


    affichage_budget();
      } else {
        alert("Veuillez remplir toutes les zones avant d'enregistrer.");
      }

     
    var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
    modal.hide();

   
}



//pour la modification
    function affiche_edition(id,libelle, description, Periodicite, Annee_debut, Annee_fin,service, identifiant_source, typr) {
	console.log("je suis dans edition"+description);

		var h5 = document.getElementById("exampleModalLabel");
      	h5.textContent = "Midifier les informations de ce budget";
     
	  document.getElementById("libelle").value=libelle;
      document.getElementById("description").value=description;
      
      document.getElementById("Periodicite").value=Periodicite;
      document.getElementById("Annee_debut").value=Annee_debut;
      document.getElementById("Annee_fin").value=Annee_fin;
      document.getElementById("Annee_fin").value=Annee_fin;


       var select = document.getElementById('service');
       var nouvelleOption = document.createElement('option');

            // Définir la valeur et le texte de l'option
            nouvelleOption.value = identifiant_source;
            nouvelleOption.textContent = service;

            // Ajouter la nouvelle option à la fin de la liste
            select.appendChild(nouvelleOption);
            nouvelleOption.selected = true;

            document.getElementById('id_budge').value=id;
            console.log('la valeur de id'+id);
                 // const url2="API_PHP/modifier_budget.php?code="+id +"&libelle="+libelle +"&description="+description +"&Periodicite="+Periodicite +"&Annee_debut="+Annee_debut +"&Annee_fin="+Annee_fin +"&service="+service;  
     // fetch(url2)

	//afficher le modale de modification
	 var modalElement = document.getElementById('exampleModal');
    // Initialiser l'instance du modal
    var modal = new bootstrap.Modal(modalElement);
    // Afficher le modal
    modal.show();

}



// le block d'enregistrement de compte
/////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
function enregistrer_compte(){
	 const numero_compte = document.getElementById("numero_compte").value;
      const intitule_compte = document.getElementById("intitule_compte").value;
      const Pourcentage = document.getElementById("Pourcentage").value;
      const input_compte = document.getElementById("input_compte").value;
    

      if (numero_compte && intitule_compte && Pourcentage) {

        //enregistrement dans la base de budget

        const url='D_Comptable_ERP/API_PHP/enregistrement_compte.php?numero_compte='+numero_compte
        +'&intitule_compte='+intitule_compte+'&Pourcentage='+Pourcentage
        +'&input_compte='+input_compte;  
  var i=1;
  console.log("je suis dans l enregistrement compte");
    
    ///¨¨¨¨£¨¨£££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££££
    //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$===========	

     // Afficher un message de confirmation avec animation
    Swal.fire({
      title: 'Confirmer l\'enregistrement',
      text: 'Êtes-vous sûr de vouloir enregistrer ces informations ?',
      icon: 'question', // Icône à afficher
      showCancelButton: true, // Afficher un bouton "Annuler"
      confirmButtonText: 'Confirmer',
      cancelButtonText: 'Annuler',
      reverseButtons: true, // Inverser les boutons pour mettre "Annuler" à gauche
      willOpen: () => {
        // Animation d'apparition personnalisée
        Swal.showLoading();
      },
      didOpen: () => {
        // Animer avec un délai
        setTimeout(() => {
          Swal.hideLoading();
        }, 1500);
      }
    }).then((result) => {
      if (result.isConfirmed) {
      	//fetch(url);
      	//envoi dans la base de données 
      	if (fetch(url)) {
          //$('#montant').val('');
      		 affichage_compte1();
           $('#montant').val('');
      	}
      	
        
      	//effacer les donnees dans le formulaire
      //const inputs = document.querySelectorAll('#formulaire_depense input');
    
    // Parcourir les éléments et vider leur valeur
    //inputs.forEach(input => {
    //  input.value = '';  // Effacer la valeur de chaque champ
   // });



        // Action après la confirmation
        Swal.fire(
          'Enregistré !',
          'Les informations ont été enregistrées.',
          'success'
        );
      } else {
        // Action après l'annulation
        Swal.fire(
          'Annulé',
          'L\'enregistrement a été annulé.',
          'error'
        );
      }
    });



      } else {
        alert("Veuillez remplir toutes les zones avant d'enregistrer.");
      }

     
    var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal1'));
    modal.hide();

}


 function affiche_edition_compte(numero_compte,intitule_compte, Pourcentage) {
	console.log("je suis dans edition"+numero_compte);

		var h5 = document.getElementById("exampleModalLabel_compte");
      	h5.textContent = "Midifier les informations de ce compte";
     
	  document.getElementById("numero_compte").value=numero_compte;
      document.getElementById("intitule_compte").value=intitule_compte;
      document.getElementById("Pourcentage").value=Pourcentage;
      document.getElementById("input_compte").value="modification";


      
            console.log('la valeur de id'+numero_compte);
                 // const url2="API_PHP/modifier_budget.php?code="+id +"&libelle="+libelle +"&description="+description +"&Periodicite="+Periodicite +"&Annee_debut="+Annee_debut +"&Annee_fin="+Annee_fin +"&service="+service;  
     // fetch(url2)

	//afficher le modale de modification
	 var modalElement = document.getElementById('exampleModal1');
    // Initialiser l'instance du modal
    var modal = new bootstrap.Modal(modalElement);
    // Afficher le modal
    modal.show();

}



//////////////////////////////////////////////////////////////////////////////
/////////////////::depenses prevues//////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/*function choix_budget(){
	console.log('je suis dans choix du budget');
	var modal = bootstrap.Modal.getInstance(document.getElementById('tableau_budget'));
    modal.hide();
    var id = document.getElementById('id_budget_select').value;
    console.log('la valeur de budget choisi est '+id);

     const rows = document.querySelectorAll('table tbody tr');

    // Ajouter un écouteur d'événements de clic pour chaque ligne
    rows.forEach(row => {
        row.addEventListener('click', function() {
            // Récupérer les données de la ligne cliquée
            const columns = this.querySelectorAll('td');
            const rowData = {
                id: columns[0].innerText,
                nom: columns[1].innerText,
                email: columns[2].innerText,
                date_creation: columns[3].innerText
            };
            
            // Afficher les données dans la console
            console.log(rowData);
        });
    });

}*/

var rows_tableau = document.querySelectorAll('#table_1 tbody tr');

    // Ajouter un écouteur d'événements de clic pour chaque ligne
    rows_tableau.forEach(row => {
        row.addEventListener('click', function() {
            // Récupérer les données de la ligne cliquée
            const columns = this.querySelectorAll('td');
            const rowData = {
                id: columns[0].innerText,
                nom: columns[1].innerText,
                email: columns[2].innerText,
                date_creation: columns[3].innerText
            };
            
            // Afficher les données dans la console
            
            var id_bud=rowData.id;
            var nom_bud=rowData.nom;
            console.log(id_bud);
            var modal = bootstrap.Modal.getInstance(document.getElementById('tableau_budget'));
   			modal.hide();

   			document.getElementById('table_depenses').style.display='block';
   			 //const url='API_PHP/affichage_depense_prevues.php?ref_budget='+id_bud; 

   			 affichage_tableau_depenses_prevues(id_bud,nom_bud);
   			 //affichage_tableau_recette_prevues(id_bud);



        });
    });


/// selection tableau recette prévues

var rows_tableau_recette = document.querySelectorAll('#table_budget_recette tbody');
  rows_tableau_recette.forEach(row => {
        row.addEventListener('click', function() {
            // Récupérer les données de la ligne cliquée
            alert('nous sommes là');
            const columns = this.querySelectorAll('td');
            const rowData = {
                id1: columns[0].innerText,
                nom1: columns[1].innerText,
                email1: columns[2].innerText
               
            };
            
            // Afficher les données dans la console
            
            var id_bud=rowData.id1;
            var nom_bud=rowData.nom1;
            console.log(id_bud);
            var modal = bootstrap.Modal.getInstance(document.getElementById('tableau_budget_recette'));
        modal.hide();

        //document.getElementById('table_depenses').style.display='block';
         //const url='API_PHP/affichage_depense_prevues.php?ref_budget='+id_bud; 

         affichage_tableau_recette_prevues(id_bud,nom_bud);
         //affichage_tableau_recette_prevues(id_bud);



        });
    });




  var rows_tableau = document.querySelectorAll('#table_resultat tbody tr');

    // Ajouter un écouteur d'événements de clic pour chaque ligne
    rows_tableau.forEach(row => {
        row.addEventListener('click', function() {
            // Récupérer les données de la ligne cliquée
            const columns = this.querySelectorAll('td');
            const rowData = {
                id: columns[0].innerText,
                nom: columns[1].innerText
            };
            
            // Afficher les données dans la console
            
            var id_bud=rowData.id;
            var nom_bud=rowData.nom;
            console.log(id_bud);
            var modal = bootstrap.Modal.getInstance(document.getElementById('tableau_budget_resultat'));
        modal.hide();

        document.getElementById('table_depenses').style.display='block';
         //const url='API_PHP/affichage_depense_prevues.php?ref_budget='+id_bud; 

         affichage_tableau_depenses_prevues_resultat(id_bud,nom_bud);
         affichage_tableau_recette_prevues_resultat(id_bud);



        });
    });

//////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////depense///////////////////////////////////////////////////////
function ajouter_depense(){
	document.getElementById('formulaire_depense').style.display='block';
}

// Fonction pour ajouter une dépense prévue
function ajout_depenses_prevues() {
    const budget = $('#id_budget_select').val();
    const budget_text = $('#id_budget_select option:selected').text(); // Correction ici
    const id_compte = $('#id_compte').val();
    const montant = $('#montant').val();

    const url = 'D_Comptable_ERP/API_PHP/enregistrement_depenses.php?budget=' + budget
              + '&id_compte=' + id_compte + '&montant=' + montant;

    // Envoi des données au serveur
    fetch(url)
        .then(response => response.text())
        .then(data => {
            console.log('Succès :', data);
            // Optionnel : rafraîchir le tableau ou afficher un message de confirmation
            affichage_tableau_depenses_prevues(budget, budget_text);
        })
        .catch(error => {
            console.error('Erreur :', error);
        });

}

// Exécuter la fonction quand on clique sur le bouton avec l'ID 'valider_depense'
$(document).ready(function () {
    $('#valider_depense').on('click', function () {
        ajout_depenses_prevues();

    });
});




/////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
//////////type recette/////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

function type_recette(){
	console.log('je suis dans depense');
    document.getElementById('block_budget').style.display='none';
    document.getElementById('block_compte').style.display='none';
    document.getElementById('block1_menu_comptabilte').style.display='none';
    document.getElementById('block_depenses').style.display='none';
    document.getElementById('block_depenses2').style.display='none';
    document.getElementById('block_recette').style.display='block';
    document.getElementById('block_resultat').style.display='none';

}


$('#Id_an_acad_budget1').on('change', function () {
    const annee = $(this).val();
    const tableau = document.getElementById("table_budget_recette");

    // Supprimer toutes les lignes sauf l'en-tête
    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    console.log("Lignes restantes après suppression : " + tableau.rows.length);

    const url1 = 'D_Comptable_ERP/API_PHP/filtrer_budgets.php?annee=' + encodeURIComponent(annee);

    fetch(url1)
        .then(response => response.json())
        .then(data => {
            const tbody = document.createElement("tbody");

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const tdref_budget = document.createElement("td");
                const td_libelle = document.createElement("td");
                const td_periode = document.createElement("td");

                tdref_budget.textContent = infos.Ref_budget;
                td_libelle.textContent = infos.libelle1;
                td_periode.textContent = infos.Periodicite;

                tr.appendChild(tdref_budget);
                tr.appendChild(td_libelle);
                tr.appendChild(td_periode);

                // Ajout d'un événement click sur la ligne
                tr.addEventListener("click", function () {
                    //alert("Ligne sélectionnée : " + infos.Ref_budget + " - " + infos.libelle1);
                    // Tu peux ici appeler une autre fonction pour utiliser les données
                    affichage_tableau_recette_prevues(infos.Ref_budget,infos.libelle1);
                    var modal = bootstrap.Modal.getInstance(document.getElementById('tableau_budget_recette'));
                    modal.hide();
                });

                tbody.appendChild(tr);
            });

            tableau.appendChild(tbody);
        })
        .catch(error => {
            console.error("Erreur lors du chargement des données :", error);
            alert("Erreur lors de la récupération des budgets.");
        });
});



$('#Id_an_acad_budget2').on('change', function () {
    const annee = $(this).val();
    const tableau1 = document.getElementById("table_depenses_1");

    // Supprimer toutes les lignes sauf l'en-tête
    while (tableau1.rows.length > 1) {
        tableau1.deleteRow(1);
    }

    console.log("Lignes restantes après suppression : " + tableau1.rows.length);

    const url1 = 'D_Comptable_ERP/API_PHP/filtrer_budgets.php?annee=' + encodeURIComponent(annee);

    fetch(url1)
        .then(response => response.json())
        .then(data => {
            const tbody = document.createElement("tbody");

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const tdref_budget = document.createElement("td");
                const td_libelle = document.createElement("td");
                const td_periode = document.createElement("td");

                tdref_budget.textContent = infos.Ref_budget;
                td_libelle.textContent = infos.libelle1;
                td_periode.textContent = infos.Periodicite;

                tr.appendChild(tdref_budget);
                tr.appendChild(td_libelle);
                tr.appendChild(td_periode);

                // Ajout d'un événement click sur la ligne
                tr.addEventListener("click", function () {
                    //alert("Ligne sélectionnée : " + infos.Ref_budget + " - " + infos.libelle1);
                    // Tu peux ici appeler une autre fonction pour utiliser les données
                    affichage_tableau_depenses_prevues(infos.Ref_budget, infos.libelle1);
                    var modal = bootstrap.Modal.getInstance(document.getElementById('tableau_budget_depenses'));
                    modal.hide();
                });

                tbody.appendChild(tr);
            });

            tableau1.appendChild(tbody);
        })
        .catch(error => {
            console.error("Erreur lors du chargement des données :", error);
            alert("Erreur lors de la récupération des budgets.");
        });
});



function Budget_general(){


   const annee = $('#Id_an_acad_budget1').val();
    const tableau = document.getElementById("table_recette_prevues");
document.getElementById('budget_rectte').textContent = " 📌 Budget général  " + $('#Id_an_acad_budget1 option:selected').text();

    // Supprimer toutes les lignes sauf l'en-tête
    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    console.log("Lignes restantes après suppression : " + tableau.rows.length);

    const url1 = 'D_Comptable_ERP/API_PHP/filtrer_budgets.php?action=bedget_general&annee=' + encodeURIComponent(annee);
var i=1;
var montant_recette=0;
    fetch(url1)
        .then(response => response.json())
        .then(data => {
            const tbody = document.createElement("tbody");

            data.forEach(infos => {
                const tr = document.createElement("tr");

                const td_num = document.createElement("td");
                const td_filiere = document.createElement("td");
                const td_libelle = document.createElement("td");
                const td_montant = document.createElement("td");

                td_num.textContent = i++;
                td_filiere.textContent = infos.Libelle_Filiere;
                td_libelle.textContent = infos.Libelle_Frais;
                td_montant.textContent = infos.Montant_Total;
                montant_recette+=infos.Montant_Total;
              document.getElementById('total_recette').textContent= parseFloat(montant_recette).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
})+' $';




                tr.appendChild(td_num);
                tr.appendChild(td_filiere);
                tr.appendChild(td_libelle);
                tr.appendChild(td_montant);

               

                tbody.appendChild(tr);
            });

            tableau.appendChild(tbody);
        })
        .catch(error => {
            console.error("Erreur lors du chargement des données :", error);
            alert("Erreur lors de la récupération des budgets.");
        });
}