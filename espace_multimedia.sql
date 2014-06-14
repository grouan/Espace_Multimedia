--
-- Base de donnÈes: `ESPACE_MULTIMEDIA`
--

-- --------------------------------------------------------

--
-- Structure de la table `stave_autorisation_parentale`
--

DROP TABLE IF EXISTS `stave_autorisation_parentale`;
CREATE TABLE IF NOT EXISTS `stave_autorisation_parentale` (
  `id` int(32) NOT NULL auto_increment,
  `inscrit_id` int(16) NOT NULL,
  `debut_date` date NOT NULL default '0000-00-00',
  `fin_date` date NOT NULL default '0000-00-00',
  `resp_nom` varchar(255) collate latin1_general_ci NOT NULL,
  `resp_prenom` varchar(255) collate latin1_general_ci NOT NULL,
  `resp_adresse` varchar(255) collate latin1_general_ci NOT NULL,
  `resp_cp` varchar(8) collate latin1_general_ci NOT NULL,
  `resp_ville` varchar(32) collate latin1_general_ci NOT NULL,
  `resp_pays` varchar(32) collate latin1_general_ci NOT NULL default 'France',
  `resp_tel_fixe` varchar(16) collate latin1_general_ci NOT NULL,
  `resp_tel_mobile` varchar(16) collate latin1_general_ci NOT NULL,
  `resp_email` varchar(255) collate latin1_general_ci NOT NULL,
  `resp_identite_type` enum('cni','passeport','permis','militaire') collate latin1_general_ci NOT NULL default 'cni',
  `resp_identite_num` varchar(32) collate latin1_general_ci NOT NULL,
  `resp_identite_date` date NOT NULL,
  `resp_identite_lieu` varchar(255) collate latin1_general_ci NOT NULL,
  `resp_signature` enum('oui','non') collate latin1_general_ci NOT NULL default 'oui',
  `note` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Structure de la table `stave_categorie_age`
--

DROP TABLE IF EXISTS `stave_categorie_age`;
CREATE TABLE IF NOT EXISTS `stave_categorie_age` (
  `id` int(2) NOT NULL auto_increment,
  `nom` varchar(255) collate latin1_general_ci NOT NULL,
  `debut_date` date NOT NULL,
  `fin_date` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Structure de la table `stave_inscrit`
--

DROP TABLE IF EXISTS `stave_inscrit`;
CREATE TABLE IF NOT EXISTS `stave_inscrit` (
  `id` int(16) NOT NULL auto_increment,
  `adherent_num` int(16) NOT NULL,
  `personnel` enum('oui','non') collate latin1_general_ci NOT NULL default 'non',
  `nom` varchar(255) collate latin1_general_ci NOT NULL,
  `prenom` varchar(255) collate latin1_general_ci NOT NULL,
  `naissance_date` date NOT NULL default '0000-00-00',
  `majeur` enum('oui','non') collate latin1_general_ci NOT NULL default 'oui',
  `sexe` enum('femme','homme') collate latin1_general_ci NOT NULL default 'homme',
  `adresse` varchar(255) collate latin1_general_ci NOT NULL,
  `cp` varchar(8) collate latin1_general_ci NOT NULL,
  `ville` varchar(32) collate latin1_general_ci NOT NULL,
  `pays` varchar(32) collate latin1_general_ci NOT NULL default 'France',
  `tel_fixe` varchar(16) collate latin1_general_ci NOT NULL,
  `tel_mobile` varchar(16) collate latin1_general_ci NOT NULL,
  `email` varchar(255) collate latin1_general_ci NOT NULL,
  `identite_type` enum('cni','passeport','permis','militaire') collate latin1_general_ci NOT NULL default 'cni',
  `identite_num` int(32) NOT NULL,
  `identite_date` date NOT NULL default '0000-00-00',
  `identite_lieu` varchar(255) collate latin1_general_ci NOT NULL,
  `socio_pro` enum('etudiant','retraite','demandeur_emploi','sans_emploi','salarie_prive','salarie_public','autre') collate latin1_general_ci NOT NULL default 'autre',
  `inscription_date` date NOT NULL default '0000-00-00',
  `note` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Structure de la table `stave_victorhugo`
--

DROP TABLE IF EXISTS `stave_victorhugo`;
CREATE TABLE IF NOT EXISTS `stave_victorhugo` (
  `id` int(2) NOT NULL auto_increment,
  `pseudo` varchar(255) collate latin1_general_ci NOT NULL,
  `mdp` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;


-- --------------------------------------------------------

--
-- Structure de la table `stave_visite`
--

DROP TABLE IF EXISTS `stave_visite`;
CREATE TABLE IF NOT EXISTS `stave_visite` (
  `id` int(32) NOT NULL auto_increment,
  `poste_num` int(2) NOT NULL,
  `jour_date` date NOT NULL default '0000-00-00',
  `heure_arrivee` time NOT NULL default '00:00:00',
  `heure_limite` time NOT NULL default '00:00:00',
  `heure_depart` time NOT NULL default '00:00:00',
  `duree_minutes` time NOT NULL default '00:00:00',
  `inscrit_id` int(16) NOT NULL,
  `activite` enum('cdrom_jeux','jeux_en_ligne','jeux_en_reseau','messagerie','blog-forum','recherches','bureautique','cdrom_documentaires') collate latin1_general_ci NOT NULL default 'bureautique',
  `note` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;

