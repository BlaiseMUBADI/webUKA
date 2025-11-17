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

    // FONCTION DE GENERATION DU NUMERO DE RECU
    // Format: AAMMJJ-XXXX (date + séquence journalière par agent)
    function generer_numero_recu($con, $date_paiement, $mat_agent) {
        // IMPORTANT: Extraire uniquement la partie DATE (sans l'heure) de $date_paiement
        // car $date_paiement peut contenir datetime complet : "2025-03-27 09:38:48"
        $date_only = date('Y-m-d', strtotime($date_paiement)); // Résultat: 2025-03-27
        
        // 1. Extraire la partie date au format AAMMJJ (Année-Mois-Jour sur 2 chiffres)
        $date_partie = date('ymd', strtotime($date_paiement)); // Format: 251116 pour 2025-11-16
        
        // 2. Compter le nombre de reçus déjà émis pour cette date et cet agent
        // Note: Date_paie est de type DATETIME dans MySQL
        $nb_recu_jour = 0;
        $sql_nb_recu = "SELECT COUNT(payer_frais.Id_payer_frais) as nb_recu
        FROM payer_frais 
        WHERE DATE(payer_frais.Date_paie) = ? AND payer_frais.Mat_agent = ?";
        
        $stmt = $con->prepare($sql_nb_recu);    
        $stmt->execute([$date_only, $mat_agent]);
        
        $ligne = $stmt->fetch();
        if($ligne) {
            $nb_recu_jour = $ligne['nb_recu'];
        }
        
        // 3. Incrémenter pour obtenir le numéro du reçu actuel
        $numero_sequence = $nb_recu_jour + 1;
        
        // 4. Formater le numéro de séquence sur 4 chiffres (0001, 0002, etc.)
        $numero_sequence_format = str_pad($numero_sequence, 4, '0', STR_PAD_LEFT);
        
        // 5. Construire le numéro de reçu final: AAMMJJ-XXXX
        $numero_recu = $date_partie . "-" . $numero_sequence_format;
        
        return $numero_recu;
    }

    $con->beginTransaction();

    // Tableau pour stocker les numéros de reçu générés
    $numeros_recus = array();

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
        
        // Génération du numéro de reçu
        $numero_recu = generer_numero_recu($con, $date_paiement, $mat_agent);
        
        if($devise_paie==="Franc Congolais")
        {

            $montant_paie_en_fc=$motant_inverse*$taux_dollar;
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Ensemble,Fc,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:ensemnle,:montant_en_fc,:numero_recu)";
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
            $stmt->bindParam(':numero_recu', $numero_recu);

            if($stmt->execute()) {
                $numeros_recus[] = $numero_recu;
            }
            else echo "\n\nimpossible de faire cet enregistrment \n\n";
        }
        else
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Ensemble,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:ensemnle,:numero_recu)";
            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':ensemnle', $ensemble);
            $stmt->bindParam(':numero_recu', $numero_recu);

            if($stmt->execute()) {
                $numeros_recus[] = $numero_recu;
            }
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

            // Génération du numéro de reçu pour l'enrôlement
            $numero_recu_enrol = generer_numero_recu($con, $date_paiement, $mat_agent);

            if($devise_paie==="Franc Congolais")
            {
                
                $montant_paye_en_fc=$motant_inverse*$taux_dollar;
                

                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Ensemble,Fc,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                    :Mat_agent,:Montant_paier,:Motif_paie,'Ensemble',:montant_en_fc,:numero_recu)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':montant_en_fc', $montant_paye_en_fc);
                $stmt->bindParam(':numero_recu', $numero_recu_enrol);
                
                if($stmt->execute()) {
                    $numeros_recus[] = $numero_recu_enrol;
                }
                else echo "\n\nimpossible de faire cet enregistrment \n\n";
            }
            else
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                    Mat_agent,Montant_paie,Motif_paie,Ensemble,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                    :Mat_agent,:Montant_paier,:Motif_paie,'Ensemble',:numero_recu)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':numero_recu', $numero_recu_enrol);
                
                if($stmt->execute()) {
                    $numeros_recus[] = $numero_recu_enrol;
                }
                else echo "\n\nimpossible de faire cet enregistrment \n\n";

            }
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



            // Génération du numéro de reçu pour chaque enrôlement
            $numero_recu = generer_numero_recu($con, $date_paiement, $mat_agent);

            if($devise_paie==="Franc Congolais")
            {
                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie,Fc,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie,:montant_en_fc,:numero_recu)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':montant_en_fc', $montant_en_fc);
                $stmt->bindParam(':numero_recu', $numero_recu);
                
                if($stmt->execute()) {
                    $numeros_recus[] = $numero_recu;
                }
                else echo "\n\nimpossible de faire cet enregistrment \n\n";

            }
            else
            {

                $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
                Mat_agent,Montant_paie,Motif_paie,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
                :Mat_agent,:Montant_paier,:Motif_paie,:numero_recu)";
                $stmt = $con->prepare($sql_insert_paiement);
                $stmt->bindParam(':mat_etudiant', $mat_etudiant);
                $stmt->bindParam(':idFrais', $idFrais);
                $stmt->bindParam(':Date_paie', $date_paiement);
                $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
                $stmt->bindParam(':Mat_agent', $mat_agent);
                $stmt->bindParam(':Montant_paier', $motant_inverse);
                $stmt->bindParam(':Motif_paie', $motif_paiement);
                $stmt->bindParam(':numero_recu', $numero_recu);
                
                if($stmt->execute()) {
                    $numeros_recus[] = $numero_recu;
                }
                else echo "\n\nimpossible de faire cet enregistrment \n\n";
            
            }



            

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

        // Génération du numéro de reçu
        $numero_recu = generer_numero_recu($con, $date_paiement, $mat_agent);

        // Ici on test pour specfier l'insertion afin de savoir si l'argent est percu en fr ou en dollar

        if($devise_paie==="Franc Congolais")
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,Fc,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:montant_en_fc,:numero_recu)";


            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':montant_en_fc', $montant_en_fc);
            $stmt->bindParam(':numero_recu', $numero_recu);
            
            if($stmt->execute()) {
                $numeros_recus[] = $numero_recu;
            }
            else echo "\n\nimpossible de faire cet enregistrment \n\n";

        }
        else
        {
            $sql_insert_paiement = "INSERT INTO  payer_frais(Matricule,idFrais,Date_paie,idLieu_paiement,
            Mat_agent,Montant_paie,Motif_paie,numero_recu) VALUES (:mat_etudiant,:idFrais,:Date_paie,:idLieu_paiement,
            :Mat_agent,:Montant_paier,:Motif_paie,:numero_recu)";

            $stmt = $con->prepare($sql_insert_paiement);
            $stmt->bindParam(':mat_etudiant', $mat_etudiant);
            $stmt->bindParam(':idFrais', $idFrais);
            $stmt->bindParam(':Date_paie', $date_paiement);
            $stmt->bindParam(':idLieu_paiement',$Id_lieupaiement);
            $stmt->bindParam(':Mat_agent', $mat_agent);
            $stmt->bindParam(':Montant_paier', $motant_inverse);
            $stmt->bindParam(':Motif_paie', $motif_paiement);
            $stmt->bindParam(':numero_recu', $numero_recu);
            
            if($stmt->execute()) {
                $numeros_recus[] = $numero_recu;
            }
            else echo "\n\nimpossible de faire cet enregistrment \n\n";
        }


    }

    
        


       
        

    $con->commit();
    
    // Retourner les numéros de reçu générés en JSON
    echo json_encode(array(
        'success' => true,
        'numeros_recus' => $numeros_recus
    ));
} 
catch(PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $con->rollback();
    echo json_encode(array(
        'success' => false,
        'error' => $e->getMessage()
    ));
}




?>

