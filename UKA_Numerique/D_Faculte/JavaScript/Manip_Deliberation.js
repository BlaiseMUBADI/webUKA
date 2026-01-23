
console.log("🎓 Module Délibération chargé")

// ==================== Variables Module Délibération ====================
let cmb_semestre_encodage_delib;
let selectedStudentMatricule_delib = null;
let selectedStudentNom_delib = null;

// Variables pour le menu contextuel des côtes
let selectedCoteMatricule = null;
let selectedCoteEcAligne = null;
let selectedCoteValeur = null;
let selectedCoteCell = null;

// Variable globale pour toutes les cotes (pour menu contextuel)
let tab_Cotes = [];

// ==================== Initialisation ====================
document.addEventListener("DOMContentLoaded", function(event) {
    const container = document.getElementById("div_gen_deliberation");
    if (container !== null) {
        cmb_semestre_encodage_delib = container.querySelector('#id_semestre_encodage') || document.getElementById('id_semestre_encodage');

        // Initialiser les badges à 0 au démarrage
        document.getElementById('count-etudiants').textContent = '0';
        document.getElementById('count-ecs').textContent = '0';
        document.getElementById('count-cotes').textContent = '0';

        Liste_Etudiants();
        // NE PAS appeler Afficher_EC_aligne_deliberation() au démarrage (aucun semestre sélectionné)
        
        if (cmb_semestre_encodage_delib !== null) {
            cmb_semestre_encodage_delib.addEventListener('change', (event) => {
                var id_semetre = cmb_semestre_encodage_delib.value;
                if (id_semetre && id_semetre !== '0') {
                    Liste_Ec_Aligne(id_semetre); 
                    Afficher_EC_aligne_deliberation();
                }
            });
        }

        // ==================== Search Functionality ====================
        const searchInput = document.getElementById('search-student');
        const clearBtn = document.querySelector('.clear-search');
        
        if (searchInput) {
            searchInput.addEventListener('input', filterStudents);
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    filterStudents();
                    searchInput.blur();
                }
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                filterStudents();
                searchInput.focus();
            });
        }

        // Initialiser le menu contextuel
        initializeContextMenu();
        initializeContextMenuCote();
        
        // Restaurer la préférence du menu
        restoreMenuPreference();
    }
});

// ==================== Toggle Menu Sidebar ====================
function toggleMenuEncodage() {
    const sidebar = document.querySelector('.sidebar');
    const container = document.getElementById('encodage-container');
    const btn = document.querySelector('.toggle-menu-btn i');
    
    if (!sidebar || !container) return;
    
    sidebar.classList.toggle('active');
    container.classList.toggle('fullscreen');
    
    // Changer l'icône selon l'état
    const indicator = document.getElementById('fullscreen-indicator');
    if (sidebar.classList.contains('active')) {
        btn.className = 'fas fa-angle-double-right';
        if (indicator) indicator.style.display = 'flex';
    } else {
        btn.className = 'fas fa-bars';
        if (indicator) indicator.style.display = 'none';
    }
    
    // Sauvegarder la préférence
    localStorage.setItem('menuEncodageCollapsed', sidebar.classList.contains('active'));
}

function restoreMenuPreference() {
    const isCollapsed = localStorage.getItem('menuEncodageCollapsed') === 'true';
    if (isCollapsed) {
        const sidebar = document.querySelector('.sidebar');
        const container = document.getElementById('encodage-container');
        const btn = document.querySelector('.toggle-menu-btn i');
        
        if (sidebar && container) {
            sidebar.classList.add('active');
            container.classList.add('fullscreen');
            if (btn) btn.className = 'fas fa-angle-double-right';
            
            const indicator = document.getElementById('fullscreen-indicator');
            if (indicator) indicator.style.display = 'flex';
        }
    }
}

// ==================== Boîte d'Alerte Moderne ====================
// Boîte d'alerte/confirmation moderne
// Si onConfirm est fourni, affiche Oui/Non, sinon simple alerte OK
function Ouvrir_Boite_Alert_Encodage(text_a_afficher, type = 'info', onConfirm = null) {
    const boite = document.getElementById('boite_alert_encodage');
    const texte = document.getElementById('text_alert_boite_encodage');
    const icon = document.getElementById('alert_icon_type_encodage');
    if (!boite || !texte || !icon) return;
    texte.innerHTML = text_a_afficher;
    // Changer l'icône selon le type
    if (type === 'success') {
        icon.className = 'fas fa-check-circle';
    } else if (type === 'error') {
        icon.className = 'fas fa-exclamation-circle';
    } else if (type === 'warning') {
        icon.className = 'fas fa-exclamation-triangle';
    } else {
        icon.className = 'fas fa-info-circle';
    }
    // Nettoyer anciens boutons
    let btns = boite.querySelector('.alert-confirm-btns');
    if (btns) btns.remove();
    // Ajouter boutons si confirmation
    if (typeof onConfirm === 'function') {
        btns = document.createElement('div');
        btns.className = 'alert-confirm-btns';
        btns.style = 'display:flex;gap:18px;justify-content:center;margin:18px 0 8px 0;';
        const btnOui = document.createElement('button');
        btnOui.className = 'btn btn-success';
        btnOui.innerHTML = '<i class="fas fa-check me-1"></i>Oui';
        btnOui.onclick = function() {
            boite.close();
            onConfirm();
        };
        const btnNon = document.createElement('button');
        btnNon.className = 'btn btn-secondary';
        btnNon.innerHTML = '<i class="fas fa-times me-1"></i>Non';
        btnNon.onclick = function() { boite.close(); };
        btns.appendChild(btnOui);
        btns.appendChild(btnNon);
        boite.querySelector('.modal-body').appendChild(btns);
    }
    boite.showModal();
}


// Ouvre le modal d'infos de transaction (doit être global pour le HTML)
function ouvrirModalTransaction() 
{    
    // Fonction utilitaire pour extraire l'id_ec_aligne depuis la référence
    function Extraire_ID_EC_dans_REF(ref) 
    {
        if (typeof ref === 'string' && ref.includes('_')) {
            const parts = ref.split('_');
            return parts[parts.length - 1];
        }
        return null;
    }

    const c = window._transactionCoteObj;
    const id_Ec_Cedant_ou_compensé = Extraire_ID_EC_dans_REF(c.Ligne_touchee_Matricule_id_ec_aligne);

    // Correction : récupérer les éléments du DOM pour le modal et le contenu
    const modal = document.getElementById('modal_transaction_info');
    const content = document.getElementById('transaction_info_content');
    if (!modal || !content || !window._transactionCoteObj) return;
    
    // Recherche des intitulés des EC cédant et bénéficiaire
    // TEST: Afficher l'intitulé de l'EC cédant (id_Ec_Cedant_ou_compensé) via tab_ECs_aligne
    let ecIntituleCedant = '-';
    let ecIntituleBenef = '-';
    if (id_Ec_Cedant_ou_compensé && Array.isArray(window.tab_ECs_aligne)) {
        const ec = window.tab_ECs_aligne.find(e => String(e.id_ec_aligne).trim() === String(id_Ec_Cedant_ou_compensé).trim());
        
        if (ec && ec.Intutile_ec) {
            ecIntituleCedant = ec.Intutile_ec;
        }
    }

    if (c.id_ec_aligne && Array.isArray(window.tab_ECs_aligne)) {
        const ec = window.tab_ECs_aligne.find(e => String(e.id_ec_aligne).trim() === String(c.id_ec_aligne).trim());
        
        if (ec && ec.Intutile_ec) {
            ecIntituleBenef = ec.Intutile_ec;
        }
    }

    
   

    // Remplir les champs du modal moderne
    // Exemples d'IDs à adapter selon le HTML du modal :
    // #transaction_type_label, #transaction_type_icon, #transaction_ec_intitule, #transaction_note_label, #transaction_note_value, #transaction_ref
    let refTransactionAffichee = (typeof c.Ligne_touchee_Matricule_id_ec_aligne !== 'undefined') ? c.Ligne_touchee_Matricule_id_ec_aligne : '(non renseignée)';
    if (window._transactionType === 'compensee') 
    {

        
        document.getElementById('transaction_type_label').textContent = 'Compensation reçue';
        document.getElementById('transaction_type_icon').className = 'fas fa-arrow-down me-2';
        document.getElementById('transaction_type_icon').style.color = '#43a047';
        document.getElementById('transaction_ec_label').innerHTML = `Cet EC (<b>${ecIntituleBenef}</b>) a reçu des points de : <b>${ecIntituleCedant}</b>`;
        //document.getElementById('transaction_ec_intitule').textContent = ecIntituleBenef;
        document.getElementById('transaction_ec_intitule').className = 'badge bg-success';
        document.getElementById('transaction_note_label').textContent = 'Note compensée :';
        document.getElementById('transaction_note_value').textContent = c.cote_compensee || '-';
        document.getElementById('transaction_ref').textContent = refTransactionAffichee;
    } 
    else if (window._transactionType === 'cedee') 
    {
        document.getElementById('transaction_type_label').textContent = 'Points cédés';
        document.getElementById('transaction_type_icon').className = 'fas fa-arrow-up me-2';
        document.getElementById('transaction_type_icon').style.color = '#e65100';
        document.getElementById('transaction_ec_label').innerHTML = `Cet EC (<b>${ecIntituleBenef}</b>) a cédé des points à : <b>${ecIntituleCedant}</b>`;
        //document.getElementById('transaction_ec_intitule').textContent = ecIntituleCedant;
        document.getElementById('transaction_ec_intitule').className = 'badge bg-primary';
        document.getElementById('transaction_note_label').textContent = 'Note après cession :';
        document.getElementById('transaction_note_value').textContent = c.cote_reste_apres_cedee || '-';
        document.getElementById('transaction_ref').textContent = refTransactionAffichee;
    } 
    else 
    {
        document.getElementById('transaction_type_label').textContent = 'Aucune information de transaction disponible';
        document.getElementById('transaction_type_icon').className = '';
        document.getElementById('transaction_type_icon').style.color = '';
        document.getElementById('transaction_ec_label').textContent = '';
        document.getElementById('transaction_ec_intitule').textContent = '';
        document.getElementById('transaction_ec_intitule').className = '';
        document.getElementById('transaction_note_label').textContent = '';
        document.getElementById('transaction_note_value').textContent = '';
        document.getElementById('transaction_ref').textContent = '';
    }
    modal.showModal();
   
}

