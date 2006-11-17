<?php
/*
** Fichier : enseignements
** Date de creation : 28/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des enseignements cote mise a jour
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
	if (isset($_POST['enseignementAdd']))
	{
		dbConnect() ;
		
		$enseignant = trim($_POST['enseignant']) ;
		$matiere = trim($_POST['matiere']) ;
			
		// on teste si l'enseignant et la matiere existent bien
		$enseignantCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM enseignant
			WHERE `id-enseignant` = '.$enseignant) ;
				
		$enseignantCount = mysql_fetch_array($enseignantCount) ;
			
		$matiereCount = dbQuery('SELECT COUNT(`id-matiere`) AS n
			FROM matiere
				WHERE `id-matiere` = '.$matiere) ;
				
		$matiereCount = mysql_fetch_array($matiereCount) ;
			
		if (($enseignantCount['n'] == 0) || ($matiereCount['n'] == 0))
		{
			centeredErrorMessage(3, 3, "Erreur lors de l'ajout, enseignant ou mati&egrave;re inexistants, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements&a=add\">\n") ;
			return ;
		}
		
		// on teste si cet enseignement existe deja
		$ensCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM enseignement
			WHERE `id-enseignant` = '.$enseignant.' AND
				`id-matiere` = '.$matiere) ;
					
		$ensCount = mysql_fetch_array($ensCount) ;
			
		if ($ensCount['n'] > 0)
		{
			centeredInfoMessage(3, 3, "Cet enseignement existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements\">\n") ;
			return ;
		}
		
		dbQuery('INSERT INTO enseignement
			VALUES ('.$matiere.', '.$enseignant.')') ;
				
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Enseignement ajout&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements\">\n") ;
			
		dbClose() ;
	} // end of menuAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['enseignementMod']))
	{
		dbConnect() ;
			
		$enseignant = $_POST['enseignant'] ;
		$matiere = trim($_POST['matiere']) ;
			
		// on teste si la matiere existe bien
				
			
		$matiereCount = dbQuery('SELECT COUNT(`id-matiere`) AS n
			FROM matiere
			WHERE `id-matiere` = '.$matiere) ;
				
		$matiereCount = mysql_fetch_array($matiereCount) ;
			
		if ($matiereCount['n'] == 0)
		{
			centeredErrorMessage(3, 3, "Erreur lors de la modification, mati&egrave;re inexistante, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements&a=mod\">\n") ;
			return ;
		}
			
		// on teste si cet enseignement existe deja
		$ensCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM enseignement
			WHERE `id-enseignant` = '.$enseignant.' AND
				`id-matiere` = '.$matiere) ;
					
		$ensCount = mysql_fetch_array($ensCount) ;
			
		if ($ensCount['n'] > 0)
		{
			centeredInfoMessage(3, 3, "Cet enseignement existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements\">\n") ;
			return ;
		}
		
		dbQuery('UPDATE enseignement
			SET `id-matiere` = '.$matiere.'
			WHERE `id-enseignant` = '.$enseignant.' AND `id-matiere` = '.$_POST['oldMatiere']) ;
				
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Enseignement modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements\">\n") ;
		
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['enseignementDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun enseignement selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements&a=del\">\n") ;
			return ;
		}
		
		// tout est correct
		dbConnect() ;			
			
		foreach ($_POST['id'] as $matiere)
		{				
			dbQuery('DELETE FROM enseignement
				WHERE `id-matiere` = '.$matiere.' AND
					`id-enseignant` = '.$_POST['enseignant']) ;
		}
			
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Enseignements(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=enseignements\">\n") ;
			
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des enseignements : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=enseignements\">\n") ;
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
