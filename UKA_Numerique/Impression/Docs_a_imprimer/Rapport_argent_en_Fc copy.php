<?php
session_start(); 

require('../FPDF/fpdf.php');
include("../../Fonctions_PHP/Fonctions.php");
include("../../Code_QR/qrlib.php");
include("../../../Connexion_BDD/Connexion_1.php");




$Mat_agent=$_SESSION['MatriculeAgent'];
$Id_lieu_paiement=$_GET['Id_lieu_paiement'];
$Id_filiere=$_GET['Id_filiere'];
$Date_debut=$_GET['Date_debut'];
$Date_fin=$_GET['Date_fin'];
$Id_annee_academique=$_GET['Id_annee_acad'];

$Nom_agent=$_SESSION['Nom_user']." ".$_SESSION['Postnom_user'];

$tab_total_paye= array(); // L'insertion dans ce tableua depend de l'ordre

$devise="Fc";
$devise_rapport=$_GET['devise'];

$devise_argent_percu=$_GET['devise_percu'];

//echo " regarde devise $devise_rapport";




/*
*********************************************************************************************************************************
*************** ICI LA REQUETTE POUR RECUPERER TOUT CE QUE L4ON A PAYER SELON UNE PLAGE DE DATE DEFINIE  ************************
**********************************************************************************************************************************/

// Ici la requette  pour recuperer tout ce qui a été payer dans une plage de date 
$libelle_frais="Frais Académiques";


// Ce test passe seuelement si l'argent perçu a été fixé en Franc congolais 
if($devise_rapport==="Fc")
{
    $sql_rapport="select 
    payer_frais.Date_paie as date_paie,
    etudiant.Matricule as mat_etudiant, 
    concat(etudiant.Nom,' ',etudiant.Postnom,' ',etudiant.Prenom) as nom_etudiant, 
    ROUND(SUM(payer_frais.Montant_paie),2) as montant_verser, 
    promotion.Abréviation as promo,
    frais.Libelle_Frais as Lib_frais,
    frais.Devise

    from etudiant,
        agent,
        payer_frais,
        passer_par,
        annee_academique,
        promotion,
        mentions,
        filiere,
        frais,
        lieu_paiement
    WHERE 
    etudiant.Matricule=payer_frais.Matricule
    and payer_frais.Matricule=passer_par.Etudiant_Matricule 
    and passer_par.idAnnee_academique=annee_academique.idAnnee_Acad 
    and passer_par.Code_Promotion=promotion.Code_Promotion
    and promotion.idMentions=mentions.idMentions
    and mentions.IdFiliere=filiere.IdFiliere
    and payer_frais.Mat_agent=agent.Mat_agent 
    and payer_frais.idFrais=frais.idFrais
    and frais.idAnnee_Acad=annee_academique.idAnnee_Acad
    and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement

    and agent.Mat_agent=:mat_agent
    and frais.idAnnee_Acad=:id_annee 
    and Date(payer_frais.Date_paie) BETWEEN :date_debut AND :date_fin
    and filiere.IdFiliere=:Id_filiere
    and lieu_paiement.idLieu_paiement=:id_lieu_paie
    and (frais.Devise IS NULL OR frais.Devise ='Fc' OR frais.Devise='')

    and (payer_frais.Fc IS NOT NULL)

    GROUP BY promotion.Abréviation,etudiant.Matricule,payer_frais.Date_paie,frais.Libelle_Frais,frais.Devise
    ORDER BY payer_frais.Date_paie DESC ";

}
else
{
    $sql_rapport="select 
    payer_frais.Date_paie as date_paie,
    etudiant.Matricule as mat_etudiant, 
    concat(etudiant.Nom,' ',etudiant.Postnom,' ',etudiant.Prenom) as nom_etudiant, 
    ROUND(SUM(payer_frais.Montant_paie),2) as montant_verser, 
    promotion.Abréviation as promo,
    frais.Libelle_Frais as Lib_frais,
    frais.Devise

    from etudiant,
        agent,
        payer_frais,
        passer_par,
        annee_academique,
        promotion,
        mentions,
        filiere,
        frais,
        lieu_paiement
    WHERE 
    etudiant.Matricule=payer_frais.Matricule
    and payer_frais.Matricule=passer_par.Etudiant_Matricule 
    and passer_par.idAnnee_academique=annee_academique.idAnnee_Acad 
    and passer_par.Code_Promotion=promotion.Code_Promotion
    and promotion.idMentions=mentions.idMentions
    and mentions.IdFiliere=filiere.IdFiliere
    and payer_frais.Mat_agent=agent.Mat_agent 
    and payer_frais.idFrais=frais.idFrais
    and frais.idAnnee_Acad=annee_academique.idAnnee_Acad
    and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement

    and agent.Mat_agent=:mat_agent
    and frais.idAnnee_Acad=:id_annee 
    and Date(payer_frais.Date_paie) BETWEEN :date_debut AND :date_fin
    and filiere.IdFiliere=:Id_filiere
    and lieu_paiement.idLieu_paiement=:id_lieu_paie
    and frais.Devise='Dollar'

    GROUP BY promotion.Abréviation,etudiant.Matricule,payer_frais.Date_paie,frais.Libelle_Frais,frais.Devise
    ORDER BY payer_frais.Date_paie DESC ";

}



