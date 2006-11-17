<?php
/*
** Fichier : information
** Date de creation : 5/08/2005
** Auteurs : Avetisyan Gohar
** Version : 1.0
** Description : Fichier inclu charge de la gestion de l'information du jour, des evenemants importants
**      cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : section principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration de l'information : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=information&a=add\">ajouter information</a> ] - ") ;
		print("[ <a href=\"admin.php?w=information&a=mod\"> modifier information</a> ] - ") ;
		print("[ <a href=\"admin.php?w=information&a=del\"> supprimer information</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	

	
	
	// une action est precisee
	else
	{
		
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			// boite d'edition
			dbConnect() ;
			
			$infoExists = dbQuery('SELECT COUNT(`id-information`) AS infNb
				FROM information') ;

			$infoExists = mysql_fetch_array($infoExists) ;
			
			/*if ($infoExists['infNb'] != 0)
			{
				centeredInfoMessage(2, 2, "Une information existe déjà!") ;
				return ;
			}*/

			dbClose() ;
			
			centeredInfoMessage(3, 3, "Administration de l'information : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=information\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\" border=\"0\">\n") ;
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Titre du message</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"informationTitre\" size=\"40\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
            print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b>Etat de la publication :</b> </td><td colspan=\"2\" align=\"left\"><select name=\"informationState\" class=\"defaultInput\"><option value=\"1\">En ligne</option><option value=\"0\">Hors ligne / En edition</option></select></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
            
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><b> Contenu du message *</b></td>\n") ;
			print("\t\t\t\t</tr>\n") ;			
			
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"informationContenu\"></textarea><br><br></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"center\" colspan=\"3\"><br><input type=\"hidden\" name=\"infoAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkInformationAdd('defaultForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
		} // end of if add
		
		
		
		
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration de l'information : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				//connexion a la base de donnees et recuperation de l'info
                                dbConnect() ;

				$infoList = dbQuery('SELECT `id-information`, titre
					FROM information
                    ORDER BY titre') ;

				$infoCount = mysql_num_rows($infoList) ;

                                //aucune information pour le moment
				if ($infoCount == 0)
				{
					centeredInfoMessage(2, 2, "Aucune information pour le moment") ;
					return ;
				}

				print("\t\t\t<center><form action=\"admin.php?w=information&a=mod\" method=\"post\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;

				for ($i = 0 ; $i < $infoCount ; $i++)
				{
				       $fInfoList = mysql_fetch_array($infoList) ;
                       $titre = stripslashes($fInfoList['titre']);
				       print("\t\t\t\t<tr>\n") ;
                        print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fInfoList['id-information']}\" onClick=\"submit()\"><b> {$titre} </b>- <a href=\"javascript:openHelp('information&titre={$fInfoList['titre']}')\">voir le contenu</a></td>\n") ;
				       print("\t\t\t\t</tr>\n") ;
				}

				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"infoMod\" value=\"Modifier\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;

                                dbClose() ;

            }
            // on connait l'element a modifier
            else
            {
                    // connexion a la base et recuperation des infos
			       dbConnect() ;
			       
			       if (isset($_GET['id'])) { $eID = $_GET['id'] ; }
			       else { $eID = $_POST['id'] ; }

			       $infoDetails = dbQuery("SELECT titre, contenu, URL
			                            FROM information
                                        WHERE `id-information` = '$eID'") ;

                               // on verifie si le resutat est correct
			       $infoExists = mysql_num_rows($infoDetails) ;
			       if ($infoExists == 0)
			       {
                        centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cette information") ;
				        return ;
			       }

			       $infoDetails = mysql_fetch_array($infoDetails) ;

                   $infoCount = dbQuery('SELECT count(`id-information`) as number
                                        FROM information') ;
                   $infoCount = mysql_fetch_array($infoCount) ;
			       $infoCount = $infoCount['number'] ;

                    $content = stripslashes($infoDetails['contenu']);
                    $titre = stripslashes($infoDetails['titre']);
                    
			       print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=information\" method=\"post\">\n") ;
			       print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;

			       print("\t\t\t\t<tr>\n") ;
			       print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Titre de l'information</b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"informationTitre\" size=\"40\" value=\"{$titre}\"></td>\n") ;
			       print("\t\t\t\t</tr>\n") ;
        
                    print("\t\t\t\t<tr>\n") ;
                    print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b>Etat de la publication :</b> </td><td colspan=\"2\" align=\"left\"><select name=\"informationState\" class=\"defaultInput\"><option value=\"1\"" . (($infoDetails['etat'] == 1)?(" selected"):("")) . ">En ligne</option><option value=\"0\"" . (($infoDetails['etat'] == 0)?(" selected"):("")) . ">Hors ligne / En edition</option></select></td>\n") ;
                    print("\t\t\t\t</tr>\n") ;
            
			       print("\t\t\t\t<tr>\n") ;
			       print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\" valign=\"top\"><b> Contenu de l'information *</b></td>\n") ;
			       print("\t\t\t\t</tr>\n") ;

			       print("\t\t\t\t<tr>\n") ;
			       print("\t\t\t\t\t<td width=\"700\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"informationContenu\">{$content}</textarea><br><br></td>\n") ;
			       print("\t\t\t\t</tr>\n") ;

			       print("\t\t\t\t<tr>\n") ;
			       print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"infoMod\" value=\"true\"><input type=\"hidden\" name=\"informationID\" value=\"$eID\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkInformationMod('defaultForm')\"></td>\n") ;
			       print("\t\t\t\t</tr>\n") ;
			       print("\t\t\t</table>\n") ;
			       print("\t\t\t</form></center>\n") ;

			       dbClose() ;

                        }
                }
     		        // end of if mod



		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration de l'information : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$infoList = dbQuery('SELECT `id-information`, titre
				FROM information
				ORDER BY titre') ;

			$infoCount = mysql_num_rows($infoList) ;

			// aucune information pour le moment
			if ($infoCount == 0)
			{
				centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
			}

			else
			{
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=information\" method=\"post\" onSubmit=\"return checkItemsToDelete($infoCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $infoCount ; $i++)
				{
					$fInfoList = mysql_fetch_array($infoList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fInfoList['id-information']}\"> {$fInfoList['titre']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}

				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"infoDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del

		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration du l'information : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=information\">\n") ;
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
