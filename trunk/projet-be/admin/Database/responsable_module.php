<?php
/*
** Fichier : responsable_module
** Date de creation : 28/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des responsables des modules cote mise a jour
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
	
	// premier cas : ajout
	if (isset($_POST['respModAdd']))
	{
		dbConnect() ;
			
		$enseignant = trim($_POST['enseignant']) ;
		$module = trim($_POST['module']) ;
			
		// on teste si l'enseignant et le module existent bien
		$enseignantCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM enseignant
			WHERE `id-enseignant` = '.$enseignant) ;
				
		$enseignantCount = mysql_fetch_array($enseignantCount) ;
			
		$moduleCount = dbQuery('SELECT COUNT(`id-module`) AS n
			FROM module
			WHERE `id-module` = '.$module) ;
				
		$moduleCount = mysql_fetch_array($moduleCount) ;
			
		if (($enseignantCount['n'] == 0) || ($moduleCount['n'] == 0))
		{
			centeredErrorMessage(3, 3, "Erreur lors de l'ajout, enseignant ou module inexistants, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module&a=add\">\n") ;
			return ;
		}
				
		// on teste si la liaison existe deja
		$respModCount = dbQuery('SELECT COUNT(`id-module`) AS n
			FROM module
			WHERE `id-responsable` = '.$enseignant.'
				AND `id-module` = '.$module) ;
					
		$respModCount = mysql_fetch_array($respModCount) ;
			
		if ($respModCount['n'] > 0)
		{
			centeredInfoMessage(3, 3, "Cette liaison existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module\">\n") ;
			return ;
		}
		
		//on regarde s'il y a deja un responsable pour le module
		$resultat = mysql_fetch_array(dbQuery('SELECT COUNT(`id-module`) AS n
											FROM `module`
											WHERE `id-module` = '.$module.'
											AND `id-responsable` <> 0'));
		
		if ($resultat['n'] > 0)
		{
			centeredInfoMessage(3, 3, "Ce module a d&eacute;j&agrave; un responsable, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module\">\n") ;
			return ;
		}
    				
		// on ajoute
		dbQuery('UPDATE module
				SET `id-responsable` = "'.$enseignant.'"
				WHERE `id-module` = "'.$module.'"') ;
		
		// recupere le login de l'enseignant
		$loginEns = mysql_fetch_array(dbQuery('SELECT login
					FROM enseignant
					WHERE `id-enseignant` = '.$enseignant)) ;
		
		if($loginEns['login'] != "")
		{
			// recupere le code apogee du module
			$moduleInfo = mysql_fetch_array(dbQuery('SELECT apogee
						FROM module
						WHERE `id-module` = '.$module)) ;
			mkdir("../Data/".$moduleInfo['apogee']."/".$loginEns['login'], 0755) ;
		}
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Responsabilit&eacute; ajout&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module\">\n") ;
			
		dbClose() ;
	} // end of respModAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['respModMod'])) // :D
	{
		dbConnect() ;
			
		$enseignant = $_POST['oldEns'] ;
		$module = trim($_POST['module']) ;
		$responsable = $_POST['responsable'];
			
		// on teste si le module existe bien			
		$moduleCount = dbQuery('SELECT COUNT(`id-module`) AS n
			FROM module
			WHERE `id-module` = '.$module) ;
				
		$moduleCount = mysql_fetch_array($moduleCount) ;
			
		if ($moduleCount['n'] == 0)
		{
			centeredErrorMessage(3, 3, "Erreur lors de la modification, module inexistant, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module&a=mod\">\n") ;
			return ;
		}
		
		dbQuery('UPDATE `module`
			SET `id-responsable` = '.$responsable.'
			WHERE `id-module` = '.$module) ;
		
		if ($enseignant != $responsable)
		{
			$moduleInfo = mysql_fetch_array(dbQuery('SELECT apogee
							FROM module
							WHERE `id-module` = '.$module)) ;
			
			// recupere le login de l'ancien responsable
			$loginEns = mysql_fetch_array(dbQuery('SELECT login
						FROM enseignant
						WHERE `id-enseignant` = '.$enseignant)) ;
			
			// suppression du dossier de l'ancien responsable
			if($loginEns['login'] != "")
			{
				sup_repertoire("../Data/".$moduleInfo['apogee']."/".$loginEns['login']) ;
			}
			
			// recupere le login du nouveau responsable
			$loginEns = mysql_fetch_array(dbQuery('SELECT login
						FROM enseignant
						WHERE `id-enseignant` = '.$responsable)) ;
			
			// ajout du dossier du nouveau responsable
			if($loginEns['login'] != "")
			{
				mkdir("../Data/".$moduleInfo['apogee']."/".$loginEns['login'], 0755) ;
			}
		}
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Responsable module modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module\">\n") ;
					
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['respModDel']))
	{
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun module selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
		
		// on recupere le login du responsable a supprimer
		$respMod = mysql_fetch_array(dbQuery('SELECT login, apogee
			FROM module, enseignant
			WHERE enseignant.`id-enseignant` = module.`id-responsable`
			AND module.`id-module` = '.$_POST['id'])) ;
		
		dbQuery('UPDATE `module`
			SET `id-responsable` = 0
			WHERE `id-module` = '.$_POST['id']);
		
		// on supprime le dossier du responsable
		if($respMod['login'] != "")
		{
			sup_repertoire("../Data/".$respMod['apogee']."/".$respMod['login']) ;
		}
		
		dbClose() ;
		centeredInfoMessage(3, 3, "Responsable supprim&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_module\">\n") ;
		
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des responsables de modules : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=responsable_module\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF responsable_module
*/
?>
