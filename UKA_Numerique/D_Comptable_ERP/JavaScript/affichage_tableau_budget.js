

function affichage_budget() {
  console.log("nous sommes dans affichage tableau");

  const tableau = document.getElementById("table_1");
  var tbody = document.createElement("tbody");

  // Nettoyer le tableau sauf l'en-tête
  while (tableau.rows.length > 1) {
    tableau.deleteRow(1);
  }

  console.log(tableau.rows.length);

  const url1 = 'D_Comptable_ERP/API_PHP/affichage_budget.php';
  var i = 1;

  fetch(url1)
    .then(response => response.json())
    .then(data => {
      data.forEach(infos => {
        // Création de la ligne
        var tr = document.createElement("tr");
        tr.style.cursor = "pointer"; // 👉 Curseur en main au survol
        var tdnum = document.createElement("td");
        tdnum.textContent = i;

        var tdlibelle = document.createElement("td");
        var tdPeriodicite = document.createElement("td");
        var tdAnnee = document.createElement("td");
        var tdService = document.createElement("td");
        var tdbouton = document.createElement("td");

        tdlibelle.textContent = infos.libelle_budget;
        tdPeriodicite.textContent = infos.Periodicite;
        tdAnnee.textContent = infos.Annee_debut + "-" + infos.Annee_fin;
        tdService.textContent = infos.Service_Concerne;
        var id = infos.Ref_budget;
        var nom= infos.libelle_budget +' : '+infos.Annee_debut+'-'+infos.Annee_fin;
        // Événement clic sur la ligne
        tr.addEventListener("click", function () {
          console.log("ID du budget cliqué :", id);
          // Tu peux aussi stocker cet ID dans un champ caché si nécessaire
          // document.getElementById("id_selectionne").value = id;
          fonctionnement_budget_general(id,nom);
        });

        // Bouton éditer
        var bouton = document.createElement('button');
        bouton.className = 'btn btn-success';
        bouton.id = 'editer_budget';
        bouton.title = 'éditer';

        bouton.addEventListener("click", function (event) {
          event.stopPropagation(); // Empêche le clic sur la ligne
          affiche_edition(
            infos.Ref_budget,
            infos.libelle_budget,
            infos.Description,
            infos.Periodicite,
            infos.Annee_debut,
            infos.Annee_fin,
            infos.Service_Concerne,
            infos.Identifiant_Source,
            infos.Type
          );
        });

        var icone = document.createElement('i');
        icone.className = 'fas fa-edit';
        bouton.appendChild(icone);
        tdbouton.appendChild(bouton);

        // Ajout des cellules à la ligne
        tr.appendChild(tdnum);
        tr.appendChild(tdlibelle);
        tr.appendChild(tdPeriodicite);
        tr.appendChild(tdAnnee);
        tr.appendChild(tdService);
        tr.appendChild(tdbouton);

        // Ajout de la ligne au corps du tableau
        tbody.appendChild(tr);
        i++;
      });

      // Ajout du tbody au tableau
      tableau.appendChild(tbody);
    });
}





////////////////////////////////////////////////////////////////////////////////////
//Fonctionnement budgte général
///////////////////////////////////////////////////////////////////////////////////

function  fonctionnement_budget_general(id, nom){
  document.getElementById('block_budget').style.display ='none';
  document.getElementById('block_budget_general').style.display ='block';
  document.getElementById('nom_budget').textContent=nom;

  affichage_recette_generale(id);
  affichage_depense_generale(id);
  affichage_budget_general(id,nom)
}





