console.log(" je suis dans noueau paiement ")

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
const txt_monte_caractere=document.getElementById("txt_monte_caractere");
const txt_numero_borderau=document.getElementById("txt_numero_borderau");

const txt_tau_jours=document.getElementById("txt_tau_jours");

const cmb_type_frais=document.getElementById("Select_type_frais");
const cmb_banque=document.getElementById("Select_banque");


const case_ems=document.getElementById("case_ems");
const case_es=document.getElementById("case_es");
const case_e2s=document.getElementById("case_e2s");
const case_ERSEM1=document.getElementById("case_ERSEM1");
const case_VC=document.getElementById("case_VC");

const btn_radio_devise=document.getElementById("dollar_payer"); // c'est une constatnte qui garde l'etat de devise

const symbole_devise=document.getElementById("symbole_devise");


const date_paie=document.getElementById("date_paiement");
var devise_paye="Franc Congolais";
var montant_en_franc=0;

var montant_taux_base=5; // Le taux dans la base de données
const div_montant_payer_fc=document.getElementById("montant_payer_fc");
var montnt_argent_dollar_fc=0;
var montant_devise_inverse="0"; // c'un montant que l'on met dans la base pour l'impression de de rapport en deux etats


var Verfi_num_borde=true; // C'est une variable globale qui nous permet de stocker la verification de numéro de bordereau

// Ici on lance ce script pour selectionner la date du jours dans le composant
var date_actuelle = new Date();
// Obtenir la date au format YYYY-MM-DD
var formattedDate = date_actuelle.toISOString().substr(0, 10);
// Ici on test si l'élement selectionner est present sur la page html
if (date_paie !== null) date_paie.value = formattedDate;



const btn_valider_paie_guichet= document.getElementById("btn_valider_paie_guichet");
const btn_valider_paie_banque= document.getElementById("btn_valider_paie_banque");



//const boite_form_UEs = document.getElementById('boite_Form_UE');
const boite_alert_Paiement_banque= document.getElementById('boite_alert_paiement_banque');
const boite_alert_Paiement_guichet= document.getElementById('boite_alert_paiement_guichet');
//const boite_confirmation_Transaction= document.getElementById('boite_confirmaion_Transactions');






// Attacher l'évenement à la zone de texte qui concerne le numéro de borderau
// on test si l'élement existe vraiment sur la page html
// Puis on appel la fonction qui verifie si le numero de borderau
if(txt_numero_borderau!==null)
{
    txt_numero_borderau.addEventListener("keyup", function(event)
    {
        var txt_bordereau=txt_numero_borderau.value;
        Verification_Num_bordereau(txt_bordereau);       
        //Affichage_etudiant_2(code_promo,Id_annee,txt_nom);
    });

}

/*************************************************************************************
 * *************** ICI ON ATTACHE UN EVEMENT A GROUPE DE RADIO POUR CHANGER LA DEVISE
 * ***********************************************************************************/

