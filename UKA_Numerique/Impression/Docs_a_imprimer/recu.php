


<?php
session_start(); 

require('../FPDF/fpdf.php');
include("../../Fonctions_PHP/Fonctions.php");
include("../../Code_QR/qrlib.php");
include("../../../Connexion_BDD/Connexion_1.php");




$mat_etuiant=$_GET['Mat_etudiant'];
$nom_etudiant=$_GET['Nom_etudiant'];
$montant_payer=$_GET['Montant_payer'];
$devise=$_GET['devise'];

$date_paiement=$_GET['Date_paiement'];
// ICI On essaie d'écrire la date en suivant ce  '/07/01/2024
$date_base=$date_paiement;
$date_paiement = date('d/m/Y', strtotime($date_paiement));


$code_promo=$_GET['Code_promo'];

$Id_an_acad=$_GET['Id_an_acad'];


$tab_motif_paiement=json_decode($_GET['Tab_motif_paiement'], true);
$type_frais=$_GET['Type_frais'];
$Id_lieu_paiement=$_GET['Id_banque'];

$motif_paiement="";
$lieupaiement="Frais payés";



$nom_agent=$_SESSION['Nom_user']." ".$_SESSION['Postnom_user'];

// LA FONCTIONQUI NOUS PERMET DE VERIFIER SI CETTE TRANSACTION CONCERNE DEUX OPERATIONS OU PAS FA + ENROLEMENT
function Verifi_Type_operation($con,$mat_etud,$code_prom,$id_an,$date_paiee,$mat_agent)
{
    $sql_select_acces="
    select 
        COUNT(etudiant.Matricule) as nb_enreg
    
        from etudiant,payer_frais,annee_academique,frais,promotion,lieu_paiement,agent
    
        WHERE etudiant.Matricule=payer_frais.Matricule 
        and promotion.Code_Promotion=frais.Code_Promotion 
        and payer_frais.idFrais=frais.idFrais 
        and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement
        and payer_frais.Mat_agent=agent.Mat_agent
        and frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
        and annee_academique.idAnnee_Acad=:id_annee_acad
        and promotion.Code_Promotion=:code_promo
        and etudiant.Matricule=:mat_etudiant        
        and agent.Mat_agent=:mat_agent
        and payer_frais.Ensemble is not null
        and DATE(payer_frais.Date_paie)=DATE(:date_paiee)
        and HOUR(payer_frais.Date_paie)=HOUR(:date_paiee)
        and MINUTE(payer_frais.Date_paie)=MINUTE(:date_paiee)";

        $stmt=$con->prepare($sql_select_acces);
        $stmt->bindParam(':id_annee_acad',$id_an);
        $stmt->bindParam(':mat_etudiant',$mat_etud);
        $stmt->bindParam(':code_promo',$code_prom);
        $stmt->bindParam(':date_paiee',$date_paiee);
        $stmt->bindParam(':mat_agent',$mat_agent);
        $stmt->execute();  
        $nb=0;
        while($ligne = $stmt->fetch())
        {
            $nb=$ligne['nb_enreg'];
    
        }
        return $nb;
}

// Ici on esseai de concatener le frais academique  aux enrolemnt selectionnés sur l'interfaces
if($type_frais=="Frais Académiques et Enrôlement à la Session")
{
   

    $nb=Verifi_Type_operation($con,$mat_etuiant,$code_promo,$Id_an_acad,$date_base,$_SESSION['MatriculeAgent']);
   
    if($nb==2)
    {
        $chaine = "Frais Academiques et Enrolement à la Session";
        $mot_cle = "Frais Academiques";
        $position_mot = strpos($chaine, $mot_cle);
        $motif_paiement = substr($chaine, $position_mot, strlen($mot_cle))." et";
    
        foreach ($tab_motif_paiement as $key => $value)
        {
            $motif_paiement.=" ".$value.",";
        }
    }
    else $motif_paiement="Frais Academiques";
    
}
else if($type_frais=="Enrôlement à la Session")
{
    foreach ($tab_motif_paiement as $key => $value)
    {
        $motif_paiement.=" ".$value." et ";
    }
}
else $motif_paiement="Frais Académiques";


