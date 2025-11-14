console.log(" je suis dans Manip_Enseignant")

/*
*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*+++++++++++++++++++ C'est un script qui se charge de la manipulation des comptes agents+++++++++
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*
*/

/*
*********************************************************************************************
* ***************************** Déclaration des composants HTML *****************************
*********************************************************************************************
*/

// Les éléments du DOM sont initialisés seulement si la page contient
// l'élément parent `div_gen_Enseignant`. Cela évite que ce script lance des
// getElementById() au top-level et retourne `null` quand il est inclus
// sur d'autres pages.
let txt_mat_enseignant;
let txt_nom_enseignant;
let txt_post_enseignant;
let txt_prenom_enseignant;
let txt_telephone_enseignant;
let txt_email_enseignant;
let txt_institution_attache;
let txt_domaine_enseignant;

let btn_sexe_enseignant_F;
let btn_sexe_enseignant_M;

let cmb_niveau_etude_enseignant;
let cmb_grade__enseignant;

let cmb_categorie_;
let cmb_etat_civil;

let boite_Form_Enseignant;
let boite_alert_Enseignant;



// Ce code nous permet de mettre en rouge le texte saisi dans la zone de text de code ue si
// ce dernier est déjà utilisé 
/*if(txt_code_ue!==null)
{
    txt_code_ue.addEventListener("keyup", function(event)
    {
      Verification_code_ue(txt_code_ue.value);        
    });

}*/

/************************************************************************************
******************* CE CODE PERMET D'AFFICHER LES SEMESTRES **************************
***************************************************************************************/
document.addEventListener("DOMContentLoaded",function(event)
{
  const container = document.getElementById("div_gen_Enseignant");
  if (container !== null) {
    // initialiser les éléments en utilisant le conteneur pour éviter
    // toute sélection hors-contexte lorsque ce script est inclus sur
    // d'autres pages
    txt_mat_enseignant = container.querySelector('#txt_mat_enseignant') || document.getElementById('txt_mat_enseignant');
    txt_nom_enseignant = container.querySelector('#txt_nom_enseignant') || document.getElementById('txt_nom_enseignant');
    txt_post_enseignant = container.querySelector('#txt_post_nom_enseignant') || document.getElementById('txt_post_nom_enseignant');
    txt_prenom_enseignant = container.querySelector('#txt_prenom_enseignant') || document.getElementById('txt_prenom_enseignant');
    txt_telephone_enseignant = container.querySelector('#txt_telephone_enseignant') || document.getElementById('txt_telephone_enseignant');
    txt_email_enseignant = container.querySelector('#txt_email_enseignant') || document.getElementById('txt_email_enseignant');
    txt_institution_attache = container.querySelector('#txt_institution_attache') || document.getElementById('txt_institution_attache');
    txt_domaine_enseignant = container.querySelector('#txt_domaine_etude') || document.getElementById('txt_domaine_etude');

    btn_sexe_enseignant_F = container.querySelector('#sexe_enseignant_F') || document.getElementById('sexe_enseignant_F');
    btn_sexe_enseignant_M = container.querySelector('#sexe_enseignant_M') || document.getElementById('sexe_enseignant_M');

    cmb_niveau_etude_enseignant = container.querySelector('#cmb_niveau_etude_enseignant') || document.getElementById('cmb_niveau_etude_enseignant');
    cmb_grade__enseignant = container.querySelector('#cmbr_titre_academique') || document.getElementById('cmbr_titre_academique');

    cmb_categorie_ = container.querySelector('#cmb_Categorie') || document.getElementById('cmb_Categorie');
    cmb_etat_civil = container.querySelector('#cmb_Etat_Civil') || document.getElementById('cmb_Etat_Civil');

    // boîtes de dialogue peuvent être en dehors du conteneur
    boite_Form_Enseignant = document.getElementById('boite_Form_Enseignant');
    boite_alert_Enseignant = document.getElementById('boite_alert_Enseignant');

    cmb_grade__enseignant.innerHTML = '<option value="rien" selected>Grade Enseignant</option>';
    cmb_categorie_.addEventListener('change',(event)=> 
    {
      
      id_cat=cmb_categorie_.value;
      
            
            if (id_cat === "1") {
                var option1 = new Option("Professeur Emerite", "PE"); 
                var option2 = new Option("Professeur Ordinaire", "PO"); 
                var option3 = new Option("Professeur", "P"); 
                var option4 = new Option("Professeur Associé", "PA"); 
                cmb_grade__enseignant.add(option1); 
                cmb_grade__enseignant.add(option2);
                cmb_grade__enseignant.add(option3);
                cmb_grade__enseignant.add(option4);
            } else if (id_cat === "2") {
                var option1 = new Option("Chef de Travaux", "CT"); 
                var option2 = new Option("Assistant 2", "ASS2"); 
                var option3 = new Option("Assistant 1", "ASS1"); // Correction ici
                var option4 = new Option("Assistant de recherche 2", "ASSR2"); // Correction ici
                var option5 = new Option("Assistant de recherche 1", "ASSR1"); // Correction ici
                var option6 = new Option("Chargé de Pratique", "CPP"); 
                cmb_grade__enseignant.add(option1); 
                cmb_grade__enseignant.add(option2);
                cmb_grade__enseignant.add(option3);
                cmb_grade__enseignant.add(option4);
                cmb_grade__enseignant.add(option5);
                cmb_grade__enseignant.add(option6);
            }
      
    });
    Affichage_Enseignant();

  } 
})



