DELIMITER $$
CREATE DEFINER=`blaise`@`%` PROCEDURE `Liste_EC_Aligne`(IN `p_id_filiere` INT, IN `p_Mat_agent` VARCHAR(20), IN `p_idAnnee_Acad` INT, IN `p_Id_Semestre` INT, IN `p_Code_Promotion` VARCHAR(10))
BEGIN
    SELECT 
        ec.id_ec,
        ec.Intutile_ec,
        ec.Credit,
        ec.CMI,
        ec.Hr_TD,
        ec.Hr_TP,
        ec.TPE,
        ec.VHT,
        ue.Intitule_ue,
        f.Libelle_Filiere,
        CASE 
            WHEN eca.Mat_agent IS NOT NULL THEN TRUE
            ELSE FALSE
        END AS etat_ec,
        CASE 
            WHEN eca_global.id_ec IS NOT NULL THEN TRUE
            ELSE FALSE
        END AS etat_ec_pris,
        CASE 
            WHEN eca_annee.id_ec IS NOT NULL THEN TRUE
            ELSE FALSE
        END AS etat_ec_pris_dans_annee,
        CASE 
            WHEN eca.Mat_agent = p_Mat_agent THEN 1
            WHEN eca_global.id_ec IS NOT NULL THEN 2
            ELSE 3
        END AS ordre_tri
        
    FROM 
        element_constitutifs ec
    JOIN 
        unite_enseignement ue ON ec.Code_ue = ue.Code_ue
    JOIN 
        filiere f ON ue.IdFiliere = f.IdFiliere
    LEFT JOIN 
        element_constitutifs_aligne eca ON ec.id_ec = eca.id_ec 
        AND eca.Mat_agent = p_Mat_agent
        AND eca.idAnnee_Acad = p_idAnnee_Acad
        AND eca.Id_Semestre = p_Id_Semestre
        AND eca.Code_Promotion = p_Code_Promotion
    LEFT JOIN 
        element_constitutifs_aligne eca_global ON ec.id_ec = eca_global.id_ec 
        AND eca_global.idAnnee_Acad = p_idAnnee_Acad
        AND eca_global.Id_Semestre = p_Id_Semestre
    LEFT JOIN 
        element_constitutifs_aligne eca_annee ON ec.id_ec = eca_annee.id_ec 
        AND eca_annee.idAnnee_Acad = p_idAnnee_Acad
    WHERE 
        f.IdFiliere = p_id_filiere
    ORDER BY 
        ordre_tri, ec.id_ec;
END$$
DELIMITER ;