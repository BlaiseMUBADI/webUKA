

/*
*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*+++++++++++++++++++ C'est un script qui charge des opérations de nouveaux paiements+++++++++
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*
*/

/*
*********************************************************************************************
* ***************************** Déclaration des composants HTML *****************************
*********************************************************************************************
*/
const txt_montant=document.getElementById("txt_montant_payer");
const txt_numero_borderau=document.getElementById("txt_numero_borderau");

const cmb_type_frais=document.getElementById("Select_type_frais");
const cmb_banque=document.getElementById("Select_banque");


const case_ems=document.getElementById("case_ems");
const case_es=document.getElementById("case_es");
const case_e2s=document.getElementById("case_e2s");

const date_paie=document.getElementById("date_paiement");







// Ici on lance ce script pour selectionner la date du jours dans le composant

var date_actuelle = new Date();

// Obtenir la date au format YYYY-MM-DD
var formattedDate = date_actuelle.toISOString().substr(0, 10);

// Mettre à jour la valeur par défaut de l'input date
date_paie.value = formattedDate;



const btn_valider_paie_guichet= document.getElementById("btn_valider_paie_guichet");
const btn_valider_paie_banque= document.getElementById("btn_valider_paie_banque");
//const btn_valider_paie= document.getElementById("btn_valider_paie");







// Ajout d'un gestionnaire d'événement pour le clic sur le bouton
/*btn_valider_paie_guichet.addEventListener("click", function() {Paiement_frais_guichet()});
btn_valider_paie_banque.addEventListener("click", function() {Paiement_frais_banque()});
*/



function Paiement_frais_guichet()
{
    // URL vers le fichier d'impression d

    var code_promo=cmb_promotion.value;
    var Id_an_acad=cmb_annee_academique.value;
    var mat_etudiant="";

    // Ici on fait recuperer la date au format date et heure
    var date_paiement=date_paie.value;

    // Créer un objet Date avec la chaîne de date et heure
    var datetime = new Date(date_paiement);

    // Convertir la date en format MySQL datetime (YYYY-MM-DD HH:mm:ss)
    var mysqlDatetime = datetime.toISOString().slice(0, 19).replace('T', ' ');

    console.log(" Voici la date "+mysqlDatetime);


    var type_frais=cmb_type_frais.value;
    var montant=txt_montant.value;

    var etat_ems=case_ems.value;
    var etat_es=case_es.value;
    var etat_e2s=case_e2s.value;

    var motif_paiement=[];
    var tab_type_frais=[];


    //$motif_paiement = array();
    if(type_frais=="Enrôlement à la Session")
    {
        //ab_type_frais=
        if(case_ems.checked) motif_paiement.push("Enrôlement à la Mi-Session");
        if(case_es.checked) motif_paiement.push("Enrôlement à la Grande-Session");
        if(case_e2s.checked) motif_paiement.push("Enrôlement à la Deuxième-Session");

    }
    else if(type_frais=="Frais Académiques")
    {
        motif_paiement.push("Frais Académiques");   
    }
    else if(type_frais=="Autres frais")
    {
        motif_paiement.push("Autres frais");   
    }
    else if(type_frais=="Frais Académiques et Enrôlement à la Session")
    {
        if(case_ems.checked) motif_paiement.push("Enrôlement à la Mi-Session");
        if(case_es.checked) motif_paiement.push("Enrôlement à la Grande-Session");
        if(case_e2s.checked) motif_paiement.push("Enrôlement à la Deuxième-Session");
    }
    
    mat_etudiant=document.getElementById("mat_etudiant").value;
    
    console.log(" Le matricule est "+mat_etudiant);

    var json_motif_paiement = JSON.stringify(motif_paiement);// Ce Json est très important car il permet d'envoyé les données du Javascript vers PHP
            
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "JavaScript/Nouveau_paiement_guichet.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            console.log(xhr.responseText)
            // Réponse du serveur
            if(xhr.responseText!="Ok") 
            {
                let nom_etudiant=document.getElementById("nom_etudiant_1").value+" "+
                document.getElementById("postnom_etudiant").value+" "+
                document.getElementById("prenom_etudiant").value;

                //t mat_etuiant=document.getElementById("mat_etudiant").value;

                var url="impression/Docs_a_imprimer/recu.php"
                +"?Mat_etudiant="+mat_etudiant
                +"&Nom_etudiant="+nom_etudiant
                +"&Montant_payer="+montant
                +"&Motif_paiement="+motif_paiement
                +"&Date_paiement="+mysqlDatetime                
                +"&Code_promo="+code_promo;
                let parametres = "left=20,top=20,width=700,height=500";
               
                let fenetre_recu=window.open(
                    url,
                    "Impression Réçu",
                    parametres
                );

                fenetre_recu.onload = function() {
                    alert("Enregistrment effectuer avec succès");
                    Intialisation_zone_paiement();
                  };
                
                
            }
                
            else 
            {
                alert( " Echec d'eregistrement ");
            }
        }
       /* else
        {
            console.log("nous avons rencotrer un blm");
        }*/
    };
            xhr.send("mat_etudiant=" + mat_etudiant 
            + "&Id_an_acad=" + Id_an_acad
            + "&code_promo=" + code_promo
            + "&montant_payer=" + montant
            + "&motif_paiement=" + json_motif_paiement
            + "&type_frais=" + type_frais
            + "&date_paiement=" + date_paiement);
}