/****************************************************************************************
 ****** ICI ON CONTROLA LA SAISI DANS LA ZONE DE TEXTE **********************************
*/
if(txt_montant!==null)
{
    txt_montant.addEventListener("keyup", function(event)
    {
        var devis="";
        var devise_fa=document.getElementById("devise_fa").innerHTML;
        
        // ici verifie pour faire une conversion  lorsqu'il s'agit d'un paiement en dollar
        // c-a-d la devise fixée dans la modalité est en dollar
        if(devise_fa===" $")
        {
            
            if (btn_radio_devise.checked) 
            {
                montnt_argent_dollar_fc=txt_montant.value;
                devise_paye="Dollar";
                devis=".$."

                montant_devise_inverse=0;
            }
            else 
            {
                devise_paye="Franc Congolais";
                
                montnt_argent_dollar_fc=(txt_montant.value/(montant_taux_base/10)).toFixed(2);
                div_montant_payer_fc.innerText="Montant en $ : ( "+montnt_argent_dollar_fc+" )";
                devis=".Fc."

                montant_devise_inverse=montnt_argent_dollar_fc;

            }
        }
        // Ici nous ce test ce pour le paiement en Fc
        // C'est à dire la devise fixée dans la modalité est Franc Congolais
        else
        {
            //console.log(" Attention le paiement s'effetue en Fc");

            if (btn_radio_devise.checked) 
            {
                montnt_argent_dollar_fc=(txt_montant.value*(montant_taux_base/10)).toFixed(2);
                div_montant_payer_fc.innerText="Montant en $ : ( "+montnt_argent_dollar_fc+" )";
               
                devise_paye="Dollar";
                devis=".$."

                montant_devise_inverse=0;
            }
            else 
            {
                devise_paye="Franc Congolais";
                devis=".Fc."
                montnt_argent_dollar_fc=txt_montant.value;

                montant_devise_inverse=montnt_argent_dollar_fc;
            }
        }
        
        
        symbole_devise.innerHTML=devis;
        var nombre=txt_montant.value;

        // Convertir le nombre 500000000 en chaîne de caractères
        const nombreEnChaine =Conversion_Nombre_En_ChaineCaractere(nombre);
        txt_monte_caractere.innerHTML=nombreEnChaine+" "+devise_paye;
    });

    txt_montant.addEventListener("blur", function() 
    {
        if (!btn_radio_devise.checked) 
        {
            //parler("Ton argent en dollar fait , "+(montnt_argent_dollar_fc.toString())) 
        
        }
          
    });

}
///////////////////////////////////////////////////////////////////////////////////////////////////



if(txt_tau_jours!==null)
{
  // Contacte de l'API PHP
    const url='D_Perception/API_PHP/Recup_taux_base.php';
          
    fetch(url) 
    .then(response => response.json())
    .then(data => {
      data.forEach(infos => {
        

        txt_tau_jours.innerText=infos.montant+" Fc";
        montant_taux_base=infos.montant;
      
      });
    })
    .catch(error => console.error('Erreur lors de la récupération des promotions :', error));
     

}

/***********************************************************************************************************
 ************** ICI , CETTE FONCTION NOUS PERMET DE FAIRE LA VERIFICATION DE NUMERO DE BBORDEREAU **********
 ************************************************************************************************************
*/

