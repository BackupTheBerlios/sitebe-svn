<?php
/*
** Fichier : sections
** Date de creation : 1/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des sections cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : section principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des sections : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=sections&a=add\">ajouter section</a> ] - ") ;
		print("[ <a href=\"admin.php?w=sections&a=mod\"> modifier section</a> ] - ") ;
		print("[ <a href=\"admin.php?w=sections&a=aff\"> reaffecter section</a> ] - ") ;
		print("[ <a href=\"admin.php?w=sections&a=del\"> supprimer section</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))

	
	
	
	// une action est precisee
	else
	{
		
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			// boite d'edition
			dbConnect() ;
			
			$pageExists = dbQuery('SELECT COUNT(`id-page`) AS pgN
				FROM page') ;
				
			$pageExists = mysql_fetch_array($pageExists) ;
			
			if ($pageExists['pgN'] == 0)
			{
				centeredInfoMessage(2, 2, "Il faut d'abord ajouter des pages") ;
				return ;
			}
			
			dbClose() ;
			require("Functions/edition.inc.php") ;
			
			centeredInfoMessage(3, 3, "Administration des sections : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=sections\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Titre de la section</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"sectionTitre\" size=\"40\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			printEditingTools("defaultForm","sectionContenu", 3) ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><b> Contenu de la section *</b></td>\n") ;
			print("\t\t\t\t</tr>\n") ;			
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"sectionContenu\"></textarea><br><br></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Page de lien *</b> (page &agrave; associer)</td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"sectionPage\" size=\"7\" maxlength=\"7\"></td><td align=\"right\" width=\"400\"><a href=\"javascript:openHelp('allpages')\"> voir toutes les pages</a></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Position de la section *</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"sectionPosition\">") ;
			print("<option>debut</option><option>fin</option>") ;
			print("</select> de la page</td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"sectionAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkSectionAdd('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des sections : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				// on ne connait pas encore la page
				if (!isset($_POST['pageID']))
				{
					// on ne connait pas non plus le menu
								
					// aucun menu
					if (!isset($_POST['menuID']))
					{
						dbConnect() ;
					
						$menuList = dbQuery('SELECT `id-menu`, intitule
						FROM menu
						WHERE type = "sections"
						ORDER BY ordre') ;
			
						$menuCount = mysql_num_rows($menuList) ;
		
						if ($menuCount == 0)
						{
							centeredInfoMessage(2, 2, "Aucune section pour le moment") ;
							return ;
						}
		
						print("\t\t\t<center><b>Choisisez le menu de la page de la section</b></center>\n") ;
						print("\t\t\t<center><form action=\"admin.php?w=sections&a=mod\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		
						for ($i = 0 ; $i < $menuCount ; $i++)
						{
							$fMenuList = mysql_fetch_array($menuList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"menuID\" value=\"{$fMenuList['id-menu']}\" onClick=\"submit()\"> {$fMenuList['intitule']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
		
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					
						dbClose() ;
					}
					
					// on connait le menu
					else
					{
						dbConnect() ;
						
						$pagesList = dbQuery('SELECT  titre, `id-page`
							FROM page
							WHERE `id-menu` = '.$_POST['menuID'].'
							ORDER BY titre') ;
					
						$pagesCount = mysql_num_rows($pagesList) ;
				
						// aucune page pour le moment
						if ($pagesCount == 0)
						{
							centeredInfoMessage(2, 2, "Element vide") ;
							return ;
						}
						print("\t\t\t<center><b>Choisissez la page de la section</b></center>\n") ;
						print("\t\t\t<center><form action=\"admin.php?w=sections&a=mod\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
						for ($i = 0 ; $i < $pagesCount ; $i++)
						{
							$fPagesList = mysql_fetch_array($pagesList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"pageID\" value=\"{$fPagesList['id-page']}\" onClick=\"submit()\"> {$fPagesList['titre']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
						
						dbClose() ;
					}
				}
				
				
				
				// on connait le menu
				else
				{
					// connexion a la base de donnees et recuperation des infos
					dbConnect() ;
					
					// infos sur la page
					$pageInfo = dbQuery('SELECT titre
						FROM page
						WHERE `id-page` = '.$_POST['pageID']) ;
					$pageInfo = mysql_fetch_array($pageInfo) ;
					
					print("\t\t\t<center><b>Page : {$pageInfo['titre']}</b></center>\n") ;
					
					$sectionList = dbQuery('SELECT `id-section`, ordre
						FROM section 
						WHERE `id-page` = '.$_POST['pageID'].'
						ORDER BY ordre') ;
					
					$sectionCount = mysql_num_rows($sectionList) ;
				
					// aucun section pour le moment
					if ($sectionCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
						return ;
					}
			
					print("\t\t\t<center><form action=\"admin.php?w=sections&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $sectionCount ; $i++)
					{
						$fsectionList = mysql_fetch_array($sectionList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fsectionList['id-section']}\" onClick=\"submit()\"><b>Section : position</b> {$fsectionList['ordre']}, <b>Id</b> {$fsectionList['id-section']} - <a href=\"javascript:openHelp('sections&id={$fsectionList['id-section']}')\">voir le contenu</a></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Modifier\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
					dbClose() ;
				}
			} // end of if not id
			
			// on connait l'element a modifier
			else
			{
				// connexion a la base et recuperation des infos
				dbConnect() ;
				// boite d'edition
				require("Functions/edition.inc.php") ;
				
				if (isset($_GET['id'])) { $eID = $_GET['id'] ; }
				else { $eID = $_POST['id'] ; }
				
				$sectionDetails = dbQuery('SELECT contenu, ordre, `id-page`, titre
					FROM section
					WHERE `id-section` = '.$eID) ;
					
				// on verifie si le resutat est correct
				$sectionExists = mysql_num_rows($sectionDetails) ;
				if ($sectionExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cette section") ;
					return ;
				}
				
				$sectionDetails = mysql_fetch_array($sectionDetails) ;
					
				$sectionCount = dbQuery('SELECT count(`id-section`) as number
					FROM section
					WHERE `id-page` = '.$sectionDetails['id-page']) ;
				$sectionCount = mysql_fetch_array($sectionCount) ;
				$sectionCount = $sectionCount['number'] ;
					
					
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=sections\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Titre de la section</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"sectionTitre\" size=\"40\" value=\"{$sectionDetails['titre']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				
				printEditingTools("defaultForm","sectionContenu", 3) ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\" valign=\"top\"><b> Contenu de la section *</b></td>\n") ;
				print("\t\t\t\t</tr>\n") ;			
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"sectionContenu\">{$sectionDetails['contenu']}</textarea><br><br></td>\n") ;
				print("\t\t\t\t</tr>\n") ;			
					
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Position de la section *</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"sectionPosition\">") ;
				for ($i = 1 ; $i <= $sectionCount ; $i++)
				{
					$selected = "" ;
					if ($sectionDetails['ordre'] == $i) { $selected = " selected" ; } 
					print("<option$selected>$i</option>") ;
				}
					print("</select></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"sectionMod\" value=\"true\"><input type=\"hidden\" name=\"sectionID\" value=\"$eID\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkSectionMod('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		// reaffectation d'un element
		elseif ($_GET['a'] == "aff")
		{
			centeredInfoMessage(3, 3, "Administration des sections : reaffectation") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				// on ne connait pas encore la page
				if (!isset($_POST['pageID']))
				{
					// on ne connait pas non plus le menu
								
					// aucun menu
					if (!isset($_POST['menuID']))
					{
						dbConnect() ;
					
						$menuList = dbQuery('SELECT `id-menu`, intitule
						FROM menu
						WHERE type = "sections"
						ORDER BY ordre') ;
			
						$menuCount = mysql_num_rows($menuList) ;
		
						if ($menuCount == 0)
						{
							centeredInfoMessage(2, 2, "Aucune section pour le moment") ;
							return ;
						}
		
						print("\t\t\t<center><b>Choisisez le menu de la page de la section</b></center>\n") ;
						print("\t\t\t<center><form action=\"admin.php?w=sections&a=aff\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		
						for ($i = 0 ; $i < $menuCount ; $i++)
						{
							$fMenuList = mysql_fetch_array($menuList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"menuID\" value=\"{$fMenuList['id-menu']}\" onClick=\"submit()\"> {$fMenuList['intitule']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
		
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					
						dbClose() ;
					}
					
					// on connait le menu
					else
					{
						dbConnect() ;
						
						$pagesList = dbQuery('SELECT  titre, `id-page`
							FROM page
							WHERE `id-menu` = '.$_POST['menuID'].'
							ORDER BY titre') ;
					
						$pagesCount = mysql_num_rows($pagesList) ;
				
						// aucune page pour le moment
						if ($pagesCount == 0)
						{
							centeredInfoMessage(2, 2, "Element vide") ;
							return ;
						}
						print("\t\t\t<center><b>Choisissez la page de la section</b></center>\n") ;
						print("\t\t\t<center><form action=\"admin.php?w=sections&a=aff\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
						for ($i = 0 ; $i < $pagesCount ; $i++)
						{
							$fPagesList = mysql_fetch_array($pagesList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"pageID\" value=\"{$fPagesList['id-page']}\" onClick=\"submit()\"> {$fPagesList['titre']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
						
						dbClose() ;
					}
				}
				
				
				
				// on connait la page
				else
				{
					// connexion a la base de donnees et recuperation des infos
					dbConnect() ;
					
					// infos sur la page
					$pageInfo = dbQuery('SELECT titre
						FROM page
						WHERE `id-page` = '.$_POST['pageID']) ;
					$pageInfo = mysql_fetch_array($pageInfo) ;
					
					print("\t\t\t<center><b>Page : {$pageInfo['titre']}</b></center>\n") ;
					
					$sectionList = dbQuery('SELECT `id-section`, ordre
						FROM section 
						WHERE `id-page` = '.$_POST['pageID'].'
						ORDER BY ordre') ;
					
					$sectionCount = mysql_num_rows($sectionList) ;
				
					// aucun section pour le moment
					if ($sectionCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
						return ;
					}
			
					print("\t\t\t<center><form action=\"admin.php?w=sections&a=aff\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $sectionCount ; $i++)
					{
						$fsectionList = mysql_fetch_array($sectionList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fsectionList['id-section']}\" onClick=\"submit()\"><b>Section : position</b> {$fsectionList['ordre']}, <b>Id</b> {$fsectionList['id-section']} - <a href=\"javascript:openHelp('sections&id={$fsectionList['id-section']}')\">voir le contenu</a></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionMod\" value=\"Modifier\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
					dbClose() ;
				}
			} // end of if not id
			
			// on connait l'element a modifier
			else
			{
				if (isset($_GET['id'])) { $eID = $_GET['id'] ; }
				else { $eID = $_POST['id'] ; }
				
				// connexion a la base et recuperation des infos
				dbConnect() ;
				
				$sectionDetails = dbQuery('SELECT `id-page`
					FROM section
					WHERE `id-section` = '.$eID) ;
					
				// on verifie si le resutat est correct
				$sectionExists = mysql_num_rows($sectionDetails) ;
				if ($sectionExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cette section") ;
					return ;
				}
				
				$sectionDetails = mysql_fetch_array($sectionDetails) ;					
					
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=sections\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
										
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Page de lien </b>(page &agrave; associer)</td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"sectionPage\" size=\"7\" maxlength=\"7\" value=\"{$sectionDetails['id-page']}\"></td><td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('allpages')\"> voir toutes les pages</a></td>\n") ;
				print("\t\t\t\t</tr>\n") ;					
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"sectionAff\" value=\"true\"><input type=\"hidden\" name=\"sectionID\" value=\"$eID\"><input class=\"defaultButton\" type=\"button\" name=\"affButton\" value=\"Modifier\" onClick=\"checkSectionAff('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}
		} // end of if aff
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des sections : suppression") ;
			
			
			// si aucune page n'est definie alors on affiche les pages
			if (!isset($_POST['pageID']))
			{
			
				// aucune menu
				if (!isset($_POST['menuID']))
				{
					dbConnect() ;
					
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
					print("\t\t\t<center><form action=\"admin.php?w=sections&a=del\" method=\"post\">\n") ;
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
					
					dbClose() ;
				}
			
				else
				{
					dbConnect() ;
					
					print("\t\t\t<center><b>Choisissez une page</b></center>\n") ;
					$sectionList = dbQuery('SELECT  titre, `id-page`
						FROM page
						WHERE `id-menu` = '.$_POST['menuID'].'
						ORDER BY titre') ;
					
					$sectionCount = mysql_num_rows($sectionList) ;
				
					// aucun section pour le moment
					if ($sectionCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
						return ;
					}
			
					print("\t\t\t<center><form action=\"admin.php?w=sections&a=del\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $sectionCount ; $i++)
					{
						$fsectionList = mysql_fetch_array($sectionList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"pageID\" value=\"{$fsectionList['id-page']}\" onClick=\"submit()\"><b>Page :</b> {$fsectionList['titre']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionDel\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
					dbClose() ;
				}
			}
			
			// une page est definie, on affiche toutes les sections de cette page
			else
			{
				dbConnect() ;
				// infos sur la page
				$pageInfo = dbQuery('SELECT titre
					FROM page
					WHERE `id-page` = '.$_POST['pageID']) ;
				$pageInfo = mysql_fetch_array($pageInfo) ;
				
				print("\t\t\t<center><b>Page : {$pageInfo['titre']}</b></center>\n") ;
				
				$sectionList = dbQuery('SELECT `id-section`, ordre
					FROM section 
					WHERE `id-page` = '.$_POST['pageID'].'
					ORDER BY ordre') ;
					
				$sectionCount = mysql_num_rows($sectionList) ;
				
				// aucun section pour le moment
				if ($sectionCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
					return ;
				}
			
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=sections\" method=\"post\" onSubmit=\"return checkItemsToDelete($sectionCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $sectionCount ; $i++)
				{
					$fsectionList = mysql_fetch_array($sectionList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fsectionList['id-section']}\"><b>Section : position</b> {$fsectionList['ordre']}, <b>Id</b> {$fsectionList['id-section']} - <a href=\"javascript:openHelp('sections&id={$fsectionList['id-section']}')\">voir le contenu</a></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"sectionDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration du section : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=sections\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF sections
*/
?>
