<?php
/*
** Fichier : pages
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des pages cote mise a jour
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
	if (isset($_POST['pageAdd']))
	{
		dbConnect() ;			
			
		$titre = trim($_POST['pageTitre']) ;
		$titre = addslashes($titre) ;
        
        $content = trim(mysql_escape_string($_POST['pageContent']));
        $snote = trim(mysql_escape_string($_POST['pageSNote']));
        $note = trim(mysql_escape_string($_POST['pageNote']));
        $menu = trim(mysql_escape_string($_POST['pageMenu']));
        
		// mise a jour
		dbQuery('INSERT INTO node VALUES
			(NULL, "'.$titre.'", "' . date("Y-m-d H:i:s").'", "' . date("Y-m-d H:i:s").'", "'.$content.'", "'.$note.'", "'.$snote.'", "'.$menu.'", "wiki")');
        $pageid = mysql_insert_id();
        
        dbQuery('UPDATE module SET id_node=' . $pageid . ' WHERE `id-module` = ' . (int)$_POST['idModule']);
		dbClose() ;
			
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Page ajout&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=pages\">\n") ;				
		
		
	} // end of pageAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['pageMod']))
	{
		dbConnect() ;			
			
		$titre = trim($_POST['pageTitre']) ;
		$titre = addslashes($titre) ;
        
        $content = trim(mysql_escape_string($_POST['pageContent']));
        $snote = trim(mysql_escape_string($_POST['pageSNote']));
        $note = trim(mysql_escape_string($_POST['pageNote']));
        $menu = trim(mysql_escape_string($_POST['pageMenu']));
        
		// mise a jour
		dbQuery('UPDATE node
			SET titre = "'.$titre.'", date_modification= "' . date("Y-m-d H:i:s").'", content = "'.$content.'", note = "'.$note.'", note_style = "'.$snote.'", menu = "'.$menu.'", filter = "wiki"
			WHERE id_node = '.$_POST['pageID']) ;
        
        dbQuery('UPDATE module SET id_node=' . $_POST['pageID'] . ' WHERE `id-module` = ' . (int)$_POST['idModule']);
        
		centeredInfoMessage(3, 3, "Page modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=pages\">\n") ;				
			
		dbClose() ;
	} // end of pageMod
	
	// dernier cas : suppression
	elseif (isset($_POST['pageDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun menu selectionne, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=pages&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		foreach ($_POST['id'] as $element)
		{
			dbQuery('DELETE FROM node
				WHERE id_node = '.$element) ;
		}
			
			
		centeredInfoMessage(3, 3, "Page(s) supprim&eacute;e(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=pages\">\n") ;				
			
			
		dbClose() ;
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des pages : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=pages\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF pages
*/
?>
