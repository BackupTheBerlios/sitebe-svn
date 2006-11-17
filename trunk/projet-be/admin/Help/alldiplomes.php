<?php
/*
** Fichier : alldiplomes
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de l'affichage de tous les diplomes
*/



/*
** IMPORTANT : cette page peut etre utilisee a partir de divers endroits le
**	nom du formulaire ainsi que le nom du champs peuvent etre passes dans
**	les variables $_GET['f'] et $_GET['t'] qui ont des valeurs par defaut
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{
	centeredInfoMessage(3, 3, "Aide : liste des pages") ;
	dbConnect() ;
	$allDips = dbQuery('SELECT *
		FROM diplome
		ORDER BY intitule') ;
		
	$dipsCount = mysql_num_rows($allDips) ;
	// si il n'y a aucune page
	if ($dipsCount == 0)
	{
		centeredInfoMessage(2, 2, "Aucun dipl&ocirc;me pour le moment") ;
	}
	
	else
	{
		$formName = "defaultForm" ;	// valeur par defaut
		if (isset($_GET['f'])) { $formName = $_GET['f'] ; }
		
		$fieldName = "moduleDiplome" ;	// valeur par defaut
		if (isset($_GET['t'])) { $fieldName = $_GET['t'] ; }
	
	
		print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Intitul&eacute;</b></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
			
		for ($i = 0 ; $i < $dipsCount ; $i++)
		{
			$details = mysql_fetch_array($allDips) ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-diplome']}</td><td width=\"300\" align=\"left\">{$details['intitule']}</td>") ;
			print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-diplome']}', '{$formName}', '{$fieldName}')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
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
** EOF alldiplomes
*/
?>
