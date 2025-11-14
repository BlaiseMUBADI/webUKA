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
    
    $idbanque=$_POST['idbanque'];
    $numero_bordereau=$_POST['numero_borderau'];

    $Id_lieupaiement=$idbanque;
    $mat_agent=$_SESSION['MatriculeAgent'];

    // Ici récuperaion de JSON envoyé depuis javascript
    $tab_motif_paiement=json_decode($_POST['motif_paiement'], true);
    
    $motif_paiement="";

    $type_frais=$_POST['type_frais'];
    $date_paiement=$_POST['date_paiement'];
    

    $con->beginTransaction();

try
{
    if($type_frais=="Frais Académiques et Enrôlement à la Session")
    {
        //echo " je suis dans FA et Enrole";
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
       
        $montant_inserer="";
        
        if($somme_deja_payer<$tranche_base)
        {

            // On test si un étudiant a déjç payé le frais academique pour retrancher l'enrolement
            
            $dif=$tranche_base-$somme_deja_payer;
            $dif_1=$montant_payer-$dif;          
            
            if($dif_1>0 && $dif_1<=$montant_base_enrol) 
            {
                $montant_inserer=$dif;
                $montant_payer=$dif_1;
                $ensemnle="Ensemble";               
            }
            if($dif_1>0 && $dif_1>$montant_base_enrol)
            {
                $montant_inserer=$dif+($dif_1-$montant_base_enrol);
                $montant_payer=$montant_base_enrol;

                
                $ensemnle="Ensemble";  

            }
            if($dif_1<=0)
            {
                $montant_inserer=$montant_payer;
                $montant_payer=0;  
                
                $ensemnle=null;
            }
            
            
        }
        else
        {
            
            $dif=$montant_base-$somme_deja_payer;
            $dif_1=$montant_payer-$dif;

            if($dif_1>0 && $dif_1<=$montant_base) 
            {
                $montant_inserer=$dif;
                 $montant_payer=$dif_1; 
                 
                $ensemnle="Ensemble";                
            }
            if($dif_1>0 && $dif_1>$montant_base)
            {
                $montant_inserer=$dif+($dif_1-$montant_base_enrol);
                $montant_payer=$montant_base_enrol;

                
                $ensemnle="Ensemble";  
            }
            if($dif_1<=0)
            {
                $montant_inserer=$montant_payer;
                $montant_payer=0; 
                
                $ensemnle=null;   
            }
        }


        
        $motif_paiement="Frais Académiques";

        if($devise_paie==="Franc Congolais")
        {
                $montant_paye_en_fc=$montant_inserer*$taux_dollar;

                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Numero_bordereau,Ensemble,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                    :Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau,:ensemble,:montant_en_fc)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $montant_inserer);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':num_bordereau', $numero_bordereau);
                $stmt->bindParam(':ensemble', $ensemnle);
                $stmt->bindParam(':montant_en_fc', $montant_paye_en_fc);
        }
        else
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie,Numero_bordereau,Ensemble) 
                VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau,:ensemble)";
            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $montant_inserer);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':num_bordereau', $numero_bordereau);
            $stmt->bindParam(':ensemble', $ensemnle);

        }
        if($stmt->execute()) echo "\n\nOk\n\n";
        else echo "\n\nimpossible de faire cet enregistrment \n\n";

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
            $montant_inserer=$montant_payer;


            if($devise_paie==="Franc Congolais")
            {
                $montant_paye_en_fc=$montant_inserer*$taux_dollar;

                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Numero_bordereau,Ensemble,Fc) 
                    VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement
                    ,:Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau,'Ensemble',:montant_en_fc)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $montant_inserer);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':num_bordereau', $numero_bordereau);
                $stmt->bindParam(':montant_en_fc', $montant_paye_en_fc);


            }
            else
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Numero_bordereau,Ensemble) 
                    VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement
                    ,:Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau,'Ensemble')";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $montant_inserer);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':num_bordereau', $numero_bordereau);

                

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
            $montant_inserer="";
            if(($montant_payer-$montant_base)>=0)
            {
                if($i==count($tab_motif_paiement)) $montant_inserer=$montant_payer;
                else
                {
                    $montant_inserer=$montant_base;
                    $montant_payer=$montant_payer-$montant_base;

                }
            }
            else $montant_inserer=$montant_payer;
            $i++;


            if($devise_paie==="Franc Congolais")
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie,Numero_bordereau,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau,:montant_en_fc)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $montant_inserer);
                $stmt->bindParam(':Motif_paie', $motif_paiement);        
                $stmt->bindParam(':num_bordereau', $numero_bordereau); 
                $stmt->bindParam(':montant_en_fc', $montant_en_fc);
            }
            else
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie,Numero_bordereau) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $montant_inserer);
                $stmt->bindParam(':Motif_paie', $motif_paiement);        
                $stmt->bindParam(':num_bordereau', $numero_bordereau);

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
        $montant_inserer=$montant_payer;


        if($devise_paie==="Franc Congolais")
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Numero_bordereau,Fc) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau,:montant_en_fc)";
            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $montant_inserer);
            $stmt->bindParam(':Motif_paie', $motif_paiement);        
            $stmt->bindParam(':num_bordereau', $numero_bordereau);  
            $stmt->bindParam(':montant_en_fc', $montant_en_fc);

        }
        else
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Numero_bordereau) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:num_bordereau)";
            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $montant_inserer);
            $stmt->bindParam(':Motif_paie', $motif_paiement);        
            $stmt->bindParam(':num_bordereau', $numero_bordereau);

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