function fermerModalTransaction() {
    window.fermerModalTransaction = fermerModalTransaction;
    const modal = document.getElementById('modal_transaction_info');
    if (modal) modal.close();
}

function Fermer_Boite_Alert_Encodage() {
    const boite = document.getElementById('boite_alert_encodage');
    if (boite) boite.close();
}

// ==================== Menu Contextuel Étudiant ====================
function initializeContextMenu() {
    const table = document.getElementById('table_deliberation');
    const contextMenu = document.getElementById('contextMenuStudent');
    
    if (!table || !contextMenu) return;
    
    // Événement clic droit sur les lignes étudiants
    table.addEventListener('contextmenu', function(e) {
        // Vérifier si on clique sur une cellule étudiant
        const studentCell = e.target.closest('.student-cell');
        const row = e.target.closest('tbody tr');
        
        if (!studentCell || !row) return;
        
        e.preventDefault();
        
        // Récupérer les données de l'étudiant
        const nomCell = row.querySelector('td:nth-child(2)'); // 2ème colonne = nom
        if (!nomCell) return;
        
        selectedStudentMatricule_delib = row.dataset.matricule;
        
        // Extraire le nom depuis le span .student-name
        const nameSpan = nomCell.querySelector('.student-name');
        selectedStudentNom_delib = nameSpan ? nameSpan.textContent.trim() : 'Étudiant';
        
        // Positionner le menu
        contextMenu.style.left = e.pageX + 'px';
        contextMenu.style.top = e.pageY + 'px';
        contextMenu.style.display = 'block';
    });
    
    // Fermer le menu si clic ailleurs
    document.addEventListener('click', function(e) {
        if (!contextMenu.contains(e.target)) {
            contextMenu.style.display = 'none';
        }
    });
    
    // Empêcher fermeture si clic dans le menu
    contextMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

function afficherInfosEtudiant() {
    if (!selectedStudentMatricule_delib) return;
    
    document.getElementById('contextMenuStudent').style.display = 'none';
    
    fetch('API_PHP/Recup_infos_etudiant.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ matricule: selectedStudentMatricule_delib })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateStudentModal(data.student);
            document.getElementById('modal_Infos_Etudiant').showModal();
        } else {
            Ouvrir_Boite_Alert_Encodage('Erreur: ' + (data.message || 'Impossible de récupérer les informations'), 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        Ouvrir_Boite_Alert_Encodage('Erreur lors de la récupération des informations', 'error');
    });
}

function populateStudentModal(student) {
    // Photo
    const photoImg = document.getElementById('student_photo');
    if (photoImg) {
        photoImg.src = student.photo_url || '../Fichiers/Images/Profil.jpg';
        photoImg.onerror = function() { this.src = '../Fichiers/Images/Profil.jpg'; };
    }
    
    // Identité
    document.getElementById('student_matricule').textContent = student.Matricule || '-';
    document.getElementById('student_sexe').textContent = student.Sexe || '-';
    document.getElementById('student_nom_complet').textContent = student.ident_etudiant || '-';
    document.getElementById('student_date_naissance').textContent = student.date_naissance || '-';
    document.getElementById('student_lieu_naissance').textContent = student.lieu_naissance || '-';
    
    // Contact
    document.getElementById('student_telephone').textContent = student.telephone || '-';
    document.getElementById('student_email').textContent = student.email || '-';
    document.getElementById('student_adresse').textContent = student.adresse || '-';
    
    // Académique
    document.getElementById('student_promotion').textContent = student.promotion || '-';
    document.getElementById('student_annee_academique').textContent = student.annee_academique || '-';
    document.getElementById('student_faculte').textContent = student.faculte || '-';
    document.getElementById('student_filiere').textContent = student.filiere || '-';
}

function closeStudentModal() {
    document.getElementById('modal_Infos_Etudiant').close();
}

function afficherHistoriqueNotes() {
    document.getElementById('contextMenuStudent').style.display = 'none';
    Ouvrir_Boite_Alert_Encodage('Fonctionnalité en cours de développement: Historique des notes pour ' + selectedStudentNom_delib, 'info');
}

function genererBulletin() {
    document.getElementById('contextMenuStudent').style.display = 'none';
    // Ouvre le bulletin PDF dans un nouvel onglet, possibilité de passer le matricule en GET
    if (selectedStudentMatricule_delib) {
        window.open(
            '../Impression/Docs_a_imprimer/bulletin_semestriel.php?matricule=' + encodeURIComponent(selectedStudentMatricule_delib),
            '_blank'
        );
    } else {
        Ouvrir_Boite_Alert_Encodage('Aucun étudiant sélectionné.', 'error');
    }
}

// ==================== Menu Contextuel Côte (pour Compensation) ====================
function initializeContextMenuCote() {
    const table = document.getElementById('table_deliberation');
    const contextMenuCote = document.getElementById('contextMenuCote');
    
    if (!table || !contextMenuCote) return;
    
    // Événement clic droit sur les cellules éditables
    table.addEventListener('contextmenu', function(e) {
        const editableCell = e.target.closest('.cell-editable');
        if (!editableCell) return;
        e.preventDefault();
        // Récupérer les données de la cellule
        const matricule = editableCell.dataset.matricule;
        const ecAligne = editableCell.dataset.ecAligne;
        const valeur = editableCell.textContent.trim();
        if (!valeur || valeur === '') return;
        // Chercher la cote dans tab_Cotes
        let coteObj = null;
        if (Array.isArray(tab_Cotes)) {
            coteObj = tab_Cotes.find(c =>
                String(c.Matricule).trim() === String(matricule).trim() &&
                String(c.id_ec_aligne).trim() === String(ecAligne).trim()
            );
        }
        // Déterminer le type de la cellule
        let isDeficitaire = false, isCompensee = false, isCede = false, refTransaction = null;
        if (coteObj) {
            if (coteObj.cote_compensee !== undefined && coteObj.cote_compensee !== null && coteObj.cote_compensee !== "") {
                isCompensee = true;
                refTransaction = coteObj.Ligne_touchee_Matricule_id_ec_aligne || null;
            } else if (coteObj.cote_reste_apres_cedee !== undefined && coteObj.cote_reste_apres_cedee !== null && coteObj.cote_reste_apres_cedee !== "" && coteObj.cote_reste_apres_cedee !== coteObj.Cote) {
                isCede = true;
                refTransaction = coteObj.Ligne_touchee_Matricule_id_ec_aligne || null;
            } else if (coteObj.Cote !== undefined && coteObj.Cote !== null && coteObj.Cote !== "") {
                const n = parseFloat(coteObj.Cote);
                if (!isNaN(n) && n < 10) isDeficitaire = true;
            }
        }
        selectedCoteMatricule = matricule;
        selectedCoteEcAligne = ecAligne;
        selectedCoteValeur = valeur;
        selectedCoteCell = editableCell;
        // Mettre à jour le menu (plus de debug)
        const menuTitle = contextMenuCote.querySelector('.context-menu-title');
        let menuHtml = `<i class=\"fas fa-calculator me-2\"></i>Note: ${valeur}/20`;
        if (isDeficitaire) menuHtml += ` <span style=\"color: #ff6b6b; font-weight: bold;\">(< 10)</span>`;
        if (menuTitle) menuTitle.innerHTML = menuHtml;
        // Afficher les options selon le cas
        const optionComp = contextMenuCote.querySelector('.context-menu-compensation');
        const optionInfo = contextMenuCote.querySelector('.context-menu-info-transaction');
        const optionAnnuler = contextMenuCote.querySelector('.context-menu-annuler-compensation');
        if (optionComp) optionComp.style.display = isDeficitaire ? 'block' : 'none';
        if (optionInfo) optionInfo.style.display = (isCompensee || isCede) ? 'block' : 'none';
        if (optionAnnuler) optionAnnuler.style.display = (isCompensee || isCede) ? 'block' : 'none';
        // Si info transaction, remplir le détail
        const infoDetail = contextMenuCote.querySelector('.context-menu-transaction-detail');
        if ((isCompensee || isCede) && infoDetail) {
            if (refTransaction) {
                // Afficher la référence brute, ou décoder si besoin
                infoDetail.textContent = isCompensee ? `Compensé par: ${refTransaction}` : `A cédé à: ${refTransaction}`;
            } else {
                infoDetail.textContent = "Aucune information de transaction disponible.";
            }
        } else if (infoDetail) {
            infoDetail.textContent = "";
        }
        // Rendre la fonction transaction globale pour le menu
        if (optionInfo) {
            optionInfo.onclick = function() {
                window._transactionCoteObj = coteObj;
                // Initialisation explicite du paramètre Ligne_touchee_Matricule_id_ec_aligne si absent
                if (window._transactionCoteObj && typeof window._transactionCoteObj.Ligne_touchee_Matricule_id_ec_aligne === 'undefined') {
                    // On tente de générer une référence à partir du matricule et de l'id_ec_aligne
                    if (window._transactionCoteObj.Matricule && window._transactionCoteObj.id_ec_aligne) {
                        window._transactionCoteObj.Ligne_touchee_Matricule_id_ec_aligne = window._transactionCoteObj.Matricule + '_' + window._transactionCoteObj.id_ec_aligne;
                    } else {
                        window._transactionCoteObj.Ligne_touchee_Matricule_id_ec_aligne = '';
                    }
                }
                window._transactionType = isCompensee ? 'compensee' : (isCede ? 'cedee' : '');
                ouvrirModalTransaction();
            };
        }
        // Positionner le menu
        contextMenuCote.style.left = e.pageX + 'px';
        contextMenuCote.style.top = e.pageY + 'px';
        contextMenuCote.style.display = 'block';
    });
    
    // Fermer le menu si clic ailleurs
    document.addEventListener('click', function(e) {
        if (!contextMenuCote.contains(e.target)) {
            contextMenuCote.style.display = 'none';
        }
    });
    
    // Empêcher fermeture si clic dans le menu
    contextMenuCote.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}



async function proposerCompensation() 
{
    const contextMenu = document.getElementById('contextMenuCote');
    if (contextMenu) contextMenu.style.display = 'none';
    
    if (!selectedCoteMatricule || !selectedCoteEcAligne) {
        Ouvrir_Boite_Alert_Encodage('Aucune note sélectionnée', 'error');
        return;
    }
    
    // Récupérer le semestre actuel
    const id_semestre = cmb_semestre_encodage_delib.value;
    if (!id_semestre || id_semestre === '0') {
        Ouvrir_Boite_Alert_Encodage('Veuillez sélectionner un semestre', 'warning');
        return;
    }
    
    // Afficher un message de chargement
    Ouvrir_Boite_Alert_Encodage('🔍 Recherche des ECs compensables...', 'info');
    
    try {
        const response = await fetch('API_PHP/Recup_EC_Compensables.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                matricule: selectedCoteMatricule,
                ec_beneficiaire: selectedCoteEcAligne,
                id_semestre: id_semestre
            })
        });
        
        const data = await response.json();
        
        Fermer_Boite_Alert_Encodage();
        
        if (!data.success) {
            Ouvrir_Boite_Alert_Encodage(data.message || 'Erreur lors de la récupération des ECs', 'error');
            return;
        }
        
        if (data.count === 0) {
            const creditInfo = data.ec_beneficiaire && data.ec_beneficiaire.credit 
                ? `${data.ec_beneficiaire.credit} crédits` 
                : 'même crédit';
            
            Ouvrir_Boite_Alert_Encodage(
                `Aucun EC disponible pour compenser.\n\n` +
                `📌 Pour qu'un EC puisse compenser, il doit:\n` +
                `• Être dans la même UE (${data.ue.intitule})\n` +
                `• Avoir le MÊME nombre de crédits (${creditInfo})\n` +
                `• Avoir une note > 10/20\n` +
                `• Ne pas avoir déjà cédé tous ses points`,
                'warning'
            );
            return;
        }
        
        // Afficher le modal avec les ECs compensables
        afficherModalCompensation(data);
        
    } catch (error) {
        console.error('Erreur:', error);
        Fermer_Boite_Alert_Encodage();
        Ouvrir_Boite_Alert_Encodage('Erreur lors de la communication avec le serveur', 'error');
    }
}



