# Proposition d'Implémentation : Système de Délibération avec Compensation

## 📋 Analyse de la Structure Actuelle

### Tables Concernées

#### 1. **Table `evaluer`** (Structure Existante)
```sql
CREATE TABLE `evaluer` (
  `Matricule` varchar(20) NOT NULL,
  `id_ec_aligne` int NOT NULL,
  `Cote` float DEFAULT NULL,
  `Cote_rattrapage` float DEFAULT NULL,
  `cote_compensee` float DEFAULT NULL,  -- Cote entre 8 et 9
  `cote_cedee` float DEFAULT NULL,      -- Cote restante après compensation
  `Ligne_touchee_Matricule_id_ec_aligne` varchar(50) DEFAULT NULL, -- Référence ligne compensée
  PRIMARY KEY (`Matricule`,`id_ec_aligne`)
)
```

#### 2. **Tables Complémentaires**
- `etudiant` : Informations étudiants
- `element_constitutifs_aligne` : EC alignés par semestre/année/promotion
- `element_constitutifs` : Détails des EC (crédits, heures, etc.)
- `unite_enseignement` : UE avec catégorie (Fondamentale, Complémentaire, etc.)
- `promotion` : Promotions (L1, L2, L3, M1, M2)
- `semestre` : Semestres (S1-S10)
- `t_jury_deliberation` : Configuration des jurys

---

## 🎯 Tables à Créer pour la Délibération

