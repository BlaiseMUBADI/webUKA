<?php
 include("../../../Connexion_BDD/Connexion_1.php");

$action = $_GET['action'] ?? 'home';

if ($action=="tableau_rubrique") {

////////////////////////////////////////////////////////////////////////////////////////
// ici c'est pour afficher le tableau de rapartition ^par promotion ///////////////////
//////////////////////////////////////////////////////////////////////////////////////
$code_promotion = $_GET['valeur_promotion'];
$id_annee_academique = $_GET['annee_academique'];


$sql="SELECT 
    r.Id_rubrique,
    ru.Libelle AS Libelle_Rubrique,
    ru.Categorie,
    p.Libelle_promotion,
    r.Id_repartition,
    p.Abréviation,
    r.Montant,
    ROUND((r.Montant / total.TotalMontant) * 100, 2) AS Pourcentage,
    total.TotalMontant
FROM 
    repartition r
JOIN 
    rubrique ru ON r.Id_rubrique = ru.Id_rubrique
JOIN 
    promotion p ON r.Code_promotion = p.Code_promotion
JOIN (
    SELECT 
        Code_promotion,
        idAnnee_Acad,
        SUM(Montant) AS TotalMontant
    FROM 
        repartition
    WHERE 
        Code_promotion = :code_promotion
        AND idAnnee_Acad = :id_annee_academique
    GROUP BY 
        Code_promotion, idAnnee_Acad
) AS total ON r.Code_promotion = total.Code_promotion AND r.idAnnee_Acad = total.idAnnee_Acad
WHERE 
    r.Code_promotion = :code_promotion
    AND r.idAnnee_Acad = :id_annee_academique
ORDER BY 
    ru.Libelle;";



    /* la requette pour toute l'université

    SELECT 
    ru.Libelle AS Libelle_rubrique,
    ROUND(SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)), 2) AS Montant_rubrique_global,
    ROUND(SUM(pf.Montant_paie), 2) AS Montant_total_paye
FROM 
    repartition r
JOIN rubrique ru ON ru.Id_rubrique = r.Id_rubrique
JOIN promotion p ON p.Code_Promotion = r.Code_Promotion
JOIN passer_par pp ON pp.Code_Promotion = p.Code_Promotion AND pp.idAnnee_academique = r.idAnnee_Acad
JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
JOIN frais fr ON fr.idFrais = pf.idFrais 
              AND fr.Code_Promotion = pp.Code_Promotion 
              AND fr.idAnnee_Acad = pp.idAnnee_academique
JOIN (
    SELECT 
        Code_Promotion,
        idAnnee_Acad,
        SUM(Montant) AS TotalMontant
    FROM 
        repartition
    WHERE 
        idAnnee_Acad = 28
    GROUP BY 
        Code_Promotion, idAnnee_Acad
) AS repartition_total 
    ON repartition_total.Code_Promotion = r.Code_Promotion 
    AND repartition_total.idAnnee_Acad = r.idAnnee_Acad
WHERE 
    r.idAnnee_Acad = 28
    AND pf.Motif_paie = 'Frais Académiques'
GROUP BY 
    ru.Id_rubrique, ru.Libelle
ORDER BY 
    ru.Libelle;



    */

        $stmt=$con->prepare($sql);
        $stmt->bindParam(':code_promotion', $code_promotion);
        $stmt->bindParam(':id_annee_academique', $id_annee_academique);

      $stmt->execute();
    
    
    
}else if ($action == "affiche_promotion") {
    // affichage promotion
    $idFiliere = $_GET['valeurFiliere'];
    

    $rqt_promotion="SELECT promotion.Code_Promotion, mentions.Libelle_mention as nom_mention,mentions.idMentions as id_mention, concat(promotion.Abréviation,' - ',mentions.Libelle_mention) as Promotion from promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE filiere.IdFiliere=:idFiliere ORDER BY promotion.Abréviation ASC";

    $stmt=$con->prepare($rqt_promotion);
    $stmt->bindParam(':idFiliere', $idFiliere);


} else if ($action=="enregistrer_ajout_rubrique") {
    $anneeAcademique = $_GET['anneeAcademique'];
    $promotion = $_GET['promotion'];
    $rubrique = $_GET['rubrique'];
    $montant_rubrique = $_GET['montant_rubrique'];

    $rqt_ajout_rubrique="INSERT INTO `repartition`( `Id_rubrique`, `Code_promotion`, `idAnnee_Acad`, `Montant`) VALUES ('$rubrique','$promotion','$anneeAcademique','$montant_rubrique')";

    $stmt=$con->prepare($rqt_ajout_rubrique);
    

}else if ($action=="enregistrer_nouvelle_rubrique") {
    $libelle_rubrique = addslashes($_GET['libelle_rubrique']);
    $id_rubrique = (int) $_GET['id_rubrique'];
    $categorie_rubrique = $_GET['categorie_rubrique'];
    echo $id_rubrique;
    $rqt_nouvelle_rubrique="INSERT INTO `rubrique`(`Id_rubrique`, `Libelle`, `Categorie`) VALUES ($id_rubrique,'$libelle_rubrique','$categorie_rubrique')";
    
    $stmt=$con->prepare($rqt_nouvelle_rubrique);

}else if ($action=="tableau_rubrique_reelle") {
    $valeurRubrique = $_GET['valeurRubrique'];
$valeurFiliere = $_GET['valeurFiliere'];
$anneeAcademique = $_GET['anneeAcademique'];

$rqt_nouvelle_rubrique_reelle = "
SELECT 
    r.Code_promotion,
    m.Libelle_Mention,
    CONCAT(prom.Libelle_promotion, ' ', m.Libelle_Mention) AS Libelle_promotion_mention,
    rub.Libelle AS Rubrique,
    total_paiement.Total_paye AS Montant_total_paye,
    ROUND((r.Montant / total_rep.Montant_total) * 100, 2) AS Pourcentage_utilise,
    ROUND(
        (r.Montant / total_rep.Montant_total) * total_paiement.Total_paye, 
        2
    ) AS Montant_total_rubrique
FROM 
    repartition r
JOIN rubrique rub ON rub.Id_rubrique = r.Id_rubrique
JOIN promotion prom ON prom.Code_Promotion = r.Code_promotion
JOIN mentions m ON m.idMentions = prom.idMentions
JOIN filiere f ON f.IdFiliere = m.IdFiliere
JOIN (
    SELECT 
        Code_promotion, idAnnee_Acad, SUM(Montant) AS Montant_total
    FROM 
        repartition
    WHERE 
        idAnnee_Acad = :id_annee_academique
    GROUP BY Code_promotion, idAnnee_Acad
) AS total_rep ON total_rep.Code_promotion = r.Code_promotion 
               AND total_rep.idAnnee_Acad = r.idAnnee_Acad
JOIN (
    SELECT 
        passer_par.Code_Promotion, passer_par.idAnnee_academique, SUM(payer_frais.Montant_paie) AS Total_paye
    FROM 
        passer_par
    JOIN 
        payer_frais ON payer_frais.Matricule = passer_par.Etudiant_Matricule
    JOIN 
        frais ON frais.idFrais = payer_frais.idFrais 
              AND frais.Code_Promotion = passer_par.Code_Promotion 
              AND frais.idAnnee_Acad = passer_par.idAnnee_academique
    WHERE 
        passer_par.idAnnee_academique = :id_annee_academique
        AND payer_frais.Motif_paie = 'Frais Académiques'
    GROUP BY passer_par.Code_Promotion, passer_par.idAnnee_academique
) AS total_paiement ON total_paiement.Code_Promotion = r.Code_promotion 
                   AND total_paiement.idAnnee_academique = r.idAnnee_Acad
WHERE 
    r.Id_rubrique = :Id_rubrique
    AND r.idAnnee_Acad = :id_annee_academique
    AND f.IdFiliere = :id_filiere;

";

$stmt = $con->prepare($rqt_nouvelle_rubrique_reelle);
$stmt->bindParam(':Id_rubrique', $valeurRubrique);
$stmt->bindParam(':id_filiere', $valeurFiliere);
$stmt->bindParam(':id_annee_academique', $anneeAcademique);

    

}elseif ($action=="tableau_general") {

$anneeAcademique = $_GET['anneeAcademique'];
$Id_rubrique_general = $_GET['Id_rubrique_general'];

    $rqt_general="SELECT 
    f.Libelle_filiere,
    SUM(pf.Montant_paie) AS Montant_total_paye,
    ROUND(AVG(r.Montant), 2) AS Pourcentage_rubrique,
    ROUND((AVG(r.Montant) / 100) * SUM(pf.Montant_paie), 2) AS Montant_rubrique
FROM 
    filiere f
JOIN mentions m ON m.IdFiliere = f.IdFiliere
JOIN promotion p ON p.idMentions = m.idMentions
JOIN repartition r ON r.Code_promotion = p.Code_Promotion
JOIN passer_par pp ON pp.Code_Promotion = p.Code_Promotion 
                   AND pp.idAnnee_academique = r.idAnnee_Acad
JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
JOIN frais fr ON fr.idFrais = pf.idFrais 
              AND fr.Code_Promotion = pp.Code_Promotion 
              AND fr.idAnnee_Acad = pp.idAnnee_academique
WHERE 
    r.idAnnee_Acad = :anneeAcademique
    AND r.Id_rubrique = :Id_rubrique_general
    AND pf.Motif_paie = 'Frais Académiques'
GROUP BY 
    f.IdFiliere, f.Libelle_filiere;
";


$stmt = $con->prepare($rqt_general);

$stmt->bindParam(':anneeAcademique', $anneeAcademique);
$stmt->bindParam(':Id_rubrique_general', $Id_rubrique_general);




}elseif ($action=="tableau_general_tout") {

$anne_academique_global_tout = $_GET['anne_academique_global_tout'];
$Id_filiere_general_tout = $_GET['Id_filiere_general_tout'];

    $rqt_general="SELECT 
    ru.Libelle AS Libelle_rubrique,
    ROUND(SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)), 2) AS Montant_rubrique_global,
    ROUND(SUM(pf.Montant_paie), 2) AS Montant_total_paye,
    ROUND(100 * SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)) / SUM(pf.Montant_paie), 2) AS Pourcentage_global
