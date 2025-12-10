<!-- 
    ============================================================================
    Include Session Monitor - À inclure dans toutes les pages protégées
    ============================================================================
    
    Ce fichier doit être inclus dans le <head> de toutes les pages nécessitant
    une authentification pour activer:
    - La détection d'inactivité
    - La notification de connexions concurrentes
    - La mise à jour automatique de l'activité
    
    Usage: <?php include('D_Generale/include_session_monitor.php'); ?>
-->

<!-- Chargement du script SessionMonitor -->
<script src="D_Generale/JavaScript/session_monitor.js"></script>

<script>
    // Initialiser et démarrer le SessionMonitor
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration du moniteur de session
        const sessionMonitor = new SessionMonitor({
            inactivityTimeout: 1800000,      // 30 minutes (1800000 ms)
            warningTime: 300000,             // Avertir 5 minutes avant (300000 ms)
            checkInterval: 30000,            // Vérifier toutes les 30 secondes
            activityUpdateInterval: 60000,   // Mettre à jour l'activité chaque minute
            checkPendingRequestsInterval: 10000, // Vérifier demandes toutes les 10 secondes
            apiBasePath: 'D_Generale/API_PHP/',
            logoutUrl: 'Fonctions_PHP/Deconnexion.php',
            
            // Callback personnalisé pour l'avertissement d'inactivité
            onWarning: function(minutesLeft) {
                // Afficher une notification élégante
                showInactivityWarning(minutesLeft);
            },
            
            // Callback personnalisé pour la déconnexion
            onLogout: function(raison) {
                console.log('Déconnexion: ' + raison);
                // Afficher un message avant redirection
                if (raison === 'inactivite') {
                    alert('Vous avez été déconnecté en raison d\'inactivité.');
                } else if (raison === 'concurrent') {
                    alert('Votre session a été terminée car une nouvelle connexion a été établie.');
                }
                window.location.href = 'Fonctions_PHP/Deconnexion.php?raison=' + raison;
            },
            
            // Callback personnalisé pour les demandes de connexion
            onPendingRequest: function(demande) {
                // Utiliser la modal par défaut du SessionMonitor
                // Vous pouvez personnaliser ceci si nécessaire
                return null; // null = utiliser la modal par défaut
            }
        });
        
        // Démarrer la surveillance
        sessionMonitor.start();
        
        // Rendre le moniteur accessible globalement pour debug si nécessaire
        window.sessionMonitor = sessionMonitor;
        
        console.log('SessionMonitor: Surveillance activée');
    });
    
    /**
     * Affiche un avertissement d'inactivité personnalisé
     */
    function showInactivityWarning(minutesLeft) {
        // Supprimer les avertissements existants
        const existingWarning = document.getElementById('inactivity-warning');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // Créer l'avertissement
        const warning = document.createElement('div');
        warning.id = 'inactivity-warning';
        warning.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            z-index: 99998;
            max-width: 350px;
            font-family: Arial, sans-serif;
            animation: slideInRight 0.3s ease-out;
        `;
        
        warning.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 15px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-top: 5px;"></i>
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 8px 0; font-size: 1.1rem; font-weight: 700;">
                        Inactivité détectée
                    </h4>
                    <p style="margin: 0 0 12px 0; font-size: 0.9rem; line-height: 1.4; opacity: 0.95;">
                        Vous serez déconnecté dans <strong>${minutesLeft} minute${minutesLeft > 1 ? 's' : ''}</strong> 
                        en raison d'inactivité.
                    </p>
                    <button onclick="this.closest('#inactivity-warning').remove()" style="
                        background: rgba(255,255,255,0.2);
                        border: 1px solid rgba(255,255,255,0.3);
                        color: white;
                        padding: 6px 16px;
                        border-radius: 6px;
                        cursor: pointer;
                        font-size: 0.85rem;
                        font-weight: 600;
                        transition: all 0.2s;">
                        Compris
                    </button>
                </div>
                <button onclick="this.closest('#inactivity-warning').remove()" style="
                    background: transparent;
                    border: none;
                    color: white;
                    cursor: pointer;
                    font-size: 1.2rem;
                    padding: 0;
                    opacity: 0.7;
                    transition: opacity 0.2s;">
                    ×
                </button>
            </div>
        `;
        
        // Ajouter les styles d'animation
        if (!document.getElementById('session-monitor-styles')) {
            const style = document.createElement('style');
            style.id = 'session-monitor-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(warning);
        
        // Auto-masquer après 10 secondes
        setTimeout(() => {
            if (warning.parentElement) {
                warning.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => warning.remove(), 300);
            }
        }, 10000);
    }
</script>

<style>
    /* Styles pour les animations de sortie */
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>
