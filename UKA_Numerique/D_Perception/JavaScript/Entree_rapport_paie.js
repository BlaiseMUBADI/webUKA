

/*
*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*+++++++++++++++++++ C'est un script qui charge la generation de rapports de paiement +++++++
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*
*/

/*
*********************************************************************************************
* ***************************** Déclaration des composants HTML *****************************
*********************************************************************************************
*/
if(document.getElementById('div_gen_Rapport_paie')!==null)
{
    const cmb_lieu_paiement=document.getElementById("Id_lieu_paiement");
    const cmb_filieree=document.getElementById("filieree");
    const cmb_id_annee_academique=document.getElementById("Id_an_acade");

    const date_debut=document.getElementById("date_debut");
    const date_fin=document.getElementById("date_fin");

    const btn_radio_devise_rapport=document.getElementById("dollar_rapport"); 
    const btn_radio_devise_percu=document.getElementById("dollar_percu"); 


    var date_actuelle = new Date();
    // Obtenir la date au format YYYY-MM-DD
    var formattedDate = date_actuelle.toISOString().substr(0, 10);




    // Ici on attache les evenement

    if(cmb_lieu_paiement!==null)
    {
        cmb_lieu_paiement.addEventListener('change',(event)=> {
            if(cmb_filieree.value!="rien" && cmb_id_annee_academique.value!="rien")Impression_rapport();
        });

    }

    if(cmb_filieree!==null)
    {
        cmb_filieree.addEventListener('change',(event)=> {
            if(cmb_lieu_paiement.value!="rien" && cmb_id_annee_academique.value!="rien") Impression_rapport();
        });

    }

    if(cmb_id_annee_academique!==null)
    {
        cmb_id_annee_academique.addEventListener('change',(event)=> {
            if(cmb_lieu_paiement.value!="rien" && cmb_filieree.value!="rien")Impression_rapport();
        });

    }

    if(date_debut!==null)
    {
        date_debut.value = formattedDate;
        date_debut.addEventListener('change',(event)=> {
            Impression_rapport();
        });

    }

    if(date_fin!==null)
    {
        date_fin.value = formattedDate;
        date_fin.addEventListener('change',(event)=> {
            Impression_rapport();
        });

    }










    // Ici on lance ce script pour selectionner la date du jours dans le composant

    var date_actuelle = new Date();

    // Obtenir la date au format YYYY-MM-DD
    var formattedDate = date_actuelle.toISOString().substr(0, 10);


    // Ici on test si l'élement selectionner est present sur la page html
    if (date_paie !== null) date_paie.value = formattedDate;




    function Impression_rapport()
    {
        var devise="Fc";
        var devise_argent_percu="$";


        var id_annee_acad=cmb_id_annee_academique.value;
        var id_filiere=cmb_filieree.value;
        var date_d=date_debut.value;
        var date_f=date_fin.value;
        var Id_lieu_paiement=cmb_lieu_paiement.value;

        
        if (btn_radio_devise_rapport.checked)devise="$";
        else devise="Fc";

        if (btn_radio_devise_percu.checked)
        {
            devise_argent_percu="$";
            var url="Impression/Docs_a_imprimer/Rapport_argent_en_$.php"
                +"?Id_annee_acad="+id_annee_acad
                +"&Id_filiere="+id_filiere
                +"&Date_debut="+date_d             
                +"&Date_fin="+date_f
                +"&Id_lieu_paiement="+Id_lieu_paiement
                +"&devise="+devise
                +"&devise_percu="+devise_argent_percu;

            let parametres = "left=20,top=20,width=700,height=500";
            let fenetre_recu=window.open(
                            url,
                            "Impression Réçu",
                            parametres
                        );
                        
            fenetre_recu.onload = function() {
                            //alert("Enregistrment effectuer avec succès");
                            //Intialisation_zone_paiement_guichet();
                        };
        }

        else
        {
            
            devise_argent_percu="Fc";
            var url="Impression/Docs_a_imprimer/Rapport_argent_en_Fc.php"
                +"?Id_annee_acad="+id_annee_acad
                +"&Id_filiere="+id_filiere
                +"&Date_debut="+date_d             
                +"&Date_fin="+date_f
                +"&Id_lieu_paiement="+Id_lieu_paiement
                +"&devise="+devise
                +"&devise_percu="+devise_argent_percu;

            let parametres = "left=20,top=20,width=700,height=500";
            let fenetre_recu=window.open(
                            url,
                            "Impression Réçu",
                            parametres
                        );
                        
            fenetre_recu.onload = function() {
                            //alert("Enregistrment effectuer avec succès");
                            //Intialisation_zone_paiement_guichet();
                        };

        } 

        //console.log("je suis dans Entree rapport regarde devise "+devise);
    }  
    
    
                
                
}
    