/**********************************************************************************************
******************* CE CODE PERMET D'AFFICHER TOUT LES AGENT DE L'UNIVERSITE DANS LE table_enseignant 
***************************************************************************************/
function Affichage_Enseignant()
{

   
    let table_enseignant = document.getElementById("table_enseignant");

    while (table_enseignant.firstChild) {
      table_enseignant.removeChild(table_enseignant.firstChild);
    }
    
    
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top","m-0","fw-bold", "text-center"); // Pour ajouter la classe à un element HTMl

    var tr1 = document.createElement("tr");
    tr1.style="background-color:midnightblue; color:white;"

    var td1 = document.createElement("td");      
    var td2 = document.createElement("td");      
    var td3 = document.createElement("td");      
    var td4 = document.createElement("td");      
    var td5 = document.createElement("td");      
    var td6 = document.createElement("td");      
    var td7 = document.createElement("td");      
    var td8 = document.createElement("td");      
    var td9 = document.createElement("td");      
    var td10 = document.createElement("td");      
    var td11 = document.createElement("td");      
    var td12 = document.createElement("td");      

    td1.textContent = "N°";
    td2.textContent = "Matricule";
    td3.textContent = "Enseignant";
    td4.textContent = "Sexe";
    td5.textContent = "Etude";
    td6.textContent = "Titre Académmique";
    td7.textContent = "Domaine";
    td8.textContent = "Institution Attache";
    td9.textContent = "Filière";
    td10.textContent = "Phone";
    td11.textContent = "Email";
    td12.textContent = "Photo";

    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td6);
    tr1.appendChild(td7);
    tr1.appendChild(td8);
    tr1.appendChild(td9);
    tr1.appendChild(td10);
    tr1.appendChild(td11);
    tr1.appendChild(td12);

      
    thead.appendChild(tr1);
    table_enseignant.appendChild(thead);
      
    var tbody = document.createElement("tbody");
    
    

    var url='API_PHP/Liste_Enseignants.php';
        
    var i=1;
    fetch(url) 
    .then(response => response.json())
    .then(data => 
    {
      data.forEach(infos =>
        {
          // Création de TR
              var tr = document.createElement("tr");
              var tdnum = document.createElement("td");
              tdnum.textContent = i;
              tdnum.classList.add("text-center,col-md-auto");

    
              var td_mat =document.createElement("td");
              var td_enseignant= document.createElement("td");
              var td_sexe = document.createElement("td");
              var td_etude = document.createElement("td");
              var td_titre_academique = document.createElement("td");
              var td_domaine = document.createElement("td");
              var td_institution= document.createElement("td");
              var td_filire= document.createElement("td");
              var td_phone= document.createElement("td");
              var td_email= document.createElement("td");
              var td_photo= document.createElement("td");
              

              td_mat.textContent=infos.mat_agent;
              td_mat.classList.add("col-md-auto");

              td_enseignant.textContent=infos.enseignant;
              td_enseignant.classList.add("col-md-auto");

              td_sexe.textContent=infos.sexe;
              td_sexe.classList.add("col-md-auto");

              td_etude.textContent=infos.niveau_etude;
              td_etude.classList.add("col-md-auto");

              td_titre_academique.textContent=infos.titre_academique;
              td_titre_academique.classList.add("col-md-auto");

              td_domaine.textContent=infos.domaine;
              td_domaine.classList.add("col-md-auto");
              
              td_institution.textContent=infos.institut_attache;
              td_institution.classList.add("col-md-auto");

              td_filire.textContent=infos.filiere;
              td_filire.classList.add("col-md-auto");

              td_phone.textContent=infos.phone;
              td_phone.classList.add("col-md-auto");

              td_email.textContent=infos.email;
              td_email.classList.add("col-md-auto");

              td_photo.textContent=infos.photo;
              td_phone.classList.add("col-md-auto");
              
              tr.appendChild(tdnum);
              tr.appendChild(td_mat);
              tr.appendChild(td_enseignant);  
              tr.appendChild(td_sexe);            
              tr.appendChild(td_etude);
              tr.appendChild(td_titre_academique);            
              tr.appendChild(td_domaine);            
              tr.appendChild(td_institution);            
              tr.appendChild(td_filire);            
              tr.appendChild(td_phone);            
              tr.appendChild(td_email);            
              tr.appendChild(td_photo);            
              
              
              tbody.appendChild(tr);
              i++;

              
              
              
        });
          
        }).catch(error => {
          // Traitez l'erreur ici
          console.log("Erreur lors de contacte des enseignants "+error);});
          table_enseignant.appendChild(tbody);
          table_enseignant.classList.add("table-striped");
}
/*****************  FIN DE LA METHODE D'AFFICHAGE DES ENSEIGNANTS*************************************/