$stmt=$con->prepare($sql_rapport);    
$stmt->bindParam(':mat_agent',$Mat_agent);
$stmt->bindParam(':id_annee',$Id_annee_academique);
$stmt->bindParam(':date_debut',$Date_debut);
$stmt->bindParam(':date_fin',$Date_fin);
$stmt->bindParam(':Id_filiere',$Id_filiere);
$stmt->bindParam(':id_lieu_paie',$Id_lieu_paiement);
$stmt->execute();

$tab_rapport_FA=array();

//echo " <h1> Regarde  ".$devise_rapport." </h1><br/>";

//echo " voici date debut ".$Date_debut." et date fin ".$Date_fin." <br/>";
while($ligne = $stmt->fetch()) 
{
    $devise=$ligne['Devise'];
    $tab_rapport_FA[]=$ligne;
    //echo " Voici le frais ".$ligne["montant_verser"]. " et lib frais ".$ligne["Lib_frais"]."<br/>";
}

if($devise==="Dollar")$devise="$";
else $devise="Fc";
///////////////////////////////////////////////////////////////








































// Ici la requette  pour recuperer la totalité de frais academiques payé 
$libelle_frais="Frais Académiques";
$sql_rapport=" 
select 
ROUND(SUM(payer_frais.Montant_paie),2) as montant_verser
from etudiant,agent,payer_frais,passer_par,annee_academique,
promotion,mentions,filiere,frais,lieu_paiement
WHERE 
payer_frais.Matricule=etudiant.Matricule 
and etudiant.Matricule=passer_par.Etudiant_Matricule 
and passer_par.idAnnee_academique=annee_academique.idAnnee_Acad 
and passer_par.Code_Promotion=promotion.Code_Promotion
and promotion.idMentions=mentions.idMentions
and mentions.IdFiliere=filiere.IdFiliere
and payer_frais.Mat_agent=agent.Mat_agent 
and payer_frais.idFrais=frais.idFrais
and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement

and agent.Mat_agent=:mat_agent
and annee_academique.idAnnee_Acad=:id_annee 
and Date(payer_frais.Date_paie) BETWEEN :date_debut AND :date_fin
and frais.Libelle_Frais=:lib_frais
and filiere.IdFiliere=:Id_filiere
and lieu_paiement.idLieu_paiement=:id_lieu_paie";


$stmt=$con->prepare($sql_rapport);    
$stmt->bindParam(':mat_agent',$Mat_agent);
$stmt->bindParam(':id_annee',$Id_annee_academique);
$stmt->bindParam(':date_debut',$Date_debut);
$stmt->bindParam(':date_fin',$Date_fin);
$stmt->bindParam(':lib_frais',$libelle_frais);
$stmt->bindParam(':Id_filiere',$Id_filiere);
$stmt->bindParam(':id_lieu_paie',$Id_lieu_paiement);
$stmt->execute();



