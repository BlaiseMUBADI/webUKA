
<?php
include("../../Fonctions_PHP/Fonctions.php");
include("../../Code_QR/qrlib.php");
include("../../autorite.php");

$mat_etudiant = $_GET['matricule'];
$nom_etudiant = $_GET['nom_etudiant'];
$postnom_etudiant = $_GET['postnom_etudiant'];
$prenom_etudiant = $_GET['prenom_etudiant'];
$faculte = $_GET['faculte'];
$date_livraison = $_GET['date_livraison'];
$secretaire = $academique;


// GENERATION DU CODE QR
$code_QR=Generation_QR_attestation(
    $nom_etudiant,
    $postnom_etudiant,
    $prenom_etudiant,
    $mat_etudiant,
    $faculte,
    $date_livraison,
    $secretaire);

$er="erick ngindu";
// renvoie de la reponse JSON
//

 ?>
 <img src="<?php echo $code_QR; ?>" id="code_qr_attestation">

 