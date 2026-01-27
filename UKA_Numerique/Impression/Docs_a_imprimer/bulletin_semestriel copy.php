
<?php
// ==================== PARAMÈTRES ET VARIABLES GLOBALES ====================
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
// ini_set('display_errors', 0);

// ==================== SESSION POUR FACULTE ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$libelle_faculte = ' Faculté des ';
if (!empty($_SESSION['libelle_fac'])) {
    $libelle_faculte .= $_SESSION['libelle_fac'];
} elseif (!empty($_SESSION['id_fac'])) {
    // Si seul l'id_fac est disponible, on va chercher le libellé en base
    $stmt = $con->prepare("SELECT Libelle_faculte FROM faculte WHERE id_fac = ? LIMIT 1");
    $stmt->execute([$_SESSION['id_fac']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $libelle_faculte .= $row && !empty($row['Libelle_faculte']) ? $row['Libelle_faculte'] : '';
}
if (empty($libelle_faculte)) {
    $libelle_faculte = 'Faculté'; // Valeur par défaut si rien trouvé
}


require('../FPDF/fpdf.php');
include("../../../Connexion_BDD/Connexion_1.php");


// ==================== VARIABLES GLOBALES ====================
// Paramètres GET
$matricule         = isset($_GET['matricule']) ? $_GET['matricule'] : '';
$promotion         = isset($_GET['promotion']) ? $_GET['promotion'] : '';
$semestre          = isset($_GET['semestre']) ? $_GET['semestre'] : '';
$libelle_promotion = isset($_GET['libelle_promotion']) ? $_GET['libelle_promotion'] : '';

// Paramètres SESSION
$id_filiere        = isset($_SESSION['id_fac']) ? $_SESSION['id_fac'] : null;
$code_promotion    = isset($_SESSION['code_prom']) ? $_SESSION['code_prom'] : $promotion;
$id_annee_acad     = isset($_SESSION['id_annee_acad']) ? $_SESSION['id_annee_acad'] : (isset($_GET['annee']) ? $_GET['annee'] : null);

// Libellés
$libelle_annee     = $id_annee_acad;
$libelle_semestre  = $semestre;
if (empty($libelle_promotion) && $code_promotion) {
    // Si le libellé n'est pas passé en GET, on va le chercher en base
    $sql = "SELECT Libelle_promotion FROM promotion WHERE Code_promotion = :code_promotion LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':code_promotion', $code_promotion);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $libelle_promotion = $row['Libelle_promotion'];
    }
}


if (is_numeric($id_annee_acad)) 
{
    $sql = "SELECT Annee_debut, Annee_fin FROM annee_academique WHERE idAnnee_Acad = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id_annee_acad, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $libelle_annee = $row['Annee_debut'] . ' - ' . $row['Annee_fin'];
    }
}

if (!empty($semestre)) 
{
    // Si c'est un ID, on peut aller chercher le libellé en base si besoin
    // Mais si c'est déjà un label, on l'affiche directement
    // Ici, on tente de détecter si c'est un nombre (id) ou un texte
    if (is_numeric($semestre)) 
    {
        $stmt = $con->prepare("SELECT libelle_semestre FROM semestre WHERE Id_Semestre = ? LIMIT 1");
        $stmt->execute([$semestre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $libelle_semestre = $row && !empty($row['libelle_semestre']) ? $row['libelle_semestre'] : ("Semestre " . $semestre);
    } else 
    {
        $libelle_semestre = $semestre;
    }
} 
else {
    $libelle_semestre = "Semestre";
}

if ($matricule) {
    $sql = "SELECT Nom, Postnom, Prenom, DateNaissance, LieuNaissance FROM etudiant WHERE Matricule = :matricule LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nom_complet = trim($row['Nom'] . ' ' . $row['Postnom'] . ' ' . $row['Prenom']);
        $date_naissance = $row['DateNaissance'];
        $lieu_naissance = $row['LieuNaissance'];
    }
}

class RelevePDF extends FPDF {
    function DoBorder() {
        $this->SetLineWidth(0.5);
        $this->Rect(5, 5, 200, 287); 
    }

    function t($str) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
    }
}

$pdf = new RelevePDF();
$pdf->AddPage();
$pdf->DoBorder();

// Logos (optionnels)
if (file_exists('logo_uka.jpeg')) $pdf->Image('logo_uka.jpeg', 10, 10, 20);
if (file_exists('drapeau_rdc.png')) $pdf->Image('drapeau_rdc.png', 175, 10, 20);

// --- EN-TÊTE ---
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 4, $pdf->t("MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE"), 0, 1, 'C');
$pdf->Cell(0, 4, $pdf->t("UNIVERSITE NOTRE-DAME DU KASAYI"), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 4, $pdf->t($libelle_faculte), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 4, $pdf->t("B.P. 70 KANANGA"), 0, 1, 'C');
$pdf->Cell(0, 4, $pdf->t("République Démocratique du Congo"), 0, 1, 'C');


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 14);