function afficherModalCompensation(data) {
    const modal = document.getElementById('modal_Compensation');
    if (!modal) return;
    
    // Remplir les informations de l'UE
    document.getElementById('comp_ue_intitule').textContent = data.ue.intitule;
    document.getElementById('comp_ue_code').textContent = data.ue.code;
    document.getElementById('comp_ue_categorie').textContent = data.ue.categorie;
    
    // Afficher la note actuelle, le déficit et le crédit
    const ecBenefInfo = document.getElementById('comp_ec_beneficiaire_info');
    if (ecBenefInfo && data.ec_beneficiaire.intitule) {
        ecBenefInfo.innerHTML = `
            <strong>${data.ec_beneficiaire.intitule}</strong>
            <span class="badge bg-primary ms-2">${data.ec_beneficiaire.credit} Crédits</span>
        `;
    }
    
    document.getElementById('comp_cote_actuelle').textContent = data.ec_beneficiaire.cote_actuelle;
    document.getElementById('comp_deficit').textContent = data.ec_beneficiaire.deficit.toFixed(2);
    // Générer dynamiquement la liste des ECs compensables
    const listeEcsDiv = document.getElementById('liste_ecs_compensables');
    if (listeEcsDiv) {
        listeEcsDiv.innerHTML = '';
        if (Array.isArray(data.ecs_compensables) && data.ecs_compensables.length > 0) {
            data.ecs_compensables.forEach(ec => {
                // Créer un bloc pour chaque EC cédant
                const ecDiv = document.createElement('div');
                ecDiv.className = 'ec-compensable-item card mb-2 p-2';
                ecDiv.style.display = 'flex';
                ecDiv.style.alignItems = 'center';
                ecDiv.style.justifyContent = 'space-between';
                ecDiv.style.border = '1px solid #c8e6c9';
                ecDiv.style.background = '#f9fdf9';

                // Infos EC
                const infoDiv = document.createElement('div');
                infoDiv.innerHTML = `
                    <strong>${ec.intitule}</strong>
                    <span class="badge bg-success ms-2">${ec.credit} Crédits</span>
                    <span class="badge bg-info ms-2">Note: ${ec.cote_actuelle}/20</span>
                    <span class="badge bg-secondary ms-2">Disponible: ${ec.points_disponibles} pts</span>
                `;

                // Bouton d'action
                const btn = document.createElement('button');
                btn.className = 'btn btn-outline-success btn-sm';
                btn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i>Utiliser pour compenser';
                btn.onclick = () => appliquerCompensation(ec.id_ec_aligne);
                btn.title = `Céder des points de cet EC pour compenser`;

                ecDiv.appendChild(infoDiv);
                ecDiv.appendChild(btn);
                listeEcsDiv.appendChild(ecDiv);
            });
        } else {
            // Aucun EC compensable trouvé (ce cas ne devrait pas arriver ici normalement)
            listeEcsDiv.innerHTML = '<div class="alert alert-warning">Aucun EC disponible pour compenser.</div>';
        }
    }
    modal.showModal();
}

function fermerModalCompensation() {
    const modal = document.getElementById('modal_Compensation');
    if (modal) modal.close();
}

async function appliquerCompensation(ec_cedant_id) {
    // Utiliser la boîte moderne pour confirmation
    Ouvrir_Boite_Alert_Encodage(
        '⚠️ Confirmer la compensation ?<br><br>Cette action modifiera les deux notes de façon permanente.',
        'warning',
        async () => {
            fermerModalCompensation();
            Ouvrir_Boite_Alert_Encodage('⏳ Application de la compensation en cours...', 'info');
            try {
                const response = await fetch('API_PHP/Compenser_Cote.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        matricule: selectedCoteMatricule,
                        ec_beneficiaire: selectedCoteEcAligne,
                        ec_cedant: ec_cedant_id
                    })
                });
                const data = await response.json();
                Fermer_Boite_Alert_Encodage();
                if (data.success) {
                    Ouvrir_Boite_Alert_Encodage(
                        `✅ Compensation réussie!<br><br>` +
                        `📈 EC bénéficiaire: ${data.beneficiaire.cote_avant} → ${data.beneficiaire.cote_apres} (+${data.beneficiaire.points_recus})<br>` +
                        `📉 EC cédant: ${data.cedant.cote_avant} → ${data.cedant.cote_apres} (-${data.cedant.points_cedes})`,
                        'success'
                    );
                    setTimeout(() => {
                        Fermer_Boite_Alert_Encodage();
                        Afficher_EC_aligne_deliberation();
                    }, 2000);
                } else {
                    Ouvrir_Boite_Alert_Encodage('❌ ' + (data.message || 'Erreur lors de la compensation'), 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                Fermer_Boite_Alert_Encodage();
                Ouvrir_Boite_Alert_Encodage('Erreur lors de la communication avec le serveur', 'error');
            }
        }
    );
}


// La fonction annulerCompensationDepuisMenu est maintenant globale
// Fonction globale pour annuler la compensation depuis le menu contextuel
async function annulerCompensationDepuisMenu() 
{
    const contextMenu = document.getElementById('contextMenuCote');
    if (contextMenu) contextMenu.style.display = 'none';
    // Contrôle de sécurité sur les paramètres
    if (!selectedCoteMatricule || !selectedCoteEcAligne || String(selectedCoteMatricule).trim() === '' || String(selectedCoteEcAligne).trim() === '') {
        Ouvrir_Boite_Alert_Encodage('Impossible d\'annuler : paramètres manquants ou invalides.<br>Merci de sélectionner une note valide.', 'error');
        return;
    }
    // Récupérer l'id de l'EC cédant depuis la référence de compensation
    let coteObj = null;
    if (Array.isArray(tab_Cotes)) {
        coteObj = tab_Cotes.find(c =>
            String(c.Matricule).trim() === String(selectedCoteMatricule).trim() &&
            String(c.id_ec_aligne).trim() === String(selectedCoteEcAligne).trim()
        );
    }
    let ec_cedant_id = null;
    if (coteObj && coteObj.Ligne_touchee_Matricule_id_ec_aligne) {
        // Format attendu : MATRICULE_idECcedant
        const ref = coteObj.Ligne_touchee_Matricule_id_ec_aligne;
        const parts = ref.split('_');
        ec_cedant_id = parts[parts.length - 1];
    }
    if (!ec_cedant_id) {
        Ouvrir_Boite_Alert_Encodage('Impossible de retrouver l\'EC cédant pour annuler la compensation.', 'error');
        return;
    }
    Ouvrir_Boite_Alert_Encodage(
        'Voulez-vous vraiment annuler cette compensation ?',
        'warning',
        async () => {
            try {
                Ouvrir_Boite_Alert_Encodage('Annulation en cours...', 'info');
                const response = await fetch('API_PHP/Annuler_Compensation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        matricule: selectedCoteMatricule,
                        ec_beneficiaire: selectedCoteEcAligne,
                        ec_cedant: ec_cedant_id
                    })
                });
                const data = await response.json();
                Fermer_Boite_Alert_Encodage();
                if (data.success) {
                    Ouvrir_Boite_Alert_Encodage('Compensation annulée avec succès', 'success');
                    if (typeof Liste_Etudiants === 'function') Liste_Etudiants();
                    if (typeof Afficher_EC_aligne_deliberation === 'function') Afficher_EC_aligne_deliberation();
                } else {
                    Ouvrir_Boite_Alert_Encodage(data.message || 'Erreur lors de l\'annulation', 'error');
                }
            } catch (error) {
                Fermer_Boite_Alert_Encodage();
                Ouvrir_Boite_Alert_Encodage('Erreur lors de la communication avec le serveur', 'error');
            }
        }
    );
}




