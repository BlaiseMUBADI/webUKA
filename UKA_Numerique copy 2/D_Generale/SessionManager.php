<?php
/**
 * ============================================================================
 * SessionManager - Gestionnaire de sessions sécurisées avec connexions uniques
 * ============================================================================
 * 
 * Cette classe gère l'authentification sécurisée avec:
 * - Une seule session active par utilisateur
 * - Notifications des tentatives de connexion concurrentes
 * - Validation bidirectionnelle (utilisateur actuel + nouveau demandeur)
 * - Détection d'inactivité et déconnexion automatique
 * - Historique complet des sessions
 * 
 * @author  Système UKA
 * @version 1.0
 * @date    2025-12-09
 */

class SessionManager {
    
    private $pdo;
    private $inactivity_timeout = 1800; // 30 minutes en secondes
    private $demande_timeout = 300; // 5 minutes pour répondre à une demande
    
    /**
     * Constructeur
     * @param PDO $pdo Instance PDO de connexion à la base de données
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Définir le délai d'inactivité
     * @param int $seconds Nombre de secondes avant déconnexion
     */
    public function setInactivityTimeout($seconds) {
        $this->inactivity_timeout = $seconds;
    }
    
    /**
     * Génère un ID de session unique et sécurisé
     * @return string ID de session
     */
    private function generateSessionId() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Génère un token unique pour validation
     * @return string Token
     */
    private function generateToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Récupère les informations de la session active pour un utilisateur
     * @param string $login Login de l'utilisateur
     * @param string $type_compte Type de compte (agent ou jury)
     * @return array|null Informations de la session ou null
     */
    public function getActiveSession($login, $type_compte = 'agent') {
        $sql = "SELECT * FROM session_actives
                WHERE user_login = :login 
                AND type_compte = :type_compte 
                AND statut = 'active'
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':type_compte' => $type_compte
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Vérifie si un utilisateur a déjà une session active
     * @param string $login Login de l'utilisateur
     * @param string $type_compte Type de compte
     * @return bool True si session active existe
     */
    public function hasActiveSession($login, $type_compte = 'agent') {
        $session = $this->getActiveSession($login, $type_compte);
        return ($session !== false && $session !== null);
    }
    
    /**
     * Crée une nouvelle session active
     * @param array $user_data Données de l'utilisateur
     * @return array Informations de la session créée
     */
    public function createSession($user_data) {
        $session_id = $this->generateSessionId();
        $token = $this->generateToken();
        $ip_address = $this->getClientIP();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $sql = "INSERT INTO session_actives 
                (session_id, user_login, user_matricule, type_compte, ip_address, 
                 user_agent, date_connexion, derniere_activite, statut, token_validation)
                VALUES 
                (:session_id, :user_login, :user_matricule, :type_compte, :ip_address,
                 :user_agent, NOW(), NOW(), 'active', :token)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':session_id' => $session_id,
            ':user_login' => $user_data['login'],
            ':user_matricule' => $user_data['matricule'],
            ':type_compte' => $user_data['type_compte'] ?? 'agent',
            ':ip_address' => $ip_address,
            ':user_agent' => $user_agent,
            ':token' => $token
        ]);
        
        // Mettre à jour la table compte_agent ou t_membre_jury
        $this->updateUserSessionStatus($user_data['login'], $user_data['type_compte'], $session_id, true);
        
        // Stocker l'ID de session en session PHP
        $_SESSION['secure_session_id'] = $session_id;
        $_SESSION['session_token'] = $token;
        $_SESSION['session_start_time'] = time();
        
