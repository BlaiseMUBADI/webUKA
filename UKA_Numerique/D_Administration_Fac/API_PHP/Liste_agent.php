<?php
include("../../../Connexion_BDD/Connexion_1.php");



$sql_select="";
if(@$_GET['mot_recherche'])
{
    $txt_mot_recherche="%".$_GET['mot_recherche']."%";
    $sql_select="select agent.Mat_agent as mat
        ,concat(agent.Nom_agent,' ',agent.Post_agent,' ',Prenom) as identite,agent.sexe as sexe 
        from agent where (agent.Nom_agent LIKE :mot OR agent.Mat_agent LIKE  :mot) 
        order by Nom_agent asc";
    
    $stmt=$con->prepare($sql_select);
    $stmt->bindParam(':mot',$txt_mot_recherche);

}
else
{
    $sql_select="select agent.Mat_agent as mat
        ,concat(agent.Nom_agent,' ',agent.Post_agent,' ',Prenom) as identite,agent.sexe as sexe 
        from agent order by Nom_agent asc"; 
    $stmt=$con->prepare($sql_select);        
}




$stmt->execute();

$etud=array();
while($ligne = $stmt->fetch()) $etud[]=$ligne;
echo json_encode($etud);

?>

