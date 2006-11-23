<?php
/*
** Fichier : etudiants
** Date de creation : 4/06/2005
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier inclu charge de la gestion des etudiants cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des étudiants : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=etudiants&a=add\">ajouter &eacute;l&eacute;ment</a> ] - ") ;
		print("[ <a href=\"admin.php?w=etudiants&a=mod\"> modifier &eacute;l&eacute;ment</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=etudiants&a=del\"> supprimer &eacute;l&eacute;ment</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{		
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			// connexion a la bd pour determiner le nombre d'etudiants deja existants
			dbConnect() ;
				
			//récupération des diplomes de la base
			$dipsList = dbQuery('SELECT *
				FROM diplome
				ORDER BY intitule') ;
				
			// generation de la liste des annees (de 1995 jusqu'a l'annee en cours + 2)
			$firstYear = 1995 ;
			$lastYear = date("Y", mktime()) ;
			$saveYear = $lastYear ;
			$lastYear += 1 ;
			
			for ($i = $firstYear ; $i  <= $lastYear ; $i++)
			{
				$allYears[] = $i." - ".($i + 1) ;
			}
			$countDips = mysql_num_rows($dipsList) ;
			
			
			centeredInfoMessage(3, 3, "Administration des etudiants : ajout") ;
			print("\t\t\t<form name=\"defaultForm\" action=\"database.php?w=etudiants\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
			print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Numéro de carte étudiant * </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"numetu\" size=\"8\" maxlength=\"8\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
						
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Nom de l'étudiant *</b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"nometu\" size=\"30\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Prenom de l'étudiant *</b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"prenometu\" size=\"30\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> E-mail de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"mailetu\" size=\"30\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;  
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Login de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"loginetu\" size=\"30\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;  

			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Mot de passe de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"mdpetu\" size=\"30\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> CV de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"cvetu\" type=\"file\" size=\"30\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			
			if ($countDips > 0)
			{
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\" height=\"30\" valign=\"bottom\"><input type=\"checkbox\" name=\"inscrire\" checked><b> Inscrire cet &eacute;tudiant </b></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Dipl&ocirc;me *</b></td><td width=\"300\" align=\"left\"><select class=\"defaultInput\" name=\"diplome\">") ;
				for($i = 0 ; $i < $countDips ; $i++)
				{
					$dipsDetails = mysql_fetch_array($dipsList) ;
					print("<option> {$dipsDetails['intitule']} </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Promotion *</b></td><td width=\"300\" align=\"left\"><select class=\"defaultInput\" name=\"annee\">") ;
				foreach ($allYears as $singleDate)
				{
					print("<option> $singleDate </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			}				
				
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"etuAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkEtudiantAdd('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table></center>\n") ;
			print("\t\t\t</form>\n") ;
			
			dbClose() ;
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration du menu : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				// connexion a la base de donnees et recuperation des infos
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
				// aucun etudiant pour le moment
				if ($etuCount == 0)
				{
					centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
					return ;
				}
				
				// les pages pour acceder aux autres etudiants
				$pagesCount = $totalEtu / $elementsCount ;
		
				print("\t\t\t<center><b>Pages</b><br>") ;
				for ($k = 0 ; $k < $pagesCount ; $k++)
				{
					if (($k * $elementsCount) == $fromLimit)
					{
						print("\t\t\t [ ".($k + 1)." ] \n") ;
					}
			
					else
					{
						print("\t\t\t <a href=\"admin.php?w=etudiants&a=mod&from=".($k * $elementsCount)."\">".($k + 1)."</a> \n") ;
					}
				}
				
				print("\t\t\t<center><form name =\"defaultForm\" action=\"admin.php?w=etudiants&a=mod\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
				for ($i = 0 ; $i < $etuCount ; $i++)
				{
					$fEtuList = mysql_fetch_array($allEtu) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fEtuList['id-etudiant']}\" onclick=\"submit()\"> {$fEtuList['nom']} {$fEtuList['prenom']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"300\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"etuMod\" value=\"Modifier\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			} // end of if not id
			
			// on connait l'element a modifier
			else
			{
				if (isset($_POST['id'])) { $eID = $_POST['id'] ; }
				else { $eID = $_GET['id'] ; }
			
				// connexion a la base et recuperation des infos relatif a l'étudiant
				dbConnect() ;
				
				$etuDetails = dbQuery('SELECT *
					FROM etudiant
					WHERE `id-etudiant` = '.$eID ) ;
												
				// on verifie si le resutat est correct
				$etuExists = mysql_num_rows($etuDetails) ;
				if ($etuExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cet étudiant ") ;
					return ;
				}
				
				$fetuDetails = mysql_fetch_array($etuDetails) ;
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=etudiants\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Nom de l'étudiant *</b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"nometu\" size=\"30\" value= \"{$fetuDetails['nom']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Prénom de l'étudiant *</b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"prenometu\"size=\"30\" value=\"{$fetuDetails['prenom']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;

				print("</select></td>") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> E-mail de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"mailetu\"size=\"30\" value=\"{$fetuDetails['email']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;

                print("</select></td>") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Login de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"loginetu\"size=\"30\" value=\"{$fetuDetails['login']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;

				print("</select></td>") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Mot de passe de l'étudiant </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"mdpetu\"size=\"30\" value=\"{$fetuDetails['mdp']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
		
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> CV de l'etudiant </b></td><td width=\"300\" align=\"left\"><input type=\"file\" class=\"defaultInput\" name=\"cvetu\" size=\"30\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;	
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\" colspan=\"2\"><input type=\"checkbox\" class=\"defaultInput\" name=\"rmCV\"><b>Remplacer le CV</b></td>\n") ;
				print("\t\t\t\t</tr>\n") ;				
					
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"etuMod\" value=\"true\"><input type=\"hidden\" name=\"etuID\" value=\"$eID\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkEtudiantMod('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des étudiants : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
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
				
			// aucun etudiant pour le moment
			if ($etuCount == 0)
			{
				centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
				return ;
			}
			
			
			// les pages pour acceder aux autres etudiants
			$pagesCount = $totalEtu / $elementsCount ;
		
			print("\t\t\t<center><b>Pages</b><br>") ;
			for ($k = 0 ; $k < $pagesCount ; $k++)
			{
				if (($k * $elementsCount) == $fromLimit)
				{
					print("\t\t\t [ ".($k + 1)." ] \n") ;
				}
			
				else
				{
					print("\t\t\t <a href=\"admin.php?w=etudiants&a=del&from=".($k * $elementsCount)."\">".($k + 1)."</a> \n") ;
				}
			}
			
			print("\t\t\t<center><form action=\"database.php?w=etudiants\" method=\"post\" onSubmit=\"return checkItemsToDelete($etuCount)\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
			for ($i = 0 ; $i < $etuCount ; $i++)
			{
				$fetuList = mysql_fetch_array($allEtu) ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"300\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fetuList['id-etudiant']}\"> {$fetuList['nom']} {$fetuList['prenom']}</td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			}
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"300\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"etuDel\" value=\"Supprimer\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des etudiants : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=etudiants\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF etudiants
*/
?>