if($Id_lieu_paiement=="-1")$lieupaiement.=" au Guichet de l'U.KA.";
else
{

    // Selection du nom de la banque

    $banque="";
    $sql_banque="
        SELECT lieu_paiement.Libelle_lieu as banque 
        FROM lieu_paiement 
        WHERE idLieu_paiement=:idbanque";
    
    $stmt=$con->prepare($sql_banque);    
    $stmt->bindParam(':idbanque',$Id_lieu_paiement);
    $stmt->execute();
    
    while($ligne = $stmt->fetch()) $banque=$ligne['banque'];
    $lieupaiement.=" a la banque ( $banque )";

}
$lieupaiement=mb_convert_encoding($lieupaiement, 'ISO-8859-1', 'UTF-8');



// ICI ON COMPTE LE NOMBRE D4ENREGISTREMENT QU'UN PERCEPTEUR A EFFECTUER LA JOURNE

$nb_recu="";
$sql_nb_recu="select count(payer_frais.Id_payer_frais) as nb_recu
from payer_frais 
where payer_frais.Mat_agent=:mat_agent
and payer_frais.Date_paie=DATE(:date_paie) 
GROUP BY payer_frais.Date_paie";

$stmt=$con->prepare($sql_nb_recu);    
$stmt->bindParam(':mat_agent',$_SESSION['MatriculeAgent']);
$stmt->bindParam(':date_paie',$date_base);
$stmt->execute();

while($ligne = $stmt->fetch()) $nb_recu=$ligne['nb_recu'];


// Calcul de rest à payé



/**************************************************************************************************
************************** ICI NOUS SOMMES ENTRAIN DE CALCULER LE RESTE ***************************
 **************************************************************************************************/

 $reste_FA="";
 $reste_EMS="";
 $reste_ES="";
 $reste_E2S="";

 
 
 /*
      ICI NOUS INTEROGONS LA BASE POUR SAVOIR LE RESTE A FA
 */
 
 
$motif="Frais Académiques";
 
$frais_fixer=0;
$sql_f_fixer="select frais.Montant as montant_fixer
    FROM frais
    WHERE frais.idAnnee_Acad=:id_annee
    and frais.Code_Promotion=:code_prom
    and frais.Libelle_Frais=:motif";
$stmt=$con->prepare($sql_f_fixer); 
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':code_prom',$code_promo);
$stmt->bindParam(':motif',$motif);
$stmt->execute();
$data = $stmt->fetch();
$frais_fixer=$data['montant_fixer'];


$sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
FROM annee_academique,frais,payer_frais,etudiant
WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
and frais.idFrais=payer_frais.idFrais
and payer_frais.Matricule=etudiant.Matricule
and annee_academique.idAnnee_Acad=:id_annee
and etudiant.Matricule=:mat_etudiant
and payer_frais.Motif_paie=:motif_paiement";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':mat_etudiant',$mat_etuiant);
$stmt->bindParam(':motif_paiement',$motif);
$stmt->execute();
$data = $stmt->fetch();
$reste_FA=$frais_fixer-$data['reste_FA'];
//////////////////////////////////////////////////////////////////////////////////



/*
******************************** ICI NOUS CHERCHONS LE REST POUR CHAQUE FRAIS D'ENROLEMENT
*/


// Enrolement à la Mi-session ou 1 primers semestre d'abord 
$motif="Enrôlement à la Mi-Session";
$motif1="Enrôlement à la Session";

$frais_fixer_enrol=0;
$sql_f_fixer="select sum(frais.Montant) as montant_fixer
    FROM frais
    WHERE frais.idAnnee_Acad=:id_annee
    and frais.Code_Promotion=:code_prom
    and frais.Libelle_Frais=:motif";
$stmt=$con->prepare($sql_f_fixer); 
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':code_prom',$code_promo);
$stmt->bindParam(':motif',$motif1);
$stmt->execute();
$data = $stmt->fetch(); $frais_fixer_enrol=$data['montant_fixer'];
//$data = $stmt->fetch();



