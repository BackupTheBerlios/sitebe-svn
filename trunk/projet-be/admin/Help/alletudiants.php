<?php
/*
** Fichier : alletudiants
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de l'affichage de tous les enseignants
*/



/*
** IMPORTANT : cette page peut etre utilisee a partir de divers endroits le
**	nom du formulaire ainsi que le nom du champs peuvent etre passes dans
**	les variables $_GET['f'] et $_GET['t'] qui ont des valeurs par defaut
**	la variable $_GET['from']  sert a limiter les etudiants a recuperer
**	toujours 30 par page
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "help.php")))
{
	centeredInfoMessage(3, 3, "Aide : liste des etudiants") ;
	dbConnect() ;
	
	$elementsCount = 30 ;	// on prend 30 elements par defaut
	$fromLimit = 0 ;
	if (isset($_GET['from']) && is_numeric($_GET['from']))
	{
		$fromLimit = $_GET['from'] ;
	}
	
	// nombre total des etudiants
	$totalEtu = dbQuery('SELECT COUNT(`id-etudiant`) AS totalEtu
		FROM etudiant') ;
		
	$totalEtu = mysql_fetch_array($totalEtu) ;
	$totalEtu = $totalEtu['totalEtu'] ;
	
	
	// etudiants correspondant a cette page
	$allEtu = dbQuery('SELECT `id-etudiant`, nom, prenom
		FROM etudiant
		ORDER BY nom, prenom
		LIMIT '.$fromLimit.', '.$elementsCount) ;
		
	$etuCount = mysql_num_rows($allEtu) ;
	// si il n'y a aucun etudiant
	if ($etuCount == 0)
	{
		centeredInfoMessage(2, 2, "Aucun &eacute;tudiant pour le moment") ;
	}
	
	else
	{
		$formName = "defaultForm" ;	// valeur par defaut
		if (isset($_GET['f'])) { $formName = $_GET['f'] ; }
		
		$fieldName = "etudiant" ;	// valeur par defaut
		if (isset($_GET['t'])) { $fieldName = $_GET['t'] ; }
		
		// les pages pour acceder aux autres etudiants
		$pagesCount = $totalEtu / $etuCount ;
		
		print("\t\t\t<center><b>Pages</b><br>") ;
		for ($k = 0 ; $k < $pagesCount ; $k++)
		{
			if (($k * $elementsCount) == $fromLimit)
			{
				print("\t\t\t [ ".($k + 1)." ] \n") ;
			}
			
			else
			{
				print("\t\t\t <a href=\"help.php?w=alletudiants&f=$formName&t=$fieldName&from=".($k * $elementsCount)."\">".($k + 1)."</a> \n") ;
			}
		}		
		print("</center>\n") ;
	
		print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Nom</b></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
			
		for ($i = 0 ; $i < $etuCount ; $i++)
		{
			$details = mysql_fetch_array($allEtu) ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-etudiant']}</td><td width=\"200\" align=\"left\">{$details['nom']}, {$details['prenom']}</td>") ;
			print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-etudiant']}', '$formName', '$fieldName')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
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
** EOF alletudiants
*/
?>
