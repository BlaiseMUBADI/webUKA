 <script>
    document.getElementById("saveButton").addEventListener("click", function() {
      const zone1 = document.getElementById("zone1").value;
      const zone2 = document.getElementById("zone2").value;
      const zone3 = document.getElementById("zone3").value;
      const zone4 = document.getElementById("zone4").value;

      if (zone1 && zone2 && zone3 && zone4) {
        // Affiche un message avec les informations enregistrées (par exemple dans la console)
        console.log("Données enregistrées :");
        console.log("Zone 1 : " + zone1);
        console.log("Zone 2 : " + zone2);
        console.log("Zone 3 : " + zone3);
        console.log("Zone 4 : " + zone4);

        // Ferme la modale après enregistrement
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.hide();
      } else {
        alert("Veuillez remplir toutes les zones avant d'enregistrer.");
      }
    });
  </script>



  <script>
  // Ajouter une action à la confirmation de l'enregistrement
  document.getElementById('confirmBtn').addEventListener('click', function() {
    alert('Enregistrement confirmé !');
    // Fermer le modal après la confirmation
    var modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
    modal.hide();
  });
</script>