function affichage_depense_generale(id) {
  const tableau = document.getElementById("table_depenses_generale");
  const tbody = document.createElement("tbody");

  while (tableau.rows.length > 1) {
    tableau.deleteRow(1);
  }

  const url1 = 'D_Comptable_ERP/API_PHP/affichage_budget.php?action=affichage_depense_general&id=' + id;

  fetch(url1)
    .then(response => response.json())
    .then(data => {
      let montantTotal = 0;
      let pourcentageTotal = 0;

      data.forEach(infos => {
        const tr = document.createElement("tr");
        tr.style.cursor = "pointer";

        const tdnum_imputation = document.createElement("td");
        const tdintitule_rubrique = document.createElement("td");
        const td_Montant = document.createElement("td");
        const td_pourcentage = document.createElement("td");

        tdnum_imputation.textContent = infos.Num_imputation;
        tdintitule_rubrique.textContent = infos.Intitul_compte;
        td_Montant.textContent = infos.Montant ;
        td_pourcentage.textContent = infos.Pourcentage + " %";

        montantTotal += parseFloat(infos.Montant || 0);
        pourcentageTotal += parseFloat(infos.Pourcentage || 0);

        // Double-clic pour rendre la cellule "Montant" modifiable
        td_Montant.addEventListener("dblclick", function () {
          const currentValue = td_Montant.textContent.trim();
          const input = document.createElement("input");
          input.type = "number";
          input.value = currentValue || "";
          input.style.width = "100px";
          input.classList.add("form-control", "form-control-sm");

          td_Montant.textContent = "";
          td_Montant.appendChild(input);
          input.focus();

          input.addEventListener("blur", function () {
            const newValue = input.value.trim();
            td_Montant.textContent = newValue;

            // Enregistrement via AJAX ou autre logique
            enregistrer_depense_generale(id, newValue, infos.Num_imputation);
          });

          input.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
              input.blur();
            }
          });
        });

        tr.appendChild(tdnum_imputation);
        tr.appendChild(tdintitule_rubrique);
        tr.appendChild(td_Montant);
        tr.appendChild(td_pourcentage);
        tbody.appendChild(tr);
      });

      // ➕ Ligne TOTAL
      const trTotal = document.createElement("tr");
      trTotal.style.fontWeight = "bold";
      trTotal.style.backgroundColor = "#f0f0f0";

      const tdVide = document.createElement("td");
      const tdLibelleTotal = document.createElement("td");
      tdLibelleTotal.textContent = "Total depenses de Fonctionnement";

      const tdTotalMontant = document.createElement("td");
      tdTotalMontant.textContent = montantTotal.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + " $";


      const tdTotalPourcentage = document.createElement("td");
      tdTotalPourcentage.textContent = "100.00 %";

      trTotal.appendChild(tdVide);
      trTotal.appendChild(tdLibelleTotal);
      trTotal.appendChild(tdTotalMontant);
      trTotal.appendChild(tdTotalPourcentage);

      tbody.appendChild(trTotal);
      tableau.appendChild(tbody);
    });
}


function affichage_recette_generale(id) {
  const tableau = document.getElementById("table_recette_generale");
  const tbody = document.createElement("tbody");

  while (tableau.rows.length > 1) {
    tableau.deleteRow(1);
  }

  const url1 = 'D_Comptable_ERP/API_PHP/affichage_budget.php?action=affichage_recette_general&id=' + id;

  fetch(url1)
    .then(response => response.json())
    .then(data => {
      let montantTotal = 0;
      let pourcentageTotal = 0;

      data.forEach(infos => {
        const tr = document.createElement("tr");
        tr.style.cursor = "pointer";

        const tdnum_rubrique = document.createElement("td");
        const tdintitule_rubrique = document.createElement("td");
        const td_Montant = document.createElement("td");
        const td_pourcentage = document.createElement("td");

        tdnum_rubrique.textContent = infos.Id_rubrique;
        tdintitule_rubrique.textContent = infos.Libelle;
        td_Montant.textContent = infos.Montant;
        td_pourcentage.textContent = infos.Pourcentage + " %";

        montantTotal += parseFloat(infos.Montant || 0);
        pourcentageTotal += parseFloat(infos.Pourcentage || 0);

        // Double-clic pour rendre la cellule "Montant" modifiable
        td_Montant.addEventListener("dblclick", function () {
          const currentValue = td_Montant.textContent.trim();
          const input = document.createElement("input");
          input.type = "number";
          input.value = currentValue || "";
          input.style.width = "100px";
          input.classList.add("form-control", "form-control-sm");

          td_Montant.textContent = "";
          td_Montant.appendChild(input);
          input.focus();

          input.addEventListener("blur", function () {
            const newValue = input.value.trim();
            td_Montant.textContent = newValue;

            // Enregistrement via AJAX ou autre logique
            enregistrer_recette_generale(id, newValue, infos.Id_rubrique);
          });

          input.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
              input.blur();
            }
          });
        });

        tr.appendChild(tdnum_rubrique);
        tr.appendChild(tdintitule_rubrique);
        tr.appendChild(td_Montant);
        tr.appendChild(td_pourcentage);
        tbody.appendChild(tr);
      });

      // ➕ Ligne TOTAL
      const trTotal = document.createElement("tr");
      trTotal.style.fontWeight = "bold";
      trTotal.style.backgroundColor = "#f0f0f0";

      const tdVide = document.createElement("td");
      const tdLibelleTotal = document.createElement("td");
      tdLibelleTotal.textContent = "Total recettes de Fonctionnement ";

      const tdTotalMontant = document.createElement("td");
      //tdTotalMontant.textContent = montantTotal.toFixed(2);
      tdTotalMontant.textContent = montantTotal.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + " $";


      const tdTotalPourcentage = document.createElement("td");
      tdTotalPourcentage.textContent = "100.00 %";

      trTotal.appendChild(tdVide);
      trTotal.appendChild(tdLibelleTotal);
      trTotal.appendChild(tdTotalMontant);
      trTotal.appendChild(tdTotalPourcentage);

      tbody.appendChild(trTotal);
      tableau.appendChild(tbody);
    });
}



