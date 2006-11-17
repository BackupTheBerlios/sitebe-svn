<?php
/*
** Fichier : allpages
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de l'affichage de toutes les pages du site
*/



// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{
	centeredInfoMessage(3, 3, "Aide : liste des pages") ;
	dbConnect() ;
	
	// on ne connait pas la section
	if (!isset($_POST['menuID']))
	{
		$menuList = dbQuery('SELECT `id-menu`, intitule
			FROM menu
			WHERE type = "sections"
			ORDER BY ordre') ;
			
		$menuCount = mysql_num_rows($menuList) ;
		
		if ($menuCount == 0)
		{
			centeredInfoMessage(2, 2, "Aucune page pour le moment") ;
			return ;
		}
		
		print("\t\t\t<center><b>Choisisez le menu de la page</b></center>\n") ;
		print("\t\t\t<center><form action=\"help.php?w=allpages\" method=\"post\">\n") ;
		print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		
		for ($i = 0 ; $i < $menuCount ; $i++)
		{
			$fMenuList = mysql_fetch_array($menuList) ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"menuID\" value=\"{$fMenuList['id-menu']}\" onClick=\"submit()\"><b>Page :</b> {$fMenuList['intitule']}</td>\n") ;
			print("\t\t\t\t</tr>\n") ;
		}
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Choisir\"></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t</table>\n") ;
		print("\t\t\t</form></center>\n") ;
	}
	
	else
	{
		$allPages = dbQuery('SELECT *
			FROM page
			WHERE `id-menu` = '.$_POST['menuID'].'
			ORDER BY titre') ;
		
		$pagesCount = mysql_num_rows($allPages) ;
		// si il n'y a aucune page
		if ($pagesCount == 0)
		{
			centeredInfoMessage(2, 2, "Aucune page pour le moment") ;
			return ;
		}
	
		
		print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Titre</b></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
			
		for ($i = 0 ; $i < $pagesCount ; $i++)
		{
			$details = mysql_fetch_array($allPages) ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-page']}</td><td width=\"300\" align=\"left\">{$details['titre']}</td>") ;
			print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-page']}', 'defaultForm', 'sectionPage')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
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
