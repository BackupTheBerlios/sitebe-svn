<?php
/*
** Fichier : enseignants
** Date de creation : 5/08/2005
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier inclu charge de la gestion des enseignants cote administration
**	ajout, suppression, modification
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : enseignants
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des enseignants : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=enseignants&a=add\">ajouter &eacute;l&eacute;ment</a> ] - ") ;
		print("[ <a href=\"admin.php?w=enseignants&a=mod\"> modifier &eacute;l&eacute;ment</a> ] - ") ;	
		print("[ <a href=\"admin.php?w=enseignants&a=del\"> supprimer &eacute;l&eacute;ment</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{

		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			centeredInfoMessage(3, 3, "Administration des enseignants : ajout") ;
			print("\t\t\t<form name=\"enseignantForm\" action=\"database.php?w=enseignants\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> nom de l'enseignant : *</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"nomEnseignant\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> prénom de l'enseignant : *</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"prenomEnseignant\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> mail :</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"mailEnseignant\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

                        print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> login :</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"loginEnseignant\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

                        print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> mot de passe :</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"mdpEnseignant\" size=\"25\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;

			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"enseignantAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButon\" value=\"Ajouter\" onClick=\"checkEnseignant('enseignantForm')\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form>\n") ;
			
		} // end of if add
		
				
		// modification d'un element
		elseif ($_GET['a'] == "mod")
		{
			centeredInfoMessage(3, 3, "Administration des enseignants : modification") ;
			// on ne connait pas encore l'element a modifier on affiche la liste
			if (!isset($_POST['id']) && !isset($_GET['id']))
			{
				// connexion a la base de donnees et recuperation des infos
				dbConnect() ;
				$enseignantList = dbQuery('SELECT `id-enseignant`, nom, prenom
					FROM enseignant
					ORDER BY nom, prenom') ;
					
				$enseignantCount = mysql_num_rows($enseignantList) ;
				
				// aucun enseignant pour le moment
				if ($enseignantCount == 0)
				{
					centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
				}
				
				else
				{
					print("\t\t\t<center><form action=\"admin.php?w=enseignants&a=mod\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					
					for ($i = 0 ; $i < $enseignantCount ; $i++)
					{
						$fEnseignantList = mysql_fetch_array($enseignantList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fEnseignantList['id-enseignant']}\" onClick=\"submit()\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input type=\"hidden\" name=\"enseignantMod\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkEnseignant('enseignantForm')\"></td>\n") ;
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
				
				$enseignantDetails = dbQuery('SELECT nom, prenom, mail, login, mdp
					FROM enseignant
					WHERE `id-enseignant` = "'.$eID.'"') ;
					
				// on verifie si le resutat est correct
				$enseignantExists = mysql_num_rows($enseignantDetails) ;
				if ($enseignantExists == 0)
				{
					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cet enseigant") ;
				}
				
				else
				{
					$enseignantCount = dbQuery('SELECT count(`id-enseignant`) as number
						FROM enseignant') ;
					$enseignantCount = mysql_fetch_array($enseignantCount) ;
					$enseignantCount = $enseignantCount['number'] ;
					
					$enseignantDetails = mysql_fetch_array($enseignantDetails) ;
					print("\t\t\t<form name=\"enseignantForm\" action=\"database.php?w=enseignants\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Nom de l'enseignant : * </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"nomEnseignant\" size=\"25\" value=\"{$enseignantDetails['nom']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Prénom de l'enseignant : * </b></td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"prenomEnseignant\" size=\"25\" value=\"{$enseignantDetails['prenom']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Mail :</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"mailEnseignant\" size=\"25\" value=\"{$enseignantDetails['mail']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ; 

					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Login :</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"loginEnseignant\" size=\"25\" value=\"{$enseignantDetails['login']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ; 

					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Mot de passe :</td><td width=\"300\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"mdpEnseignant\" size=\"25\" value=\"{$enseignantDetails['mdp']}\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;

					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"enseignantID\" value=\"{$eID}\"><input type=\"hidden\" name=\"enseignantMod\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkEnseignant('enseignantForm')\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
	
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form>\n") ;
				}
				
				dbClose() ;
			}
		
		} // end of if mod
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des enseignants : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$enseignantList = dbQuery('SELECT `id-enseignant`, nom, prenom
				FROM enseignant
				ORDER BY nom, prenom') ;
					
			$enseignantCount = mysql_num_rows($enseignantList) ;
				
			// aucun enseignant pour le moment
			if ($enseignantCount == 0)
			{
				centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
			}
			
			else
			{
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=enseignants\" method=\"post\" onSubmit=\"return checkItemsToDelete($enseignantCount)\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $enseignantCount ; $i++)
				{
					$fEnseignantList = mysql_fetch_array($enseignantList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fEnseignantList['id-enseignant']}\"> {$fEnseignantList['nom']} {$fEnseignantList['prenom']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"enseignantDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des enseignants : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=enseignants\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF enseignant
*/
?>
