<?php 
include("../../../Connexion_BDD/Connexion_1.php");
$id_budget = $_GET['id'];

$sql_select_acces="SELECT 
    r.Designation,
    r.Id_recette,
    p.Libelle_promotion,
    p.Abréviation AS Abreviation_Promotion,
    m.Libelle_mention,  -- Libellé de la mention
    f.Libelle_Frais,
    f.Montant * etudiant_count.Nombre_Etudiants AS Montant_Total
FROM 
    t_recettes_prevues r
JOIN 
    promotion p ON r.IdPromotion = p.Code_Promotion
JOIN 
    t_budget b ON r.Ref_budget = b.Ref_budget
JOIN 
    annee_academique aa ON b.Annee_debut = aa.Annee_debut
JOIN 
    frais f ON f.Code_Promotion = p.Code_Promotion
             AND f.idAnnee_Acad = aa.idAnnee_Acad
JOIN 
    mentions m ON m.idMentions = p.IdMentions  -- Récupération du Libelle_mention
JOIN 
    (
        SELECT 
            Code_Promotion, 
            idAnnee_academique, 
            COUNT(Etudiant_Matricule) AS Nombre_Etudiants
        FROM 
            passer_par
        GROUP BY 
            Code_Promotion, idAnnee_academique
    ) AS etudiant_count ON etudiant_count.Code_Promotion = p.Code_Promotion
                         AND etudiant_count.idAnnee_academique = aa.idAnnee_Acad
WHERE 
    r.Ref_budget = :id
ORDER BY 
    p.Libelle_promotion ASC;
  -- Tri croissant par libellé de promotion
";
    $stmt=$con->prepare($sql_select_acces);


    $stmt->bindParam(':id', $id_budget);
      $stmt->execute();
    
    
    $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);
//$bdd->close();
 ?>