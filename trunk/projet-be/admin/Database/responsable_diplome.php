<?php
/*
** Fichier : responsable_diplome
** Date de creation : 28/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des responsables des diplomes cote mise a jour
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
	if (isset($_POST['respDipAdd']))
	{
		dbConnect() ;
			
		$enseignant = trim($_POST['enseignant']) ;
		$diplome = trim($_POST['diplome']) ;
			
		// on teste si l'enseignant et la diplome existent bien
		$enseignantCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM enseignant
			WHERE `id-enseignant` = '.$enseignant) ;
				
		$enseignantCount = mysql_fetch_array($enseignantCount) ;
			
		$diplomeCount = dbQuery('SELECT COUNT(`id-diplome`) AS n
			FROM diplome
			WHERE `id-diplome` = '.$diplome) ;
				
		$diplomeCount = mysql_fetch_array($diplomeCount) ;
		
		if (($enseignantCount['n'] == 0) || ($diplomeCount['n'] == 0))
		{
			centeredErrorMessage(3, 3, "Erreur lors de l'ajout, enseignant ou dipl&ocirc;me inexistants, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome&a=add\">\n") ;
			return ;
		}
		
		// on teste si la liaison existe deja
		$respDipCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM `resp-diplome`
			WHERE `id-enseignant` = '.$enseignant.' AND
				`id-diplome` = '.$diplome) ;
					
		$respDipCount = mysql_fetch_array($respDipCount) ;
			
		if ($respDipCount['n'] > 0)
		{
			centeredInfoMessage(3, 3, "Cette liaison existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome\">\n") ;
			return ;
		}
					
		dbQuery('INSERT INTO `resp-diplome`
				VALUES ('.$diplome.', '.$enseignant.')') ;
				
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Responsabilit&eacute; ajout&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome\">\n") ;
			
		dbClose() ;
	} // end of respModAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['respDipMod']))
	{
		dbConnect() ;
			
		$enseignant = $_POST['enseignant'] ;
		$diplome = trim($_POST['diplome']) ;
			
		// on teste si la diplome existe bien
				
			
		$diplomeCount = dbQuery('SELECT COUNT(`id-diplome`) AS n
			FROM diplome
			WHERE `id-diplome` = '.$diplome) ;
				
		$diplomeCount = mysql_fetch_array($diplomeCount) ;
			
		if ($diplomeCount['n'] == 0)
		{
			centeredErrorMessage(3, 3, "Erreur lors de la modification, diplome inexistant, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome&a=mod\">\n") ;
			return ;
		}
		
		// on teste si cette existe deja
		$ensCount = dbQuery('SELECT COUNT(`id-enseignant`) AS n
			FROM `resp-diplome`
			WHERE `id-enseignant` = '.$enseignant.' AND
				`id-diplome` = '.$diplome) ;
					
		$ensCount = mysql_fetch_array($ensCount) ;
			
		if ($ensCount['n'] > 0)
		{
			centeredInfoMessage(3, 3, "Cette liaison existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome\">\n") ;
			return ;
		}
		
		dbQuery('UPDATE `resp-diplome`
			SET `id-diplome` = '.$diplome.'
			WHERE `id-enseignant` = '.$enseignant.' AND `id-diplome` = '.$_POST['oldDiplome']) ;
				
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Responsable dipl&ocirc;me modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome\">\n") ;
		
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['respDipDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune responsabilit&eacute; de dipl&ocirc;me selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		foreach ($_POST['id'] as $diplome)
		{				
			dbQuery('DELETE FROM `resp-diplome`
				WHERE `id-diplome` = '.$diplome.' AND
					`id-enseignant` = '.$_POST['enseignant']) ;
		}
			
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Liaison(s) supprim&eacute;e(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=responsable_diplome\">\n") ;
			
	} // end of respDipDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des responsables de dipl&ocirc;mes : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=responsable_diplome\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF responsable_diplome
*/
?>
