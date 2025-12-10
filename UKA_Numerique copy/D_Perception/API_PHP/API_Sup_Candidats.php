<?php
session_start(); 
include("../../../Connexion_BDD/Connexion_1.php");


//recupération des variables qui viennent avec les données
$matricule = $_GET['MatCandidat'];
//echo "matricule candidat ".$MatriculeCandidats;

try {
    $con1->beginTransaction();
  // Supprimer dans la table payer_frais
        $sql_sup_paiement = "DELETE  FROM payer_frais WHERE Matricule=:Matricule";
        $stmt1=$con1->prepare($sql_sup_paiement);
        $stmt1->bindParam(':Matricule', $matricule);
        $stmt1->execute(); 

    // Supprimer dans la table autreinfo_etudiant
  
        $sql_sup_autreinfo = "DELETE  FROM autreinfo_etudiant WHERE Matricule=:Matricule";
        $stmt2=$con1->prepare($sql_sup_autreinfo);
        $stmt2->bindParam(':Matricule', $matricule);
        $stmt2->execute(); 

        // Supprimer dans la table passer_par

        $sql_sup_passerpar = "DELETE  FROM passer_par WHERE Etudiant_Matricule=:Matricule";
        $stmt3=$con1->prepare($sql_sup_passerpar);
        $stmt3->bindParam(':Matricule', $matricule);
        $stmt3->execute(); 
        // Supprimer dans la table etudiant
        $sql_sup_etudiant = "DELETE  FROM etudiant WHERE Matricule=:Matricule";
        $stmt=$con1->prepare($sql_sup_etudiant);
        $stmt->bindParam(':Matricule', $matricule);
        $stmt->execute();  

    // Si toutes les insertions ont réussi
    $con1->commit();
    echo json_encode(['success' => true, 'message' => 'Suppresion Reussie.']);
    } catch (PDOException $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    $con1->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
?>