function enregistrer_depense_generale(Ref_budget, montant, num_imputation){

  const url1 = 'D_Comptable_ERP/API_PHP/affichage_budget.php?action=enregistrement_depense_fonctionnement&Ref_budget=' 
  + Ref_budget+'&montant='+montant+'&num_imputation='+num_imputation;
  fetch(url1);
  affichage_depense_generale(Ref_budget);

}


function enregistrer_recette_generale(Ref_budget, montant, num_rubrique){

  const url1 = 'D_Comptable_ERP/API_PHP/affichage_budget.php?action=enregistrement_recette_fonctionnement&Ref_budget=' 
  + Ref_budget+'&montant='+montant+'&num_rubrique='+num_rubrique;
  fetch(url1);
  affichage_recette_generale(Ref_budget);

}

////////////////////////////////////////////////////////////////////////////////////
//affichege tableau compte
///////////////////////////////////////////////////////////////////////////////////

function affichage_compte1(){
console.log("nous sommes dans affichage tableau ++++");

  const tableau= document.getElementById("table_compte");
  var tbody = document.createElement("tbody");

  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
   
  }
  console.log(tableau.rows.length);

const url1='D_Comptable_ERP/API_PHP/affichage_compte.php';  
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

              var tdnum_compte= document.createElement("td");
              var tdintitule_compte = document.createElement("td");
              var tdpourcentage = document.createElement("td");
              var tdbouton = document.createElement("td");
              
              

              tdnum_compte.textContent =infos.Num_imputation;
              tdintitule_compte.textContent=infos.Intitul_compte;
              tdpourcentage.textContent=infos.Pourcent_budget;
             


              console.log("compte blablabla"+infos.Num_imputation);
              var bouton=document.createElement('button');
              bouton.className = 'btn btn-success';
              bouton.id = 'editer_budget';
              bouton.title = 'éditer';
              
              bouton.addEventListener("click", function() {
            // Actions à réaliser lors du clic
              affiche_edition_compte(infos.Num_imputation, infos.Intitul_compte,infos.Pourcent_budget);
              });

              var icone=document.createElement('i');
              icone.className='fas fa-edit';
              bouton.appendChild(icone);
              tdbouton.appendChild(bouton);

              tr.appendChild(tdnum);
              tr.appendChild(tdnum_compte);
              tr.appendChild(tdintitule_compte);
              tr.appendChild(tdpourcentage);
              tr.appendChild(tdbouton);
              
              tbody.appendChild(tr);
             
               i++;
            
});
    });
      tableau.appendChild(tbody);
  }




////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
////tableau depense prevues
/////////////////////////////////////////////////////////////////////////////////
var montant = 0;

