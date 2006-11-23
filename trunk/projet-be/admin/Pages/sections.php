<?php
/*
** Fichier : sections
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu par l'index dont le role est d'afficher les sections
*/


/*
** VARIABLES : $_GET['pos'] sert a determiner la position de la page
*/


// on verifie toujours que cette page a ete appelee a partir de index
if (is_numeric(strpos($_SERVER['PHP_SELF'], "index.php")))
{
	// on est connecte a la base de donnees
	
	
	
	
	// mini haut
	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"0\" width=\"800\">\n") ;
	print("\t\t\t\t<tr>\n") ;
	
	// il faut verifier l'identifiant du menu sans quoi erreur
	if (!isset($_GET['id']) || !is_numeric($_GET['id']))
	{	
		print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Erreur : page inexistante</td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t</table>\n") ;
		return ;
	}
	
	
	$position = 1 ; // position par defaut de la page : on prend tjs la premiere
	if (isset($_GET['pos']) && is_numeric($_GET['pos']))
	{
		$position = $_GET['pos'] ;
	}
		
	$pageInfo = dbQuery('SELECT position, titre, `id-page`
		FROM page
		WHERE `id-menu` = '.$_GET['id'].' AND
			position = '.$position) ;
	
	// on verifie si les donnees sont correctes	
	$checkPage = mysql_num_rows($pageInfo) ;
	if ($checkPage < 1)
	{
		print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Erreur : page inexistante</td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t</table>\n") ;
		return ;
	}
	
	$pageDetails = mysql_fetch_array($pageInfo) ;
	
	// le menu doit etre correct on peut recuperer son intitule
	$menuInfo = dbQuery('SELECT intitule
		FROM menu
		WHERE `id-menu` = '.$_GET['id']) ;
	$menuDetails = mysql_fetch_array($menuInfo) ; 
	
	print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; <a href=\"index.php?p=sections&id={$_GET['id']}\">$menuDetails[intitule]</a> &raquo;&nbsp; $pageDetails[titre] </td>\n") ;
	print("\t\t\t\t</tr>\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td align=\"left\">\n") ;
	if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
	{
		print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=pages&a=add&menu={$_GET['id']}')\">ajouter une page &agrave; ce menu</a> ]\n") ;
		print("[ <a class=\"admin\" href=\"javascript:openAdmin('w=pages&a=mod&id={$pageDetails['id-page']}')\">modifier cette page</a> ]\n") ;
		print("[ <a class=\"admin\" href=\"javascript:openAdmin('w=pages&a=aff&id={$pageDetails['id-page']}')\">reaffecter cette page</a> ]<br>\n") ;
	}
	
	/*
	** PRINCIPE : afficher les liens vers toutes les pages du menu
	*/
	
	
	
	$pagesTitles = dbQuery('SELECT position, titre
		FROM page
		WHERE `id-menu` = '.$_GET['id']) ;
	
	$total = mysql_num_rows($pagesTitles) ;
	
	print("\t\t\t\t\t<br><br><h3>Aller &agrave;</h3>&nbsp;&nbsp;&nbsp;<select class=\"defaultSelect\" name=\"pageTitle\" onChange=\"gotoPage('{$_GET['id']}', this.value)\">") ;
	
	$pagesCounter = 1 ;
	while($pageTitlesF = mysql_fetch_array($pagesTitles))
	{
		$selected = "" ;
		if ($pageTitlesF['position'] == $position) { $selected = " selected" ; }
		print("<option value=\"{$pageTitlesF['position']}\"$selected>Page ".($pagesCounter++)." {$pageTitlesF['titre']}</option>") ;
	}
	print("</select>\n<br>") ;
	
	// affichage de la liste des sections appartenant a cette page
	
	$sectionsList = dbQuery('SELECT * 
		FROM section
		WHERE `id-page` = '.$pageDetails['id-page'].'
		ORDER BY ordre') ;
		
	$sectionsCount = mysql_num_rows($sectionsList) ;
	
	if ($sectionsCount == 0)
	{
		centeredInfoMessage(3, 3, "La page est encore vide") ;
	}
	
	else
	{
		for ($i = 0 ; $i < $sectionsCount ; $i++)
		{
			$sectionDetails = mysql_fetch_array($sectionsList) ;
			print("\t\t\t\t\t\t\t<br>") ;
			if (!empty($sectionDetails['titre']))
			{
				$titre = stripslashes($sectionDetails['titre']) ;
				print("<h2>$titre</h2>") ;
			}
			print("\t\t\t\t\t\t\t<br>\n") ;
			$contenu = stripslashes($sectionDetails['contenu']) ;
			$contenu = nl2br($contenu) ;
			print("\t\t\t\t\t\t\t$contenu<br><br>\n") ;
			if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
			{
				print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=sections&a=mod&id={$sectionDetails['id-section']}')\">modifier cette section</a> ]\n") ;
				print("[ <a class=\"admin\" href=\"javascript:openAdmin('w=sections&a=aff&id={$sectionDetails['id-section']}')\">reaffecter cette section</a> ]<br>\n") ;
			}
		}
	}
	
	// liens vers les autres pages
	print("\t\t\t\t\t<br>") ;
	if ($position > 1)
	{
		print("<a href=\"index.php?p=sections&id={$_GET['id']}&pos=".($position - 1)."\">&laquo; page pr&eacute;cedente</a>") ;
	}
	if ($position < $total)
	{
		print("<a href=\"index.php?p=sections&id={$_GET['id']}&pos=".($position + 1)." align=\"right\"\">page suivante &raquo;</a>") ;
	}	
	print("\n") 	;
		
	
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
** EOF sections
*/
?>
