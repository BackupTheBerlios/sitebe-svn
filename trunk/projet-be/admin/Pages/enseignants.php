<?php
/*
** Fichier : enseignants
** Date de creation : 08/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu par l'index dont le role est d'afficher les enseignants de l'iup
*/

// on verifie toujours que cette page a ete appelee a partir de index
if (is_numeric(strpos($_SERVER['PHP_SELF'], "index.php")))
{
	// on est connecte a la base de donnees
	// mini haut
	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"0\" width=\"800\">\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Enseignants</td>\n") ;
	print("\t\t\t\t</tr>\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td align=\"left\">\n") ;
	if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
	{
		print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=enseignants&a=add')\">ajouter enseignant</a> ]<br>\n") ;
	}
	
	/*
	** PRINCIPE : afficher les enseignants par paquets de 30, le debut est defini dans
	**		la variable $_GET['from'] 0 par defaut
	*/
	
	$startCount = 0 ;
	if (isset($_GET['from']) && is_numeric($_GET['from']))
	{
		$startCount = $_GET['from'] ;
	}
	
	// le nombre total d'enseignants
	$totalEns = dbQuery('SELECT COUNT(`id-enseignant`) AS total
		FROM enseignant') ;
		
	$totalEns = mysql_fetch_array($totalEns) ;
	// si il n'y a aucun enseignant
	if ($totalEns['total'] == 0)
	{
		centeredInfoMessage(2, 2, "Aucun enseignant pour le moment") ;
		return ;
	}
	
	// liste des enseignants
	$ensList = dbQuery('SELECT *
		FROM enseignant
		ORDER BY nom, prenom
		LIMIT '.$startCount.', 30') ;
		
	$ensNumber = mysql_num_rows($ensList) ;
	
	$pagesCount = $ensNumber / $totalEns['total'] ;
	
		
	print("\t\t\t\t\t<br><h3>Pages</h3> ") ;
	for ($k = 0 ; $k < $pagesCount ; $k++)
	{
		if (($k * 30) == $startCount)
		{
			print(" [ ".($k + 1)." ] \n") ;
		}
		
		else
		{
			print(" <a href=\"index.php?p=enseignants&from=".($k * 30)."\">".($k + 1)."</a> \n") ;
		}
	}		
	print("\n<br><br>") ;
	
	// affichage de la liste des enseignants
	print("\t\t\t\t\t\t<table class=\"dataTable\" cellpadding=\"0\" cellspacing=\"0\">\n") ;
	
	print("\t\t\t\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t\t\t<th align=\"left\" width=\"100\">Nom</th>") ;
	print("<th align=\"left\" width=\"150\">Pr&eacute;nom</th>") ;
	print("<th align=\"left\" width=\"150\">Mail</th>") ;
	if (isset($_SESSION['connecte']) && isset($_SESSION['rootNavigation']))
	{
		print("<th align=\"left\">Administration</th>") ;
	}
	print("\n") ;
	print("\t\t\t\t\t\t\t</tr>\n") ;
	
	for ($i = 0 ; $i < $ensNumber ; $i++)
	{
		$i % 2 == 0 ? $style = "pairRow" : $style = "oddRow" ;
		$ensDetails = mysql_fetch_array($ensList) ;
	
		print("\t\t\t\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t\t\t<td class=\"$style\" align=\"left\">$ensDetails[nom]</td>") ;
		print("<td class=\"$style\" align=\"left\">$ensDetails[prenom]</td>") ;
		if (empty($ensDetails['mail']))
		{
			print("<td class=\"$style\" align=\"left\">&laquo; pas de mail</td>") ;
		}
		else
		{
			print("<td class=\"$style\" align=\"left\"><a href=\"mailto:$ensDetails[mail]\">&raquo; $ensDetails[mail]</a></td>") ;
		}
		
		// cas ou l'utilisateur est un admin
		if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
		{
			print("<td class=\"$style\" align=\"left\">[ <a class=\"admin\" href=\"javascript:openAdmin('w=enseignants&a=mod&id={$ensDetails['id-enseignant']}')\">modifier</a> ]</td>") ;
		}
		
		
		print("\n") ;
		print("\t\t\t\t\t\t\t</tr>\n") ;
		
	}
	
	print("\t\t\t\t\t\t</table>\n") ;
	
	
	// mini bas
	print("\t\t\t\t\t</td>\n") ;
	print("\t\t\t\t</tr>\n") ;
	print("\t\t\t</table>\n") ;
	
}




else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}



/*
** EOF enseignants
*/
?>
