<?php
/*
** Fichier : enseignants
** Date de creation : 03/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion de la nature des examens cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : nature examens
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration de la nature des examens : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=natures&a=add\">ajouter une nature d'examen</a> ] - ") ;
		print("[ <a href=\"admin.php?w=natures&a=mod\"> modifier une nature d'examen</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=natures&a=del\"> supprimer une nature d'examen</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{
	
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			centeredInfoMessage(3, 3, "Administration de la nature des examens : ajout") ;
			print("\t\t\t<form name=\"natureForm\" action=\"database.php?w=natures\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> nature de l'examen *</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"natureExamen\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<br>\n") ; 
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><input  type=\"hidden\" name=\"natureAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkMenu('natureForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form>\n") ;
			
		} // end of if add
		
				
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration de la nature des examens : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				$natureList = dbQuery('SELECT `nature`
					FROM nature') ;
					
				$natureCount = mysql_num_rows($natureList) ;
				
				// aucune nature d'exam pr le moment
				if ($natureCount == 0)
				{
					centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
				}
				
				else
				{
					print("\t\t\t<center><form name=\"defaultForm\" action=\"admin.php?w=natures&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $natureCount ; $i++)
					{
						$fnatureList = mysql_fetch_array($natureList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fnatureList['nature']}\" onClick=\"submit()\"> {$fnatureList['nature']} </td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"hidden\" name=\"natureMod\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkMenu('defaultForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
				dbClose() ;
			} // end of if not id
			
			// on connait l'element a modifier
			else
			{
				// connexion a la base et recuperation des infos
				dbConnect() ;
				
				$natureDetails = dbQuery('SELECT  nature
					FROM nature
					WHERE `nature` = "'.$_POST['id'].'"') ;
					
				// on verifie si le resutat est correct
				$natureExists = mysql_num_rows($natureDetails) ;
				if ($natureExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cette nature d'examen") ;
				}
				
				else
				{
					$natureCount = dbQuery('SELECT count(`nature`) as number
						FROM nature') ;
					$natureCount = mysql_fetch_array($natureCount) ;
					$natureCount = $natureCount['number'] ;
					
					$natureDetails = mysql_fetch_array($natureDetails) ;
					print("\t\t\t<form name=\"natureForm\" action=\"database.php?w=natures\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> nature de l'examen *</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"natureExamen\" size=\"25\" value=\"{$natureDetails['nature']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					
					 
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"natureMod\" value=\"true\"><input type=\"hidden\" name=\"natureID\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkMenu('natureForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
	
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form>\n") ;
				}
				
				dbClose() ;
			}
		
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration de la nature des examens : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$natureList = dbQuery('SELECT `nature`
				FROM nature') ;
					
			$natureCount = mysql_num_rows($natureList) ;
				
			// aucune nature d'exam pour le moment
			if ($natureCount == 0)
			{
				centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
			}
			
			else
			{
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=natures\" method=\"post\" onSubmit=\"return checkItemsToDelete($natureCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $natureCount ; $i++)
				{
					$fnatureList = mysql_fetch_array($natureList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fnatureList['nature']}\"> {$fnatureList['nature']} </td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"natureDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration de la nature des examens : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=natures\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF enseignant
*/
?>
