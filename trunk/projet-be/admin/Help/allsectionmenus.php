<?php
/*
** Fichier : allsectionmenus
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de les menus de type section du site
*/



/*
** IMPORTANT : cette page peut etre utilisee a partir de divers endroits le
**	nom du formulaire ainsi que le nom du champs peuvent etre passes dans
**	les variables $_GET['f'] et $_GET['t'] qui ont des valeurs par defaut
*/



// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{
	centeredInfoMessage(3, 3, "Aide : liste des menus") ;
	dbConnect() ;
	$allMenus = dbQuery('SELECT *
		FROM menu
		WHERE type = "sections"') ;
		
	$menusCount = mysql_num_rows($allMenus) ;
	// si il n'y a aucun menu
	if ($menusCount == 0)
	{
		centeredInfoMessage(2, 2, "Aucun menu pour le moment") ;
	}
	
	else
	{
	
		$formName = "defaultForm" ;	// valeur par defaut
		if (isset($_GET['f'])) { $formName = $_GET['f'] ; }
		
		$fieldName = "pageMenu" ;	// valeur par defaut
		if (isset($_GET['t'])) { $fieldName = $_GET['t'] ; }
	
		print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Intitul&eacute;</b></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
			
		for ($i = 0 ; $i < $menusCount ; $i++)
		{
			$details = mysql_fetch_array($allMenus) ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-menu']}</td><td width=\"300\" align=\"left\">{$details['intitule']}</td>") ;
			print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-menu']}', '$formName', '$fieldName')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
		}
		
		print("\t\t\t</table></center>\n") ;
	}
	
	dbClose() ;
} // end of if session connecte


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}

/*
** EOF allpages
*/
?>
