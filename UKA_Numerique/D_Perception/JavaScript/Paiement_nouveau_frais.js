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
// DOM nodes will be initialized on DOMContentLoaded when present on page
let txt_montant;
let txt_monte_caractere;
let txt_numero_borderau;

let txt_tau_jours;

let cmb_type_frais;
let cmb_banque;

let case_ems;
let case_es;
let case_e2s;
let case_ERSEM1;
let case_VC;

let btn_radio_devise; // c'est une constante qui garde l'etat de devise

let symbole_devise;

let date_paie;

let boite_alert_Paiement_banque;
let boite_alert_Paiement_guichet;
var devise_paye="Franc Congolais";
var montant_en_franc=0;

var montant_taux_base=5; // Le taux dans la base de données
const div_montant_payer_fc=document.getElementById("montant_payer_fc");
var montnt_argent_dollar_fc=0;
var montant_devise_inverse="0"; // c'un montant que l'on met dans la base pour l'impression de de rapport en deux etats


var Verfi_num_borde=true; // C'est une variable globale qui nous permet de stocker la verification de numéro de bordereau

// DOM initialization for elements and event attachments
document.addEventListener("DOMContentLoaded", function(event) {
    const container = document.getElementById('div_gen_Paiement') || document;

    txt_montant = container.querySelector('#txt_montant_payer') || document.getElementById('txt_montant_payer');
    txt_monte_caractere = container.querySelector('#txt_monte_caractere') || document.getElementById('txt_monte_caractere');
    txt_numero_borderau = container.querySelector('#txt_numero_borderau') || document.getElementById('txt_numero_borderau');

    txt_tau_jours = container.querySelector('#txt_tau_jours') || document.getElementById('txt_tau_jours');

    cmb_type_frais = container.querySelector('#Select_type_frais') || document.getElementById('Select_type_frais');
    cmb_banque = container.querySelector('#Select_banque') || document.getElementById('Select_banque');

    case_ems = container.querySelector('#case_ems') || document.getElementById('case_ems');
    case_es = container.querySelector('#case_es') || document.getElementById('case_es');
    case_e2s = container.querySelector('#case_e2s') || document.getElementById('case_e2s');
    case_ERSEM1 = container.querySelector('#case_ERSEM1') || document.getElementById('case_ERSEM1');
    case_VC = container.querySelector('#case_VC') || document.getElementById('case_VC');

    btn_radio_devise = container.querySelector('#dollar_payer') || document.getElementById('dollar_payer');
    symbole_devise = container.querySelector('#symbole_devise') || document.getElementById('symbole_devise');

    date_paie = container.querySelector('#date_paiement') || document.getElementById('date_paiement');

    // date initialization
    var date_actuelle = new Date();
    var formattedDate = date_actuelle.toISOString().substr(0, 10);
    if (date_paie !== null) date_paie.value = formattedDate;

    // boite dialogs
    // const boite_form_UEs = document.getElementById('boite_Form_UE');
    boite_alert_Paiement_banque = container.querySelector('#boite_alert_paiement_banque') || document.getElementById('boite_alert_paiement_banque');
    boite_alert_Paiement_guichet = container.querySelector('#boite_alert_paiement_guichet') || document.getElementById('boite_alert_paiement_guichet');

    // Attacher l'évenement à la zone de texte qui concerne le numéro de borderau
    if(txt_numero_borderau!==null)
    {
        txt_numero_borderau.addEventListener("keyup", function(event)
        {
            var txt_bordereau=txt_numero_borderau.value;
            Verification_Num_bordereau(txt_bordereau);       
        });

    }

    // Contrôles montant
    if(txt_montant!==null)
    {
        txt_montant.addEventListener("keyup", function(event)
        {
            var devis="";
            var devise_fa=document.getElementById("devise_fa").innerHTML;
            
            // ici verifie pour faire une conversion  lorsqu'il s'agit d'un paiement en dollar
            if(devise_fa===" $")
            {
                
                if (btn_radio_devise.checked) 
                {
                    montnt_argent_dollar_fc=txt_montant.value;
                    devise_paye="Dollar";
                    devis=".$.";

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
            else
            {
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

    // récupération du taux
    if(txt_tau_jours!==null)
    {
      const url='API_PHP/Recup_taux_base.php';
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

}); // FIN DOMContentLoaded



/*************************************************************************************
 * *************** CODE DUPLIQUÉ SUPPRIMÉ - TOUT EST DANS DOMContentLoaded **********
 *************************************************************************************/

/***********************************************************************************************************
 ************** ICI , CETTE FONCTION NOUS PERMET DE FAIRE LA VERIFICATION DE NUMERO DE BBORDEREAU **********
 ************************************************************************************************************
*/

function Verification_Num_bordereau(Num_bordereau)
{
    
    // Contacte de l'API PHP
    const url='API_PHP/Verification_num_bordereau.php?num_bordereau='+Num_bordereau;
          
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
        xhr.open("POST", "API_PHP/Nouveau_paiement_guichet.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState === 4 && xhr.status === 200)
            {
                console.log(xhr.responseText)
                // Réponse du serveur - Parser la réponse JSON
                try {
                    var response = JSON.parse(xhr.responseText);
                    
                    if(response.success && response.numeros_recus && response.numeros_recus.length > 0) 
                    {
                        // Utiliser le premier numéro de reçu (ou le dernier si ensemble FA+Enrol)
                        var numero_recu = response.numeros_recus[response.numeros_recus.length - 1];
                        
                        let nom_etudiant=document.getElementById("nom_etudiant_1").value+" "+
                        document.getElementById("postnom_etudiant").value+" "+
                        document.getElementById("prenom_etudiant").value;

                        //t mat_etuiant=document.getElementById("mat_etudiant").value;

                        var url="../Impression/Docs_a_imprimer/recu.php"
                        +"?Mat_etudiant="+mat_etudiant
                        +"&Nom_etudiant="+nom_etudiant
                        +"&Montant_payer="+montant
                        +"&Date_paiement="+date_paiement                
                        +"&Code_promo="+code_promo
                        +"&Type_frais="+type_frais              
                        +"&Tab_motif_paiement="+json_motif_paiement
                        +"&Id_banque=-1"
                        +"&Id_an_acad="+Id_an_acad
                        +"&devise="+devise_fa.trim()
                        +"&numero_recu="+numero_recu;

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
                        Ouvrir_Boite_Alert_Paiement_Guichet("Erreur: " + (response.error || "Impossible de générer le reçu"));
                    }
                } catch(e) {
                    console.error("Erreur parsing JSON:", e);
                    Ouvrir_Boite_Alert_Paiement_Guichet("Erreur lors du traitement de la réponse");
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
            xhr.open("POST", "API_PHP/Nouveau_paiement_banque.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() 
            {
                if (xhr.readyState === 4 && xhr.status === 200)
                {
                    console.log(xhr.responseText)
                    // Réponse du serveur - Parser la réponse JSON
                    try {
                        var response = JSON.parse(xhr.responseText);
                        
                        if(response.success && response.numeros_recus && response.numeros_recus.length > 0) 
                        {
                            // Utiliser le dernier numéro de reçu
                            var numero_recu = response.numeros_recus[response.numeros_recus.length - 1];
                            
                            let nom_etudiant=document.getElementById("nom_etudiant_1").value+" "+
                            document.getElementById("postnom_etudiant").value+" "+
                            document.getElementById("prenom_etudiant").value;

                            //t mat_etuiant=document.getElementById("mat_etudiant").value;

                            var url="../Impression/Docs_a_imprimer/recu.php"
                            +"?Mat_etudiant="+mat_etudiant
                            +"&Nom_etudiant="+nom_etudiant
                            +"&Montant_payer="+montant
                            +"&Date_paiement="+date_paiement                
                            +"&Code_promo="+code_promo
                            +"&Type_frais="+type_frais              
                            +"&Tab_motif_paiement="+json_motif_paiement
                            +"&Id_banque="+idbanque
                            +"&Id_an_acad="+Id_an_acad
                            +"&devise="+devise
                            +"&numero_recu="+numero_recu;

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
                            alert("Erreur: " + (response.error || "Impossible de générer le reçu"));
                        }
                    } catch(e) {
                        console.error("Erreur parsing JSON:", e);
                        alert("Erreur lors du traitement de la réponse");
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