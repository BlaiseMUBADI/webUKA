<?php

/**********************************************************************************************
*************  c'est une fonction qui permet d'écrire un nombre en toute lettre  **************
***********************************************************************************************
*/
function nombreEnTexte($nombre)
{
    // Créer un formateur de nombres
    $formateur = new NumberFormatter('fr_FR', NumberFormatter::SPELLOUT);
    
    // Formater le nombre en toutes lettres
    return $formateur->format($nombre);
}
////////////////////////////////////////////////////////////////////////////////////////////////////

/************************************************************************************************
 * ********* C'est une fontion qui permet de generer le code QR sur le reçu *********************************
 * **********************************************************************************************
 */
function Generation_QR($nom_agent,$promotion,$annee_acade,$mat_etudiant,$montant,$date_paie,$motif)
{
    

    // Texte à encoder en code QR
    $text =$mat_etudiant." / ".$promotion." / ".$montant."Fc / ".$motif.
    " / ".$date_paie." / ".$annee_acade." / ".$nom_agent;
    
    $image_code_QR = "CodeQR.png";
    // Nom du fichier dans lequel sera enregistré le code QR généré
    

    // Taille du code QR (la taille par défaut est 3 et sa peu aller jusqu'à 10)
    $size = 3;
    // Niveau de correction d'erreur (L - le plus bas, H - le plus élevé)
    $level = "L";

    // Autres options du code QR (voir la documentation QR Code pour plus d'informations)
    $margin = 4;
    $color = array("r" => 0, "g" => 0, "b" => 0);
    $bgcolor = array("r" => 255, "g" => 255, "b" => 255);

    // Générer le code QR
    QRcode::png($text,  $image_code_QR, $level, $size, $margin, false, $color, $bgcolor);

    return $image_code_QR;
}
////////////////////////////////////////////////////////////////////////////////////////////
// Fonction pour afficher une image BLOB stockée en base
function afficher_image_blob($mat_agent, $con, $default = "default.png") {
    $sql = "SELECT Photo_profil FROM compte_agent WHERE Mat_agent = ?";
    $stat = $con->prepare($sql);
    $stat->execute([$mat_agent]);
    $row = $stat->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['Photo_profil'])) {
        header("Content-Type: image/jpeg"); // Change to image/png si besoin
        echo $row['Photo_profil'];
        exit;
    } else {
        header("Content-Type: image/png");
        readfile($default);
        exit;
    }
}

// Script d'affichage direct via GET
if (isset($_GET['id'])) {
    include("../Connexion_BDD/Connexion_1.php");
    afficher_image_blob($_GET['id'], $con);
}

?>
