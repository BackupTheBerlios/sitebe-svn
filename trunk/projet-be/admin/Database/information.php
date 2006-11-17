<?php
/*
** Fichier : information
** Date de creation : 1/07/2005
** Auteurs : Avetisyan Gohar
** Version : 1.0
** Description : Fichier inclu charge de la gestion de l'information du jour cote mise a jour
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
	if (isset($_POST['infoAdd']))
	{
		// donnees correctes : traitement et ajout
		dbConnect() ;			

        $infotitre = trim(mysql_escape_string($_POST['informationTitre']));
        $infostate = (int)trim(mysql_escape_string($_POST['informationState']));
        $infocontent = trim(mysql_escape_string($_POST['informationContenu']));
        
        dbQuery('INSERT INTO information
		VALUES (NULL, "'.$infotitre.'", "'.date("Y-m-d H:i:s"). '", "'.date("Y-m-d H:i:s"). '", "'.$infocontent.'", "", "'.$infostate.'")') ;

        // felicitations et redirection
        centeredInfoMessage(3, 3, "Information ajout&eacute;e avec succ&egrave;s, redirection...") ;
        print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information\">\n") ;

        dbClose() ;
	} // end of infoAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['infoMod']))
	{
		dbConnect() ;
		
        $infotitre = trim(mysql_escape_string($_POST['informationTitre']));
        $infostate = trim(mysql_escape_string($_POST['informationState']));
        $infocontent = trim(mysql_escape_string($_POST['informationContenu']));

		// mise a jour
		dbQuery('UPDATE information
			SET contenu = "'.$infocontent.'", titre = "'.$infotitre.'", URL = "", date_modification = "'. date("Y-m-d H:i:s") . '", etat = "' . $infostate . '"
                        WHERE `id-information` = '.$_POST['informationID']) ;

		centeredInfoMessage(3, 3, "Information modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information\">\n") ;
			
		dbClose() ;
	} // end of infoMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['infoDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune information selectionn&eacute;e, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		foreach ($_POST['id'] as $info)
		{
			dbQuery('DELETE FROM information
				WHERE `id-information` = '.$info) ;
		}
			
			
		centeredInfoMessage(3, 3, "Information supprim&eacute;e(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information\">\n") ;				
			
			
		dbClose() ;	
	} // end of sectionDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration de l'informaton : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=information\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF information
*/
?>