// ==================== Search Filter ====================
function normalizeString(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

function filterStudents() {
    const searchTerm = normalizeString(document.getElementById('search-student').value);
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const nameCell = row.querySelector('.sticky-col-nom');
        if (nameCell) {
            const studentName = normalizeString(nameCell.textContent);
            const matricule = row.dataset.matricule || '';
            
            if (studentName.includes(searchTerm) || matricule.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
    });

    // Mettre à jour le compteur d'étudiants visibles
    const etudiantsCountElement = document.getElementById('count-etudiants');
    if (etudiantsCountElement && searchTerm) {
        etudiantsCountElement.textContent = visibleCount;
    }
}

/* ******************** LA FONCTION QUI R2CUPERE LMES ETUDIANTS DANS UNE PROMOTION ***********/
/*async function Liste_Etudiants() {
  const response = await fetch('/api/ecAlignes', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ promotion, anneeAcad })
  });
  return response.json();
}*/

async function Liste_Etudiants() {
  const response = await fetch('API_PHP/Liste_etudiant_delib.php');
  return response.json();
}

// ==================== Badge Management ====================
function updateStats() {
    const etudiantsCount = document.querySelectorAll('#table_deliberation tbody tr').length;
    
    // Compter les ECs réels (colonnes td dans tr2 du thead, index 1)
    const thead = document.querySelector('#table_deliberation thead');
    const tr2 = thead ? thead.querySelectorAll('tr')[1] : null; // 2ème ligne (index 1)
    let ecsCount = 0;
    if (tr2) {
        const ecCells = tr2.querySelectorAll('td');
        // tr2 contient: 1 label "EC" + N noms d'ECs
        ecsCount = ecCells.length - 1; // -1 pour exclure le label "EC"
    }
    
    // Compter les côtes réellement remplies (cellules éditables avec valeur)
    const allEditableCells = document.querySelectorAll('#table_deliberation tbody .cell-editable');
    const cotesCount = Array.from(allEditableCells)
        .filter(cell => {
            const value = cell.textContent.trim();
            return value !== '' && value !== null && value !== undefined;
        }).length;
    
    
    document.getElementById('count-etudiants').textContent = etudiantsCount;
    document.getElementById('count-ecs').textContent = ecsCount;
    document.getElementById('count-cotes').textContent = cotesCount;
}

function updateCotesCount(increment) {
    const badge = document.getElementById('count-cotes');
    if (!badge) return;
    
    const currentCount = parseInt(badge.textContent) || 0;
    const newCount = currentCount + increment;
    
    badge.textContent = newCount;
    badge.classList.add('updating');
    setTimeout(() => badge.classList.remove('updating'), 300);
}

// ==================== API Calls ====================
async function Liste_Ec_Aligne(id_semestre) {
    const response = await fetch('API_PHP/Liste_EC_aligne_delibe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_semestre: id_semestre })
    });
    return response.json();
}

async function Liste_Cotes(id_semestre) {
    const response = await fetch('API_PHP/Liste_Cotes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_semestre: id_semestre })
    });
    return response.json();
}