FROM 
    repartition r
JOIN rubrique ru ON ru.Id_rubrique = r.Id_rubrique
JOIN promotion p ON p.Code_Promotion = r.Code_Promotion
JOIN mentions m ON m.idMentions = p.idMentions
JOIN filiere f ON f.IdFiliere = m.IdFiliere
JOIN passer_par pp ON pp.Code_Promotion = p.Code_Promotion AND pp.idAnnee_academique = r.idAnnee_Acad
JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
JOIN frais fr ON fr.idFrais = pf.idFrais 
              AND fr.Code_Promotion = pp.Code_Promotion 
              AND fr.idAnnee_Acad = pp.idAnnee_academique
JOIN (
    SELECT 
        Code_Promotion,
        idAnnee_Acad,
        SUM(Montant) AS TotalMontant
    FROM 
        repartition
    WHERE 
        idAnnee_Acad = :anne_academique_global_tout
    GROUP BY 
        Code_Promotion, idAnnee_Acad
) AS repartition_total 
    ON repartition_total.Code_Promotion = r.Code_Promotion 
    AND repartition_total.idAnnee_Acad = r.idAnnee_Acad
WHERE 
    r.idAnnee_Acad = :anne_academique_global_tout
    AND f.IdFiliere = :Id_filiere_general_tout
    AND pf.Motif_paie = 'Frais Académiques'
