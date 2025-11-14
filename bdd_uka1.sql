-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 14 nov. 2025 à 14:55
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_uka`
--

-- --------------------------------------------------------

--
-- Structure de la table `affecter`
--

DROP TABLE IF EXISTS `affecter`;
CREATE TABLE IF NOT EXISTS `affecter` (
  `IdAffectation` int NOT NULL AUTO_INCREMENT,
  `Fonction` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `DateAffectation` date DEFAULT NULL,
  `Statut` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Matricule_Agent` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `IdService` int DEFAULT NULL,
  `Id_filiere` int DEFAULT NULL,
  PRIMARY KEY (`IdAffectation`),
  KEY `fk_service` (`IdService`),
  KEY `Id_filiere` (`Id_filiere`),
  KEY `Matricule_Agent` (`Matricule_Agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agent`
--

DROP TABLE IF EXISTS `agent`;
CREATE TABLE IF NOT EXISTS `agent` (
  `Mat_agent` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Nom_agent` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Post_agent` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Prenom` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Sexe` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Lieu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Grade` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `EtatCivil` varchar(20) NOT NULL,
  `IdCategorie` int NOT NULL DEFAULT '1',
  `AdressePhysique` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Mail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Tel` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Date_Engagement` date DEFAULT NULL,
  `Niveau_Etude` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Annee_Obt` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Institution` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Domaine` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type_agent` int DEFAULT NULL,
  `Institution_attachee` varchar(255) DEFAULT NULL,
  `Domaine_etude` varchar(255) DEFAULT NULL,
  `Id_filiere` int DEFAULT NULL,
  PRIMARY KEY (`Mat_agent`),
  KEY `fk_categorie` (`IdCategorie`),
  KEY `Id_filiere` (`Id_filiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `annee_academique`
--

DROP TABLE IF EXISTS `annee_academique`;
CREATE TABLE IF NOT EXISTS `annee_academique` (
  `idAnnee_Acad` int NOT NULL AUTO_INCREMENT,
  `Annee_debut` int DEFAULT NULL,
  `Annee_fin` int DEFAULT NULL,
  PRIMARY KEY (`idAnnee_Acad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `autorisation_depense`
--

DROP TABLE IF EXISTS `autorisation_depense`;
CREATE TABLE IF NOT EXISTS `autorisation_depense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Num_pce` varchar(50) NOT NULL,
  `Motif` varchar(1000) NOT NULL,
  `Beneficiaire` varchar(100) NOT NULL,
  `Montant` float NOT NULL,
  `Imputation` int NOT NULL,
  `Date_ajout` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Num_pce` (`Num_pce`),
  KEY `Imputation` (`Imputation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `autoriser_depense`
--

DROP TABLE IF EXISTS `autoriser_depense`;
CREATE TABLE IF NOT EXISTS `autoriser_depense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Num_pce` varchar(50) NOT NULL,
  `Agent_auriz1` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Date_autoriz1` datetime DEFAULT NULL,
  `Niveau_1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Agent_auriz2` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Date_autoriz2` datetime DEFAULT NULL,
  `Niveau_2` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Num_pce` (`Num_pce`),
  KEY `Agent_auriz1` (`Agent_auriz1`),
  KEY `Agent_auriz2` (`Agent_auriz2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `autreinfo_etudiant`
--

DROP TABLE IF EXISTS `autreinfo_etudiant`;
CREATE TABLE IF NOT EXISTS `autreinfo_etudiant` (
  `idAutreInfo_etudiant` int NOT NULL AUTO_INCREMENT,
  `Matricule` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Religion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Nationalite` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `EtatCiv` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `NomPere` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ProfPere` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `NomMere` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ProfMere` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `AdresseActuelle` varchar(45) DEFAULT NULL,
  `Paroisse` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Diocese` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `TelVoda` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `TelOrange` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `TelAirtel` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Annscol` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `NomEtablis` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `PourceCertificat` int DEFAULT NULL,
  `PourceDiplome` int DEFAULT NULL,
  `NumDiplom` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `SetionEtude` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `OptionEtude` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Lieudelivrance` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Datedelivrance` date DEFAULT NULL,
  `Ecole` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Province` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ProvinceOrigine` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Territoire` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `TelResponsable` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`idAutreInfo_etudiant`),
  KEY `fk_AutreInfo_etudiant_Etudiant1_idx` (`Matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `IdCategorie` int NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`IdCategorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compte_agent`
--

DROP TABLE IF EXISTS `compte_agent`;
CREATE TABLE IF NOT EXISTS `compte_agent` (
  `Id_Compte_agent` int NOT NULL AUTO_INCREMENT,
  `Mat_agent` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `id_annee_academique` int DEFAULT NULL,
  `Code_promotion` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Id_filiere` int DEFAULT NULL,
  `Login` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Mot_passe` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Etat` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Categorie` varchar(45) DEFAULT NULL,
  `Nom_image` varchar(255) DEFAULT NULL,
  `Type_image` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Photo_profil` longblob,
  `Fonction` varchar(50) DEFAULT NULL,
  `Date_creation` datetime DEFAULT NULL,
  PRIMARY KEY (`Id_Compte_agent`),
  KEY `fk_Compte_agent_Agent1_idx` (`Mat_agent`),
  KEY `Id_filiere` (`Id_filiere`),
  KEY `Code_promotion` (`Code_promotion`),
  KEY `id_annee_academique` (`id_annee_academique`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `compte_etudiant`
--

DROP TABLE IF EXISTS `compte_etudiant`;
CREATE TABLE IF NOT EXISTS `compte_etudiant` (
  `id_Compte_etudiant` int NOT NULL AUTO_INCREMENT,
  `Mail_etudiant` varchar(255) NOT NULL,
  `Mot_de_passe` text NOT NULL,
  `Matricule` varchar(20) NOT NULL,
  PRIMARY KEY (`id_Compte_etudiant`),
  KEY `fk_Compte_etudiant_Etudiant1_idx` (`Matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `decaissement_caisse`
--

DROP TABLE IF EXISTS `decaissement_caisse`;
CREATE TABLE IF NOT EXISTS `decaissement_caisse` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Beneficiaire` varchar(100) NOT NULL,
  `Motif` varchar(200) NOT NULL,
  `Montant` float NOT NULL,
  `Date_Oper` datetime NOT NULL,
  `Imputation` int NOT NULL,
  `Num_piece` varchar(100) NOT NULL,
  `Statut` varchar(10) NOT NULL,
  `Id_Anne_Acad` int NOT NULL,
  `Num_Autoriz` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `Imputation` (`Imputation`),
  KEY `Num_piece` (`Num_piece`),
  KEY `Num_Autoriz` (`Num_Autoriz`),
  KEY `Id_Anne_Acad` (`Id_Anne_Acad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `domaine`
--

DROP TABLE IF EXISTS `domaine`;
CREATE TABLE IF NOT EXISTS `domaine` (
  `idDomaine` int NOT NULL AUTO_INCREMENT,
  `Libelle_domaine` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idDomaine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `element_constitutifs`
--

DROP TABLE IF EXISTS `element_constitutifs`;
CREATE TABLE IF NOT EXISTS `element_constitutifs` (
  `id_ec` int NOT NULL AUTO_INCREMENT,
  `Code_ue` varchar(10) NOT NULL,
  `Intutile_ec` varchar(50) NOT NULL,
  `Hr_TD` int NOT NULL,
  `Hr_TP` int NOT NULL,
  `Credit` int NOT NULL,
  `TPE` int NOT NULL,
  `VHT` int NOT NULL,
  `CMI` int NOT NULL,
  PRIMARY KEY (`id_ec`),
  KEY `fk_Element_constutifs_Unite_Enseignant1_idx` (`Code_ue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `element_constitutifs_aligne`
--

DROP TABLE IF EXISTS `element_constitutifs_aligne`;
CREATE TABLE IF NOT EXISTS `element_constitutifs_aligne` (
  `id_ec_aligne` int NOT NULL AUTO_INCREMENT,
  `idAnnee_Acad` int NOT NULL,
  `id_ec` int NOT NULL,
  `Id_Semestre` int NOT NULL,
  `Code_promotion` varchar(10) NOT NULL,
  `Mat_agent` varchar(20) NOT NULL,
  `Mat_assistant` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_ec_aligne`),
  KEY `fk_Element_constitufs_aligne_Annee_academique1_idx` (`idAnnee_Acad`),
  KEY `fk_Element_constitufs_aligne_Element_constutifs1_idx` (`id_ec`),
  KEY `Id_Semestre` (`Id_Semestre`),
  KEY `Mat_agent` (`Mat_agent`),
  KEY `Code_promotion` (`Code_promotion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `encaissement_caisse`
--

DROP TABLE IF EXISTS `encaissement_caisse`;
CREATE TABLE IF NOT EXISTS `encaissement_caisse` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Motif` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Id_Service` int DEFAULT NULL,
  `Id_filiere` int DEFAULT NULL,
  `Montant` float NOT NULL,
  `Numero_pce` varchar(100) NOT NULL,
  `Date_Oper` datetime NOT NULL,
  `Statut` varchar(50) NOT NULL,
  `Deposant` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Imputation` int NOT NULL,
  `IdAnnee` int NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Numero_pce` (`Numero_pce`),
  KEY `Imputation` (`Imputation`),
  KEY `IdAnnee` (`IdAnnee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enfant`
--

DROP TABLE IF EXISTS `enfant`;
CREATE TABLE IF NOT EXISTS `enfant` (
  `IdEnfant` int NOT NULL AUTO_INCREMENT,
  `Noms` varchar(80) NOT NULL,
  `Lieu_Naissance` varchar(30) NOT NULL,
  `DateNaissance` date NOT NULL,
  `Mat_agent` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`IdEnfant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

DROP TABLE IF EXISTS `enseignant`;
CREATE TABLE IF NOT EXISTS `enseignant` (
  `Matr_enseign` varchar(20) NOT NULL,
  `Nom_enseign` varchar(30) DEFAULT NULL,
  `Postnom_enseign` varchar(30) DEFAULT NULL,
  `Prenom_enseign` varchar(30) DEFAULT NULL,
  `Sexe` char(1) DEFAULT NULL,
  `Titre_academique` varchar(50) DEFAULT NULL,
  `Domaine` varchar(50) DEFAULT NULL,
  `Instutition_attache` varchar(50) DEFAULT NULL,
  `Photo_profil` longblob,
  PRIMARY KEY (`Matr_enseign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `est_pris_en_charger`
--

DROP TABLE IF EXISTS `est_pris_en_charger`;
CREATE TABLE IF NOT EXISTS `est_pris_en_charger` (
  `Mat_agent` varchar(20) NOT NULL,
  `Matricule` varchar(20) NOT NULL,
  `_idAnnee_Acad` int NOT NULL,
  `Mois` varchar(45) DEFAULT NULL,
  `Montant` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Mat_agent`,`Matricule`,`_idAnnee_Acad`),
  KEY `fk_Agent_has_Etudiant_Etudiant1_idx` (`Matricule`),
  KEY `fk_Agent_has_Etudiant_Agent1_idx` (`Mat_agent`),
  KEY `fk_Est_pris_en_charger_Annee_academique1_idx` (`_idAnnee_Acad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `Matricule` varchar(20) NOT NULL,
  `Nom` varchar(30) DEFAULT NULL,
  `Postnom` varchar(30) DEFAULT NULL,
  `Prenom` varchar(30) DEFAULT NULL,
  `Sexe` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `LieuNaissance` varchar(50) DEFAULT NULL,
  `DateNaissance` date DEFAULT NULL,
  PRIMARY KEY (`Matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `evaluer`
--

DROP TABLE IF EXISTS `evaluer`;
CREATE TABLE IF NOT EXISTS `evaluer` (
  `Matricule` varchar(20) NOT NULL,
  `id_ec_aligne` int NOT NULL,
  `Cote` float DEFAULT NULL,
  `Cote_rattrapage` float DEFAULT NULL,
  PRIMARY KEY (`Matricule`,`id_ec_aligne`),
  KEY `fk_Etudiant_has_Element_constitufs_aligne_Element_constituf_idx` (`id_ec_aligne`),
  KEY `fk_Etudiant_has_Element_constitufs_aligne_Etudiant1_idx` (`Matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `details` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

DROP TABLE IF EXISTS `filiere`;
CREATE TABLE IF NOT EXISTS `filiere` (
  `IdFiliere` int NOT NULL AUTO_INCREMENT,
  `idDomaine` int NOT NULL,
  `Libelle_Filiere` varchar(52) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdFiliere`),
  KEY `fk_Filiere_Domaine1_idx` (`idDomaine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `fonction_user`
--

DROP TABLE IF EXISTS `fonction_user`;
CREATE TABLE IF NOT EXISTS `fonction_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Designation` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `frais`
--

DROP TABLE IF EXISTS `frais`;
CREATE TABLE IF NOT EXISTS `frais` (
  `idFrais` int NOT NULL AUTO_INCREMENT,
  `idAnnee_Acad` int NOT NULL,
  `Code_Promotion` varchar(10) NOT NULL,
  `Libelle_Frais` varchar(45) NOT NULL,
  `Montant` float NOT NULL,
  `Tranche` float NOT NULL,
  `Devise` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`idFrais`),
  KEY `fk_Frais_Annee_academique1_idx` (`idAnnee_Acad`),
  KEY `fk_Frais_Promotion1_idx` (`Code_Promotion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `lieu_paiement`
--

DROP TABLE IF EXISTS `lieu_paiement`;
CREATE TABLE IF NOT EXISTS `lieu_paiement` (
  `idLieu_paiement` int NOT NULL AUTO_INCREMENT,
  `Libelle_lieu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idLieu_paiement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `mecanisation`
--

DROP TABLE IF EXISTS `mecanisation`;
CREATE TABLE IF NOT EXISTS `mecanisation` (
  `MatriculeAgent` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Libelle` varchar(20) NOT NULL,
  `Observation` int NOT NULL,
  PRIMARY KEY (`MatriculeAgent`,`Libelle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mentions`
--

DROP TABLE IF EXISTS `mentions`;
CREATE TABLE IF NOT EXISTS `mentions` (
  `idMentions` int NOT NULL AUTO_INCREMENT,
  `IdFiliere` int NOT NULL,
  `Libelle_mention` varchar(50) DEFAULT NULL,
  `Cycle_mention` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idMentions`),
  KEY `fk_Mentions_Filiere1_idx` (`IdFiliere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `numero_autorisation`
--

DROP TABLE IF EXISTS `numero_autorisation`;
CREATE TABLE IF NOT EXISTS `numero_autorisation` (
  `numero_pce` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`numero_pce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `numero_piece`
--

DROP TABLE IF EXISTS `numero_piece`;
CREATE TABLE IF NOT EXISTS `numero_piece` (
  `numero_pce` varchar(100) NOT NULL,
  PRIMARY KEY (`numero_pce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paie_locale`
--

DROP TABLE IF EXISTS `paie_locale`;
CREATE TABLE IF NOT EXISTS `paie_locale` (
  `Idpaie` int NOT NULL AUTO_INCREMENT,
  `MatriculeAgent` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Libelle` varchar(20) NOT NULL,
  `Observation` varchar(20) NOT NULL,
  PRIMARY KEY (`Idpaie`),
  KEY `Mat_agent` (`MatriculeAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parent`
--

DROP TABLE IF EXISTS `parent`;
CREATE TABLE IF NOT EXISTS `parent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `MatriculeAgent` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Noms` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Statut` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `annee_dec` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `passer_par`
--

DROP TABLE IF EXISTS `passer_par`;
CREATE TABLE IF NOT EXISTS `passer_par` (
  `Etudiant_Matricule` varchar(20) NOT NULL,
  `Code_Promotion` varchar(10) NOT NULL,
  `idAnnee_academique` int NOT NULL,
  `Decision_jury` varchar(50) DEFAULT NULL,
  `Session1` float DEFAULT NULL,
  `Mention1` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Session2` float DEFAULT NULL,
  `Mention2` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Active` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`Etudiant_Matricule`,`Code_Promotion`,`idAnnee_academique`),
  KEY `fk_Etudiant_has_Promotion_Promotion1_idx` (`Code_Promotion`),
  KEY `fk_Etudiant_has_Promotion_Etudiant1_idx` (`Etudiant_Matricule`),
  KEY `fk_Etudiant_has_Promotion_Annee_academique1_idx` (`idAnnee_academique`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `payer_frais`
--

DROP TABLE IF EXISTS `payer_frais`;
CREATE TABLE IF NOT EXISTS `payer_frais` (
  `Id_payer_frais` int NOT NULL AUTO_INCREMENT,
  `Matricule` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `idFrais` int NOT NULL,
  `Date_paie` datetime NOT NULL,
  `idLieu_paiement` int NOT NULL,
  `Mat_agent` varchar(20) NOT NULL,
  `Montant_paie` float DEFAULT NULL,
  `Motif_paie` varchar(45) DEFAULT NULL,
  `Numero_bordereau` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Ensemble` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Cette colonne nous permet de rassamblés les réçus d''une personne ( FA et F_Enrol)',
  `Fc` float DEFAULT NULL COMMENT 'Cette colonne nous permet de faire l''impression de rapport en franc congolais',
  `Motif_suppression` varchar(255) DEFAULT NULL COMMENT 'Motif obligatoire avant suppression',
  PRIMARY KEY (`Id_payer_frais`),
  KEY `fk_Etudiant_has_Frais_Frais1_idx` (`idFrais`),
  KEY `fk_Etudiant_has_Frais_Etudiant1_idx` (`Matricule`),
  KEY `fk_Paie_Etudiant_Lieu_paiement1_idx` (`idLieu_paiement`),
  KEY `fk_Payer_Frais_Agent1_idx` (`Mat_agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déclencheurs `payer_frais`
--
DROP TRIGGER IF EXISTS `Log_Modification`;
DELIMITER $$
CREATE TRIGGER `Log_Modification` BEFORE UPDATE ON `payer_frais` FOR EACH ROW BEGIN
  DECLARE ancienne_valeur TEXT;
  DECLARE nouvelle_valeur TEXT;
  DECLARE colonne_modifiée TEXT;

  -- Vérifier si l'utilisateur est uka
  IF SESSION_USER() LIKE 'uka@%' THEN

    -- Modification du Montant_paie
    IF OLD.Montant_paie <> NEW.Montant_paie THEN
      SET colonne_modifiée = 'Montant_paie';
      SET ancienne_valeur = CONCAT('Montant:', IFNULL(OLD.Montant_paie, ''));
      SET nouvelle_valeur = CONCAT('Montant:', IFNULL(NEW.Montant_paie, ''));

      CALL bdd_uka_audit.Modification_Log(
        SESSION_USER(),
        'payer_frais',
        colonne_modifiée,
        OLD.Id_payer_frais,
        ancienne_valeur,
        nouvelle_valeur,
        CONCAT_WS(', ',
          CONCAT('Matricule:', IFNULL(OLD.Matricule, '')),
          CONCAT('Mat_agent:', IFNULL(OLD.Mat_agent, '')),
          CONCAT('Ensemble:', IFNULL(OLD.Ensemble, '')),
          CONCAT('Motif_paie:', IFNULL(OLD.Motif_paie, ''))
        )
      );
    END IF;

    -- Modification de Date_paie
    IF OLD.Date_paie <> NEW.Date_paie THEN
      SET colonne_modifiée = 'Date_paie';
      SET ancienne_valeur = CONCAT('Date:', IFNULL(OLD.Date_paie, ''));
      SET nouvelle_valeur = CONCAT('Date:', IFNULL(NEW.Date_paie, ''));

      CALL bdd_uka_audit.Modification_Log(
        SESSION_USER(),
        'payer_frais',
        colonne_modifiée,
        OLD.Id_payer_frais,
        ancienne_valeur,
        nouvelle_valeur,
        CONCAT_WS(', ',
          CONCAT('Matricule:', IFNULL(OLD.Matricule, '')),
          CONCAT('Mat_agent:', IFNULL(OLD.Mat_agent, '')),
          CONCAT('Ensemble:', IFNULL(OLD.Ensemble, '')),
          CONCAT('Motif_paie:', IFNULL(OLD.Motif_paie, ''))
        )
      );
    END IF;
    
     -- Modification Matricule agent
    IF OLD.Mat_agent  <> NEW.Mat_agent  THEN
      SET colonne_modifiée = 'Mat_agent ';
      SET ancienne_valeur = CONCAT('Mat_agent :', IFNULL(OLD.Date_paie, ''));
      SET nouvelle_valeur = CONCAT('Mat_agent :', IFNULL(NEW.Date_paie, ''));

      CALL bdd_uka_audit.Modification_Log(
        SESSION_USER(),
        'payer_frais',
        colonne_modifiée,
        OLD.Id_payer_frais,
        ancienne_valeur,
        nouvelle_valeur,
        CONCAT_WS(', ',
          CONCAT('Matricule:', IFNULL(OLD.Matricule, '')),
          CONCAT('Mat_agent:', IFNULL(OLD.Mat_agent, '')),
          CONCAT('Ensemble:', IFNULL(OLD.Ensemble, '')),
          CONCAT('Motif_paie:', IFNULL(OLD.Motif_paie, ''))
        )
      );
    END IF;

  END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Log_Suppression`;
DELIMITER $$
CREATE TRIGGER `Log_Suppression` BEFORE DELETE ON `payer_frais` FOR EACH ROW BEGIN
  -- Étape 1 : Vérification du motif obligatoire
  IF OLD.Motif_suppression IS NULL OR TRIM(OLD.Motif_suppression) = '' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Motif de suppression obligatoire dans Motif_suppression';
  END IF;

  -- Étape 2 : Journalisation uniquement si l'utilisateur est uka
  IF SESSION_USER() LIKE 'uka@%' THEN
    CALL bdd_uka_audit.Suppression_Log(
      SESSION_USER(),
      'payer_frais',
      'Montant_paie, Date_paie',
      OLD.Id_payer_frais,
      CONCAT('Montant:', IFNULL(OLD.Montant_paie, ''), ', Date:', IFNULL(OLD.Date_paie, '')),
      CONCAT_WS(', ',
        CONCAT('Matricule:', IFNULL(OLD.Matricule, '')),
        CONCAT('Mat_agent:', IFNULL(OLD.Mat_agent, '')),
        CONCAT('Ensemble:', IFNULL(OLD.Ensemble, '')),
        CONCAT('Motif_paie:', IFNULL(OLD.Motif_paie, '')),
        CONCAT('Motif_suppression:', IFNULL(OLD.Motif_suppression, ''))
      )
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `IdImage` int NOT NULL AUTO_INCREMENT,
  `Matricule` varchar(20) NOT NULL,
  `Photo` longblob,
  `Nom_image` varchar(255) DEFAULT NULL,
  `Type_image` varchar(50) DEFAULT NULL,
  `Avatar` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`IdImage`),
  KEY `fk_Image_Etudiant_idx` (`Matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `Code_Promotion` varchar(10) NOT NULL,
  `idMentions` int NOT NULL,
  `Libelle_promotion` varchar(50) NOT NULL,
  `Abréviation` varchar(10) NOT NULL,
  PRIMARY KEY (`Code_Promotion`),
  KEY `fk_Promotion_Mentions1_idx` (`idMentions`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `recette_generale`
--

DROP TABLE IF EXISTS `recette_generale`;
CREATE TABLE IF NOT EXISTS `recette_generale` (
  `Id_rubrique` int NOT NULL,
  `Ref_budget` int NOT NULL,
  `Montant` int NOT NULL,
  PRIMARY KEY (`Id_rubrique`,`Ref_budget`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `repartition`
--

DROP TABLE IF EXISTS `repartition`;
CREATE TABLE IF NOT EXISTS `repartition` (
  `Id_repartition` int NOT NULL AUTO_INCREMENT,
  `Id_rubrique` int NOT NULL,
  `Code_promotion` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `idAnnee_Acad` int NOT NULL,
  `Montant` float NOT NULL,
  PRIMARY KEY (`Id_repartition`),
  KEY `idAnnee_Acad` (`idAnnee_Acad`),
  KEY `Code_promotion` (`Code_promotion`),
  KEY `Id_rubrique` (`Id_rubrique`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

DROP TABLE IF EXISTS `rubrique`;
CREATE TABLE IF NOT EXISTS `rubrique` (
  `Id_rubrique` int NOT NULL,
  `Libelle` varchar(100) NOT NULL,
  `Categorie` varchar(50) NOT NULL,
  PRIMARY KEY (`Id_rubrique`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `Id_Semestre` int NOT NULL AUTO_INCREMENT,
  `libelle_semestre` varchar(45) DEFAULT NULL,
  `Niveau_semestre` int DEFAULT NULL,
  PRIMARY KEY (`Id_Semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `IdService` int NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(100) NOT NULL,
  PRIMARY KEY (`IdService`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `solde`
--

DROP TABLE IF EXISTS `solde`;
CREATE TABLE IF NOT EXISTS `solde` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `date_solde` datetime NOT NULL,
  `devise` varchar(10) NOT NULL,
  `montant` float NOT NULL,
  `Observation` varchar(1000) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `taux_du_jours`
--

DROP TABLE IF EXISTS `taux_du_jours`;
CREATE TABLE IF NOT EXISTS `taux_du_jours` (
  `Id_Taux_du_jours` int NOT NULL AUTO_INCREMENT,
  `Montant_du_jour` float NOT NULL,
  `Date_modification` date NOT NULL,
  PRIMARY KEY (`Id_Taux_du_jours`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_conge`
--

DROP TABLE IF EXISTS `type_conge`;
CREATE TABLE IF NOT EXISTS `type_conge` (
  `IdTypeConge` int NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`IdTypeConge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_budget`
--

DROP TABLE IF EXISTS `t_budget`;
CREATE TABLE IF NOT EXISTS `t_budget` (
  `Ref_budget` int NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Periodicite` varchar(25) NOT NULL,
  `Annee_debut` int NOT NULL,
  `Annee_fin` int NOT NULL,
  `Idservice` int DEFAULT NULL,
  `id_filiere` int DEFAULT NULL,
  PRIMARY KEY (`Ref_budget`),
  KEY `Idservice` (`Idservice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_depense_generale`
--

DROP TABLE IF EXISTS `t_depense_generale`;
CREATE TABLE IF NOT EXISTS `t_depense_generale` (
  `Ref_budget` int NOT NULL,
  `Num_imputation` int NOT NULL,
  `Montant` int NOT NULL,
  PRIMARY KEY (`Ref_budget`,`Num_imputation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_depense_prevues`
--

DROP TABLE IF EXISTS `t_depense_prevues`;
CREATE TABLE IF NOT EXISTS `t_depense_prevues` (
  `Id_depense` int NOT NULL AUTO_INCREMENT,
  `Ref_budget` int NOT NULL,
  `Num_imputation` int DEFAULT NULL,
  `Montant` float NOT NULL,
  PRIMARY KEY (`Id_depense`),
  KEY `Num_compte` (`Num_imputation`),
  KEY `Ref_budget` (`Ref_budget`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_imputation`
--

DROP TABLE IF EXISTS `t_imputation`;
CREATE TABLE IF NOT EXISTS `t_imputation` (
  `Num_imputation` int NOT NULL,
  `Intitul_compte` varchar(50) NOT NULL,
  `Pourcent_budget` int NOT NULL,
  PRIMARY KEY (`Num_imputation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_recettes_prevues`
--

DROP TABLE IF EXISTS `t_recettes_prevues`;
CREATE TABLE IF NOT EXISTS `t_recettes_prevues` (
  `Id_recette` int NOT NULL AUTO_INCREMENT,
  `Designation` varchar(50) NOT NULL,
  `IdPromotion` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Id_type_Recette` int NOT NULL,
  `Ref_budget` int NOT NULL,
  PRIMARY KEY (`Id_recette`),
  KEY `Ref_budget` (`Ref_budget`),
  KEY `Id_type_recette` (`Id_type_Recette`),
  KEY `IdPromotion` (`IdPromotion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_type_recette`
--

DROP TABLE IF EXISTS `t_type_recette`;
CREATE TABLE IF NOT EXISTS `t_type_recette` (
  `Id_type_Recette` int NOT NULL AUTO_INCREMENT,
  `Libelle_type` varchar(80) NOT NULL,
  PRIMARY KEY (`Id_type_Recette`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `unite_enseignement`
--

DROP TABLE IF EXISTS `unite_enseignement`;
CREATE TABLE IF NOT EXISTS `unite_enseignement` (
  `Code_ue` varchar(10) NOT NULL,
  `IdFiliere` int NOT NULL,
  `Intitule_ue` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Catégorie` varchar(200) NOT NULL,
  PRIMARY KEY (`Code_ue`),
  KEY `fk_Unite_Enseignant_Semestre1_idx` (`IdFiliere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `affecter`
--
ALTER TABLE `affecter`
  ADD CONSTRAINT `affecter_ibfk_1` FOREIGN KEY (`Id_filiere`) REFERENCES `filiere` (`IdFiliere`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `affecter_ibfk_2` FOREIGN KEY (`Matricule_Agent`) REFERENCES `agent` (`Mat_agent`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`IdService`) REFERENCES `service` (`IdService`);

--
-- Contraintes pour la table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agent_ibfk_1` FOREIGN KEY (`Id_filiere`) REFERENCES `filiere` (`IdFiliere`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_categorie` FOREIGN KEY (`IdCategorie`) REFERENCES `categorie` (`IdCategorie`);

--
-- Contraintes pour la table `autorisation_depense`
--
ALTER TABLE `autorisation_depense`
  ADD CONSTRAINT `autorisation_depense_ibfk_1` FOREIGN KEY (`Imputation`) REFERENCES `t_imputation` (`Num_imputation`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `autorisation_depense_ibfk_2` FOREIGN KEY (`Num_pce`) REFERENCES `numero_autorisation` (`numero_pce`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `autoriser_depense`
--
ALTER TABLE `autoriser_depense`
  ADD CONSTRAINT `autoriser_depense_ibfk_1` FOREIGN KEY (`Agent_auriz1`) REFERENCES `agent` (`Mat_agent`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `autoriser_depense_ibfk_2` FOREIGN KEY (`Agent_auriz2`) REFERENCES `agent` (`Mat_agent`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `autoriser_depense_ibfk_3` FOREIGN KEY (`Num_pce`) REFERENCES `numero_autorisation` (`numero_pce`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `compte_agent`
--
ALTER TABLE `compte_agent`
  ADD CONSTRAINT `compte_agent_ibfk_1` FOREIGN KEY (`Id_filiere`) REFERENCES `filiere` (`IdFiliere`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compte_agent_ibfk_2` FOREIGN KEY (`Code_promotion`) REFERENCES `promotion` (`Code_Promotion`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `compte_agent_ibfk_3` FOREIGN KEY (`id_annee_academique`) REFERENCES `annee_academique` (`idAnnee_Acad`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_Compte_agent_Agent1` FOREIGN KEY (`Mat_agent`) REFERENCES `agent` (`Mat_agent`);

--
-- Contraintes pour la table `compte_etudiant`
--
ALTER TABLE `compte_etudiant`
  ADD CONSTRAINT `fk_Compte_etudiant_Etudiant1` FOREIGN KEY (`Matricule`) REFERENCES `etudiant` (`Matricule`);

--
-- Contraintes pour la table `decaissement_caisse`
--
ALTER TABLE `decaissement_caisse`
  ADD CONSTRAINT `decaissement_caisse_ibfk_1` FOREIGN KEY (`Imputation`) REFERENCES `t_imputation` (`Num_imputation`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `decaissement_caisse_ibfk_2` FOREIGN KEY (`Num_piece`) REFERENCES `numero_piece` (`numero_pce`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `decaissement_caisse_ibfk_3` FOREIGN KEY (`Num_Autoriz`) REFERENCES `numero_autorisation` (`numero_pce`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `decaissement_caisse_ibfk_4` FOREIGN KEY (`Id_Anne_Acad`) REFERENCES `annee_academique` (`idAnnee_Acad`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `element_constitutifs`
--
ALTER TABLE `element_constitutifs`
  ADD CONSTRAINT `fk_Element_constutifs_Unite_Enseignant1` FOREIGN KEY (`Code_ue`) REFERENCES `unite_enseignement` (`Code_ue`);

--
-- Contraintes pour la table `element_constitutifs_aligne`
--
ALTER TABLE `element_constitutifs_aligne`
  ADD CONSTRAINT `element_constitutifs_aligne_ibfk_1` FOREIGN KEY (`idAnnee_Acad`) REFERENCES `annee_academique` (`idAnnee_Acad`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `element_constitutifs_aligne_ibfk_2` FOREIGN KEY (`Id_Semestre`) REFERENCES `semestre` (`Id_Semestre`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `element_constitutifs_aligne_ibfk_3` FOREIGN KEY (`Mat_agent`) REFERENCES `agent` (`Mat_agent`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `element_constitutifs_aligne_ibfk_4` FOREIGN KEY (`Code_promotion`) REFERENCES `promotion` (`Code_Promotion`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_Element_constitufs_aligne_Element_constutifs1` FOREIGN KEY (`id_ec`) REFERENCES `element_constitutifs` (`id_ec`);

--
-- Contraintes pour la table `encaissement_caisse`
--
ALTER TABLE `encaissement_caisse`
  ADD CONSTRAINT `encaissement_caisse_ibfk_1` FOREIGN KEY (`Numero_pce`) REFERENCES `numero_piece` (`numero_pce`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `encaissement_caisse_ibfk_2` FOREIGN KEY (`Imputation`) REFERENCES `t_imputation` (`Num_imputation`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `encaissement_caisse_ibfk_3` FOREIGN KEY (`IdAnnee`) REFERENCES `annee_academique` (`idAnnee_Acad`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `est_pris_en_charger`
--
ALTER TABLE `est_pris_en_charger`
  ADD CONSTRAINT `fk_Agent_has_Etudiant_Agent1` FOREIGN KEY (`Mat_agent`) REFERENCES `agent` (`Mat_agent`),
  ADD CONSTRAINT `fk_Agent_has_Etudiant_Etudiant1` FOREIGN KEY (`Matricule`) REFERENCES `etudiant` (`Matricule`),
  ADD CONSTRAINT `fk_Est_pris_en_charger_Annee_academique1` FOREIGN KEY (`_idAnnee_Acad`) REFERENCES `annee_academique` (`idAnnee_Acad`);

--
-- Contraintes pour la table `evaluer`
--
ALTER TABLE `evaluer`
  ADD CONSTRAINT `fk_Etudiant_has_Element_constitufs_aligne_Element_constitufs_1` FOREIGN KEY (`id_ec_aligne`) REFERENCES `element_constitutifs_aligne` (`id_ec_aligne`),
  ADD CONSTRAINT `fk_Etudiant_has_Element_constitufs_aligne_Etudiant1` FOREIGN KEY (`Matricule`) REFERENCES `etudiant` (`Matricule`);

--
-- Contraintes pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD CONSTRAINT `fk_Filiere_Domaine1` FOREIGN KEY (`idDomaine`) REFERENCES `domaine` (`idDomaine`);

--
-- Contraintes pour la table `frais`
--
ALTER TABLE `frais`
  ADD CONSTRAINT `fk_Frais_Annee_academique1` FOREIGN KEY (`idAnnee_Acad`) REFERENCES `annee_academique` (`idAnnee_Acad`),
  ADD CONSTRAINT `fk_Frais_Promotion1` FOREIGN KEY (`Code_Promotion`) REFERENCES `promotion` (`Code_Promotion`);

--
-- Contraintes pour la table `mecanisation`
--
ALTER TABLE `mecanisation`
  ADD CONSTRAINT `mecanisation_ibfk_1` FOREIGN KEY (`MatriculeAgent`) REFERENCES `agent` (`Mat_agent`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mentions`
--
ALTER TABLE `mentions`
  ADD CONSTRAINT `fk_Mentions_Filiere1` FOREIGN KEY (`IdFiliere`) REFERENCES `filiere` (`IdFiliere`);

--
-- Contraintes pour la table `paie_locale`
--
ALTER TABLE `paie_locale`
  ADD CONSTRAINT `paie_locale_ibfk_1` FOREIGN KEY (`MatriculeAgent`) REFERENCES `agent` (`Mat_agent`);

--
-- Contraintes pour la table `passer_par`
--
ALTER TABLE `passer_par`
  ADD CONSTRAINT `fk_Etudiant_has_Promotion_Annee_academique1` FOREIGN KEY (`idAnnee_academique`) REFERENCES `annee_academique` (`idAnnee_Acad`),
  ADD CONSTRAINT `fk_Etudiant_has_Promotion_Etudiant1` FOREIGN KEY (`Etudiant_Matricule`) REFERENCES `etudiant` (`Matricule`),
  ADD CONSTRAINT `fk_Etudiant_has_Promotion_Promotion1` FOREIGN KEY (`Code_Promotion`) REFERENCES `promotion` (`Code_Promotion`);

--
-- Contraintes pour la table `payer_frais`
--
ALTER TABLE `payer_frais`
  ADD CONSTRAINT `fk_Etudiant_has_Frais_Etudiant1` FOREIGN KEY (`Matricule`) REFERENCES `etudiant` (`Matricule`),
  ADD CONSTRAINT `fk_Paie_Etudiant_Lieu_paiement1` FOREIGN KEY (`idLieu_paiement`) REFERENCES `lieu_paiement` (`idLieu_paiement`),
  ADD CONSTRAINT `fk_Payer_Frais_Agent1` FOREIGN KEY (`Mat_agent`) REFERENCES `agent` (`Mat_agent`),
  ADD CONSTRAINT `payer_frais_ibfk_1` FOREIGN KEY (`idFrais`) REFERENCES `frais` (`idFrais`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `fk_Image_Etudiant` FOREIGN KEY (`Matricule`) REFERENCES `etudiant` (`Matricule`);

--
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `fk_Promotion_Mentions1` FOREIGN KEY (`idMentions`) REFERENCES `mentions` (`idMentions`);

--
-- Contraintes pour la table `unite_enseignement`
--
ALTER TABLE `unite_enseignement`
  ADD CONSTRAINT `unite_enseignement_ibfk_1` FOREIGN KEY (`IdFiliere`) REFERENCES `filiere` (`IdFiliere`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
