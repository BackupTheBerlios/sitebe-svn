<?php
/*
** Fichier : menu
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion du menu cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration du menu : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=menu&a=add\">ajouter &eacute;l&eacute;ment</a> ] - ") ;
		print("[ <a href=\"admin.php?w=menu&a=mod\"> modifier &eacute;l&eacute;ment</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=menu&a=del\"> supprimer &eacute;l&eacute;ment</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{
	
		// differents types de menus utile pour l'ajout et la modification
		// tous les types de menus (modules) sont presents dans le repertoire Pages
		$pageDir = opendir("Pages") ;
		while ($fileName = readdir($pageDir))
		{
			if (preg_match("/.*\.php/", $fileName))
			{
				$fileParts = explode(".", $fileName) ;
				$menuTypes[] = $fileParts[0] ;
			}
		}
	
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			// connexion a la bd pour determiner le nombre de menus deja existants
			dbConnect() ;
			$menuCount = dbQuery('SELECT count(`id-menu`) as number FROM menu') ;
				
			// pas tres elegant mais ...
			$menuCount = mysql_fetch_array($menuCount) ;
			$menuCount = $menuCount['number'] + 1 ; // ex : si 3 elements on peut ajouter a la 4e place
			
			$menuSub = dbQuery('SELECT `id-menu`, intitule FROM menu WHERE id_pmenu=0');
            
			centeredInfoMessage(3, 3, "Administration du menu : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=menu\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Intitul&eacute; du menu </b>(texte qui apparaitra dans la barre des menus)*</td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"menuIntitule\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Sous-menu </b>(définit si le menu comme sous-menu de)</td><td width=\"300\" align=\"left\"><select class=\"defaultInput\" name=\"menuSub\">") ;
			print("<option value='0'>Racine</option>");
            
            while($row = mysql_fetch_array($menuSub))
            {
                print("<option value='{$row['id-menu']}'>{$row['intitule']}</option>");
            }
			print("</select></td>") ;
			print("\t\t\t\t</tr>\n") ;
			
            print("\t\t\t\t<tr>\n") ;
            print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Description de l' &eacute;l&eacute;ment: </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"menuDescription\" size=\"25\" value=\"{$menuDetails['description']}\"></td>\n") ;
            print("\t\t\t\t</tr>\n") ;
            
            print("\t\t\t\t<tr>\n") ;
            print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> URL </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"menuPath\" size=\"25\" value=\"{$menuDetails['path']}\"></td>\n") ;
            print("\t\t\t\t</tr>\n") ;
                
                
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"menuAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkMenu('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
			dbClose() ;
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration du menu : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				$menuList = dbQuery('SELECT `id-menu`, intitule
					FROM menu
                    WHERE id_pmenu = 0
					ORDER BY ordre') ;
					
				$menuCount = mysql_num_rows($menuList) ;
				
				// aucun menu pour le moment
				if ($menuCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
					return ;
				}
				
				print("\t\t\t<center><form action=\"admin.php?w=menu&a=mod\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
				for ($i = 0 ; $i < $menuCount ; $i++)
				{
					$fMenuList = mysql_fetch_array($menuList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fMenuList['id-menu']}\" onClick=\"submit()\"> {$fMenuList['intitule']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"menuMod\" value=\"Modifier\"></td>\n") ;
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
				
				$menuDetails = dbQuery('SELECT intitule, description, ordre, id_pmenu, path
					FROM menu
					WHERE `id-menu` = '.$_POST['id']) ;
					
				// on verifie si le resutat est correct
				$menuExists = mysql_num_rows($menuDetails) ;
				if ($menuExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; ce menu") ;
					return ;
				}
				
                $menuDetails = mysql_fetch_array($menuDetails) ;
				$menuCount = dbQuery('SELECT count(`id-menu`) as number
					FROM menu WHERE id_pmenu = ' . (int)$menuDetails['id_pmenu']) ;
				$menuCount = mysql_fetch_array($menuCount) ;
				$menuCount = $menuCount['number'] ;
					
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=menu\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Intitul&eacute; du menu </b>(texte qui apparaitra dans la barre des menus)*</td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"menuIntitule\" size=\"25\" value=\"{$menuDetails['intitule']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			
                print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Description de l' &eacute;l&eacute;ment: </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"menuDescription\" size=\"25\" value=\"{$menuDetails['description']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
                
                print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> URL </b></td><td width=\"300\" align=\"left\"><input class=\"defaultInput\" name=\"menuPath\" size=\"25\" value=\"{$menuDetails['path']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
                
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Position du menu </b></td><td width=\"300\" align=\"left\"><select class=\"defaultInput\" name=\"menuPosition\">") ;
				for ($i = 1 ; $i <= $menuCount ; $i++)
				{
					$selected = "" ;
					if ($i == $menuDetails['ordre']) { $selected = " selected" ; }
					print("<option$selected> $i </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
                
                print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"menuMod\" value=\"true\"><input type=\"hidden\" name=\"menuID\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkMenu('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
                
                /* Modification posibilités de sous menu niveau 2 */
                // connexion a la base de donnees et recuperation des infos
				$menuList = dbQuery('SELECT `id-menu`, intitule
					FROM menu
                    WHERE id_pmenu = '.(int)$_POST['id'].'
					ORDER BY ordre') ;
					
				$menuCount = mysql_num_rows($menuList) ;
				
				// aucun menu pour le moment
				if ($menuCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
					return ;
				}
                
                print("\t\t\t<center><form action=\"admin.php?w=menu&a=mod\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
				for ($i = 0 ; $i < $menuCount ; $i++)
				{
					$fMenuList = mysql_fetch_array($menuList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fMenuList['id-menu']}\" onClick=\"submit()\"> {$fMenuList['intitule']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"menuMod\" value=\"Modifier\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
                /* Fin Modif */
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration du menu : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$menuList = dbQuery('SELECT `id-menu`, intitule
				FROM menu
				ORDER BY ordre') ;
					
			$menuCount = mysql_num_rows($menuList) ;
				
			// aucun menu pour le moment
			if ($menuCount == 0)
			{
				centeredInfoMessage(2, 2, "Element vide") ;
				return ;
			}
			
			print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=menu\" method=\"post\" onSubmit=\"return checkItemsToDelete($menuCount)\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
			for ($i = 0 ; $i < $menuCount ; $i++)
			{
				$fMenuList = mysql_fetch_array($menuList) ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fMenuList['id-menu']}\"> {$fMenuList['intitule']}</td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			}
				
				
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"menuDel\" value=\"Supprimer\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration du menu : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=menu\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie ou essaie d acceder directement
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;	
}


/*
** EOF menu
*/
?>
