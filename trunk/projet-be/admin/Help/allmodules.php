<?php
/*
** Fichier : allmodules
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de l'affichage de tous les modules
*/



/*
** IMPORTANT : cette page peut etre utilisee a partir de divers endroits le
**	nom du formulaire ainsi que le nom du champs peuvent etre passes dans
**	les variables $_GET['f'] et $_GET['t'] qui ont des valeurs par defaut
*/



// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{
	centeredInfoMessage(3, 3, "Aide : liste des modules") ;
	dbConnect() ;
	
	$formName = "defaultForm" ;	// valeur par defaut
	if (isset($_GET['f'])) { $formName = $_GET['f'] ; }
		
	$fieldName = "matiereModule" ;	// valeur par defaut
	if (isset($_GET['t'])) { $fieldName = $_GET['t'] ; }
	
	// on ne connait pas le diplome 
	if (!isset($_POST['dipID']))
	{
		print("\t\t\t<center><b>Choisissez le dipl&ocirc;me du module</b></center>\n") ;
		
		$dipList = dbQuery('SELECT *
			FROM diplome
			ORDER BY intitule') ;
		
		$dipCount = mysql_num_rows($dipList) ;
		// si il n'y a aucune page
		if ($dipCount == 0)
		{
			centeredInfoMessage(2, 2, "Aucun dipl&ocirc;me pour le moment") ;
		}
	
		else
		{
			print("\t\t\t<center><form action=\"help.php?w=allmodules&f=$formName&t=$fieldName\" method=\"post\">\n") ;
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
	
	// on connait le diplome, on affiche la liste des modules
	else
	{	
	
		$modulesList = dbQuery('SELECT intitule, `id-module`
			FROM module
			WHERE `id-diplome` = '.$_POST['dipID'].'
			ORDER BY intitule') ;
		
		$modCount = mysql_num_rows($modulesList) ;
		// si il n'y a aucune page
		if ($modCount == 0)
		{
			centeredInfoMessage(2, 2, "Aucun module pour le moment") ;
		}
	
		else
		{
			print("\t\t\t<center><table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b>Identifiant</b></td><td colspan=\"2\" align=\"left\"><b>Intitul&eacute;</b></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			
			for ($i = 0 ; $i < $modCount ; $i++)
			{
				$details = mysql_fetch_array($modulesList) ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"100\" align=\"left\">{$details['id-module']}</td><td width=\"300\" align=\"left\">{$details['intitule']}</td>") ;
				print("<td align=\"right\" width=\"200\"><input onClick=\"setLink('{$details['id-module']}', '$formName', '$fieldName')\" class=\"defaultButton\" type=\"button\" value=\"choisir\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
			}
		
			print("\t\t\t</table></center>\n") ;
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
** EOF allmodules
*/
?>
