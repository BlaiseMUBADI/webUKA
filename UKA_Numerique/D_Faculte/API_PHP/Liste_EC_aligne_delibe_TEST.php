<?php
session_start();
header('Content-Type: application/json');

// ============================================
// 🧪 FICHIER DE TEST AVEC 45 ECs FICTIVES
// ============================================
// Ce fichier génère des données de test pour tester
// le système avec beaucoup d'ECs (40+)
// ============================================

$data = json_decode(file_get_contents('php://input'), true);
$id_semestre = isset($data['id_semestre']) ? $data['id_semestre'] : 1;

// Générer 45 ECs fictives pour tester
$ecs = [];

// ECs réelles (9 premières)
$ecsReelles = [
    "Électricité",
    "Électronique I",
    "Anglais I",
    "Français",
    "Informatique I",
    "Algorithmique et Principes",
    "Valeurs, Principes...",
    "Production Allée Géné...",
    "Imputabilité Géné..."
];

foreach ($ecsReelles as $index => $nom) {
    $ecs[] = [
        'id_ec_aligne' => $index + 1,
        'Intutile_ec' => $nom,
        'Credit' => rand(3, 5)
    ];
}

// Ajouter 36 ECs fictives pour atteindre 45 au total
for ($i = 10; $i <= 45; $i++) {
    $matieres = [
        "Mathématiques Avancées",
        "Physique Quantique",
        "Chimie Organique",
        "Biologie Moléculaire",
        "Géologie Structurale",
        "Astronomie",
        "Thermodynamique",
        "Mécanique des Fluides",
        "Électromagnétisme",
        "Programmation Python",
        "Base de Données",
        "Intelligence Artificielle",
        "Réseaux Informatiques",
        "Système d'Exploitation",
        "Cryptographie",
        "Architecture Logicielle",
        "Gestion de Projet",
        "Économie Numérique",
        "Droit Informatique",
        "Éthique & IA",
        "Analyse Numérique",
        "Statistiques Appliquées",
        "Recherche Opérationnelle",
        "Théorie des Graphes",
        "Compilation",
        "Traitement d'Images",
        "Robotique",
        "Systèmes Embarqués",
        "Cloud Computing",
        "DevOps & CI/CD",
        "Blockchain",
        "Cybersécurité",
        "Big Data",
        "Machine Learning",
        "Deep Learning",
        "Vision par Ordinateur"
    ];
    
    $matiere = $matieres[($i - 10) % count($matieres)];
    $niveau = ceil($i / 5);
    
    $ecs[] = [
        'id_ec_aligne' => $i,
        'Intutile_ec' => $matiere . " " . $niveau,
        'Credit' => rand(3, 5)
    ];
}

echo json_encode($ecs);
?>
