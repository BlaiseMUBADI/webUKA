<?php
include("../../../Connexion_BDD/Connexion_1.php");

$mat_agent=$_GET['mat_agent'];

$sql_select="select 
compte_agent.Id_Compte_agent as IdCompte_Agent
,compte_agent.Login as login
,compte_agent.Mot_passe as password
,compte_agent.Categorie as fonction
,filiere.Libelle_Filiere as faculte
,compte_agent.Etat as etat 
,compte_agent.Date_creation as date_creation
from agent,compte_agent
LEFT JOIN filiere ON compte_agent.Id_filiere = filiere.IdFiliere
where compte_agent.Mat_agent=agent.Mat_agent
and agent.Mat_agent=:mat
ORDER BY compte_agent.Id_Compte_agent DESC";

$stmt=$con->prepare($sql_select);
$stmt->bindParam(':mat',$mat_agent);
$stmt->execute();

$etud=array();
while($ligne = $stmt->fetch()) $etud[]=$ligne;
echo json_encode($etud);

?>

