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
						
		dbQuery('UPDATE `module`
			SET `id-responsable` = 0
			WHERE `id-module` = '.$_POST['id']);
		
			
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