function affichage_tableau_depenses_prevues(id, nom) {
    console.log("nous sommes dans affichage tableau1");

    const tableau = document.getElementById("table_depenses_prevues");
    var tbody = document.createElement("tbody");

    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    console.log(tableau.rows.length);

    const url1 = 'D_Comptable_ERP/API_PHP/affichage_depense_prevues.php?id=' + id;
    var select_budget = document.getElementById('id_budget_select');
                const nouvelleOption = document.createElement("option");
                nouvelleOption.value = id;
                nouvelleOption.text = nom;
                nouvelleOption.selected = true;
                select_budget.appendChild(nouvelleOption);
                select_budget.disabled = true;

    var i = 1;

    document.getElementById('affiche_nom_budget').textContent = nom;
    fetch(url1)
        .then(response => response.json())
        .then(data => {
            data.forEach(infos => {
                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                var td_budget = document.createElement("td");
                var td_compte = document.createElement("td");
                var td_Montant = document.createElement("td");
                var tdbouton = document.createElement("td");

                tdnum.textContent = i++;
                td_budget.textContent = infos.Num_imputation;

                // === COMPTE (modifiable texte)
                td_compte.textContent = infos.Intitul_compte;
                td_compte.setAttribute("data-id", infos.Id_depense);
                td_compte.setAttribute("data-field", "Intitul_compte");
                td_compte.classList.add("editable");

                // === MONTANT (modifiable numérique)
                td_Montant.textContent = infos.Montant;
                td_Montant.setAttribute("data-id", infos.Id_depense);
                td_Montant.setAttribute("data-field", "Montant");
                td_Montant.classList.add("editable");

                montant += parseFloat(infos.Montant);
                document.getElementById('affichage_montant_depense').textContent = montant;

                

                // === Bouton supprimer
                var divBoutons = document.createElement('div');
                divBoutons.className = 'd-flex justify-content-center gap-2';

                var boutonSupprimer = document.createElement('button');
                boutonSupprimer.className = 'btn btn-danger btn-sm';
                boutonSupprimer.title = 'Supprimer';
                var iconeDelete = document.createElement('i');
                iconeDelete.className = 'fas fa-trash';
                boutonSupprimer.appendChild(iconeDelete);
                boutonSupprimer.addEventListener("click", function () {
                    affiche_supprimer_depenses_prevue(infos.Id_depense, id, nom);
                });

                divBoutons.appendChild(boutonSupprimer);
                tdbouton.appendChild(divBoutons);

                tr.appendChild(tdnum);
                tr.appendChild(td_budget);
                tr.appendChild(td_compte);
                tr.appendChild(td_Montant);
                tr.appendChild(tdbouton);

                tbody.appendChild(tr);
            });

            tableau.appendChild(tbody);
            montant = 0;

            // Active l’édition inline
            activerEditionInline(id, nom);
        });
}


///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
/////affichage depense prevu dans le resultat////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
  var resultat_1=0;
  var montant_depense_resultat=0
