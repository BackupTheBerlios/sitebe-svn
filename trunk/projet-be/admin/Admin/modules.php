<?php
/*
** Fichier : modules
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
		centeredInfoMessage(3, 3, "Administration des modules : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=modules&a=add\">ajouter module</a> ] - ") ;
		print("[ <a href=\"admin.php?w=modules&a=mod\"> modifier module</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=modules&a=del\"> supprimer module</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{	
		
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			centeredInfoMessage(3, 3, "Administration des modules : ajout") ;
			dbConnect() ;
			
			// liste des diplomes
			$dipList = dbQuery('SELECT *
				FROM diplome
				ORDER BY intitule') ;
            
            $respList = dbQuery('SELECT *
                FROM enseignant
                ORDER BY nom');
                
            $nodeList = dbQuery('SELECT *
                FROM node
                ORDER BY titre');
                    
                    
			$dipCount = mysql_num_rows($dipList) ;
			
			if ($dipCount == 0)
			{
				centeredInfoMessage(2, 2, "Il faut d'abord ajouter des dipl&ocirc;mes") ;
				return ;
			}
			
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=modules\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Intitul&eacute; du module *</b></td><td width=\"400\" align=\"left\"><input class=\"defaultInput\" name=\"moduleIntitule\" size=\"60\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;			
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Dipl&ocirc;me associ&eacute; </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"moduleDiplome\">\n") ;
			for ($i = 0 ; $i < $dipCount ; $i++)
			{
				$dipDetail = mysql_fetch_array($dipList) ;
				print("<option>{$dipDetail['intitule']}</option>") ;
			}
			print("\n\t\t\t\t\t</select></td>\n") ;			
			print("\t\t\t\t</tr>\n") ;
			
            print("\t\t\t\t<tr>\n") ;
            print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Enseignant responsable </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"moduleResp\">\n") ;
            while ($dipDetails = mysql_fetch_array($respList))
            {
                $selected = "" ;
                if ($moduleDetails['id-responsable'] == $dipDetails['id-enseignant']) { $selected = " selected" ; }
                print("<option$selected value=\"{$dipDetails['id-enseignant']}\">{$dipDetails['nom']} {$dipDetails['prenom']}</option>") ;
            }
            print("\n\t\t\t\t\t</select></td>") ;
            print("\t\t\t\t</tr>\n") ;
                
            print("\t\t\t\t<tr>\n") ;
            print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Page </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"moduleNode\">\n") ;
            while ($dipDetails = mysql_fetch_array($nodeList))
            {
                $selected = "" ;
                if ($moduleDetails['id_node'] == $dipDetails['ID_NODE']) { $selected = " selected" ; }
                print("<option$selected value=\"{$dipDetails['ID_NODE']}\">{$dipDetails['TITRE']}</option>") ;
            }
            print("\n\t\t\t\t\t</select></td>") ;
            print("\t\t\t\t</tr>\n") ;
            
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><b> Description d&eacute;taill&eacute;e du module (y compris des mati&egrave;res) </b></td>\n") ;
			print("\t\t\t\t</tr>\n") ;			
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" colspan=\"2\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"moduleContenu\"></textarea><br><br></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"moduleAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkModule('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
			dbClose() ;
			
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des modules : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
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
			
					print("\t\t\t<center><form action=\"admin.php?w=modules&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $dipCount ; $i++)
					{
						$fDipList = mysql_fetch_array($dipList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"><b>Dipl&ocirc;me :</b> {$fDipList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"moduleMod\" value=\"Choisir\"></td>\n") ;
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
					
					print("\t\t\t<center><b>Dipl&ocirc;me : {$dipInfo['intitule']}</b></center>\n") ;
					
					$modulesList = dbQuery('SELECT `id-module`, intitule, no_semestre
						FROM module 
						WHERE `id-diplome` = '.$_POST['dipID'].'
						ORDER BY intitule') ;
					
					$modulesCount = mysql_num_rows($modulesList) ;
				
					// aucun module pour le moment
					if ($modulesCount == 0)
					{
						centeredInfoMessage(2, 2, "Element vide") ;
						return ;
					}
			
					print("\t\t\t<center><form action=\"admin.php?w=modules&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $modulesCount ; $i++)
					{
						$fModulesList = mysql_fetch_array($modulesList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fModulesList['id-module']}\" onClick=\"submit()\"> S{$fModulesList['no_semestre']} - {$fModulesList['intitule']} </td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"moduleMod\" value=\"Modifier\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
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
				// boite d'edition
				require("Functions/edition.inc.php") ;
				
				$moduleDetails = dbQuery('SELECT description, intitule, `id-diplome`, id_node, `id-responsable`
					FROM module
					WHERE `id-module` = '.$eID) ;
					
				// on verifie si le resutat est correct
				$moduleExists = mysql_num_rows($moduleDetails) ;
				if ($moduleExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; ce module") ;
					return ;
				}
				
				$moduleDetails = mysql_fetch_array($moduleDetails) ;
					
				$dipList = dbQuery('SELECT *
					FROM diplome
					ORDER BY intitule') ;
                    
                $respList = dbQuery('SELECT *
                    FROM enseignant
                    ORDER BY nom');
										
                $nodeList = dbQuery('SELECT *
                    FROM node
                    ORDER BY titre');
                        
				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=modules\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Intitul&eacute; du module *</b></td><td width=\"400\" align=\"left\"><input class=\"defaultInput\" name=\"moduleIntitule\" size=\"60\" value=\"{$moduleDetails['intitule']}\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;			
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Dipl&ocirc;me associ&eacute; </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"moduleDiplome\">\n") ;
				while ($dipDetails = mysql_fetch_array($dipList))
				{
					$selected = "" ;
					if ($moduleDetails['id-diplome'] == $dipDetails['id-diplome']) { $selected = " selected" ; }
					print("<option$selected>{$dipDetails['intitule']}</option>") ;
				}
				print("\n\t\t\t\t\t</select></td>") ;
				print("\t\t\t\t</tr>\n") ;
                
                print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Enseignant responsable </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"moduleResp\">\n") ;
				while ($dipDetails = mysql_fetch_array($respList))
				{
					$selected = "" ;
					if ($moduleDetails['id-responsable'] == $dipDetails['id-enseignant']) { $selected = " selected" ; }
					print("<option$selected value=\"{$dipDetails['id-enseignant']}\">{$dipDetails['nom']} {$dipDetails['prenom']}</option>") ;
				}
				print("\n\t\t\t\t\t</select></td>") ;
				print("\t\t\t\t</tr>\n") ;

                print("\t\t\t\t<tr>\n") ;
                print("\t\t\t\t\t<td width=\"300\" align=\"left\"><b> Page </b></td><td width=\"400\" align=\"left\"><select class=\"defaultInput\" name=\"moduleNode\">\n") ;
                while ($dipDetails = mysql_fetch_array($nodeList))
                {
                    $selected = "" ;
                    if ($moduleDetails['id_node'] == $dipDetails['ID_NODE']) { $selected = " selected" ; }
                    print("<option$selected value=\"{$dipDetails['ID_NODE']}\">{$dipDetails['TITRE']}</option>") ;
                }
                print("\n\t\t\t\t\t</select></td>") ;
                print("\t\t\t\t</tr>\n") ;
            
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><b> Description d&eacute;taill&eacute;e du module (y compris des mati&egrave;res) </b></td>\n") ;
				print("\t\t\t\t</tr>\n") ;			
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" colspan=\"2\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"moduleContenu\">{$moduleDetails['description']}</textarea><br><br></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"moduleMod\" value=\"true\"><input type=\"hidden\" name=\"moduleID\" value=\"$eID\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkModule('defaultForm')\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
				
				dbClose() ;
			}
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des modules : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			
			// si aucun diplome
			if (!isset($_POST['dipID']))
			{
				print("\t\t\t<center><b>Choisissez un dipl&ocirc;me</b></center>\n") ;
				$dipList = dbQuery('SELECT  intitule, `id-diplome`
					FROM diplome
					ORDER BY intitule') ;
					
				$dipCount = mysql_num_rows($dipList) ;
				
				// aucun section pour le moment
				if ($dipCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
				}
			
				else
				{
					print("\t\t\t<center><form action=\"admin.php?w=modules&a=del\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $dipCount ; $i++)
					{
						$fDipList = mysql_fetch_array($dipList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"dipID\" value=\"{$fDipList['id-diplome']}\" onClick=\"submit()\"><b>Page :</b> {$fDipList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"moduleDel\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
			}
			
			// un diplome est defini
			else
			{
				// infos sur le diplome
				$dipInfo = dbQuery('SELECT intitule
					FROM diplome
					WHERE `id-diplome` = '.$_POST['dipID']) ;
				$dipInfo = mysql_fetch_array($dipInfo) ;
				
				print("\t\t\t<center><b>Dipl&ocirc;me : {$dipInfo['intitule']}</b></center>\n") ;
				
				$moduleList = dbQuery('SELECT `id-module`, intitule
					FROM module 
					WHERE `id-diplome` = '.$_POST['dipID'].'
					ORDER BY intitule') ;
					
				$moduleCount = mysql_num_rows($moduleList) ;
				
				// aucun diplome pour le moment
				if ($moduleCount == 0)
				{
					centeredInfoMessage(2, 2, "Element vide") ;
				}
			
				else
				{					
					print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=modules\" method=\"post\" onSubmit=\"return checkItemsToDelete($moduleCount)\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $moduleCount ; $i++)
					{
						$fModuleList = mysql_fetch_array($moduleList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fModuleList['id-module']}\">{$fModuleList['intitule']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"moduleDel\" value=\"Supprimer\"></td>\n") ;
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
			centeredErrorMessage(3, 3, "Administration des modules : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=modules\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF modules
*/
?>