$sql_FA="select sum(payer_frais.Montant_paie) as reste_FA 
FROM annee_academique,frais,payer_frais,etudiant
WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
and frais.idFrais=payer_frais.idFrais
and payer_frais.Matricule=etudiant.Matricule
and annee_academique.idAnnee_Acad=:id_annee
and etudiant.Matricule=:mat_etudiant
and payer_frais.Motif_paie=:motif_paiement";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':mat_etudiant',$mat_etuiant);
$stmt->bindParam(':motif_paiement',$motif);
$stmt->execute();
$data = $stmt->fetch();
$reste_EMS=$frais_fixer_enrol-$data['reste_FA'];
/********************************************************************************* */



// Enrolement à la grande Session ou 2 eme semestre
$motif="Enrôlement à la Grande-Session";
$motif1="Enrôlement à la Session";

$frais_fixer_enrol_1=0;
$sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
    FROM frais
    WHERE frais.idAnnee_Acad=:id_annee
    and frais.Code_Promotion=:code_prom
    and frais.Libelle_Frais=:motif";
$stmt=$con->prepare($sql_f_fixer); 
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':code_prom',$code_promo);
$stmt->bindParam(':motif',$motif1);
$stmt->execute();
$data = $stmt->fetch(); $frais_fixer_enrol_1=$data['montant_fixer'];
//echo " Je suis à 124 ".$frais_fixer_enrol_1;



$sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
FROM annee_academique,frais,payer_frais,etudiant
WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
and frais.idFrais=payer_frais.idFrais
and payer_frais.Matricule=etudiant.Matricule
and annee_academique.idAnnee_Acad=:id_annee
and etudiant.Matricule=:mat_etudiant
and payer_frais.Motif_paie=:motif_paiement";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':mat_etudiant',$mat_etuiant);
$stmt->bindParam(':motif_paiement',$motif);
$stmt->execute();
$data = $stmt->fetch();
$reste_ES=$frais_fixer_enrol_1-$data['reste_FA'];
/********************************************************************************* */



// Enrolement à la deuxième session Session ou rattrapage
$motif="Enrôlement à la Deuxième-Session";
$motif1="Enrôlement à la Session";

$frais_fixer_enrol_2=0;
$sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
    FROM frais
    WHERE frais.idAnnee_Acad=:id_annee
    and frais.Code_Promotion=:code_prom
    and frais.Libelle_Frais=:motif";
$stmt=$con->prepare($sql_f_fixer); 
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':code_prom',$code_promo);
$stmt->bindParam(':motif',$motif1);
$stmt->execute();

$data = $stmt->fetch(); $frais_fixer_enrol_2=$data['montant_fixer'];


$sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
FROM annee_academique,frais,payer_frais,etudiant
WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
and frais.idFrais=payer_frais.idFrais
and payer_frais.Matricule=etudiant.Matricule
and annee_academique.idAnnee_Acad=:id_annee
and etudiant.Matricule=:mat_etudiant
and payer_frais.Motif_paie=:motif_paiement";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':mat_etudiant',$mat_etuiant);
$stmt->bindParam(':motif_paiement',$motif);
$stmt->execute();
$data = $stmt->fetch();
$reste_E2S=$frais_fixer_enrol_2-$data['reste_FA'];

// Enrolement aux rattrapages sem 1
$motif="Enrôlement aux rattrapages sem 1";
$motif1="Enrôlement à la Session";

$frais_fixer_rat_sem_1=0;
$sql_f_fixer="select ROUND(sum(frais.Montant),2) as montant_fixer
    FROM frais
    WHERE frais.idAnnee_Acad=:id_annee
    and frais.Code_Promotion=:code_prom
    and frais.Libelle_Frais=:motif";
$stmt=$con->prepare($sql_f_fixer); 
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':code_prom',$code_promo);
$stmt->bindParam(':motif',$motif1);
$stmt->execute();

$data = $stmt->fetch(); $frais_fixer_rat_sem_1=$data['montant_fixer'];