GROUP BY 
    ru.Id_rubrique, ru.Libelle
ORDER BY 
    ru.Libelle;


";


$stmt = $con->prepare($rqt_general);

$stmt->bindParam(':anne_academique_global_tout', $anne_academique_global_tout);
$stmt->bindParam(':Id_filiere_general_tout', $Id_filiere_general_tout);


}elseif ($action=="tableau_general_tout_1") {

$anne_academique_global_tout_1 = $_GET['anne_academique_global_tout_1'];

    $rqt_general="SELECT 
    ru.Libelle AS Libelle_rubrique,
    ROUND(SUM(pf.Montant_paie * (r.Montant / repartition_total.TotalMontant)), 2) AS Montant_rubrique_global,
    ROUND(SUM(pf.Montant_paie), 2) AS Montant_total_paye
FROM 
    repartition r
JOIN rubrique ru ON ru.Id_rubrique = r.Id_rubrique
JOIN promotion p ON p.Code_Promotion = r.Code_Promotion
JOIN passer_par pp ON pp.Code_Promotion = p.Code_Promotion AND pp.idAnnee_academique = r.idAnnee_Acad
JOIN payer_frais pf ON pf.Matricule = pp.Etudiant_Matricule
JOIN frais fr ON fr.idFrais = pf.idFrais 
              AND fr.Code_Promotion = pp.Code_Promotion 
              AND fr.idAnnee_Acad = pp.idAnnee_academique
JOIN (
    SELECT 
        Code_Promotion,
        idAnnee_Acad,
        SUM(Montant) AS TotalMontant
    FROM 
        repartition
    WHERE 
        idAnnee_Acad = :anne_academique_global_tout_1
    GROUP BY 
        Code_Promotion, idAnnee_Acad
) AS repartition_total 
    ON repartition_total.Code_Promotion = r.Code_Promotion 
    AND repartition_total.idAnnee_Acad = r.idAnnee_Acad
WHERE 
    r.idAnnee_Acad = :anne_academique_global_tout_1
    AND pf.Motif_paie = 'Frais Académiques'
GROUP BY 
    ru.Id_rubrique, ru.Libelle
ORDER BY 
    ru.Libelle;


";


$stmt = $con->prepare($rqt_general);

$stmt->bindParam(':anne_academique_global_tout_1', $anne_academique_global_tout_1);
}
    $stmt->execute();
    $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);

     ?>