### 1. **Table `decision_semestre`** - Décisions Semestrielles
```sql
CREATE TABLE `decision_semestre` (
  `id_decision_sem` INT NOT NULL AUTO_INCREMENT,
  `Matricule` VARCHAR(20) NOT NULL,
  `idAnnee_Acad` INT NOT NULL,
  `Id_Semestre` INT NOT NULL,
  `Code_Promotion` VARCHAR(10) NOT NULL,
  `ID_jury` INT NOT NULL,
  
  -- Résultats
  `credits_valides` INT DEFAULT 0,
  `credits_totaux` INT DEFAULT 30,
  `moyenne_semestre` FLOAT DEFAULT NULL,
  
  -- Décision du jury (Art. 07.A)
  `decision` ENUM(
    'VALIDE_CAPITALISATION',  -- Semestre validé avec capitalisation définitive
    'VALIDE_COMPENSATION',     -- Semestre validé avec compensation
    'DEF',                     -- Manque de notes (absence justifiée/non)
    'NON_VALIDE'              -- Semestre non validé
  ) DEFAULT NULL,
  
  -- Détails compensation
  `nb_ue_compensees` INT DEFAULT 0,
  `nb_ec_compenses` INT DEFAULT 0,
  
  -- Traçabilité
  `date_decision` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `observations` TEXT DEFAULT NULL,
  
  PRIMARY KEY (`id_decision_sem`),
  UNIQUE KEY `unique_decision_sem` (`Matricule`, `idAnnee_Acad`, `Id_Semestre`),
  
  FOREIGN KEY (`Matricule`) REFERENCES `etudiant`(`Matricule`),
  FOREIGN KEY (`idAnnee_Acad`) REFERENCES `annee_academique`(`idAnnee_Acad`),
  FOREIGN KEY (`Id_Semestre`) REFERENCES `semestre`(`Id_Semestre`),
  FOREIGN KEY (`Code_Promotion`) REFERENCES `promotion`(`Code_Promotion`),
  FOREIGN KEY (`ID_jury`) REFERENCES `t_jury_deliberation`(`ID_jury`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. **Table `decision_annuelle`** - Décisions Annuelles
```sql
CREATE TABLE `decision_annuelle` (
  `id_decision_annuelle` INT NOT NULL AUTO_INCREMENT,
  `Matricule` VARCHAR(20) NOT NULL,
  `idAnnee_Acad` INT NOT NULL,
  `Code_Promotion` VARCHAR(10) NOT NULL,
  `ID_jury` INT NOT NULL,
  
  -- Résultats annuels
  `credits_valides_s1` INT DEFAULT 0,
  `credits_valides_s2` INT DEFAULT 0,
  `credits_valides_total` INT DEFAULT 0,
  `credits_requis` INT DEFAULT 60,
  `moyenne_annuelle` FLOAT DEFAULT NULL,
  
  -- Décision finale (Art. 07.B)
  `decision` ENUM(
    'ADM',      -- Admis avec capitalisation définitive
    'COMP',     -- Admis avec compensation
    'DEF',      -- Manque de notes (absence)
    'AJ',       -- Ajourné (non admis)
    'ABS'       -- N'a présenté aucun examen
  ) DEFAULT NULL,
  
  -- Passage automatique ou conditionnel (Art. 08)
  `type_passage` ENUM(
    'AUTOMATIQUE',     -- 60 crédits validés
    'CONDITIONNEL',    -- 45+ crédits (L1→L2) ou 45+ (M1→M2)
    'BLOQUE',          -- Moins de 45 crédits
    'REDOUBLE'         -- Redoublement
  ) DEFAULT NULL,
  
  `credits_dette` INT DEFAULT 0,  -- Crédits manquants à régulariser
  
  -- Historique cursus
  `annees_cursus_total` INT DEFAULT 1,
  `credits_cumules` INT DEFAULT 0,  -- Total depuis L1 ou M1
  
  -- Observations
  `peut_progresser` BOOLEAN DEFAULT FALSE,
  `doit_regulariser` BOOLEAN DEFAULT FALSE,
  `ue_fondamentales_validees` BOOLEAN DEFAULT FALSE,
  `date_decision` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `observations` TEXT DEFAULT NULL,
  
  PRIMARY KEY (`id_decision_annuelle`),
  UNIQUE KEY `unique_decision_annuelle` (`Matricule`, `idAnnee_Acad`),
  
  FOREIGN KEY (`Matricule`) REFERENCES `etudiant`(`Matricule`),
  FOREIGN KEY (`idAnnee_Acad`) REFERENCES `annee_academique`(`idAnnee_Acad`),
  FOREIGN KEY (`Code_Promotion`) REFERENCES `promotion`(`Code_Promotion`),
  FOREIGN KEY (`ID_jury`) REFERENCES `t_jury_deliberation`(`ID_jury`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. **Table `historique_compensation`** - Traçabilité des Compensations
```sql
CREATE TABLE `historique_compensation` (
  `id_compensation` INT NOT NULL AUTO_INCREMENT,
  `Matricule` VARCHAR(20) NOT NULL,
  `idAnnee_Acad` INT NOT NULL,
  `Id_Semestre` INT NOT NULL,
  
  -- EC source (qui cède des points)
  `id_ec_cedant` INT NOT NULL,
  `cote_originale_cedant` FLOAT NOT NULL,
  `cote_apres_cession` FLOAT NOT NULL,
  `points_cedes` FLOAT NOT NULL,
  
  -- EC bénéficiaire (qui reçoit des points)
  `id_ec_beneficiaire` INT NOT NULL,
  `cote_originale_beneficiaire` FLOAT NOT NULL,
  `cote_apres_compensation` FLOAT NOT NULL,
  `points_recus` FLOAT NOT NULL,
  
  -- Contexte
  `type_compensation` ENUM('UE', 'SEMESTRE', 'ANNEE') NOT NULL,
  `ID_jury` INT NOT NULL,
  `date_compensation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `valide_par` VARCHAR(20) DEFAULT NULL,  -- Mat_agent du jury
  
  PRIMARY KEY (`id_compensation`),
  
  FOREIGN KEY (`Matricule`) REFERENCES `etudiant`(`Matricule`),
  FOREIGN KEY (`idAnnee_Acad`) REFERENCES `annee_academique`(`idAnnee_Acad`),
  FOREIGN KEY (`Id_Semestre`) REFERENCES `semestre`(`Id_Semestre`),
  FOREIGN KEY (`id_ec_cedant`) REFERENCES `element_constitutifs_aligne`(`id_ec_aligne`),
  FOREIGN KEY (`id_ec_beneficiaire`) REFERENCES `element_constitutifs_aligne`(`id_ec_aligne`),
  FOREIGN KEY (`ID_jury`) REFERENCES `t_jury_deliberation`(`ID_jury`),
  FOREIGN KEY (`valide_par`) REFERENCES `agent`(`Mat_agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 4. **Table `parcours_etudiant`** - Suivi du Cursus
```sql
CREATE TABLE `parcours_etudiant` (
  `id_parcours` INT NOT NULL AUTO_INCREMENT,
  `Matricule` VARCHAR(20) NOT NULL,
  `Code_Promotion_initial` VARCHAR(10) NOT NULL,  -- Promotion d'entrée
  `Code_Promotion_actuel` VARCHAR(10) NOT NULL,   -- Promotion actuelle
  `idAnnee_Acad` INT NOT NULL,
  
  -- Progression dans le cycle
  `niveau_actuel` ENUM('L1', 'L2', 'L3', 'M1', 'M2') NOT NULL,
  `annees_dans_niveau` INT DEFAULT 1,
  `annees_total_cycle` INT DEFAULT 1,
  
  -- Limites temporelles (Art. 08)
  `max_annees_cycle` INT DEFAULT 5,  -- 5 pour Licence, 3 pour Maîtrise
  `peut_continuer` BOOLEAN DEFAULT TRUE,
  
  -- Cumul crédits
  `credits_L1` INT DEFAULT 0,
  `credits_L2` INT DEFAULT 0,
  `credits_L3` INT DEFAULT 0,
  `credits_M1` INT DEFAULT 0,
  `credits_M2` INT DEFAULT 0,
  `credits_total_licence` INT DEFAULT 0,
  `credits_total_maitrise` INT DEFAULT 0,
  
  -- Historique redoublements
  `nb_redoublements` INT DEFAULT 0,
  `a_redouble_L1` BOOLEAN DEFAULT FALSE,
  `a_redouble_L2` BOOLEAN DEFAULT FALSE,
  `annee_derniere_reprise` INT DEFAULT NULL,
  
  -- État
  `statut` ENUM('EN_COURS', 'DIPLOME', 'EXCLU', 'ABANDONNE') DEFAULT 'EN_COURS',
  `date_inscription_initiale` DATE DEFAULT NULL,
  `date_derniere_maj` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id_parcours`),
  UNIQUE KEY `unique_parcours` (`Matricule`, `idAnnee_Acad`),
  
  FOREIGN KEY (`Matricule`) REFERENCES `etudiant`(`Matricule`),
  FOREIGN KEY (`Code_Promotion_initial`) REFERENCES `promotion`(`Code_Promotion`),
  FOREIGN KEY (`Code_Promotion_actuel`) REFERENCES `promotion`(`Code_Promotion`),
  FOREIGN KEY (`idAnnee_Acad`) REFERENCES `annee_academique`(`idAnnee_Acad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## ⚙️ Procédures Stockées Principales

### 1. **Procédure de Compensation au Niveau UE**
```sql
DELIMITER $$

CREATE PROCEDURE `Compensation_UE`(
  IN p_matricule VARCHAR(20),
  IN p_id_semestre INT,
  IN p_annee_acad INT,
  IN p_id_jury INT,
  OUT p_success BOOLEAN,
  OUT p_message TEXT
)
BEGIN
  DECLARE v_code_ue VARCHAR(10);
  DECLARE v_total_credits INT;
  DECLARE v_credits_valides INT;
  DECLARE v_moyenne_ue FLOAT;
  DECLARE v_peut_compenser BOOLEAN DEFAULT FALSE;
  DECLARE done INT DEFAULT FALSE;
  
  DECLARE cur_ue CURSOR FOR 
    SELECT DISTINCT ue.Code_ue, SUM(ec.Credit) as total_credits
    FROM evaluer ev
    JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
    JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
    JOIN unite_enseignement ue ON ec.Code_ue = ue.Code_ue
    WHERE ev.Matricule = p_matricule
      AND eca.Id_Semestre = p_id_semestre
      AND eca.idAnnee_Acad = p_annee_acad
    GROUP BY ue.Code_ue;
  
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  
  SET p_success = FALSE;
  SET p_message = '';
  
  OPEN cur_ue;
  
  read_loop: LOOP
    FETCH cur_ue INTO v_code_ue, v_total_credits;
    IF done THEN
      LEAVE read_loop;
    END IF;
    
    -- Calculer la moyenne de l'UE
    SELECT 
      AVG(COALESCE(ev.Cote_rattrapage, ev.Cote)) as moy_ue,
      SUM(CASE WHEN COALESCE(ev.Cote_rattrapage, ev.Cote) >= 10 THEN ec.Credit ELSE 0 END) as credits_ok
    INTO v_moyenne_ue, v_credits_valides
    FROM evaluer ev
    JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
    JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
    WHERE ev.Matricule = p_matricule
      AND eca.Id_Semestre = p_id_semestre
      AND eca.idAnnee_Acad = p_annee_acad
      AND ec.Code_ue = v_code_ue;
    
    -- Règle de compensation : moyenne UE >= 10 ET chaque EC >= 8
    IF v_moyenne_ue >= 10 THEN
      -- Appliquer la compensation pour les EC entre 8 et 9.99
      CALL Compenser_EC_Dans_UE(p_matricule, v_code_ue, p_id_semestre, p_annee_acad, p_id_jury);
      SET v_peut_compenser = TRUE;
    END IF;
    
  END LOOP;
  
  CLOSE cur_ue;
  
  IF v_peut_compenser THEN
    SET p_success = TRUE;
    SET p_message = 'Compensation appliquée avec succès.';
  ELSE
    SET p_message = 'Aucune compensation possible.';
  END IF;
  
END$$

DELIMITER ;
```

### 2. **Procédure de Compensation au Niveau EC**
```sql
DELIMITER $$

CREATE PROCEDURE `Compenser_EC_Dans_UE`(
  IN p_matricule VARCHAR(20),
  IN p_code_ue VARCHAR(10),
  IN p_id_semestre INT,
  IN p_annee_acad INT,
  IN p_id_jury INT
)
BEGIN
  DECLARE v_id_ec_faible INT;
  DECLARE v_id_ec_fort INT;
  DECLARE v_cote_faible FLOAT;
  DECLARE v_cote_forte FLOAT;
  DECLARE v_deficit FLOAT;
  DECLARE v_surplus FLOAT;
  DECLARE v_points_a_transferer FLOAT;
  DECLARE done INT DEFAULT FALSE;
  
  -- Curseur des EC faibles (entre 8 et 9.99)
  DECLARE cur_ec_faibles CURSOR FOR
    SELECT eca.id_ec_aligne, COALESCE(ev.Cote_rattrapage, ev.Cote) as cote
    FROM evaluer ev
    JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
    JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
    WHERE ev.Matricule = p_matricule
      AND ec.Code_ue = p_code_ue
      AND eca.Id_Semestre = p_id_semestre
      AND eca.idAnnee_Acad = p_annee_acad
      AND COALESCE(ev.Cote_rattrapage, ev.Cote) >= 8
      AND COALESCE(ev.Cote_rattrapage, ev.Cote) < 10
      AND ev.cote_compensee IS NULL  -- Pas encore compensé
    ORDER BY COALESCE(ev.Cote_rattrapage, ev.Cote) ASC;
  
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = FALSE;
  
  OPEN cur_ec_faibles;
  
  compensation_loop: LOOP
    FETCH cur_ec_faibles INTO v_id_ec_faible, v_cote_faible;
    IF done THEN
      LEAVE compensation_loop;
    END IF;
    
    SET v_deficit = 10 - v_cote_faible;
    
    -- Chercher un EC avec surplus (> 10)
    SELECT eca.id_ec_aligne, COALESCE(ev.Cote_rattrapage, ev.Cote) as cote
    INTO v_id_ec_fort, v_cote_forte
    FROM evaluer ev
    JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
    JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
    WHERE ev.Matricule = p_matricule
      AND ec.Code_ue = p_code_ue
      AND eca.Id_Semestre = p_id_semestre
      AND eca.idAnnee_Acad = p_annee_acad
      AND COALESCE(ev.Cote_rattrapage, ev.Cote) > 10
      AND (ev.cote_cedee IS NULL OR ev.cote_cedee > 10)  -- Peut encore céder
    ORDER BY COALESCE(ev.Cote_rattrapage, ev.Cote) DESC
    LIMIT 1;
    
    IF v_id_ec_fort IS NOT NULL THEN
      SET v_surplus = v_cote_forte - 10;
      SET v_points_a_transferer = LEAST(v_deficit, v_surplus);
      
      -- Mettre à jour l'EC bénéficiaire
      UPDATE evaluer
      SET cote_compensee = v_cote_faible + v_points_a_transferer,
          Ligne_touchee_Matricule_id_ec_aligne = CONCAT(p_matricule, '_', v_id_ec_fort)
      WHERE Matricule = p_matricule AND id_ec_aligne = v_id_ec_faible;
      
      -- Mettre à jour l'EC cédant
      UPDATE evaluer
      SET cote_cedee = v_cote_forte - v_points_a_transferer
      WHERE Matricule = p_matricule AND id_ec_aligne = v_id_ec_fort;
      
      -- Enregistrer dans l'historique
      INSERT INTO historique_compensation (
        Matricule, idAnnee_Acad, Id_Semestre,
        id_ec_cedant, cote_originale_cedant, cote_apres_cession, points_cedes,
        id_ec_beneficiaire, cote_originale_beneficiaire, cote_apres_compensation, points_recus,
        type_compensation, ID_jury
      ) VALUES (
        p_matricule, p_annee_acad, p_id_semestre,
        v_id_ec_fort, v_cote_forte, v_cote_forte - v_points_a_transferer, v_points_a_transferer,
        v_id_ec_faible, v_cote_faible, v_cote_faible + v_points_a_transferer, v_points_a_transferer,
        'UE', p_id_jury
      );
    END IF;
    
  END LOOP;
  
  CLOSE cur_ec_faibles;
  
END$$

DELIMITER ;
```

### 3. **Procédure de Décision Semestrielle**
```sql
DELIMITER $$

CREATE PROCEDURE `Decision_Semestre`(
  IN p_matricule VARCHAR(20),
  IN p_id_semestre INT,
  IN p_annee_acad INT,
  IN p_code_promotion VARCHAR(10),
  IN p_id_jury INT,
  OUT p_decision VARCHAR(50),
  OUT p_credits_valides INT
)
BEGIN
  DECLARE v_total_credits INT DEFAULT 30;
  DECLARE v_credits_obtenus INT DEFAULT 0;
  DECLARE v_nb_absences INT DEFAULT 0;
  DECLARE v_nb_ue_compensees INT DEFAULT 0;
  DECLARE v_nb_ec_compenses INT DEFAULT 0;
  
  -- 1. Appliquer les compensations
  CALL Compensation_UE(p_matricule, p_id_semestre, p_annee_acad, p_id_jury, @success, @msg);
  
  -- 2. Calculer les crédits validés
  SELECT 
    SUM(ec.Credit) as credits_ok,
    SUM(CASE WHEN ev.Cote IS NULL THEN 1 ELSE 0 END) as nb_abs
  INTO v_credits_obtenus, v_nb_absences
  FROM evaluer ev
  JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
  JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
  WHERE ev.Matricule = p_matricule
    AND eca.Id_Semestre = p_id_semestre
    AND eca.idAnnee_Acad = p_annee_acad
    AND (
      COALESCE(ev.cote_compensee, ev.Cote_rattrapage, ev.Cote) >= 10
    );
  
  -- 3. Compter les compensations
  SELECT COUNT(DISTINCT ec.Code_ue)
  INTO v_nb_ue_compensees
  FROM evaluer ev
  JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
  JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
  WHERE ev.Matricule = p_matricule
    AND eca.Id_Semestre = p_id_semestre
    AND eca.idAnnee_Acad = p_annee_acad
    AND ev.cote_compensee IS NOT NULL;
  
  -- 4. Déterminer la décision (Art. 07.A)
  IF v_nb_absences > 0 THEN
    SET p_decision = 'DEF';
  ELSEIF v_credits_obtenus = v_total_credits AND v_nb_ue_compensees = 0 THEN
    SET p_decision = 'VALIDE_CAPITALISATION';
  ELSEIF v_credits_obtenus = v_total_credits AND v_nb_ue_compensees > 0 THEN
    SET p_decision = 'VALIDE_COMPENSATION';
  ELSE
    SET p_decision = 'NON_VALIDE';
  END IF;
  
  SET p_credits_valides = v_credits_obtenus;
  
  -- 5. Enregistrer la décision
  INSERT INTO decision_semestre (
    Matricule, idAnnee_Acad, Id_Semestre, Code_Promotion, ID_jury,
    credits_valides, credits_totaux, decision,
    nb_ue_compensees, nb_ec_compenses
  ) VALUES (
    p_matricule, p_annee_acad, p_id_semestre, p_code_promotion, p_id_jury,
    v_credits_obtenus, v_total_credits, p_decision,
    v_nb_ue_compensees, v_nb_ec_compenses
  )
  ON DUPLICATE KEY UPDATE
    credits_valides = v_credits_obtenus,
    decision = p_decision,
    nb_ue_compensees = v_nb_ue_compensees,
    date_decision = CURRENT_TIMESTAMP;
  
END$$

DELIMITER ;
```

### 4. **Procédure de Décision Annuelle**
```sql
DELIMITER $$

CREATE PROCEDURE `Decision_Annuelle`(
  IN p_matricule VARCHAR(20),
  IN p_annee_acad INT,
  IN p_code_promotion VARCHAR(10),
  IN p_id_jury INT,
  OUT p_decision VARCHAR(20),
  OUT p_type_passage VARCHAR(20)
)
BEGIN
  DECLARE v_credits_s1 INT DEFAULT 0;
  DECLARE v_credits_s2 INT DEFAULT 0;
  DECLARE v_credits_total INT DEFAULT 0;
  DECLARE v_decision_s1 VARCHAR(50);
  DECLARE v_decision_s2 VARCHAR(50);
  DECLARE v_niveau VARCHAR(5);
  DECLARE v_credits_cumules INT DEFAULT 0;
  DECLARE v_ue_fond_ok BOOLEAN DEFAULT FALSE;
  
  -- Récupérer le niveau actuel
  SELECT LEFT(Code_Promotion, 2) INTO v_niveau
  FROM promotion WHERE Code_Promotion = p_code_promotion;
  
  -- Récupérer les décisions semestrielles
  SELECT 
    MAX(CASE WHEN ds.Id_Semestre IN (1,3,5,7,9) THEN ds.credits_valides ELSE 0 END),
    MAX(CASE WHEN ds.Id_Semestre IN (2,4,6,8,10) THEN ds.credits_valides ELSE 0 END),
    MAX(CASE WHEN ds.Id_Semestre IN (1,3,5,7,9) THEN ds.decision ELSE NULL END),
    MAX(CASE WHEN ds.Id_Semestre IN (2,4,6,8,10) THEN ds.decision ELSE NULL END)
  INTO v_credits_s1, v_credits_s2, v_decision_s1, v_decision_s2
  FROM decision_semestre ds
  WHERE ds.Matricule = p_matricule
    AND ds.idAnnee_Acad = p_annee_acad;
  
  SET v_credits_total = v_credits_s1 + v_credits_s2;
  
  -- Calculer crédits cumulés depuis le début
  SELECT COALESCE(SUM(credits_valides_total), 0)
  INTO v_credits_cumules
  FROM decision_annuelle
  WHERE Matricule = p_matricule
    AND idAnnee_Acad < p_annee_acad;
  
  SET v_credits_cumules = v_credits_cumules + v_credits_total;
  
  -- Vérifier les UE fondamentales
  SELECT COUNT(*) = 0 INTO v_ue_fond_ok
  FROM evaluer ev
  JOIN element_constitutifs_aligne eca ON ev.id_ec_aligne = eca.id_ec_aligne
  JOIN element_constitutifs ec ON eca.id_ec = ec.id_ec
  JOIN unite_enseignement ue ON ec.Code_ue = ue.Code_ue
  WHERE ev.Matricule = p_matricule
    AND eca.idAnnee_Acad = p_annee_acad
    AND ue.Catégorie = 'Fondamentale'
    AND COALESCE(ev.cote_compensee, ev.Cote_rattrapage, ev.Cote) < 10;
  
  -- Déterminer la décision annuelle (Art. 07.B)
  IF v_decision_s1 = 'DEF' OR v_decision_s2 = 'DEF' THEN
    SET p_decision = 'DEF';
  ELSEIF v_credits_total = 0 THEN
    SET p_decision = 'ABS';
  ELSEIF v_credits_total = 60 THEN
    IF v_decision_s1 = 'VALIDE_CAPITALISATION' AND v_decision_s2 = 'VALIDE_CAPITALISATION' THEN
      SET p_decision = 'ADM';
    ELSE
      SET p_decision = 'COMP';
    END IF;
  ELSE
    SET p_decision = 'AJ';
  END IF;
  
  -- Déterminer le type de passage (Art. 08)
  IF v_credits_total = 60 THEN
    SET p_type_passage = 'AUTOMATIQUE';
  ELSEIF v_niveau = 'L1' AND v_credits_total >= 45 THEN
    SET p_type_passage = 'CONDITIONNEL';
  ELSEIF v_niveau = 'L2' AND v_credits_cumules >= 90 AND v_ue_fond_ok THEN
    SET p_type_passage = 'CONDITIONNEL';
  ELSEIF v_niveau IN ('M1') AND v_credits_total >= 45 THEN
    SET p_type_passage = 'CONDITIONNEL';
  ELSE
    SET p_type_passage = 'REDOUBLE';
  END IF;
  
  -- Enregistrer la décision annuelle
  INSERT INTO decision_annuelle (
    Matricule, idAnnee_Acad, Code_Promotion, ID_jury,
    credits_valides_s1, credits_valides_s2, credits_valides_total,
    decision, type_passage, credits_dette, credits_cumules,
    ue_fondamentales_validees
  ) VALUES (
    p_matricule, p_annee_acad, p_code_promotion, p_id_jury,
    v_credits_s1, v_credits_s2, v_credits_total,
    p_decision, p_type_passage, 60 - v_credits_total, v_credits_cumules,
    v_ue_fond_ok
  )
  ON DUPLICATE KEY UPDATE
    credits_valides_s1 = v_credits_s1,
    credits_valides_s2 = v_credits_s2,
    credits_valides_total = v_credits_total,
    decision = p_decision,
    type_passage = p_type_passage,
    credits_cumules = v_credits_cumules,
    date_decision = CURRENT_TIMESTAMP;
  
END$$

DELIMITER ;
```

---

## 📊 Flux de Traitement pour la Délibération

### Étape 1 : Préparation
```
1. Le jury est créé dans t_jury_deliberation
2. Les membres sont ajoutés dans t_membre_jury
3. Toutes les cotes sont saisies dans evaluer
```

### Étape 2 : Traitement Semestriel
```
Pour chaque étudiant :
  1. Appliquer la compensation au niveau UE
     → Procédure: Compensation_UE()
     
  2. Calculer les crédits validés
  
  3. Déterminer la décision semestrielle
     → Procédure: Decision_Semestre()
     → Résultat dans decision_semestre
```

### Étape 3 : Traitement Annuel
```
Après les 2 semestres :
  1. Récupérer les décisions semestrielles
  
  2. Calculer les crédits annuels
  
  3. Vérifier les UE fondamentales
  
  4. Déterminer la décision annuelle
     → Procédure: Decision_Annuelle()
     → Résultat dans decision_annuelle
  
  5. Déterminer le type de passage
     → AUTOMATIQUE (60 crédits)
     → CONDITIONNEL (45+ crédits)
     → REDOUBLE (< 45 crédits)
```

### Étape 4 : Mise à Jour du Parcours
```
  1. Mettre à jour parcours_etudiant
     - Crédits cumulés
     - Niveau actuel
     - Années dans le cycle
     - Contrôle des limites temporelles
```

---

## 🎨 Interfaces PHP Suggérées

### 1. **Page de Délibération Semestrielle**
```php
// Fichier: D_Academique/Deliberation_Semestre.php

<?php
// Afficher la liste des étudiants du semestre
// Permettre de :
//   - Saisir/Modifier les cotes
//   - Appliquer les compensations
//   - Valider les décisions
//   - Imprimer les PV

// Appel de la procédure
$stmt = $conn->prepare("CALL Decision_Semestre(?, ?, ?, ?, ?, @decision, @credits)");
$stmt->execute([$matricule, $id_semestre, $annee_acad, $code_promo, $id_jury]);

$result = $conn->query("SELECT @decision as decision, @credits as credits");
$row = $result->fetch_assoc();
?>
```

### 2. **Page de Délibération Annuelle**
```php
// Fichier: D_Academique/Deliberation_Annuelle.php

<?php
// Afficher le récapitulatif annuel
// Permettre de :
//   - Voir les décisions semestrielles
//   - Appliquer la décision annuelle
//   - Déterminer le passage
//   - Imprimer les bulletins

$stmt = $conn->prepare("CALL Decision_Annuelle(?, ?, ?, ?, @decision, @passage)");
$stmt->execute([$matricule, $annee_acad, $code_promo, $id_jury]);
?>
```

### 3. **Tableau de Bord des Compensations**
```php
// Fichier: D_Academique/Dashboard_Compensations.php

<?php
// Vue d'ensemble :
//   - Nombre de compensations par UE
//   - Nombre d'étudiants bénéficiaires
//   - Points cédés/reçus
//   - Statistiques par promotion

$sql = "SELECT * FROM historique_compensation 
        WHERE idAnnee_Acad = ? AND Id_Semestre = ?";
?>
```

---

## 📈 Requêtes Utiles

### Statistiques de Délibération
```sql
-- Répartition des décisions semestrielles
SELECT 
  decision,
  COUNT(*) as nb_etudiants,
  ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) as pourcentage
FROM decision_semestre
WHERE idAnnee_Acad = 30 AND Id_Semestre = 1
GROUP BY decision;

-- Étudiants avec passage conditionnel
SELECT 
  e.Matricule,
  e.Nom,
  e.Postnom,
  da.credits_valides_total,
  da.credits_dette,
  da.type_passage
FROM decision_annuelle da
JOIN etudiant e ON da.Matricule = e.Matricule
WHERE da.idAnnee_Acad = 30
  AND da.type_passage = 'CONDITIONNEL';

-- Historique des compensations d'un étudiant
SELECT 
  hc.Id_Semestre,
  ec_ced.Intutile_ec as EC_cedant,
  hc.cote_originale_cedant,
  hc.points_cedes,
  ec_ben.Intutile_ec as EC_beneficiaire,
  hc.cote_originale_beneficiaire,
  hc.cote_apres_compensation
FROM historique_compensation hc
JOIN element_constitutifs_aligne eca_ced ON hc.id_ec_cedant = eca_ced.id_ec_aligne
JOIN element_constitutifs ec_ced ON eca_ced.id_ec = ec_ced.id_ec
JOIN element_constitutifs_aligne eca_ben ON hc.id_ec_beneficiaire = eca_ben.id_ec_aligne
JOIN element_constitutifs ec_ben ON eca_ben.id_ec = ec_ben.id_ec
WHERE hc.Matricule = 'MAT001'
ORDER BY hc.date_compensation DESC;
```

---

## ✅ Plan d'Implémentation

### Phase 1 : Création des Tables
- [ ] Créer `decision_semestre`
- [ ] Créer `decision_annuelle`
- [ ] Créer `historique_compensation`
- [ ] Créer `parcours_etudiant`

### Phase 2 : Procédures Stockées
- [ ] Implémenter `Compenser_EC_Dans_UE`
- [ ] Implémenter `Compensation_UE`
- [ ] Implémenter `Decision_Semestre`
- [ ] Implémenter `Decision_Annuelle`

### Phase 3 : Interfaces PHP
- [ ] Créer page délibération semestrielle
- [ ] Créer page délibération annuelle
- [ ] Créer dashboard compensations
- [ ] Créer page suivi parcours

### Phase 4 : Tests
- [ ] Tester compensation simple (1 EC)
- [ ] Tester compensation complexe (multiple EC)
- [ ] Tester décisions semestrielles
- [ ] Tester décisions annuelles
- [ ] Tester règles de passage

---

## 🔐 Considérations Importantes

### Règles de Gestion
1. **Compensation UE** : Moyenne UE ≥ 10 ET chaque EC ≥ 8
2. **Validation définitive** : Note ≥ 10 sans compensation
3. **Passage automatique** : 60 crédits validés
4. **Passage conditionnel L1→L2** : ≥ 45 crédits (75%)
5. **Passage conditionnel L2→L3** : ≥ 90 crédits (75%) + UE fondamentales
6. **Redoublement** : CC effacés, à repasser
7. **Limites temporelles** :
   - Licence : max 5 ans (6 si 120 crédits validés)
   - Maîtrise : max 3 ans

### Sécurité & Traçabilité
- Toutes les compensations sont tracées dans `historique_compensation`
- Les décisions sont horodatées et liées au jury
- Les parcours étudiants sont suivis dans `parcours_etudiant`
- Audit trail complet pour chaque décision

---

**Prochaines Étapes** :
1. Valider cette structure avec vous
2. Créer les tables dans la base de données
3. Implémenter les procédures stockées
4. Développer les interfaces PHP

Voulez-vous que je commence par créer les scripts SQL pour les tables ?
