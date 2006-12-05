<?php
/*
** Fichier : modules
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des modules cote mise a jour
**	ajout, suppression, modification
*/




/*
** IMPORTANT :	La verification des donnees critiques se fait cote serveur il
**		est donc important de controler toutes les donnees correspondantes du formulaire
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
	if (isset($_POST['moduleAdd']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['moduleIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		$description = trim($_POST['moduleContenu']) ;
		$description = addslashes($description) ;
			
		// on recupere l'identifiant du diplome
		$dipID = dbQuery('SELECT `id-diplome`
			FROM diplome
			WHERE intitule = "'.$_POST['moduleDiplome'].'"') ;
		
		$dipExists = mysql_num_rows($dipID) ;
		if ($dipExists == 0)
		{
			centeredErrorMessage(3, 3, "Impossible d'ajouter ce module car le dipl&ocirc;me semble ne pas exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules&a=add\">\n") ;
			return ;
		}
	
				
		$dipID = mysql_fetch_array($dipID) ;
		$dipID = $dipID['id-diplome'] ;
			
			
		// on verifie si le module existe deja
		$moduleInfo = dbQuery('SELECT `id-module`
			FROM module
			WHERE intitule = "'.$intitule.'" AND `id-diplome` = '.$dipID) ;
				
		$moduleExists = mysql_num_rows($moduleInfo) ; 
			
		// si quelque chose existe deja
		if ($moduleExists > 0)
		{
			centeredInfoMessage(3, 3, "Impossible d'ajouter ce module car il existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules&a=add\">\n") ;
			return ;
		}
			
		// Ajout module		
		dbQuery('INSERT INTO module
			VALUES (NULL, '.$dipID.', "'.$intitule.'", 1, "'.$description.'", "0", "0", "0", "'.(((int)$_POST['semestre'])+($dipID*2)).'", "'. (int)$_POST['moduleResp'] . '", "", "0")') ;
		
		// on recupere l'identifiant du module cree
		$modID = mysql_insert_id();
		
		// Dans le cas ou on choisi un enseignant responsable du module on doit ajouter un tuple dans la base
		// dans la table resp-module
		if(isset($_POST['respMod']))
		{
			if($_POST['respMod'] == 'oui' && $_POST['moduleResp'] != '0')
			{
				$req = "UPDATE module
						SET `id-responsable` = ".$_POST['moduleResp']."
						WHERE `id-module` = ".$modID;
				dbQuery($req);
			}
		}else{
			print("Erreur le champ demandé n'existe pas !");
		}
		
		// Ajout du repertoire associe
		mkdir("../Data/".$modID, 0755) ;
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Module ajout&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules\">\n") ;
		
		dbClose() ;
	} // end of menuAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['moduleMod']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['moduleIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		$description = trim($_POST['moduleContenu']) ;
		$description = addslashes($description) ;
			
		// on recupere l'identifiant du diplome
		$dipID = dbQuery('SELECT `id-diplome`
			FROM diplome
			WHERE intitule = "'.$_POST['moduleDiplome'].'"') ;
			
		// on verifie si le diplome existe
		$dipExists = mysql_num_rows($dipID) ;	
			
			
		if ($dipExists == 0)
		{
			centeredErrorMessage(3, 3, "Impossible d'ajouter ce module car le dipl&ocirc;me semble ne pas exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules&a=add\">\n") ;
			return ;
		}	
			
		$dipID = mysql_fetch_array($dipID) ;
		$dipID = $dipID['id-diplome'] ;
			
			
		// on verifie si le module existe deja
		$moduleInfo = dbQuery('SELECT `id-module`
			FROM module
			WHERE intitule = "'.$intitule.'" AND 
			`id-diplome` = '.$dipID.' AND
			`id-module` != '.$_POST['moduleID']) ;
				
		$moduleExists = mysql_num_rows($moduleInfo) ; 
			
		// si un module de meme nom existe deja
		if ($moduleExists > 0)
		{
			centeredInfoMessage(3, 3, "Impossible de modifier l'intitule de ce module car il en existe d&eacute;j&agrave; un de meme nom, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules&a=mod&id={$_POST['moduleID']}\">\n") ;
			return ;
		}
				
		// recupere l'id du nouveau responsable de module, si la case 'oui' était cochee
		$moduleResp = (int)$_POST['moduleResp'];
		if ($_POST['respMod'] == 'non')
		{
			$moduleResp = 0;
		}
		
		// on ajoute sinon	
		dbQuery('UPDATE module
			SET  `id-diplome` = '.$dipID.', intitule = "'.$intitule.'", description = "'.$description.'", no_semestre = "'.(((int)$_POST['semestre']) + 2*$dipID).'", `id-responsable` = "'. $moduleResp . '", id_node = "0"
			WHERE `id-module` = '.$_POST['moduleID']) ;
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Module modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules\">\n") ;
		
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['moduleDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun module selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		// enfin on supprime les modules
		foreach ($_POST['id'] as $idKey)
		{	
			// suppression des matieres
			$matList = dbQuery('SELECT `id-matiere`
				FROM matiere
				WHERE `id-module` = '.$idKey) ;
			// et donc suppression des enseignements	
			while ($details = mysql_fetch_array($matList))	
			{
				dbQuery('DELETE
					FROM enseignement
					WHERE `id-matiere` = '.$details['id-matiere']) ;
					
				// Suppression des eventuels contrôles de module
				$evalList = dbQuery('SELECT `id-evaluation`
					FROM evaluation
					WHERE `id-matiere` = '.$details['id-matiere']) ;
				// et donc suppression des notes	
				while ($detailsEval = mysql_fetch_array($evalList))	
				{
					dbQuery('DELETE
						FROM note
						WHERE `id-evaluation` = '.$detailsEval['id-evaluation']) ;
				}
				
				// suppression des controles de matiere
				dbQuery('DELETE
					FROM evaluation
					WHERE `id-matiere` = '.$details['id-matiere']) ;
			}
			
			dbQuery('DELETE
				FROM matiere
				WHERE `id-module` = '.$idKey) ;
			
			// Suppression des eventuels contrôles de module
			$evalList = dbQuery('SELECT `id-evaluation`
				FROM evaluation
				WHERE `id-module` = '.$idKey) ;
			// et donc suppression des notes	
			while ($details = mysql_fetch_array($evalList))	
			{
				dbQuery('DELETE
					FROM note
					WHERE `id-evaluation` = '.$details['id-evaluation']) ;
			}
			
			// suppression des controles de module
			dbQuery('DELETE
				FROM evaluation
				WHERE `id-module` = '.$idKey) ;
					
			dbQuery('DELETE
				FROM module						
				WHERE `id-module` = '.$idKey) ;
			
			// On supprime le dossier lié au module
			sup_repertoire("../Data/".$idKey) ;
		}
			
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Module(s) et enseignements supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=modules\">\n") ;
			
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des modules : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=modules\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF modules
*/
?>
