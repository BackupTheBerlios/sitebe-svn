<?php
/*
** Fichier : sections
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des sections cote mise a jour
**	ajout, suppression, modification
*/




/*
** IMPORTANT :	La verification des donnees fournies se fait cote serveur il
**		est donc important de controler toutes les donnees du formulaire
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	
	// premier cas : ajout
	if (isset($_POST['sectionAdd']))
	{
		// donnees correctes : traitement et ajout
		dbConnect() ;			
						
		$contenu = trim($_POST['sectionContenu']) ;
		$contenu = addslashes($contenu) ;
			
		$page = trim($_POST['sectionPage']) ;
		
		$titre = trim($_POST['sectionTitre']) ;
		$titre = addslashes($titre) ;
		
			
		// on verifie si la page existe
		$pageExists = dbQuery('SELECT COUNT(`id-page`) AS pgNumb
			FROM page
			WHERE `id-page` = '.$page) ;
				
		$pageExists = mysql_fetch_array($pageExists) ;
		
		
			
		// si la page existe
		if ($pageExists['pgNumb'] != 1)
		{
			centeredErrorMessage(3, 3, "La page associ&eacute;e semble ne pas exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections\">\n") ;
			return ;
		}
		
		// a priori tout va bien
		// si on ajoute a la fin
		if ($_POST['sectionPosition'] == "fin")
		{
			$maxPosition = dbQuery('SELECT MAX(ordre) AS position
				FROM section
				WHERE `id-page` = '.$page) ;
				
			$maxPosition = mysql_fetch_array($maxPosition) ;
			$position = $maxPosition['position'] + 1 ;
			
			dbQuery('INSERT INTO section
				VALUES (NULL, '.$page.', "'.$contenu.'", '.$position.', "'.$titre.'")') ;
		}
			
		// si on ajoute au debut
		else
		{
			dbQuery('UPDATE section
				SET ordre = ordre + 1
				WHERE `id-page` = '.$page) ;
			
					
			dbQuery('INSERT INTO section
				VALUES (NULL, '.$page.', "'.$contenu.'", 1, "'.$titre.'")') ;
		}
				
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Section ajout&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections\">\n") ;				
			
		dbClose() ;			
	} // end of sectionAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['sectionMod']))
	{
		dbConnect() ;			
			
		$contenu = trim($_POST['sectionContenu']) ;
		$contenu = addslashes($contenu) ;
			
		$titre = trim($_POST['sectionTitre']) ;
		$titre = addslashes($titre) ;
			
		// mise a jour des position des autres sections
		$sectionDetails = dbQuery('SELECT ordre
			FROM section
			WHERE `id-section` = '.$_POST['sectionID']) ;
		$sectionDetails = mysql_fetch_array($sectionDetails) ;
		$oldPosition = $sectionDetails['ordre'] ;
				
		// cas ou la position du menu a ete reduite			
		if ($_POST['sectionPosition'] <= $oldPosition)
		{
			dbQuery('UPDATE section
			SET ordre = ordre + 1
			WHERE ordre >= '.$_POST['sectionPosition'].' AND ordre < '.$oldPosition) ;
		}
			
		// cas ou la position a ete augmentee
		else
		{
			dbQuery('UPDATE section
			SET ordre = ordre - 1
			WHERE ordre > '.$oldPosition.' AND ordre <= '.$_POST['sectionPosition']) ;
		}
			
		// mise a jour
		dbQuery('UPDATE section
			SET contenu = "'.$contenu.'", ordre = '.$_POST['sectionPosition'].', titre = "'.$titre.'"
			WHERE `id-section` = '.$_POST['sectionID']) ;
				
		centeredInfoMessage(3, 3, "Section modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections\">\n") ;				
			
		dbClose() ;
	} // end of sectionMod
	
	
	
	// troisieme cas : reaffectation
	elseif (isset($_POST['sectionAff']))
	{
		dbConnect() ;
				
		$page = trim($_POST['sectionPage']) ;
			
		// on verifie si la page existe
		$pageExists = dbQuery('SELECT COUNT(`id-page`) AS pgNumb
			FROM page
			WHERE `id-page` = '.$page) ;
				
		$pageExists = mysql_fetch_array($pageExists) ;
		
			
		// si la page existe
		if ($pageExists['pgNumb'] != 1)
		{
			centeredErrorMessage(3, 3, "La page associ&eacute;e semble ne pas exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections\">\n") ;
			return ;
		}
		
		// on diminue les positions des sections dans l'ancienne page
		$oldPos = dbQuery('SELECT `id-page`, ordre
			FROM section
			WHERE `id-section` = '.$_POST['sectionID']) ;
		$oldPos = mysql_fetch_array($oldPos) ;
		dbQuery('UPDATE section
			SET ordre = ordre - 1
			WHERE ordre > '.$oldPos['ordre'].' AND `id-page` = '.$oldPos['id-page']) ;
			
				
		// on insere a la fin : position max
		$posMax = dbQuery('SELECT MAX(ordre) AS pos
			FROM section
			WHERE `id-page` = '.$page) ;
		$posMax = mysql_fetch_array($posMax) ;
		$posMax = $posMax['pos'] + 1 ; 
			
			
			
		// mise a jour
		dbQuery('UPDATE section
			SET `id-page` = '.$page.', ordre = '.$posMax.'
			WHERE `id-section` = '.$_POST['sectionID']) ;
			
				
		centeredInfoMessage(3, 3, "Section modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections\">\n") ;
		
		dbClose() ;
	} // end of sectionAff
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['sectionDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune section selectionn&eacute;e, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		foreach ($_POST['id'] as $element)
		{
			dbQuery('DELETE FROM section
				WHERE `id-section` = '.$element) ;
		}
			
			
		centeredInfoMessage(3, 3, "Section(s) supprim&eacute;e(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=sections\">\n") ;				
			
			
		dbClose() ;	
	} // end of sectionDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des sections : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=sections\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF sections
*/
?>
