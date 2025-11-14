<?php

include("../../../Connexion_BDD/Connexion_1.php");

header('Content-Type: application/json');

$chaine="";
            
$id="";

// Récupérer les données via POST et vérifier si elles existent
$matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$postnom = isset($_POST['postnom']) ? $_POST['postnom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';
$LieuNaissanceAgent = isset($_POST['LieuNaissanceAgent']) ? $_POST['LieuNaissanceAgent'] : '';
$DateNaissanceAgent = isset($_POST['DateNaissanceAgent']) ? $_POST['DateNaissanceAgent'] : '';
$EtatCivil = isset($_POST['EtatCivil']) ? $_POST['EtatCivil'] : '';
$NomConjoint = isset($_POST['NomConjoint']) ? $_POST['NomConjoint'] : '';
$DateNaisConjoint = isset($_POST['DateNaisConjoint']) ? $_POST['DateNaisConjoint'] : '';
$LieuNaisConjoint = isset($_POST['LieuNaisConjoint']) ? $_POST['LieuNaisConjoint'] : '';
$grade = isset($_POST['Grade']) ? $_POST['Grade'] : '';
$idCategorie = isset($_POST['IdCategorie']) ? $_POST['IdCategorie'] : '';
$idservice = isset($_POST['IdService']) ? $_POST['IdService'] : '';
$fonction = isset($_POST['Fonction']) ? $_POST['Fonction'] : '';
$dateaff = isset($_POST['dateaffectation']) ? $_POST['dateaffectation'] : '';
$adresse = isset($_POST['AdressePhysique']) ? $_POST['AdressePhysique'] : '';
$mail = isset($_POST['Mail']) ? $_POST['Mail'] : '';
$tel = isset($_POST['Telephone']) ? $_POST['Telephone'] : '';

$dateEngage = isset($_POST['DateEngagement']) ? $_POST['DateEngagement'] : '';
$niveau = isset($_POST['NiveauEtude']) ? $_POST['NiveauEtude'] : '';
$anneeobt = isset($_POST['AnneeObt']) ? $_POST['AnneeObt'] : '';
$institution = isset($_POST['Insitution']) ? $_POST['Insitution'] : '';
$domaine = isset($_POST['Domaine']) ? $_POST['Domaine'] : '';

$statut = "Encours";

if (empty($matricule)) 
    { 
        echo json_encode(['success' => false, 'message' => 'La champ Matricule ne peut pas être vide.']);
        exit;
    }

if (empty($nom)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ Nom ne peut pas être vide.']);
        exit;
    }
if (empty($postnom)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ Postnom ne peut pas être vide.']);
        exit;
    }
if (empty($LieuNaissanceAgent)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ Lieu de naissance ne peut pas être vide.']);
        exit;
    }
if (empty($idCategorie)) 
    { 
        echo json_encode(['success' => false, 'message' => 'La champ Catégorie ne peut pas être vide.']);
        exit;
    }
if (empty($fonction)) 
    { 
        echo json_encode(['success' => false, 'message' => 'Le champ Fonction ne peut pas être vide.']);
        exit;
    }
   try {
        ob_start(); // Démarrer la capture du buffer de sortie
        $con->beginTransaction();
    
        // Vérifier le matricule et le générer si nécessaire
        if ($matricule == "NU") {
            $sql_select_acces = "SELECT COUNT(*) AS total FROM agent WHERE Mat_agent LIKE '%NU%'";
            $stm = $con->prepare($sql_select_acces);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $total = $result['total'];
            $matricule = "NU" . ($total + 1);
        } else {
            $matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
        }
        
        // Insertion dans la table Agent
        $type=1;
        if (!empty($matricule) && !empty($nom) && !empty($postnom)) {
            $sql_insert = "INSERT INTO agent (Mat_agent, Nom_agent, Post_agent, Prenom, Sexe, 
                                                Lieu, DateNaissance, Grade, EtatCivil, 
                                                IdCategorie, AdressePhysique, Mail, 
                                                Tel,Date_Engagement,Niveau_Etude,Annee_Obt,Institution,Domaine,type_agent) 
                          VALUES (:matricule, :nom, :postnom, :prenom, :sexe, 
                                    :lieu, :datenais, :grade, :etat, 
                                    :Idcategorie, :Adr, :mail, :tel,:dateEng,:niveau,
                                    :anneobt,:institution,:domaine,:type_agent)";
            $stmt = $con->prepare($sql_insert);
            $stmt->bindParam(':matricule', $matricule);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':postnom', $postnom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':sexe', $sexe);
            $stmt->bindParam(':lieu', $LieuNaissanceAgent);
            $stmt->bindParam(':datenais', $DateNaissanceAgent);
            $stmt->bindParam(':grade', $grade);
            $stmt->bindParam(':etat', $EtatCivil);
            $stmt->bindParam(':Idcategorie', $idCategorie);
            $stmt->bindParam(':Adr', $adresse);
            $stmt->bindParam(':mail', $mail);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':dateEng', $dateEngage);
            $stmt->bindParam(':niveau', $niveau);
            $stmt->bindParam(':anneobt', $anneeobt);
            $stmt->bindParam(':institution', $institution);
            $stmt->bindParam(':domaine', $domaine);
            $stmt->bindParam(':type_agent', $type);
            $stmt->execute();
        }
    
        // Insertion dans la table Conjoint
        if (!empty($NomConjoint) && !empty($LieuNaisConjoint) && !empty($DateNaisConjoint)) {
            $sql_insert = "INSERT INTO conjoint (MatriculeAgent, Noms, LieuNais, DateNaissance) 
                          VALUES (:matricule, :noms, :lieuCong, :datenais)";
            $stmt1 = $con->prepare($sql_insert);
            $stmt1->bindParam(':matricule', $matricule);
            $stmt1->bindParam(':noms', $NomConjoint);
            $stmt1->bindParam(':lieuCong', $LieuNaisConjoint);
            $stmt1->bindParam(':datenais', $DateNaisConjoint);
            $stmt1->execute();
        }




