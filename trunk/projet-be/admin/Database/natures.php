<?php
/*
** Fichier : enseignant
** Date de creation : 03/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion de la nature des examens cote mise a jour
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
	if (isset($_POST['natureAdd']))
	{
		// test des donnees du formulaire
		// donnees incorrectes
		if (empty($_POST['natureExamen']) || preg_match("/^\s+$/", $_POST['natureExamen']))
		{
			centeredErrorMessage(3, 3, "Erreur lors de l'ajout, nature d'examen; vide, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=natures&a=add\">\n") ;
		} // end of empty vars
		 
		else
		{
			dbConnect() ;
			
			$nature = trim($_POST['natureExamen']) ;
			$nature = addslashes($nature) ;
			 	
			// on insere le nouveau enseignant
			dbQuery('INSERT INTO nature
				VALUES ( "'.$nature.'")') ;
					
			// felicitations et redirection
			centeredInfoMessage(3, 3, "nature d'examen ajout&eacute; avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=natures\">\n") ;				
			dbClose() ;
		} // end of else donnees correctes
	} // end of natureAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['natureMod']))
	{
		// test des donnees du formulaire
		// donnees incorrectes
		if (empty($_POST['natureExamen']) || preg_match("/^\s+$/", $_POST['natureExamen']))
		{
			centeredErrorMessage(3, 3, "Erreur lors de la modification, nature; vide, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=natures&a=mod\">\n") ;
		} // end of empty vars
		
 
		// donnees correctes : traitement et ajout
		else
		{
			dbConnect() ;
			
			$nature = trim($_POST['natureExamen']) ;
			$nature = addslashes($nature) ; 
			
				
			// on met a jour la nature
			dbQuery('UPDATE nature
				SET nature = "'.$nature.'" 
				WHERE `nature` = "'.$_POST['natureID'].'"') ;
				
			// ainsi que les evalutions
			dbQuery('UPDATE est_evalue
				SET nature = "'.$nature.'" 
				WHERE `nature` = "'.$_POST['natureID'].'"') ;
					
			// felicitations et redirection
			centeredInfoMessage(3, 3, "nature d'examen modifi&eacute; avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=natures\">\n") ;		
			
			dbClose() ;
		} // end of else donnees correctes
	} // end of natureMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['natureDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune nature d'examen selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=natures&a=del\">\n") ;
		}
		
		// tout est correct
		else
		{
			dbConnect() ;
			// on supprime les natures d'examens
			foreach($_POST['id'] as $idKey)
			{	
				dbQuery('DELETE
					FROM nature						
					WHERE nature = "'.$idKey.'"') ;
				dbQuery('DELETE
					FROM est_evalue						
					WHERE nature = "'.$idKey.'"') ;
			}
			dbClose() ;
			centeredInfoMessage(3, 3, "nature(s) d'examen supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=natures\">\n") ;
				
		}
			
	} // end of natureDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration de la nature des examens : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=natures\">\n") ;
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