function affichage_tableau_depenses_prevues_resultat(id, nom){
console.log("nous sommes dans affichage tableau");
  resultat_1=0;
  const tableau= document.getElementById("table_depenses_resultat");
 // const tableau2= document.getElementById("table_depenses2");
  var tbody = document.createElement("tbody");

  while(tableau.rows.length>1){
    
    tableau.deleteRow(1);
   
  }
  console.log(tableau.rows.length);

const url1='D_Comptable_ERP/API_PHP/affichage_depense_prevues.php?id='+id;  
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

              var td_budget= document.createElement("td");
              var td_compte = document.createElement("td");
              var td_Montant = document.createElement("td");
              var tdbouton = document.createElement("td");
              

              
              
              tdnum.textContent = i++;
              td_budget.textContent = infos.Num_compte;
              td_compte.textContent =infos.Intitul_compte;
              td_Montant.textContent=infos.Montant;
              montant_depense_resultat+=infos.Montant;

              document.getElementById('affichage_montant_depense_resultat').textContent=montant_depense_resultat+' $';
              document.getElementById('montant2').value=montant_depense_resultat;
              
              document.getElementById('affiche_nom_budget').textContent=nom;


              var select_budget=document.getElementById('id_budget_select');
              const nouvelleOption = document.createElement("option");
              nouvelleOption.value = id;
              nouvelleOption.text = nom;
              nouvelleOption.selected = true;
              select_budget.appendChild(nouvelleOption);
              select_budget.disabled = true;
              //document.getElementById('affichage_montant_depense2').textContent=montant;


              //console.log("compte blablabla"+infos.Num_compte);
              var bouton=document.createElement('button');
              bouton.className = 'btn btn-success';
              bouton.id = 'editer_depense';
              bouton.title = 'éditer';

              bouton.addEventListener("click", function() {
            // Actions à réaliser lors du clic
              affiche_edition_depense_prevue(infos.Id_depense, infos.Num_compte,infos.Montant);
              });

              var icone=document.createElement('i');
              icone.className='fas fa-edit';
              bouton.appendChild(icone);
              tdbouton.appendChild(bouton);

              tr.appendChild(tdnum);
              tr.appendChild(td_budget);
              tr.appendChild(td_compte);
              tr.appendChild(td_Montant);
              
              tbody.appendChild(tr);
             
              
            
});
    });
      tableau.appendChild(tbody);
      //tableau2.appendChild(tbody);
      montant_depense_resultat=0;
  }

  function affiche_edition_depense_prevue(Id_depense, Num_compte,Montant){
    console.log('je suis dans editer depense prevues');
  }



  var montant_recette=0;
  var id_budget1;
  var nom_budget;
  function affichage_tableau_recette_prevues(id,nom){

      id_budget1=id;
      nom_budget  =nom;

    console.log("nous sommes dans affichage tableau recette prevues");
    document.getElementById('budget_rectte').textContent=nom;


  const tableau_recette= document.getElementById("table_recette_prevues");
  var tbody = document.createElement("tbody");

  while(tableau_recette.rows.length>1){
    
    tableau_recette.deleteRow(1);
   
  }
  console.log(tableau_recette.rows.length);

  const url1='D_Comptable_ERP/API_PHP/affichage_recette_prevues.php?id='+id;  
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

              var td_designation_promotion= document.createElement("td");
              var td_type_recette = document.createElement("td");
              var td_montant = document.createElement("td");
              var tdbouton = document.createElement("td");
              

              
              
              tdnum.textContent = i++;
              td_designation_promotion.textContent = infos.Abreviation_Promotion+' '+infos.Libelle_mention;
              td_type_recette.textContent =infos.Libelle_Frais;
              td_montant.textContent=infos.Montant_Total;
              montant_recette+=infos.Montant_Total;
              document.getElementById('total_recette').textContent= parseFloat(montant_recette).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
})+' $';

              //console.log("compte blablabla"+infos.Num_compte);
              var bouton=document.createElement('button');
              bouton.className = 'btn btn-primary';
              bouton.id = 'editer_depense';
              bouton.title = 'supprimer';

             
              bouton.addEventListener("click", function() {
            // Actions à réaliser lors du clic
              affiche_supprimer_recette_prevue(infos.Id_recette);
              });

              var icone=document.createElement('i');
              icone.className='fas fa-trash';
              icone.style='';
              bouton.appendChild(icone);
              tdbouton.appendChild(bouton);

              tr.appendChild(tdnum);
              tr.appendChild(td_designation_promotion);
              tr.appendChild(td_type_recette);
              tr.appendChild(td_montant);
              tr.appendChild(tdbouton);
              
              tbody.appendChild(tr);
             
              
            
});
    });
      tableau_recette.appendChild(tbody);
       promotion_reste(id);
      montant_recette=0;
  }

/////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////affichage tableau recette pour le resultat/////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