$sql_FA="select ROUND(sum(payer_frais.Montant_paie),2) as reste_FA 
FROM annee_academique,frais,payer_frais,etudiant
WHERE annee_academique.idAnnee_Acad=frais.idAnnee_Acad
and frais.idFrais=payer_frais.idFrais
and payer_frais.Matricule=etudiant.Matricule
and annee_academique.idAnnee_Acad=:id_annee
and etudiant.Matricule=:mat_etudiant
and payer_frais.Motif_paie=:motif_paiement";

$stmt=$con->prepare($sql_FA);    
$stmt->bindParam(':id_annee',$Id_an_acad);
$stmt->bindParam(':mat_etudiant',$mat_etuiant);
$stmt->bindParam(':motif_paiement',$motif);
$stmt->execute();
$data = $stmt->fetch();
$reste_ratSem1=$frais_fixer_rat_sem_1-$data['reste_FA'];

/********************************************************************************* */



 $reste_FA=$reste_FA.$devise;
 $reste_EMS=$reste_EMS.$devise;
 $reste_ES=$reste_ES.$devise;
 $reste_E2S=$reste_E2S.$devise;
 $reste_RatSem1=$reste_ratSem1.$devise;
 /********************************************************************************* */









/******************************************************************************************
********* ICI LA RECUPERATION DE LA PROMOTION ET LA FILIERE ET AUSSI L'ANNEE ACADEMIQUE ***
*******************************************************************************************/

$sql_select_acces="SELECT 
promotion.Code_Promotion as cd_prom, 
concat(promotion.Abréviation,' - ',mentions.Libelle_mention) as promotion, 
filiere.Libelle_Filiere as filiere,
concat(annee_academique.Annee_debut,' - ',annee_academique.Annee_fin) as annee_acad
from promotion,mentions,filiere,annee_academique,passer_par
where annee_academique.idAnnee_Acad=passer_par.idAnnee_academique
and passer_par.Code_Promotion=promotion.Code_Promotion
and promotion.IdMentions=mentions.IdMentions 
and mentions.IdFiliere=filiere.IdFiliere 
and promotion.Code_Promotion=:code_promo";



$stmt=$con->prepare($sql_select_acces);
$stmt->bindParam(':code_promo',$code_promo);
$stmt->execute();

$promtoion="";
$filiere="";
$annee_acad="";

while($ligne = $stmt->fetch())
{
    $promtoion=$ligne['promotion'];
    $filiere=$ligne['filiere'];
    $annee_acad=$ligne['annee_acad'];
}


// GENERATION DU CODE QR
$code_QR=Generation_QR(
    $nom_agent,
    $promtoion,
    $annee_acad,
    $mat_etuiant,
    $montant_payer,
    $date_paiement,
    $motif_paiement);







// ici nous commençons a inserer les objets sur le document pdf

// Instanciation de la classe dérivée
$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->AddPage();






// Insertion de l'image du code QR
// Position de l'image sur la page PDF
$x = 180;  // Coordonnée X
$y = 3;  // Coordonnée Y
$width = 20;  // Largeur de l'image
$height = 20;  // Hauteur de l'image
// Ajouter l'image à la page PDF
$pdf->Image($code_QR, $x, $y, $width, $height);







// Affichage de ligne d'en haut
$pdf->SetDrawColor(20,20, 200);
$pdf->Line(10, 26, 200, 26);



// Affichage de la filière
$pdf->SetY(28);
$pdf->SetX(13);
$pdf->SetFont('Times','B',10);
$text=mb_convert_encoding("Filière : ", 'ISO-8859-1', 'UTF-8')." ".$filiere;
$pdf->Cell(0,10,$text,0,1,'L');




// Affichage du numéro de reçu



//Affichage de montant en chiffre
$pdf->SetDrawColor(20,200, 200);
$pdf->Line(150, 30, 200, 30);
$pdf->Line(150, 31, 200, 31);
$pdf->Line(150, 32, 200, 32);
$pdf->Line(150, 33, 200, 33);
$pdf->Line(150, 34, 200, 34);
$pdf->Line(150, 35, 200, 35);
$pdf->Line(150, 36, 200, 36);

