<?php
/*
** Fichier : controles
** Date de creation : 03/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des types de controle
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : controles
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des controles : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=controles&a=add\">ajouter un type de controle</a> ] - ") ;
		print("[ <a href=\"admin.php?w=controles&a=mod\"> modifier un type de controle</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=controles&a=del\"> supprimer un type de controle</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	// une action est precisee
	else
	{
	
		// ajout d'un type
		if ($_GET['a'] == "add")
		{
			centeredInfoMessage(3, 3, "Administration des controles : ajout") ;
			print("\t\t\t<center><form name=\"controleForm\" action=\"database.php?w=controles\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> type de controle * </td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"typeControle\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"controleAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkControle('controleForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
		} // end of if add
		
				
		// modification d'un type
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des controles : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				$controleList = dbQuery('SELECT type
					FROM controle
					ORDER BY type') ;
					
				$controleCount = mysql_num_rows($controleList) ;
				
				// aucun type pour le moment
				if ($controleCount == 0)
				{
					centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
				}
				
				else
				{
					print("\t\t\t<center><form action=\"admin.php?w=controles&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $controleCount ; $i++)
					{
						$fControleList = mysql_fetch_array($controleList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fControleList['type']}\" onClick=\"submit()\"> {$fControleList['type']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input type=\"hidden\" name=\"controleMod\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkControle('controleForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
				dbClose() ;
			} // end of if not id
			
			// on connait le type a modifier
			else
			{
				// connexion a la base et recuperation des infos
				dbConnect() ;
				
				$controleDetails = dbQuery('SELECT type
					FROM controle
					WHERE `type` = "'.$_POST['id'].'"') ;
					
				// on verifie si le resutat est correct
				$controleExists = mysql_num_rows($controleDetails) ;
				if ($controleExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond a ce type") ;
				}
				
				else
				{
					$controleCount = dbQuery('SELECT count(`type`) as number
						FROM controle') ;
					$controleCount = mysql_fetch_array($controleCount) ;
					$controleCount = $controleCount['number'] ;
					
					$controleDetails = mysql_fetch_array($controleDetails) ;
					print("\t\t\t<center><form name=\"controleForm\" action=\"database.php?w=controles\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> type de controle : * </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"typeControle\" size=\"25\" value=\"{$controleDetails['type']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"controleID\" value=\"{$_POST['id']}\"><input type=\"hidden\" name=\"controleMod\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkControle('controleForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
	
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
				
				dbClose() ;
			}
		
		} // end of if mod
		
		
		
		
		// suppression d'un type
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des controles : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$controleList = dbQuery('SELECT type
				FROM controle
				ORDER BY type') ;
					
			$controleCount = mysql_num_rows($controleList) ;
				
			// aucun type pour le moment
			if ($controleCount == 0)
			{
				centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
			}
			
			else
			{
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=controles\" method=\"post\" onSubmit=\"return checkItemsToDelete($controleCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $controleCount ; $i++)
				{
					$fControleList = mysql_fetch_array($controleList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fControleList['type']}\"> {$fControleList['type']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"controleDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des controles : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=controles\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF controle
*/
?>
