-- phpMyAdmin SQL Dump

-- version 2.6.0-pl3

-- http://www.phpmyadmin.net

-- 

-- Serveur: localhost

-- Généré le : Dimanche 03 Decembre 2006 à 16:34

-- Version du serveur: 4.1.8

-- Version de PHP: 5.0.3

-- 

-- Base de données: `be`

-- 



-- --------------------------------------------------------



-- 

-- Structure de la table `administrateur`

-- 



CREATE TABLE `administrateur` (
  `login` varchar(15) NOT NULL default '',
  `passwd` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `administrateur`

-- 



INSERT INTO `administrateur` (`login`, `passwd`) VALUES ('admin', 'admin');



-- --------------------------------------------------------



-- 

-- Structure de la table `secretaire`

-- 



CREATE TABLE `secretaire` (
  `id-secretaire` int(8) NOT NULL auto_increment,
  `nom` varchar(30) NOT NULL default '',
  `prenom` varchar(30) NOT NULL default '',
  `mail` varchar(100) default NULL,
  `login` varchar(20) NOT NULL default '',
  `mdp` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id-secretaire`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `secretaire`

-- 



INSERT INTO `secretaire` (`id-secretaire`, `nom`, `prenom`, `mail`, `login`, `mdp`) VALUES ('1', 'AIROLA', 'Christine', 'airola@cict.fr', 'secretaire', 'secretaire');




-- --------------------------------------------------------



-- 

-- Structure de la table `controle`

-- 



CREATE TABLE `controle` (
  `type` char(20) NOT NULL default '',
  PRIMARY KEY  (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `controle`

-- 



INSERT INTO `controle` (`type`) VALUES ('C. Continu');

INSERT INTO `controle` (`type`) VALUES ('C. Terminal'); 

INSERT INTO `controle` (`type`) VALUES ('C. Partiel');



-- --------------------------------------------------------



-- 

-- Structure de la table `diplome`

-- 



CREATE TABLE `diplome` (
  `id-diplome` int(2) NOT NULL auto_increment,
  `intitule` varchar(100) NOT NULL default '',
  `code` char(1) NOT NULL default '',
  PRIMARY KEY  (`id-diplome`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `diplome`

-- 



INSERT INTO `diplome` (`id-diplome`, `intitule`, `code`) VALUES (1, 'Licence 2', 'L');

INSERT INTO `diplome` (`id-diplome`, `intitule`, `code`) VALUES (2, 'Licence 3', 'L');

INSERT INTO `diplome` (`id-diplome`, `intitule`, `code`) VALUES (3, 'Master 1', 'M');

INSERT INTO `diplome` (`id-diplome`, `intitule`, `code`) VALUES (4, 'Master 2', 'S');



-- --------------------------------------------------------



-- 

-- Structure de la table `enseignant`

-- 



CREATE TABLE `enseignant` (
  `id-enseignant` int(8) NOT NULL auto_increment,
  `nom` varchar(30) NOT NULL default '',
  `prenom` varchar(30) NOT NULL default '',
  `mail` varchar(100) default NULL,
  `login` varchar(20) NOT NULL default '',
  `mdp` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id-enseignant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `enseignant`

-- 



-- rien



-- --------------------------------------------------------



-- 

-- Structure de la table `enseignement`

-- 



CREATE TABLE `enseignement` (
  `id-matiere` int(4) NOT NULL default '0',
  `id-enseignant` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`id-matiere`,`id-enseignant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `enseignement`

-- 



-- --------------------------------------------------------



-- 

-- Structure de la table `evaluation`

-- 



CREATE TABLE `evaluation` (
  `id-evaluation` int(8) NOT NULL auto_increment, 
  `id-matiere` int(8) NOT NULL default '0', 
  `id-module` int(8) NOT NULL default '0',
  `type` varchar(20) NOT NULL default '', 
  `apogee` varchar(20) NOT NULL default '', 
  PRIMARY KEY  (`id-evaluation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `evaluation`

-- 





-- --------------------------------------------------------



-- 

-- Structure de la table `note`

-- 



CREATE TABLE `note` (
  `id-evaluation` int(8) NOT NULL default '0', 
  `id-etudiant` int(8) NOT NULL default '0', 
  `note` float (10) NOT NULL default '-1', 
  PRIMARY KEY  (`id-evaluation`, `id-etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `note`

-- 





-- --------------------------------------------------------



-- 

-- Structure de la table `etudiant`

-- 



CREATE TABLE `etudiant` (
  `id-etudiant` int(8) NOT NULL default '0',
  `nom` varchar(30) NOT NULL default '',
  `prenom` varchar(30) NOT NULL default '',
  `email` varchar(50) default NULL,
  `login` varchar(20) NOT NULL default '',
  `mdp` varchar(40) NOT NULL default '',
  `CV` varchar(80) default NULL,
  PRIMARY KEY  (`id-etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `etudiant`

-- 


-- --------------------------------------------------------



-- 

-- Structure de la table `inscrit`

-- 



CREATE TABLE `inscrit` (
  `id-etudiant` int(10) NOT NULL default '0',
  `id-diplome` int(10) NOT NULL default '0',
  `annee` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`id-etudiant`,`id-diplome`,`annee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `inscrit`

-- 



-- --------------------------------------------------------



-- 

-- Structure de la table `fichier`

-- 



CREATE TABLE `fichier` (
  `id-fichier` int(4) NOT NULL auto_increment,
  `titre` varchar(40) NOT NULL default '',
  `id-diplome` int(2) NOT NULL default '0',
  `id-prop` int(8) NOT NULL default '0', 
  `URL` varchar(100) NOT NULL default '',
  `commentaire` text,
  PRIMARY KEY  (`id-fichier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `fichier`

-- 



-- --------------------------------------------------------



-- 

-- Structure de la table `matiere`

-- 



CREATE TABLE `matiere` (
  `id-matiere` int(4) NOT NULL auto_increment,
  `id-module` int(3) NOT NULL default '0',
  `no_matiere` tinyint(2) NOT NULL default '0',
  `coefficient` float NOT NULL default '0',
  `intitule` varchar(200) NOT NULL default '', 
  `nbre-heures` int(3) NOT NULL default '0',
  `CT` tinyint(2) NOT NULL default '0',
  `CC` tinyint(2) NOT NULL default '0',
  `CP` tinyint(2) NOT NULL default '0', 
  `apogee` varchar(20) NOT NULL default '', 
  PRIMARY KEY  (`id-matiere`,`id-module`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `matiere`

-- 



-- --------------------------------------------------------



-- 

-- Structure de la table `menu`

-- 



CREATE TABLE `menu` (
  `ID-MENU` tinyint(10) unsigned NOT NULL auto_increment COMMENT 'Identifiant d&#146;une branche du menu',
  `ID_PMENU` tinyint(10) unsigned NOT NULL default '0' COMMENT 'Identifiant du noeud parent',
  `INTITULE` varchar(50) collate latin1_general_ci NOT NULL default '',
  `DESCRIPTION` varchar(150) collate latin1_general_ci NOT NULL default '',
  `PATH` varchar(150) collate latin1_general_ci NOT NULL default '',
  `ORDRE` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID-MENU`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



-- 

-- Contenu de la table `menu`

-- 



INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (1, 0, 'IUP ISI', '', '', 0);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (2, 1, 'Formation', '', '?p=2', 0);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (3, 1, 'Candidature', '', '?p=3', 1);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (4, 1, 'Partenariat', '', '?p=4', 3);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (5, 1, 'Fonctionnement', '', '?p=6', 4);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (6, 0, '', '', '', 2);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (7, 6, 'Association', '', 'http://atisi.free.fr/', 1);

INSERT INTO `menu` (`ID-MENU`, `ID_PMENU`, `INTITULE`, `DESCRIPTION`, `PATH`, `ORDRE`) VALUES (22, 1, 'Espace Reserve', '', 'espacereserve.php', 5);



-- --------------------------------------------------------



-- 

-- Structure de la table `module`

-- 



CREATE TABLE `module` (
  `id-module` int(3) NOT NULL auto_increment,
  `id-diplome` int(2) NOT NULL default '0',
  `intitule` varchar(200) NOT NULL default '',
  `no_module` tinyint(2) NOT NULL default '0',
  `description` text, 
  `CC` tinyint(2) NOT NULL default '0',
  `CP` tinyint(2) NOT NULL default '0',
  `CT` tinyint(2) NOT NULL default '0', 
  `no_semestre` tinyint(2) unsigned NOT NULL default '1' COMMENT 'Num&eacute;ro du semestre auquel le moduleest enseign&eacute;',
  `id-responsable` int(8) NOT NULL default '0' COMMENT 'Identifiant de l&#146;enseignant responsable du module', 
  `apogee` varchar(20) NOT NULL default '', 
  `id_node` int(10) NOT NULL default '0' COMMENT 'Identifiant du noeud de page associ&eacute;',
  PRIMARY KEY  (`id-module`,`id-diplome`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `module`

-- 



-- --------------------------------------------------------



-- 

-- Structure de la table `node`

-- 



CREATE TABLE `node` (
  `ID_NODE` tinyint(10) unsigned NOT NULL auto_increment COMMENT 'Identifiant d&#146;un noeud',
  `TITRE` varchar(50) collate latin1_general_ci NOT NULL default '',
  `DATE_CREATION` datetime NOT NULL default '0000-00-00 00:00:00',
  `DATE_MODIFICATION` datetime NOT NULL default '0000-00-00 00:00:00',
  `CONTENT` longtext collate latin1_general_ci NOT NULL,
  `NOTE` longtext collate latin1_general_ci NOT NULL,
  `NOTE_STYLE` varchar(25) collate latin1_general_ci NOT NULL default '',
  `MENU` longtext collate latin1_general_ci NOT NULL,
  `FILTER` varchar(10) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`ID_NODE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1;



-- 

-- Contenu de la table `node`

-- 



INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (1, 'Acceuil', '2005-11-12 00:00:00', '2005-11-27 20:47:54', '===== Acceuil IUP ISI =====\r\n\r\nCr&#233;&#233; en 1992, avec chaque ann&#233;e en moyenne 120 &#233;tudiants r&#233;partis sur les quatre ann&#233;es de la formation, l&#146;IUP ISI a pour objectif de former de futurs cadres aux divers m&#233;tiers impliqu&#233;s dans le d&#233;veloppement, le d&#233;ploiement et la maintenance de logiciels et de syst&#232;mes informatiques.\r\nLes enseignements de l&#146;IUP ISI sont d&#233;finis et planifi&#233;s pour r&#233;pondre aux besoins des entreprises ayant en charge la r&#233;alisation de produits souvent complexes, dans des domaines de haute technologie tels que le spatial, l&#146;a&#233;ronautique, les transports, le m&#233;dical, le pharmaceutique ... o&#249; les contraintes de qualit&#233;, de performances et de fiabilit&#233; (voire de certification) sont tr&#232;s &#233;lev&#233;es.', '===== Nouveaut&#233;s =====\r\n==== Forums ====\r\n12.10.2005\r\n\r\nPlusieurs forums sont ouverts et utilis&#233;s en particulier par le secr&#233;tariat et les enseignants pour communiquer avec les &#233;tudiants (propositions de stages, informations BE, informations diverses).\r\nSuivre le lien Association &#233;l&#232;ves ci-contre.', 'nouveaute', '[[Acceuil|?p=1]]\r\n[[Actualit&#233;s|?p=6]]', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (2, 'Formation', '2005-11-13 00:00:00', '2005-11-27 21:10:43', '===== La formation =====\r\n\r\nCr&#233;&#233;e en 1992, avec 120 &#233;tudiants en moyenne chaque ann&#233;e, r&#233;partis sur l&#146;ensemble des quatre ann&#233;es,\r\nFinalit&#233;\r\nL&#146;IUP Ing&#233;nierie des Syst&#232;mes Informatiques (ISI) a pour objectif de former de futurs cadres aux divers m&#233;tiers impliqu&#233;s dans le d&#233;veloppement, le d&#233;ploiement et la maintenance de logiciels et de syst&#232;mes informatiques.\r\n Les enseignements de l&#8217;IUP ISI sont d&#233;finis et planifi&#233;s pour r&#233;pondre aux besoins des entreprises ayant en charge la r&#233;alisation de produits souvent complexes, dans des domaines de haute technologie tels que le spatial, l&#8217;a&#233;ronautique, les transports, le m&#233;dical, le pharmaceutique ... o&#249; les contraintes de qualit&#233;, de performances et de fiabilit&#233; (voire de certification) sont tr&#232;s &#233;lev&#233;es.\r\nIls pr&#233;parent &#233;galement les &#233;tudiants aux mutations de leur futur m&#233;tier, inh&#233;rentes &#224; l&#146;&#233;volution des technologies et des formes de travail (de tels projets sont men&#233;s par des &#233;quipes pluridisciplinaires, issues de soci&#233;t&#233;s, de laboratoires et de pays diff&#233;rents, sur des dur&#233;es souvent tr&#232;s longues).\r\n\r\n==== M&#233;tiers et d&#233;bouch&#233;s professionnels ====\r\n\r\nAu terme de la formation, les &#233;tudiants peuvent &#234;tre directement int&#233;gr&#233;s &#224; des &#233;quipes travaillant principalement dans les domaines de la d&#233;finition des exigences, des choix de solution technique, de la sp&#233;cification, de la conception, de la r&#233;alisation, du test, de l&#8217;int&#233;gration, de la validation, du d&#233;ploiement et de la maintenance de logiciels et de syst&#232;mes, tant au niveau de la ma&#238;trise d&#8217;ouvrage qu&#8217;&#224; celui de la ma&#238;trise d&#8217;oeuvre.\r\nIl peuvent postuler &#224; des m&#233;tiers tels que : d&#233;veloppeur, testeur, analyste des besoins, architecte logiciel, architecte syst&#232;me, concepteur de base de donn&#233;es, sp&#233;cialiste IHM, ing&#233;nieur qualit&#233;, ing&#233;nieur m&#233;thodes, ing&#233;nieur processus &#8230; puis &#233;voluer vers l&#8217;encadrement d&#8217;&#233;quipes de d&#233;veloppement et la \r\nresponsabilit&#233; de projet.\r\n\r\nPar la place privil&#233;gi&#233;e qu&#8217;elle occupe dans le secteur des hautes technologies, avec nombre d&#8217;entreprises de dimension nationale ou internationale impliqu&#233;es dans les grands programmes europ&#233;ens (Airbus, Ariane &#8230; ), la r&#233;gion Midi-Pyr&#233;n&#233;es - le site toulousain en particulier - offre de nombreux d&#233;bouch&#233;s aux &#233;tudiants issus de notre formation.\r\n\r\nDepuis maintenant plusieurs ann&#233;es, 95% des &#233;tudiants trouvent un emploi dans le domaine directement &#224; la sortie du M2 (ex DESS) et 5% continuent leurs &#233;tudes.\r\nPr&#233;sentation synth&#233;tique des enseignements\r\n\r\nLa formation est construite sur une alternance de p&#233;riodes d&#8217;enseignement et de p&#233;riodes de stage : un stage chaque ann&#233;e, pour une dur&#233;e totale de 16 mois minimum en 4 ans.\r\nLes enseignements s&#8217;articulent autour de trois axes :\r\n  - une formation scientifique de base en math&#233;matiques et informatique, accompagn&#233;e d&#8217;une m&#233;thodologie de travail &#233;prouv&#233;e, assurant les connaissances th&#233;oriques fondamentales qui sont le gage de la capacit&#233; d&#8217;adaptation ;\r\n  - une formation technique et technologique approfondie et pratique, assurant la ma&#238;trise du savoir-faire li&#233; &#224; l&#8217;activit&#233; professionnelle  en privil&#233;giant les approches m&#233;thodologiques (processus de d&#233;veloppement, mod&#233;lisation visuelle, mais aussi m&#233;thodes formelles, techniques de validation &#8230; ) et les standards de d&#233;veloppement utilis&#233;s en milieu professionnel ;\r\n  - une formation g&#233;n&#233;rale donnant une bonne connaissance du fonctionnement des entreprises (gestion, conduite de projets) et une parfaite ma&#238;trise des langues (anglais obligatoire), assurant une bonne insertion dans la vie de l&#8217;entreprise.\r\nDes bureaux d&#8217;&#233;tudes, r&#233;alis&#233;s en groupes de 4 &#224; 10 &#233;tudiants, permettent aux &#233;tudiants de mettre en application ces enseignements sur des cas r&#233;els et les pr&#233;parent aux stages et &#224; l&#146;insertion professionnelle.', '', 'nouveaute', '[[Acceuil|?p=1]]\r\n[[Actualit&#233;|?p=5]]', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (3, 'Candidature', '2005-11-13 00:00:00', '2005-11-27 20:55:52', '===== Candidature =====\r\n\r\n==== Conditions d&#146;admission en Licence ====\r\n\r\n  * Peuvent faire acte de candidature : \r\n    * en L2 : les &#233;tudiants ayant valid&#233; une 1&#232;re ann&#233;e d&#146;enseignement sup&#233;rieur (L1) ou CPGE. \r\n    * en L3 : les &#233;tudiants titulaires d&#146;une 2&#232;me ann&#233;e de licence (L2) ou CPGE, d&#146;un DUT ou BTS avec avis favorable de poursuite d&#146;&#233;tudes, ou d&#146;un dipl&#244;me &#233;quivalent.\r\n\r\n  * Pr&#233; inscription et t&#233;l&#233;chargement des dossiers de candidature sur le site d&#146;inscription aux IUP, de d&#233;but mars &#224; mi-avril.\r\n  * Admission prononc&#233;e par un jury compos&#233; d&#146;universitaires et de professionnels du secteur socio-&#233;conomique, apr&#232;s examen du dossier (en juin), suivi d&#146;un entretien si le dossier est retenu (fin juin-d&#233;but juillet).\r\n\r\n  * Acc&#232;s en L2 et L3 par validation d&#146;acquis universitaires, pour les etudiants non titulaires des dipl&#244;mes ci-dessus.\r\n  * Dipl&#244;mes accessibles par les voies de formation continue et VAE.\r\n\r\n==== Conditions d&#146;acc&#232;s en Master ====\r\n\r\n  * Acc&#232;s en M1 pour les titulaires du parcours Ing&#233;nierie des Syst&#232;mes Informatiques de la Licence MIA (Math&#233;matiques Informatique et Applications) de l&#146;Universit&#233; Paul Sabatier.\r\n\r\n  * Acc&#232;s en M2 pour les titulaires du M1 Ing&#233;nierie des Syst&#232;mes Informatiques de l&#146;Universit&#233; Paul Sabatier.\r\n  * Pr&#233; inscription et t&#233;l&#233;chargement des dossiers de candidature en M2 sur le site de l&#146;UPS, de mi-avril &#224; mi-juin.\r\n\r\n  * Acc&#232;s en M1 et M2 par validation d&#146;acquis universitaires, constituant les pr&#233; requis th&#233;matiques et de formation professionnelle conformes &#224; l&#146;habilitation des dipl&#244;mes vis&#233;s, pour les &#233;tudiants non titulaires des dipl&#244;mes ci-dessus.\r\n  * Dipl&#244;mes accessibles par les voies de formation continue et VAE.', '', '', '[[Acceuil|?p=1]]\r\n[[Actualit&#233;|?p=5]]', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (4, 'Partenariat', '2005-11-13 00:00:00', '2005-11-27 20:53:56', '===== Partenariat =====\r\n==== Partenariat industriel ====\r\nLe partenariat avec le milieu industriel intervient &#224; diff&#233;rents niveaux :\r\n\r\n  * Conseil de Perfectionnement de l&#146;IUP : compos&#233; &#224; parit&#233; d&#146;enseignants et de professionnels, il d&#233;finit la politique de l&#146;IUP en mati&#232;re de programmes, p&#233;dagogie, organisation et moyens pour atteindre les objectifs fix&#233;s ;\r\n  * jurys : des professionnels participent aux jurys de recrutement des &#233;tudiants et aux jurys de dipl&#244;mes ;\r\n  * &#233;quipes p&#233;dagogiques : l&#146;enseignement et l&#146;encadrement de projets sont en partie assur&#233;s par des professionnels (vacataires et PAST) ;\r\n  * stages en entreprise, &#224; la fin de chaque ann&#233;e : l&#146;&#233;tudiant est co-encadr&#233; par un tuteur en entreprise et un enseignant universitaire ;\r\n  * autres formes : logiciels et mat&#233;riels gratuits ou &#224; prix r&#233;duits, taxe d&#146;apprentissage ? \r\n\r\nDe nombreuses entreprises entrent dans le cadre de ce partenariat :\r\n  * grandes entreprises, priv&#233;es ou publiques\r\nAlcatel Space Industrie, CNES, EADS-Airbus, EADS-Astrium, EDF, France Telecom, Freescale, IBM, Siemens, SNCF, Thales ...\r\n  * soci&#233;t&#233;s de services\r\nAstek Sud-Ouest, C-S, Cap-Gemini, Esterel Technologies, Realix Technologies, Silogic, Steria, Telelogic, Transiciel Technologies, Versant ...\r\n\r\n==== Partenariat international ====\r\n\r\nDes &#233;changes d&#146;&#233;tudiants ont lieu entre universit&#233;s : Universit&#233; Polytechnique de Catalogne,  Barcelone (Espagne) ; Universit&#233; de Dundee (Irlande) ; Universit&#233; de Liverpool (Angleterre).\r\nDes entreprises accueillent des stagiaires : GTD, Barcelone (Espagne) ;  Lynx Graphics Ltd, Winnipeg (Canada) et  RVSI, Nashua (Etats-Unis).\r\n\r\n==== Adossement &#224; la recherche ====\r\n\r\nLes enseignants-chercheurs membres des &#233;quipes p&#233;dagogiques de l&#146;IUP ISI effectuent leur recherche sur des th&#232;mes en relation avec leur enseignement :\r\n  * &#224; l&#146;IRIT (Institut de Recherche en Informatique de Toulouse),\r\n  * au LAAS (Laboratoire d&#146;Automatique et d&#146;Analyse des Syst&#232;mes, Toulouse),\r\n  * au CERT (Centre d&#146;Etudes et de Recherches de Toulouse).\r\nD&#146;autres laboratoires de recherche apportent leur soutien par l&#146;intervention de leurs membres en tant que vacataires ou en accueillant des stagiaires : Cemes, CNRS, INRA, Observatoire de Midi-Pyr&#233;n&#233;es, INSERM ...', '', '', '[[Acceuil|?p=1]]\r\n[[Actualit&#233;|?p=5]]', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (5, 'Actualit&#233;s', '2005-11-27 21:04:50', '2005-11-27 21:04:50', '===== Actualit&#233;s =====\r\n\r\n{{isi>actu}}', '', '', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (6, 'Fonctionnement', '2005-11-13 00:00:00', '2005-11-27 20:50:43', '===== Fonctionnement =====\r\n==== Pr&#233;sentation g&#233;n&#233;rale ====\r\nL&#146;Universit&#233; Paul Sabatier a choisi d&#146;int&#233;grer, d&#232;s septembre 2004, l&#146;ensemble de ses formations dans le sch&#233;ma europ&#233;en LMD.\r\nLes formations IUP y sont identifi&#233;es, tant au niveau Licence (L) qu&#146;au niveau Master (M) par un seul et m&#234;me intitul&#233; de mention, &#171; Ing&#233;nierie &#187;, qui se retrouve dans diff&#233;rents domaines th&#233;matiques de Licence et de Master.\r\nLes objectifs de formation &#224; des m&#233;tiers dans le cadre d&#146;un partenariat &#233;troit avec le secteur socio-&#233;conomique, ainsi que la p&#233;dagogie adapt&#233;e &#224; une professionnalisation d&#233;velopp&#233;e tout au long des cursus Licence et Master, demeurent le fondement de ces formations.\r\nLe parcours type IUP est sanctionn&#233; au niveau Bac + 5 par le dipl&#244;me de Master Professionnel obtenu au sein de l&#146;IUP et dont la sp&#233;cialit&#233; est l&#146;intitul&#233; de l&#146;IUP.\r\n\r\n==== Organisation de la formation ====\r\n\r\nL&#146;IUP ISI propose une formation en 4 ans, menant au Master Professionnel, sp&#233;cialit&#233; ISI :\r\n  * Licence 2 IUP ISI (ex IUP 1&#232;re ann&#233;e)\r\n  * Licence 3 IUP ISI (ex IUP 2&#232;me ann&#233;e)\r\n  * Master 1 IUP ISI (ex IUP 3&#232;me ann&#233;e)\r\n  * Master 2 IUP ISI (ex DESS ISI)\r\nChaque ann&#233;e est dipl&#244;mante.\r\n\r\nLa formation est construite sur une alternance de p&#233;riodes d&#146;enseignement et de p&#233;riodes de stage : un stage chaque ann&#233;e, pour une dur&#233;e totale de 16 mois minimum en 4 ans.\r\nDes bureaux d&#146;&#233;tudes, r&#233;alis&#233;s en groupes de 4 &#224; 10 &#233;tudiants, permettent aux &#233;tudiants de mettre en application les enseignements de l&#146;ann&#233;e sur des cas r&#233;els et les pr&#233;parent aux stages et &#224; l&#146;insertion professionnelle.\r\n\r\n==== Conseil de Perfectionnement ====\r\n\r\nLa formation est pilot&#233;e par un conseil de perfectionnement, compos&#233; &#224; parit&#233; d&#146;enseignants et de professionnels, qui d&#233;finit la politique de l&#146;IUP en mati&#232;re de programmes, p&#233;dagogie, organisation et moyens pour atteindre les objectifs fix&#233;s.\r\n', '', '', '[[Acceuil|?p=1]]\r\n[[Actualit&#233;s|?p=5]]', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (15, 'L3', '2005-12-04 22:02:09', '2005-12-04 22:12:39', '===== Licence 3 : M&eacute;thodes, techniques et outils des logiciels =====\r\n\r\nLes enseignements de Licence 3 sont principalement ax&eacute;s sur l&#146;apprentissage des techniques fondamentales de l&#146;informatique et des m&eacute;thodes et outils de d&eacute;veloppement de logiciels couramment utilis&eacute;s en milieu industriel. Les &eacute;tudiants apprennent à d&eacute;finir des modèles d&#146;analyse et de conception (UML), d&eacute;velopper à partir de ces modèles des applications Java, C++ ou bases de donn&eacute;es, en respectant des standards de d&eacute;veloppement, r&eacute;aliser les tests et faire la maintenance.', '{{isi>uenav?2}}', 'nouveaute', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (16, 'L3 - Architecture des Syst&#232;mes Informatiques', '2005-12-04 22:16:42', '2005-12-09 10:59:08', '===== Licence 3 : M&#233;thodes, techniques et outils des logiciels =====\r\n\r\n{{isi>apog&eacute3}}\r\n\r\n**Objectif**\r\n\r\n**nouveau** //Italique//\r\n\r\nCette UE a pour objectif de faire acqu&#233;rir &#224; l&#8217;&#233;tudiant une bonne connaissance du fonctionnement d&#8217;un calculateur (de type calculateur personnel ou station de travail) et de sa programmation en assembleur.\r\n\r\n**Pr&#233;requis**\r\n\r\nAucun pr&#233;requis\r\n\r\n**Volume horaire**\r\n\r\n{{isi>ue?3}}\r\n\r\n**Contenu**\r\n\r\n  * Programmation assembleur : Jeu d&#8217;instruction, modes d&#8217;adressage, m&#233;thodes et outils pour le d&#233;veloppement de programmes assembleur. Manipulation de la pile, r&#233;servation de l&#8217;espace de travail. R&#233;entrance, r&#233;cursivit&#233;.\r\n  * Syst&#232;mes d&#8217;interruptions : Interruptions hi&#233;rarchis&#233;es en niveaux de priorit&#233;. Pilote d&#8217;interruption. Contr&#244;leur d&#8217;interruption.\r\n  * Techniques d&#146;Entr&#233;es / Sorties : E/S par tests d&#8217;&#233;tats, E/S par interruption, E/S en acc&#232;s direct &#224; la m&#233;moire. Etude de circuits sp&#233;cialis&#233;s et de leurs divers modes de fonctionnement : ports parall&#232;les, ports s&#233;rie, timer.\r\n  * Organisation m&#233;moire : Partition de l&#8217;espace d&#8217;adressage. Techniques de d&#233;codage de l&#8216;adresse m&#233;moire. M&#233;moires caches : principe de fonctionnement, hi&#233;rarchie de caches.\r\n  * Les m&#233;moires virtuelles : dispositifs mat&#233;riels pour la segmentation et la pagination de la m&#233;moire.\r\n  * Etude des p&#233;riph&#233;riques usuels et de leur mode de couplage : disques, &#233;crans, claviers et souris.\r\n  * R&#233;seaux locaux : Ethernet. Etude d&#233;taill&#233;e des couches ISO 1 &#224; 3.\r\nTravaux pratiques sur syst&#232;mes de d&#233;veloppement famille de processeurs ARM.\r\n\r\n**Niveau attendu**\r\n\r\nEn ayant suivi cette UE, les &#233;tudiants doivent &#234;tre capables de ...\r\n\r\n**Bibliographie**\r\n\r\n  * Architecture des calculateurs : une approche quantitative, Hennesy et Patterson(Thomson publishing, 1996)\r\n  * Introduction to switching theory and logical design, Hill, Peterson (Mac Graw Hill 92)\r\n\r\n**Enseignants**\r\n\r\n{{isi>ens?3}}', '{{isi>uenav?2}}', 'nouveaute', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (17, 'L3 - CRCL1', '2005-12-04 22:48:08', '2005-12-09 00:17:46', '===== Licence 3 : M&#233;thodes, techniques et outils des logiciels =====\r\n\r\n{{isi>apog?2}}\r\n\r\n**Objectif**\r\n\r\nA revoir avec CRCL2.\r\nL&#146;UE a pour objectif la conception et la r&#233;alisation d&#146;unit&#233;s de programmation r&#233;utilisables, &#224; partir du concept de Type Abstrait de Donn&#233;es. La r&#233;utilisation de logiciels au travers des concepts d&#146;h&#233;ritage sont mis en oeuvre dans les langages Java et C++.\r\n\r\n**Pr&#233;requis**\r\n\r\nConnaissances de base en Java.\r\n\r\n**Volume horaire**\r\n\r\n{{isi>ue?2}}\r\n\r\n**Contenu**\r\n\r\n**Programmation objet et composants objets**\r\n  * type abstrait de donn&#233;es et composants objets\r\n  * r&#233;utilisation de composants logiciels, h&#233;ritage\r\n  * liaison statique, liaison dynamique\r\n\r\n**R&#233;utilisation en Java**\r\n  * l&#8217;h&#233;ritage Java\r\n  * conception et mise en &#339;uvre de composants logiciels en Java\r\n  * exemples d&#8217;applications\r\n\r\n**Niveau attendu**\r\n\r\nA l&#8217;issue de ce cours, les &#233;tudiants doivent &#234;tre capables de :\r\n  * construire des programmes Java et C++, avec toute la rigueur exig&#233;e pour faciliter leur mise au point et leur maintenance ;\r\n  * donner diff&#233;rentes versions d&#8217;un m&#234;me algorithme en Java et en C++, en choisissant diff&#233;rentes structures de donn&#233;es et en tenant compte des particularit&#233;s et des sp&#233;cificit&#233;s de chacun de ces deux langages ;\r\n  * &#233;valuer et comparer les performances de ces diff&#233;rentes versions ;\r\n  * effectuer les tests n&#233;cessaires pour valider leurs logiciels.\r\n\r\n**Bibliographie**\r\n\r\n  * Introduction &#224; la programmation objet en Java, J. Brondeau (Dunod)\r\n\r\n**Enseignants**\r\n\r\n{{isi>ens?2}}', '{{isi>uenav?2}}', 'nouveaute', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (18, 'L3 - LPL', '2005-12-04 22:56:21', '2005-12-08 23:31:31', '===== Licence 3 : M&#233;thodes, techniques et outils des logiciels =====\r\n\r\n{{isi>apog?1}}\r\n\r\n**Objectif**\r\n\r\nCette UE introduit aux concepts fondamentaux de la logique. L&#146;objectif consiste &#224; donner les connaissances de base pour aborder la sp&#233;cification et la validation de logiciels. Un style et un langage de programmation issus des formalismes et des m&#233;canismes de la logique sont &#233;tudi&#233;s.\r\n\r\n**Pr&#233;requis**\r\n\r\nAucun pr&#233;requis\r\n\r\n**Volume horaire**\r\n\r\n{{isi>ue?2}}\r\n\r\n**Contenu**\r\n\r\n  * logique des propositions, logique des pr&#233;dicats\r\n  * formule, interpr&#233;tation, mod&#232;le, consistance et validit&#233;\r\n  * formes standard de Skolem, clauses\r\n  * algorithme d&#8217;unification, r&#233;solution et strat&#233;gies d&#8217;impl&#233;mentation\r\n  * programmation logique avec Prolog.\r\n\r\n**Niveau attendu**\r\n\r\nEn ayant suivi cette UE, les &#233;tudiants doivent ma&#238;triser les formalismes logiques pour l&#146;expression de propri&#233;t&#233;s et leur d&#233;monstration.\r\n\r\n**Bibliographie**\r\n\r\n  * Approche logique de l&#8217;intelligence artificielle 1 : de la logique classique &#224; la programmation logique, IA. Thaysse (Dunod Inforrmatique)\r\n  * Cours de Prolog avec Turbo Prolog, J.P. Delahaye (&#201;ditions Eyrolles, 1988)\r\n\r\n**Enseignants**\r\n{{isi>ens?1}}', '{{isi>uenav?2}}', 'nouveaute', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (19, 'nouveau titre', '2005-12-09 10:49:51', '2005-12-09 10:49:51', 'bonjour **bonjour**', 'nouvelle //page//', '', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (20, 'essai', '2005-12-09 10:53:38', '2005-12-09 10:53:38', '**essai**', '', '', '', 'wiki');

INSERT INTO `node` (`ID_NODE`, `TITRE`, `DATE_CREATION`, `DATE_MODIFICATION`, `CONTENT`, `NOTE`, `NOTE_STYLE`, `MENU`, `FILTER`) VALUES (21, 'essai', '2005-12-09 10:54:38', '2005-12-09 10:54:38', '**essa**', '', '', '', 'wiki');



-- --------------------------------------------------------



-- 

-- Structure de la table `page`

-- 



CREATE TABLE `page` (
  `id-page` int(3) NOT NULL auto_increment,
  `id-menu` int(11) NOT NULL default '0',
  `position` int(11) NOT NULL default '0',
  `titre` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id-page`),
  FULLTEXT KEY `titre` (`titre`),
  FULLTEXT KEY `titre_2` (`titre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `page`

-- 



INSERT INTO `page` (`id-page`, `id-menu`, `position`, `titre`) VALUES (1, 4, 1, 'La formation IUP ISI');

INSERT INTO `page` (`id-page`, `id-menu`, `position`, `titre`) VALUES (2, 4, 2, 'Temporaire');

INSERT INTO `page` (`id-page`, `id-menu`, `position`, `titre`) VALUES (5, 4, 3, 'glkjuhhl');

INSERT INTO `page` (`id-page`, `id-menu`, `position`, `titre`) VALUES (6, 4, 4, 'wazaaaaaaaaa');



-- --------------------------------------------------------



-- 

-- Structure de la table `section`

-- 



CREATE TABLE `section` (
  `id-section` int(7) NOT NULL auto_increment,
  `id-page` int(3) NOT NULL default '0',
  `contenu` text,
  `ordre` int(3) default '1',
  `titre` varchar(100) default NULL,
  PRIMARY KEY  (`id-section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- 

-- Contenu de la table `section`

-- 



INSERT INTO `section` (`id-section`, `id-page`, `contenu`, `ordre`, `titre`) VALUES (1, 1, 'L&#146;<b>I</b>nstitut  <b>U</b>niversitaire <b>P</b>rofessionnalis? en <b> I</b>ng?nierie des <b>S</b>yst?mes <b>I</b>nformatiques  a pour objectif de former des ?tudiants de niveau <b>Bac + 4</b>  et <b>Bac + 6</b> dans le domaine des concepts, m?thodes, techniques et outils des logiciels.\r\n\r\nLes enseignements de l&#146;IUP ISI sont d?finis et planifi?s de mani?re ? r?pondre aux besoins des entreprises ayant une tr?s forte activit? dans le domaine de la conception et de la r?alisation de logiciels tr?s complexes n?cessitant, sur une dur?e qui peut ?tre tr?s longue, le concours d&#146;equipes d&#146;ing?nieurs <b>pluridisciplinaires</b> venant des societ?s assez diff?rentes. Etant destin?s ? des applications industrielles de haute technologie (a?ronautiques, spatiales, m?dicales, ou autres), ces logiciels doivent  r?pondre ? des exigences et des normes de qualit? tr?s strictes garantissant pleinement ce pour quoi ils ont ?t? developp?s. Ils doivent !\r\n ?tre con?us de mani?re ? faciliter leur maintenance et ?tre capables d&#146;int?grer de nouvelles fonctionnalit?s. Ils doivent, aussi, ?tre capables de suivre les ?volutions, toujours plus rapides, des nouvelles technologies (mat?rielles et logicielles) sur lesquelles ils doivent ?tre implant?s.\r\n\r\nToutes les ?tapes de la vie d&#146;un logiciel sont enseign?es aux ?tudiants qui sauront concevoir et r?aliser diff?rents types de d&#146;applications, temps r?els en particulier, sous environnement <b>UNIX</b>  et <b>X-WINDOW</b> au travers d&#146;approches m?thodologiques de l&#146;ing?nierie des syst?mes informatiques issues du monde industriel et en respectant les standards de d?veloppement  utilis?s en milieu professionnel.\r\nLes connaissances de base n?cessaires ? tout ing?nieur seront donn?es aux ?tudiants de mani?re ? leur permettre de suivre l&#146;?volution des techniques informatiques. Des enseignements intensifs d&#146;anglais g?n?ral et scientifique et de gestion d&#146;entreprises informatiqu!\r\n es compl?tent cette formation.\r\n\r\nAu terme de la formation l&#146;etud\r\niant devient sp?cialiste dans les domaines suivants :\r\n<ul>\r\n<li> Conception et d?veloppement de logiciels\r\n<li> Sp?cification et validation de logiciels\r\n<li> Tests et maintenance de logiciels\r\n<li> Qualit? des syst?mes\r\n<li> Interface homme-machine\r\n<li> Ing?nierie du logiciel\r\n<li> D?veloppement d&#146;applications bases de donn?es\r\n<li> D?veloppement d&#146;applications temps r?el\r\n<li> Applications r?parties (internet, intranet)\r\n<li> Architecture de machines, calculateurs d?di?s\r\n</ul><i>bonjour</i>', 2, 'Finalit? de la formation');

INSERT INTO `section` (`id-section`, `id-page`, `contenu`, `ordre`, `titre`) VALUES (2, 1, 'Le partenariat avec le milieu industriel intervient au niveau :\r\n<ul>\r\n<li> du conseil de Perfectionnement, compos? ? parit? ?gale d&#146;enseignants de l&#146;Universit? et de Professionnels, o&ugrave; se discutent les orientations ? prendre en mati?re de contenu des enseignements,\r\n<li> de l&#146;implication dans les enseignements de professionnels qui apportent leurs exp?riences professionnelles sur les concepts, les m?thodes et les outils ?tudi?s en cours, ou qui compl?tent les enseignements dans des domaines plus sp?cifiques,\r\n<li> des stages en entreprise, que doivent r?aliser les ?tudiants ? la fin de chaque ann?e, et\r\n<li> de l&#146;association IUP ISI regroupant toutes les personnes impliqu?es ? diff?rents titres dans les activit?s de l&#146;IUP ISI (?tudiants, universitaires, anciens ?tudiants et professionnels).\r\n</ul>\r\n\r\nQuelques entreprises partenaires de l&#146;IUP ISI : A?rospatiale, Alcatel, Astrium, Siemens, Thomson, Aonix, Cap G!\r\n ?mini, CS-SI, Comtech, CR2A-DI, DIAF, Elan Informatique...', 3, 'Partenariat industriel');

INSERT INTO `section` (`id-section`, `id-page`, `contenu`, `ordre`, `titre`) VALUES (3, 5, 'Calcul', 1, 'fguljhkhkm');

INSERT INTO `section` (`id-section`, `id-page`, `contenu`, `ordre`, `titre`) VALUES (4, 1, 'L&#146;IUP ISI a pour objectif .....\r\n\r\n\r\nsur des dur?es souvent tr?s longues.', 2, 'Finalit? de la formation');

INSERT INTO `section` (`id-section`, `id-page`, `contenu`, `ordre`, `titre`) VALUES (5, 2, 'dgsdgsdrgfd', 2, 'l&#146;info');

INSERT INTO `section` (`id-section`, `id-page`, `contenu`, `ordre`, `titre`) VALUES (6, 6, 'salut ca fatre moi ca farte bien ntu a l&#146;air super glissade de loin mdrrrrrrrrrrrr', 1, 'kjhfedhjkdshgjfgbdskjgfsdgfkjsdhgfsd');

-- --------------------------------------------------------


