  console.log(" je suis dans Manip_EC_Aligne")

  /*
  *++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  *+++++++++++++++++++ C'est un script qui se charge de la manipulation des comptes agents+++++++++
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  *
  */

  /*
  *********************************************************************************************
  * ***************************** Déclaration des composants HTML *****************************
  *********************************************************************************************
  */

  let tr_="";
  let mat_agent_="";
  let verfi_=true;

  // Les éléments du DOM sont initialisés seulement si la page contient
  // l'élément parent `div_gen_Aligne_Enseignant`. Cela évite que ce script lance des
  // getElementById() au top-level et retourne `null` quand il est inclus
  // sur d'autres pages.
  let cmb_semestre_alignre;
  let cmb_promotion_FAC;
  let cmb_annee_academique_aligne;

  document.addEventListener("DOMContentLoaded",function(event)
  {
    const container = document.getElementById("div_gen_Aligne_Enseignant");
    if(container !== null)
    {
      // initialiser les éléments en utilisant le conteneur pour éviter
      // toute sélection hors-contexte lorsque ce script est inclus sur
      // d'autres pages
      cmb_semestre_alignre = container.querySelector('#id_semestre') || document.getElementById('id_semestre');
      cmb_promotion_FAC = container.querySelector('#code_prom_Align_EC') || document.getElementById('code_prom_Align_EC');
      cmb_annee_academique_aligne = container.querySelector('#id_fac_annee') || document.getElementById('id_fac_annee');

      if(cmb_semestre_alignre !== null)
      {
        cmb_semestre_alignre.addEventListener('change',(event)=> 
          {
            Affichage_ECs_Par_Filiere();
            
          });

          if (cmb_annee_academique_aligne !== null) {
            cmb_annee_academique_aligne.addEventListener('change',(event)=> 
              {
                Affichage_ECs_Par_Filiere();
                
              });
          }

          if (cmb_promotion_FAC !== null) {
            cmb_promotion_FAC.addEventListener('change',(event)=> 
              {
                Affichage_ECs_Par_Filiere();
                
              });
          }
      }
      Affichage_Enseignant_Aligner();
      Affichage_ECs_Par_Filiere();

    } 
  })

  /*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES UEs D'UNE FILIERE ********************
  *****************************************************************************************
  */

  function Affichage_Enseignant_Aligner() {
    let table_aligne_enseignant = document.getElementById("table_aligne_enseignant");

    // Supprimer les enfants existants de la table
    while (table_aligne_enseignant.firstChild) {
        table_aligne_enseignant.removeChild(table_aligne_enseignant.firstChild);
    }

    // Créer l'en-tête de la table
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold", "text-center");

    var tr1 = document.createElement("tr");
    tr1.style = "background-color:midnightblue; color:white;";

    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");

    td1.textContent = "N°";
    td2.textContent = "Enseignant";
    td3.textContent = "Domaine";
    td4.textContent = "Titre";
    td5.textContent = "Filière";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);

    thead.appendChild(tr1);
    table_aligne_enseignant.appendChild(thead);

    var tbody = document.createElement("tbody");

    var url = 'API_PHP/Liste_Enseignants.php';

    var i = 1;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            data.forEach(infos => {
                // Création de TR
                var tr = document.createElement("tr");

                var tdnum = document.createElement("td");
                tdnum.textContent = i;
                tdnum.classList.add("text-center");

                var td_enseignant = document.createElement("td");
                td_enseignant.classList.add("text-center", "w-auto");
                var td_domaine = document.createElement("td");
                var td_titre_academique = document.createElement("td");
                var td_filire = document.createElement("td");

                td_enseignant.textContent = infos.enseignant;
                td_titre_academique.textContent = infos.titre_academique;
                td_domaine.textContent = infos.domaine;
                td_filire.textContent = infos.filiere;

                tr.appendChild(tdnum);
                tr.appendChild(td_enseignant);
                tr.appendChild(td_titre_academique);
                tr.appendChild(td_domaine);
                tr.appendChild(td_filire);

               /* tr.addEventListener('mouseenter', function() {
                  tr.style.cursor = 'pointer'; // Change le curseur au survol
                  tr.style.backgroundColor = 'rgba(9, 241, 160, 0.5)'; // Optionnel: changer la couleur de fond
                });

                tr.addEventListener('mouseleave', function() {
                    tr.style.cursor = ''; // Réinitialise le curseur à sa valeur par défaut
                    tr.style.backgroundColor = ''; // Réinitialise la couleur de fond
                    
                });*/

                tbody.appendChild(tr);

                // Ajouter l'événement de clic pour afficher les infos de la ligne
                tr.addEventListener("click", function () {
                  mat_agent_=infos.mat_agent;
                    Selectionner_Enseignant(infos.mat_agent, tr);
                    Affichage_ECs_Par_Filiere() ;
                });
                i++;
            });
        })
        .catch(error => {
            // Traitez l'erreur ici
            console.log("Erreur lors de la récupération des enseignants: " + error);
        });

    table_aligne_enseignant.appendChild(tbody);
    table_aligne_enseignant.classList.add("table-striped");
}