$pdf->Cell(0, 10, $pdf->t("RELEVE DE NOTES / " . $libelle_semestre), 0, 1, 'C');



$pdf->SetFont('Arial', '', 8);
$y_info = $pdf->GetY();
$pdf->Rect(10, $y_info, 190, 18);
$pdf->SetXY(10, $y_info + 1);
$pdf->Cell(115, 5, $pdf->t(" Nom de l'étudiant : ") . $pdf->t($nom_complet ?? ''), 0, 0);
$pdf->Cell(75, 5, $pdf->t(" Promotion : ") . $pdf->t($libelle_promotion ?? ''), 0, 1);
$pdf->SetX(10);
$pdf->Cell(115, 5, $pdf->t(" Lieu et date de naissance : ") . $pdf->t($lieu_naissance ?? '') . ', ' . $pdf->t($date_naissance ?? ''), 0, 0);
$pdf->Cell(75, 5, $pdf->t(" Année académique : ") . $pdf->t($libelle_annee ?? ''), 0, 1);
$pdf->SetX(10);
$pdf->Cell(115, 5, $pdf->t(" Matricule : ") . $pdf->t($matricule ?? ''), 0, 0);
$pdf->Cell(75, 5, $pdf->t(" Semestre : ") . $pdf->t($libelle_semestre ?? ''), 0, 1);
$pdf->Line(125, $y_info, 125, $y_info + 18);

$pdf->SetY($y_info + 22);

// --- TABLEAU DES NOTES (Largeur totale 190mm) ---
$pdf->SetFont('Arial', 'B', 8);
// Définition des largeurs pour atteindre 190mm : 20 + 80 + 20 + 25 + 20 + 25 = 190
$w_code = 20;
$w_ue   = 102;
$w_cred = 16;
$w_note = 16;
$w_moy  = 18;
$w_val  = 18;



$x_header = $pdf->GetX();
$y_header = $pdf->GetY();

// Fonction pour simuler la hauteur d'un MultiCell
function simulateMultiCellHeight($pdf, $width, $text, $fontSize = 8) {
    $pdf->SetFont('Arial', '', $fontSize);
    $lines = explode("\n", wordwrap($text, floor($width / ($pdf->GetStringWidth('A') ?: 1)), "\n", true));
    return max(10, count($lines) * 5); // 5 est la hauteur approximative d'une ligne
}

// Refactorisation : titres et largeurs des colonnes
$headerColumns = [
    ['title' => $pdf->t('Code UE'), 'width' => $w_code],
    ['title' => $pdf->t('UE & Eléments Constitutifs'), 'width' => $w_ue],
    ['title' => $pdf->t('Crédit'), 'width' => $w_cred],
    ['title' => $pdf->t('Note EC/20'), 'width' => $w_note],
    ['title' => $pdf->t('Moy UE/20'), 'width' => $w_moy],
    ['title' => $pdf->t('Crédits validés'), 'width' => $w_val],
];

// Calcul de la hauteur maximale de l'en-tête
$headerMaxHeight = 0;
foreach ($headerColumns as $col) {
    $h = simulateMultiCellHeight($pdf, $col['width'], $col['title']);
    if ($h > $headerMaxHeight) $headerMaxHeight = $h;
}

// Affichage de l'en-tête du tableau
$x = $x_header;
$y = $y_header;
foreach ($headerColumns as $col) {
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($col['width'], $headerMaxHeight, $col['title'], 1, 'C');
    $x += $col['width'];
}
$pdf->SetXY($x_header, $y_header + $headerMaxHeight);

function DrawUERow($pdf, $code, $ue_name, $total_credit_ue, $ecs, $moy_ue, $credits_valides, $w) {
    $h = 7;
    $nb_ecs = count($ecs);
    $total_h = $h * ($nb_ecs + 1);
    $x = $pdf->GetX(); $y = $pdf->GetY();

    // --- STYLE MODERNE ---
    // Couleur de fond gris clair pour la ligne UE
    $pdf->SetFillColor(60, 60, 60); // Gris foncé pour Code UE
    $pdf->SetTextColor(255,255,255); // Texte blanc pour Code UE
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell($w[0], $total_h, $code, 1, 0, 'C', true);

    $pdf->SetFillColor(230, 230, 230); // Gris clair pour UE
    $pdf->SetTextColor(60,60,60); // Texte gris foncé pour UE
    $pdf->Cell($w[1], $h, $pdf->t($ue_name), 1, 0, 'L', true);
    $pdf->Cell($w[2], $h, $total_credit_ue, 1, 0, 'C', true);
    $pdf->Cell($w[3], $h, '', 1, 0, 'C', true);

    // Moyenne et Validés
    $pdf->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3], $y);
    $pdf->Cell($w[4], $total_h, $moy_ue, 1, 0, 'C', true);
    $pdf->Cell($w[5], $total_h, $credits_valides, 1, 1, 'C', true);

    // Eléments Constitutifs (fond blanc, texte gris foncé)
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetFillColor(255,255,255); // Blanc
    $pdf->SetTextColor(60,60,60); // Gris foncé
    $next_y = $y + $h;
    foreach ($ecs as $ec) {
        $pdf->SetXY($x + $w[0], $next_y);
        $pdf->Cell($w[1], $h, $pdf->t($ec[0]), 1, 0, 'L', true);
        $pdf->Cell($w[2], $h, $ec[1], 1, 0, 'C', true);
        $pdf->Cell($w[3], $h, $ec[2], 1, 1, 'C', true);
        $next_y += $h;
    }
    $pdf->SetY($next_y);
}


