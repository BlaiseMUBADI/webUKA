<?php


session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$chaine = "";
$id = "";


$data = json_decode(file_get_contents('php://input'), true);
// Récupérer les données via POST et vérifier si elles existent
$matricule = isset($data['mat_enseignant']) ? $data['mat_enseignant'] : null;
$nom = isset($data['nom_enseignant']) ? $data['nom_enseignant'] : null;
$postnom = isset($data['post_enseignant']) ? $data['post_enseignant'] : null;
$prenom = isset($data['prenom_enseignant']) ? $data['prenom_enseignant'] : null;
$sexe = isset($data['sexe_enseignant']) ? $data['sexe_enseignant'] : null;

$EtatCivil = isset($data['EtatCivil']) ? $data['EtatCivil'] : null;
$NomConjoint = isset($data['NomConjoint']) ? $data['NomConjoint'] : null;

$grade = isset($data['Grade']) ? $data['Grade'] : null;
$idCategorie = isset($data['IdCategorie']) ? $data['IdCategorie'] : null;
$id_filiere = $_SESSION['id_fac'];
$photo_enseignant = null;

$mail = isset($data['email_enseignant']) ? $data['email_enseignant'] : null;
$tel = isset($data['telephone_enseignant']) ? $data['telephone_enseignant'] : null;

$niveau = isset($data['niveau_etude_enseignant']) ? $data['niveau_etude_enseignant'] : null;

$institution = isset($data['Insitution']) ? $data['Insitution'] : null;
$domaine = isset($data['domaine_enseignant']) ? $data['domaine_enseignant'] : null;

try {
    ob_start();
    $con->beginTransaction();

    $type = 2;

    $sql_insert = "INSERT INTO agent (Mat_agent, Nom_agent, Post_agent, Prenom, Sexe, Grade, EtatCivil, IdCategorie, Mail, 
                                        Tel, Niveau_Etude, Institution_attachee, Domaine_etude, type_agent,Id_filiere) 
                    VALUES (:matricule, :nom, :postnom, :prenom, :sexe, :grade, :etat, :Idcategorie, :mail, :tel, :niveau, :institution, :domaine, :type_agent,:id_filiere)";

    $stmt = $con->prepare($sql_insert);
    $stmt->bindParam(':matricule', $matricule);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':postnom', $postnom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':sexe', $sexe);
    $stmt->bindParam(':grade', $grade);
    $stmt->bindParam(':etat', $EtatCivil);
    $stmt->bindParam(':Idcategorie', $idCategorie);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':niveau', $niveau);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':domaine', $domaine);
    $stmt->bindParam(':type_agent', $type);
    $stmt->bindParam(':id_filiere', $id_filiere);
    $stmt->execute();

    $sql_insert = "INSERT INTO affecter (Fonction, Statut, Matricule_Agent, Id_filiere) 
                    VALUES (:fonction, :statut, :matricule, :id_filiere)";

    $fonction = 'Enseignant';
    $statut = 'Encours';

    $stmt3 = $con->prepare($sql_insert);
    $stmt3->bindParam(':matricule', $matricule);
    $stmt3->bindParam(':fonction', $fonction);
    $stmt3->bindParam(':statut', $statut);
    $stmt3->bindParam(':id_filiere', $id_filiere);
    $stmt3->execute();

    $con->commit();
    ob_clean();
    echo json_encode(['success' => true, 'message' => 'Enseignant ajouté avec succès. ']);

} catch (PDOException $e) {
    $con->rollBack();
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);

} catch (Exception $e) {
    $con->rollBack();
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Erreur inattendue : ' . $e->getMessage()]);
}



?>