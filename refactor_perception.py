#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Refactor D_Perception JavaScript files to use DOMContentLoaded pattern
"""

import re
from pathlib import Path

def refactor_entree_rapport_paie(filepath):
    """Refactor Entree_rapport_paie.js"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replace the entire section from start of if to end of file
    pattern = r"if\(document\.getElementById\('div_gen_Rapport_paie'\)!==null\)\s*\{.*"
    
    replacement = '''let cmb_lieu_paiement;
let cmb_filieree;
let cmb_id_annee_academique;
let date_debut;
let date_fin;
let btn_radio_devise_rapport;
let btn_radio_devise_percu;

document.addEventListener("DOMContentLoaded", function(event) {
  const container = document.getElementById('div_gen_Rapport_paie');
  if(container !== null)
  {
    cmb_lieu_paiement = container.querySelector('#Id_lieu_paiement') || document.getElementById("Id_lieu_paiement");
    cmb_filieree = container.querySelector('#filieree') || document.getElementById("filieree");
    cmb_id_annee_academique = container.querySelector('#Id_an_acade') || document.getElementById("Id_an_acade");
    date_debut = container.querySelector('#date_debut') || document.getElementById("date_debut");
    date_fin = container.querySelector('#date_fin') || document.getElementById("date_fin");
    btn_radio_devise_rapport = container.querySelector('#dollar_rapport') || document.getElementById("dollar_rapport");
    btn_radio_devise_percu = container.querySelector('#dollar_percu') || document.getElementById("dollar_percu");

    var date_actuelle = new Date();
    var formattedDate = date_actuelle.toISOString().substr(0, 10);

    if(cmb_lieu_paiement !== null)
    {
        cmb_lieu_paiement.addEventListener('change',(event)=> {
            if(cmb_filieree.value!="rien" && cmb_id_annee_academique.value!="rien")Impression_rapport();
        });
    }

    if(cmb_filieree !== null)
    {
        cmb_filieree.addEventListener('change',(event)=> {
            if(cmb_lieu_paiement.value!="rien" && cmb_id_annee_academique.value!="rien") Impression_rapport();
        });
    }

    if(cmb_id_annee_academique !== null)
    {
        cmb_id_annee_academique.addEventListener('change',(event)=> {
            if(cmb_lieu_paiement.value!="rien" && cmb_filieree.value!="rien")Impression_rapport();
        });
    }

    if(date_debut !== null)
    {
        date_debut.value = formattedDate;
        date_debut.addEventListener('change',(event)=> {
            Impression_rapport();
        });
    }

    if(date_fin !== null)
    {
        date_fin.value = formattedDate;
        date_fin.addEventListener('change',(event)=> {
            Impression_rapport();
        });
    }
  }
});

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
        let fenetre_recu=window.open(url, "Impression Réçu", parametres);
                    
        fenetre_recu.onload = function() {};
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
        let fenetre_recu=window.open(url, "Impression Réçu", parametres);
                    
        fenetre_recu.onload = function() {};
    }
}
'''
    
    content = re.sub(pattern, replacement, content, flags=re.DOTALL)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"✓ Refactored: {filepath}")

# Run refactoring
if __name__ == '__main__':
    base_path = Path(r"c:\wamp64\www\webUKA\UKA_Numerique\D_Perception\JavaScript")
    
    # Only do first file for now
    entree_file = base_path / "Entree_rapport_paie.js"
    if entree_file.exists():
        refactor_entree_rapport_paie(str(entree_file))
