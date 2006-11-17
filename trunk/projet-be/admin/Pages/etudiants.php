<?php
/*
** Fichier : etudiants
** Date de creation : 10/01/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu par l'index dont le role est d'afficher la liste des etudiants
*/


/*
** IMPORTANT : les etudiants d'une promotion si elle existe seront affiches en entier
*/

/*
** VARIABLES : $_GET[promo] est la promotion $_GET[year] est l'annee $_GET[dip] est le diplome
*/



// on verifie toujours que cette page a ete appelee a partir de index
if (is_numeric(strpos($_SERVER['PHP_SELF'], "index.php")))
{
	// on est connecte a la base de donnees
	// mini haut
	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"0\" width=\"800\">\n") ;
	print("\t\t\t\t<tr>\n") ;
	
	
	
	// determination de l'annee en cours (prendre en compte le mois)
	$thisYear = date("Y", mktime()) ;
	$thisMonth = date("m", mktime()) ;
	
	// si on est avant septembre on decremente l'annee
	if ($thisMonth < 8) { $thisYear-- ; }
	
	$finalYear = $thisYear." - ".($thisYear + 1) ;
	
	if (isset($_GET['year']) && (preg_match("/^[\d]{4} [\-]{1} [\d]{4}$/", $_GET['year']))) { $finalYear = $_GET['year'] ; }
	
	// variable permettant de determiner ce qu'il faut afficher
	$mode = 0 ; // on affiche l'accueil
	// si aucun diplome specifie
	if (!isset($_GET['dip']) || !is_numeric($_GET['dip']))
	{
		print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Diplomes $finalYear</td>\n") ;
	}
	
	// un diplome
	else
	{
		$mode = 1 ;
		
		$dipN = "" ;
		$dipInt = dbQuery('SELECT intitule
			FROM diplome
			WHERE `id-diplome` = '.$_GET['dip']) ;
		$dipInt = mysql_fetch_array($dipInt) ;
		if (!empty($dipInt['intitule'])) { $dipN = $dipInt['intitule'] ; } 
		
		print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Etudiants $dipN $finalYear </td>\n") ;
	}
	
	print("\t\t\t\t</tr>\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td align=\"left\">\n") ;
	
	// si on ne connait pas le diplome on affiche la page principale
	if ($mode == 0)
	{
		if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
		{
			print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=diplomes&a=add')\">ajouter dipl&ocirc;me</a> ]<br>\n") ;
		}
	
		// on affiche la liste de tous les diplomes a droite et les archives a gauche
	
		// liste des diplomes
		$dipList = dbQuery('SELECT DISTINCT D.`id-diplome`, D.intitule
			FROM inscrit I, diplome D
			WHERE I.`id-diplome` = D.`id-diplome` AND
				I.annee = "'.$finalYear.'"
			ORDER BY D.intitule') ;
			
		// archives
		$archiveList = dbQuery('SELECT DISTINCT annee
			FROM inscrit
			ORDER BY annee') ;
	
		
		print("\t\t\t\t\t\t<table cellpadding=\"0\" cellspacing=\"0\">\n") ;
		print("\t\t\t\t\t\t\t<tr>\n") ;
		
		// colonne des diplomes
		print("\t\t\t\t\t\t\t\t<td width=\"600\" align=\"left\"><br><h3>Choisissez un dipl&ocirc;me pour voir les &eacute;tudiants</h3><br><br>\n") ;
		while($dipDetails = mysql_fetch_array($dipList))
		{
			print("\t\t\t\t\t\t\t\tDipl&ocirc;me : <a href=\"index.php?p=etudiants&year={$finalYear}&dip={$dipDetails['id-diplome']}\">{$dipDetails['intitule']}</a><br>\n") ;
		}
		print("\t\t\t\t\t\t\t\t</td>\n") ;
		
		
		// colonne des archives
		print("\t\t\t\t\t\t\t\t<td width=\"200\" align=\"left\"><br><br><div class=\"grayZone\"><h3>Archives des promotions</h3><br><br>\n") ;
		while($archiveDetails = mysql_fetch_array($archiveList))
		{
			print("\t\t\t\t\t\t\t\t<a href=\"index.php?p=etudiants&year={$archiveDetails['annee']}\">{$archiveDetails['annee']}</a><br>\n") ;
		}
		print("\t\t\t\t\t\t\t\t</div></td>\n") ;
		
		
		
		print("\n") ;
		print("\t\t\t\t\t\t\t</tr>\n") ;
		print("\t\t\t\t\t\t</table>\n") ;
		
	}
	
	// on connait le diplome et l'annee
	else
	{
		$etuList = dbQuery('SELECT DISTINCT E.`id-etudiant`, E.nom, E.prenom, E.email, E.CV
			FROM inscrit I, etudiant E
			WHERE I.`id-etudiant` = E.`id-etudiant` AND
				I.annee = "'.$_GET['year'].'" AND
				I.`id-diplome` = '.$_GET['dip'].'
			ORDER BY E.nom, E.prenom') ;
			
		$etuCount = mysql_num_rows($etuList) ;
		
		if ($etuCount == 0)
		{
			centeredInfoMessage(3, 3, "Aucun &eacute;tudiant inscrit") ;
			return ;
		}
		
		// affichage de la liste des etudiants
		print("\t\t\t\t\t\t<br><br><table class=\"dataTable\" cellpadding=\"0\" cellspacing=\"0\">\n") ;
		
		print("\t\t\t\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t\t\t<th align=\"left\" width=\"100\">Nom</th>") ;
		print("<th align=\"left\" width=\"150\">Pr&eacute;nom</th>") ;
		print("<th align=\"left\" width=\"150\">Mail</th>") ;
		print("<th align=\"left\">CV</th>") ;
		if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
		{
			print("<th align=\"left\">Administration</th>") ;
		}
		print("\n") ;
		print("\t\t\t\t\t\t\t</tr>\n") ;
		for ($i = 0 ; $i < $etuCount ; $i++)
		{
			$i % 2 == 0 ? $style = "pairRow" : $style = "oddRow" ;
			$etuDetails = mysql_fetch_array($etuList) ;
	
			print("\t\t\t\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t\t\t<td class=\"$style\" align=\"left\">$etuDetails[nom]</td>") ;
			print("<td class=\"$style\" align=\"left\">$etuDetails[prenom]</td>") ;
			if (empty($etuDetails['email']))
			{
				print("<td class=\"$style\" align=\"left\">&laquo; pas de mail</td>") ;
			}
			else
			{
				print("<td class=\"$style\" align=\"left\"><a href=\"mailto:$etuDetails[email]\">&raquo; $etuDetails[email]</a></td>") ;
			}
			
			if (empty($etuDetails['CV']))
			{
				print("<td class=\"$style\" align=\"left\">&laquo; pas de CV</td>") ;
			}
			else
			{
				print("<td class=\"$style\" align=\"left\"><a href=\"Data/CV/$etuDetails[CV]\">&raquo; T&eacute;l&eacute;charger</a></td>") ;
			}
		
			// cas ou l'utilisateur est un admin
			if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
			{
				print("<td class=\"$style\" align=\"left\">[ <a class=\"admin\" href=\"javascript:openAdmin('w=etudiants&a=mod&id={$etuDetails['id-etudiant']}')\">modifier</a> ]</td>") ;
			}
		
		
			print("\n") ;
			print("\t\t\t\t\t\t\t</tr>\n") ;
		
		}
		print("\t\t\t\t\t\t</table>\n") ;		
	}
	if ($mode != 0)
	{
		print("\t\t\t\t\t\t<br><br><a href=\"index.php?p=etudiants&year=$finalYear\">&raquo; retour vers la liste des dipl&ocirc;mes $finalYear</a><br>\n") ;
	}
	
	
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
** EOF etudiants
*/
?>
