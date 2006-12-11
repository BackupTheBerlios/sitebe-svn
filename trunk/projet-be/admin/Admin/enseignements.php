<?php
/*
** Fichier : enseignements
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge d'attribuer des matieres aux enseignants
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des enseignements : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=enseignements&a=add\">ajouter enseignement</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=enseignements&a=del\"> supprimer enseignement</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))

    
	// une action est precisee
	else
	{

        dbConnect() ;
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			
			centeredInfoMessage(3, 3, "Administration des enseignements : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=enseignements\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
			$requette= 'SELECT `nom`,`prenom`,`id-enseignant` FROM `enseignant` order by nom, prenom';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix de l'enseignant :</b></td>");
			print("<td><select class='defaultInput' name=enseignant>");
			while($maligne=mysql_fetch_array($resultat))
			{
			   echo "<option value='".$maligne['id-enseignant']."'>".$maligne['nom']." ".$maligne['prenom']."</option>";
			}
			print("</select></td></tr>");
			$requette= 'SELECT matiere.intitule as intmat, module.intitule as intmod, matiere.`id-matiere` as idmat, diplome.intitule as intdip
						FROM matiere, module, diplome
						WHERE matiere.`id-module` = module.`id-module`
						AND module.`id-diplome` = diplome.`id-diplome`
						ORDER BY no_semestre, intmod, intmat';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix de la matiere :</B></td>");
			print("<td><select class='defaultInput' name=matiere>");
			while($maligne=mysql_fetch_array($resultat))
			{
			   echo "<option value='".$maligne['idmat']."'>".$maligne['intmat']." (".$maligne['intmod'].", ".$maligne['intdip'].")</option>";
			}
			print("</select></td></tr>");
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"600\" align=\"center\" colspan=\"3\"><br><input type=\"hidden\" name=\"enseignementAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkEnseignementAdd('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;			
        

            dbClose() ;
		} // end of if add
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des enseignements : suppression") ;
			
			
			dbConnect() ;
			
			// on ne connait pas encore l'enseignant dont on veut supprimer les matieres
			if (!isset($_POST['id']))
			{
				print("\t\t\t<center><b>Choisissez l'enseignant dont il faut supprimer des mati&egrave;res</b></center><br>\n") ;
				// liste des enseignants
				$ensList = dbQuery('SELECT DISTINCT E.nom, E.prenom, E.`id-enseignant`
					FROM enseignant E, enseignement X
						WHERE X.`id-enseignant` = E.`id-enseignant`
					ORDER BY E.nom, E.prenom') ;
						
				$ensCount = mysql_num_rows($ensList) ;
					
				// aucun enseignant
				if ($ensCount == 0)
				{
					centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
				}
					
				// affichage de la liste
				else
				{
					print("\t\t\t<center><form action=\"admin.php?w=enseignements&a=del\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $ensCount ; $i++)
					{
						$fEnseignantList = mysql_fetch_array($ensList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fEnseignantList['id-enseignant']}\" onClick=\"submit()\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"enseignementDel\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
				} 					
			}// end of if !isset($_POST['id'])
			
			else
			{
				$matList = dbQuery('SELECT M.intitule, M.`id-matiere`, D.`intitule` AS diplome, X.`intitule` AS module
					FROM matiere M, enseignement E, diplome D, module X
					WHERE E.`id-matiere` = M.`id-matiere` AND
						M.`id-module` = X.`id-module` AND
						X.`id-diplome` = D.`id-diplome` AND
						E.`id-enseignant` = '.$_POST['id'].'
					ORDER BY D.intitule, X.intitule, M.intitule') ;
					
				$matCount = mysql_num_rows($matList) ;
				
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=enseignements\" method=\"post\" onSubmit=\"return checkItemsToDelete($matCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $matCount ; $i++)
				{
					$fMatList = mysql_fetch_array($matList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"600\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fMatList['id-matiere']}\"> {$fMatList['intitule']} ({$fMatList['module']}, {$fMatList['diplome']})</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"600\" align=\"left\"><br><input type=\"hidden\" name=\"enseignant\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"submit\" name=\"enseignementDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des enseignements : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=enseignements\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF enseignements
*/
?>