async function Afficher_EC_aligne_deliberation() {
    // Récupération des données
    window.tab_ECs_aligne = await Liste_Ec_Aligne(cmb_semestre_encodage_delib.value);
    let tab_ECs_aligne = window.tab_ECs_aligne;
    let tab_etudiants_aligne = await Liste_Etudiants();
    tab_Cotes = await Liste_Cotes(cmb_semestre_encodage_delib.value);
    if (!Array.isArray(tab_Cotes)) {
        console.error("Erreur récupération des cotes :", tab_Cotes);
        tab_Cotes = [];
    }

    let table_encodage = document.getElementById("table_deliberation");
    table_encodage.innerHTML = '';
    
    // Utiliser DocumentFragment pour performance
    const fragment = document.createDocumentFragment();

    // ==================== THEAD (4 rows) ====================
    var thead = document.createElement("thead");
    thead.classList.add("sticky-sm-top", "m-0", "fw-bold", "text-center");

    var tr1 = document.createElement("tr"); // Entete 1: CUE + colonnes calculées
    tr1.style = "background-color:midnightblue; color:white;"

    var tr2 = document.createElement("tr"); // Entete 2: EC
    tr2.style = "background-color:white; color:black;"

    var tr3 = document.createElement("tr"); // Entete 3: CEC
    tr3.style = "background-color:midnightblue; color:white;"

    var tr4 = document.createElement("tr"); // Entete 4: MAX
    tr4.style = "background-color:midnightblue; color:white;"

    // Colonnes fixes (N° et Nom)
    var td11 = document.createElement("td");
    td11.rowSpan = 4;
    td11.textContent = "N°";
    td11.classList.add("text-center", "sticky-col-numero");
    td11.style = "background-color:midnightblue; color:white;"

    var td12 = document.createElement("td");
    td12.rowSpan = 4;
    td12.textContent = "Mat & Nom, Post, Prénom";
    td12.classList.add("text-center", "sticky-col-nom");
    td12.style = "background-color:midnightblue; color:white;"

    var td13 = document.createElement("td");
    td13.textContent = "CUE";
    td13.classList.add("text-center");
    td13.style = "background-color:midnightblue; color:white;"

    // Colonnes calculées (sticky à droite)
    var td14 = document.createElement("td");
    td14.rowSpan = 3;
    td14.textContent = "Crédits validés";
    td14.classList.add("text-start", "ec-header-modern");
    td14.style.backgroundColor = "midnightblue";
    td14.style.color = "white";
    td14.style.writingMode = "vertical-rl";
    td14.style.transform = "rotate(180deg)";
    td14.style.minWidth = "45px";
    td14.style.maxWidth = "45px";
    td14.style.width = "45px";
    td14.style.padding = "15px 8px";
    td14.style.height = "200px";
    td14.style.borderLeft = "2px solid #ffffff";
    td14.style.borderRight = "1px solid rgba(255, 255, 255, 0.2)";

    var td15 = document.createElement("td");
    td15.rowSpan = 3;
    td15.textContent = "Total notes pondérées";
    td15.classList.add("text-start", "ec-header-modern");
    td15.style.backgroundColor = "midnightblue";
    td15.style.color = "white";
    td15.style.writingMode = "vertical-rl";
    td15.style.transform = "rotate(180deg)";
    td15.style.minWidth = "45px";
    td15.style.maxWidth = "45px";
    td15.style.width = "45px";
    td15.style.padding = "15px 8px";
    td15.style.height = "200px";
    td15.style.borderRight = "1px solid rgba(255, 255, 255, 0.2)";


    // Récupérer le numéro du semestre sélectionné (affichage humain)
    let numSemestre = '';
    if (cmb_semestre_encodage_delib) {
        // Essayer de trouver le texte du semestre sélectionné
        const selectedOption = cmb_semestre_encodage_delib.options[cmb_semestre_encodage_delib.selectedIndex];
        if (selectedOption) {
            // Cherche un numéro dans le libellé (ex: S1, Semestre 1, etc.)
            const match = selectedOption.textContent.match(/([0-9]+)/);
            if (match) numSemestre = match[1];
            else numSemestre = cmb_semestre_encodage_delib.value;
        } else {
            numSemestre = cmb_semestre_encodage_delib.value;
        }
    }

    var td16 = document.createElement("td");
    td16.rowSpan = 3;
    td16.textContent = "Moyenne du SM " + numSemestre;
    td16.classList.add("text-start", "ec-header-modern");
    td16.style.backgroundColor = "midnightblue";
    td16.style.color = "white";
    td16.style.writingMode = "vertical-rl";
    td16.style.transform = "rotate(180deg)";
    td16.style.minWidth = "45px";
    td16.style.maxWidth = "45px";
    td16.style.width = "45px";
    td16.style.padding = "15px 8px";
    td16.style.height = "200px";
    td16.style.borderRight = "1px solid rgba(255, 255, 255, 0.2)";

    var td17 = document.createElement("td");
    td17.rowSpan = 3;
    td17.textContent = "Mention SM " + numSemestre;
    td17.classList.add("text-start", "ec-header-modern");
    td17.style.backgroundColor = "midnightblue";
    td17.style.color = "white";
    td17.style.writingMode = "vertical-rl";
    td17.style.transform = "rotate(180deg)";
    td17.style.minWidth = "45px";
    td17.style.maxWidth = "45px";
    td17.style.width = "45px";
    td17.style.padding = "15px 8px";
    td17.style.height = "200px";
    td17.style.borderRight = "1px solid rgba(255, 255, 255, 0.2)";

    var td18 = document.createElement("td");
    td18.rowSpan = 3;
    td18.textContent = "Décision SM " + numSemestre;
    td18.classList.add("text-start", "ec-header-modern");
    td18.style.backgroundColor = "midnightblue";
    td18.style.color = "white";
    td18.style.writingMode = "vertical-rl";
    td18.style.transform = "rotate(180deg)";
    td18.style.minWidth = "45px";
    td18.style.maxWidth = "45px";
    td18.style.width = "45px";
    td18.style.padding = "15px 8px";
    td18.style.height = "200px";
    td18.style.borderRight = "1px solid rgba(255, 255, 255, 0.2)";

    tr1.appendChild(td11);
    tr1.appendChild(td12);
    tr1.appendChild(td13);

    var td4 = document.createElement("td");
    td4.textContent = "EC";
    td4.classList.add("text-center");
    td4.style = "background-color:midnightblue; color:white;"
    tr2.appendChild(td4);

    var td5 = document.createElement("td");
    td5.textContent = "CEC";
    td5.classList.add("text-center");
    tr3.appendChild(td5);

    var td6 = document.createElement("td");
    td6.textContent = "MAX";
    td6.classList.add("text-center");
    tr4.appendChild(td6);


    // ==================== Boucle UE (colspan grouping) ====================
    let precedent_code_ue = null;

    tab_ECs_aligne.forEach(ue_aligne => {
        if (ue_aligne.cd_ue !== precedent_code_ue) { 
            const td_cue = document.createElement('td');
            td_cue.textContent = ue_aligne.cd_ue;
            td_cue.classList.add("text-center"); 
            td_cue.colSpan = ue_aligne.nombre_ec;
            tr1.appendChild(td_cue);
            precedent_code_ue = ue_aligne.cd_ue;
        }
    });

    // ==================== Boucle ECs ====================
    let tab_ec = [];
    tab_ECs_aligne.forEach(ec_s_aligne => {
        tab_ec.push(ec_s_aligne.id_ec_aligne);

        const td_ec = document.createElement('td');
        td_ec.textContent = ec_s_aligne.Intutile_ec;
        td_ec.classList.add("text-start", "ec-header-modern");
        td_ec.style.writingMode = "vertical-rl";
        td_ec.style.transform = "rotate(180deg)";
        td_ec.style.minWidth = "45px";
        td_ec.style.maxWidth = "45px";
        td_ec.style.width = "45px";
        td_ec.style.padding = "15px 8px";
        td_ec.style.height = "200px";
        td_ec.style.whiteSpace = "nowrap";
        td_ec.style.overflow = "hidden";
        td_ec.style.textOverflow = "ellipsis";
        td_ec.title = ec_s_aligne.Intutile_ec; // Tooltip pour voir le nom complet

        const td_ec_credit = document.createElement('td');
        td_ec_credit.textContent = ec_s_aligne.Credit;
        td_ec_credit.classList.add("text-center", "credit-cell-modern");
        td_ec_credit.style.minWidth = "45px";
        td_ec_credit.style.maxWidth = "45px";
        td_ec_credit.style.width = "45px";
        td_ec_credit.style.fontWeight = "700";
        td_ec_credit.style.fontSize = "14px";

        const td_ec_max = document.createElement('td');
        td_ec_max.textContent = 20;
        td_ec_max.classList.add("text-center", "max-cell-modern");
        td_ec_max.style.minWidth = "45px";
        td_ec_max.style.maxWidth = "45px";
        td_ec_max.style.width = "45px";
        td_ec_max.style.fontWeight = "700";
        td_ec_max.style.fontSize = "14px";

        tr2.appendChild(td_ec);
        tr3.appendChild(td_ec_credit);
        tr4.appendChild(td_ec_max);
    });

    // ==================== Ajouter les colonnes calculées ====================
    tr1.appendChild(td14);  // Crédits validés (rowSpan=3 fusionne sur 3 lignes)
    tr1.appendChild(td15);  // Total notes pondérées
    tr1.appendChild(td16);  // Moyenne du semestre
    tr1.appendChild(td17);  // Mention
    tr1.appendChild(td18);  // Décision

    // ==================== Ligne MAX pour les colonnes calculées ====================
    // MAX Crédits validés = Somme de tous les crédits des ECs
    const td_max_credits = document.createElement('td');
    let maxCredits = 0;
    tab_ECs_aligne.forEach(ec => {
        maxCredits += parseFloat(ec.Credit);
    });
    td_max_credits.textContent = maxCredits;
    td_max_credits.classList.add("text-center", "max-cell-modern");
    td_max_credits.style.minWidth = "45px";
    td_max_credits.style.maxWidth = "45px";
    td_max_credits.style.width = "45px";
    td_max_credits.style.fontWeight = "700";
    td_max_credits.style.fontSize = "14px";
    tr4.appendChild(td_max_credits);

    // MAX Total notes pondérées = Somme de (20 × crédit de chaque EC)
    const td_max_total_pondere = document.createElement('td');
    let maxTotalPondere = 0;
    tab_ECs_aligne.forEach(ec => {
        maxTotalPondere += 20 * parseFloat(ec.Credit);
    });
    td_max_total_pondere.textContent = maxTotalPondere;
    td_max_total_pondere.classList.add("text-center", "max-cell-modern");
    td_max_total_pondere.style.minWidth = "45px";
    td_max_total_pondere.style.maxWidth = "45px";
    td_max_total_pondere.style.width = "45px";
    td_max_total_pondere.style.fontWeight = "700";
    td_max_total_pondere.style.fontSize = "14px";
    tr4.appendChild(td_max_total_pondere);

    // MAX Moyenne = 20
    const td_max_moyenne = document.createElement('td');
    td_max_moyenne.textContent = "20";
    td_max_moyenne.classList.add("text-center", "max-cell-modern");
    td_max_moyenne.style.minWidth = "45px";
    td_max_moyenne.style.maxWidth = "45px";
    td_max_moyenne.style.width = "45px";
    td_max_moyenne.style.fontWeight = "700";
    td_max_moyenne.style.fontSize = "14px";
    tr4.appendChild(td_max_moyenne);

    // MAX Mention = vide (pas de MAX pour mention)
    const td_max_mention = document.createElement('td');
    td_max_mention.textContent = "";
    td_max_mention.classList.add("text-center", "max-cell-modern");
    td_max_mention.style.minWidth = "45px";
    td_max_mention.style.maxWidth = "45px";
    td_max_mention.style.width = "45px";
    tr4.appendChild(td_max_mention);

    // MAX Décision = vide (pas de MAX pour décision)
    const td_max_decision = document.createElement('td');
    td_max_decision.textContent = "";
    td_max_decision.classList.add("text-center", "max-cell-modern");
    td_max_decision.style.minWidth = "45px";
    td_max_decision.style.maxWidth = "45px";
    td_max_decision.style.width = "45px";
    tr4.appendChild(td_max_decision);

    thead.appendChild(tr1);
    thead.appendChild(tr2);
    thead.appendChild(tr3);
    thead.appendChild(tr4);

    // ==================== TBODY ====================
    var tbody = document.createElement("tbody");

    var i = 1;
    tab_etudiants_aligne.forEach(etudiant => {
        var tr = document.createElement("tr");
        tr.dataset.matricule = etudiant.Matricule;

        const tdnum = document.createElement("td");
        tdnum.textContent = i;
        tdnum.classList.add("text-center", "col-md-auto");
        tdnum.style.minWidth = "50px";
        tdnum.style.maxWidth = "50px";
        tdnum.style.width = "50px";
        tdnum.title = `Étudiant N°${i}`;

        const td_etudiant = document.createElement('td');
        td_etudiant.classList.add("text-start");
        td_etudiant.style.minWidth = "280px";
        td_etudiant.style.maxWidth = "280px";
        td_etudiant.style.width = "280px";
        
        // Conteneur pour nom + matricule avec retour à la ligne
        const nameDiv = document.createElement('div');
        nameDiv.style.display = "flex";
        nameDiv.style.flexDirection = "column";
        nameDiv.style.gap = "2px";
        nameDiv.style.cursor = "context-menu";
        nameDiv.classList.add("student-cell");
        
        // Info-bulle complète pour l'étudiant
        nameDiv.title = `${etudiant.ident_etudiant}\nMatricule: ${etudiant.Matricule}\n\n💡 Clic droit pour plus d'options`;
        
        // Créer le nom (en premier)
        const nameSpan = document.createElement('span');
        nameSpan.textContent = etudiant.ident_etudiant;
        nameSpan.classList.add("student-name");
        nameSpan.style.display = "block";
        
        // Créer le matricule (en dessous - simple texte bleu)
        const matriculeSpan = document.createElement('span');
        matriculeSpan.textContent = etudiant.Matricule;
        matriculeSpan.classList.add("student-matricule");
        
        nameDiv.appendChild(nameSpan);
        nameDiv.appendChild(matriculeSpan);
        td_etudiant.appendChild(nameDiv);

        const td_vide = document.createElement('td');
        td_vide.classList.add("separator-column-sticky");
        td_vide.style.backgroundColor = "midnightblue";
        td_vide.style.color = "white";
        td_vide.style.position = "sticky";
        td_vide.style.left = "330px";
        td_vide.style.zIndex = "5";
        td_vide.style.minWidth = "60px";
        td_vide.style.maxWidth = "60px";
        td_vide.style.width = "60px";

        tr.appendChild(tdnum);
        tr.appendChild(td_etudiant);
        tr.appendChild(td_vide);
        

        // Boucle pour afficher les notes (ÉDITABLE - avec DIV comme Encodage)
        tab_ECs_aligne.forEach((ec_s_aligne, index) => {
            const td_note = document.createElement('td');
            td_note.classList.add("text-center", "cell-grade");

            // Créer un div ÉDITABLE pour la note
            const editableCell = document.createElement('div');
            editableCell.contentEditable = "true";
            editableCell.spellcheck = false;
            editableCell.classList.add('cell-editable');
            editableCell.dataset.matricule = etudiant.Matricule;
            editableCell.dataset.ecAligne = ec_s_aligne.id_ec_aligne;
            editableCell.dataset.valeurOriginale = "";

            // Vérifier si une cote existe
            let cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec_s_aligne.id_ec_aligne);

            // Affichage spécial pour la compensation
            let valeurAffichee = '';
            let isCompensee = false;
            if (cote) {
                // Si la cote est compensée (EC déficitaire), on affiche la cote compensée et badge
                if (cote.cote_compensee !== undefined && cote.cote_compensee !== null && cote.cote_compensee !== "") {
                    valeurAffichee = cote.cote_compensee;
                    isCompensee = true;
                }
                // Sinon, si l'EC a cédé des points, on affiche la cote restante après cession et badge
                else if (cote.cote_reste_apres_cedee !== undefined && cote.cote_reste_apres_cedee !== null && cote.cote_reste_apres_cedee !== "" && cote.cote_reste_apres_cedee !== cote.Cote) {
                    valeurAffichee = cote.cote_reste_apres_cedee;
                    isCompensee = false;
                }
                // Sinon, cote normale
                else if (cote.Cote !== undefined && cote.Cote !== null && cote.Cote !== "") {
                    valeurAffichee = cote.Cote;
                }
            }

            if (valeurAffichee !== '') {
                editableCell.textContent = valeurAffichee;
                editableCell.dataset.valeurOriginale = valeurAffichee;
                editableCell.dataset.coteExiste = "true";
                applyCellColorDelib(editableCell, valeurAffichee);
                if (isCompensee) {
                    // Optimisation de l'affichage : flex colonne, centré
                    editableCell.classList.add('cote-compensee-badge');
                    editableCell.title = 'Cote compensée';
                    editableCell.textContent = '';
                    editableCell.style.display = 'flex';
                    editableCell.style.flexDirection = 'column';
                    editableCell.style.alignItems = 'center';
                    editableCell.style.justifyContent = 'center';
                    editableCell.style.padding = '2px 0';
                    // Cote centrée
                    const coteSpan = document.createElement('span');
                    coteSpan.textContent = valeurAffichee;
                    coteSpan.style.fontWeight = 'bold';
                    coteSpan.style.fontSize = '1em';
                    coteSpan.style.display = 'block';
                    editableCell.appendChild(coteSpan);
                    // Badge en dessous
                    const badge = document.createElement('span');
                    badge.textContent = 'Comp.';
                    badge.className = 'badge bg-warning mt-1';
                    badge.style.fontSize = '0.7em';
                    badge.style.marginTop = '2px';
                    badge.style.display = 'inline-block';
                    editableCell.appendChild(badge);
                } else if (cote.cote_reste_apres_cedee !== undefined && cote.cote_reste_apres_cedee !== null && cote.cote_reste_apres_cedee !== "" && cote.cote_reste_apres_cedee !== cote.Cote) {
                    // Optimisation de l'affichage : flex colonne, centré
                    editableCell.classList.add('cote-cedee-badge');
                    editableCell.title = 'EC a cédé des points';
                    editableCell.textContent = '';
                    editableCell.style.display = 'flex';
                    editableCell.style.flexDirection = 'column';
                    editableCell.style.alignItems = 'center';
                    editableCell.style.justifyContent = 'center';
                    editableCell.style.padding = '2px 0';
                    // Cote centrée
                    const coteSpan = document.createElement('span');
                    coteSpan.textContent = valeurAffichee;
                    coteSpan.style.fontWeight = 'bold';
                    coteSpan.style.fontSize = '1em';
                    coteSpan.style.display = 'block';
                    editableCell.appendChild(coteSpan);
                    // Badge en dessous
                    const badgeCede = document.createElement('span');
                    badgeCede.textContent = 'Cédé';
                    badgeCede.className = 'badge bg-info mt-1';
                    badgeCede.style.fontSize = '0.7em';
                    badgeCede.style.marginTop = '2px';
                    badgeCede.style.display = 'inline-block';
                    editableCell.appendChild(badgeCede);
                }
            } else {
                editableCell.dataset.coteExiste = "false";
            }

            // Focus : sélectionner tout le contenu
            editableCell.addEventListener('focus', (e) => {
                e.target.classList.add('focused');
                
                // Mettre en évidence la ligne - tous les TD de la ligne
                const td = e.target.parentElement;
                const row = td.parentElement;
                const table = row.closest('table');
                const tbody = table.querySelector('tbody');
                
                // Surligner TOUTE la ligne (tous les children = TD)
                Array.from(row.children).forEach(cell => {
                    cell.classList.add('row-highlight');
                });
                
                // Calculer l'index de colonne réel
                let realColIndex = 0;
                for (let i = 0; i < row.children.length; i++) {
                    if (row.children[i] === td) {
                        realColIndex = i;
                        break;
                    }
                }
                
                // Surligner la colonne dans TOUTES les lignes du tbody
                if (tbody) {
                    tbody.querySelectorAll('tr').forEach(bodyRow => {
                        const targetCell = bodyRow.children[realColIndex];
                        if (targetCell) {
                            targetCell.classList.add('col-highlight');
                        }
                    });
                }
                
                // Marquer la cellule active (intersection)
                td.classList.add('cell-active');
                
                const range = document.createRange();
                range.selectNodeContents(e.target);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            });

            // Blur : valider et sauvegarder
            editableCell.addEventListener('blur', (e) => {
                e.target.classList.remove('focused');
                
                // Retirer toutes les surbrillances
                const table = e.target.closest('table');
                if (table) {
                    table.querySelectorAll('.row-highlight').forEach(el => el.classList.remove('row-highlight'));
                    table.querySelectorAll('.col-highlight').forEach(el => el.classList.remove('col-highlight'));
                    table.querySelectorAll('.cell-active').forEach(el => el.classList.remove('cell-active'));
                }
                
                const valeurOriginale = e.target.dataset.valeurOriginale;
                let nouvelleValeur = e.target.textContent.trim().replace(',', '.');
                
                // Validation
                if (nouvelleValeur !== "") {
                    const nombre = parseFloat(nouvelleValeur);
                    if (isNaN(nombre)) {
                        e.target.textContent = valeurOriginale;
                        alert('⚠️ Veuillez entrer un nombre valide');
                        return;
                    }
                    nouvelleValeur = nombre.toString();
                    e.target.textContent = nouvelleValeur;
                }
                
                // Appliquer couleur et réafficher badge si compensée/cédée
                if (nouvelleValeur !== "") {
                    applyCellColorDelib(e.target, nouvelleValeur);
                    // Si la cellule est compensée ou cédée, régénérer l'affichage spécial
                    const coteObj = tab_Cotes.find(c => String(c.Matricule) === String(e.target.dataset.matricule) && String(c.id_ec_aligne) === String(e.target.dataset.ecAligne));
                    if (coteObj) {
                        // Effacer le contenu
                        e.target.textContent = '';
                        // Cote compensée
                        if (coteObj.cote_compensee !== undefined && coteObj.cote_compensee !== null && coteObj.cote_compensee !== "") {
                            e.target.classList.add('cote-compensee-badge');
                            e.target.title = 'Cote compensée';
                            e.target.style.display = 'flex';
                            e.target.style.flexDirection = 'column';
                            e.target.style.alignItems = 'center';
                            e.target.style.justifyContent = 'center';
                            e.target.style.padding = '2px 0';
                            // Cote centrée
                            const coteSpan = document.createElement('span');
                            coteSpan.textContent = coteObj.cote_compensee;
                            coteSpan.style.fontWeight = 'bold';
                            coteSpan.style.fontSize = '1em';
                            coteSpan.style.display = 'block';
                            e.target.appendChild(coteSpan);
                            // Badge en dessous
                            const badge = document.createElement('span');
                            badge.textContent = 'Comp.';
                            badge.className = 'badge bg-warning mt-1';
                            badge.style.fontSize = '0.7em';
                            badge.style.marginTop = '2px';
                            badge.style.display = 'inline-block';
                            e.target.appendChild(badge);
                        } else if (coteObj.cote_reste_apres_cedee !== undefined && coteObj.cote_reste_apres_cedee !== null && coteObj.cote_reste_apres_cedee !== "" && coteObj.cote_reste_apres_cedee !== coteObj.Cote) {
                            e.target.classList.add('cote-cedee-badge');
                            e.target.title = 'EC a cédé des points';
                            e.target.style.display = 'flex';
                            e.target.style.flexDirection = 'column';
                            e.target.style.alignItems = 'center';
                            e.target.style.justifyContent = 'center';
                            e.target.style.padding = '2px 0';
                            // Cote centrée
                            const coteSpan = document.createElement('span');
                            coteSpan.textContent = coteObj.cote_reste_apres_cedee;
                            coteSpan.style.fontWeight = 'bold';
                            coteSpan.style.fontSize = '1em';
                            coteSpan.style.display = 'block';
                            e.target.appendChild(coteSpan);
                            // Badge en dessous
                            const badgeCede = document.createElement('span');
                            badgeCede.textContent = 'Cédé';
                            badgeCede.className = 'badge bg-info mt-1';
                            badgeCede.style.fontSize = '0.7em';
                            badgeCede.style.marginTop = '2px';
                            badgeCede.style.display = 'inline-block';
                            e.target.appendChild(badgeCede);
                        } else {
                            // Sinon, valeur simple
                            e.target.textContent = nouvelleValeur;
                        }
                    }
                } else {
                    applyCellColorDelib(e.target, "");
                }
                
                // Sauvegarder si changement
                const matricule = e.target.dataset.matricule;
                const ecAligne = e.target.dataset.ecAligne;
                const coteExiste = e.target.dataset.coteExiste === "true";
                if (nouvelleValeur !== valeurOriginale) {
                    if (coteExiste && nouvelleValeur === "") {
                        Suppression(matricule, ecAligne);
                        e.target.dataset.coteExiste = "false";
                    } else if (coteExiste && nouvelleValeur !== "") {
                        Modifier_cote(matricule, ecAligne, nouvelleValeur);
                    } else if (!coteExiste && nouvelleValeur !== "") {
                        Ajout_point_Obtenu(matricule, ecAligne, nouvelleValeur);
                        e.target.dataset.coteExiste = "true";
                    }
                    e.target.dataset.valeurOriginale = nouvelleValeur;
                }
                // Toujours recalculer la décision dynamiquement
                recalculerCreditsValides(tr, tab_ECs_aligne, tab_Cotes);
            });

            // Validation en temps réel
            editableCell.addEventListener('input', (e) => {
                let texte = e.target.textContent;
                texte = texte.replace(/[^0-9.,]/g, '').replace(',', '.');
                const parts = texte.split('.');
                if (parts.length > 2) texte = parts[0] + '.' + parts.slice(1).join('');
                if (parts[1] && parts[1].length > 2) texte = parts[0] + '.' + parts[1].substring(0, 2);
                
                if (e.target.textContent !== texte) {
                    const sel = window.getSelection();
                    const offset = sel.getRangeAt(0).startOffset;
                    e.target.textContent = texte;
                    if (e.target.childNodes[0]) {
                        const range = document.createRange();
                        range.setStart(e.target.childNodes[0], Math.min(offset, texte.length));
                        range.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                }
            });

            // Navigation clavier avec les touches directionnelles
            editableCell.addEventListener('keydown', (e) => {
                const cell = e.target;
                const td = cell.parentElement;
                const row = td.parentElement;
                const rowIndex = Array.from(row.parentElement.children).indexOf(row);
                const colIndex = Array.from(row.children).indexOf(td);

                // Enter : valider et descendre
                if (e.key === 'Enter') {
                    e.preventDefault();
                    cell.blur();
                    navigateCellDelib(row.parentElement, rowIndex + 1, colIndex);
                    return;
                }

                // Tab : valider et aller à droite/gauche
                if (e.key === 'Tab') {
                    e.preventDefault();
                    cell.blur();
                    navigateCellDelib(row.parentElement, rowIndex, colIndex + (e.shiftKey ? -1 : 1));
                    return;
                }

                // Flèches : TOUJOURS sauvegarder et naviguer
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                    
                    // Sauvegarder manuellement avant de naviguer
                    const valeurOriginale = cell.dataset.valeurOriginale;
                    let nouvelleValeur = cell.textContent.trim().replace(',', '.');
                    
                    // Validation
                    if (nouvelleValeur !== "") {
                        const nombre = parseFloat(nouvelleValeur);
                        if (isNaN(nombre)) {
                            cell.textContent = valeurOriginale;
                            return;
                        }
                        nouvelleValeur = nombre.toString();
                        cell.textContent = nouvelleValeur;
                    }
                    
                    // Appliquer couleur
                    if (nouvelleValeur !== "") {
                        applyCellColorDelib(cell, nouvelleValeur);
                    } else {
                        applyCellColorDelib(cell, "");
                    }
                    
                    // Sauvegarder si changement
                    if (nouvelleValeur !== valeurOriginale) {
                        const matricule = cell.dataset.matricule;
                        const ecAligne = cell.dataset.ecAligne;
                        const coteExiste = cell.dataset.coteExiste === "true";
                        
                        if (coteExiste && nouvelleValeur === "") {
                            Suppression(matricule, ecAligne);
                            cell.dataset.coteExiste = "false";
                        } else if (coteExiste && nouvelleValeur !== "") {
                            Modifier_cote(matricule, ecAligne, nouvelleValeur);
                        } else if (!coteExiste && nouvelleValeur !== "") {
                            Ajout_point_Obtenu(matricule, ecAligne, nouvelleValeur);
                            cell.dataset.coteExiste = "true";
                        }
                        cell.dataset.valeurOriginale = nouvelleValeur;
                        
                        // Recalculer les crédits validés
                        recalculerCreditsValides(row, tab_ECs_aligne, tab_Cotes);
                    }
                    
                    // Naviguer vers la cellule suivante
                    const dir = {
                        'ArrowUp': [-1, 0], 
                        'ArrowDown': [1, 0],
                        'ArrowLeft': [0, -1], 
                        'ArrowRight': [0, 1]
                    }[e.key];
                    
                    // Retirer le focus et naviguer immédiatement
                    cell.blur();
                    setTimeout(() => {
                        navigateCellDelib(row.parentElement, rowIndex + dir[0], colIndex + dir[1]);
                    }, 50);
                }
            });

            td_note.appendChild(editableCell);
            tr.appendChild(td_note);
        });

        // ==================== Ajouter les colonnes calculées pour l'étudiant ====================
        // Crédits validés - Calculer la somme des crédits pour les notes >= 10
        const td_credits_valides = document.createElement('td');
        td_credits_valides.classList.add("text-center", "cell-calculated");
        
        let totalCreditsValidesDecision = 0;
        tab_ECs_aligne.forEach((ec_s_aligne) => {
            // Trouver la cote de l'étudiant pour cet EC
            let cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec_s_aligne.id_ec_aligne);
            let valeurPourCredit = null;
            if (cote) {
                if (cote.cote_compensee !== undefined && cote.cote_compensee !== null && cote.cote_compensee !== "") {
                    valeurPourCredit = parseFloat(cote.cote_compensee);
                } else if (cote.cote_reste_apres_cedee !== undefined && cote.cote_reste_apres_cedee !== null && cote.cote_reste_apres_cedee !== "" && cote.cote_reste_apres_cedee !== cote.Cote) {
                    valeurPourCredit = parseFloat(cote.cote_reste_apres_cedee);
                } else if (cote.Cote !== undefined && cote.Cote !== null && cote.Cote !== "") {
                    valeurPourCredit = parseFloat(cote.Cote);
                }
            }
            if (valeurPourCredit !== null && !isNaN(valeurPourCredit) && valeurPourCredit >= 10) {
                totalCreditsValidesDecision += parseFloat(ec_s_aligne.Credit);
            }
        });
        
        td_credits_valides.textContent = totalCreditsValidesDecision;
        td_credits_valides.style.fontWeight = "700";
        td_credits_valides.style.color = totalCreditsValidesDecision >= 30 ? "#2ecc71" : "#e74c3c"; // Vert si >= 30, rouge sinon
        tr.appendChild(td_credits_valides);

        // Total notes pondérées = Somme de (cote × crédit de chaque EC)
        const td_total_pondere = document.createElement('td');
        td_total_pondere.classList.add("text-center", "cell-calculated");
        
        let totalPondere = 0;
        tab_ECs_aligne.forEach((ec_s_aligne) => {
            let cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec_s_aligne.id_ec_aligne);
            
            if (cote && cote.Cote !== "" && cote.Cote !== null) {
                let numericCote = parseFloat(cote.Cote);
                if (!isNaN(numericCote)) {
                    totalPondere += numericCote * parseFloat(ec_s_aligne.Credit);
                }
            }
        });
        
        td_total_pondere.textContent = totalPondere % 1 === 0 ? totalPondere.toString() : totalPondere.toFixed(2);
        td_total_pondere.style.fontWeight = "700";
        tr.appendChild(td_total_pondere);

        // Moyenne du semestre = (Total pondéré étudiant / Total MAX pondéré) * 20
        const td_moyenne = document.createElement('td');
        td_moyenne.classList.add("text-center", "cell-calculated");
        
        let moyenne = 0;
        if (maxTotalPondere > 0 && totalPondere > 0) {
            moyenne = (totalPondere / maxTotalPondere) * 20;
        }
        
        td_moyenne.textContent = moyenne % 1 === 0 ? moyenne.toString() : moyenne.toFixed(2);
        td_moyenne.style.fontWeight = "700";
        tr.appendChild(td_moyenne);

        // Mention
        const td_mention = document.createElement('td');
        td_mention.classList.add("text-center", "cell-calculated");
        const mention = calculerMention(moyenne);
        td_mention.textContent = mention;
        td_mention.style.fontWeight = "700";
        td_mention.style.fontSize = "16px";
        
        // Appliquer couleur selon mention (alternance beige/blanc)
        if (['A', 'C', 'E', 'G'].includes(mention)) {
            td_mention.style.backgroundColor = "#f5e6d3";
        }
        
        tr.appendChild(td_mention);

        // Décision
        const td_decision = document.createElement('td');
        td_decision.classList.add("text-center", "cell-calculated");

        // --- Logique décision du semestre ---
        // 1. DEF : s'il manque une note (absence de note sur un EC)
        let defaillant = false;
        let tousEcsValides = true;
        for (const ec of tab_ECs_aligne) {
            const cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec.id_ec_aligne);
            let valeurPourCredit = null;
            if (!cote || (!cote.Cote && !cote.cote_compensee && !cote.cote_reste_apres_cedee)) {
                defaillant = true;
                tousEcsValides = false;
                break;
            }
            if (cote) {
                if (cote.cote_compensee !== undefined && cote.cote_compensee !== null && cote.cote_compensee !== "") {
                    valeurPourCredit = parseFloat(cote.cote_compensee);
                } else if (cote.cote_reste_apres_cedee !== undefined && cote.cote_reste_apres_cedee !== null && cote.cote_reste_apres_cedee !== "" && cote.cote_reste_apres_cedee !== cote.Cote) {
                    valeurPourCredit = parseFloat(cote.cote_reste_apres_cedee);
                } else if (cote.Cote !== undefined && cote.Cote !== null && cote.Cote !== "") {
                    valeurPourCredit = parseFloat(cote.Cote);
                }
                if (valeurPourCredit === null || isNaN(valeurPourCredit) || valeurPourCredit < 10) {
                    tousEcsValides = false;
                }
            } else {
                tousEcsValides = false;
            }
        }

        // 2. Calculer crédits validés et s'il y a eu compensation
        let totalCreditsValides = 0;
        let aCompensation = false;
        for (const ec of tab_ECs_aligne) {
            const cote = tab_Cotes.find(c => c.Matricule === etudiant.Matricule && c.id_ec_aligne === ec.id_ec_aligne);
            let valeurPourCredit = null;
            if (cote) {
                if (cote.cote_compensee !== undefined && cote.cote_compensee !== null && cote.cote_compensee !== "") {
                    valeurPourCredit = parseFloat(cote.cote_compensee);
                    aCompensation = true;
                } else if (cote.cote_reste_apres_cedee !== undefined && cote.cote_reste_apres_cedee !== null && cote.cote_reste_apres_cedee !== "" && cote.cote_reste_apres_cedee !== cote.Cote) {
                    valeurPourCredit = parseFloat(cote.cote_reste_apres_cedee);
                } else if (cote.Cote !== undefined && cote.Cote !== null && cote.Cote !== "") {
                    valeurPourCredit = parseFloat(cote.Cote);
                }
            }
            if (valeurPourCredit !== null && !isNaN(valeurPourCredit) && valeurPourCredit >= 10) {
                totalCreditsValidesDecision += parseFloat(ec.Credit);
            }
        }
        // Crédit requis pour valider le semestre (ex: 30)
        let creditsRequis = 0;
        tab_ECs_aligne.forEach(ec => { creditsRequis += parseFloat(ec.Credit); });

        let decision = "-";
        if (defaillant) {
            decision = "DEF";
        } else if (totalCreditsValidesDecision >= creditsRequis && tousEcsValides) {
            // SVC : tous les EC validés (>=10 après compensation) ET au moins une compensation
            if (aCompensation) {
                decision = "SVC";
            } else {
                decision = "SM";
            }
        } else {
            decision = "SNV";
        }
        td_decision.textContent = decision;
        td_decision.style.fontWeight = "700";
        td_decision.style.fontSize = "16px";
        tr.appendChild(td_decision);

        tbody.appendChild(tr);
        i++;
    });

    fragment.appendChild(thead);
    fragment.appendChild(tbody);
    table_encodage.appendChild(fragment);
    table_encodage.classList.add("table-bordered");
    
    // Mettre à jour les badges (avec setTimeout pour s'assurer que le DOM est rendu)
    setTimeout(() => {
        updateStats();
    }, 100);
    checkTableOverflow();
}



