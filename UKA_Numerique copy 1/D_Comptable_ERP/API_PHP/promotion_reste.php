<?php 
include("../../../Connexion_BDD/Connexion_1.php");
$id_budget = $_GET['id'];

$rqt="SELECT 
    MAX(p.Abréviation) AS Libelle_promotion,
    p.idMentions,
    MAX(m.IdFiliere) AS IdFiliere,
    MAX(m.Libelle_mention) AS Libelle_mention,
    MAX(f.Libelle_Frais) AS Libelle_Frais,
    MAX(f.Montant) AS Montant,
    p.Code_Promotion
FROM 
    t_budget b
JOIN 
    annee_academique aa ON b.Annee_debut = aa.Annee_debut
JOIN 
    mentions m ON m.IdFiliere = b.id_filiere
JOIN 
    promotion p ON p.idMentions = m.idMentions
JOIN 
    frais f ON f.Code_Promotion = p.Code_Promotion
          AND f.idAnnee_Acad = aa.idAnnee_Acad
WHERE 
    b.Ref_budget = :id
GROUP BY 
    p.idMentions, p.Code_Promotion
ORDER BY 
    Libelle_promotion ASC;

";
$stmt=$con->prepare($rqt);


    $stmt->bindParam(':id', $id_budget);
      $stmt->execute();
    
    
    $etud1=array();
    while($ligne = $stmt->fetch())
    {
        $etud1[]=$ligne;

    }

    //Renvoyer les resultats sous forme de json
   echo json_encode($etud1);

 ?>