var montant_recette_resultat=0;
  function affichage_tableau_recette_prevues_resultat(id){

    document.getElementById('bouton_resultat').style.display='block';
    console.log("nous sommes dans affichage tableau recette prevues");


  const tableau_recette= document.getElementById("table_recette_resultat");
  var tbody = document.createElement("tbody");

  while(tableau_recette.rows.length>1){
    
    tableau_recette.deleteRow(1);
   
  }
  console.log(tableau_recette.rows.length);

  const url1='D_Comptable_ERP/API_PHP/affichage_recette_prevues.php?id='+id;  
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

              var td_designation_promotion= document.createElement("td");
              var td_type_recette = document.createElement("td");
              var td_montant = document.createElement("td");
              var tdbouton = document.createElement("td");
              

              
              
              tdnum.textContent = i++;
              td_designation_promotion.textContent = infos.Abreviation_Promotion+' '+infos.Libelle_mention;
              td_type_recette.textContent =infos.Libelle_Frais;
              td_montant.textContent=infos.Montant_Total;
              montant_recette_resultat+=infos.Montant_Total;
              document.getElementById('affichage_montant_recette_resultat').textContent=montant_recette_resultat+' $';
             
              document.getElementById('montant1').value=montant_recette_resultat;
              //console.log("compte blablabla"+infos.Num_compte);
              var bouton=document.createElement('button');
              bouton.className = 'btn btn-primary';
              bouton.id = 'editer_depense';
              bouton.title = 'supprimer';

              
             
              bouton.addEventListener("click", function() {
            // Actions à réaliser lors du clic
              affiche_supprimer_recette_prevue(infos.Id_recette);
              });

              var icone=document.createElement('i');
              icone.className='fas fa-trash';
              icone.style='';
              bouton.appendChild(icone);
              tdbouton.appendChild(bouton);

              tr.appendChild(tdnum);
              tr.appendChild(td_designation_promotion);
              tr.appendChild(td_type_recette);
              tr.appendChild(td_montant);
              
              tbody.appendChild(tr);
             
              
            
});
    });
      tableau_recette.appendChild(tbody);
       promotion_reste(id);
      montant_recette_resultat=0;
  }





  function promotion_reste(id){

  const url1='D_Comptable_ERP/API_PHP/promotion_reste.php?id='+id;  
  var i=1;

var select_promotion=document.getElementById('promotion_reste');
             while (select_promotion.options.length > 0) {
              select_promotion.remove(0);
              }

    fetch(url1)
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {

          // Création de TR
            
              const nouvelleOption = document.createElement("option");
              nouvelleOption.value = infos.Code_Promotion;
              nouvelleOption.text = infos.Libelle_promotion +' '+infos.Libelle_mention;
              select_promotion.appendChild(nouvelleOption);

              


});
    });
              document.getElementById('id_budget1').value=id;
      
  }


  function enregistrer_recette(){

    var id_budget=document.getElementById('id_budget1').value;
    var id_promotion=document.getElementById('promotion_reste').value;

    console.log('enregistrer recette'+id_budget);
    console.log('enregistrer recette'+id_promotion);

    const url1='D_Comptable_ERP/API_PHP/enregistrer_recette.php?id_budget='+id_budget+'&promotion='+id_promotion;  
  var i=1;

    fetch(url1)
       var modal = bootstrap.Modal.getInstance(document.getElementById('form_ajout_recette'));
        modal.hide();

        affichage_tableau_recette_prevues(id_budget1,nom_budget)

  }


  function affiche_supprimer_recette_prevue(Id_recette){
    const url1='D_Comptable_ERP/API_PHP/supprimer_recette.php?Id_recette='+Id_recette; 
    fetch(url1)
  }

  function affiche_supprimer_depenses_prevue(Id_depense, id, nom){
    const url1='D_Comptable_ERP/API_PHP/supprimer.php?action=depense&Id_depense='+Id_depense; 
    fetch(url1)
    affichage_tableau_depenses_prevues(id, nom);


  }



  // fonction modification en ligneaffichage_tableau_depenses_prevues(id, nom)
  function activerEditionInline(id1, nom) {
    document.querySelectorAll(".editable").forEach(cell => {
        cell.addEventListener("dblclick", function () {
            const ancienneValeur = this.textContent;
            const id = this.dataset.id;
            const champ = this.dataset.field;

            const input = document.createElement("input");
            input.type = "text";
            input.value = ancienneValeur;
            this.textContent = '';
            this.appendChild(input);
            input.focus();

            input.addEventListener("blur", () => {
                const nouvelleValeur = input.value;

                if (champ === "Montant" && isNaN(nouvelleValeur)) {
                    alert("Le montant doit être un nombre !");
                    cell.textContent = ancienneValeur;
                    return;
                }

                this.textContent = nouvelleValeur;

                if (nouvelleValeur !== ancienneValeur) {
                    fetch('D_Comptable_ERP/API_PHP/modifier_depense.php?id='+id+'&champ='+champ+'&valeur='+encodeURIComponent(nouvelleValeur));
                     affichage_tableau_depenses_prevues(id1, nom); 
                }
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Enter") input.blur();
            });
        });
    });
}





  $('#depenses_generales1').on('click', function () {
   
    console.log("Le lien 'dépenses générales' a été cliqué !");
    var Annee_debut = document.getElementById("Id_an_acad_budget2").value;
    var tableau = document.getElementById("table_depenses_prevues");
    var tbody = document.createElement("tbody");

    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    console.log(tableau.rows.length);

    var url1 = 'D_Comptable_ERP/API_PHP/affichage_depense_prevues.php?action=depense_generale&Annee_debut=' + Annee_debut;
            
    var i = 1;
    var montant_gen=0;
    
    fetch(url1)
        .then(response => response.json())
        .then(data => {
            data.forEach(infos => {
                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                var td_budget = document.createElement("td");
                var td_compte = document.createElement("td");
                var td_Montant = document.createElement("td");
                var tdbouton = document.createElement("td");

                tdnum.textContent = i++;
                td_budget.textContent = infos.Num_imputation;
                td_compte.textContent = infos.Intitul_compte;
                td_Montant.textContent = infos.Montant_Total;
               
                tdbouton.innerHTML = "✅"; 
                tdbouton.style.textAlign = "center"; // Centre le contenu dans la cellule
                montant_gen += parseFloat(infos.Montant_Total);
                document.getElementById('affichage_montant_depense').textContent = montant_gen;

                tr.appendChild(tdnum);
                tr.appendChild(td_budget);
                tr.appendChild(td_compte);
                tr.appendChild(td_Montant);
                tr.appendChild(tdbouton);

                tbody.appendChild(tr);
            });

            tableau.appendChild(tbody);
            montant_gen = 0;

            // Active l’édition inline
            
        });
    // affichage_tableau_depenses_prevues(1, "Dépenses générales");
});








