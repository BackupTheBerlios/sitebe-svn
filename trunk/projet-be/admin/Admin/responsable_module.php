<?php
/*
** Fichier : responsable_module
** Date de creation : 28/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de faire des responsables de modules
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des responsables de modules : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=responsable_module&a=add\">ajouter responsabilit&eacute;</a> ] - ") ;
		print("[ <a href=\"admin.php?w=responsable_module&a=mod\"> modifier responsabilit&eacute;</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=responsable_module&a=del\"> supprimer responsabilit&eacute;</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			dbConnect() ;
			centeredInfoMessage(3, 3, "Administration des responsables de modules : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=responsable_module\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
      $requette= 'SELECT `nom`,`prenom`,`id-enseignant` FROM `enseignant` order by nom';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix de l'enseignant :</b></td>");
			print("<td><select class='defaultInput' name='enseignant'>");
			while($maligne=mysql_fetch_array($resultat))
			{
			   echo "<option value='".$maligne['id-enseignant']."'>".$maligne['nom']."</option>";
      }
      print("</select></td></tr>");
		/*	print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de l'enseignant *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"enseignant\" size=\"7\" maxlength=\"7\"></td>") ;
			print("<td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('allenseignants')\"> voir les enseignants </a></td>\n") ;
			print("\t\t\t\t</tr>\n") ;*/
			
			
      $requette= 'SELECT `intitule`,`id-module` FROM `module` order by intitule';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix du module :</b></td>");
			print("<td><select class='defaultInput' name='module'>");
			while($maligne=mysql_fetch_array($resultat))
			{
			   echo "<option value='".$maligne['id-module']."'>".$maligne['intitule']."</option>";
      }
      print("</select></td></tr>");
		/*	print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant du module *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"module\" size=\"7\" maxlength=\"7\"></td>") ;
			print("<td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('allmodules&t=module')\"> voir les modules</a></td>\n") ;
			print("\t\t\t\t</tr>\n") ;*/
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"600\" align=\"center\" colspan=\"3\"><br><input type=\"hidden\" name=\"respModAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkRMAdd('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;			
			
            dbClose() ;
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des responsables de modules : modification") ;
			// on ne connait pas encore la matiere a modifier
			
			dbConnect() ;
			
			if (!isset($_POST['id']))
			{
				// on ne connait pas encore l'enseignant dont on veut modifier le module
				if (!isset($_POST['ensID']))
				{
					print("\t\t\t<center><b>Choisissez l'enseignant dont il faut modifier un module</b></center><br>\n") ;
					// liste des enseignants
					$ensList = dbQuery('SELECT DISTINCT E.nom, E.prenom, E.`id-enseignant`
						FROM enseignant E, `resp-module` X
						WHERE X.`id-enseignant` = E.`id-enseignant`
						ORDER BY E.nom, E.prenom') ;
						
					$ensCount = mysql_num_rows($ensList) ;
					
					// aucun enseignant
					if ($ensCount == 0)
					{
						centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
						return ;
					}
					
					// affichage de la liste
					print("\t\t\t<center><form action=\"admin.php?w=responsable_module&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $ensCount ; $i++)
					{
						$fEnseignantList = mysql_fetch_array($ensList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"ensID\" value=\"{$fEnseignantList['id-enseignant']}\" onClick=\"submit()\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respModMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
				} // end of if !isset($_POST['ensID'])
				
				// on connait l'enseignant, on affiche la liste de toutes les matieres qu'il enseigne
				else
				{
					print("\t\t\t<center><b>Choisissez le module &agrave; modifier</b></center><br>\n") ;
					// liste des matieres
					$modList = dbQuery('SELECT X.`id-module`, D.`intitule` AS diplome, X.`intitule` AS module
						FROM `resp-module` E, diplome D, module X
						WHERE E.`id-module` = X.`id-module` AND
							X.`id-diplome` = D.`id-diplome` AND
							E.`id-enseignant` = '.$_POST['ensID'].'
						ORDER BY D.intitule, X.intitule') ;
						
					$modCount = mysql_num_rows($modList) ;
					
					// aucun enseignant
					if ($modCount == 0)
					{
						centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
						return ;
					}
					
					print("\t\t\t<center><form action=\"admin.php?w=responsable_module&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $modCount ; $i++)
					{
						$fModList = mysql_fetch_array($modList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"600\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fModList['id-module']}\" onClick=\"submit()\"> {$fModList['module']} ({$fModList['diplome']})</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"600\" align=\"center\"><br><input type=\"hidden\" name=\"ensID\" value=\"{$_POST['ensID']}\"><input type=\"hidden\" name=\"enseignant\" value=\"{$_POST['ensID']}\"><input class=\"defaultButton\" type=\"submit\" name=\"respModMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				} // end of else !isset($_POST['ensID'])				
			}
			
			
			// on connait le module
			else
			{
				// info sur l'enseignant
				$ensInfo = dbQuery('SELECT nom, prenom
					FROM enseignant
					WHERE `id-enseignant` = '.$_POST['enseignant']) ;
				$ensInfo = mysql_fetch_array($ensInfo) ;
				
								
				
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=responsable_module\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Enseignant </b></td><td width=\"300\" align=\"left\" colspan=\"2\">{$ensInfo['nom']} {$ensInfo['prenom']}</td>") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de l'enseignant </b></td><td width=\"300\" align=\"left\" colspan=\"2\">{$_POST['enseignant']}</td>") ;
				print("\t\t\t\t</tr>\n") ;				

        $requette= 'SELECT `intitule`,`id-module` FROM `module` order by intitule';
  			$resultat=dbQuery($requette);
  			print("<tr><td><b>choix du module :</b></td>");
  			print("<td><select class='defaultInput' name='module'>");
  			while($maligne=mysql_fetch_array($resultat))
  			{
  			   echo "<option value='".$maligne['id-module']."'>".$maligne['intitule']."</option>";
        }
        print("</select></td></tr>");
  			
			/*	print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant du nouveau module *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"module\" size=\"7\" maxlength=\"7\" value=\"{$_POST['id']}\"></td>") ;
				print("<td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('allmodules&t=module')\"> voir les modules</a></td>\n") ;
				print("\t\t\t\t</tr>\n") ;*/
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"respModMod\" value=\"true\"><input type=\"hidden\" name=\"enseignant\"  value=\"{$_POST['enseignant']}\"><input type=\"hidden\" name=\"oldModule\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"submit\" name=\"buttonMod\" value=\"Modifier\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			
			dbClose() ;
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des responsables de module : suppression") ;
			
			
			dbConnect() ;
			
			// on ne connait pas encore l'enseignant dont on veut supprimer les matieres
			if (!isset($_POST['id']))
			{
				print("\t\t\t<center><b>Choisissez l'enseignant dont il faut supprimer des modules</b></center><br>\n") ;
				// liste des enseignants
				$ensList = dbQuery('SELECT DISTINCT E.nom, E.prenom, E.`id-enseignant`
					FROM enseignant E, `resp-module` X
						WHERE X.`id-enseignant` = E.`id-enseignant`
					ORDER BY E.nom, E.prenom') ;
						
				$ensCount = mysql_num_rows($ensList) ;
					
				// aucun enseignant
				if ($ensCount == 0)
				{
					centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
					return ;
				}
					
				print("\t\t\t<center><form action=\"admin.php?w=responsable_module&a=del\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
				for ($i = 0 ; $i < $ensCount ; $i++)
				{
					$fEnseignantList = mysql_fetch_array($ensList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fEnseignantList['id-enseignant']}\" onClick=\"submit()\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respModDel\" value=\"Choisir\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;	
			}// end of if !isset($_POST['id'])
			
			else
			{
				$modList = dbQuery('SELECT X.`id-module`, D.`intitule` AS diplome, X.`intitule` AS module
						FROM `resp-module` E, diplome D, module X
						WHERE E.`id-module` = X.`id-module` AND
							X.`id-diplome` = D.`id-diplome` AND
							E.`id-enseignant` = '.$_POST['id'].'
						ORDER BY D.intitule, X.intitule') ;
					
				$modCount = mysql_num_rows($modList) ;
				
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=responsable_module\" method=\"post\" onSubmit=\"return checkItemsToDelete($modCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $modCount ; $i++)
				{
					$fModList = mysql_fetch_array($modList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fModList['id-module']}\"> {$fModList['module']} ({$fModList['diplome']})</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input type=\"hidden\" name=\"enseignant\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"submit\" name=\"respModDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des responsables de modules : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=responsable_module\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF responsable_module
*/
?>
