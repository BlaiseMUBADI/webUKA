<?php
    //include("Connexion.php");
include("../../Connexion_BDD/Connexion_1.php");

    //nclude("Fonctions.php");

    $valeur_envoyee=$_GET['valeur_envoyee'];
    $Matricule_etudiant=$_GET['Matricule'];
    $Zone=$_GET['Zone'];
    $code_promo=$_GET['code_promo'];    
    $id_annee_acad=$_GET['id_annee_acad'];

    //echo "id anne suivante ".$id_annee_acad;
    $sql_update="";
    $pass_promo=0;
    $annee=0;
    //echo "la valeur de la zon est ".$Zone;
    if($Zone=="zone_1")
    {
         $sql_update="UPDATE passer_par 
         SET Session1=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee";

    }
    else if($Zone=="zone_2")
    {
         $sql_update="UPDATE passer_par 
         SET Mention1=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee";

          if ($valeur_envoyee=="S" || $valeur_envoyee=="D" || $valeur_envoyee=="GD" || $valeur_envoyee=="TGD")
               {
                    if( Passage_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant,"passer"))
                    {
                         echo " Tous s'est passé avec succès ";
                    }
                    else echo " Impossible de faire passer cet étudiant ";
               }
         
          else if ($valeur_envoyee=="-")
          {
               echo "je suis dans null";
               if( Enlever_etudiant_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant))
               {
                    echo " Tous s'est passé avec succès ";
               }
               else echo " Impossible de supprimer cet étudiant ";
          }

    }
    else if($Zone=="zone_3")
    {
         $sql_update="UPDATE passer_par 
         SET Session2=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee";

    }else if($Zone=="zone_4")
    {
         $sql_update="UPDATE passer_par 
         SET Mention2=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee";

          if ($valeur_envoyee=="S" || $valeur_envoyee=="D" || $valeur_envoyee=="GD" || $valeur_envoyee=="TGD")
               {
                    if( Passage_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant,"passer"))
                    {
                         echo " Tous s'est passé avec succès ";
                    }
                    else echo " Impossible de faire passer cet étudiant ";
               }
          else if ($valeur_envoyee=="A" )
          {
               echo " je suis dans recaler";
               if( Passage_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant,"Recaler"))
               {
                    echo " Tous s'est passé avec succès ";
               }
               else echo " Impossible de recaler cet étudiant ";
          }
          else if ($valeur_envoyee=="-")
          {
               echo "je suis dans null";
               if( Enlever_etudiant_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant))
               {
                    echo " Tous s'est passé avec succès ";
               }
               else echo " Impossible de supprimer cet étudiant ";
          }
         
           
    }
    else if($Zone=="zone_5")
    {
         $sql_update="UPDATE passer_par 
         SET Decision_jury=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee";

       
          if ($valeur_envoyee=="Admis" || $valeur_envoyee=="Admis sous condition"  )
          {
               if( Passage_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant,"passer"))
               {
                    echo " Tous s'est passé avec succès ";
               }
               else echo " Impossible de faire passer cet étudiant ";
          }
          else if ($valeur_envoyee=="Recaler")
          {
               echo " je suis dans recaler";
               if( Passage_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant,"Recaler"))
               {
                    echo " Tous s'est passé avec succès ";
               }
               else echo " Impossible de supprimer cet étudiant ";
          }
          else if ($valeur_envoyee=="null")
          {
               echo " je suis dans null";
               if( Enlever_etudiant_Promotion($id_annee_acad,$code_promo, $Matricule_etudiant))
               {
                    echo " Tous s'est passé avec succès ";
               }
               else echo " Impossible de supprimer cet étudiant ";
          }

         
    }
    else if($Zone=="zone_11")
    {
         $sql_update="UPDATE passer_par 
         SET Mention1=:val
         WHERE Etudiant_Matricule=:mat_etudiant
         AND Code_Promotion=:code_promo
         AND idAnnee_academique=:id_annee";  
    }



    $stmt = $con->prepare($sql_update);

    $stmt->bindParam(':val', $valeur_envoyee);
    $stmt->bindParam(':mat_etudiant', $Matricule_etudiant);
    $stmt->bindParam(':code_promo', $code_promo);
    $stmt->bindParam(':id_annee', $id_annee_acad);
    
   // echo "la veulleur de sql :".$sql_update;


    $stmt->execute();