        return [
            'session_id' => $session_id,
            'token' => $token,
            'success' => true
        ];
    }
    
    /**
     * Met à jour le statut de session dans les tables utilisateur
     * @param string $login Login
     * @param string $type_compte Type de compte
     * @param string $session_id ID de session
     * @param bool $active Statut actif
     */
    private function updateUserSessionStatus($login, $type_compte, $session_id, $active) {
        if ($type_compte === 'agent') {
            $sql = "UPDATE compte_agent 
                    SET session_active = :active,
                        session_id_actuelle = :session_id,
                        derniere_connexion = NOW()
                    WHERE Login = :login";
        } else {
            $sql = "UPDATE t_membre_jury 
                    SET session_active = :active,
                        session_id_actuelle = :session_id,
                        derniere_connexion = NOW()
                    WHERE Login = :login";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':active' => $active ? 1 : 0,
            ':session_id' => $active ? $session_id : null,
            ':login' => $login
        ]);
    }
    
    /**
     * Crée une demande de connexion quand une session est déjà active
     * @param array $user_data Données du nouvel utilisateur
     * @param string $session_id_actuelle ID de la session à remplacer
     * @return array Informations de la demande créée
     */
    public function createConnectionRequest($user_data, $session_id_actuelle) {
        $token = $this->generateToken();
        $ip_address = $this->getClientIP();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $expiration = date('Y-m-d H:i:s', time() + $this->demande_timeout);
        
        $sql = "INSERT INTO session_demandes_connexion 
                (user_login, user_matricule, type_compte, ip_address, user_agent, 
                 date_demande, statut_demande, session_id_actuelle, token_demande, expiration)
                VALUES 
                (:user_login, :user_matricule, :type_compte, :ip_address, :user_agent,
                 NOW(), 'en_attente', :session_id_actuelle, :token, :expiration)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_login' => $user_data['login'],
            ':user_matricule' => $user_data['matricule'],
            ':type_compte' => $user_data['type_compte'] ?? 'agent',
            ':ip_address' => $ip_address,
            ':user_agent' => $user_agent,
            ':session_id_actuelle' => $session_id_actuelle,
            ':token' => $token,
            ':expiration' => $expiration
        ]);
        
        $demande_id = $this->pdo->lastInsertId();
        
        return [
            'demande_id' => $demande_id,
            'token' => $token,
            'expiration' => $expiration,
            'success' => true
        ];
    }
    
    /**
     * Vérifie s'il existe des demandes de connexion en attente pour l'utilisateur actuel
     * @param string $login Login de l'utilisateur
     * @param string $type_compte Type de compte
     * @return array|null Informations de la demande ou null
     */
    public function getPendingConnectionRequest($login, $type_compte = 'agent') {
        $sql = "SELECT * FROM session_demandes_connexion 
                WHERE user_login = :login 
                AND type_compte = :type_compte
                AND statut_demande = 'en_attente'
                AND expiration > NOW()
                ORDER BY date_demande DESC
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':type_compte' => $type_compte
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * L'utilisateur actuel accepte la nouvelle connexion
     * @param int $demande_id ID de la demande
     * @return bool Succès
     */
    public function currentUserAcceptsRequest($demande_id) {
        $sql = "UPDATE session_demandes_connexion 
                SET reponse_user_actuel = 'accepte',
                    date_reponse_actuel = NOW()
                WHERE id_demande = :demande_id
                AND statut_demande = 'en_attente'";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':demande_id' => $demande_id]);
    }
    
    /**
     * L'utilisateur actuel refuse la nouvelle connexion
     * @param int $demande_id ID de la demande
     * @return bool Succès
     */
    public function currentUserRefusesRequest($demande_id) {
        $sql = "UPDATE session_demandes_connexion 
                SET reponse_user_actuel = 'refuse',
                    date_reponse_actuel = NOW(),
                    statut_demande = 'refusee'
                WHERE id_demande = :demande_id
                AND statut_demande = 'en_attente'";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':demande_id' => $demande_id]);
    }
    
    /**
     * Le nouveau demandeur confirme qu'il veut activer sa session
     * @param int $demande_id ID de la demande
     * @param string $token Token de validation
     * @return array Résultat de l'opération
     */
    public function newUserConfirmsActivation($demande_id, $token) {
        // Vérifier que la demande existe et est valide
        $sql = "SELECT * FROM session_demandes_connexion 
                WHERE id_demande = :demande_id
                AND token_demande = :token
                AND statut_demande = 'en_attente'
                AND reponse_user_actuel = 'accepte'
                AND expiration > NOW()";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':demande_id' => $demande_id,
            ':token' => $token
        ]);
        
        $demande = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$demande) {
            return [
                'success' => false,
                'message' => 'Demande invalide, expirée ou non acceptée'
            ];
        }
        
        // Commencer une transaction
        $this->pdo->beginTransaction();
        
        try {
            // 1. Désactiver l'ancienne session
            $this->terminateSession($demande['session_id_actuelle'], 'concurrent');
            
            // 2. Marquer la demande comme acceptée
            $sql = "UPDATE session_demandes_connexion 
                    SET reponse_user_demandeur = 'accepte',
                        date_reponse_demandeur = NOW(),
                        statut_demande = 'acceptee'
                    WHERE id_demande = :demande_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':demande_id' => $demande_id]);
            
            // 3. Créer la nouvelle session
            $user_data = [
                'login' => $demande['user_login'],
                'matricule' => $demande['user_matricule'],
                'type_compte' => $demande['type_compte']
            ];
            
            $new_session = $this->createSession($user_data);
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Session activée avec succès',
                'session_data' => $new_session
            ];
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'activation: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Le nouveau demandeur refuse d'activer sa session
     * @param int $demande_id ID de la demande
     * @param string $token Token de validation
     * @return bool Succès
     */
    public function newUserRefusesActivation($demande_id, $token) {
        $sql = "UPDATE session_demandes_connexion 
                SET reponse_user_demandeur = 'refuse',
                    date_reponse_demandeur = NOW(),
                    statut_demande = 'refusee'
                WHERE id_demande = :demande_id
                AND token_demande = :token
                AND statut_demande = 'en_attente'";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':demande_id' => $demande_id,
            ':token' => $token
        ]);
    }
    
    /**
     * Met à jour l'heure de dernière activité d'une session
     * @param string $session_id ID de la session
     * @return bool Succès
     */
    public function updateActivity($session_id = null) {
        if ($session_id === null && isset($_SESSION['secure_session_id'])) {
            $session_id = $_SESSION['secure_session_id'];
        }
        
        if (!$session_id) {
            return false;
        }
        
        $sql = "UPDATE session_actives 
                SET derniere_activite = NOW()
                WHERE session_id = :session_id
                AND statut = 'active'";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':session_id' => $session_id]);
    }
    
    /**
     * Termine une session et l'archive dans l'historique
     * @param string $session_id ID de la session
     * @param string $raison Raison de déconnexion
     * @return bool Succès
     */
    public function terminateSession($session_id, $raison = 'manuelle') {
        // Récupérer les infos de la session avant suppression
        $sql = "SELECT * FROM session_actives WHERE session_id = :session_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':session_id' => $session_id]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$session) {
            return false;
        }
        
        // Archiver dans l'historique
        $sql = "INSERT INTO session_historique 
                (session_id, user_login, user_matricule, type_compte, ip_address, 
                 user_agent, date_connexion, date_deconnexion, duree_session, raison_deconnexion)
                VALUES 
                (:session_id, :user_login, :user_matricule, :type_compte, :ip_address,
                 :user_agent, :date_connexion, NOW(), 
                 TIMESTAMPDIFF(SECOND, :date_connexion, NOW()), :raison)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':session_id' => $session['session_id'],
            ':user_login' => $session['user_login'],
            ':user_matricule' => $session['user_matricule'],
            ':type_compte' => $session['type_compte'],
            ':ip_address' => $session['ip_address'],
            ':user_agent' => $session['user_agent'],
            ':date_connexion' => $session['date_connexion'],
            ':raison' => $raison
        ]);
        
        // Supprimer de session_actives
        $sql = "DELETE FROM session_actives WHERE session_id = :session_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':session_id' => $session_id]);
        
        // Mettre à jour le statut utilisateur
        $this->updateUserSessionStatus($session['user_login'], $session['type_compte'], null, false);
        
        return true;
    }
    
    /**
     * Valide qu'une session est toujours active et valide
     * @param string $session_id ID de la session
     * @return bool True si valide
     */
    public function validateSession($session_id = null) {
        if ($session_id === null && isset($_SESSION['secure_session_id'])) {
            $session_id = $_SESSION['secure_session_id'];
        }
        
        if (!$session_id) {
            return false;
        }
        

        
        $sql = "SELECT * FROM session_actives 
                WHERE session_id = :session_id 
                AND statut = 'active'
                AND derniere_activite > DATE_SUB(NOW(), INTERVAL :timeout SECOND)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':session_id' => $session_id,
            ':timeout' => $this->inactivity_timeout
        ]);
        
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($session) {
            // Mettre à jour l'activité
            $this->updateActivity($session_id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Nettoie toutes les sessions inactives
     * @return int Nombre de sessions nettoyées
     */
    public function cleanInactiveSessions() {
        $sql = "SELECT session_id FROM session_actives 
                WHERE derniere_activite < DATE_SUB(NOW(), INTERVAL :timeout SECOND)
                AND statut = 'active'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':timeout' => $this->inactivity_timeout]);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $count = 0;
        foreach ($sessions as $session) {
            if ($this->terminateSession($session['session_id'], 'inactivite')) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Obtient l'adresse IP réelle du client
     * @return string IP address
     */
    private function getClientIP() {
        $ip = '';
        
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'UNKNOWN';
        }
        
        return $ip;
    }
    
    /**
     * Enregistre une activité dans le log (optionnel)
     * @param string $session_id ID de session
     * @param string $page Page visitée
     * @param string $action Action effectuée
     * @param array $data Données supplémentaires
     */
    public function logActivity($session_id, $page, $action = null, $data = null) {
        $sql = "INSERT INTO session_activites 
                (session_id, user_login, page_visitee, action, donnees_supplementaires)
                SELECT session_id, user_login, :page, :action, :data
                FROM session_actives
                WHERE session_id = :session_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':session_id' => $session_id,
            ':page' => $page,
            ':action' => $action,
            ':data' => $data ? json_encode($data) : null
        ]);
    }
}
