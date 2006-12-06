<?php
/*
** Fichier : etudiant
** Date de creation : 15/08/2004
** Auteurs : Avetisyan Gohar
** Auteurs deuxieme version : Julien SIEGA, Emilien PERICO
** Version : 2.0
** Description : Consultation  des fichiers déposés
*/

// on verifie toujours que cette page a ete appelee a partir de l'espace reserve
if (is_numeric(strpos($_SERVER['PHP_SELF'], "espacereserve.php")))
{
	/*
	if (!isset($_GET['a']))
	{
		print("\t\t\t<center>[ <a href=\"espacereserve.php?p=connexion&w=etudiants&a=load\"><u>Consulter les fichiers &agrave; t&eacute;l&eacute;charger</u></a> ] - ") ;
	}
	else
	*/
		
	if (isset($_GET['a']))
	{
		/****************************
		*     Depot d'un fichier     
		****************************/
		if ($_GET['a'] == "dep")
		{
			require ("deposer_doc.php");
		}
		
		/* visu ...*/
		if($_GET['a']== "tele")
		{
			if(isset($_POST['depot']))
			{
				$valeur=stripslashes($_POST['depot']);
				$valeur2=stripslashes($_POST['file']);
				$valeur3=stripslashes($valeur2);
				// teste de toutes les facons
				copy($_POST['file'], $valeur."".$_POST['fic']);
				//if ($success) { $finalCV = $fileId."_fich.".$finalExtension ; }
			}
			else
			{
				print("\t\t\t<center><form name=\"Form\" action=\"espacereserve.php?p=connexion&w=etudiants&a=tele\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
				print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\>\n") ;
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td align=\"left\"><input type=\"hidden\" name=\"fic\" value=\"".$_GET['fic']."\"><input type=\"hidden\" name=\"file\" value=\"".$_GET['chemin']."\"><b> repertoire de telechargement </b></td><td width=\"700\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"depot\" size=\"40\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print "<tr><td><input class=\"defaultButton\" type=\"submit\" name=\"addButton\" value=\"D&eacute;poser\" ></td></tr>  ";      
				print("\t\t\t\t</table");
			}
		}
		
		/****************************************************
		*     Partie modification (login ou mot de passe)    
		****************************************************/
		if($_GET['a']=='modif')
		{
			// on include le fichier modif
			require("modifier.php");
		}
		
		/*********************
		*     Deconnexion     
		*********************/
		if ($_GET['a'] == "logout")
		{
			// rien de critique
			session_destroy() ;
			print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion\">\n") ;
		} 
		print("<center><table><tr><td><br><br><a href='espacereserve.php?p=connexion&w=etudiants'>retour</a></td></tr></table></center>");
	}
}
else
{
	print("<table><tr><td>") ;
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
	print("</td></tr></table>") ;
}
/*
** EOF etudiants
*/
?>
