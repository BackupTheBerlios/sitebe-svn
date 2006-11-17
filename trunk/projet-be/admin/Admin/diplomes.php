<?php
/*
** Fichier : diplomes
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des diplomes cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des dipl&ocirc;mes : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=diplomes&a=add\">ajouter dipl&ocirc;me</a> ] - ") ;
		print("[ <a href=\"admin.php?w=diplomes&a=mod\"> modifier dipl&ocirc;me</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=diplomes&a=del\"> supprimer dipl&ocirc;me</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{	
		
		// ajout d'un element
		if ($_GET['a'] == "add")
		{			
			centeredInfoMessage(3, 3, "Administration des dipl&ocirc;mes : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=diplomes\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Intitul&eacute; du dipl&ocirc;me </b>(par exemple <b>Licence 3</b>) *</td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"dipIntitule\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;			
			
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"dipAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkDiplome('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des dipl&ocirc;mes : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				$dipList = dbQuery('SELECT `id-diplome`, intitule
					FROM diplome
					ORDER BY intitule') ;
					
				$dipCount = mysql_num_rows($dipList) ;
				
				// aucun diplome pour le moment
				if ($dipCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
					return ;
				}
				
				print("\t\t\t<center><form action=\"admin.php?w=diplomes&a=mod\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
				for ($i = 0 ; $i < $dipCount ; $i++)
				{
					$fDipList = mysql_fetch_array($dipList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"> {$fDipList['intitule']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"dipMod\" value=\"Modifier\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			} // end of if not id
			
			// on connait l'element a modifier
			else
			{
				// connexion a la base et recuperation des infos
				dbConnect() ;
				
				$dipDetails = dbQuery('SELECT intitule
					FROM diplome
					WHERE `id-diplome` = '.$_POST['id']) ;
					
				// on verifie si le resutat est correct
				$dipExists = mysql_num_rows($dipDetails) ;
				if ($dipExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; ce menu") ;
					return ;
				}
				
				$dipDetails = mysql_fetch_array($dipDetails) ;
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=diplomes\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Intitul&eacute; du dipl&ocirc;me </b>(par exemple <b>Licence 3</b>)*</td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"dipIntitule\" size=\"25\" value=\"{$dipDetails['intitule']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;			
					
					
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"dipMod\" value=\"true\"><input type=\"hidden\" name=\"dipID\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkDiplome('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des dipl&ocirc;mes : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$dipList = dbQuery('SELECT `id-diplome`, intitule
				FROM diplome
				ORDER BY intitule') ;
					
			$dipCount = mysql_num_rows($dipList) ;
				
			// aucun menu pour le moment
			if ($dipCount == 0)
			{
				centeredInfoMessage(2, 2, "Element vide") ;
			}
			
			else
			{
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=diplomes\" method=\"post\" onSubmit=\"return checkItemsToDelete($dipCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $dipCount ; $i++)
				{
					$fDipList = mysql_fetch_array($dipList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fDipList['id-diplome']}\"> {$fDipList['intitule']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"dipDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des dipl&ocirc;mes : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=diplomes\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF menu
*/
?>