while($ligne = $stmt->fetch()) 
$tab_total_paye[]=$ligne['montant_verser'];
///////////////////////////////////////////////////////////////

// Ici la requette  pour recuperer la totalité de frais d'enrolement 
$libelle_frais="Enrôlement à la Session";
$sql_rapport=" 
select 
ROUND(SUM(payer_frais.Montant_paie),2) as montant_verser
from etudiant,agent,payer_frais,passer_par,annee_academique,
promotion,mentions,filiere,frais,lieu_paiement
WHERE 
payer_frais.Matricule=etudiant.Matricule 
and etudiant.Matricule=passer_par.Etudiant_Matricule 
and passer_par.idAnnee_academique=annee_academique.idAnnee_Acad 
and passer_par.Code_Promotion=promotion.Code_Promotion
and promotion.idMentions=mentions.idMentions
and mentions.IdFiliere=filiere.IdFiliere
and payer_frais.Mat_agent=agent.Mat_agent 
and payer_frais.idFrais=frais.idFrais
and payer_frais.idLieu_paiement=lieu_paiement.idLieu_paiement

and agent.Mat_agent=:mat_agent
and annee_academique.idAnnee_Acad=:id_annee 
and Date(payer_frais.Date_paie) BETWEEN :date_debut AND :date_fin
and frais.Libelle_Frais=:lib_frais
and filiere.IdFiliere=:Id_filiere
and lieu_paiement.idLieu_paiement=:id_lieu_paie";


$stmt=$con->prepare($sql_rapport);    
$stmt->bindParam(':mat_agent',$Mat_agent);
$stmt->bindParam(':id_annee',$Id_annee_academique);
$stmt->bindParam(':date_debut',$Date_debut);
$stmt->bindParam(':date_fin',$Date_fin);
$stmt->bindParam(':lib_frais',$libelle_frais);
$stmt->bindParam(':Id_filiere',$Id_filiere);
$stmt->bindParam(':id_lieu_paie',$Id_lieu_paiement);
$stmt->execute();



while($ligne = $stmt->fetch()) 
$tab_total_paye[]=$ligne['montant_verser'];
///////////////////////////////////////////////////////////////






/*
****************** ICI JE RECUPERE LE LIEU DE PAIEMENT *****************

*/
$lieu_paiement="Frais payés";
if($Id_lieu_paiement=="3")$lieu_paiement.=" au Guichet de l'U.KA.";
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
    $lieu_paiement.=" à la banque ( $banque )";

}

$lieu_paiement=mb_convert_encoding($lieu_paiement, 'ISO-8859-1', 'UTF-8');
///////////////////////////////////////////////////////////////////////////////////////

// Ici on cherche le nom de la faculté 

$sql_select_acces="
SELECT filiere.Libelle_Filiere as filiere
from filiere
where filiere.IdFiliere=:id_filiere";

$stmt=$con->prepare($sql_select_acces);
$stmt->bindParam(':id_filiere',$Id_filiere);
$stmt->execute();

$filiere="";

while($ligne = $stmt->fetch())$filiere=$ligne['filiere'];



/*
****************************************************************************************
********** La fonction qui poura dessiner le tableau il attends en parametre l'entête et 
********** les données ******************************************************************
*/

class PDF extends FPDF
{
    function Tableau_couleur($entete, $donnees,$tab_total_paye,$dev)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');

