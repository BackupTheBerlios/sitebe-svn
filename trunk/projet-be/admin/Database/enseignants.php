<?php
/*
** Fichier : enseignant
** Date de creation : 20/08/2004
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier inclu charge de la gestion des enseignants cote mise a jour
**	ajout, suppression, modification
*/




/*
** IMPORTANT :	La verification des donnees fournies se fait cote serveur il
**		est donc important de controler toutes les donnees du formulaire
*/


// Fonction permettant de supprimer l'intégralité d'un fichier
function sup_repertoire($chemin)
{
	// vérifie si le nom du repertoire contient "/" à la fin
	if ($chemin[strlen($chemin)-1] != '/') // place le pointeur en fin d'url
	{
		$chemin .= '/'; // rajoute '/'
	}
	
	if (is_dir($chemin))
	{
		$sq = opendir($chemin); // lecture
		while ($f = readdir($sq))
		{
			if ($f != '.' && $f != '..')
			{
				$fichier = $chemin.$f; // chemin fichier
				if (is_dir($fichier))
				{
					sup_repertoire($fichier);  // rapel la fonction de manière récursive
				}
				else
				{
					unlink($fichier); // sup le fichier
				}
			}
		}
		closedir($sq);
		rmdir($chemin); // sup le répertoire
	}
	else
	{
		unlink($chemin);  // sup le fichier
	}
}



// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	//partie Administration

	// premier cas : ajout
	if (isset($_POST['enseignantAdd']))
	{		
		// donnees correctes : traitement et ajout

		dbConnect() ;
			
		$nom = trim($_POST['nomEnseignant']) ;
		$nom = addslashes($nom) ;
		$prenom = trim($_POST['prenomEnseignant']) ;
		$prenom = addslashes($prenom) ;
		$mail = trim($_POST['mailEnseignant']) ;
		$mail = addslashes($mail) ;
		$login = trim($_POST['loginEnseignant']) ;
		$login = addslashes($login) ;
		$mdp = md5($_POST['mdpEnseignant']);
		$mdp = addslashes($mdp) ;
		$idUe = $_POST['ue'];
		
		// on verifie si le login est unique
		$logEtu = dbQuery('SELECT COUNT(*) AS counter
			FROM etudiant
			WHERE login = "'.$login.'"') ;
		$logEns = dbQuery('SELECT COUNT(*) AS counter
			FROM enseignant
			WHERE login = "'.$login.'"') ;
			
		$logEtu = mysql_fetch_array($logEtu) ;
		$logEns = mysql_fetch_array($logEns) ;	
		
		// login existant
		if ($logEtu['counter'] + $logEns['counter'] != 0)
		{
			centeredErrorMessage(3, 3, "Ce login semble d&eacute;j&egrave; exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignant&a=add\">\n") ;				
			return ;
		}
		
		// on insere le nouveau enseignant
		dbQuery('INSERT INTO enseignant
			VALUES (NULL, "'.$nom.'", "'.$prenom.'", "'.$mail.'", "'.$login.'", "'.$mdp.'")') ;
		
		// Dans le cas ou on choisi que l'enseignant sera responsable d'une UE on doit ajouter un tuple dans la base
		// dans la table resp-module, et creer le dossier correspondant (si le login est defini)
		if(isset($_POST['respUe']))
		{
			if($_POST['respUe'] == 'oui')
			{
				$idProf = mysql_insert_id();
				$req = "UPDATE module
						SET `id-responsable` = ".$idProf."
						WHERE `id-module` = ".$idUe ;
				dbQuery($req);
				
				// cree le dossier d'espace reserve si le login est defini
				if($login != "")
				{
					$moduleInfo = mysql_fetch_array(dbQuery('SELECT apogee
						FROM module
						WHERE `id-module` = '.$idUe)) ;
					mkdir("../Data/".$moduleInfo['apogee']."/".$login, 0755) ;
				}
			}
		}else{
			print("Erreur le champ demandé n'existe pas !");
		}
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Enseignant ajout&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignants\">\n") ;				
		dbClose() ;
	} // end of enseignantAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['enseignantMod']))
	{
		dbConnect() ;
			
		$nom = trim($_POST['nomEnseignant']) ;
		$nom = addslashes($nom) ;
		$prenom = trim($_POST['prenomEnseignant']) ;
		$prenom = addslashes($prenom) ;
		$mail = trim($_POST['mailEnseignant']) ;
		$mail = addslashes($mail) ; 
		$login = trim($_POST['loginEnseignant']) ;
		$login = addslashes($login) ;
		$mdp = md5($_POST['mdpEnseignant']);
		$mdp = addslashes($mdp) ;
		
		// on verifie si le login est unique
		$logEtu = dbQuery('SELECT COUNT(*) AS counter
			FROM etudiant
			WHERE login = "'.$login.'"') ;
		$logEns = dbQuery('SELECT COUNT(*) AS counter
			FROM enseignant
			WHERE login = "'.$login.'"
			AND `id-enseignant` <> '.$_POST['enseignantID']) ;
			
		$logEtu = mysql_fetch_array($logEtu) ;
		$logEns = mysql_fetch_array($logEns) ;	
		
		// login existant
		if ($logEtu['counter'] + $logEns['counter'] != 0)
		{
			centeredErrorMessage(3, 3, "Ce login semble d&eacute;j&egrave; exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignant&a=mod\">\n") ;				
			return ;
		}
		
		$EnsInfo = mysql_fetch_array(dbQuery('SELECT login
						FROM enseignant
						WHERE `id-enseignant` = '.$_POST['enseignantID'])) ;
		
		// on met a jour l'enseignant
		dbQuery('UPDATE enseignant
			SET nom = "'.$nom.'", prenom = "'.$prenom.'", mail = "'.$mail.'", login = "'.$login.'", mdp = "'.$mdp.'"
			WHERE `id-enseignant` = '.$_POST['enseignantID']) ;
		
		if($login == "" && $EnsInfo['login'] != "")
		{
			// Si on supprime le login, alors on supprime tous les dossiers
			// on supprime le dossier de responsable
			$moduleInfo = dbQuery('SELECT apogee
						FROM module
						WHERE `id-responsable` = '.$_POST['enseignantID']) ;
			$moduleCount = mysql_num_rows($moduleInfo) ;
			if($moduleCount != 0)
			{
				$moduleDetail = mysql_fetch_array($moduleInfo) ;
				sup_repertoire("../Data/".$moduleDetail['apogee']."/".$EnsInfo['login']) ;
			}
			
			// on supprime les dossiers d'enseignement
			$moduleInfo = dbQuery('SELECT module.apogee As apogeeMod, matiere.apogee As apogeeMat
						FROM module, matiere, enseignement
						WHERE enseignement.`id-enseignant` = '.$_POST['enseignantID'].'
						AND enseignement.`id-matiere` = matiere.`id-matiere`
						AND matiere.`id-module` = module.`id-module`') ;
			$moduleCount = mysql_num_rows($moduleInfo) ;
			for($i = 0 ; $i < $moduleCount ; $i++)
			{
				$moduleDetail = mysql_fetch_array($moduleInfo) ;
				sup_repertoire("../Data/".$moduleDetail['apogeeMod']."/".$moduleDetail['apogeeMat']."/".$EnsInfo['login']) ;
			}
		}
		else
		{
			if($login != "" && $EnsInfo['login'] == "")
			{
				// Si on ajoute le login, alors on ajoute tous les dossiers
				// on ajoute le dossier de responsable
				$moduleInfo = dbQuery('SELECT apogee
							FROM module
							WHERE `id-responsable` = '.$_POST['enseignantID']) ;
				$moduleCount = mysql_num_rows($moduleInfo) ;
				if($moduleCount != 0)
				{
					$moduleDetail = mysql_fetch_array($moduleInfo) ;
					mkdir("../Data/".$moduleDetail['apogee']."/".$login, 0755) ;
				}
				
				// on ajoute les dossiers d'enseignement
				$moduleInfo = dbQuery('SELECT module.apogee As apogeeMod, matiere.apogee As apogeeMat
							FROM module, matiere, enseignement
							WHERE enseignement.`id-enseignant` = '.$_POST['enseignantID'].'
							AND enseignement.`id-matiere` = matiere.`id-matiere`
							AND matiere.`id-module` = module.`id-module`') ;
				$moduleCount = mysql_num_rows($moduleInfo) ;
				for($i = 0 ; $i < $moduleCount ; $i++)
				{
					$moduleDetail = mysql_fetch_array($moduleInfo) ;
					mkdir("../Data/".$moduleDetail['apogeeMod']."/".$moduleDetail['apogeeMat']."/".$login, 0755) ;
				}
			}
			else
			{
				if($login != "" && $EnsInfo['login'] != "")
				{
					// Si on modifie le login, alors on modifie tous les dossiers
					// on modifie le dossier de responsable
					$moduleInfo = dbQuery('SELECT apogee
								FROM module
								WHERE `id-responsable` = '.$_POST['enseignantID']) ;
					$moduleCount = mysql_num_rows($moduleInfo) ;
					if($moduleCount != 0)
					{
						$moduleDetail = mysql_fetch_array($moduleInfo) ;
						rename("../Data/".$moduleDetail['apogee']."/".$EnsInfo['login'], "../Data/".$moduleDetail['apogee']."/".$login) ;
					}
					
					// on modifie les dossiers d'enseignement
					$moduleInfo = dbQuery('SELECT module.apogee As apogeeMod, matiere.apogee As apogeeMat
								FROM module, matiere, enseignement
								WHERE enseignement.`id-enseignant` = '.$_POST['enseignantID'].'
								AND enseignement.`id-matiere` = matiere.`id-matiere`
								AND matiere.`id-module` = module.`id-module`') ;
					$moduleCount = mysql_num_rows($moduleInfo) ;
					for($i = 0 ; $i < $moduleCount ; $i++)
					{
						$moduleDetail = mysql_fetch_array($moduleInfo) ;
						rename("../Data/".$moduleDetail['apogeeMod']."/".$moduleDetail['apogeeMat']."/".$EnsInfo['login'], "../Data/".$moduleDetail['apogeeMod']."/".$moduleDetail['apogeeMat']."/".$login) ;
					}
				}
			}
		}
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Enseignant modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignants\">\n") ;		
			
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['enseignantDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun enseignant selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignants&a=del\">\n") ;
		}
		
		// tout est correct
		else
		{
			dbConnect() ;
			// on supprime les enseignants
			foreach ($_POST['id'] as $idKey)
			{
				// recupere le login de l'enseignant
				$loginEns = mysql_fetch_array(dbQuery('SELECT login
							FROM enseignant
							WHERE `id-enseignant` = '.$idKey)) ;
				
				// on supprime le dossier de responsable
				if($loginEns['login'] !="")
				{
					$moduleInfo = dbQuery('SELECT apogee
								FROM module
								WHERE `id-responsable` = '.$idKey) ;
					$moduleCount = mysql_num_rows($moduleInfo) ;
					if($moduleCount != 0)
					{
						$moduleDetail = mysql_fetch_array($moduleInfo) ;
						sup_repertoire("../Data/".$moduleDetail['apogee']."/".$loginEns['login']) ;
					}
				}
				
				// on supprime les dossiers d'enseignement
				$moduleInfo = dbQuery('SELECT module.apogee As apogeeMod, matiere.apogee As apogeeMat
							FROM module, matiere, enseignement
							WHERE enseignement.`id-enseignant` = '.$idKey.'
							AND enseignement.`id-matiere` = matiere.`id-matiere`
							AND matiere.`id-module` = module.`id-module`') ;
				$moduleCount = mysql_num_rows($moduleInfo) ;
				for($i = 0 ; $i < $moduleCount ; $i++)
				{
					$moduleDetail = mysql_fetch_array($moduleInfo) ;
					sup_repertoire("../Data/".$moduleDetail['apogeeMod']."/".$moduleDetail['apogeeMat']."/".$loginEns['login']) ;
				}
				
				// suppression dans la BD
				dbQuery('DELETE
					FROM enseignant						
					WHERE `id-enseignant` = '.$idKey) ;
				dbQuery('DELETE
					FROM enseignement				
					WHERE `id-enseignant` = '.$idKey) ;
				dbQuery('UPDATE module
					SET `id-responsable` = 0				
					WHERE `id-responsable` = '.$idKey) ;
			}
			dbClose() ;
			centeredInfoMessage(3, 3, "Enseignant(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignants\">\n") ;
				
		}
			
	} // end of enseignantDel
	
//---------------------------------------------------------------------------------
	// choix de l'action a realiser
	//partie gestion de fichiers
	
	// premier cas : depot
	elseif (isset($_POST['fileDep']))
	{
		
                ///print("on est dans database enseignant");

                // donnees correctes : traitement et ajout
		dbConnect() ;			
		
                //on ne trim pas le titre ni la matiere car il peut y avoir des espaces
                $fMatiere = adslashes($_POST['matiere']);
                $fileTitre = addslashes($_POST['titreDepot']) ;
                $fileCommentaire = addslashes($_POST['commentaireDepot']) ;

                $fileURL = $_FILES['fichierDepot']['name'];
                $fileURLT = $_FILES['fichierDepot']['tmp_name'];

                $fileIdList = dbQuery('SELECT `id-fichier`
                        FROM fichier');
                $fileIdCount = mysql_num_rows($fileIdList);
                $fileId = 0;
                for($i=0; $i<$fileIdCount; $i++)
                {
                          $fFileIdList = mysql_fetch_array($fileIdList);
                          if ($fFileIdList > $fileId)
                          {
                             $fileId = $fFileIdList;
                          }
                }
                $fileId = $fileId + 1;

                // extension du fichier
		$extension = explode(".", $fileURL) ;
		$parts = count($extension) ;
		$finalExtension = $extension[$parts - 1] ;
		
		$finalFile = "" ;
		// on verifie si le cv est uploade avec succes
		if (is_uploaded_file($fileURLT))
		{
			// teste de toutes les facons
			@ $success = move_uploaded_file($fileURLT, "Data/Telechargement/{$_SESSION['diplome']}/{$fileId}_fich.".$finalExtension) ;
			if ($success) { $finalCV = $fileId."_fich.".$finalExtension ; }
		}

		$fMatiereId = dbQuery('SELECT `id-matiere`
                            FROM matiere
                            WHERE intitule = "'.$fMatiere.'"');
                $fMatiereId = mysql_fetch_array($fMatiereId);
                $fMatiereId = $fMatiereId['id-matiere'];

	        dbQuery('INSERT INTO fichier
		VALUES (NULL, "'.$fileTitre.'", "'.$_SESSION['id-dip'].'", "'.$_SESSION['id-enseignant'].'", "'.$finalFile.'", "'.$fileCommentaire.'")') ;

                // felicitations et redirection
                centeredInfoMessage(3, 3, "Fichier d&eacute;pos&eacute; avec succ&egrave;s, redirection...") ;
                print("<meta http-equiv=\"refresh\" content=\"2;url=index.php?w=enseignant\">\n") ;

                dbClose() ;
	} // end of fileDep


        //deuxieme cas suppression
        
        elseif (isset($_POST['fileUndep']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredInfoMessage(3,3,"Aucun fichier selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=index.php?p=connexion&w=enseignant&a=undep\">\n") ;
			return ;
		}
		
		// tout est correct
		dbConnect() ;	
				
		// on supprime les etudiants
		foreach ($_POST['id'] as $idKey)
		{
			dbQuery('DELETE
				FROM fichier
				WHERE `id-fichier` = '.$idKey) ;

			
			// suppression de tous les fichiers qui ont l'id en tete
			$fileDir = opendir("Data/Telechargement/{$_SESSION['diplome']}/") ;
			while ($fileName = readdir($fileDir))
			{

				if (is_numeric(strpos($fileName, $idKey."_")))
				{
					unlink("Data/Telechargement/{$_SESSION['diplome']}/".$fileName) ;
				}
			}
		}
			
		dbClose() ;
		centeredInfoMessage(3,3,"Fichiers(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=index.php?p=connexion&w=enseignants\">\n") ;
				
	} // end of etuDel
//----------------------------------------------------------------------------------------


	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des enseignants : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=enseignants\">\n") ;

	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF menu
*/
?>
