<?php
/*
** Fichier : diplomes
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des diplomes cote mise a jour
**	ajout, suppression, modification
*/







// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	
	// premier cas : ajout
	if (isset($_POST['dipAdd']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['dipIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		/*
		** IMPORTANT :	Verifier si le diplome est deja present
		*/
			
		$dipOccurences = dbQuery('SELECT COUNT(`id-diplome`) AS dipNumber
			FROM diplome
			WHERE intitule = "'.$intitule.'"') ;
		$dipOccurences = mysql_fetch_array($dipOccurences) ;
		$dipNumber = $dipOccurences['dipNumber'] ;
			
		// cas ou le diplome existe deja
		if ($dipNumber > 0)
		{
			centeredInfoMessage(3, 3, "Impossible d'ajouter ce dipl&ocirc;me car il existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=diplomes&a=add\">\n") ;
			return ;
		}
			
			
		// on insere le nouveau diplome
		dbQuery('INSERT INTO diplome
			VALUES (NULL, "'.$intitule.'")') ;
					
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Dipl&ocirc;me ajout&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=diplomes\">\n") ;				
			
		dbClose() ;
	} // end of dipAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['dipMod']))
	{
		
		dbConnect() ;
			
		$intitule = trim($_POST['dipIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		/*
		** IMPORTANT :	Verifier si le diplome est deja present
		*/
			
		$dipOccurences = dbQuery('SELECT COUNT(`id-diplome`) AS dipNumber
			FROM diplome
			WHERE intitule = "'.$intitule.'"') ;
		$dipOccurences = mysql_fetch_array($dipOccurences) ;
		$dipNumber = $dipOccurences['dipNumber'] ;
			
		// cas ou le diplome existe deja
		if ($dipNumber > 0)
		{
			centeredInfoMessage(3, 3, "Impossible de modifier ce dipl&ocirc;me car il existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=diplomes&a=mod\">\n") ;
			return ;
		}
		
		// on modifie
		dbQuery('UPDATE diplome
			SET intitule = "'.$intitule.'"
			WHERE `id-diplome` = '.$_POST['dipID']) ;
					
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Dipl&ocirc;me modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=diplomes\">\n") ;				
		
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['dipDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun dipl&ocirc;me selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=diplomes&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		// enfin on supprime les diplomes
		foreach ($_POST['id'] as $idKey)
		{	
			$modulesList = dbQuery('SELECT `id-module`
				FROM module
				WHERE `id-diplome` = '.$idKey) ;
					
			while ($moduleID = mysql_fetch_array($modulesList))
			{
				// important : les enseignements
				$matList = dbQuery('SELECT `id-matiere`
					FROM matiere
					WHERE `id-module` = '.$moduleID['id-module']) ;
					
				while ($matID = mysql_fetch_array($matList))
				{
					dbQuery('DELETE
					FROM enseignement
					WHERE `id-matiere` = '.$matID['id-matiere']) ;
					
					dbQuery('DELETE
						FROM est_evalue						
						WHERE `id-matiere` = '.$matID['id-matiere']) ;
				}
					
				
				
				dbQuery('DELETE
					FROM matiere
					WHERE `id-module` = '.$moduleID['id-module']) ;
				
			}
						
			// suppression des modules
			dbQuery('DELETE
				FROM module
				WHERE `id-diplome` = '.$idKey) ;
									
			dbQuery('DELETE
				FROM diplome						
				WHERE `id-diplome` = '.$idKey) ;
		}
			
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Dipl&ocirc;me(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=diplomes\">\n") ;
		
				
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des dipl&ocirc;mes : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=diplomes\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF diplomes
*/
?>
