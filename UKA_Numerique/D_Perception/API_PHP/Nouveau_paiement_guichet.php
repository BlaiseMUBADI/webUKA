<?php
    session_start();
    include("../../../Connexion_BDD/Connexion_1.php");

    
    $mat_etudiant=$_POST['mat_etudiant'];

    $devise_paie=$_POST['devise_paye']; // Cette variable permet de faire le rapport en franc et en dollar
    $montant_en_fc=$_POST['montant_en_fc'];
    $taux_dollar=$_POST['Taux_dollar'];
    //echo " Voici le de devise $devise_paie";

    $Id_an_acad=$_POST['Id_an_acad'];
    $code_promo=$_POST['code_promo'];
    $montant_payer=$_POST['montant_payer'];

    $motant_inverse=$_POST['montant_inverse'];

    // Ici récuperaion de JSON envoyé depuis javascript
    $tab_motif_paiement=json_decode($_POST['motif_paiement'], true);

    $Id_lieupaiement=3;
    $mat_agent=$_SESSION['MatriculeAgent'] ;
    
    $motif_paiement="";

    $type_frais=$_POST['type_frais'];
    $date_paiement=$_POST['date_paiement'];

    $con->beginTransaction();

try
{
    if($type_frais=="Frais Académiques et Enrôlement à la Session")
    {
        /*
        * Nous sommes entrain de traiter d'abord l'insertion de frais académique
        */

        // Ici on récupere la somme fixée pour le frai d'enrolement 

        $type_frais="Enrôlement à la Session";
        $sql_frais="
        SELECT frais.idFrais,frais.Montant
        FROM frais,annee_academique,promotion 
        WHERE frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
        and frais.Code_Promotion=promotion.Code_Promotion 
        and promotion.Code_Promotion=:code_promo 
        and annee_academique.idAnnee_Acad=:idannee_acad
        and Libelle_Frais=:lib_frais";

        $stmt=$con->prepare($sql_frais);
        $stmt->bindParam(':code_promo',$code_promo);
        $stmt->bindParam(':idannee_acad',$Id_an_acad);    
        $stmt->bindParam(':lib_frais',$type_frais);
        $stmt->execute();
        
        $montant_base_enrol="";
        while($ligne = $stmt->fetch()) $montant_base_enrol=$ligne['Montant'];
        




        // Ici on récupere la somme fixée pour le frais académique, l'IdFrais et la première tranche
        $type_frais="Frais Académiques";
        // Ici on fait pour récupere l' ID frais
        $sql_frais="
        SELECT frais.idFrais,frais.Montant,frais.Tranche
        FROM frais,annee_academique,promotion 
        WHERE frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
        and frais.Code_Promotion=promotion.Code_Promotion 
        and promotion.Code_Promotion=:code_promo 
        and annee_academique.idAnnee_Acad=:idannee_acad
        and Libelle_Frais=:lib_frais";

        $stmt=$con->prepare($sql_frais);
        $stmt->bindParam(':code_promo',$code_promo);
        $stmt->bindParam(':idannee_acad',$Id_an_acad);    
        $stmt->bindParam(':lib_frais',$type_frais);
        $stmt->execute();
        
        $idFrais="";
        $montant_base="";
        $tranche_base="";
        while($ligne = $stmt->fetch())
        {
            $idFrais=$ligne['idFrais'];
            $montant_base=$ligne['Montant'];
            $tranche_base=$ligne['Tranche'];
        
        }

        // Ici on récupère ce que l'étudiant à déjà payé pour le frais academique
        $sql_select_acces="select SUM(payer_frais.Montant_paie) as somme_paier
                from etudiant,payer_frais,annee_academique,frais
                where etudiant.Matricule=payer_frais.Matricule 
                and payer_frais.idFrais=frais.idFrais 
                and frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
                and annee_academique.idAnnee_Acad=:id_annee_acad
                and etudiant.Matricule=:mat_etudiant
                and payer_frais.idFrais=:idFrais"; 
    
        $stmt=$con->prepare($sql_select_acces);
        $stmt->bindParam(':id_annee_acad',$Id_an_acad);
        $stmt->bindParam(':mat_etudiant',$mat_etudiant);
        $stmt->bindParam(':idFrais',$idFrais);
        $stmt->execute();
        $somme_deja_payer="";
        while($ligne = $stmt->fetch()) $somme_deja_payer=$ligne['somme_paier'];
       
        $motant_inverse="";
        
        if($somme_deja_payer<$tranche_base)
        {

            // On test si un étudiant a déjà payé le frais academique pour retrancher l'enrolement
            
            $dif=$tranche_base-$somme_deja_payer;
            $dif_1=$montant_payer-$dif;          
            
            if($dif_1>0 && $dif_1<=$montant_base_enrol) 
            {
                $motant_inverse=$dif;
                $montant_payer=$dif_1; 
                
                $ensemble="Ensemble";            
            }
            if($dif_1>0 && $dif_1>$montant_base_enrol)
            {
                $motant_inverse=$dif+($dif_1-$montant_base_enrol);
                $montant_payer=$montant_base_enrol;
                
                $ensemble="Ensemble";
                

            }
            if($dif_1<=0)
            {
                $motant_inverse=$montant_payer;
                $montant_payer=0;  

                $ensemble=null;
            }
            
            
        }
        else
        {
            
            $dif=$montant_base-$somme_deja_payer;
            $dif_1=$montant_payer-$dif;

            if($dif_1>0 && $dif_1<=$montant_base) 
            {
                $motant_inverse=$dif;
                $montant_payer=$dif_1;   
                
                $ensemble="Ensemble";          
            }
            if($dif_1>0 && $dif_1>$montant_base)
            {
                $motant_inverse=$dif+($dif_1-$montant_base_enrol);
                $montant_payer=$montant_base_enrol;

                $ensemble="Ensemble";
            }
            if($dif_1<=0)
            {
                $motant_inverse=$montant_payer;
                $montant_payer=0;  

                $ensemble=null;
            }

        }


        $motif_paiement="Frais Académiques";
        
        if($devise_paie==="Franc Congolais")
        {

            $montant_paie_en_fc=$motant_inverse*$taux_dollar;
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Ensemble,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:ensemnle,:montant_en_fc)";
            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':ensemnle', $ensemble);
            $stmt->bindParam(':montant_en_fc',$montant_paie_en_fc);

            if($stmt->execute()) echo "\n\nOk\n\n";
            else echo "\n\nimpossible de faire cet enregistrment \n\n";
        }
        else
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Ensemble) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:ensemnle)";
            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':ensemnle', $ensemble);

            if($stmt->execute()) echo "\n\nOk\n\n";
            else echo "\n\nimpossible de faire cet enregistrment \n\n";
        }

        /*******************************FIN INSERTION POUR LE FRAIS ACADEMIQUE ********************************/

        
        
        /********************************************************************************************
        ***************** NOUS SOMMES ENTRAIN DE TRAITER L'INSERTION DE FRAIS D'ENROLEMENT***********
        *********************************************************************************************
        */
        if($montant_payer>0)
        {
            $type_frais="Enrôlement à la Session";
        // Ici on fait pour récupere l' ID frais
            $sql_frais="
            SELECT frais.idFrais,frais.Montant
            FROM frais,annee_academique,promotion 
            WHERE frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
            and frais.Code_Promotion=promotion.Code_Promotion 
            and promotion.Code_Promotion=:code_promo 
            and annee_academique.idAnnee_Acad=:idannee_acad
            and Libelle_Frais=:lib_frais";

            $stmt=$con->prepare($sql_frais);
            $stmt->bindParam(':code_promo',$code_promo);
            $stmt->bindParam(':idannee_acad',$Id_an_acad);    
            $stmt->bindParam(':lib_frais',$type_frais);
            $stmt->execute();
            
            $idFrais="";
            $montant_base="";
            while($ligne = $stmt->fetch())
            {
                $idFrais=$ligne['idFrais'];
                $montant_base=$ligne['Montant'];
            }



            $motif_paiement=$tab_motif_paiement[0];
            $motant_inverse=$montant_payer;

            if($devise_paie==="Franc Congolais")
            {
                
                $montant_paye_en_fc=$motant_inverse*$taux_dollar;
                

                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Ensemble,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                    :Mat_agent,:Montant_paier,:Motif_paie,'Ensemble',:montant_en_fc)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':montant_en_fc', $montant_paye_en_fc);

            }
            else
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Ensemble) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                    :Mat_agent,:Montant_paier,:Motif_paie,'Ensemble')";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);


            }
            
            if($stmt->execute()) echo "\n\nOk\n\n";
            else echo "\n\nimpossible de faire cet enregistrment \n\n";
        }

        

    }



     /***********************************************************************************************************
     ******** CE ELESE S'EXECUTE QUE LORQUE NOUS VOULONS INSERER QUE LE FRAIS ACADEMIQUE SEUL OU ENROLEMENT ****
     ***********************************************************************************************************
     */

    else if($type_frais=="Enrôlement à la Session")
    {

        
        $type_frais="Enrôlement à la Session";
        // Ici on fait pour récupere l' ID frais
        $sql_frais="
        SELECT frais.idFrais,frais.Montant
        FROM frais,annee_academique,promotion 
        WHERE frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
        and frais.Code_Promotion=promotion.Code_Promotion 
        and promotion.Code_Promotion=:code_promo 
        and annee_academique.idAnnee_Acad=:idannee_acad
        and Libelle_Frais=:lib_frais";

        $stmt=$con->prepare($sql_frais);
        $stmt->bindParam(':code_promo',$code_promo);
        $stmt->bindParam(':idannee_acad',$Id_an_acad);    
        $stmt->bindParam(':lib_frais',$type_frais);
        $stmt->execute();
        
        $idFrais="";
        $montant_base="";
        while($ligne = $stmt->fetch())
        {
            $idFrais=$ligne['idFrais'];
            $montant_base=$ligne['Montant'];
        }

        
        $i=1;
        foreach( $tab_motif_paiement as  $motif_paiement)
        {
            $motant_inverse="";
            if(($montant_payer-$montant_base)>=0)
            {
                if($i==count($tab_motif_paiement)) $motant_inverse=$montant_payer;
                else
                {
                    $motant_inverse=$montant_base;
                    $montant_payer=$montant_payer-$montant_base;

                }
            }
            else $motant_inverse=$montant_payer;
            $i++;



            if($devise_paie==="Franc Congolais")
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie,:montant_en_fc)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':montant_en_fc', $montant_en_fc);
            

            }
            else
            {

                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
            
            }
                
            if($stmt->execute()) echo "\n\nOk\n\n";
            else echo "\n\nimpossible de faire cet enregistrment \n\n";



            

        }

    }

    
   
    else if($type_frais=="Frais Académiques")
    {

        
        $type_frais="Frais Académiques";
        // Ici on fait pour récupere l' ID frais
        $sql_frais="
        SELECT frais.idFrais,frais.Montant
        FROM frais,annee_academique,promotion 
        WHERE frais.idAnnee_Acad=annee_academique.idAnnee_Acad 
        and frais.Code_Promotion=promotion.Code_Promotion 
        and promotion.Code_Promotion=:code_promo 
        and annee_academique.idAnnee_Acad=:idannee_acad
        and Libelle_Frais=:lib_frais";

        $stmt=$con->prepare($sql_frais);
        $stmt->bindParam(':code_promo',$code_promo);
        $stmt->bindParam(':idannee_acad',$Id_an_acad);    
        $stmt->bindParam(':lib_frais',$type_frais);
        $stmt->execute();
        
        $idFrais="";
        $montant_base="";
        while($ligne = $stmt->fetch())
        {
            $idFrais=$ligne['idFrais'];
            $montant_base=$ligne['Montant'];
        }

        
        $motif_paiement=$type_frais;
        $motant_inverse=$montant_payer;

        // Ici on test pour specfier l'insertion afin de savoir si l'argent est percu en fr ou en dollar

        if($devise_paie==="Franc Congolais")
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:montant_en_fc)";


            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':montant_en_fc', $montant_en_fc);

        }
        else
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie)";

            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
        }
        
        if($stmt->execute()) echo "\n\nOk\n\n";
        else echo "\n\nimpossible de faire cet enregistrment \n\n";


    }

    
        


       
        

    $con->commit();
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo "Erreur lors de l'insertion: " . $e->getMessage();
}




?>

