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
	if (isset($_GET['a']))
	{
		/****************************
		*     Depot d'un fichier     
		****************************/
		if ($_GET['a'] == "dep")
		{
			require ("deposer_doc.php");
		}
		
		/******************************
		*     Partie visualisation
		******************************/		
		if ($_GET['a'] == "load")
		{
		$res= DB_Query("select * from diplome where `intitule` = '".$_SESSION['diplome']."'");
		$diplome = mysql_fetch_array($res);
		$fics = DB_Query('SELECT * FROM fichier
						WHERE `id-diplome` = "'.$diplome['id-diplome'].'"
						AND `id-etu` = 0
						ORDER BY `id-fichier`');
		$nbFic = mysql_num_rows($fics);
		//print("<table  cellspacing=\"1\" cellpadding=\"0\>") ;
		if ($nbFic == 0)
		{
			print("<table cellspacing=\"3\" cellpadding=\"0\">") ;
			print("<tr>\n") ;
			print("<td width=\"600\" align=\"center\"> ");
			print "Aucun fichier pr&eacute;sent pour ce diplome !" ;
			print("</td></tr></table>") ;
		}
		elseif ($nbFic > 0)
		{
			print ("<table align=\"center\" border=\"1\" width=\"90%\" cellspacing=\"1\"><tr><th>Enseignant</th><th>Fichier</th><th>Commentaire</th></tr>");
			for ($i=0; $i<$nbFic; $i++)
			{
				$fichier = mysql_fetch_array($fics);
				$fensDetails = DB_Query('SELECT nom, prenom
									FROM enseignant
									WHERE `id-enseignant` = "'.$fichier['id-ens'].'"');
				$ensDetails = mysql_fetch_array($fensDetails);
				print("\t\t\t\t<tr>\n") ;
				print("<td nowrap> ".$ensDetails['nom']." ".$ensDetails['prenom']." </td>") ;
				print("<td><a href=\"".$fichier['URL']."\">".$fichier['titre']."</a></td>") ;
				$fichier['commentaire'] = nl2br($fichier['commentaire']);
				print("<td nowrap>".$fichier['commentaire']."</td>") ;
				print("\t\t\t\t</tr>\n") ;
				}
			}
			print("\t\t\t</table>\n");
		}
		
		/*********************
		*     Deconnexion     
		*********************/
		if ($_GET['a'] == "logout")
		{
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
** EOF secretaire
*/
?>
