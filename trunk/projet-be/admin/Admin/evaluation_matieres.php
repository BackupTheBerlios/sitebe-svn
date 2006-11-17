<?php
/*
** Fichier : evaluation_matieres
** Date de creation : 03/01/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des evaluations (ou controles de connaissances) cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration de l'&eacute;valuation des mati : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=evaluation_matieres&a=add\">ajouter &eacute;valuation</a> ] - ") ;
		print("[ <a href=\"admin.php?w=evaluation_matieres&a=mod\"> modifier &eacute;valuation</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=evaluation_matieres&a=del\"> supprimer &eacute;valuation</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{	
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			dbConnect() ;
			$typesList = dbQuery('SELECT *
				FROM controle') ;
				
			$naturesList = dbQuery('SELECT *
				FROM nature') ;
				
			$countTypes = mysql_num_rows($typesList) ;
			$countNatures = mysql_num_rows($naturesList) ;
			
			centeredInfoMessage(3, 3, "Administration des &eacute;valuations des mati&egrave;res : ajout") ;
			
			if (($countTypes == 0) || ($countNatures ==0))
			{
				centeredInfoMessage(2, 2, "Impossible d'ajouter, controle ou nature vides") ;
				return ;
			}
			
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=evaluation_matieres\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
	
      $requette= 'SELECT `intitule`,`id-matiere` FROM `matiere` order by intitule';
			$resultat=dbQuery($requette);
			print("<tr><td><b>choix de la matiere :</b></td>");
			print("<td><select class='defaultInput' name='matiere'>");
			while($maligne=mysql_fetch_array($resultat))
			{
			   echo "<option value='".$maligne['id-matiere']."'>".$maligne['intitule']."</option>";
      }
      print("</select></td></tr>");	
				
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Coefficient 1 </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"coeff1\" size=\"7\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Coefficient 2 </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"coeff2\" size=\"7\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
								
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Type du controle </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"controle\">") ;
			for($i = 0 ; $i < $countTypes ; $i++)
			{
				$typesDetails = mysql_fetch_array($typesList) ;
				print("<option> {$typesDetails['type']} </option>") ;
			}
			print("</select></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
				
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Nature du controle </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"nature\">") ;
			for($i = 0 ; $i < $countNatures ; $i++)
			{
				$naturesDetails = mysql_fetch_array($naturesList) ;
				print("<option> {$naturesDetails['nature']} </option>") ;
			}
			print("</select></td>\n") ;
			print("\t\t\t\t</tr>\n") ;				
				
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"500\" align=\"center\" colspan=\"3\"><br><input type=\"hidden\" name=\"evalMatAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkEM('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
		
			dbClose() ;
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des &eacute;valuations des mati&egrave;res : modification") ;
			
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_GET['matID']) || !isset($_GET['type']) || !isset($_GET['nature']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				
				// on ne connait pas le module auquel appartient la matiere
				if (!isset($_POST['modID']))
				{
					// on ne connait pas le diplome
					if (!isset($_POST['dipID']))
					{
						$dipList = dbQuery('SELECT DISTINCT D.intitule, D.`id-diplome`
						FROM diplome D, module M, matiere E, est_evalue X
						WHERE D.`id-diplome` = M.`id-diplome` AND
							M.`id-module` = E.`id-module` AND
							E.`id-matiere` = X.`id-matiere`
							ORDER BY D.intitule') ;
							
						$dipCount = mysql_num_rows($dipList) ;
						
						if ($dipCount == 0)
						{
							centeredInfoMessage(2, 2, "Aucun dipl&ocirc;me pour le moment") ;
							return ;
						}
					
						// formulaire
						print("\t\t\t<center><b>Choisissez un dipl&ocirc;me</b></center>\n") ;
						print("\t\t\t<center><form action=\"admin.php?w=evaluation_matieres&a=mod\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
						for ($i = 0 ; $i < $dipCount ; $i++)
						{
							$fDipList = mysql_fetch_array($dipList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"> {$fDipList['intitule']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"evalMatMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}
					
					// on connait le diplome : liste des modules
					else
					{
						$modList = dbQuery('SELECT DISTINCT M.intitule, M.`id-module`
						FROM module M, matiere E, est_evalue X
						WHERE M.`id-diplome` = '.$_POST['dipID'].' AND
							M.`id-module` = E.`id-module` AND
							E.`id-matiere` = X.`id-matiere`
							ORDER BY M.intitule') ;
							
						$modCount = mysql_num_rows($modList) ;
					
						if ($modCount == 0)
						{
							centeredInfoMessage(2, 2, "Aucun module pour le moment") ;
							return ;
						}
					
						// formulaire
						print("\t\t\t<center><b>Choisissez un module</b></center>\n") ;
						print("\t\t\t<center><form action=\"admin.php?w=evaluation_matieres&a=mod\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
						for ($i = 0 ; $i < $modCount ; $i++)
						{
							$fModList = mysql_fetch_array($modList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"modID\" value=\"{$fModList['id-module']}\" onClick=\"submit()\"> {$fModList['intitule']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input type=\"hidden\" name=\"dipID\" value=\"{$_POST['dipID']}\"><input class=\"defaultButton\" type=\"submit\" name=\"evalMatMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					} // end of else (!isset($_POST['dipID']))
				} // end of if (!isset($_POST['modID']))
				
				
				// on connait le module
				else
				{					
					// les identifiants sont passes par la variable GET
					$eltsList = dbQuery('SELECT M.intitule, X.type, X.nature, M.`id-matiere`
							FROM matiere M, est_evalue X
							WHERE M.`id-module` = '.$_POST['modID'].' AND
								M.`id-matiere` = X.`id-matiere`
							ORDER BY M.intitule') ;
							
					$eltsCount = mysql_num_rows($eltsList) ;
					
					if ($eltsCount == 0)
					{
						centeredInfoMessage(2, 2, "Aucune mati&egrave;re pour le moment") ;
						return ;
					}
					
					
					print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $eltsCount ; $i++)
					{
						$fEltsList = mysql_fetch_array($eltsList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"400\" align=\"left\">- {$fEltsList['intitule']} ({$fEltsList['type']}, {$fEltsList['nature']})</td>") ;
						print("<td width=\"100\" align=\"center\" valign=\"top\">[ <a href=\"admin.php?w=evaluation_matieres&a=mod&matID={$fEltsList['id-matiere']}&type={$fEltsList['type']}&nature={$fEltsList['nature']}\">modifier</a> ]</td>") ;
						print("\t\t\t\t</tr>\n") ;
					}				
						
					print("\t\t\t</table></center>\n") ;
				}
			}
			
			
			// on connait l'element a modifier
			else
			{
				dbConnect() ;
				
				$eltDetails = dbQuery('SELECT coefficient1, coefficient2
					FROM est_evalue
					WHERE `id-matiere` = '.$_GET['matID'].' AND
						type = "'.$_GET['type'].'" AND
						nature = "'.$_GET['nature'].'"') ;
						
				$eltExists = mysql_num_rows($eltDetails) ;
				
				if ($eltExists == 0)
				{
					centeredErrorMessage(2, 2, "Rien ne correspond a cet &eacute;l&eacute;ment") ;
					return ;
				}
				
				$typesList = dbQuery('SELECT *
					FROM controle') ;
						
				$naturesList = dbQuery('SELECT *
					FROM nature') ;
						
				$fEltDetails = mysql_fetch_array($eltDetails) ;
						
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=evaluation_matieres\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
  			
        $requette= 'SELECT `intitule`,`id-matiere` FROM `matiere` order by intitule';
  			$resultat=dbQuery($requette);
  			print("<tr><td><b>choix de la matiere :</b></td>");
  			print("<td><select class='defaultInput' name='matiere'>");
  			while($maligne=mysql_fetch_array($resultat))
  			{
  			   echo "<option value='".$maligne['id-matiere']."'>".$maligne['intitule']."</option>";
        }
        print("</select></td></tr>");	  
			/*	print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de la mati&egrave;re </b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"matiere\" size=\"7\" maxlength=\"7\" value=\"{$_GET['matID']}\"></td>") ;
				print("<td align=\"right\" width=\"100\"><a href=\"javascript:openHelp('allmatieres')\"> voir les mati&egrave;res </a></td>\n") ;
				print("\t\t\t\t</tr>\n") ;*/
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Coefficient 1 </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"coeff1\" size=\"7\" value=\"{$fEltDetails['coefficient1']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Coefficient 2 </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"coeff2\" size=\"7\" value=\"{$fEltDetails['coefficient2']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
								
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Type du controle </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"controle\">") ;
				while ($typesDetails = mysql_fetch_array($typesList))
				{
					$selected = "" ;
					if ($typesDetails['type'] == $_GET['type']) { $selected = " selected" ; }
					print("<option$selected> {$typesDetails['type']} </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Nature du controle </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><select class=\"defaultInput\" name=\"nature\">") ;
				while($naturesDetails = mysql_fetch_array($naturesList))
				{
					$selected = "" ;
					if ($naturesDetails['nature'] == $_GET['nature']) { $selected = " selected" ; }
					print("<option$selected> {$naturesDetails['nature']} </option>") ;
				}
				print("</select></td>\n") ;
				print("\t\t\t\t</tr>\n") ;				
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"evalMatMod\" value=\"true\"><input type=\"hidden\" name=\"oldMat\" value=\"{$_GET['matID']}\"><input type=\"hidden\" name=\"oldType\" value=\"{$_GET['type']}\"><input type=\"hidden\" name=\"oldNature\" value=\"{$_GET['nature']}\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkEM('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}				
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des &eacute;valuations des mati&egrave;res : suppression") ;
			
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_GET['matID']) || !isset($_GET['type']) || !isset($_GET['nature']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				
				// on ne connait pas le module auquel appartient la matiere
				if (!isset($_POST['modID']))
				{
					// on ne connait pas le diplome
					if (!isset($_POST['dipID']))
					{
						$dipList = dbQuery('SELECT DISTINCT D.intitule, D.`id-diplome`
						FROM diplome D, module M, matiere E, est_evalue X
						WHERE D.`id-diplome` = M.`id-diplome` AND
							M.`id-module` = E.`id-module` AND
							E.`id-matiere` = X.`id-matiere`
							ORDER BY D.intitule') ;
							
						$dipCount = mysql_num_rows($dipList) ;
						
						if ($dipCount == 0)
						{
							centeredInfoMessage(2, 2, "Aucun dipl&ocirc;me pour le moment") ;
						}
					
						// formulaire
						else
						{
							print("\t\t\t<center><b>Choisissez un dipl&ocirc;me</b></center>\n") ;
							print("\t\t\t<center><form action=\"admin.php?w=evaluation_matieres&a=del\" method=\"post\">\n") ;
							print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
							for ($i = 0 ; $i < $dipCount ; $i++)
							{
								$fDipList = mysql_fetch_array($dipList) ;
								print("\t\t\t\t<tr>\n") ;
								print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"> {$fDipList['intitule']}</td>\n") ;
								print("\t\t\t\t</tr>\n") ;
							}
				
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"evalMatMod\" value=\"Choisir\"></td>\n") ;
							print("\t\t\t\t</tr>\n") ;
							print("\t\t\t</table>\n") ;
							print("\t\t\t</form></center>\n") ;							
						}
					}
					
					// on connait le diplome : liste des modules
					else
					{
						$modList = dbQuery('SELECT DISTINCT M.intitule, M.`id-module`
						FROM module M, matiere E, est_evalue X
						WHERE M.`id-diplome` = '.$_POST['dipID'].' AND
							M.`id-module` = E.`id-module` AND
							E.`id-matiere` = X.`id-matiere`
							ORDER BY M.intitule') ;
							
						$modCount = mysql_num_rows($modList) ;
					
						if ($modCount == 0)
						{
							centeredInfoMessage(2, 2, "Aucun module pour le moment") ;
						}
					
						// formulaire
						else
						{
							print("\t\t\t<center><b>Choisissez un module</b></center>\n") ;
							print("\t\t\t<center><form action=\"admin.php?w=evaluation_matieres&a=del\" method=\"post\">\n") ;
							print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
							for ($i = 0 ; $i < $modCount ; $i++)
							{
								$fModList = mysql_fetch_array($modList) ;
								print("\t\t\t\t<tr>\n") ;
								print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"modID\" value=\"{$fModList['id-module']}\" onClick=\"submit()\"> {$fModList['intitule']}</td>\n") ;
								print("\t\t\t\t</tr>\n") ;
							}
				
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input type=\"hidden\" name=\"dipID\" value=\"{$_POST['dipID']}\"><input class=\"defaultButton\" type=\"submit\" name=\"evalMatMod\" value=\"Choisir\"></td>\n") ;
							print("\t\t\t\t</tr>\n") ;
							print("\t\t\t</table>\n") ;
							print("\t\t\t</form></center>\n") ;							
						}
					} // end of else (!isset($_POST['dipID']))
				} // end of if (!isset($_POST['modID']))
				
				
				// on connait le module
				else
				{					
					// les identifiants sont passes par la variable GET
					$eltsList = dbQuery('SELECT M.intitule, X.type, X.nature, M.`id-matiere`
							FROM matiere M, est_evalue X
							WHERE M.`id-module` = '.$_POST['modID'].' AND
								M.`id-matiere` = X.`id-matiere`
							ORDER BY M.intitule') ;
							
					$eltsCount = mysql_num_rows($eltsList) ;
					
					if ($eltsCount == 0)
					{
						centeredInfoMessage(2, 2, "Aucune mati&egrave;re pour le moment") ;
					}
					
					else
					{
						print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
						for ($i = 0 ; $i < $eltsCount ; $i++)
						{
							$fEltsList = mysql_fetch_array($eltsList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"400\" align=\"left\">- {$fEltsList['intitule']} ({$fEltsList['type']}, {$fEltsList['nature']})</td>") ;
							print("<td width=\"100\" align=\"center\" valign=\"top\">[ <a href=\"admin.php?w=evaluation_matieres&a=del&matID={$fEltsList['id-matiere']}&type={$fEltsList['type']}&nature={$fEltsList['nature']}\">supprimer</a> ]</td>") ;
							print("\t\t\t\t</tr>\n") ;
						}				
						
						print("\t\t\t</table></center>\n") ;
					}					
				}
			}
			
			
			// on connait l'element a modifier
			else
			{
				dbConnect() ;
				
				$eltDetails = dbQuery('SELECT coefficient1, coefficient2
					FROM est_evalue
					WHERE `id-matiere` = '.$_GET['matID'].' AND
						type = "'.$_GET['type'].'" AND
						nature = "'.$_GET['nature'].'"') ;
						
				$eltExists = mysql_num_rows($eltDetails) ;
				
				if ($eltExists == 0)
				{
					centeredErrorMessage(2, 2, "Rien ne correspond a cet &eacute;l&eacute;ment") ;
				}
				
				else
				{	
					$fEltDetails = mysql_fetch_array($eltDetails) ;					
					print("\t\t\t<center><b>Vous &ecirc;tes sur le point de supprimer l'&eacute'l&eacute;ment suivant</b></center>") ;	
					print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=evaluation_matieres\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Identifiant de la mati&egrave;re </b></td><td width=\"200\" align=\"left\">{$_GET['matID']}</td>") ;
					print("\t\t\t\t</tr>\n") ;
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Type </b></td><td width=\"200\" align=\"left\">{$_GET['type']}</td>") ;
					print("\t\t\t\t</tr>\n") ;
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Nature </b></td><td width=\"200\" align=\"left\">{$_GET['nature']}</td>") ;
					print("\t\t\t\t</tr>\n") ;
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Coefficient 1 </b></td><td width=\"200\" align=\"left\">{$fEltDetails['coefficient1']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
			
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Coefficient 2 </b></td><td width=\"200\" align=\"left\">{$fEltDetails['coefficient2']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
								
									
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"oldMat\" value=\"{$_GET['matID']}\"><input type=\"hidden\" name=\"oldType\" value=\"{$_GET['type']}\"><input type=\"hidden\" name=\"oldNature\" value=\"{$_GET['nature']}\"><input class=\"defaultButton\" type=\"submit\" name=\"evalMatDel\" value=\"Continuer\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
				
				dbClose() ;
			}
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des &eacute;valuations des mati&egrave;res : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=evaluation_matieres\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie ou essaie d acceder directement
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;	
}


/*
** EOF evaluation_matieres
*/
?>
