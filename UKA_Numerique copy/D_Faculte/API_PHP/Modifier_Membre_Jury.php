<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');*/

try {
    // Récupérer les données
    $id_membre = isset($_POST['id_membre']) ? intval($_POST['id_membre']) : 0;
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $statut = isset($_POST['statut']) ? trim($_POST['statut']) : 'Actif';
    
    // Validation
    if ($id_membre <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID membre invalide.']);
        exit;
    }
    
    if (empty($role)) {
        echo json_encode(['success' => false, 'message' => 'Le rôle est obligatoire.']);
        exit;
    }
    
    // Vérifier si le membre existe
    $stmt = $con->prepare("SELECT * FROM t_membre_jury WHERE ID_jury_membre = ?");
    $stmt->execute([$id_membre]);
    $membre = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$membre) {
        echo json_encode(['success' => false, 'message' => 'Membre introuvable.']);
        exit;
    }
    
    // Si le rôle est Président ou Secrétaire, on doit avoir login
    if (($role === 'Président' || $role === 'Secrétaire')) {
        if (empty($login)) {
            echo json_encode(['success' => false, 'message' => 'Le login est obligatoire pour ce rôle.']);
            exit;
        }
        
        // Vérifier si le login existe déjà (sauf pour ce membre)
        $stmt = $con->prepare("SELECT ID_jury_membre FROM t_membre_jury WHERE Login = ? AND ID_jury_membre != ?");
        $stmt->execute([$login, $id_membre]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Ce login est déjà utilisé par un autre membre.']);
            exit;
        }
        
        // Préparer la mise à jour avec login/password
        if (!empty($password)) {
            // Nouveau mot de passe fourni
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $con->prepare("
                UPDATE t_membre_jury 
                SET role = ?, Login = ?, Mot_passe = ?, Statut = ?
                WHERE ID_jury_membre = ?
            ");
            $stmt->execute([$role, $login, $password_hash, $statut, $id_membre]);
        } else {
            // Garder l'ancien mot de passe
            $stmt = $con->prepare("
                UPDATE t_membre_jury 
                SET role = ?, Login = ?, Statut = ?
                WHERE ID_jury_membre = ?
            ");
            $stmt->execute([$role, $login, $statut, $id_membre]);
        }
        
    } else {
        // Simple Membre - pas de login/password
        $stmt = $connexion->prepare("
            UPDATE t_membre_jury 
            SET role = ?, Login = NULL, Mot_passe = NULL, Statut = 'Inactif'
            WHERE ID_jury_membre = ?
        ");
        $stmt->execute([$role, $id_membre]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Membre modifié avec succès.'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la modification : ' . $e->getMessage()
    ]);
}
?>