/**********************************************************************************************
******************* Cette méthode permet d'ajouter une nouvelle unité d'enseignement *****************
************************************************************************************************/

function Ajout_Enseignants()
{
  let sexe='M'
  if(btn_sexe_enseignant_F.checked) sexe='F';
  
  var url = 'API_PHP/Ajout_Enseignants.php';

  const data = {
      mat_enseignant: txt_mat_enseignant.value,
      nom_enseignant: txt_nom_enseignant.value,
      post_enseignant: txt_post_enseignant.value,
      prenom_enseignant: txt_prenom_enseignant.value,
      sexe_enseignant: sexe,
      Grade: cmb_grade__enseignant.value,
      EtatCivil: cmb_etat_civil.value,
      IdCategorie: cmb_categorie_.value,
      niveau_etude_enseignant: cmb_niveau_etude_enseignant.value,
      telephone_enseignant: txt_telephone_enseignant.value,
      email_enseignant : txt_email_enseignant.value,
      domaine_enseignant : txt_domaine_enseignant.value,
      photo_enseignant: photo_profil,
      Insitution: txt_institution_attache.value,
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
      if (result.success) {
          console.log(result.message);
          Affichage_Enseignant();
          Ouvrir_Boite_Alert_Enseignant(result.message);  
      } else {
        Ouvrir_Boite_Alert_Enseignant(result.message);  
      }
  })
  .catch(error => {
      console.error('Erreur:', error);
      console.log('Erreur lors de l\'ajout de l\'enseignant.');
  });
  
  //Fermer_Form_UE();
}
// *************  FIN DE LA METHODE AJOUT  ************************


/*************************************************************************************
********************    ICI C'EST POUR OUVRIR LA BOITE DE DIALOGUE ********************
***************************************************************************************/

function Ouvrir_Form_Enseignant()
{
    boite_Form_Enseignant.showModal();
}
// Fermer la boîte de dialogue
function Fermer_boite_Enseignant() {
    boite_Form_Enseignant.close();
}


function Ouvrir_Boite_Alert_Enseignant(text_a_afficher)
{
    document.getElementById("text_alert_boite").innerText=text_a_afficher;
    boite_alert_Enseignant.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Boite_Alert_Enseignant() {
  boite_alert_Enseignant.close();
}

/******************************  FIN MANIPULATION DE LA BBOITE E DIALOGUE********************** */






/********************     LA METHODE POUR VERIFIER SI LE CODE UE N'EST PAS ENCORE ATTRIBUER  */

function Verification_code_ue(code_ue)
{
 
 // console.log(" voici le contenu code "+txt_code_ue.value===null +" et libelle " + txt_libelle_ue.value.length)
    // Contacte de l'API PHP
    const url='API_PHP/Verification_Code_UE.php?code_ue='+code_ue;
          
    fetch(url) 
    .then(response => response.json())
    .then(
      data => {
      data.forEach(infos => {
        
        
        var nb=infos.nb_ue;

        if (nb>0 || txt_code_ue.value.length==0 ||  txt_libelle_ue.value.length==0)
        {
            txt_code_ue.style.color = 'red';
            verfi=false;
        } 
        else
        {
          txt_code_ue.style.color = 'white';
            verfi=true;
        }
        
      });
    }
  
  )
    .catch(error => console.error('Erreur lors de la verification de code UE:', error));
}




