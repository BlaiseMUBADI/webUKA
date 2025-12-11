<?php
 //include("../../../connexion_BDD/connexion_1.php");
 include("../../../Connexion_BDD/Connexion_1.php");

$valeur_envoyee = $_GET['text'];
$Matricule = $_GET['matricule'];
$zone = $_GET['zone'];



if($zone =="zone_nom"){
        $sql_update="UPDATE etudiant SET Nom=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_postnom") {
        $sql_update="UPDATE `etudiant` SET `Postnom`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_prenom") {
        $sql_update="UPDATE `etudiant` SET `Prenom`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_sexe") {
        $sql_update="UPDATE `etudiant` SET `Sexe`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_lieu_naiss") {
        $sql_update="UPDATE `etudiant` SET `LieuNaissance`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_date_naiss") {
        $sql_update="UPDATE `etudiant` SET `DateNaissance`=:val WHERE Matricule=:mat_etudiant";



}elseif ($zone =="zone_etat_civil") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `EtatCiv`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_nationalite") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Nationalite`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_adresse_actuelle") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `AdresseActuelle`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_religion") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Religion`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_paroisse") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Paroisse`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_diocese") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Diocese`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_nom_pere") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `NomPere`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_profession_pere") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `ProfPere`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_nom_mere") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `NomMere`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_profession_mere") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `ProfMere`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_province_origine") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `ProvinceOrigine`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_territoire_origine") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Territoire`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_contact_responsable") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `TelResponsable`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_num_airtel") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `TelAirtel`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_num_vodacom") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `TelVoda`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_num_orange") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `TelOrange`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_anne_scolaire_primaire") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Annscol`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_etablissement_primaire") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `NomEtablis`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_pourc_certificat_primaire") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `PourceCertificat`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_anne_scolaire") {

//8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888
        $sql_update="UPDATE `autreinfo_etudiant` SET `Annscol`=:val WHERE Matricule=:mat_etudiant";
//8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888

}elseif ($zone =="zone_province_educationnelle") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Province`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_ecole_provenance") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Ecole`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_section") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `SetionEtude`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_option") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `OptionEtude`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_pourcentage_diplome") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `PourceDiplome`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_num_dip") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `NumDiplom`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_lieu_delivrance") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Lieudelivrance`=:val WHERE Matricule=:mat_etudiant";
}elseif ($zone =="zone_date_delivrance") {
        $sql_update="UPDATE `autreinfo_etudiant` SET `Datedelivrance`=:val WHERE Matricule=:mat_etudiant";
}

    $stmt = $con1 ->prepare($sql_update);
    $stmt->bindParam(':val', $valeur_envoyee);
    $stmt->bindParam(':mat_etudiant', $Matricule);
   // echo "la veulleur de sql :".$sql_update;
    $stmt->execute();



?>
