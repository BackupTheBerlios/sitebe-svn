<?php
/*
** Fichier : matieres
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des matieres cote mise a jour
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
	if (isset($_POST['matiereAdd']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['matiereIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		$coeff = trim($_POST['matiereCoeff']) ;
		$coeff = (float)$coeff ;
			
		$heures = trim($_POST['matiereHeures']) ;
		
		$moduleID = trim($_POST['matiereModule']) ;
		
		$no = trim($_POST['matiereNo']) ;
							
				
		// on verifie si la matiere existe deja
		$matiereInfo = dbQuery('SELECT COUNT(`id-matiere`) AS matNumb
			FROM matiere
			WHERE intitule = "'.$intitule.'"
			AND `id-module` = '.$moduleID) ;
				
		$matiereInfo = mysql_fetch_array($matiereInfo) ; 
			
		// si quelque chose existe deja
		if ($matiereInfo['matNumb'] > 0)
		{
			centeredInfoMessage(3, 3, "Impossible d'ajouter cette mati&egrave;re car elle existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres&a=add\">\n") ;
			return ;
		}
		
		// on recupere les infos sur le module (pour le code apogee)
		$moduleInfo = mysql_fetch_array(dbQuery('SELECT code AS dipCode, no_module, no_semestre, apogee
			FROM module, diplome
			WHERE `id-module` = '.$moduleID.'
			AND module.`id-diplome` = diplome.`id-diplome`')) ;
		
		// Creation du code apogee
		$apogee = "U".$moduleInfo['dipCode'] ;
		if ($moduleInfo['no_semestre'] == 10)
		{
			$apogee = $apogee."A" ;
		}
		else
		{
			$apogee = $apogee.$moduleInfo['no_semestre'] ;
		}
		$apogee = $apogee."A" ;
		if ($moduleInfo['no_module'] < 10)
		{
			$apogee = $apogee."0" ;
		}
		$apogee = $apogee.$moduleInfo['no_module'].$no ;
		
		dbQuery('INSERT INTO matiere
			VALUES (NULL, '.$moduleID.', '.$no.', '.$coeff.', "'.$intitule.'",'.$heures.', 0,0,0,"'.$apogee.'")') ;
		
		// Ajout du repertoire associe
		mkdir("../Data/".$moduleInfo['apogee']."/".$apogee, 0755) ;
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Mati&egrave;re ajout&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres\">\n") ;
		
		dbClose() ;
	} // end of matiereAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['matiereMod']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['matiereIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		$coeff = trim($_POST['matiereCoeff']) ;
		$coeff = (float)$coeff ;
			
		$heures = trim($_POST['matiereHeures']) ;
			
		$moduleID = trim($_POST['matiereModule']) ;
		
		$no = trim($_POST['matiereNo']) ;
							
					
		// on verifie si la matiere existe deja
		$matiereInfo = dbQuery('SELECT COUNT(`id-matiere`) AS matNumb
			FROM matiere
			WHERE intitule = "'.$intitule.'"
			AND `id-module` = '.$moduleID.'
			AND `id-matiere` != '.$_POST['matiereID']) ;
				
		$matiereInfo = mysql_fetch_array($matiereInfo) ; 
			
		// si quelque chose existe deja
		if ($matiereInfo['matNumb'] > 0)
		{
			centeredInfoMessage(3, 3, "Impossible d'ajouter cette mati&egrave;re car elle existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres&a=mod&id={$_POST['matiereID']}\">\n") ;
			return ;
		}
		
		// on recupere les infos sur le module (pour le code apogee)
		$moduleInfo = mysql_fetch_array(dbQuery('SELECT code AS dipCode, no_module, no_semestre, module.apogee AS apogeeMod, matiere.apogee AS apogeeMat
			FROM module, diplome, matiere
			WHERE matiere.`id-matiere` = '.$_POST['matiereID'].'
			AND module.`id-module` = matiere.`id-module`
			AND module.`id-diplome` = diplome.`id-diplome`')) ;
		
		// Creation du code apogee
		$apogee = "U".$moduleInfo['dipCode'] ;
		if ($moduleInfo['no_semestre'] == 10)
		{
			$apogee = $apogee."A" ;
		}
		else
		{
			$apogee = $apogee.$moduleInfo['no_semestre'] ;
		}
		$apogee = $apogee."A" ;
		if ($moduleInfo['no_module'] < 10)
		{
			$apogee = $apogee."0" ;
		}
		$apogee = $apogee.$moduleInfo['no_module'].$no ;
		
		dbQuery('UPDATE matiere
			SET intitule = "'.$intitule.'", coefficient = '.$coeff.', `nbre-heures` = '.$heures.', no_matiere = '.$no.', apogee = "'.$apogee.'"
			WHERE `id-matiere` = '.$_POST['matiereID']) ;
		
		// Modification du nom du repertoire associe
		rename("../Data/".$moduleInfo['apogeeMod']."/".$moduleInfo['apogeeMat'], "../Data/".$moduleInfo['apogeeMod']."/".$apogee) ;
		
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Mati&egrave;re modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres\">\n") ;
		
		dbClose() ;
	} // end of matiereMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['matiereDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune mati&egrave;re selectionn&eacute;e, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;
			
			
		foreach ($_POST['id'] as $idKey)
		{
			$apogee = dbQuery('SELECT module.apogee AS apogeeMod, matiere.apogee AS apogeeMat
				FROM module, matiere						
				WHERE matiere.`id-matiere` = '.$idKey.'
				AND	matiere.`id-module` = module.`id-module`') ;
			$apogee = mysql_fetch_array($apogee) ;
							
			dbQuery('DELETE
				FROM matiere						
				WHERE `id-matiere` = '.$idKey) ;
					
			dbQuery('DELETE
				FROM enseignement						
				WHERE `id-matiere` = '.$idKey) ;
			
			dbQuery('DELETE
				FROM note						
				WHERE `id-evaluation` in (SELECT E.`id-evaluation`
										FROM evaluation E
										WHERE E.`id-matiere` = '.$idKey.')') ;
			
			dbQuery('DELETE
				FROM evaluation						
				WHERE `id-matiere` = '.$idKey) ;
			
			// On supprime le dossier lié a la matiere
			sup_repertoire("../Data/".$apogee['apogeeMod']."/".$apogee['apogeeMat']) ;
		}
		
		
		dbClose() ;
		centeredInfoMessage(3, 3, "Mati&egrave;re(s) et enseignements supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres\">\n") ;
			
	} // end of matiereDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des mati&egrave;res : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=matieres\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF matieres
*/
?>