$pdf->SetXY(155,28);
$pdf->SetFont('Times','B',13);

$text=" ".$montant_payer." ".$devise;
$pdf->Cell(0,10,$text,0,1,'C');

// Affichage de numro du reçu
$pdf->SetXY(20,28);
$pdf->SetFont('Times','B',13);
$text=mb_convert_encoding(" Reçu N° :", 'ISO-8859-1', 'UTF-8')." ".($nb_recu);
$pdf->Cell(0,10,$text,0,1,'C');








// Affichage de montant en toutes lettres
// ici on affiche d'abors le nom de l'étudiant
$pdf->SetY(45);
$pdf->SetFont('Times','B',12);
$text="Etudiant(e) : ".$nom_etudiant." (de ".$promtoion.")";
$text=mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
$pdf->Cell(0,8,$text,0,1,'L');

// Puis on trace les traits
$pdf->SetDrawColor(20,200, 200);
$pdf->Line(45, 53, 200, 53);
$pdf->Line(45, 54, 200, 54);
$pdf->Line(45, 55, 200, 55);
$pdf->Line(45, 56, 200, 56);
$pdf->Line(45, 57, 200, 57);
$pdf->Line(45, 58, 200, 58);

//Puis ici on affiche le montant 
$pdf->SetY(50);
$montant_en_text = nombreEnTexte($montant_payer)." ".$devise;
$pdf->SetFont('Times','B',12);
$text="Somme en lettre :  ";
$pdf->Cell(0,10,$text,0,1,'L');

$pdf->SetXY(50,50);
$montant_en_text = nombreEnTexte($montant_payer)." ".$devise;
$pdf->SetFont('Times','B',12);
$text=$montant_en_text;
$pdf->Cell(0,10,$text,0,1,'L');




// Affichage de motif
$pdf->SetY(58);
$pdf->SetFont('Times','B',12);
$text="Motif : ". mb_convert_encoding($motif_paiement, 'ISO-8859-1', 'UTF-8');//utf8_decode($motif_paiement);
$pdf->Cell(0,10,$text,0,1,'L');


// affichage de rest à payer

$pdf->SetY(65);
$pdf->SetFont('Times','B',11);
$text=mb_convert_encoding("Reste à payer", 'ISO-8859-1', 'UTF-8');
$pdf->Cell(0,5,$text,0,1,'C');

$pdf->SetY(70);
$pdf->SetFont('Times','B',11);
$text="(FA: $reste_FA ) "." (E.M.S./E-1-Sem : $reste_EMS) "." (E-Rat-Sem_1 : $reste_RatSem1) "." (E.G.S/E-2-Sem : $reste_ES) "." (E.2.S/E-Ratt : $reste_E2S)";
$pdf->Cell(0,5,$text,0,1,'C');




// Ici on affiche la date et le nom du percepteur

$pdf->SetY(76);
$pdf->SetX(150);
$pdf->SetFont('Times','',11);
$text="Kga, le ".$date_paiement;
$pdf->Cell(0,5,$text,0,1,'C');


$pdf->SetY(85);
$pdf->SetX(130);
$pdf->SetFont('Times','B',12);
$text="Guichetier (e) : ".$nom_agent;
$pdf->Cell(0,5,$text,0,1,'C');

// Affichage du lieu de paiement 
$pdf->SetY(78);
$pdf->SetFont('Times','',11);
$text=$lieupaiement;
$pdf->Cell(0,5,$text,0,1,'');

$pdf->SetY(85);
$pdf->SetFont('Times','B',12);
$text="Etudiant";
$pdf->Cell(50,5,$text,0,1,'C');


// Affichage de ligne d'en bas

// ici on ecrit le montant en chiffre 
// On trace d'abord les traits
$pdf->SetDrawColor(20,20, 200);
$pdf->Line(10, 92, 200, 92);
$pdf->Line(10, 93, 200, 93);







$pdf->Output();
?>