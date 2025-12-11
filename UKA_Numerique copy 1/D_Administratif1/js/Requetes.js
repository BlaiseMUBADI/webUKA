console.log("nous sommes dans requetes pour les agents");

//GESTION DE GRADE SELON LA CATEGORIE
window.onload = function() {
    var cat = document.getElementById("Categorie");
    var grade = document.getElementById("Grade");
  
    if (cat && grade) {
        cat.addEventListener('change', (event) => {
            var idCat = cat.value;
            
            console.log("la valeur de la catégorie est :::: " + idCat);
            grade.innerHTML = '<option value="" selected>-</option>';
            
            if (idCat === "1") {
                var option1 = new Option("Professeur Emerite", "PE"); 
                var option2 = new Option("Professeur Ordinaire", "PO"); 
                var option3 = new Option("Professeur", "P"); 
                var option4 = new Option("Professeur Associé", "PA"); 
                grade.add(option1); 
                grade.add(option2);
                grade.add(option3);
                grade.add(option4);
            } else if (idCat === "2") {
                var option1 = new Option("Chef de Travaux", "CT"); 
                var option2 = new Option("Assistant 2", "ASS2"); 
                var option3 = new Option("Assistant 1", "ASS1"); // Correction ici
                var option4 = new Option("Assistant de recherche 2", "ASSR2"); // Correction ici
                var option5 = new Option("Assistant de recherche 1", "ASSR1"); // Correction ici
                var option6 = new Option("Chargé de Pratique", "CPP"); 
                grade.add(option1); 
                grade.add(option2);
                grade.add(option3);
                grade.add(option4);
                grade.add(option5);
                grade.add(option6);
            } else if (idCat === "3") {
                var option1 = new Option("Directeur", "DIR"); 
                var option2 = new Option("Chef de Bureau", "CB"); 
                var option3 = new Option("Attache de Première Classe", "ATB1"); 
                var option4 = new Option("Attache de Deuxième Classe", "ATB2"); 
                grade.add(option1); 
                grade.add(option2);
                grade.add(option3);
                grade.add(option4);
            }
            else if (idCat === "4") {
                var option1 = new Option("AGB1", "AGB1"); 
                var option2 = new Option("AGB2", "AGB2"); 
              
                grade.add(option1); 
                grade.add(option2);
            
            }
        });
    } else {
        console.error("Élément(s) non trouvé(s)!");
    }
  };
  


// ENREGISTREMENT DES AGENTS
document.getElementById('enregistrer').addEventListener('click', function() {
    EnregistrementAgent();
});

document.getElementById('SaveParent').addEventListener('click', function() {
    
    SaveParent();
    SelectParent();
});

