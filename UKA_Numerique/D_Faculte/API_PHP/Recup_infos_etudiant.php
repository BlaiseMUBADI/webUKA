<?php
session_start();
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// API pour ajouter un jury via la procédure stockée AddJury
//header('Content-Type: application/json');

include("../../../Connexion_BDD/Connexion_1.php");

// Vérifier la connexion à la base de données
if (!isset($con) || $con === null) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit;
}

try {
    // Récupérer les données POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['matricule']) || empty($data['matricule'])) {
        throw new Exception('Matricule manquant');
    }
    
    $matricule = $data['matricule'];
    
    // Récupérer la promotion et l'année académique depuis la session
    $code_promotion = $_SESSION['code_prom'] ?? null;
    $annee_acad = $_SESSION['id_annee_acad'] ?? null;
    
    // Requête pour récupérer toutes les informations de l'étudiant
    $sql = "SELECT 
                e.Matricule,
                CONCAT(e.Nom, ' ', e.Postnom, ' ', e.Prenom) as ident_etudiant,
                e.Nom,
                e.Postnom,
                e.Prenom,
                e.Sexe,
                e.DateNaissance as date_naissance,
                e.LieuNaissance as lieu_naissance,
                COALESCE(ai.TelVoda, ai.TelOrange, ai.TelAirtel, ai.TelResponsable) as telephone,
                ce.Mail_etudiant as email,
                ai.AdresseActuelle as adresse,
                ai.Nationalite as nationalite,
                ai.EtatCiv as etat_civil,
                p.Libelle_promotion as promotion,
                CONCAT(aa.Annee_debut, '-', aa.Annee_fin) as annee_academique,
                m.Libelle_mention as mention,
                fil.Libelle_Filiere as filiere,
                ph.Nom_image as photo_nom,
                ph.Type_image as photo_type
              FROM etudiant e
              LEFT JOIN autreinfo_etudiant ai ON e.Matricule = ai.Matricule
              LEFT JOIN compte_etudiant ce ON e.Matricule = ce.Matricule
              LEFT JOIN photo ph ON e.Matricule = ph.Matricule
              LEFT JOIN passer_par pp ON e.Matricule = pp.Etudiant_Matricule 
                  AND pp.Code_Promotion = :code_promotion 
                  AND pp.idAnnee_academique = :annee_acad
              LEFT JOIN promotion p ON pp.Code_Promotion = p.Code_Promotion
              LEFT JOIN annee_academique aa ON pp.idAnnee_academique = aa.idAnnee_Acad
              LEFT JOIN mentions m ON p.idMentions = m.idMentions
              LEFT JOIN filiere fil ON m.IdFiliere = fil.IdFiliere
              WHERE e.Matricule = :matricule
              LIMIT 1";
    
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $stmt->bindParam(':code_promotion', $code_promotion, PDO::PARAM_STR);
    $stmt->bindParam(':annee_acad', $annee_acad, PDO::PARAM_INT);
    $stmt->execute();
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    if (!$student) {
        throw new Exception('Étudiant non trouvé');
    }
    
    // Formater la date de naissance si elle existe
    if (!empty($student['date_naissance'])) {
        $date = new DateTime($student['date_naissance']);
        $student['date_naissance'] = $date->format('d/m/Y');
    }
    
    // Construire l'URL de la photo (relatif depuis le fichier PHP appelant, pas depuis l'API)
    $photoPath = '';
    if (!empty($student['photo_nom'])) {
        // Si le nom de la photo est stocké dans la table photo
        // Vérifier si le fichier existe (chemin absolu depuis l'API)
        $absolutePath = '../../Fichiers/Images/' . $student['photo_nom'];
        if (file_exists($absolutePath)) {
            // Retourner le chemin relatif depuis la page HTML
            $photoPath = '../Fichiers/Images/' . $student['photo_nom'];
        } else {
            $photoPath = '../Fichiers/Images/Profil.jpg';
        }
    } else {
        // Sinon, essayer avec le matricule
        $absolutePhotoFile = '../../Fichiers/Images/' . $matricule . '.jpg';
        if (file_exists($absolutePhotoFile)) {
            $photoPath = '../Fichiers/Images/' . $matricule . '.jpg';
        } else {
            // Photo par défaut (Profil.jpg existe dans le dossier)
            $photoPath = '../Fichiers/Images/Profil.jpg';
        }
    }
    
    $student['photo_url'] = $photoPath;
    
    // Retourner les données
    echo json_encode([
        'success' => true,
        'student' => $student
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
