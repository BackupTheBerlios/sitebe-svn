<?php
/*
** Fichier : controle
** Date de creation : 03/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des controles cote mise a jour
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
	if (isset($_POST['controleAdd']))
	{
		// test des donnees du formulaire
		// donnees incorrectes
		if (empty($_POST['typeControle']) || preg_match("/^\s+$/", $_POST['typeControle']))
		{
			centeredErrorMessage(3, 3, "Erreur lors de l'ajout, type; vide, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles&a=add\">\n") ;
		} // end of empty vars
		
		// donnees correctes : traitement et ajout
		else
		{
			dbConnect() ;
			
			$type = trim($_POST['typeControle']) ;
			$type = addslashes($type) ;
			
			// on verifie si le type existe
			$typeExists = dbQuery('SELECT COUNT(`type`) AS typeNumb
				FROM controle
				WHERE `type` = "'.$type.'"') ;
				
			$typeExists = mysql_fetch_array($typeExists) ;
			
			// si le type n'existe pas
			if ($typeExists['typeNumb'] == 0)
			{
			
				// on insere le nouveau type
				dbQuery('INSERT INTO controle
					VALUES ("'.$type.'")') ;
					
				// felicitations et redirection
				centeredInfoMessage(3, 3, "Type de contrôle ajout&eacute; avec succ&egrave;s, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles\">\n") ;
			}
			
			else
			{
				//le type existe on fait une redirection
				centeredInfoMessage(3, 3, "Type de contrôle déjà présent, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles\">\n") ;
			}				
			dbClose() ;
		} // end of else donnees correctes
	} // end of controleAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['controleMod']))
	{
		// test des donnees du formulaire
		// donnees incorrectes
		if (empty($_POST['typeControle']) || preg_match("/^\s+$/", $_POST['typeControle']))
		{
			centeredErrorMessage(3, 3, "Erreur lors de la modification, type; vide, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles&a=mod\">\n") ;
		} // end of empty vars
		
		// donnees correctes : traitement et ajout
		else
		{
			dbConnect() ;
			
			$type = trim($_POST['typeControle']) ;
			$type = addslashes($type) ;
			
			
			// on met a jour le type de controle
			dbQuery('UPDATE controle
				SET type = "'.$type.'" 
				WHERE type = "'.$_POST['controleID'].'"') ;
				
			dbQuery('UPDATE est_evalue
				SET type = "'.$type.'" 
				WHERE type = "'.$_POST['controleID'].'"') ;	
				
					
			// felicitations et redirection
			centeredInfoMessage(3, 3, "Type modifi&eacute; avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles\">\n") ;		
			
			dbClose() ;
		} // end of else donnees correctes
	} // end of controleMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['controleDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun type selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles&a=del\">\n") ;
		}
		
		// tout est correct
		else
		{
			dbConnect() ;
			// on supprime les types
			foreach ($_POST['id'] as $idKey)
			{	
				dbQuery('DELETE
					FROM controle					
					WHERE type = "'.$idKey.'"') ;
				dbQuery('DELETE
					FROM est_evalue					
					WHERE type = "'.$idKey.'"') ;
			}
			dbClose() ;
			centeredInfoMessage(3, 3, "Type(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=controles\">\n") ;
				
		}
			
	} // end of controleDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des controles : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=controles\">\n") ;
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