function Paiement_frais_banque()
{
    // URL vers le fichier d'impression d

    var code_promo=cmb_promotion.value;
    var Id_an_acad=cmb_annee_academique.value;
    var mat_etudiant="";
    var date_paiement=date_paie.value;

    var type_frais=cmb_type_frais.value;    
    var idbanque=cmb_banque.value;

    var montant=txt_montant.value;
    var numero_bordereau=txt_numero_borderau.value;

    var etat_ems=case_ems.value;
    var etat_es=case_es.value;
    var etat_e2s=case_e2s.value;

    var motif_paiement=[];
    var tab_type_frais=[];


    //$motif_paiement = array();
    if(type_frais=="Enrôlement à la Session")
    {
        //ab_type_frais=
        if(case_ems.checked) motif_paiement.push("Enrôlement à la Mi-Session");
        if(case_es.checked) motif_paiement.push("Enrôlement à la Grande-Session");
        if(case_e2s.checked) motif_paiement.push("Enrôlement à la Deuxième-Session");

    }
    else if(type_frais=="Frais Académiques")
    {
        motif_paiement.push("Frais Académiques");   
    }
    else if(type_frais=="Autres frais")
    {
        motif_paiement.push("Autres frais");   
    }
    else if(type_frais=="Frais Académiques et Enrôlement à la Session")
    {
        if(case_ems.checked) motif_paiement.push("Enrôlement à la Mi-Session");
        if(case_es.checked) motif_paiement.push("Enrôlement à la Grande-Session");
        if(case_e2s.checked) motif_paiement.push("Enrôlement à la Deuxième-Session");
    }
    
    mat_etudiant=document.getElementById("mat_etudiant").value;
    var json_motif_paiement = JSON.stringify(motif_paiement);// Ce Json est très important car il permet d'envoyé les données du Javascript vers PHP
       
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "JavaScript/Nouveau_paiement_banque.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            console.log(xhr.responseText)
            // Réponse du serveur
            if(xhr.responseText!="Ok") 
            {
                let nom_etudiant=document.getElementById("nom_etudiant_1").value+" "+
                document.getElementById("postnom_etudiant").value+" "+
                document.getElementById("prenom_etudiant").value;

                //t mat_etuiant=document.getElementById("mat_etudiant").value;

                var url="impression/Docs_a_imprimer/recu.php"
                +"?Mat_etudiant="+mat_etudiant
                +"&Nom_etudiant="+nom_etudiant
                +"&Montant_payer="+montant
                +"&Motif_paiement="+motif_paiement
                +"&Date_paiement="+date_paiement                
                +"&Code_promo="+code_promo;
                let parametres = "left=20,top=20,width=700,height=500";
                
                let fenetre_recu=window.open(
                    url,
                    "Impression Réçu",
                    parametres
                );

                fenetre_recu.onload = function() {
                    alert("Enregistrment effectuer avec succès");
                    Intialisation_zone_paiement();
                  };
                
                
            }
                
            else 
            {
                alert( " Echec d'eregistrement ");
            }
        }
       /* else
        {
            console.log("nous avons rencotrer un blm");
        }*/
    };
            xhr.send("mat_etudiant=" + mat_etudiant 
            + "&Id_an_acad=" + Id_an_acad
            + "&code_promo=" + code_promo
            + "&montant_payer=" + montant
            + "&motif_paiement=" + json_motif_paiement
            + "&type_frais=" + type_frais
            + "&date_paiement=" + date_paiement
            + "&idbanque=" +idbanque
            + "&numero_borderau=" + numero_bordereau);
}




function Intialisation_zone_paiement()
{
    txt_montant.value="";
    txt_numero_borderau.value="";
    case_e2s.checked=false;
    case_ems.checked=false;
    case_es.checked=false;
    cmb_type_frais.selectedIndex = 0;
    cmb_banque.selectedIndex=0;



    // appel de la methode pour reafficher les nouvelles infos inserées

    Recuperation_situation_finaniere(
        txt_mat_etudiant.value,
        txt_nom_etudiant.value, 
        txt_postnom_etudiant.value,
        txt_prenom_etudiant.value, 
        txt_sexe_etudiant.value,
        cmb_annee_academique.value);
    
    //cmb

}
    