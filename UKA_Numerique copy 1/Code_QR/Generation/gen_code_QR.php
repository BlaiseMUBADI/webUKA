<?php
// Inclure la bibliothèque QR Code
require("../qrlib.php");

// Texte à encoder en code QR
$text = "Blaise MUBADI";

// Nom du fichier dans lequel sera enregistré le code QR généré
$filename = "qrcode.png";

// Taille du code QR (la taille par défaut est 3 et sa peu aller jusqu'à 10)
$size = 3;

// Niveau de correction d'erreur (L - le plus bas, H - le plus élevé)
$level = "L";

// Autres options du code QR (voir la documentation QR Code pour plus d'informations)
$margin = 4;
$color = array("r" => 0, "g" => 0, "b" => 0);
$bgcolor = array("r" => 255, "g" => 255, "b" => 255);

// Générer le code QR
QRcode::png($text, $filename, $level, $size, $margin, false, $color, $bgcolor);

// Afficher le code QR généré
echo '<img src="' . $filename . '" alt="QR Code">';
?>