function checkTableOverflow() {
    const container = document.querySelector('.table-container-encodage');
    if (container) {
        const isOverflowing = container.scrollWidth > container.clientWidth;
        container.classList.toggle('has-overflow', isOverflowing);
    }
}

// Appliquer les couleurs selon la note (Délibération)
function applyCellColorDelib(cell, valeur) {
    cell.classList.remove('note-echec', 'note-normal', 'note-excellent');
    
    if (valeur === "" || valeur === null || valeur === undefined) {
        cell.style.backgroundColor = "";
        cell.style.border = "";
        cell.style.animation = "";
        return;
    }
    
    const note = parseFloat(valeur);
    if (isNaN(note)) {
        cell.style.backgroundColor = "";
        cell.style.border = "";
        cell.style.animation = "";
        return;
    }
    
    if (note < 0 || note > 20) {
        cell.classList.add('note-excellent');
        cell.style.backgroundColor = "#ffa94d";
        cell.style.color = "white";
        cell.style.border = "2px solid #fd7e14";
    } else if (note < 10) {
        cell.classList.add('note-echec');
        cell.style.backgroundColor = "";
        cell.style.color = "";
        cell.style.border = "";
    } else {
        cell.classList.add('note-normal');
        cell.style.backgroundColor = "";
        cell.style.color = "";
        cell.style.border = "";
    }
}

