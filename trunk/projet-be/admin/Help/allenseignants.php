<?php
/*
** Fichier : allenseignants
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de l'affichage de tous les enseignants
*/



/*
** IMPORTANT : cette page peut etre utilisee a partir de divers endroits le
**	nom du formulaire ainsi que le nom du champs peuvent etre passes dans
**	les variables $_GET['f'] et $_GET['t'] qui ont des valeurs par defaut
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{
	centeredInfoMessage(3, 3, "Aide : liste des enseignants") ;
	dbConnect() ;
	
	$elementsCount = 30 ;	// on prend 30 elements par defaut
	$fromLimit = 0 ;
	if (isset($_GET['from']) && is_numeric($_GET['from']))
	{
		$fromLimit = $_GET['from'] ;
	}
	
	
	// nombre total des enseignants
	$totalEns = dbQuery('SELECT COUNT(`id-enseignant`) AS totalEns
		FROM enseignant') ;
	$totalEns = mysql_fetch_array($totalEns) ;
	$totalEns = $totalEns['totalEns'] ;
	
	$allEns = dbQuery('SELECT *
		FROM enseignant
		ORDER BY nom, prenom
		LIMIT '.$fromLimit.', '.$elementsCount) ;
		
	$ensCount = mysql_num_rows($allEns) ;
	// si il n'y a aucun enseignant
	if ($ensCount == 0)
	{
		centeredInfoMessage(2, 2, "Aucun enseignant pour le moment") ;
		return ;
	}
	
	$formName = "defaultForm" ;	// valeur par defaut
	if (isset($_GET['f'])) { $formName = $_GET['f'] ; }
		
	$fieldName = "enseignant" ;	// valeur par defaut
	if (isset($_GET['t'])) { $fieldName = $_GET['t'] ; }
		
	// les pages pour acceder aux autres enseignants
	$pagesCount = $totalEns / $ensCount ;
		
	print("\t\t\t<center><b>Pages</b><br>") ;
	for ($k = 0 ; $k < $pagesCount ; $k++)
	{
		if (($k * $elementsCount) == $fromLimit)
		{
			print(" [ ".($k + 1)." ] \n") ;
		}
		
		else
		{
			print(" <a href=\"help.php?w=allenseignants&f=$formName&t=$fieldName&from=".($k * $elementsCount)."\">".($k + 1)."</a> \n") ;
			}
	}		
	print("</center>\n") ;
	
	print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Nom</b></td>\n") ;
	print("\t\t\t\t</tr>\n") ;
			
	for ($i = 0 ; $i < $ensCount ; $i++)
	{
		$details = mysql_fetch_array($allEns) ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-enseignant']}</td><td width=\"300\" align=\"left\">{$details['nom']}, {$details['prenom']}</td>") ;
		print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-enseignant']}', '$formName', '$fieldName')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
	}
		
	print("\t\t\t</table></center>\n") ;
	
	dbClose() ;
} // end of if session connecte


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}

/*
** EOF allenseignants
*/
?>