/*
*************  AFFICHAGE DE TOUS CES ECS EN TENANT AUSSI COMPTE DES ATTRIBUTIONS **********
*/
function Affichage_ECs_Par_Filiere() 
{
  let table_ecs = document.getElementById("table_aligne_EC");

  // Supprimer les enfants existants de la table
  while (table_ecs.firstChild) {
      table_ecs.removeChild(table_ecs.firstChild);
  }

  // Créer l'en-tête de la table
  var thead = document.createElement("thead");
  thead.classList.add("sticky-sm-top", "m-0", "fw-bold", "text-center");

  var tr1 = document.createElement("tr");
  tr1.style = "background-color:midnightblue; color:white;";

  var td1 = document.createElement("td");
  var td2 = document.createElement("td");
  var td3 = document.createElement("td");
  var td4 = document.createElement("td");

  td1.textContent = "N°";
  td2.textContent = "Action";
  td3.textContent = "Intitulé EC";
  td4.textContent = "Crédits";

  tr1.appendChild(td1);
  tr1.appendChild(td2);
  tr1.appendChild(td3);
  tr1.appendChild(td4);

  thead.appendChild(tr1);
  table_ecs.appendChild(thead);

  var tbody = document.createElement("tbody");
  var i = 1;

  var url = 'API_PHP/Liste_EC_Aligne.php';
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        mat_agent: mat_agent_,
        id_annee_acad: cmb_annee_academique_aligne.value,
        id_semestre: cmb_semestre_alignre.value,
        code_prom: cmb_promotion_FAC.value
    })
  })
  .then(response => response.json())
  .then(data => 
  {
      data.forEach(ec => {
        // Création de TR
        var tr = document.createElement("tr");

        tdnum = document.createElement("td");
        tdnum.textContent = i;
        tdnum.classList.add("text-center");

        var td_intitule = document.createElement("td");
        var td_credits = document.createElement("td");
        var td_action = document.createElement("td");

        td_intitule.textContent = ec.Intutile_ec;
        td_credits.textContent = ec.Credit;


        var div = document.createElement("div");
        div.classList.add("row", "text-center", "p-0", "m-0");
        div.style.display = "flex";
        div.style.justifyContent = "center";
        div.style.alignItems = "center";

        var case_cocher=document.createElement("input");
        case_cocher.type="checkbox";
        case_cocher.style.width = "20px"; // Augmenter la largeur
        case_cocher.style.height = "20px"; // Augmenter la hauteur
        case_cocher.classList.add("form-check-input")

        

       


        if(ec.etat_ec===1 && ec.etat_ec_pris===1 )
        {
          case_cocher.disabled=false;
          case_cocher.checked=true;
        }
        if((ec.etat_ec!==1 && ec.etat_ec_pris===1 ))case_cocher.disabled=true;
        if(ec.etat_ec_pris === 1)case_cocher.checked=true;
        
        
        
        // Ajouter l'événement pour ajouter ou supprimer EC aligné
        case_cocher.addEventListener('change', function() 
        {
          
          if (case_cocher.checked) {
              Ajouter_EC_Aligne(ec.id_ec, mat_agent_); 
              Affichage_ECs_Par_Filiere();
          } else {
              Supprimer_EC_Aligne(ec.id_ec,mat_agent_);
              Affichage_ECs_Par_Filiere();

          }
        });


        div.appendChild(case_cocher);
        td_action.appendChild(div);         
        
        tr.appendChild(tdnum);          
        tr.appendChild(td_action);
        tr.appendChild(td_intitule);
        tr.appendChild(td_credits);

        tbody.appendChild(tr);
        i++;
    });
  })
  .catch((error) => 
  {
    console.log("Erreur lors de la récupération des ECs: " + error);
  });
  table_ecs.appendChild(tbody);
  table_ecs.classList.add("table-striped");
}



/*
  *****************************************************************************************
  ************  CETTE FONCTION PERMET D'AFFCIHER LES ECs D'UNE UE ********************
  *****************************************************************************************
  */
  function Selectionner_Enseignant(mat_agent,tr1)
  {
    // Ce bout de code permet de faire une selection de ligne en fixant une couleur de fond
    var table_aligne_enseignant= document.getElementById("table_aligne_enseignant");
    var rows = table_aligne_enseignant.getElementsByTagName('tr');  
    for(var j = 0; j < rows.length; j++) 
    {
      if(j!=0) rows[j].style.backgroundColor = '';
    }
    tr1.style.backgroundColor = 'RGBA(255,200,1,0.5)';
    tr_selectionner=tr1;
    

  }
  //

  
  
  /*
  *****************************************************************************************
  ************  CETTE FONCTION D'AJOUTER UN NOUVEL EC  ************************************
  *****************************************************************************************
  */

  function Ajouter_EC_Aligne(ec, mat_agent) 
  {
    //console.log("Je suis dans ajouter")
    var url = 'API_PHP/Ajout_EC_Aligne.php';

    const data = {
        idAnnee_Acad: cmb_annee_academique_aligne.value,
        id_ec: ec,
        Id_Semestre: cmb_semestre_alignre.value,
        Code_Promotion: cmb_promotion_FAC.value,
        Mat_agent: mat_agent
    };
    fetch(url, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            console.log(result.message);
        } else {
          console.log('Erreur : ' + result.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        console.log('Erreur lors de l\'ajout de l\'élément constitutif aligné.');
    });

    
}


  /******************************************************************** */



  function Supprimer_EC_Aligne(ec, mat_agent) {
    var url = 'API_PHP/Supprimer_EC_Aligner.php';

    const data = {
        idAnnee_Acad: cmb_annee_academique_aligne.value,
        id_ec: ec,
        Id_Semestre: cmb_semestre_alignre.value,
        Code_Promotion: cmb_promotion_FAC.value,
        Mat_agent: mat_agent
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
          console.log(result.message);
        } else {
          console.log('Erreur : ' + result.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        console.log('Erreur lors de la suppression de l\'élément constitutif aligné.');
    });
}