function changerIcon(iconId, collapseId) {
    let iconElement = document.getElementById(iconId);
    let collapseElement = document.getElementById(collapseId);

    collapseElement.addEventListener('shown.bs.collapse', function () {
        iconElement.innerHTML = "➖"; // Change to minus when expanded
    });

    collapseElement.addEventListener('hidden.bs.collapse', function () {
        iconElement.innerHTML = "➕"; // Change back to plus when collapsed
    });
}








//////////////////////////////////////////////////////////////////////////
//////////////////affichage de tableau d'impression//////////////////////
////////////////////////////////////////////////////////////////////////
function affichage_budget_general(id, nom) {
    const tableau = document.getElementById("table_budget_general");
    document.getElementById('titre_tableau').textContent = nom;
    const tbody = document.createElement("tbody");

    while (tableau.rows.length > 1) {
        tableau.deleteRow(1);
    }

    const urlDepenses = 'D_Comptable_ERP/API_PHP/affichage_budget.php?action=affichage_depense_general&id=' + id;
    const urlRecettes = 'D_Comptable_ERP/API_PHP/affichage_budget.php?action=affichage_recette_general&id=' + id;

    Promise.all([
        fetch(urlDepenses).then(response => response.json()),
        fetch(urlRecettes).then(response => response.json())
    ])
    .then(([dataDepenses, dataRecettes]) => {
        let montantTotalDepenses = 0;
        let montantTotalRecettes = 0;

        let depenseRows = dataDepenses
            .filter(infos => parseFloat(infos.Montant) > 0)
            .map(infos => {
                montantTotalDepenses += parseFloat(infos.Montant || 0);
                return {
                    Num_imputation: infos.Num_imputation,
                    Intitul_compte: infos.Intitul_compte,
                    Montant_depense: infos.Montant,
                    Pourcentage_depense: infos.Pourcentage + " %"
                };
            });

        let recetteRows = dataRecettes
            .filter(infos => parseFloat(infos.Montant) > 0)
            .map(infos => {
                montantTotalRecettes += parseFloat(infos.Montant || 0);
                return {
                    Id_rubrique: infos.Id_rubrique,
                    Libelle: infos.Libelle,
                    Montant_recette: infos.Montant,
                    Pourcentage_recette: infos.Pourcentage + " %"
                };
            });

        let maxLength = Math.max(depenseRows.length, recetteRows.length);

        for (let i = 0; i < maxLength; i++) {
            const tr = document.createElement("tr");

            let depense = depenseRows[i] || { Num_imputation: "", Intitul_compte: "", Montant_depense: "", Pourcentage_depense: "" };
            let recette = recetteRows[i] || { Id_rubrique: "", Libelle: "", Montant_recette: "", Pourcentage_recette: "" };

            tr.innerHTML = `
                <td style="font-size:12px;">${depense.Num_imputation}</td>
                <td style="font-size:12px;">${depense.Intitul_compte}</td>
                <td style="font-size:12px;">${depense.Montant_depense}</td>
                <td style="font-size:12px;">${depense.Pourcentage_depense}</td>
                <td style="font-size:12px;">${recette.Id_rubrique}</td>
                <td style="font-size:12px;">${recette.Libelle}</td>
                <td style="font-size:12px;">${recette.Montant_recette}</td>
                <td style="font-size:12px;">${recette.Pourcentage_recette}</td>
            `;

            tbody.appendChild(tr);
        }

        // ➕ Ligne TOTAL
        const trTotal = document.createElement("tr");
        trTotal.style.fontWeight = "bold";
        trTotal.style.backgroundColor = "#e0e0e0";

        trTotal.innerHTML = `
            <td></td>
            <td><strong>Total Dépenses</strong></td>
            <td style="font-size:12px;">${montantTotalDepenses.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} $</td>
            <td style="font-size:12px;">100.00 %</td>
            <td></td>
            <td><strong>Total Recettes</strong></td>
            <td style="font-size:12px;">${montantTotalRecettes.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} $</td>
            <td style="font-size:12px;">100.00 %</td>
        `;

        tbody.appendChild(trTotal);

        // 🔹 Résultat d'Exploitation
        const trResultat = document.createElement("tr");
        trResultat.style.fontWeight = "bold";
        trResultat.style.backgroundColor = montantTotalRecettes >= montantTotalDepenses ? "#d4edda" : "#f8d7da";

        let difference = montantTotalRecettes - montantTotalDepenses;
        let statut = difference >= 0 ? "Excédent" : "Déficit";

        trResultat.innerHTML = `
            <td><strong>130000</strong></td>
            <td><strong>Résultat d'Exploitation</strong></td>
            <td colspan="2" style="font-size:12px;">${difference.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} $ (${statut})</td>
            <td colspan="4"></td>
        `;

        tbody.appendChild(trResultat);
        tableau.appendChild(tbody);
    });
}