// Calculer la mention selon le système CECT
function calculerMention(moyenne) {
    if (moyenne >= 18) return 'A';
    if (moyenne >= 16) return 'B';
    if (moyenne >= 14) return 'C';
    if (moyenne >= 12) return 'D';
    if (moyenne >= 10) return 'E';
    if (moyenne >= 8) return 'F';
    if (moyenne < 8) return 'G';
    return '-';
}

// Navigation entre cellules (Délibération)
function navigateCellDelib(tbody, targetRowIndex, targetColIndex) {
    const rows = tbody.querySelectorAll('tr');
    
    // Vérifier limites lignes
    if (targetRowIndex < 0 || targetRowIndex >= rows.length) return;
    
    const targetRow = rows[targetRowIndex];
    const targetTd = targetRow.children[targetColIndex];
    
    // Vérifier si c'est une cellule éditable
    if (!targetTd) return;
    
    const editableDiv = targetTd.querySelector('.cell-editable');
    if (editableDiv) {
        editableDiv.focus();
    }
}

// Recalculer les crédits validés et total pondéré d'un étudiant
function recalculerCreditsValides(row, tab_ECs_aligne, tab_Cotes) {
    const matricule = row.dataset.matricule;
    let totalCreditsValides = 0;
    let totalPondere = 0;
    
    // Récupérer toutes les cellules de notes de cette ligne
    const cellsNotes = row.querySelectorAll('.cell-editable');
    
    cellsNotes.forEach((cell, index) => {
        const valeur = cell.textContent.trim();
        if (valeur !== "") {
            const note = parseFloat(valeur);
            const ec = tab_ECs_aligne[index];
            
            if (!isNaN(note) && ec) {
                const credit = parseFloat(ec.Credit);
                
                // Total pondéré : somme de (note × crédit)
                totalPondere += note * credit;
                
                // Crédits validés : somme des crédits si note >= 10
                if (note >= 10) {
                    totalCreditsValides += credit;
                }
            }
        }
    });
    
    // Mettre à jour les cellules calculées
    const cellsCalculated = row.querySelectorAll('.cell-calculated');
    
    // Première cellule = Crédits validés
    if (cellsCalculated[0]) {
        cellsCalculated[0].textContent = totalCreditsValides;
        cellsCalculated[0].style.fontWeight = "700";
        cellsCalculated[0].style.color = totalCreditsValides >= 30 ? "#2ecc71" : "#e74c3c";
    }
    
    // Deuxième cellule = Total pondéré
    if (cellsCalculated[1]) {
        cellsCalculated[1].textContent = totalPondere % 1 === 0 ? totalPondere.toString() : totalPondere.toFixed(2);
        cellsCalculated[1].style.fontWeight = "700";
    }
    
    // Troisième cellule = Moyenne du semestre
    if (cellsCalculated[2]) {
        // Calculer maxTotalPondere
        let maxTotalPondere = 0;
        tab_ECs_aligne.forEach(ec => {
            maxTotalPondere += 20 * parseFloat(ec.Credit);
        });
        
        let moyenne = 0;
        if (maxTotalPondere > 0 && totalPondere > 0) {
            moyenne = (totalPondere / maxTotalPondere) * 20;
        }
        
        cellsCalculated[2].textContent = moyenne % 1 === 0 ? moyenne.toString() : moyenne.toFixed(2);
        cellsCalculated[2].style.fontWeight = "700";
    }
    
    // Quatrième cellule = Mention
    if (cellsCalculated[3]) {
        // Récupérer la moyenne de la cellule précédente
        const moyenneText = cellsCalculated[2] ? cellsCalculated[2].textContent : "0";
        const moyenne = parseFloat(moyenneText);
        
        const mention = calculerMention(moyenne);
        cellsCalculated[3].textContent = mention;
        cellsCalculated[3].style.fontWeight = "700";
        cellsCalculated[3].style.fontSize = "16px";
        
        // Appliquer couleur selon mention (alternance beige/blanc)
        if (['A', 'C', 'E', 'G'].includes(mention)) {
            cellsCalculated[3].style.backgroundColor = "#f5e6d3";
        } else {
            cellsCalculated[3].style.backgroundColor = "";
        }
    }

    // Cinquième cellule = Décision dynamique (même logique que crédits validés, moyenne, mention)
    if (cellsCalculated[4]) {
        let defaillant = false;
        let tousEcsValides = true;
        let totalCreditsValidesDecision = 0;
        let aCompensation = false;
        for (let i = 0; i < tab_ECs_aligne.length; i++) {
            const ec = tab_ECs_aligne[i];
            const cell = cellsNotes[i];
            const valeur = cell ? cell.textContent.trim() : "";
            if (valeur === "") {
                defaillant = true;
                tousEcsValides = false;
                break;
            }
            const note = parseFloat(valeur);
            if (isNaN(note)) {
                defaillant = true;
                tousEcsValides = false;
                break;
            }
            // Recherche la compensation dans tab_Cotes pour ce matricule et cet EC
            const coteObj = tab_Cotes.find(c => String(c.Matricule) === String(matricule) && String(c.id_ec_aligne) === String(ec.id_ec_aligne));
            if (coteObj && coteObj.cote_compensee !== undefined && coteObj.cote_compensee !== null && coteObj.cote_compensee !== "") {
                aCompensation = true;
            }
            if (note < 10) {
                tousEcsValides = false;
            } else {
                totalCreditsValidesDecision += parseFloat(ec.Credit);
            }
        }
        let creditsRequis = 0;
        tab_ECs_aligne.forEach(ec => { creditsRequis += parseFloat(ec.Credit); });
        let decision = "-";
        if (defaillant) {
            decision = "DEF";
        } else if (totalCreditsValidesDecision >= creditsRequis && tousEcsValides) {
            if (aCompensation) {
                decision = "SVC";
            } else {
                decision = "SM";
            }
        } else {
            decision = "SNV";
        }
        cellsCalculated[4].textContent = decision;
        cellsCalculated[4].style.fontWeight = "700";
        cellsCalculated[4].style.fontSize = "16px";
    }
}


