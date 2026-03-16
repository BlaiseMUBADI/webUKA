<?php
include("../../../Connexion_BDD/Connexion_1.php");

$mat_etudiant=$_GET['matricule'];
$Id_an_acad=$_GET['id_annee_acad'];
$code_promo=$_GET['code_promo'];;



$tab_rest[]=array();

$reste_EMS="";
$reste_FA="";
$reste_EMS="";
$reste_ES="";
$reste_E2S="";

/*
     ICI NOUS INTEROGONS LA BASE POUR SAVOIR LE RESTE A FA
*/
$motif="Frais Académiques";
 
 $frais_fixer=0;
 $sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
     FROM frais
     WHERE frais.idAnnee_Acad=:id_annee
     and frais.Code_Promotion=:code_prom
     and frais.Libelle_Frais=:motif";
 $stmt=$con->prepare($sql_f_fixer); 
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':code_prom',$code_promo);
 $stmt->bindParam(':motif',$motif);
 $stmt->execute();
 $data = $stmt->fetch();
 $frais_fixer=$data['montant_fixer'];
 //echo " regarde FA ".$frais_fixer;
 
 
 $sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
 FROM annee_academique,frais,payer_frais,etudiant
 WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
 and frais.idFrais=payer_frais.idFrais
 and payer_frais.Matricule=etudiant.Matricule
 and annee_academique.idAnnee_Acad=:id_annee
 and etudiant.Matricule=:mat_etudiant
 and payer_frais.Motif_paie=:motif_paiement";
 
 $stmt=$con->prepare($sql_FA);    
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':mat_etudiant',$mat_etudiant);
 $stmt->bindParam(':motif_paiement',$motif);
 $stmt->execute();
 $data = $stmt->fetch();
 $reste_FA=$frais_fixer-$data['reste_FA'];
 //////////////////////////////////////////////////////////////////////////////////
 
 
 
 /*
 ******************************** ICI NOUS CHERCHONS LE REST POUR CHAQUE FRAIS D'ENROLEMENT
 */
 
 
 // Enrolement à la Mi-session ou 1 primers semestre d'abord 
 $motif="Enrôlement à la Mi-Session";
 $motif1="Enrôlement à la Session";
 
 $frais_fixer_enrol=0;
 $sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
     FROM frais
     WHERE frais.idAnnee_Acad=:id_annee
     and frais.Code_Promotion=:code_prom
     and frais.Libelle_Frais=:motif";
 $stmt=$con->prepare($sql_f_fixer); 
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':code_prom',$code_promo);
 $stmt->bindParam(':motif',$motif1);
 $stmt->execute();
 $data = $stmt->fetch(); $frais_fixer_enrol=$data['montant_fixer'];
 //echo " regarde Enrole ".$frais_fixer_enrol;
 //$data = $stmt->fetch();

 
 
 $sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
 FROM annee_academique,frais,payer_frais,etudiant
 WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
 and frais.idFrais=payer_frais.idFrais
 and payer_frais.Matricule=etudiant.Matricule
 and annee_academique.idAnnee_Acad=:id_annee
 and etudiant.Matricule=:mat_etudiant
 and payer_frais.Motif_paie=:motif_paiement";
 
 $stmt=$con->prepare($sql_FA);    
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':mat_etudiant',$mat_etudiant);
 $stmt->bindParam(':motif_paiement',$motif);
 $stmt->execute();
 $data = $stmt->fetch();
 $reste_EMS=$frais_fixer_enrol-$data['reste_FA'];
 /********************************************************************************* */
 
 
 
 // Enrolement à la grande Session ou 2 eme semestre
 $motif="Enrôlement à la Grande-Session";
 $motif1="Enrôlement à la Session";
 
 $frais_fixer_enrol_1=0;
 $sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
     FROM frais
     WHERE frais.idAnnee_Acad=:id_annee
     and frais.Code_Promotion=:code_prom
     and frais.Libelle_Frais=:motif";
 $stmt=$con->prepare($sql_f_fixer); 
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':code_prom',$code_promo);
 $stmt->bindParam(':motif',$motif1);
 $stmt->execute();
 $data = $stmt->fetch(); $frais_fixer_enrol_1=$data['montant_fixer'];
 //echo " Je suis à 124 ".$frais_fixer_enrol_1;
 
 
 
 $sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
 FROM annee_academique,frais,payer_frais,etudiant
 WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
 and frais.idFrais=payer_frais.idFrais
 and payer_frais.Matricule=etudiant.Matricule
 and annee_academique.idAnnee_Acad=:id_annee
 and etudiant.Matricule=:mat_etudiant
 and payer_frais.Motif_paie=:motif_paiement";
 
 $stmt=$con->prepare($sql_FA);    
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':mat_etudiant',$mat_etudiant);
 $stmt->bindParam(':motif_paiement',$motif);
 $stmt->execute();
 $data = $stmt->fetch();
 $reste_ES=$frais_fixer_enrol_1-$data['reste_FA'];
 /********************************************************************************* */
 
 
 
 // Enrolement à la deuxième session Session ou rattrapage
 $motif="Enrôlement à la Deuxième-Session";
 $motif1="Enrôlement à la Session";
 
 $frais_fixer_enrol_2=0;
 $sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
     FROM frais
     WHERE frais.idAnnee_Acad=:id_annee
     and frais.Code_Promotion=:code_prom
     and frais.Libelle_Frais=:motif";
 $stmt=$con->prepare($sql_f_fixer); 
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':code_prom',$code_promo);
 $stmt->bindParam(':motif',$motif1);
 $stmt->execute();

 $data = $stmt->fetch(); $frais_fixer_enrol_2=$data['montant_fixer'];
 
 
 $sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
 FROM annee_academique,frais,payer_frais,etudiant
 WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
 and frais.idFrais=payer_frais.idFrais
 and payer_frais.Matricule=etudiant.Matricule
 and annee_academique.idAnnee_Acad=:id_annee
 and etudiant.Matricule=:mat_etudiant
 and payer_frais.Motif_paie=:motif_paiement";
 
 $stmt=$con->prepare($sql_FA);    
 $stmt->bindParam(':id_annee',$Id_an_acad);
 $stmt->bindParam(':mat_etudiant',$mat_etudiant);
 $stmt->bindParam(':motif_paiement',$motif);
 $stmt->execute();
 $data = $stmt->fetch();
 $reste_E2S=$frais_fixer_enrol_2-$data['reste_FA'];

 /********************************************************************************* */



$tab_rest[]=$reste_FA;
$tab_rest[]=$reste_EMS;
$tab_rest[]=$reste_ES;
$tab_rest[]=$reste_E2S;


//Renvoyer les resultats sous forme de JSOn
header('Content-Type: application/json');
echo json_encode($tab_rest);

        

    


?>

