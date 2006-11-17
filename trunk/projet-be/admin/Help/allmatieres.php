<?php
/*
** Fichier : allenseignants
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de l'affichage de tous les enseignants
*/



/*
** IMPORTANT : cette page peut etre utilisee a partir de divers endroits le
**	nom du formulaire ainsi que le nom du champs peuvent etre passes dans
**	les variables $_GET['f'] et $_GET['t'] qui ont des valeurs par defaut
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{
	centeredInfoMessage(3, 3, "Aide : liste des mati&egrave;res") ;
	dbConnect() ;
	$allMatiere = dbQuery('SELECT *
		FROM matiere') ;
		
	$matiereCount = mysql_num_rows($allMatiere) ;
	// si il n'y a aucune matiere
	if ($matiereCount == 0)
	{
		centeredInfoMessage(2, 2, "Aucune mati&egrave;re pour le moment") ;
	}
	
	else
	{
		$formName = "defaultForm" ;	// valeur par defaut
		if (isset($_GET['f'])) { $formName = $_GET['f'] ; }
		
		$fieldName = "matiere" ;	// valeur par defaut
		if (isset($_GET['t'])) { $fieldName = $_GET['t'] ; }
		
		// on ne connait pas encore le module dont on liste les matieres
		if (!isset($_POST['id']))
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
					print("\t\t\t<center><form action=\"help.php?w=allmatieres&f=$formName&t=$fieldName\" method=\"post\">\n") ;
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
					print("\t\t\t<center><form action=\"help.php?w=allmatieres&f=$formName&t=$fieldName\" method=\"post\">\n") ;
					print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
					for ($i = 0 ; $i < $modulesCount ; $i++)
					{
						$fModulesList = mysql_fetch_array($modulesList) ;
						print("\t\t\t\t<tr>\n") ;
						print("\t\t\t\t\t<td width=\"500\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fModulesList['id-module']}\" onClick=\"submit()\"> {$fModulesList['intitule']} </td>\n") ;
						print("\t\t\t\t</tr>\n") ;
					}
				
				
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"500\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"moduleMod\" value=\"Choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
					print("\t\t\t</table>\n") ;
					print("\t\t\t</form></center>\n") ;
				}
			}
		} // end of if not id
			
		// on connait le module dont on veut afficher les matieres
		else
		{
				
				
			$allMatiere = dbQuery('SELECT intitule, `id-matiere`
				FROM matiere
				WHERE `id-module` = '.$_POST['id'].'
				ORDER BY intitule') ;
					
			// on verifie si le resutat est correct
			$matiereCount = mysql_num_rows($allMatiere) ;
			if ($matiereCount == 0)
			{
				centeredInfoMessage(2, 2, "El&eacute;ment vide") ;
			}
				
			else
			{		
				print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Titre</b></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			
				for ($i = 0 ; $i < $matiereCount ; $i++)
				{
					$details = mysql_fetch_array($allMatiere) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-matiere']}</td><td width=\"300\" align=\"left\">{$details['intitule']}</td>") ;
					print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-matiere']}', '$formName', '$fieldName')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
		
				print("\t\t\t</table></center>\n") ;
			}	
		}
	}
	
	dbClose() ;
} // end of if session connecte


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}

/*
** EOF allenseignants
*/
?>