async function Ajout_point_Obtenu(mat_etudiant, id_ec, cote) {
    if (cote !== "") {
        try {
            const response = await fetch("API_PHP/Ajout_Cote.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    "matricule": mat_etudiant,
                    "id_ec_aligne": id_ec,
                    "cote": cote
                })
            });
            const data = await response.json();
            
            if (data.status === "success") {
                console.log("✅ Côte ajoutée:", data.message);
                updateCotesCount(1); // Incrémenter le badge
            } else {
                console.error("❌ Erreur ajout:", data.message);
            }
        } catch (error) {
            console.error("❌ Erreur réseau:", error);
        }
    }
}

async function Suppression(mat_etudiant, id_ec_aligne) {
    try {
        const response = await fetch("API_PHP/Suppression_Cote.php", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                "matricule": mat_etudiant,
                "id_ec_aligne": id_ec_aligne
            })
        });
        const data = await response.json();
        
        if (data.status === "success") {
            console.log("✅ Côte supprimée:", data.message);
            updateCotesCount(-1); // Décrémenter le badge
        } else {
            console.error("❌ Erreur suppression:", data.message);
        }
    } catch (error) {
        console.error("❌ Erreur réseau:", error);
    }
}

async function Modifier_cote(mat_etudiant, id_ec, cote) {
    if (cote !== "") {
        try {
            const response = await fetch("API_PHP/Modifier_Cote.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    "matricule": mat_etudiant,
                    "id_ec_aligne": id_ec,
                    "cote": cote
                })
            });
            const data = await response.json();
            
            if (data.status === "success") {
                console.log("✅ Côte modifiée:", data.message);
                // Pas de changement de compteur lors de la modification
            } else {
                console.error("❌ Erreur modification:", data.message);
            }
        } catch (error) {
            console.error("❌ Erreur réseau:", error);
        }
    }
}
  



  