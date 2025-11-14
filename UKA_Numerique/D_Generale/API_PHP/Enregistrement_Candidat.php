<?php
session_start(); 



include("../../../Connexion_BDD/Connexion_1.php");

$matricule=$_GET['matricule'];
$nom=$_GET['nom'];
$postnom=$_GET['postnom'];
$prenom=$_GET['prenom'];
$sexe=$_GET['sexe'];
$idfrais=$_GET['idfrais'];
$datepaie=$_GET['datepaie'];
$promo_filiere=$_GET['promo_filiere'];
$annee_acad=$_GET['annee_acad'];
$montantpaie=$_GET['montantpaie'];
$lieu_paie="3";
$motif="Frais d'inscription";

$mat_agent=$_SESSION['MatriculeAgent'] ;


   //echo $idfrais;
   //echo $datepaie;
   //echo $promo_filiere;
   //echo $annee_acad;
   try {
        $con1->beginTransaction();
        // Insertion dans la table etudiant
        $sql_insert_paiement = "INSERT INTO  etudiant (Matricule,Nom,Postnom,Prenom,
                                Sexe) VALUES (:matricule,:nom,:postnom,:prenom,:sexe)";
        $stmt = $con1->prepare($sql_insert_paiement);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':postnom', $postnom);
        $stmt->bindParam(':prenom',$prenom);
        $stmt->bindParam(':sexe',$sexe);
        $stmt->execute();  

        // Insertion dans la table payer_frais
        $sql_insert_paiement = "INSERT INTO payer_frais (Matricule,idFrais,Date_paie,idLieu_paiement,
                                Mat_agent,Montant_paie,Motif_paie) VALUES (:matricule,:idfrais,:datepaie,:lieu_paie,:mat_agent
                                ,:montantpaie,:motif)";
        $stmt1 = $con1->prepare($sql_insert_paiement);
        $stmt1->bindParam(':matricule', $matricule);
        $stmt1->bindParam(':idfrais', $idfrais);
        $stmt1->bindParam(':datepaie', $datepaie);
        $stmt1->bindParam(':lieu_paie',$lieu_paie);
        $stmt1->bindParam(':mat_agent',$mat_agent);
        $stmt1->bindParam(':montantpaie', $montantpaie);
        $stmt1->bindParam(':motif', $motif);
        $stmt1->execute(); 

        // Insertion dans la table autreinfo_etudiant
        $sql_insert_paiement = "INSERT INTO autreinfo_etudiant (Matricule) VALUES (:matricule)";        
        $stmt2 = $con1->prepare($sql_insert_paiement);
        $stmt2->bindParam(':matricule', $matricule);
        $stmt2->execute(); 

            // Insertion dans la table passer_par
        $Session1="0";
        $Session2="0";
        $sql_insert_paiement = "INSERT INTO passer_par (Etudiant_Matricule,Code_Promotion,idAnnee_academique,Session1,Session2) 
                           VALUES (:matricule,:promo_filiere,:annee_acad,:Session1,:Session2)";
        $stmt3 = $con1->prepare($sql_insert_paiement);
        $stmt3->bindParam(':matricule', $matricule);
        $stmt3->bindParam(':promo_filiere', $promo_filiere);
        $stmt3->bindParam(':annee_acad', $annee_acad);
        $stmt3->bindParam(':Session1', $Session1);
        $stmt3->bindParam(':Session2', $Session2);
        $stmt3->execute(); 
        // Si toutes les insertions ont réussi
        $con1->commit();
        echo json_encode(['success' => true, 'message' => 'Enregistrement réussi.']);
        } catch (PDOException $e) {
        // En cas d'erreur, renvoyer un message d'erreur
        $con1->rollBack();
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    ?>