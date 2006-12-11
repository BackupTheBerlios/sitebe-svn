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
			   echo "<option value='".$maligne['id-enseignant']."'>".$maligne['nom']." ".$maligne['prenom']."</option>";
			}
			print("</select></td></tr>");
					
					
			$requette= 'SELECT intitule,`id-module`, no_semestre FROM `module` order by no_semestre, intitule';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix du module :</b></td>");
			print("<td><select class='defaultInput' name='module'>");
			
			while($maligne=mysql_fetch_array($resultat))
			{
				echo "<option value='".$maligne['id-module']."'>s".$maligne['no_semestre']." - ".$maligne['intitule']."</option>";
			}
			print("</select></td></tr>");
			
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
			// on ne connait pas encore le module a modifier
			
			dbConnect() ;
			
			if (!isset($_POST['id']))
			{
				// on ne connait pas encore le diplome où se trouve le module que l'on veut modifier
				if (!isset($_POST['dipID']))
				{
					print("\t\t\t<center><b>Choisissez le diplome du module dont il faut modifier le responsable</b></center><br>\n") ;
					// liste des diplomes
					$dipList = dbQuery('SELECT DISTINCT `id-diplome`, intitule
						FROM diplome') ;
						
					$dipCount = mysql_num_rows($dipList) ;
					
					// aucun diplome
					if ($dipCount == 0)
					{
						centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
						return ;
					}
					
					// affichage de la liste
					print("\t\t\t<center><form action=\"admin.php?w=responsable_module&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $dipCount ; $i++)
					{
						$fDiplomeList = mysql_fetch_array($dipList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDiplomeList['id-diplome']}\" onClick=\"submit()\">{$fDiplomeList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respModMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
				} // end of if !isset($_POST['dipID'])
				
				// on connait le diplome, on affiche la liste de touts les modules ayant déjà un responsable
				else
				{
					print("\t\t\t<center><b>Choisissez le module &agrave; dont il faut modifier le responsable</b></center><br>\n") ;
					// liste des modules
					$modList = dbQuery('SELECT `id-module`, `intitule`, no_semestre
						FROM module
						WHERE `id-diplome` = '.$_POST['dipID'].'
						AND `id-responsable` <> 0
						ORDER BY no_semestre, intitule') ;
						
					$modCount = mysql_num_rows($modList) ;
					
					// aucun module
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
						print("\t\t\t\t\t<td width=\"600\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fModList['id-module']}\" onClick=\"submit()\"> s{$fModList['no_semestre']} - {$fModList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"600\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respModMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				} // end of else !isset($_POST['modID'])				
			}
			
			
			// on connait le module
			else
			{
				// info sur le module
				$modInfo = dbQuery('SELECT intitule, no_semestre, apogee, `id-responsable`
					FROM module
					WHERE `id-module` = '.$_POST['id']) ;
				$modInfo = mysql_fetch_array($modInfo) ;
				
				
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=responsable_module\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Module </b></td><td width=\"300\" align=\"left\" colspan=\"2\">{$modInfo['intitule']} (semestre {$modInfo['no_semestre']})</td>") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Code apogee du module </b></td><td width=\"300\" align=\"left\" colspan=\"2\">{$modInfo['apogee']}</td>") ;
				print("\t\t\t\t</tr>\n") ;				

				$requette= 'SELECT `id-enseignant`, nom, prenom FROM enseignant order by nom, prenom';
				$resultat=dbQuery($requette);
				print("<tr><td><b>choix du responsable :</b></td>");
				print("<td><select class='defaultInput' name='responsable'>");
				while($maligne=mysql_fetch_array($resultat))
				{
					if ($maligne['id-enseignant'] == $modInfo['id-responsable'])
					{
						echo "<option value='".$maligne['id-enseignant']."' selected>".$maligne['nom']." ".$maligne['prenom']."</option>";
					}
					else
					{
						echo "<option value='".$maligne['id-enseignant']."'>".$maligne['nom']." ".$maligne['prenom']."</option>";
					}
				}
				print("</select></td></tr>");
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"respModMod\" value=\"true\"><input type=\"hidden\" name=\"module\"  value=\"{$_POST['id']}\"><input type=\"hidden\" name=\"oldEns\" value=\"{$modInfo['id-responsable']}\"><input class=\"defaultButton\" type=\"submit\" name=\"buttonMod\" value=\"Modifier\"></td>\n") ;
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
			
			if (!isset($_POST['id']))
			{
				// on ne connait pas encore le diplome où se trouve le module dont on veut supprimer le responsable
				if (!isset($_POST['dipID']))
				{
					print("\t\t\t<center><b>Choisissez le diplome du module dont il faut supprimer le responsable</b></center><br>\n") ;
					// liste des diplomes
					$dipList = dbQuery('SELECT DISTINCT `id-diplome`, intitule
						FROM diplome') ;
						
					$dipCount = mysql_num_rows($dipList) ;
					
					// aucun diplome
					if ($dipCount == 0)
					{
						centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
						return ;
					}
					
					// affichage de la liste
					print("\t\t\t<center><form action=\"admin.php?w=responsable_module&a=del\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $dipCount ; $i++)
					{
						$fDiplomeList = mysql_fetch_array($dipList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDiplomeList['id-diplome']}\" onClick=\"submit()\">{$fDiplomeList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respModDel\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
				} // end of if !isset($_POST['dipID'])
				
				// on connait le diplome, on affiche la liste de touts les modules ayant déjà un responsable
				else
				{
					print("\t\t\t<center><b>Choisissez le module &agrave; dont il faut supprimer le responsable</b></center><br>\n") ;
					// liste des modules avec leur responsable
					$modList = dbQuery('SELECT `id-module`, `intitule`, no_semestre, nom, prenom
						FROM module, enseignant
						WHERE `id-diplome` = '.$_POST['dipID'].'
						AND `id-responsable` = `id-enseignant`
						ORDER BY no_semestre, intitule') ;
						
					$modCount = mysql_num_rows($modList) ;
					
					// aucun module
					if ($modCount == 0)
					{
						centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
						return ;
					}
					
					print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=responsable_module\" method=\"post\"\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $modCount ; $i++)
					{
						$fModList = mysql_fetch_array($modList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"600\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fModList['id-module']}\" onClick=\"submit()\"> {$fModList['intitule']} ({$fModList['nom']} {$fModList['prenom']})</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"600\" align=\"center\"><br><input type=\"hidden\" name=\"respModDel\" value=\"true\"><input class=\"defaultButton\" type=\"submit\" name=\"respModDel\" value=\"Supprimer\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				} // end of else !isset($_POST['modID'])				
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
