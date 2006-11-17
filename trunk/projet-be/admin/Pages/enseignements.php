<?php
/*
** Fichier : enseignements
** Date de creation : 10/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu par l'index dont le role est d'afficher les differents enseignements
*/



/*
** VARIABLES : $dip pour un diplome &mod pour details d'un module // view pour ce qu on veut voir : evaluations, accueil...
*/



// on verifie toujours que cette page a ete appelee a partir de index
if (is_numeric(strpos($_SERVER['PHP_SELF'], "index.php")))
{
	// on est connecte a la base de donnees
	// mini haut
	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"0\" width=\"800\">\n") ;
	print("\t\t\t\t<tr>\n") ;
	
	
	$mode = 0 ;	 // pour determiner l'action a effectuer 0 par defaut pour la liste des modules
	// on ne connait pas encore de diplome
	if (!isset($_GET['dip']) || !is_numeric($_GET['dip']))
	{
		print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Enseignements &raquo;&nbsp; Choix du dipl&ocirc;me</td>\n") ;
	}
	
	// un choix est defini
	else
	{
		if (isset($_GET['view']) && ($_GET['view'] == 1))
		{
			$mode = 1 ;
			print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; <a href=\"index.php?p=enseignements\">Enseignements</a> &raquo;&nbsp; Liste des modules et mati&egrave;res</td>\n") ;
		} 
		
		elseif (isset($_GET['view']) && ($_GET['view'] == 2))
		{
			$mode = 2 ;
			print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; <a href=\"index.php?p=enseignements\">Enseignements</a> &raquo;&nbsp; Descriptions détaillées des modules</td>\n") ;
		}
		
		elseif (isset($_GET['view']) && ($_GET['view'] == 3))
		{
			$mode = 3 ;
			print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; <a href=\"index.php?p=enseignements\">Enseignements</a> &raquo;&nbsp; Evaluations des modules et mati&egrave;res</td>\n") ;
		}

		else
		{
			$mode = 4 ;
			print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Enseignements &raquo;&nbsp; Choix incorrect</td>\n") ;
		}
	}

	print("\t\t\t\t</tr>\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td align=\"left\"><br>\n") ;


	if (isset($_SESSION['rootConnecte']) && isset($_SESSION['rootNavigation']))
	{
		print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=diplomes&a=add')\">ajouter un dipl&ocirc;me</a> ]\n") ;
		print(" [ <a class=\"admin\" href=\"javascript:openAdmin('w=modules&a=add')\">ajouter un module</a> ]\n") ;
		print(" [ <a class=\"admin\" href=\"javascript:openAdmin('w=matieres&a=add')\">ajouter une mati&egrave;e</a> ]<br>\n") ;
	}
	
	
	switch ($mode)
	{
		case (0) : // on affiche la liste des diplomes
		$dipList = dbQuery('SELECT *
			FROM diplome
			ORDER BY intitule') ;
			
		$dipCount = mysql_num_rows($dipList) ; 
		if ($dipCount == 0)
		{
			centeredInfoMessage(3, 3, "Aucun dipl&ocirc;me dans la base de donn&eacute;es") ;
			return ;
		}
		
		for ($i = 0 ; $i < $dipCount ; $i++)
		{
			$dipDetails = mysql_fetch_array($dipList) ;
			print("\t\t\t\t\t\t<b>$dipDetails[intitule]</b>") ;			
			print(" &raquo; voir les <a href=\"index.php?p=enseignements&dip={$dipDetails['id-diplome']}&view=1\">modules et matières</a>") ;
			print(" &raquo; voir les <a href=\"index.php?p=enseignements&dip={$dipDetails['id-diplome']}&view=2\">informations d&eacute;taill&eacute;es</a>") ;
			print(" &raquo; voir les <a href=\"index.php?p=enseignements&dip={$dipDetails['id-diplome']}&view=3\">modalit&eacute;s de contr&ocirc;le</a><br>") ;
			
			$respList = dbQuery('SELECT E.nom, E.prenom, E.mail
				FROM `resp-diplome` R, enseignant E
				WHERE R.`id-enseignant` = E.`id-enseignant` AND
					R.`id-diplome` = '.$dipDetails['id-diplome'].'
				ORDER BY E.nom, E.prenom') ;
			$respCount = mysql_num_rows($respList) ;
			
			// on affiche des responsables si necessaire
			if($respCount > 0)
			{
				print("<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsable(s) &raquo; ") ;
				for ($j = 0 ; $j < $respCount ; $j++)
				{
					$respD = mysql_fetch_array($respList) ;
					print("$respD[nom] $respD[prenom] <a href=\"mailto:$respD[mail]\">$respD[mail]</a> &raquo; ") ;
				}
				print("<br>") ;
			}	
			print("<br>\n") ;
		}
			
		break ;
		// fin du switch 0
		
		
		// deuxieme cas : lise des modules et matieres
		case(1) :
		$modList = dbQuery('SELECT `id-module`, intitule
			FROM module
			WHERE `id-diplome` = '.$_GET['dip'].'
			ORDER BY intitule') ;
			
		$modCount = mysql_num_rows($modList) ;
		if ($modCount == 0)
		{
			centeredInfoMessage(3, 3, "Aucun module correspondant &agrave; ce dipl&ocirc;me dans la base de donn&eacute;es") ;
			return ;
		}
		// sinon on affiche notre table qui va bien
		print("\t\t\t\t\t\t<table class=\"dataTable\" cellpadding=\"0\" cellspacing=\"0\">\n") ;
	
		print("\t\t\t\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t\t\t<th align=\"left\">Modules et mati&egrave;res</th>") ;
		print("<th align=\"left\">Volumes horaires</th>") ;
		print("<th align=\"left\">Responsables</th>") ;
		
		print("\n") ;
		print("\t\t\t\t\t\t\t</tr>\n") ;
		
		for ($i = 0 ; $i < $modCount ; $i++)
		{			
			$modDetails = mysql_fetch_array($modList) ;
	
			print("\t\t\t\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">$modDetails[intitule]</td>") ;
			
			//nombre d'heures
			$hoursT = dbQuery('SELECT SUM(`nbre-heures`) AS heures
				FROM matiere
				WHERE `id-module` = '.$modDetails['id-module']) ;
			$hoursT = mysql_fetch_array($hoursT) ;
			empty ($hoursT['heures']) ? $fH = 0 : $fH =  $hoursT['heures'] ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">$fH heures</td>") ;
			
			// responsable
			$resp = dbQuery('SELECT E.nom, E.prenom
				FROM enseignant E, `resp-module` R
				WHERE E.`id-enseignant` = R.`id-enseignant` AND
					R.`id-module` = '.$modDetails['id-module'].'
				ORDER BY E.nom, E.prenom') ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">") ;
			while ($respF = mysql_fetch_array($resp))
			{
				print($respF['nom']." ".$respF['prenom']."<br>") ;
			}
			print("</td>\n") ;
			
			// matieres
			$matList = dbQuery('SELECT *
				FROM matiere
				WHERE `id-module` = '.$modDetails['id-module'].'
				ORDER BY intitule') ;
			$matCount = mysql_num_rows($matList) ;
			for($j = 0 ; $j < $matCount ; $j++)
			{
				$j % 2 == 0 ? $style = "pairRow" : $style = "oddRow" ;
				$matF = mysql_fetch_array($matList) ;
				
				print("\t\t\t\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t\t\t<td class=\"$style\" align=\"left\">$matF[intitule]</td>") ;
				print("\t\t\t\t\t\t\t<td class=\"$style\" align=\"left\">{$matF['nbre-heures']} heures</td>") ;
				
				$respMat = dbQuery('SELECT E.nom, E.prenom
				FROM enseignant E, enseignement R
				WHERE E.`id-enseignant` = R.`id-enseignant` AND
					R.`id-matiere` = '.$matF['id-matiere'].'
				ORDER BY E.nom, E.prenom') ;
				
				print("\t\t\t\t\t\t\t<td class=\"$style\" align=\"left\">") ;
				while ($respM = mysql_fetch_array($respMat))
				{
					print($respM['nom']." ".$respM['prenom']."<br>") ;
				}
				print("</td>\n") ;
			}
			
			
			print("\t\t\t\t\t\t\t</tr>\n") ;
		}
		
		
			
		print("\t\t\t\t\t\t</table>\n") ;
		
		
		break ;
		// fin du deuxieme switch
		
		
		// troisieme choix : description
		case(2) :
		$modList = dbQuery('SELECT description, intitule
			FROM module
			WHERE `id-diplome` = '.$_GET['dip'].'
			ORDER BY intitule') ;
			
		$modCount = mysql_num_rows($modList) ;
		if ($modCount == 0)
		{
			centeredInfoMessage(3, 3, "Aucun module correspondant &agrave; ce dipl&ocirc;me dans la base de donn&eacute;es") ;
			return ;
		}
		
		for ($i = 0 ; $i < $modCount ; $i++)
		{
			$modDetails = mysql_fetch_array($modList) ;
			print("\t\t\t\t\t\t<h2>{$modDetails['intitule']}</h2><br><br>\n") ;
			if (!empty($modDetails['description']))
			{
				$details = stripslashes($modDetails['description']) ;
				$details = nl2br($details) ;
				print("\t\t\t\t\t$details<br><br>\n") ;
			}
			else
			{
				defaultInfoMessage("La description de ce module n'a pas encore &eacute;t&eacute; fournie<br><br>") ;
			}
		}
		break ;
		// fin du troisieme switch
		
		
		// enfin le dernier cas : controles
		case(3) :
		$modList = dbQuery('SELECT `id-module`, intitule
			FROM module
			WHERE `id-diplome` = '.$_GET['dip'].'
			ORDER BY intitule') ;
			
		$modCount = mysql_num_rows($modList) ;
		if ($modCount == 0)
		{
			centeredInfoMessage(3, 3, "Aucun module correspondant &agrave; ce dipl&ocirc;me dans la base de donn&eacute;es") ;
			return ;
		}
		
		
		// sinon on affiche notre table qui va bien
		print("\t\t\t\t\t\t<table class=\"dataTable\" cellpadding=\"0\" cellspacing=\"0\">\n") ;
		
		print("\t\t\t\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t\t\t<th align=\"left\">Modules et mati&egrave;res</th>") ;
		print("<th align=\"left\">Volumes horaires</th>") ;
		print("<th align=\"left\">Coefficents mati&egrave;res</th>") ;
		print("<th align=\"left\">Evaluations mati&egrave;res</th>") ;
		print("<th align=\"left\">Session1</th>") ;
		print("<th align=\"left\">Session2</th>") ;
		
		print("\n") ;
		print("\t\t\t\t\t\t\t</tr>\n") ;
		
		for ($i = 0 ; $i < $modCount ; $i++)
		{			
			$modDetails = mysql_fetch_array($modList) ;
			print("\t\t\t\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">{$modDetails['intitule']}</td>") ;
		
			//nombre d'heures
			$hoursT = dbQuery('SELECT SUM(`nbre-heures`) AS heures
				FROM matiere
				WHERE `id-module` = '.$modDetails['id-module']) ;
			$hoursT = mysql_fetch_array($hoursT) ;
			empty ($hoursT['heures']) ? $fH = 0 : $fH =  $hoursT['heures'] ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">$fH heures</td>") ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">&nbsp;</td>") ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">&nbsp;</td>") ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">&nbsp;</td>") ;
			print("\t\t\t\t\t\t\t<td class=\"impRow\" align=\"left\">&nbsp;</td>") ;
			print("\t\t\t\t\t\t\t</tr>\n") ;
			// matieres
			// matieres
			$matList = dbQuery('SELECT *
				FROM matiere
				WHERE `id-module` = '.$modDetails['id-module'].'
				ORDER BY intitule') ;
			$matCount = mysql_num_rows($matList) ;
			for($j = 0 ; $j < $matCount ; $j++)
			{
				$j % 2 == 0 ? $style = "pairRow" : $style = "oddRow" ;
				$matF = mysql_fetch_array($matList) ;
				
				print("\t\t\t\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t\t\t<td class=\"$style\" align=\"left\">$matF[intitule]</td>") ;
				print("<td class=\"$style\" align=\"left\">{$matF['nbre-heures']} heures</td>") ;
				print("<td class=\"$style\" align=\"left\">$matF[coefficient]</td>") ;
				print("<td class=\"$style\" align=\"left\">") ;
				// details des evaluations
				$evalD = dbQuery('SELECT type, nature
					FROM est_evalue
					WHERE `id-matiere` = '.$matF['id-matiere']) ;
				while ($evalF = mysql_fetch_array($evalD))
				{
					print("$evalF[type] ($evalF[nature])<br>") ;
				}
					
				print("</td>") ;
				
				print("<td class=\"$style\" align=\"left\">") ;
				$evalC1 = dbQuery('SELECT coefficient1
					FROM est_evalue
					WHERE `id-matiere` = '.$matF['id-matiere']) ;
				while ($evalFC1 = mysql_fetch_array($evalC1))
				{
					print("$evalFC1[coefficient1]<br>") ;
				}
				print("</td>") ;
				
				print("<td class=\"$style\" align=\"left\">") ;
				$evalC2 = dbQuery('SELECT coefficient2
					FROM est_evalue
					WHERE `id-matiere` = '.$matF['id-matiere']) ;
				while ($evalFC2 = mysql_fetch_array($evalC2))
				{
					print("$evalFC2[coefficient2]<br>") ;
				}
				print("</td>") ;
				
				print("\t\t\t\t\t\t\t</tr>\n") ;
			}
			
			
		}
		
		print("\t\t\t\t\t\t</table>\n") ;
		
		
		break ;
		// fin du dernier cas
		
		
		
		default :
		centeredErrorMessage(3, 3, "Choix incorrect") ;
	}
	
	if ($mode != 0)
	{
		print("\t\t\t\t\t\t<br><br><a href=\"index.php?p=enseignements\">&raquo; retour vers les enseignements</a><br>\n") ;
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
** EOF enseignements
*/
?>
