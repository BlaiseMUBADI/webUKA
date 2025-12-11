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
      	fetch(url);

      	
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