echo "je suis dans la fonction ici";
    function Passage_Promotion($id_annee_actuelle,$code_promo_actuelle,$mat_etudiant, $operation)
    {

include("../../Connexion_BDD/Connexion_1.php");

        $id_annee_suivante="";
        $nb_id_annee=0;
        $code_promo_montante="";
        $promo_montante="";
        
        echo "\n\n\nid_anne_actuelle ".$id_annee_actuelle
        ." prom_actuelle ".$code_promo_actuelle

        ."\nid_anne_suivante ".$id_annee_suivante
        ." promo suivante ".$code_promo_montante."\n\n\n";
            
        
        try{
            //recuperation de l'année academique suivante
            $sql_annee="SELECT Annee_debut, Annee_fin 
            FROM annee_academique 
            where idAnnee_Acad='$id_annee_actuelle'" ;

            $reponse = $con->query ($sql_annee);

            while ($ligne = $reponse->fetch())
            {
                $anne_debut=$ligne['Annee_fin'];
                $anne_fin=$ligne['Annee_fin']+1;
            }
            echo"\n\n</br></br> voici annee debut $anne_debut - $anne_fin</br></br>\n\n";


            // verification de l'année suivante si cette année n'existe pas on l'ajoute
            $nd_id_anne=0;
            $sql_annee_verification=
            "SELECT idAnnee_Acad, idAnnee_Acad as id_annee
            FROM annee_academique 
            where Annee_debut = '$anne_debut'";

            $reponse1 = $con->query ($sql_annee_verification);
            while ($ligne = $reponse1->fetch())
            {
                $nb_id_annee++;
                $id_annee_suivante=$ligne['id_annee'];
            } 

            echo"\n\n</br></br> voicie le nb $nb_id_annee </br></br>\n\n";


            if ($nb_id_annee==0)
            {
                $req_ajout_annee=
                "INSERT INTO `annee_academique`(`Annee_debut`, `Annee_fin`) 
                VALUES ('$anne_debut','$anne_fin')";
                
                $con->query($req_ajout_annee);
                
                $id_annee_suivante=$con->lastInsertId();
            }

            if ($operation=="passer") {
            // verification de la promotion et montage de promotion
            $sql_verifi_promo=
            "SELECT * 
            FROM promotion 
            where Code_Promotion = '$code_promo_actuelle'";

            $reponse2 = $con->query ($sql_verifi_promo);

            while ($ligne = $reponse2->fetch()) 
            {
                $Abréviation1=$ligne['Abréviation'];
                $idMention=$ligne['idMentions'];
                
                if ($Abréviation1=="L0 LMD") {$promo_montante="L1 LMD";}
                if ($Abréviation1=="L1 LMD") {$promo_montante="L2 LMD";}
                if ($Abréviation1=="L2 LMD") {$promo_montante="L3 LMD";}
                if ($Abréviation1=="L3 LMD") {$promo_montante="M1 LMD";}
                else if ($Abréviation1=="M1 LMD") {$promo_montante="M2 LMD";}
                else if ($Abréviation1=="M2 LMD") { $promo_montante=""; }
               
                if ($Abréviation1=="G3" and $idMention == "10" ) { $promo_montante="D1"; }
                else if ($Abréviation1=="G3") { $promo_montante="L1"; }
                if ($Abréviation1=="D1") { $promo_montante="D2"; }
                if ($Abréviation1=="D2") { $promo_montante="D3"; }
                if ($Abréviation1=="D3") { $promo_montante="D4"; }
                if ($Abréviation1=="L1") { $promo_montante="L2"; }
            
                
            }

            $sql_verifi_promo_montante=
            "SELECT * 
            FROM promotion 
            where Abréviation = '$promo_montante' 
            and idMentions='$idMention'";
            
            $reponse3 = $con->query ($sql_verifi_promo_montante);
            while ($ligne = $reponse3->fetch()) $code_promo_montante=$ligne['Code_Promotion'];

             }else if ($operation=="Recaler") {
           
           /************************** lorsque l'operation est recaler **************/
           $code_promo_montante=$code_promo_actuelle;

            }

            /****************************************************************************
             * ************* ICI ON INSERE L'ETUDIANT DANS UNE PROMOTION MONTANTE ou meme classe dans le cas de recaler *******
             *****************************************************************************/

            echo "\n\n\nid_anne_actuelle ".$id_annee_actuelle." prom_actuelle ".$code_promo_actuelle."\nid_anne_suivante ".$id_annee_suivante." promosuivante ".$code_promo_montante."\n\n\n"." idmention ".$idMention;

            echo '<br>';
            
            $sql_insert_passer_par= 
            "INSERT INTO 
            passer_par(
                Etudiant_Matricule, 
                Code_Promotion, 
                idAnnee_academique, 
                Decision_jury, 
                Session1, 
                Mention1, 
                Session2, 
                Mention2, Active) 
                VALUES ( :mat_etudiant,'$code_promo_montante','$id_annee_suivante',null,'0','-','0','-','')"; 

            $stmt1=$con->prepare($sql_insert_passer_par);
            $stmt1->bindParam(':mat_etudiant',$mat_etudiant);
            $stmt1->execute();

            return true;
            

        } catch (\Throwable $th) {    return false; }
            
   
}



    /**********************************************************************************
     * ******** CETTE FONCTION NOUS PERMET D'ENELEVER UN ETUDIANT DANS UNE PROMOTION **
     * ********************************************************************************
     */


     function Enlever_etudiant_Promotion($id_annee_actuelle,$code_promo_actuelle,$mat_etudiant)
    {
       
include("../../Connexion_BDD/Connexion_1.php");

        $id_annee_suivante="";
        $nb_id_annee=0;
        $code_promo_montante="";
        $promo_montante="";


        echo "\n\n\nid_anne_actuelle ".$id_annee_actuelle
        ." prom_actuelle ".$code_promo_actuelle

        ."\nid_anne_suivante ".$id_annee_suivante
        ." promo suivante ".$code_promo_montante."\n\n\n";

        echo "nous sommes dans la fonction recaler";
            
        
        /*try
        {*/
        

            //$Matricule_etudiant=$_POST['Matricule_const'];
            /*$code_promo__actuelle;
            $id_annee_actuelle;//=$_GET['Id_annee_acad'];
            $mat_etudiant=$_GET['mat_etudiant'];*/

            //recuperation de l'année academique suivante
            $sql_annee="SELECT Annee_debut, Annee_fin 
            FROM annee_academique 
            where idAnnee_Acad='$id_annee_actuelle'";

            $reponse = $con->query ($sql_annee);

            while ($ligne = $reponse->fetch())
            {
                $anne_debut=$ligne['Annee_fin'];
                $anne_fin=$ligne['Annee_fin']+1;
            }
            echo"\n\n</br></br> voici annee debut $anne_debut </br></br>\n\n";


            // verification de l'année suivante si cette année n'existe pas on l'ajoute
            
            $sql_annee_verification=
            "SELECT count(idAnnee_Acad) as nd_id_anne, idAnnee_Acad as id_annee
            FROM annee_academique 
            where Annee_debut = '$anne_debut'";

            $reponse1 = $con->query ($sql_annee_verification);
            while ($ligne = $reponse1->fetch())
            {
                $nb_id_annee=$ligne['nd_id_anne'];
                $id_annee_suivante=$ligne['id_annee'];
            } 

            echo"\n\n</br></br> voicie le nb $nb_id_annee </br></br>\n\n";


            if ($nb_id_annee==0)
            {
                $req_ajout_annee=
                "INSERT INTO `annee_academique`(`Annee_debut`, `Annee_fin`) 
                VALUES ('$anne_debut','$anne_fin')";
                
                $con->query($req_ajout_annee);
                
                $id_annee_suivante=$con->lastInsertId();
            }






            
            // verification de la promotion et montage de promotion

            
            /*$rqt2="SELECT filiere.IdFiliere, mentions.idMentions, promotion.Code_Promotion,promotion.Abréviation FROM filiere, mentions, promotion JOIN mentions on     promotion.idMentions=mentions.idMentions JOIN filiere ON mentions.IdFiliere=filiere.IdFiliere WHERE promotion.Code_Promotion='$code_promo__actuelle'";*/
            $sql_verifi_promo=
            "SELECT * 
            FROM promotion 
            where Code_Promotion = '$code_promo_actuelle'";

            $reponse2 = $con->query ($sql_verifi_promo);

            while ($ligne = $reponse2->fetch()) 
            {
                $Abréviation1=$ligne['Abréviation'];
                $idMention=$ligne['idMentions'];
                
                if ($Abréviation1=="L0 LMD") {$promo_montante="L1 LMD";}
                else if ($Abréviation1=="L1 LMD") {$promo_montante="L2 LMD";}
                else if ($Abréviation1=="L2 LMD") {$promo_montante="L3 LMD";}
                else if ($Abréviation1=="L3 LMD") {$promo_montante="M1 LMD";}
                else if ($Abréviation1=="M1 LMD") {$promo_montante="M2 LMD";}
                else if ($Abréviation1=="M2 LMD") { $promo_montante=""; }
                
                if ($Abréviation1=="G3" and $idMention != 1) { $promo_montante="L1"; }
                if ($Abréviation1=="G3" and $idMention == 1 ) { $promo_montante="D1"; }
                if ($Abréviation1=="D1") { $promo_montante="D2"; }
                if ($Abréviation1=="D2") { $promo_montante="D3"; }
                if ($Abréviation1=="D3") { $promo_montante="D4"; }
                if ($Abréviation1=="L1") { $promo_montante="L2"; }
              
               
            }

            $sql_verifi_promo_montante=
            "SELECT * 
            FROM promotion 
            where Abréviation = '$promo_montante' 
            and idMentions='$idMention'";
            
            $reponse3 = $con->query ($sql_verifi_promo_montante);
            while ($ligne = $reponse3->fetch()) $code_promo_montante=$ligne['Code_Promotion'];

            /****************************************************************************
             * ************* ICI ON INSERE L'ETUDIANT DANS UNE PROMOTION MONTANTE *******
             *****************************************************************************/

            echo "\n\n\nid_anne_actuelle ".$id_annee_actuelle." prom_actuelle ".$code_promo_actuelle."\nid_anne_suivante ".$id_annee_suivante." promosuivante ".$code_promo_montante."\n\n\n";
            
            $sql_insert_passer_par= 
            "DELETE FROM passer_par 
            WHERE 
            Etudiant_Matricule=:mat_etudiant 
            and Code_Promotion=:cd_prom_suivante
            and idAnnee_academique=:id_suivante";

            $stmt1=$con->prepare($sql_insert_passer_par);
            $stmt1->bindParam(':mat_etudiant',$mat_etudiant);            
            $stmt1->bindParam(':cd_prom_suivante',$code_promo_montante);
            $stmt1->bindParam(':id_suivante',$id_annee_suivante);
            $stmt1->execute();

            return true;
            /*

        } catch (\Throwable $th) {
            return false;
        }*/
        
    }
?>