//echo json_encode(['success' => true, 'message' => ' Je suis dans.']);

    
        // Insertion dans la table Affecter
        if (!empty($idservice)) 
        {

            $sql_insert="";
            $tab_prefix_nombre = extraireTexteEtNombre($idservice); // Nous 

            $chaine=$tab_prefix_nombre['texte'];
            $id=$tab_prefix_nombre['nombre'];



            if($chaine=="fac")
            {
                $sql_insert = "INSERT INTO affecter (Fonction, DateAffectation, Statut, Matricule_Agent, Id_filiere) 
                          VALUES (:fonction, :dateaff, :statut, :matricule, :id)";


            }
            else
            {
                $sql_insert = "INSERT INTO affecter (Fonction, DateAffectation, Statut, Matricule_Agent, IdService) 
                          VALUES (:fonction, :dateaff, :statut, :matricule, :id)";

            }
            $stmt3 = $con->prepare($sql_insert);
            $stmt3->bindParam(':matricule', $matricule);
            $stmt3->bindParam(':fonction', $fonction);
            $stmt3->bindParam(':dateaff', $dateaff);
            $stmt3->bindParam(':statut', $statut);
            $stmt3->bindParam(':id', $id);
            $stmt3->execute();
        }
    
        


        // Insertion dans la table Enfant
        $nombreEnfant = isset($_POST['NombreEnfant']) ? intval($_POST['NombreEnfant']) : 0;
        for ($i = 1; $i <= $nombreEnfant; $i++) {
            $nomEnfant = isset($_POST['NomEnfant' . $i]) ? $_POST['NomEnfant' . $i] : '';
            $lieuNaissanceEnfant = isset($_POST['LieuNaisEnfant' . $i]) ? $_POST['LieuNaisEnfant' . $i] : '';
            $dateNaissanceEnfant = isset($_POST['DateNaissanceEnfant' . $i]) ? $_POST['DateNaissanceEnfant' . $i] : '';
            if (!empty($nomEnfant) && !empty($dateNaissanceEnfant) && !empty($lieuNaissanceEnfant)) {
                $sql_insert = "INSERT INTO enfant (Noms, Lieu_Naissance, DateNaissance, Mat_Agent) 
                              VALUES (:nom, :lieuNaissance, :dateNaissance, :mat_agent)";
                $stmt2 = $con->prepare($sql_insert);
                $stmt2->bindParam(':mat_agent', $matricule);
                $stmt2->bindParam(':nom', $nomEnfant);
                $stmt2->bindParam(':lieuNaissance', $lieuNaissanceEnfant);
                $stmt2->bindParam(':dateNaissance', $dateNaissanceEnfant);
                $stmt2->execute();
            }
        }
    
        // Committer la transaction si toutes les insertions ont réussi
        $con->commit();
        ob_clean(); // Vider les buffers de sortie
        echo json_encode(['success' => true, 'message' => 'Enregistrement réussi. '.$id. " pref ".$chaine]);
    } catch (PDOException $e) {
        $con->rollBack();
        ob_clean(); // Vider les buffers de sortie
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    } catch (Exception $e) {
        $con->rollBack();
        ob_clean(); // Vider les buffers de sortie
        echo json_encode(['success' => false, 'message' => 'Erreur inattendue : ' . $e->getMessage()]);
    }

    // Fonction pour extraire la partie texte et la partie numérique
function extraireTexteEtNombre($chaine) {
    preg_match('/([a-zA-Z]+)\s*(\d+)/', $chaine, $matches);
    return array('texte' => $matches[1], 'nombre' => $matches[2]);
}
    
?>