        // En-tête
        $w = array(15,25,45, 80, 30,30,30);
        for($i=0;$i<count($entete);$i++)
            $this->Cell($w[$i],9,$entete[$i],1,0,'C',true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Données
        $fill = false;
       
        // cette boucle tourne que pour afficher le Frais academique
        
        for($i = 0; $i < (count($donnees)+1); $i++)
        {
            $tot_somme_ligne="";
            if($i<(count($donnees)))
            {
                $row=$donnees[$i];
                $this->Cell($w[0],7,($i+1),'LR',0,'C',$fill);
                $this->Cell($w[1],7,$row["promo"],'LR',0,'C',$fill);
                $this->Cell($w[2],7,$row["mat_etudiant"],'LR',0,'L',$fill);
                $this->Cell($w[3],7,$row["nom_etudiant"],'LR',0,'L',$fill);

                if($row['Lib_frais']=="Frais Académiques")$this->Cell($w[4],7,$row["montant_verser"]." ".$dev,'LR',0,'R',$fill);
                else $this->Cell($w[4],7,'','LR',0,'R',$fill);

                if($row['Lib_frais']=="Enrôlement à la Session")$this->Cell($w[5],7,$row["montant_verser"]." ".$dev,'LR',0,'R',$fill);
                else $this->Cell($w[5],7,'','LR',0,'R',$fill);              
                
                $this->Cell($w[6],7,"",'LR',0,'L',$fill);
                
            }
            else
            {
                $this->SetFont('','B',13);

                $this->Cell($w[0],7,'','LR',0,'C',$fill);
                $this->Cell($w[1],7,'','LR',0,'C',$fill);
                $this->Cell($w[2],7,'','LR',0,'L',$fill);
                $this->Cell($w[3],7,"TOTAL : ",'LR',0,'R',$fill);
                $this->Cell($w[4],7,$tab_total_paye[0]." ".$dev,'LR',0,'R',$fill);
                $this->Cell($w[5],7,$tab_total_paye[1]." ".$dev,'LR',0,'R',$fill);
                $this->Cell($w[6],7,"",'LR',0,'L',$fill);

            }
            $this->Ln();
            $fill = !$fill;
        }

        // Trait de terminaison
        $this->Cell(array_sum($w),0,'','T');
        
    }

}




$pdf = new PDF('L','mm','A4');
$pdf->AddPage();




// ICI ON INSERE LE LOGO DE L'UKA
// Insertion de l'image du code QR
// Position de l'image sur la page PDF
$x = 20;  // Coordonnée X
$y = 10;  // Coordonnée Y
$width = 25;  // Largeur de l'image
$height = 25;  // Hauteur de l'image
// Ajouter l'image à la page PDF
$pdf->Image("logo_uka.jpeg", $x, $y, $width, $height);


$pdf->SetFont('Times','B',12);
//FPDF();
$text_1="REPUBLIQUE DEMOCRATIQUE DU CONGO";
$pdf->Cell(0,5,$text_1,0,1,'C');

$text_1="MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET UNIVERSITAIRE";
$pdf->Cell(0,5,$text_1,0,1,'C');

$text_1="UNIVERSITE NOTRE DAME DU KASAYI";
$pdf->Cell(0,5,$text_1,0,1,'C');

$text_1="ADMINSTRATEUR DU BUDGET";
$pdf->Cell(0,5,$text_1,0,1,'C');

$text_1="U.KA.";
$pdf->Cell(0,5,$text_1,0,1,'C');

$pdf->Ln();

// Puis on trace les traits
$pdf->SetDrawColor(20,200, 200);
$pdf->Line(10, 38, 280,38);




$text_1="VENTILATION";
$pdf->Cell(0,5,$text_1,0,1,'C');
$pdf->Ln();

// Affichage faculté 

$pdf->SetXY(200,40);
$text_1="Filière : ".$filiere;
$text_1= mb_convert_encoding($text_1, 'ISO-8859-1', 'UTF-8');
$pdf->Cell(0,5,$text_1,0,1,'C');

$pdf->SetY(48);
// Affichage Date et Frais
$text_1="Date  ".date('d/m/Y', strtotime($Date_debut))." -  "
.date('d/m/Y', strtotime($Date_fin))
."   Agent : ".$Nom_agent." /  ".$lieu_paiement;
$pdf->Cell(0,5,$text_1,0,1,'L');
$pdf->Ln();

$pdf->SetFont('Arial','',14);
$entete = array(
    mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8')
    ,'Prom'
    , 'Matricule'
    , 'Etudiant'
    , 'FA'
    ,mb_convert_encoding('Enrôlement', 'ISO-8859-1', 'UTF-8')
    ,'F.Freq.a');
$pdf->Tableau_couleur($entete,$tab_rapport_FA,$tab_total_paye,$devise);


$pdf->Output();


?>