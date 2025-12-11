<?php
include('../../D_Generale/session_check.php');
require_once("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion PDO
if (!isset($con) || !($con instanceof PDO)) {
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données"]);
    exit;
}

$mat_etudaint=$_GET['matricule'];
$id_annee_acad=$_GET['id_annee_acad'];
$type_frais=$_GET['type_frais'];
//echo " je suis dans min fichier";

//$sql_select_acces="select Code_Promotion from promotion where idMentions=2";/*
$sql_select_acces="select ROUND(SUM(payer_frais.Montant_paie),2) as somme_paier,
                frais.Devise as Devise
                from etudiant,payer_frais,annee_academique,frais
                where etudiant.Matricule=payer_frais.Matricule 
                and payer_frais.idFrais=frais.idFrais 
                and frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
                and annee_academique.idAnnee_Acad=:id_annee_acad
                and etudiant.Matricule=:mat_etudiant
                and payer_frais.Motif_paie=:motif_paie
                GROUP BY frais.Devise"; 
    
    $stmt=$con->prepare($sql_select_acces);
    $stmt->bindParam(':id_annee_acad',$id_annee_acad);
    $stmt->bindParam(':mat_etudiant',$mat_etudaint);
    $stmt->bindParam(':motif_paie',$type_frais);
    $stmt->execute();
    
    
    $etudiant=array();
    while($ligne = $stmt->fetch())
    {
        $etudiant[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
    echo json_encode($etudiant);
    //echo $etudiant;
        

    


?>