// Impression du tableau général en mode paysage
document.getElementById("imprimer_budget_general").addEventListener("click", function () {

  document.getElementById('table_budget_general').style.display='block';

    const printContent = document.getElementById("table_budget_general").outerHTML;
    const printWindow = window.open("", "", "width=1200,height=800");

    printWindow.document.write(`
        <html>
        <head>
            <title>Impression Budget Général</title>
            <style>
                @media print {
                    @page {
                        size: A4 landscape; /* Mode paysage */
                        margin: 20mm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f0f0f0;
                    }
                }
            </style>
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.print();
  document.getElementById('table_budget_general').style.display='none';

});











///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////:affichage graphique//////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

function afficherGraphique(anneeAcademique) {
    fetch(`D_Comptable_ERP/API_PHP/affichage_graphique.php?annee_academique=${anneeAcademique}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById("graphique_1");
            container.innerHTML = ""; // Vider le contenu avant d'ajouter les nouvelles données

            data.forEach(row => {
                const libelle = row.Libelle;
                const montantRecette = row.Montant_recette_generale.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + " $";
                const montantPaye = row.Montant_paye_etudiant.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + " $";

                let pourcentageProgress = 0;
                if (row.Montant_recette_generale > 0) {
                    pourcentageProgress = Math.round((row.Montant_paye_etudiant / row.Montant_recette_generale) * 100);
                }

                const element = `
                    <div class="col-12">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-1">
                            <div class="w-100 me-3">
                                <p id="Rubrique" class="mb-1 fw-bold">${row.Id_rubrique} - ${libelle}</p>
                                <h6 id="Montant" class="mb-2">
                                    Recette prévue: ${montantRecette} <br>
                                    Montant payé: ${montantPaye}
                                </h6>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: ${pourcentageProgress}%;" 
                                         aria-valuenow="${pourcentageProgress}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        ${pourcentageProgress}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.innerHTML += element;
            });
        });
}

// Charger la dernière année académique par défaut
document.addEventListener("DOMContentLoaded", function() {
    const selectAnnee = document.getElementById("annee_academique_graphique");
    afficherGraphique(selectAnnee.value);
});

// Mettre à jour lors du changement d'année
document.getElementById("annee_academique_graphique").addEventListener("change", function() {
    afficherGraphique(this.value);
});
