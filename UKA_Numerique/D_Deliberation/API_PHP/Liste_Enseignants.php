<?php
 session_start();
 include("../../../Connexion_BDD/Connexion_1.php");

 

 
 $id_filiere=$_SESSION['id_fac'];

$sql_select="select agent.Mat_agent as mat_agent,
concat(agent.Nom_agent,' ',agent.Post_agent,' ',agent.Prenom) as enseignant,
agent.Sexe as sexe,
agent.Niveau_Etude as niveau_etude,
agent.Categorie as categorie,
agent.Numero_Tel as phone,
agent.Adresse_Mail as email,
agent.Adresse_Mail as adresse,
agent.Photo_profil as photo,
infos_enseignants.Titre_academique as titre_academque,
infos_enseignants.Domaine as domaine,
infos_enseignants.Institution_attache as institut_attache,
filiere.Libelle_Filiere as filiere

from agent,infos_enseignants,filiere
where agent.Mat_agent=infos_enseignants.Mat_agent
and infos_enseignants.IdFiliere_Attache=filiere.IdFiliere

ORDER BY CASE WHEN filiere.IdFiliere =:idfiliere THEN 1 ELSE 2 END, 
agent.Nom_agent, agent.Post_agent, agent.Prenom
";
$stmt=$con->prepare($sql_select);
$stmt->bindParam(':idfiliere',$id_filiere);
$stmt->execute();

$etud=array();
while($ligne = $stmt->fetch()) $etud[]=$ligne;
echo json_encode($etud);

?>

