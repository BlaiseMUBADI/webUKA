/**
 * ============================================================================
 * SessionMonitor - Surveillance de l'activité et gestion des sessions
 * ============================================================================
 * 
 * Fonctionnalités:
 * - Détection d'inactivité de l'utilisateur
 * - Déconnexion automatique après période d'inactivité
 * - Vérification périodique des demandes de connexion concurrentes
 * - Notifications en temps réel
 * - Mise à jour automatique de l'activité
 * 
 * @author  Système UKA
 * @version 1.0
 * @date    2025-12-09
 */

class SessionMonitor {
    constructor(options = {}) {
        // Configuration par défaut
        this.config = {
            inactivityTimeout: options.inactivityTimeout || 1800000, // 30 minutes en ms
            warningTime: options.warningTime || 300000, // Avertir 5 minutes avant
            checkInterval: options.checkInterval || 30000, // Vérifier toutes les 30 secondes
            activityUpdateInterval: options.activityUpdateInterval || 60000, // Mettre à jour toutes les minutes
            checkPendingRequestsInterval: options.checkPendingRequestsInterval || 10000, // Vérifier demandes toutes les 10 sec
            apiBasePath: options.apiBasePath || 'API/',
            logoutUrl: options.logoutUrl || 'Fonctions_PHP/Deconnexion.php',
            onWarning: options.onWarning || null,
            onLogout: options.onLogout || null,
            onPendingRequest: options.onPendingRequest || null
        };
        
        // État
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.checkTimer = null;
        this.activityUpdateTimer = null;
        this.pendingRequestTimer = null;
        this.isMonitoring = false;
        
        // Événements qui comptent comme activité
        this.activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        // Bind methods
        this.handleActivity = this.handleActivity.bind(this);
        this.checkInactivity = this.checkInactivity.bind(this);
        this.updateServerActivity = this.updateServerActivity.bind(this);
        this.checkPendingRequests = this.checkPendingRequests.bind(this);
    }
    
    /**
     * Démarre la surveillance
     */
    start() {
        if (this.isMonitoring) {
            console.warn('SessionMonitor: Déjà en cours d\'exécution');
            return;
        }
        
        console.log('SessionMonitor: Démarrage de la surveillance');
        this.isMonitoring = true;
        this.lastActivity = Date.now();
        
        // Attacher les écouteurs d'événements
        this.activityEvents.forEach(event => {
            document.addEventListener(event, this.handleActivity, true);
        });
        
        // Démarrer les timers
        this.checkTimer = setInterval(this.checkInactivity, this.config.checkInterval);
        this.activityUpdateTimer = setInterval(this.updateServerActivity, this.config.activityUpdateInterval);
        this.pendingRequestTimer = setInterval(this.checkPendingRequests, this.config.checkPendingRequestsInterval);
        
        // Première vérification immédiate
        this.checkPendingRequests();
    }
    
    /**
     * Arrête la surveillance
     */
    stop() {
        if (!this.isMonitoring) {
            return;
        }
        
        console.log('SessionMonitor: Arrêt de la surveillance');
        this.isMonitoring = false;
        
        // Détacher les écouteurs
        this.activityEvents.forEach(event => {
            document.removeEventListener(event, this.handleActivity, true);
        });
        
        // Arrêter les timers
        if (this.checkTimer) {
            clearInterval(this.checkTimer);
            this.checkTimer = null;
        }
        if (this.activityUpdateTimer) {
            clearInterval(this.activityUpdateTimer);
            this.activityUpdateTimer = null;
        }
        if (this.pendingRequestTimer) {
            clearInterval(this.pendingRequestTimer);
            this.pendingRequestTimer = null;
        }
    }
    
    /**
     * Gère les événements d'activité utilisateur
     */
    handleActivity() {
        this.lastActivity = Date.now();
        this.warningShown = false;
    }
    
    /**
     * Vérifie l'inactivité et déclenche les actions appropriées
     */
    checkInactivity() {
        const now = Date.now();
        const inactiveTime = now - this.lastActivity;
        
        // Vérifier si on doit afficher l'avertissement
        const timeUntilLogout = this.config.inactivityTimeout - inactiveTime;
        
        if (timeUntilLogout <= this.config.warningTime && !this.warningShown) {
            this.warningShown = true;
            const minutesLeft = Math.ceil(timeUntilLogout / 60000);
            
            if (this.config.onWarning) {
                this.config.onWarning(minutesLeft);
            } else {
                this.showDefaultWarning(minutesLeft);
            }
        }
        
        // Vérifier si on doit déconnecter
        if (inactiveTime >= this.config.inactivityTimeout) {
            this.logout('inactivite');
        }
    }
    