function Verification_Num_bordereau(Num_bordereau)
{
    
    // Contacte de l'API PHP
    const url='D_Perception/API_PHP/Verification_num_bordereau.php?num_bordereau='+Num_bordereau;
          
    fetch(url) 
    .then(response => response.json())
    .then(data => {
      data.forEach(infos => {
        
        var nb=infos.nb_num_bordereau;
        //console.log(" voici le nb "+nb);
        if (nb>0)
        {
            txt_numero_borderau.style.color = 'red';
            Verfi_num_borde=false;
        } 
        else
        {
            txt_numero_borderau.style.color = 'white';
            Verfi_num_borde=true;
        }
        
      });
    })
    .catch(error => console.error('Erreur lors de la récupération de nombre de borderau:', error));

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function Verification_avant_paiement(type_frais)
{
    var t=0;
    if(type_frais=="Frais Académiques") t++;
    
    else if(type_frais=="Enrôlement à la Session")
    {
        if(case_ems.checked) t++;;
        if(case_es.checked) t++;
        if(case_e2s.checked) t++;
        if(case_ERSEM1.checked) t++;
        if(case_VC.checked) t++;

    }
    else if(type_frais=="Frais Académiques et Enrôlement à la Session")
    {
        if(case_ems.checked) t++;;
        if(case_es.checked) t++;
        if(case_e2s.checked) t++;
        if(case_ERSEM1.checked) t++;
        if(case_VC.checked) t++;

    }
    else t=0;

    if (btn_radio_devise.checked) 
    {
        montant_en_franc=0;
        devise_paye="Dollar";
    }
    else
    {
        montant_en_franc=txt_montant.value;
        devise_paye="Franc Congolais";
    
    }
    var devis="";
    var devise_fa=document.getElementById("devise_fa").innerHTML;
        
        // ici verifie pour faire une conversion  lorsqu'il s'agit d'un paiement en dollar
        // c-a-d la devise fixée dans la modalité est en dollar
    if(devise_fa===" $")
    {
            
        if (btn_radio_devise.checked) 
        {
            montnt_argent_dollar_fc=txt_montant.value;
            devise_paye="Dollar";
            devis=".$."

            montant_devise_inverse=0;
        }
        else 
        {
            devise_paye="Franc Congolais";
                
            montnt_argent_dollar_fc=(txt_montant.value/(montant_taux_base/10)).toFixed(2);
            div_montant_payer_fc.innerText="Montant en $ : ( "+montnt_argent_dollar_fc+" )";
            devis=".Fc."

            montant_devise_inverse=montnt_argent_dollar_fc;

         }
    }
    // Ici nous ce test ce pour le paiement en Fc
    // C'est à dire la devise fixée dans la modalité est Franc Congolais
    else
    {
        console.log(" Attention le paiement s'effetue en Fc");

        if (btn_radio_devise.checked) 
        {
            montnt_argent_dollar_fc=(txt_montant.value*(montant_taux_base/10)).toFixed(2);
            div_montant_payer_fc.innerText="Montant en $ : ( "+montnt_argent_dollar_fc+" )";
               
            devise_paye="Dollar";
            devis=".$."

            montant_devise_inverse=0;
         }
        else 
        {
            devise_paye="Franc Congolais";
            devis=".Fc."
            montnt_argent_dollar_fc=txt_montant.value;

            montant_devise_inverse=montnt_argent_dollar_fc;
        }
    }
        
        
    symbole_devise.innerHTML=devis;
    var nombre=txt_montant.value;

    // Convertir le nombre 500000000 en chaîne de caractères
    const nombreEnChaine =Conversion_Nombre_En_ChaineCaractere(nombre);
    txt_monte_caractere.innerHTML=nombreEnChaine+" "+devise_paye; 
    
    if(t>0) return true;
    else return false;
}
function Paiement_frais_guichet()
{
    var type_frais=cmb_type_frais.value;
    if(Verification_avant_paiement(type_frais))
    {
        
        var code_promo=cmb_promotion.value;
        var Id_an_acad=cmb_annee_academique.value;
        var mat_etudiant="";


        var devise_fa=document.getElementById("devise_fa").innerHTML;
        var devise_enrol=document.getElementById("devise_eronl").innerHTML;

        /*var devise_fa=document.getElementById("devise_fa");
        var devise=" Fc";

        if(devise_fa.innerText==="$")devise=" $";*/

        // Ici on fait recuperer la date au format date et heure
        //var date_paiement=date_paie.value;
        var date_paiement= new Date().toISOString().substr(0, 10);


        // Ici on rcupère la ate de l'heure, minutes et séconde
        var date_actuelle = new Date();
        var heure = date_actuelle.getHours();
        var minutes = date_actuelle.getMinutes();
        var secondes = date_actuelle.getSeconds();

        // Créer une chaîne au format YYYY-MM-DD HH:mm:ss
        
        // Convertir la date en format MySQL datetime (YYYY-MM-DD HH:mm:ss)
        date_paiement = date_paiement+ ' ' + ("0" + heure).slice(-2) + ':' + ("0" + minutes).slice(-2) + ':' + ("0" + secondes).slice(-2);
        ;
        
        
        
        

        
        var montant=montnt_argent_dollar_fc;
        

        var etat_ems=case_ems.value;
        var etat_es=case_es.value;
        var etat_e2s=case_e2s.value;
        var etat_ESEM1=case_ERSEM1.value;
        var etat_VC=case_VC.value;

        var motif_paiement=[];
        var tab_type_frais=[];


        //$motif_paiement = array();
        if(type_frais=="Enrôlement à la Session")
        {
            //ab_type_frais=
            if(case_ems.checked) motif_paiement.push("Enrôlement à la Mi-Session");
            if(case_es.checked) motif_paiement.push("Enrôlement à la Grande-Session");
            if(case_e2s.checked) motif_paiement.push("Enrôlement à la Deuxième-Session");
            if(case_ERSEM1.checked) motif_paiement.push("Enrôlement aux rattrapages sem 1");
            if(case_VC.checked) motif_paiement.push("Enrôlement validation crédits");

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
            if(case_ERSEM1.checked) motif_paiement.push("Enrôlement aux rattrapages sem 1");
            if(case_VC.checked) motif_paiement.push("Enrôlement validation crédits");
        }
        
        mat_etudiant=document.getElementById("mat_etudiant").value;

        var json_motif_paiement = JSON.stringify(motif_paiement);// Ce Json est très important car il permet d'envoyé les données du Javascript vers PHP
                
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "D_Perception/API_PHP/Nouveau_paiement_guichet.php", true);
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

                    var url="Impression/Docs_a_imprimer/recu.php"
                    +"?Mat_etudiant="+mat_etudiant
                    +"&Nom_etudiant="+nom_etudiant
                    +"&Montant_payer="+montant
                    +"&Date_paiement="+date_paiement                
                    +"&Code_promo="+code_promo
                    +"&Type_frais="+type_frais              
                    +"&Tab_motif_paiement="+json_motif_paiement
                    +"&Id_banque=-1"
                    +"&Id_an_acad="+Id_an_acad
                    +"&devise="+devise_fa.trim();

                    let parametres = "left=20,top=20,width=700,height=500";
                
                    let fenetre_recu=window.open(
                        url,
                        "Impression Réçu",
                        parametres
                    );

                    fenetre_recu.onload = function() {
                        //alert("Enregistrment effectuer avec succès");
                        Intialisation_zone_paiement_guichet();
                    };                  
                    
                }
                    
                else 
                {
                    Ouvrir_Boite_Alert_Paiement_Guichet( " Echec d'eregistrement ");
                }
            }
        /*  else
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
                + "&montant_inverse=" + montant_devise_inverse
                + "&devise_paye="+devise_paye
                + "&montant_en_fc="+montant_en_franc
                + "&Taux_dollar="+(montant_taux_base/10));

    }
}
///////////////////////////////////////////////////////////////////////////////////////////





/***************************************************************************************
******************** Cette fonction consiste à faire un paiemnt à la banque ************
****************************************************************************************/
function Paiement_frais_banque()
{
    var type_frais=cmb_type_frais.value;
    if(Verification_avant_paiement(type_frais))
    {
         // URL vers le fichier d'impression d

        var code_promo=cmb_promotion.value;
        var Id_an_acad=cmb_annee_academique.value;
        var mat_etudiant="";

        var devise_fa=document.getElementById("devise_fa");
        var devise=" Fc";
        if(devise_fa.innerText==="$")devise=" $";
        
        // Ici on fait recuperer la date au format date et heure
        // var date_paiement=date_paie.value;

        var date_paiement= new Date().toISOString().substr(0, 10);

        // Ici on rcupère la ate de l'heure, minutes et séconde
        var date_actuelle = new Date();
        var heure = date_actuelle.getHours();
        var minutes = date_actuelle.getMinutes();
        var secondes = date_actuelle.getSeconds();

        // Créer une chaîne au format YYYY-MM-DD HH:mm:ss    
        // Convertir la date en format MySQL datetime (YYYY-MM-DD HH:mm:ss)
        date_paiement = date_paiement+ ' ' + ("0" + heure).slice(-2) + ':' + ("0" + minutes).slice(-2) + ':' + ("0" + secondes).slice(-2);
        


        
        var idbanque=cmb_banque.value;

        var montant=montnt_argent_dollar_fc;
        var numero_bordereau=txt_numero_borderau.value;

        var etat_ems=case_ems.value;
        var etat_es=case_es.value;
        var etat_e2s=case_e2s.value;
        var etat_ESEM1=case_ERSEM1.value;
        var etat_VC=case_VC.value;

        var motif_paiement=[];
        var tab_type_frais=[];


        //$motif_paiement = array();
        if(type_frais=="Enrôlement à la Session")
        {
            //ab_type_frais=
            if(case_ems.checked) motif_paiement.push("Enrôlement à la Mi-Session");
            if(case_es.checked) motif_paiement.push("Enrôlement à la Grande-Session");
            if(case_e2s.checked) motif_paiement.push("Enrôlement à la Deuxième-Session");
            if(case_ERSEM1.checked) motif_paiement.push("Enrôlement aux rattrapages sem 1");
            if(case_VC.checked) motif_paiement.push("Enrôlement validation crédits");

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
            if(case_ERSEM1.checked) motif_paiement.push("Enrôlement aux rattrapages sem 1");
            if(case_VC.checked) motif_paiement.push("Enrôlement validation crédits");
        }
        
        mat_etudiant=document.getElementById("mat_etudiant").value;
        var json_motif_paiement = JSON.stringify(motif_paiement);// Ce Json est très important car il permet d'envoyé les données du Javascript vers PHP
        

        //console.log(" regarde Verifi num"+Verfi_num_borde);
        if(Verfi_num_borde)
        {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "D_Perception/API_PHP/Nouveau_paiement_banque.php", true);
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

                        var url="Impression/Docs_a_imprimer/recu.php"
                        +"?Mat_etudiant="+mat_etudiant
                        +"&Nom_etudiant="+nom_etudiant
                        +"&Montant_payer="+montant
                        +"&Date_paiement="+date_paiement                
                        +"&Code_promo="+code_promo
                        +"&Type_frais="+type_frais              
                        +"&Tab_motif_paiement="+json_motif_paiement
                        +"&Id_banque="+idbanque
                        +"&Id_an_acad="+Id_an_acad
                        +"&devise="+devise;

                        let parametres = "left=20,top=20,width=700,height=500"; // Les dimensions de la fenetres d'impression et la position                
                        let fenetre_recu=window.open(
                            url,
                            "Impression Réçu",
                            parametres
                        );

                        fenetre_recu.onload = function() {
                            //alert("Enregistrment effectuer avec succès");
                            Intialisation_zone_paiement_banque();
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
                    + "&numero_borderau=" + numero_bordereau
                    + "&montant_inverse=" + montant_devise_inverse  
                    + "&devise_paye="+devise_paye
                    + "&montant_en_fc="+montant_en_franc
                    + "&Taux_dollar="+(montant_taux_base/10));
        }
        else Ouvrir_Boite_Alert_Paiement_Banque(" Ce borderau est déjà utilisé !");

    }
   
}




