<?php
/*
** Fichier : matieres
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des matieres cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des mati&egrave;res : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=matieres&a=add\">ajouter mati&egrave;re</a> ] - ") ;
		print("[ <a href=\"admin.php?w=matieres&a=mod\"> modifier mati&egrave;re</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=matieres&a=del\"> supprimer mati&egrave;re</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{	
		
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			centeredInfoMessage(3, 3, "Administration des mati&egrave;res : ajout") ;
			dbConnect() ;
			
			// si on ne connait pas encore le module
			if (!isset($_POST['moduleID']))
			{
				// si aucun diplome n'est defini alors on les affiche
				if (!isset($_POST['dipID']))
				{
					print("\t\t\t<center><b>Choisissez un dipl&ocirc;me</b></center>\n") ;
					$dipList = dbQuery('SELECT  intitule, `id-diplome`
						FROM diplome
						ORDER BY intitule') ;
				
					$dipCount = mysql_num_rows($dipList) ;
			
					// aucun diplome pour le moment
					if ($dipCount == 0)
					{
						centeredInfoMessage(2, 2, "Il faut d'abord ajouter des diplomes") ;
					}
					else
					{
						print("\t\t\t<center><form action=\"admin.php?w=matieres&a=add\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
						for ($i = 0 ; $i < $dipCount ; $i++)
						{
							$fDipList = mysql_fetch_array($dipList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"><b>Dipl&ocirc;me :</b> {$fDipList['intitule']}</td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
			
			
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matiereAdd\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}
				}
				// un diplome est defini, on affiche tous les modules
				else
				{
					// infos sur le diplome
					$dipInfo = dbQuery('SELECT intitule
						FROM diplome
						WHERE `id-diplome` = '.$_POST['dipID']) ;
					$dipInfo = mysql_fetch_array($dipInfo) ;
				
					print("\t\t\t<center><b>Choisissez un module pour le dipl&ocirc;me : {$dipInfo['intitule']}</b></center>\n") ;
				
					$modulesList = dbQuery('SELECT `id-module`, intitule
						FROM module 
						WHERE `id-diplome` = '.$_POST['dipID'].'
						ORDER BY intitule') ;
				
					$modulesCount = mysql_num_rows($modulesList) ;
			
					// aucun module pour le moment
					if ($modulesCount == 0)
					{
						centeredInfoMessage(2, 2, "Il faut d'abord ajouter des modules") ;
					}
					else
					{
						print("\t\t\t<center><form action=\"admin.php?w=matieres&a=add\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			
						for ($i = 0 ; $i < $modulesCount ; $i++)
						{
							$fModulesList = mysql_fetch_array($modulesList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"moduleID\" value=\"{$fModulesList['id-module']}\" onClick=\"submit()\"> {$fModulesList['intitule']} </td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
			
			
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matiereAdd\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}
				}					
			}
			
			// on connait le module : on cree la matiere
			else
			{
				// Numeros de matiere deja utilises
				$nomatutil = dbQuery('SELECT DISTINCT no_matiere
					FROM matiere
					WHERE `id-module` = '.$_POST['moduleID'].'
					ORDER BY no_matiere') ;
				$nomatutilcount = mysql_num_rows($nomatutil);
				$nomatutildetail = mysql_fetch_array($nomatutil);
				
				// Verifie que l'on ait pas deja 9 matieres dans ce module
				if ($nomatutilcount == 9)
				{
					centeredInfoMessage(2, 2, "Impossible de cr&eacute;er plus de 9 matieres par module") ;
				}
				else
				{
					print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=matieres\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Intitul&eacute; de la mati&egrave;re *</b></td><td width=\"300\" colspan=\"2\" align=\"left\"><input class=\"defaultInput\" name=\"matiereIntitule\" size=\"45\"></td>") ;
					print("\t\t\t\t</tr>\n") ;
						
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Coefficient *</b></td><td width=\"300\" colspan=\"2\" align=\"left\"><input class=\"defaultInput\" name=\"matiereCoeff\"  size=\"7\" maxlength=\"7\"></td>") ;
					print("\t\t\t\t</tr>\n") ;
						
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Nombre d'heures *</b></td><td width=\"300\" colspan=\"2\" align=\"left\"><input class=\"defaultInput\" name=\"matiereHeures\" size=\"7\" maxlength=\"7\"></td>") ;
					print("\t\t\t\t</tr>\n") ;
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Num&eacute;ro de matiere * </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"matiereNo\">\n") ;
					$nbnoutil = 0;
					for ($i = 1 ; $i < 10 ; $i++)
					{
						// On recupere le numéro utilise suivant si il est depasse
						if ($nomatutildetail['no_matiere'] < $i && $nbnoutil < $nomatutilcount)
						{
							$nomatutildetail = mysql_fetch_array($nomatutil) ;
						}
						
						// On verifie si le numero est utilise ou non
						if ($nomatutildetail['no_matiere'] != $i)
						{
							print("<option value=$i>{$i}</option>\n") ;
						}
						else
						{
							$nbnoutil++;
						}
					}
					print("\t\t\t\t\t</select></td>\n") ;			
					print("\t\t\t\t</tr>\n") ;
					
					
					$modulesList = mysql_fetch_array(dbQuery('SELECT `id-module`, intitule, no_semestre
						FROM module
						WHERE `id-module`='.$_POST['moduleID'])) ;
					
					print("\t\t\t\t<tr>\n") ;   
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Module associ&eacute;</td><td width=\"100\" align=\"left\">".$modulesList['intitule']." (semestre ".$modulesList['no_semestre'].")</td>");
					print("\t\t\t\t</tr>\n") ;			
					
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"matiereAdd\" value=\"true\"><input type=\"hidden\" name=\"matiereModule\" value=".$_POST['moduleID']."><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkMatiere('defaultForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
			}
			
			dbClose() ;
			
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des mati&egrave;res : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				// si on ne connait pas encore le module
				if (!isset($_POST['moduleID']))
				{
					// si aucun diplome n'est defini alors on les affiche
					if (!isset($_POST['dipID']))
					{
						print("\t\t\t<center><b>Choisissez un dipl&ocirc;me</b></center>\n") ;
						$dipList = dbQuery('SELECT  intitule, `id-diplome`
							FROM diplome
							ORDER BY intitule') ;
					
						$dipCount = mysql_num_rows($dipList) ;
				
						// aucun diplome pour le moment
						if ($dipCount == 0)
						{
							centeredInfoMessage(2, 2, "Element vide") ;
						}
			
						else
						{
							print("\t\t\t<center><form action=\"admin.php?w=matieres&a=mod\" method=\"post\">\n") ;
							print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
							for ($i = 0 ; $i < $dipCount ; $i++)
							{
								$fDipList = mysql_fetch_array($dipList) ;
								print("\t\t\t\t<tr>\n") ;
								print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"><b>Dipl&ocirc;me :</b> {$fDipList['intitule']}</td>\n") ;
								print("\t\t\t\t</tr>\n") ;
							}
				
				
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matiereMod\" value=\"Choisir\"></td>\n") ;
							print("\t\t\t\t</tr>\n") ;
							print("\t\t\t</table>\n") ;
							print("\t\t\t</form></center>\n") ;
						}
					}
			
					// un diplome est defini, on affiche tous les modules
					else
					{
						// infos sur le diplome
						$dipInfo = dbQuery('SELECT intitule
							FROM diplome
							WHERE `id-diplome` = '.$_POST['dipID']) ;
						$dipInfo = mysql_fetch_array($dipInfo) ;
					
						print("\t\t\t<center><b>Choisissez un module pour le dipl&ocirc;me : {$dipInfo['intitule']}</b></center>\n") ;
					
						$modulesList = dbQuery('SELECT `id-module`, intitule
							FROM module 
							WHERE `id-diplome` = '.$_POST['dipID'].'
							ORDER BY intitule') ;
					
						$modulesCount = mysql_num_rows($modulesList) ;
				
						// aucun module pour le moment
						if ($modulesCount == 0)
						{
							centeredInfoMessage(2, 2, "Element vide") ;
						}
			
						else
						{
							print("\t\t\t<center><form action=\"admin.php?w=matieres&a=mod\" method=\"post\">\n") ;
							print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
							for ($i = 0 ; $i < $modulesCount ; $i++)
							{
								$fModulesList = mysql_fetch_array($modulesList) ;
								print("\t\t\t\t<tr>\n") ;
								print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"moduleID\" value=\"{$fModulesList['id-module']}\" onClick=\"submit()\"> {$fModulesList['intitule']} </td>\n") ;
								print("\t\t\t\t</tr>\n") ;
							}
				
				
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matiereMod\" value=\"Choisir\"></td>\n") ;
							print("\t\t\t\t</tr>\n") ;
							print("\t\t\t</table>\n") ;
							print("\t\t\t</form></center>\n") ;
						}
					}					
				}
				
				// on connait le module : on affiche toutes les matieres
				else
				{
					// infos sur le module et le diplome
					$moduleInfo = dbQuery('SELECT M.intitule AS mInt, D.intitule AS dInt
						FROM module M, diplome D
						WHERE M.`id-diplome` = D.`id-diplome` AND
							M.`id-module` = '.$_POST['moduleID']) ;
						
					$moduleInfo = mysql_fetch_array($moduleInfo) ;
					
					// liste des matieres
					$matieresList = dbQuery('SELECT intitule, `id-matiere`
						FROM matiere
						WHERE `id-module` = '.$_POST['moduleID'].'
						ORDER BY intitule') ;
						
					// affichage
					print("\t\t\t<center><b>Choisissez une mati&egrave;re pour le module {$moduleInfo['mInt']} du dipl&ocirc;me {$moduleInfo['dInt']}</b></center>\n") ;
					
					$matieresCount = mysql_num_rows($matieresList) ;
					
					if ($matieresCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
					}
					
					else
					{					
						print("\t\t\t<center><form action=\"admin.php?w=matieres&a=mod\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
						for ($i = 0 ; $i < $matieresCount ; $i++)
						{
							$fMatieresList = mysql_fetch_array($matieresList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fMatieresList['id-matiere']}\" onClick=\"submit()\"> {$fMatieresList['intitule']} </td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input type=\"hidden\" name=\"moduleID\" value=\"".$_POST['moduleID']."\"><input class=\"defaultButton\" type=\"submit\" name=\"matiereMod\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}
				}
				
				dbClose() ;
			} // end of if not id
			
			// on connait l'element a modifier
			else
			{
				if (isset($_POST['id'])) { $eID = $_POST['id'] ; }
				else { $eID = $_GET['id'] ; }
				// connexion a la base et recuperation des infos
				dbConnect() ;
				
				$matiereDetails = dbQuery('SELECT coefficient, intitule, `id-module`, `nbre-heures`, no_matiere
					FROM matiere
					WHERE `id-matiere` = '.$eID) ;
					
				// on verifie si le resutat est correct
				$matiereExists = mysql_num_rows($matiereDetails) ;
				if ($matiereExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cette mati&egrave;re") ;
					return;
				}
				
				else
				{
					$matiereDetails = mysql_fetch_array($matiereDetails) ;
					
					// Numeros de matiere deja utilises
					$nomatutil = dbQuery('SELECT DISTINCT no_matiere
						FROM matiere
						WHERE `id-module` = '.$_POST['moduleID'].'
						AND `id-matiere` <> '.$eID.'
						ORDER BY no_matiere') ;
					$nomatutilcount = mysql_num_rows($nomatutil);
					$nomatutildetail = mysql_fetch_array($nomatutil);
					
					print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=matieres\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Intitul&eacute; de la mati&egrave;re *</b></td><td width=\"300\" colspan=\"2\" align=\"left\"><input class=\"defaultInput\" name=\"matiereIntitule\" size=\"45\" value=\"{$matiereDetails['intitule']}\"></td>") ;
					print("\t\t\t\t</tr>\n") ;
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Coefficient *</b></td><td width=\"300\" colspan=\"2\" align=\"left\"><input class=\"defaultInput\" name=\"matiereCoeff\"  size=\"7\" maxlength=\"7\" value=\"{$matiereDetails['coefficient']}\"></td>") ;
					print("\t\t\t\t</tr>\n") ;
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Nombre d'heures *</b></td><td width=\"300\" colspan=\"2\" align=\"left\"><input class=\"defaultInput\" name=\"matiereHeures\" size=\"7\" maxlength=\"7\" value=\"{$matiereDetails['nbre-heures']}\"></td>") ;
					print("\t\t\t\t</tr>\n") ;
			
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Num&eacute;ro de matiere * </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"matiereNo\">\n") ;
					$nbnoutil = 0;
					for ($i = 1 ; $i < 10 ; $i++)
					{
						// On recupere le numéro utilise suivant si il est depasse
						if ($nomatutildetail['no_matiere'] < $i && $nbnoutil < $nomatutilcount)
						{
							$nomatutildetail = mysql_fetch_array($nomatutil) ;
						}
						
						// On verifie si le numero est utilise ou non
						if ($nomatutildetail['no_matiere'] != $i)
						{
							if ($matiereDetails['no_matiere'] == $i)
							{
								print("<option value=".$i." selected>".$i."</option>\n") ;
							}
							else
							{
								print("<option value=".$i.">".$i."</option>\n") ;
							}
						}
						else
						{
							$nbnoutil++;
						}
					}
					print("\t\t\t\t\t</select></td>\n") ;			
					print("\t\t\t\t</tr>\n") ;
					
                    $modulesList = mysql_fetch_array(dbQuery('SELECT intitule, no_semestre
                        FROM module
						WHERE `id-module` = '.$_POST['moduleID'])) ;
					
                    print("\t\t\t\t<tr>\n") ;   
                    print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Module associ&eacute;</td><td width=\"100\" align=\"left\">".$modulesList['intitule']." (semestre".$modulesList['no_semestre'].")</td>");
					print("\t\t\t\t</tr>\n") ;		
					
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"matiereModule\" value=\"".$_POST['moduleID']."\"><input type=\"hidden\" name=\"matiereMod\" value=\"true\"><input type=\"hidden\" name=\"matiereID\" value=\"$eID\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkMatiere('defaultForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des mati&egrave;res : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			// si on ne connait pas encore le module
			if (!isset($_POST['moduleID']))
			{
				// si aucun diplome n'est defini alors on les affiche
				if (!isset($_POST['dipID']))
				{
					print("\t\t\t<center><b>Choisissez un dipl&ocirc;me</b></center>\n") ;
					$dipList = dbQuery('SELECT  intitule, `id-diplome`
						FROM diplome
						ORDER BY intitule') ;
					
					$dipCount = mysql_num_rows($dipList) ;
				
					// aucun diplome pour le moment
					if ($dipCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
						return ;
					}
					
					print("\t\t\t<center><form action=\"admin.php?w=matieres&a=del\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $dipCount ; $i++)
					{
						$fDipList = mysql_fetch_array($dipList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"><b>Dipl&ocirc;me :</b> {$fDipList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matiereDel\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
			
				// un diplome est defini, on affiche tous les modules
				else
				{
					// infos sur le diplome
					$dipInfo = dbQuery('SELECT intitule
						FROM diplome
						WHERE `id-diplome` = '.$_POST['dipID']) ;
					$dipInfo = mysql_fetch_array($dipInfo) ;
					
					print("\t\t\t<center><b>Choisissez un module pour le dipl&ocirc;me : {$dipInfo['intitule']}</b></center>\n") ;
					
					$modulesList = dbQuery('SELECT `id-module`, intitule
						FROM module 
						WHERE `id-diplome` = '.$_POST['dipID'].'
						ORDER BY intitule') ;
					
					$modulesCount = mysql_num_rows($modulesList) ;
			
					// aucun module pour le moment
					if ($modulesCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
					}
			
					else
					{
						print("\t\t\t<center><form action=\"admin.php?w=matieres&a=del\" method=\"post\">\n") ;
						print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
						for ($i = 0 ; $i < $modulesCount ; $i++)
						{
							$fModulesList = mysql_fetch_array($modulesList) ;
							print("\t\t\t\t<tr>\n") ;
							print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"moduleID\" value=\"{$fModulesList['id-module']}\" onClick=\"submit()\"> {$fModulesList['intitule']} </td>\n") ;
							print("\t\t\t\t</tr>\n") ;
						}
				
				
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matieresDel\" value=\"Choisir\"></td>\n") ;
						print("\t\t\t\t</tr>\n") ;
						print("\t\t\t</table>\n") ;
						print("\t\t\t</form></center>\n") ;
					}
				}					
			}
				
			// on connait le module : on affiche toutes les matieres
			else
			{
				// liste des matieres
				$matieresList = dbQuery('SELECT intitule, `id-matiere`
					FROM matiere
					WHERE `id-module` = '.$_POST['moduleID'].'
					ORDER BY intitule') ;
						
				// affichage
					
				$matieresCount = mysql_num_rows($matieresList) ;
					
				if ($matieresCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
				}
					
				else
				{					
					print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=matieres\" method=\"post\" onSubmit=\"return checkItemsToDelete($matieresCount)\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $matieresCount ; $i++)
					{
						$fMatieresList = mysql_fetch_array($matieresList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fMatieresList['id-matiere']}\"> {$fMatieresList['intitule']} </td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"matiereDel\" value=\"Supprimer\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
			}
				
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des mati&egrave;res : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=matieres\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF matieres
*/
?>
