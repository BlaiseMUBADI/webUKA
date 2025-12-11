function afficher_resultat(){
	var recette= document.getElementById("montant1").value;
	var depense= document.getElementById("montant2").value;
	var resultat_resultat=(recette - depense);
	console.log('le resultat est :'+ (recette - depense));


	if (resultat_resultat<0) {
	document.getElementById('resultat_final').textContent=resultat_resultat.toLocaleString('fr-FR')+' $';
	document.getElementById('text_resultat').textContent="Il y a déficit, parce que le résultat est négatif";
    document.getElementById('text_resultat').classList.add('text-danger');
    

	}else if (resultat_resultat==0) {
	document.getElementById('resultat_final').textContent=resultat_resultat.toLocaleString('fr-FR')+' $';
	document.getElementById('text_resultat').textContent=" Le résultat est équilibré ou sans perte.";
    document.getElementById('text_resultat').classList.add('text-primary');
    
	}else if (resultat_resultat>0) {
	document.getElementById('resultat_final').textContent=resultat_resultat.toLocaleString('fr-FR')+' $';
    document.getElementById('text_resultat').textContent=" Il y a un excédent ou bénéfice.";
    document.getElementById('text_resultat').classList.add('text-success');
	}

	document.getElementById('bouton_resultat').style.display='none';
	}


	//////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////fonction pour les rubriques de frais à payer///////////////////
	///////////////////////////////////////////////////////////////////////////////////////
   
  $(document).ready(function () {
    // Initialisation de Select2 (si utilisé)
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

function affichage_promotion_select(valeur_Filiere, anneeAcademique){
    const url='API_PHP/fonction_rubrique.php?action=affiche_promotion&valeurFiliere='+valeur_Filiere;  
    
   
	 fetch(url)
    .then(response => response.json())
    .then(data => {
      var select = document.getElementById("promotion_rubrique");
      select.innerHTML="";
      data.forEach(promo => {

        var option = document.createElement("option");
        option.value = promo.Code_Promotion;
        option.textContent = promo.Promotion;
        select.appendChild(option);
      });
    })
   
}


  $('#promotion_rubrique').on('select2:select', function (e) {
  const valeur_promotion = e.params.data.id;
  const anneeAcademique = $('#annee_academique_rubrique').val();
  affichage_rubrique(valeur_promotion, anneeAcademique);
});



/// affichage resultat rubrique
 function affichage_rubrique(valeur_promotion, annee_academique) {
      console.log("Faculté : " + valeur_promotion + "\nAnnée : " + annee_academique);
      // Tu peux appeler ici ton Ajax ou autre traitement
    
  const tableau= document.getElementById("table_rubrique");
  var tbody = document.createElement("tbody");

  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
   
  }

    const url1='API_PHP/fonction_rubrique.php?action=tableau_rubrique&valeur_promotion='+valeur_promotion+'&annee_academique='+annee_academique;  
  	var i=1;

    fetch(url1)
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
              var tr = document.createElement("tr");

              var tdnum = document.createElement("td");
              tdnum.textContent = i;

              var tdlibelle= document.createElement("td");
              var tdPeriodicite = document.createElement("td");
              var tdAnnee = document.createElement("td");
              var tdService = document.createElement("td");
              var tdbouton = document.createElement("td");
              
              

              tdlibelle.textContent =infos.Libelle_Rubrique;
              tdPeriodicite.textContent=infos.Pourcentage;
              tdAnnee.textContent=infos.Montant;
             

              document.getElementById('total_frais').textContent=infos.TotalMontant+" $";
              var input=document.createElement('input');
              input.type='text';
              input.id='id_budget';
              input.style.display="none";
              input.value=infos.Ref_budget;

              var bouton=document.createElement('button');
              bouton.className = 'btn btn-success';
              bouton.id = 'supprimer_rubrique';
              bouton.title = 'supprimier';
              
              bouton.addEventListener("click", function() {
            // Actions à réaliser lors du clic
            supprimer(infos.Id_repartition, "supprimer_rubrique");
            affichage_rubrique(valeur_promotion, annee_academique);

              });

              var icone=document.createElement('i');
              icone.className='fas fa-trash';
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

    }
  });



function enregistrer_ajout_rubrique(){
    const anneeAcademique = $('#annee_academique_rubrique').val();
    const promotion = $('#promotion_rubrique').val();
    const rubrique = $('#rubrique').val();
    const montant_rubrique = $('#montant_rubrique').val();

   const url='API_PHP/fonction_rubrique.php?action=enregistrer_ajout_rubrique&anneeAcademique='+anneeAcademique+'&promotion='+promotion+'&rubrique='+rubrique+'&montant_rubrique='+montant_rubrique;  
   fetch(url);

   affichage_rubrique(promotion, anneeAcademique);

   var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal_ajout_rubrique'));
    modal.hide();
}




function enregistrer_nouvelle_rubrique(){
    const libelle_rubrique = $('#libelle_rubrique').val();
    const categorie_rubrique = $('#categorie_rubrique').val();
 
    const url='API_PHP/fonction_rubrique.php?action=enregistrer_nouvelle_rubrique&libelle_rubrique='+libelle_rubrique+'&categorie_rubrique='+categorie_rubrique;  
    fetch(url)
    .then(response => response.json())
    .then(data => {
      var select = document.getElementById("rubrique");
      select.innerHTML="";
      data.forEach(promo => {

        var option = document.createElement("option");
        option.value = promo.Id_rubrique;
        option.textContent = promo.Libelle;
        select.appendChild(option);
      });
    })

    var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal_nouvelle_rubrique'));
    modal.hide();
}





 function supprimer(id,action){
    const url1='API_PHP/supprimer.php?id='+id+'&action='+action; 
    fetch(url1)
  }




  $('#rubrique_reelle').on('change', function () {
    const rubrique_reelle = $(this).val(); // valeur sélectionnée

    const faculte_rubrique_reelle = $('#faculte_rubrique_reelle').val();
    const annee_academique_rubrique_reelle = $('#annee_academique_rubrique_reelle').val();


    
    // Appelle ta fonction ici
    affichage_promotion_select(valeurFiliere, anneeAcademique, valeurRubrique);
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////message confirmation ///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
function message_confirme(url){
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

}