<?php

//session_start(); 

require('../FPDF/fpdf.php');

// =========================
// 🔹 Variables étudiant
// =========================
$nom = "KASONGO BULELA Robert";
$naissance = "Kananga, le 27/06/2005";
$matricule = "03319/23/KAN";
$annee_etude = "Licence (LMD)";
$annee_academique = "2024 – 2025";

// =========================
// 🔹 Tableau des UE & EC
// =========================
$ues = [
    [
        "code" => "IEB111",
        "intitule" => "INFORMATIQUE ET BUREAUTIQUE",
        "credit" => 7,
        "ecs" => [
            ["nom" => "Informatique générale et TIC", "note" => 8],
            ["nom" => "Bureautique (Pack Office)", "note" => 7]
        ],
        "moy_ue" => 8,
        "credits_valides" => 3
    ],
    [
        "code" => "LPR111",
        "intitule" => "ALGORITHMIQUE ET PROGRAMMATION",
        "credit" => 7,
        "ecs" => [
            ["nom" => "Programmation web 1", "note" => 12]
        ],
        "moy_ue" => 9,
        "credits_valides" => 3
    ],
    [
        "code" => "ARR111",
        "intitule" => "ARCHITECTURE ET RESEAU",
        "credit" => 6,
        "ecs" => [
            ["nom" => "Architecture des ordinateurs", "note" => 8],
            ["nom" => "Réseau informatique 1: Concepts de base", "note" => 9]
        ],
        "moy_ue" => 9,
        "credits_valides" => 2
    ],
    [
        "code" => "IFB111",
        "intitule" => "ECONOMIE",
        "credit" => 6,
        "ecs" => [
            ["nom" => "Comptabilité Générale", "note" => 11],
            ["nom" => "Introduction à l’Economie", "note" => 8]
        ],
        "moy_ue" => 8,
        "credits_valides" => 2
    ],
    [
        "code" => "DCR111",
        "intitule" => "DROIT ET CIVISME",
        "credit" => 4,
        "ecs" => [
            ["nom" => "Valeurs, principes et symboles", "note" => 8],
            ["nom" => "Droit civil et Législation sociale", "note" => 10]
        ],
        "moy_ue" => 9,
        "credits_valides" => 2
    ]
];

// =========================
// 🔹 Résultats globaux
// =========================
$moyenne_semestrielle = 10;
$mention = "F";
$total_credits_valides = "10 SUR 30";
$date_certification = "11 novembre 2025";
$doyen = "C.T. Nshola TSHILUMBA Monnassé";
$secretaire = "Prof. Martin BAYAMBA Kasonga";

// =========================
// 🔹 Génération PDF
// =========================
$pdf = new FPDF();
$pdf->AddPage();

// Charger une police UTF‑8 (DejaVuSans)
// ⚠️ Assure-toi d’avoir le fichier DejaVuSans.ttf dans ton projet
$pdf->AddFont('DejaVu','','DejaVuSans.ttf',true);
$pdf->SetFont('DejaVu','',12);

// En-tête institutionnel
$pdf->Cell(0,8,"MINISTÈRE DE L’ENSEIGNEMENT SUPÉRIEUR ET UNIVERSITAIRE",0,1,'C');
$pdf->Cell(0,8,"UNIVERSITÉ NOTRE-DAME DU KASAYI",0,1,'C');
$pdf->Cell(0,8,"Faculté des Sciences Informatiques",0,1,'C');
$pdf->Cell(0,8,"B.P. 70 KANANGA",0,1,'C');
$pdf->Cell(0,8,"République Démocratique du Congo",0,1,'C');
$pdf->Ln(5);
$pdf->Cell(0,8,"RELEVÉ DE NOTES / Premier semestre",0,1,'C');
$pdf->Ln(10);

// Infos étudiant
$pdf->SetFont('DejaVu','',11);
$pdf->Cell(0,8,"Nom de l’étudiant: $nom",0,1);
$pdf->Cell(0,8,"Lieu et date de naissance: $naissance",0,1);
$pdf->Cell(0,8,"Matricule: $matricule",0,1);
$pdf->Cell(0,8,"Année d’étude: $annee_etude",0,1);
$pdf->Cell(0,8,"Année Académique: $annee_academique",0,1);
$pdf->Ln(5);

// Tableau des notes
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(20,8,'Code UE',1,0,'C');
$pdf->Cell(70,8,'UE & Éléments Constitutifs',1,0,'C');
$pdf->Cell(15,8,'Crédit',1,0,'C');
$pdf->Cell(20,8,'Note EC/20',1,0,'C');
$pdf->Cell(20,8,'Moy UE/20',1,0,'C');
$pdf->Cell(25,8,'Crédits validés',1,1,'C');

$pdf->SetFont('DejaVu','',10);
foreach ($ues as $ue) {
    // Ligne UE
    $pdf->Cell(20,8,$ue['code'],1);
    $pdf->Cell(70,8,$ue['intitule'],1);
    $pdf->Cell(15,8,$ue['credit'],1,0,'C');
    $pdf->Cell(20,8,'',1,0,'C');
    $pdf->Cell(20,8,'',1,0,'C');
    $pdf->Cell(25,8,$ue['credits_valides'] == 0 ? '0' : '',1,1,'C');

    // Lignes EC
    foreach ($ue['ecs'] as $ec) {
        $pdf->Cell(20,8,'',1);
        $pdf->Cell(70,8,$ec['nom'],1);
        $pdf->Cell(15,8,'',1);
        $pdf->Cell(20,8,$ec['note'],1,0,'C');
        $pdf->Cell(20,8,'',1);
        $pdf->Cell(25,8,'',1,1);
    }

    // Moyenne UE
    $pdf->Cell(20,8,'',1);
    $pdf->Cell(70,8,'',1);
    $pdf->Cell(15,8,'',1);
    $pdf->Cell(20,8,'',1);
    $pdf->Cell(20,8,$ue['moy_ue'],1,0,'C');
    $pdf->Cell(25,8,$ue['credits_valides'],1,1,'C');
}

// Résultats globaux
$pdf->Ln(5);
$pdf->Cell(0,8,"Moyenne Semestrielle: $moyenne_semestrielle",0,1);
$pdf->Cell(0,8,"Mention: $mention",0,1);
$pdf->Cell(0,8,"Crédits Validés: $total_credits_valides",0,1);

// Certification
$pdf->Ln(10);
$pdf->Cell(0,8,"Certifié exact d’après le registre de délibération",0,1);
$pdf->Cell(0,8,"Fait à Kananga, le $date_certification",0,1);
$pdf->Ln(8);
$pdf->Cell(0,8,"Le Doyen de la Faculté: $doyen",0,1);
$pdf->Cell(0,8,"Le Secrétaire Général Académique: $secretaire",0,1);

$pdf->Output();
?>
