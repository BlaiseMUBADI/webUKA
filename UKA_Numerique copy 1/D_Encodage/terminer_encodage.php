<?php  
//include("Connexion.php");

include("../../Connexion_BDD/Connexion_1.php");


//$Matricule_etudiant=$_POST['Matricule_const'];
$code_promo=$_GET['code_promo'];    
$Id_annee_acad=$_GET['Id_annee_acad'];
$mat_etudiant=$_GET['mat_etudiant'];

//recuperation de l'année academique prochaine*
$sql_annee="SELECT Annee_debut, Annee_fin 
FROM annee_academique 
where idAnnee_Acad='$Id_annee_acad'" ;

$reponse = $con->query ($sql_annee);

while ($ligne = $reponse->fetch())
{
  $anne_debut=$ligne['Annee_fin'];
  $anne_fin=$ligne['Annee_fin']+1;
}


// verification de l'année suivante si cette année n'existe pas on l'ajoute
$k=0;
$sql_annee_verification=
"SELECT idAnnee_Acad 
FROM annee_academique 
where Annee_debut = '$anne_debut' 
and Annee_fin='$anne_fin'";

$reponse1 = $con->query ($sql_annee_verification);

while ($ligne = $reponse1->fetch())
{
  $Id_annee_acad1=$ligne['idAnnee_Acad'];
  $k++;
}
echo "la valeur de k est ".$k;
if ($k==0)
{
  $req_ajout_annee=
  "INSERT INTO `annee_academique`(`Annee_debut`, `Annee_fin`) 
  VALUES ('$anne_debut','$anne_fin')";
  
  $con->query($req_ajout_annee);
  
  $Id_annee_acad1=$con->lastInsertId();
  echo "le dernier id est ".$id_annee_acad;
}

// verification de la promotion et montage de promotion

$promo_montante="";
$code_promo_montante="";
/*$rqt2="SELECT filiere.IdFiliere, mentions.idMentions, promotion.Code_Promotion,promotion.Abréviation FROM filiere, mentions, promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE promotion.Code_Promotion='$code_promo'";*/
$sql_verifi_promo="SELECT * FROM promotion where Code_Promotion = '$code_promo'";
$reponse2 = $con->query ($sql_verifi_promo);

while ($ligne = $reponse2->fetch()) 
{
  $Abréviation1=$ligne['Abréviation'];
  $idMention=$ligne['idMentions'];
  echo "l Abréviation promotion est ".$Abréviation1;
  
  if ($Abréviation1=="L0 LMD") {$promo_montante="L1 LMD";}
  else if ($Abréviation1=="L1 LMD") {$promo_montante="L2 LMD";}
  else if ($Abréviation1=="L2 LMD") {$promo_montante="L3 LMD";}
  else if ($Abréviation1=="L3 LMD") {$promo_montante="M1 LMD";}
  else if ($Abréviation1=="M1 LMD") {$promo_montante="M2 LMD";}
  else if ($Abréviation1=="M2 LMD") { $promo_montante=""; }
}

$sql_verifi_promo_montante=
"SELECT * 
FROM promotion 
where Abréviation = '$promo_montante' 
and idMentions='$idMention'";

$Code_promo_montante="";
$reponse3 = $con->query ($sql_verifi_promo_montante);
while ($ligne = $reponse3->fetch()) $code_promo_montante=$ligne['Code_Promotion'];

/****************************************************************************
 * ************* ICI ON INSERE L'ETUDIANT DANS UNE PROMOTION MONTANTE *******
 *****************************************************************************/

 $sql_insert_passer_par= 
  "INSERT INTO 
  passer_par(
    Etudiant_Matricule, 
    Code_Promotion, 
    idAnnee_academique, 
    Decision_jury, 
    Session1, 
    Mention1, 
    Session2, 
    Mention2) 
    VALUES ( :mat_etudiant,'$code_promo_montante','$Id_annee_acad1',null,'0','-','0','-')"; 

 $stmt1=$con->prepare($sql_insert_passer_par);
 $stmt1->bindParam(':mat_etudiant',$mat_etudiant);
 $stmt1->execute();

 /*
$sql_select_etudiant_prom="SELECT 
      passer_par.Etudiant_Matricule,
      passer_par.Code_Promotion,
      passer_par.idAnnee_academique,
      passer_par.Decision_jury,
      passer_par.Session1,
      passer_par.Mention1,
      passer_par.Session2,
      passer_par.Mention2

      FROM passer_par
      WHERE passer_par.idAnnee_academique=:idannee 
      AND passer_par.Code_Promotion=:code_prom";

$stmt=$bdd->prepare($sql_select_etudiant_prom);

$stmt->bindParam(':idannee',$Id_annee_acad);
$stmt->bindParam(':code_prom',$code_promo);
$stmt->execute();

$i=0;
$mat_etudiant="";
$etud=array();

while($ligne = $stmt->fetch())
{
  if ($ligne['Decision_jury']=="Admis")
  {
    $i++;
    // ici on ajoute une ligne dans la table passer par si la decision est "admis"
             $sql_insert_passer_par= "INSERT INTO passer_par(Etudiant_Matricule, Code_Promotion, idAnnee_academique, Decision_jury, Session1, Mention1, Session2, Mention2) VALUES ( :mat_etudiant,'$code_promo_montante','$Id_annee_acad1',null,'0','-','0','-')"; 

            $stmt1=$bdd->prepare($sql_insert_passer_par);
            $stmt1->bindParam(':mat_etudiant',$ligne['Etudiant_Matricule']);
            $stmt1->execute();
        }

    }*/

    echo "la valeur de i est ".$i;


    ?>