function EnregistrementAgent() {
    const txtMatAgent = document.getElementById("matricule").value;
    const txtNomAgent = document.getElementById("Nom").value;
    const txtPostnomAgent = document.getElementById("Postnom").value;
    const txtPrenomAgent = document.getElementById("Prenom").value;
    const CmbSexe = document.getElementById("sexe").value;
    const txtLieuNaissanceAgent = document.getElementById("LieuNaissance").value;
    const txtDateNaissanceAgent = document.getElementById("DateNaissance").value;
    const txtGrade = document.getElementById("Grade").value;
    const CmbEtatCivil = document.getElementById("EtatCivil").value;
    const txtNomConjoint = document.getElementById("NomConjoint").value;
    const txtDateNaisConjoint = document.getElementById("DateNaissanceCong").value;
    const txtLieuNaisConjoint = document.getElementById("LieuNaissanceCong").value;
    const nombreEnfant = document.getElementById('NombreEnfant').value;
    const cmbCategorie = document.getElementById('Categorie').value;
    const txtDateAffectation = document.getElementById('dateAffectation').value;
    const txtFonction = document.getElementById('Fonction').value;
    const txtIdService=document.getElementById('Idservice').value;
    const txtAdresse=document.getElementById('AdressePhysique').value;
    const txtMail=document.getElementById('AdresseMail').value;
    const txtTel=document.getElementById('NumTel').value;

    const txtDateEngagement=document.getElementById('DateEngagement').value;
    const txtNiveauEtude=document.getElementById('NiveauEtude').value;
    const txtAnneeObt=document.getElementById('AnneeObtDiplome').value;
    const txtInstitution=document.getElementById('Institution').value;
    const txtDomaine=document.getElementById('Domaine').value;


    console.log("la categorie est " + cmbCategorie);

    // Construire les paramètres URL pour l'agent
    var urlParams = new URLSearchParams({
        matricule: txtMatAgent,
        nom: txtNomAgent,
        postnom: txtPostnomAgent,
        prenom: txtPrenomAgent,
        sexe: CmbSexe,
        LieuNaissanceAgent: txtLieuNaissanceAgent,
        Grade: txtGrade,
        EtatCivil: CmbEtatCivil,
        DateNaissanceAgent: txtDateNaissanceAgent,
        NomConjoint: txtNomConjoint,
        DateNaisConjoint: txtDateNaisConjoint,
        LieuNaisCong: txtLieuNaisConjoint,
        NombreEnfant: nombreEnfant,
        IdCategorie: cmbCategorie,
        Fonction:txtFonction,dateaffectation:txtDateAffectation,IdService:txtIdService,
        AdressePhysique:txtAdresse,Mail:txtMail,Telephone:txtTel,
        DateEngagement:txtDateEngagement,NiveauEtude:txtNiveauEtude,AnneeObt:txtAnneeObt,
        Insitution:txtInstitution,Domaine:txtDomaine
    });

    // Récupérer les données des enfants et les ajouter aux paramètres URL
    for (let i = 1; i <= nombreEnfant; i++) {
        const nomEnfant = document.getElementById('NomEnfant' + i).value;
        const lieuNaissanceEnfant = document.getElementById('LieuNaisEnfant' + i).value;
        const dateNaissanceEnfant = document.getElementById('DateNaissanceEnfant' + i).value;
        urlParams.append('NomEnfant' + i, nomEnfant);
        urlParams.append('LieuNaisEnfant' + i, lieuNaissanceEnfant);
        urlParams.append('DateNaissanceEnfant' + i, dateNaissanceEnfant);
    }

    // Contacter l'API pour l'enregistrement de l'agent
    fetch('D_Administratif/API/API_Gestion_Agent.php?' + urlParams.toString(), {
        method: 'POST',
        body: urlParams
    })
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
             
                document.getElementById("Nom").value = "";
                document.getElementById("Postnom").value = "";
                document.getElementById("Prenom").value = "";
                document.getElementById("sexe").value = "";
                document.getElementById("LieuNaissance").value = "";
                document.getElementById("NomConjoint").value = "";
                document.getElementById("LieuNaissanceCong").value = "";
                document.getElementById("childrenNamesFields").innerHTML = "";
                document.getElementById("NombreEnfant").value = "";
                document.getElementById("AdressePhysique").value = "";
                document.getElementById("AdresseMail").value = "";
                document.getElementById("NumTel").value = "";
                document.getElementById("NiveauEtude").value = "";
                document.getElementById("AnneeObtDiplome").value = "";
                document.getElementById("Institution").value = "";
                document.getElementById("Domaine").value = "";
                document.getElementById("Fonction").value = "";
                // Afficher le nouveau formulaire pour le père
                
               //SelectParent();
               // Déplacer le formulaire au-dessus du bloc
               //afficherFormulaire();
            }
        });
    })
    .catch(error => {
        alert("Erreur lors de l'enregistrement : " + error);
    });
}
/* function afficherFormulaire() {
    document.getElementById('nouveauForm').style.display = 'block';
    document.getElementById('fondTransparent').style.display = 'block';
}
// ENREGISTREMENT DES PARENTS
function SaveParent() 
{
  const txtMatricule = document.getElementById("matricule").value;

  const txtnom = document.getElementById("noms").value;
  const txtStatut = document.getElementById("statut").value;
  const txtannedec = document.getElementById("anneeDeces").value;
  const nbrparent = document.getElementById("Nombre_parent").innerText;
  //console.log("le nbre parents"+nbrparent);

  var url = 'D_Administratif/API/API_Ajouter_Parents_Agents.php?mat=' + txtMatricule+'&noms='+txtnom+'&statut='+txtStatut+
              '&anneedeces='+txtannedec+'&NbrParent='+nbrparent;

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
               
                
              
            }
        });
    })
    .catch(error => {
        alert("Erreur lors de l'enregistrement : " + error);
    });
}
function SelectNbreParent()
  {
    
    const txtMatricule = document.getElementById("matricule").value;

    var url = 'D_Administratif/API/API_Select_Nbre_Parent.php?mat=' + txtMatricule;

      fetch(url)
        .then(response => response.json())
        .then(data => {
            document.getElementById("Nombre_parent").innerText=data.nombre_de_parents;
      
      }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
  }
  function SelectParent()
  {
    
    let tableParent = document.getElementById("TableauParent");
    tableParent.classList.add("table", "table-bordered");
    while (tableParent.firstChild) {
        tableParent.removeChild(tableParent.firstChild);
    }

    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold");

    var tr = document.createElement("tr");
    tr.style = "background-color:midnightblue; color:white; text-align:center;";

    var headers = ["N°", "Nom complet", "Statut", "Année décès"];
    headers.forEach(header => {
        var td = document.createElement("td");
        td.textContent = header;
        tr.appendChild(td);
    });

    thead.appendChild(tr);
    tableParent.appendChild(thead);

    var tbody = document.createElement("tbody");
    
    const txtMatricule = document.getElementById("matricule").value;

    var url = 'D_Administratif/API/API_Select_Parents.php?mat=' + txtMatricule;

      fetch(url)
      
        .then(response => response.json())
        .then(donnees => {
          let i = 1; // Initialiser la numérotation

          donnees.forEach(tos => {
          var tr = document.createElement("tr");

          var tdnum = document.createElement("td");
          tdnum.textContent = i;

          var tdnom = document.createElement("td");
          var tdlieu = document.createElement("td");
          var tddate = document.createElement("td");
          
          tdnom.textContent = tos.Noms;
          tdlieu.textContent = tos.Statut;
          tddate.textContent = tos.annee_dec;

          tr.appendChild(tdnum);
          tr.appendChild(tdnom);
          tr.appendChild(tdlieu);
          tr.appendChild(tddate);

          tbody.appendChild(tr);
          i++;
    
        });    
      }).catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de contacte de l'API Afficher Liste"+error);});
            tableParent.appendChild(tbody);            
  }
  */