<?php
include("../../../Connexion_BDD/Connexion_1.php");

$mat_etudaint=$_GET['matricule'];
$id_annee_acad=$_GET['id_annee_acad'];
$code_promo=$_GET['code_promo'];
//echo " je suis dans min fichier";

// Cette recupère toutes les transactions qui ne provienne pas de deux operations comme FA + Enrolement
$sql_select_acces="
select 
    payer_frais.Date_paie as date_paie,
    payer_frais.Motif_paie as motif, 
    payer_frais.Montant_paie as montant_paie,
    lieu_paiement.idLieu_paiement as Id_lieu,
    lieu_paiement.Libelle_lieu as Libelle_lieu,
    CONCAT(agent.Nom_agent,' ',agent.Post_agent) as nom_agent,
    frais.Devise as devise

    from etudiant,payer_frais,annee_academique,frais,promotion,lieu_paiement,agent

    WHERE etudiant.Matricule=payer_frais.Matricule 
    and promotion.Code_Promotion=frais.Code_Promotion 
    and payer_frais.idFrais=frais.idFrais 
    and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement
    and payer_frais.Mat_agent=agent.Mat_agent
    and frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
    and annee_academique.idAnnee_Acad=:id_annee_acad
    and promotion.Code_Promotion=:code_promo
    and etudiant.Matricule=:mat_etudiant
    and payer_frais.Ensemble is null"; 

    /*
    select 
    payer_frais.Date_paie as date_paie,
    GROUP_CONCAT(payer_frais.Motif_paie) as motif, 
    SUM(payer_frais.Montant_paie) as montant_paie,
    lieu_paiement.idLieu_paiement as Id_lieu,
    lieu_paiement.Libelle_lieu as Libelle_lieu,
    CONCAT(agent.Nom_agent,' ',agent.Post_agent) as nom_agent

    from etudiant,payer_frais,annee_academique,frais,promotion,lieu_paiement,agent

    WHERE etudiant.Matricule=payer_frais.Matricule 
    and promotion.Code_Promotion=frais.Code_Promotion 
    and payer_frais.idFrais=frais.idFrais 
    and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement
    and payer_frais.Mat_agent=agent.Mat_agent
    and frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
    and annee_academique.idAnnee_Acad=:id_annee_acad
    and promotion.Code_Promotion=:code_promo
    and etudiant.Matricule=:mat_etudiant
    GROUP by DATE(payer_frais.Date_paie)"; 
    */
    
    $stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':id_annee_acad',$id_annee_acad);
    $stmt->bindParam(':mat_etudiant',$mat_etudaint);
    $stmt->bindParam(':code_promo',$code_promo);
    $stmt->execute();
    
    
    $etudiant=array();
    while($ligne = $stmt->fetch())
    {
        $etudiant[]=$ligne;

    }

    // ************* Ici nous recuperons que les operations resultantes de FA+Enrolement
    $sql_select_acces="
select 
    payer_frais.Date_paie as date_paie,
    GROUP_CONCAT(payer_frais.Motif_paie) as motif, 
    ROUND(SUM(payer_frais.Montant_paie),2) as montant_paie,
    lieu_paiement.idLieu_paiement as Id_lieu,
    lieu_paiement.Libelle_lieu as Libelle_lieu,
    CONCAT(agent.Nom_agent,' ',agent.Post_agent) as nom_agent,
    frais.Devise as devise

    from etudiant,payer_frais,annee_academique,frais,promotion,lieu_paiement,agent

    WHERE etudiant.Matricule=payer_frais.Matricule 
    and promotion.Code_Promotion=frais.Code_Promotion 
    and payer_frais.idFrais=frais.idFrais 
    and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement
    and payer_frais.Mat_agent=agent.Mat_agent
    and frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
    and annee_academique.idAnnee_Acad=:id_annee_acad
    and promotion.Code_Promotion=:code_promo
    and etudiant.Matricule=:mat_etudiant
    and payer_frais.Ensemble is not null
    GROUP by payer_frais.Date_paie,lieu_paiement.idLieu_paiement,lieu_paiement.Libelle_lieu,
    agent.Nom_agent,agent.Post_agent,frais.Devise";//, HOUR(payer_frais.Date_paie), MINUTE(payer_frais.Date_paie)"; 

    
    $stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':id_annee_acad',$id_annee_acad);
    $stmt->bindParam(':mat_etudiant',$mat_etudaint);
    $stmt->bindParam(':code_promo',$code_promo);
    $stmt->execute();
    
    
    
    while($ligne = $stmt->fetch())
    {
        $etudiant[]=$ligne;

    }







    //Renvoyer les resultats sous forme de json
    echo json_encode($etudiant);
    //echo $etudiant;
        

    


?>