$widths = [$w_code, $w_ue, $w_cred, $w_note, $w_moy, $w_val];

// ==================== RÉCUPÉRATION DYNAMIQUE DES UE/EC ====================
// Appel de la procédure stockée Liste_EC_Aligne


/*// DEBUG : Afficher les paramètres envoyés à la procédure
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(255,0,0);
$pdf->Cell(0, 5, $pdf->t('DEBUG PARAMS : id_filiere=' . $id_filiere . ' | mat_agent=' . $matricule . ' | id_annee_acad=' . $id_annee_acad . ' | id_semestre=' . $semestre . ' | code_prom=' . $code_promotion), 0, 1, 'L');
$pdf->SetTextColor(0,0,0);
*/

// ==================== RÉCUPÉRATION DYNAMIQUE DES EC ALIGNÉS ====================
// Appel de la procédure stockée Liste_EC_aligner_delibee (comme dans la délibération JS)
$ues = [];
if ($code_promotion && $id_annee_acad && $semestre) 
{
    try 
    {
        $sql = "CALL Liste_EC_aligner_delibee(:promo_code, :annee_acad, :id_semestre)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':promo_code', $code_promotion);
        $stmt->bindParam(':annee_acad', $id_annee_acad, PDO::PARAM_INT);
        $stmt->bindParam(':id_semestre', $semestre, PDO::PARAM_INT);
        $stmt->execute();

        // Regrouper les EC par UE (cd_ue)
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $code_ue = isset($row['cd_ue']) ? $row['cd_ue'] : null;
            if (!$code_ue) continue;
            if (!isset($ues[$code_ue])) 
            {
                $ues[$code_ue] = [
                    'code' => $code_ue,
                    'intitule' => isset($row['Intitule_ue']) ? $row['Intitule_ue'] : '',
                    'credit_ue' => isset($row['total_credits']) ? $row['total_credits'] : '',
                    'ecs' => []
                ];
            }
                // Correction : récupération stricte du champ Intitule_ec
                $intitule_ec = isset($row['Intutile_ec']) ? $row['Intutile_ec'] : '';
                $credit_ec = isset($row['Credit']) ? $row['Credit'] : '';
                
                $ues[$code_ue]['ecs'][] = [
                    $intitule_ec,
                    $credit_ec,
                    '' // Note EC (à compléter si besoin)
                ];
        }
    } catch (PDOException $e) {
        $pdf->Cell(0, 10, $pdf->t('Erreur lors de la récupération des EC alignés : ' . $e->getMessage()), 0, 1, 'C');
    }
}


if (!empty($ues)) 
{
    foreach ($ues as $ue) {
        DrawUERow(
            $pdf,
            $ue['code'],
            $ue['intitule'],
            $ue['credit_ue'],
            $ue['ecs'],
            '', // moyenne UE non fournie
            '', // crédits validés non fournis
            $widths
        );
    }
} 
else 
{
    $pdf->Cell(0, 10, $pdf->t('Aucune UE/EC aligné trouvée pour les paramètres donnés.'), 1, 1, 'C');
}




// --- TOTAUX ---
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(165, 6, $pdf->t("Moyenne Semestrielle : 10"), 0, 1, 'R');
$pdf->Cell(165, 6, $pdf->t("Mention : F"), 0, 1, 'R');
$pdf->Cell(165, 6, $pdf->t("Crédits Validés : 10   SUR 30"), 0, 1, 'R');

// --- SIGNATURES ---
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 4, $pdf->t("Certifié exact d'après le registre de délibération"), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 4, $pdf->t("Fait à Kananga, le 11 novembre 2025"), 0, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'U', 9);
$pdf->Cell(95, 4, $pdf->t("Le Doyen de la Faculté"), 0, 0, 'L');
$pdf->Cell(95, 4, $pdf->t("Le Secrétaire Général Académique"), 0, 1, 'R');

$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(95, 4, $pdf->t("C.T. Nobla TSHILUMBA Monnasese"), 0, 0, 'L');
$pdf->Cell(95, 4, $pdf->t("Prof. Martin BAYAMBA Kasonga"), 0, 1, 'R');

$pdf->Output();
?>