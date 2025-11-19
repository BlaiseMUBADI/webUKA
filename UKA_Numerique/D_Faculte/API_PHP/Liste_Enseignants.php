<?php
session_start();
include("../../../Connexion_BDD/Connexion_1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id_filiere = $_SESSION['id_fac'];

//echo 'Voici  le numéro de la filière : ' . $id_filiere;
/*
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
    $sql_select="SELECT semestre.Id_Semestre as id_semestre
                , semestre.libelle_semestre as lib_semestre
                ,semestre.Niveau_semestre as niveau 
                FROM semestre;"; 
    $stmt=$con->prepare($sql_select);        
}
$mot_recherche=
*/


try {
    $sql_select = "CALL Liste_Enseignants_Aligner(:idfiliere)";
    $stmt = $con->prepare($sql_select);
    $stmt->bindParam(':idfiliere', $id_filiere);
    $stmt->execute();

    $agents = array();
    while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $agents[] = $ligne;
        
    }
    
    // Retourner les données avec l'ID de la filière de l'utilisateur
    $response = array(
        'id_filiere_user' => $id_filiere,
        'enseignants' => $agents
    );
    
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de la récupération des agents: " . $e->getMessage()]);
}
?>