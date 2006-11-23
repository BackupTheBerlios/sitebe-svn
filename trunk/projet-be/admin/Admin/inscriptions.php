<?php
/*
** Fichier : inscriptions
** Date de creation : 03/01/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des inscriptions des etudiants cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des inscriptions : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=inscriptions&a=add\">ajouter inscription</a> ] - ") ;
		print("[ <a href=\"admin.php?w=inscriptions&a=mod\"> modifier inscription</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=inscriptions&a=del\"> supprimer inscription</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{	
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			dbConnect() ;
			$dipsList = dbQuery('SELECT *
				FROM diplome') ;
				
			$etuList = dbQuery('SELECT COUNT(`id-etudiant`) AS etuNumb
				FROM etudiant') ;
				
			$etuList = mysql_fetch_array($etuList) ;
				
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
			
			centeredInfoMessage(3, 3, "Administration des inscriptions : ajout") ;
			
			// si aucun diplome ou aucun etudiant inscrits
			if (($countDips == 0) || ($etuList['etuNumb'] == 0))
			{
				centeredInfoMessage(2, 2, "Impossible d'ajouter, dipl&ocirc;me ou &eacute;tudiant vides") ;
			}
			
			
			else
			{				
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=inscriptions\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
		 		$requette= 'SELECT `nom`,`prenom`,`id-etudiant` FROM `etudiant` order by nom';
  			$resultat=dbQuery($requette);
  			print("<tr><td><b>choix de l'etudiant :</b></td>");
  			print("<td><select class='defaultInput' name='etudiant'>");
  			while($maligne=mysql_fetch_array($resultat))
  			{
  			   echo "<option value='".$maligne['id-etudiant']."'>".$maligne['nom']." ".$maligne['prenom']."</option>";
        }
        print("</select></td></tr>");
								
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Dipl&ocirc;me de l'&eacute;tudiant </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"diplome\">") ;
				for($i = 0 ; $i < $countDips ; $i++)
				{
					$dipsDetails = mysql_fetch_array($dipsList) ;
					print("<option> {$dipsDetails['intitule']} </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Promotion de l'&eacute;tudiant </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"annee\">") ;
				foreach ($allYears as $singleDate)
				{
					print("<option> $singleDate </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"inscriptionAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkInscription('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
			}
			
			
			dbClose() ;
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des &eacute;valuations des mati&egrave;res : modification") ;
			
			// on ne connait pas encore l'element a modifier
			if (!isset($_GET['etuID']) || !isset($_GET['diplome']) || !isset($_GET['annee']))
			{
				// on ne connait pas encore l'etudiant
				if (!isset($_POST['etuID']))
				{
					dbConnect() ;
					$elementsCount = 30 ;	// on affiche 30 par page
					$fromLimit = 0 ;
					if (isset($_GET['from']) && is_numeric($_GET['from']))
					{
						$fromLimit = $_GET['from'] ;
					}
	
					// nombre total des etudiants
					$totalEtu = dbQuery('SELECT DISTINCT `id-etudiant`
						FROM inscrit') ;
		
					$totalEtu = mysql_num_rows($totalEtu) ;
					
					// etudiants correspondant a cette page
					$allEtu = dbQuery('SELECT DISTINCT I.`id-etudiant`, E.nom, E.prenom
						FROM etudiant E, inscrit I
						WHERE	E.`id-etudiant` = I.`id-etudiant`
						ORDER BY E.nom, E.prenom
						LIMIT '.$fromLimit.', '.$elementsCount) ;
		
					$etuCount = mysql_num_rows($allEtu) ;
					
					// si il n'y a aucun etudiant
					if ($etuCount == 0)
					{
						centeredInfoMessage(2, 2, "Aucun &eacute;tudiant pour le moment") ;
					}
					
					else
					{
						// les pages pour acceder aux autres etudiants
						$pagesCount = $totalEtu / $elementsCount ;
						
						print("\t\t\t<center><b>Choisissez un &eacute;tudiant</b></center><br>\n") ;
						print("\t\t\t<center><b>Pages</b><br>") ;
						for ($k = 0 ; $k < $pagesCount ; $k++)
						{
							if (($k * $elementsCount) == $fromLimit)
							{
								print("\t\t\t [ ".($k + 1)." ] \n") ;
							}	
			
							else
							{
								print("\t\t\t <a href=\"admin.php?w=inscriptions&a=mod&from=".($k * $elementsCount)."\">".($k + 1)."</a> \n") ;
							}
						}		
						print("</center>\n") ;						
						
						print("\t\t\t<center><form action=\"admin.php?w=inscriptions&a=mod\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
						for ($i = 0 ; $i < $etuCount ; $i++)
						{
							$tempList = mysql_fetch_array($allEtu) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"etuID\" value=\"{$tempList['id-etudiant']}\" onClick=\"submit()\"> {$tempList['nom']} {$tempList['prenom']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"inscriptionMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}	
					dbClose() ;				
				} // end of if (!isset($_POST['etuID']))
				
				
				// on connait l'etudiant on affiche toutes les inscriptions lui correspondant
				else
				{
					dbConnect() ;
					
					$etuDetails = dbQuery('SELECT nom, prenom
						FROM etudiant
						WHERE `id-etudiant` = '.$_POST['etuID']) ;
						
					$etuDetails = mysql_fetch_array($etuDetails) ;
					print("<center><b>Inscriptions de {$etuDetails['nom']} {$etuDetails['prenom']}</b></center><br>\n") ;
					
					
					$elementsList = dbQuery('SELECT I.`id-diplome`, I.annee, D.intitule
						FROM inscrit I, diplome D
						WHERE I.`id-diplome` = D.`id-diplome` AND
							I.`id-etudiant` = '.$_POST['etuID'].'
						ORDER BY D.intitule') ;
						
					// le cas vide  ne devrait jamais arriver 
					print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					while ($details = mysql_fetch_array($elementsList))
					{
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\">- {$details['intitule']}, {$details['annee']}</td>") ;
						print("<td width=\"100\" align=\"center\" valign=\"top\">[ <a href=\"admin.php?w=inscriptions&a=mod&etuID={$_POST['etuID']}&diplome={$details['id-diplome']}&annee={$details['annee']}\">modifier</a> ]</td>") ;
						print("\t\t\t\t</tr>\n") ;
					}				
						
					print("\t\t\t</table></center>\n") ;					
					
					dbClose() ;
				}				
			} // end of	if (!isset($_GET['etuID']) || !isset($_GET['diplome']) || !isset($_GET[annee']))		
			
			
			// on connait l'element a modifier
			else
			{
				dbConnect() ;
				
				// tableau contenant les dates
				// generation de la liste des annees (de 1995 jusqu'a l'annee en cours + 2)
				$firstYear = 1995 ;
				$lastYear = date("Y", mktime()) ;
				$saveYear = $lastYear ;
				$lastYear += 1 ;
			
				for ($i = $firstYear ; $i  <= $lastYear ; $i++)
				{
					$allYears[] = $i." - ".($i + 1) ;
				}
				
				// en entree on recoit l'identifiant du diplome il faut recuperer son intitule
				$dipInfo = dbQuery('SELECT intitule
					FROM diplome
					WHERE `id-diplome` = '.$_GET['diplome']) ;
					
				$dipInfo = mysql_fetch_array($dipInfo) ;
				
				// liste de tous les diplomes
				$dipsList = dbQuery('SELECT intitule
					FROM diplome
					ORDER BY intitule') ;
					
				$countDips = mysql_fetch_array($dipsList) ;
					
				// formulaire
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=inscriptions\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
		 		$requette= 'SELECT `nom`,`prenom`,`id-etudiant` FROM `etudiant` order by nom';
  			$resultat=dbQuery($requette);
  			print("<tr><td><b>choix de l'etudiant :</b></td>");
  			print("<td><select class='defaultInput' name='etudiant'>");
  			while($maligne=mysql_fetch_array($resultat))
  			{
  			   echo "<option value='".$maligne['id-etudiant']."'>".$maligne['nom']." ".$maligne['prenom']."</option>";
        }
        print("</select></td></tr>");
		/*		print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de l'&eacute;tudiant *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"etudiant\" size=\"8\" maxlength=\"8\" value=\"{$_GET['etuID']}\"></td>") ;
				print("<td align=\"right\" width=\"200\"><a href=\"javascript:openHelp('alletudiants')\"> voir les &eacute;tudiants </a></td>\n") ;
				print("\t\t\t\t</tr>\n") ;				*/
				
								
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Dipl&ocirc;me de l'&eacute;tudiant </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"diplome\">") ;
				while ($dipsDetails = mysql_fetch_array($dipsList))
				{
					$selected = "" ;
					if ($dipsDetails['intitule'] == $dipInfo['intitule']) { $selected = " selected" ; }
					print("<option$selected> {$dipsDetails['intitule']} </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Promotion de l'&eacute;tudiant </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"annee\">") ;
				foreach ($allYears as $singleDate)
				{
					$selected = "" ;
					if ($_GET['annee'] == $singleDate) { $selected = " selected" ; }
					print("<option$selected> $singleDate </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"inscriptionMod\" value=\"true\"><input type=\"hidden\" name=\"oldEtuID\" value=\"{$_GET['etuID']}\"><input type=\"hidden\" name=\"oldDiplome\" value=\"{$_GET['diplome']}\"><input type=\"hidden\" name=\"oldAnnee\" value=\"{$_GET['annee']}\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkInscription('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;					
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des inscriptions : suppression") ;
			
			// on ne connait pas encore l'element a modifier
			if (!isset($_GET['etuID']) || !isset($_GET['diplome']) || !isset($_GET['annee']))
			{
				// on ne connait pas encore l'etudiant
				if (!isset($_POST['etuID']))
				{
					dbConnect() ;
					$elementsCount = 30 ;	// on affiche 30 par page
					$fromLimit = 0 ;
					if (isset($_GET['from']) && is_numeric($_GET['from']))
					{
						$fromLimit = $_GET['from'] ;
					}
	
					// nombre total des etudiants
					$totalEtu = dbQuery('SELECT DISTINCT `id-etudiant`
						FROM inscrit') ;
		
					$totalEtu = mysql_num_rows($totalEtu) ;
					
					// etudiants correspondant a cette page
					$allEtu = dbQuery('SELECT DISTINCT I.`id-etudiant`, E.nom, E.prenom
						FROM etudiant E, inscrit I
						WHERE	E.`id-etudiant` = I.`id-etudiant`
						ORDER BY E.nom, E.prenom
						LIMIT '.$fromLimit.', '.$elementsCount) ;
		
					$etuCount = mysql_num_rows($allEtu) ;
					
					// si il n'y a aucun etudiant
					if ($etuCount == 0)
					{
						centeredInfoMessage(2, 2, "Aucun &eacute;tudiant pour le moment") ;
					}
					
					else
					{
						// les pages pour acceder aux autres etudiants
						$pagesCount = $totalEtu / $elementsCount ;
						
						print("\t\t\t<center><b>Choisissez un &eacute;tudiant</b></center><br>\n") ;
						print("\t\t\t<center><b>Pages</b><br>") ;
						for ($k = 0 ; $k < $pagesCount ; $k++)
						{
							if (($k * $elementsCount) == $fromLimit)
							{
								print("\t\t\t [ ".($k + 1)." ] \n") ;
							}	
			
							else
							{
								print("\t\t\t <a href=\"admin.php?w=inscriptions&a=mod&from=".($k * $elementsCount)."\">".($k + 1)."</a> \n") ;
							}
						}		
						print("</center>\n") ;						
						
						print("\t\t\t<center><form action=\"admin.php?w=inscriptions&a=del\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
						for ($i = 0 ; $i < $etuCount ; $i++)
						{
							$tempList = mysql_fetch_array($allEtu) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"etuID\" value=\"{$tempList['id-etudiant']}\" onClick=\"submit()\"> {$tempList['nom']} {$tempList['prenom']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"inscriptionMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}	
					dbClose() ;				
				} // end of if (!isset($_POST['etuID']))
				
				
				// on connait l'etudiant on affiche toutes les inscriptions lui correspondant
				else
				{
					dbConnect() ;
					
					$etuDetails = dbQuery('SELECT nom, prenom
						FROM etudiant
						WHERE `id-etudiant` = '.$_POST['etuID']) ;
						
					$etuDetails = mysql_fetch_array($etuDetails) ;
					print("<center><b>Inscriptions de {$etuDetails['nom']} {$etuDetails['prenom']}</b></center><br>\n") ;
					
					
					$elementsList = dbQuery('SELECT I.`id-diplome`, I.annee, D.intitule
						FROM inscrit I, diplome D
						WHERE I.`id-diplome` = D.`id-diplome` AND
							I.`id-etudiant` = '.$_POST['etuID'].'
						ORDER BY D.intitule') ;
						
					// le cas vide  ne devrait jamais arriver 
					print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					while ($details = mysql_fetch_array($elementsList))
					{
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\">- {$details['intitule']}, {$details['annee']}</td>") ;
						print("<td width=\"100\" align=\"center\" valign=\"top\">[ <a href=\"admin.php?w=inscriptions&a=del&etuID={$_POST['etuID']}&diplome={$details['id-diplome']}&annee={$details['annee']}\">supprimer</a> ]</td>") ;
						print("\t\t\t\t</tr>\n") ;
					}				
						
					print("\t\t\t</table></center>\n") ;					
					
					dbClose() ;
				}				
			} // end of	if (!isset($_GET['etuID']) || !isset($_GET['diplome']) || !isset($_GET[annee']))		
			
			
			// on connait l'element a modifier
			else
			{
				dbConnect() ;
				
				
				// en entree on recoit l'identifiant du diplome il faut recuperer son intitule
				$dipInfo = dbQuery('SELECT intitule
					FROM diplome
					WHERE `id-diplome` = '.$_GET['diplome']) ;
					
				$dipInfo = mysql_fetch_array($dipInfo) ;
				
				// liste de tous les diplomes
				$dipsList = dbQuery('SELECT intitule
					FROM diplome
					ORDER BY intitule') ;
					
				$countDips = mysql_fetch_array($dipsList) ;
					
				// formulaire
				print("\t\t\t<center><b>Vous &ecirc;tes sur le point de supprimer l'&eacute;l&eacute;ment suivant</b></center><br>\n") ;
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=inscriptions\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de l'&eacute;tudiant </b></td><td width=\"100\" align=\"left\">$_GET[etuID]</td>") ;
				print("\t\t\t\t</tr>\n") ;				
				
								
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Dipl&ocirc;me de l'&eacute;tudiant </b></td><td width=\"300\" align=\"left\">$dipInfo[intitule]</td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Promotion de l'&eacute;tudiant </b></td><td width=\"300\" align=\"left\">$_GET[annee]</td>\n") ;
				print("\t\t\t\t</tr>\n") ;				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"oldEtuID\" value=\"{$_GET['etuID']}\"><input type=\"hidden\" name=\"oldDiplome\" value=\"{$_GET['diplome']}\"><input type=\"hidden\" name=\"oldAnnee\" value=\"{$_GET['annee']}\"><input class=\"defaultButton\" type=\"submit\" name=\"inscriptionDel\" value=\"Continuer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;					
				
				dbClose() ;
			}
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des inscriptions : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=inscriptions\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie ou essaie d acceder directement
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;	
}


/*
** EOF inscriptions
*/
?>