function Intialisation_zone_paiement_guichet()
{
    txt_montant.value="";
    case_e2s.checked=false;
    case_ems.checked=false;
    case_es.checked=false;
    case_ERSEM1.checked=false;
    case_VC.checked=false;

    cmb_type_frais.selectedIndex = 0;



    // appel de la methode pour reafficher les nouvelles infos inserées

    Recuperation_situation_finaniere(
        txt_mat_etudiant.value,
        txt_nom_etudiant.value, 
        txt_postnom_etudiant.value,
        txt_prenom_etudiant.value, 
        txt_sexe_etudiant.value,
        cmb_annee_academique.value,tr_globale_ligne_select_etudiant);
    
    //cmb

    
}


function Intialisation_zone_paiement_banque()
{
    txt_montant.value="";
    txt_numero_borderau.value="";
    case_e2s.checked=false;
    case_ems.checked=false;
    case_es.checked=false;
    case_ERSEM1.checked=false;
    case_VC.checked=false;

    cmb_type_frais.selectedIndex = 0;
    cmb_banque.selectedIndex=0;



    // appel de la methode pour reafficher les nouvelles infos inserées

    Recuperation_situation_finaniere(
        txt_mat_etudiant.value,
        txt_nom_etudiant.value, 
        txt_postnom_etudiant.value,
        txt_prenom_etudiant.value, 
        txt_sexe_etudiant.value,
        cmb_annee_academique.value,tr_globale_ligne_select_etudiant);
    
    //cmb

}
    


/*************************************************************************************
********************    ICI C'EST POUR OUVRIR LA BOITE DE DIALOGUE ********************
***************************************************************************************/



function Ouvrir_Boite_Alert_Paiement_Banque(text_a_afficher)
{
    document.getElementById("text_alert_paiement_banque").innerText=text_a_afficher;
    boite_alert_Paiement_banque.showModal();
}
function Ouvrir_Boite_Alert_Paiement_Guichet(text_a_afficher)
{
    document.getElementById("text_alert_paiement_guichet").innerText=text_a_afficher;
    boite_alert_Paiement_guichet.showModal();
}
// Fermer la boîte de dialogue
function Fermer_Boite_Paiement_Banque() {
  boite_alert_Paiement_banque.close();
}
function Fermer_Boite_Paiement_Guichet() {
    boite_alert_Paiement_guichet.close();
  }