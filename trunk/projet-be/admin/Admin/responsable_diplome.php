<?php
/*
** Fichier : responsable_diplome
** Date de creation : 28/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de faire des responsables de diplomes
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des responsables de dipl&ocirc;me : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=responsable_diplome&a=add\">ajouter responsabilit&eacute;</a> ] - ") ;
		print("[ <a href=\"admin.php?w=responsable_diplome&a=mod\"> modifier responsabilit&eacute;</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=responsable_diplome&a=del\"> supprimer responsabilit&eacute;</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			dbConnect();
            
			centeredInfoMessage(3, 3, "Administration des responsables de dipl&ocirc;mes : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=responsable_diplome\" method=\"post\">\n") ;
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
			print("\t\t\t\t</tr>\n") ;
			*/
			$requette= 'SELECT `intitule`,`id-diplome` FROM `diplome` order by intitule';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix du diplome :</b></td>");
			print("<td><select class='defaultInput' name='diplome'>");
			while($maligne=mysql_fetch_array($resultat))
			{
			   echo "<option value='".$maligne['id-diplome']."'>".$maligne['intitule']."</option>";
      }
		/*	print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant du diplome *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"diplome\" size=\"7\" maxlength=\"7\"></td>") ;
			print("<td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('alldiplomes&t=diplome')\"> voir les diplomes</a></td>\n") ;
			print("\t\t\t\t</tr>\n") ;*/
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"600\" align=\"center\" colspan=\"3\"><br><input type=\"hidden\" name=\"respDipAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"buttonAdd\" value=\"Ajouter\" onClick=\"checkRDAdd('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;			
			
            dbClose() ;
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des responsables de dipl&ocirc;mes : modification") ;
			// on ne connait pas encore la matiere a modifier
			
			dbConnect() ;
			
			if (!isset($_POST['id']))
			{
				// on ne connait pas encore l'enseignant dont on veut modifier le diplome
				if (!isset($_POST['ensID']))
				{
					print("\t\t\t<center><b>Choisissez l'enseignant dont il faut modifier un dipl&ocirc;me</b></center><br>\n") ;
					// liste des enseignants
					$ensList = dbQuery('SELECT DISTINCT E.nom, E.prenom, E.`id-enseignant`
						FROM enseignant E, `resp-diplome` X
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
					print("\t\t\t<center><form action=\"admin.php?w=responsable_diplome&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $ensCount ; $i++)
					{
						$fEnseignantList = mysql_fetch_array($ensList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"ensID\" value=\"{$fEnseignantList['id-enseignant']}\" onClick=\"submit()\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respDipMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;					
				} // end of if !isset($_POST['ensID'])
				
				// on connait l'enseignant, on affiche la liste de tous les diplomes dont il est responsable
				else
				{
					print("\t\t\t<center><b>Choisissez le dipl&ocirc;me &agrave; modifier</b></center><br>\n") ;
					// liste des matieres
					$dipList = dbQuery('SELECT D.`id-diplome`, D.`intitule` AS diplome
						FROM `resp-diplome` E, diplome D
						WHERE E.`id-diplome` = D.`id-diplome` AND
							E.`id-enseignant` = '.$_POST['ensID'].'
						ORDER BY D.intitule') ;
						
					$dipCount = mysql_num_rows($dipList) ;
					
					// aucun enseignant
					if ($dipCount == 0)
					{
						centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
						return ;
					}
					
					// affichage de la liste
					print("\t\t\t<center><form action=\"admin.php?w=responsable_diplome&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $dipCount ; $i++)
					{
						$fDipList = mysql_fetch_array($dipList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"400\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\">{$fDipList['diplome']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><br><input type=\"hidden\" name=\"ensID\" value=\"{$_POST['ensID']}\"><input type=\"hidden\" name=\"enseignant\" value=\"{$_POST['ensID']}\"><input class=\"defaultButton\" type=\"submit\" name=\"respDipMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				} // end of else !isset($_POST['ensID'])				
			}
			
			
			// on connait le diplome
			else
			{
				// info sur l'enseignant
				$ensInfo = dbQuery('SELECT nom, prenom
					FROM enseignant
					WHERE `id-enseignant` = '.$_POST['enseignant']) ;
				$ensInfo = mysql_fetch_array($ensInfo) ;
				
								
				
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=responsable_diplome\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Enseignant </b></td><td width=\"300\" align=\"left\" colspan=\"2\">{$ensInfo['nom']} {$ensInfo['prenom']}</td>") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de l'enseignant </b></td><td width=\"300\" align=\"left\" colspan=\"2\">{$_POST['enseignant']}</td>") ;
				print("\t\t\t\t</tr>\n") ;				
			
				$requette= 'SELECT `intitule`,`id-diplome` FROM `diplome` order by intitule';
		  	$resultat=dbQuery($requette);
		  	print("<tr><td><b>choix du diplome :</b></td>");
	   		print("<td><select class='defaultInput' name='diplome'>");
  			while($maligne=mysql_fetch_array($resultat))
  			{
  			   echo "<option value='".$maligne['id-diplome']."'>".$maligne['intitule']."</option>";
        }
			/*	print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant du nouveau diplome *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"diplome\" size=\"7\" maxlength=\"7\" value=\"{$_POST['id']}\"></td>") ;
				print("<td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('alldiplomes&t=diplome')\"> voir les diplomes</a></td>\n") ;
				print("\t\t\t\t</tr>\n") ;*/
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\"  name=\"respDipMod\" value=\"true\"><input type=\"hidden\" name=\"enseignant\"  value=\"{$_POST['enseignant']}\"><input type=\"hidden\" name=\"oldDiplome\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"button\" name=\"respDipMod\" value=\"Modifier\" onClick=\"checkRDMod('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			
			dbClose() ;
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des responsables de diplome : suppression") ;
			
			
			dbConnect() ;
			
			// on ne connait pas encore l'enseignant dont on veut supprimer les matieres
			if (!isset($_POST['id']))
			{
				print("\t\t\t<center><b>Choisissez l'enseignant dont il faut supprimer des diplomes</b></center><br>\n") ;
				// liste des enseignants
				$ensList = dbQuery('SELECT DISTINCT E.nom, E.prenom, E.`id-enseignant`
					FROM enseignant E, `resp-diplome` X
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
					print("\t\t\t<center><form action=\"admin.php?w=responsable_diplome&a=del\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $ensCount ; $i++)
					{
						$fEnseignantList = mysql_fetch_array($ensList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fEnseignantList['id-enseignant']}\" onClick=\"submit()\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"respDipDel\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
					
				} 					
			}// end of if !isset($_POST['id'])
			
			else
			{
				$dipList = dbQuery('SELECT D.`id-diplome`, D.`intitule` AS diplome
						FROM `resp-diplome` E, diplome D
						WHERE E.`id-diplome` = D.`id-diplome` AND
							E.`id-enseignant` = '.$_POST['id'].'
						ORDER BY D.intitule') ;
					
				$dipCount = mysql_num_rows($dipList) ;
				
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=responsable_diplome\" method=\"post\" onSubmit=\"return checkItemsToDelete($dipCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $dipCount ; $i++)
				{
					$fDipList = mysql_fetch_array($dipList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fDipList['id-diplome']}\"> {$fDipList['diplome']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"300\" align=\"left\"><br><input type=\"hidden\" name=\"enseignant\" value=\"{$_POST['id']}\"><input class=\"defaultButton\" type=\"submit\" name=\"respDipDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des responsables de dipl&ocirc;mes : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=responsable_diplome\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF responsable_diplome
*/
?>