    /**
     * Affiche un avertissement par défaut
     */
    showDefaultWarning(minutesLeft) {
        const message = `Attention: Vous serez déconnecté dans ${minutesLeft} minute${minutesLeft > 1 ? 's' : ''} en raison d'inactivité.`;
        
        // Créer une notification simple
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff9800;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            z-index: 10000;
            font-family: Arial, sans-serif;
            max-width: 300px;
        `;
        notification.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i> ${message}
            <button onclick="this.parentElement.remove()" style="
                margin-left: 10px;
                background: transparent;
                border: none;
                color: white;
                cursor: pointer;
                font-size: 18px;
            ">&times;</button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 10000);
    }
    
    /**
     * Met à jour l'activité sur le serveur
     */
    updateServerActivity() {
        fetch(this.config.apiBasePath + 'update_activity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.warn('SessionMonitor: Échec de mise à jour de l\'activité', data.message);
            }
        })
        .catch(error => {
            console.error('SessionMonitor: Erreur lors de la mise à jour de l\'activité', error);
        });
    }
    
    /**
     * Vérifie s'il y a des demandes de connexion en attente
     */
    checkPendingRequests() {
        fetch(this.config.apiBasePath + 'check_pending_requests.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.session_expired) {
                this.logout('expiration');
                return;
            }
            
            if (data.success && data.has_pending_request) {
                // Arrêter la vérification des demandes pendant le traitement
                if (this.pendingRequestTimer) {
                    clearInterval(this.pendingRequestTimer);
                    this.pendingRequestTimer = null;
                }
                
                if (this.config.onPendingRequest) {
                    this.config.onPendingRequest(data.demande);
                } else {
                    this.showPendingRequestModal(data.demande);
                }
            }
        })
        .catch(error => {
            console.error('SessionMonitor: Erreur lors de la vérification des demandes', error);
        });
    }
    
    /**
     * Affiche une modal pour les demandes de connexion
     */
    showPendingRequestModal(demande) {
        // Créer la modal
        const modal = document.createElement('div');
        modal.id = 'pending-request-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
        `;
        
        const browserInfo = this.parseBrowserInfo(demande.user_agent);
        const timeAgo = this.getTimeAgo(demande.date_demande);
        
        modal.innerHTML = `
            <div style="
                background: white;
                padding: 30px;
                border-radius: 10px;
                max-width: 500px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            ">
                <h3 style="margin: 0 0 20px 0; color: #d32f2f;">
                    <i class="fas fa-user-lock"></i> Tentative de connexion détectée
                </h3>
                <p style="margin-bottom: 20px; line-height: 1.6;">
                    Une personne tente de se connecter avec vos identifiants depuis un autre appareil.
                </p>
                <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <p style="margin: 5px 0;"><strong>IP:</strong> ${demande.ip_address}</p>
                    <p style="margin: 5px 0;"><strong>Navigateur:</strong> ${browserInfo}</p>
                    <p style="margin: 5px 0;"><strong>Quand:</strong> ${timeAgo}</p>
                </div>
                <p style="margin-bottom: 20px; font-weight: bold; color: #333;">
                    Voulez-vous autoriser cette connexion ?
                </p>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button id="refuse-btn" style="
                        padding: 10px 20px;
                        background: #666;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 14px;
                    ">
                        <i class="fas fa-times"></i> Refuser
                    </button>
                    <button id="accept-btn" style="
                        padding: 10px 20px;
                        background: #4caf50;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 14px;
                    ">
                        <i class="fas fa-check"></i> Accepter
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Gérer les boutons
        document.getElementById('accept-btn').addEventListener('click', () => {
            this.respondToRequest(demande.id, 'accepte', modal);
        });
        
        document.getElementById('refuse-btn').addEventListener('click', () => {
            this.respondToRequest(demande.id, 'refuse', modal);
        });
    }
    
    /**
     * Répond à une demande de connexion
     */
    respondToRequest(demandeId, action, modal) {
        fetch(this.config.apiBasePath + 'respond_connection_request.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                demande_id: demandeId,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.remove();
                
                if (action === 'accepte') {
                    // Afficher message et déconnecter
                    alert('Vous avez accepté la nouvelle connexion. Vous allez être déconnecté.');
                    this.logout('concurrent');
                } else {
                    // Reprendre la vérification des demandes
                    this.pendingRequestTimer = setInterval(
                        this.checkPendingRequests, 
                        this.config.checkPendingRequestsInterval
                    );
                    alert('Demande refusée. Votre session reste active.');
                }
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la réponse', error);
            alert('Erreur lors du traitement de votre réponse');
        });
    }
    
    /**
     * Déconnecte l'utilisateur
     */
    logout(raison = 'manuelle') {
        console.log('SessionMonitor: Déconnexion (' + raison + ')');
        this.stop();
        
        if (this.config.onLogout) {
            this.config.onLogout(raison);
        } else {
            window.location.href = this.config.logoutUrl + '?raison=' + raison;
        }
    }
    
    /**
     * Parse le user agent pour affichage
     */
    parseBrowserInfo(userAgent) {
        if (!userAgent) return 'Inconnu';
        
        const browsers = [
            { name: 'Chrome', pattern: /Chrome\/(\d+)/ },
            { name: 'Firefox', pattern: /Firefox\/(\d+)/ },
            { name: 'Safari', pattern: /Safari\/(\d+)/ },
            { name: 'Edge', pattern: /Edg\/(\d+)/ },
            { name: 'Opera', pattern: /OPR\/(\d+)/ }
        ];
        
        for (let browser of browsers) {
            const match = userAgent.match(browser.pattern);
            if (match) {
                return `${browser.name} ${match[1]}`;
            }
        }
        
        return 'Navigateur inconnu';
    }
    
    /**
     * Calcule le temps écoulé
     */
    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'À l\'instant';
        if (seconds < 3600) return `Il y a ${Math.floor(seconds / 60)} minutes`;
        if (seconds < 86400) return `Il y a ${Math.floor(seconds / 3600)} heures`;
        return `Il y a ${Math.floor(seconds / 86400)} jours`;
    }
}

// Export pour utilisation globale
window.SessionMonitor = SessionMonitor;
