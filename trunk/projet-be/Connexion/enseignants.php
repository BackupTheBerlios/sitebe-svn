<?php
/*
** Fichier : enseignant
** Date de creation : 15/08/2004
** Auteurs : Avetisyan Gohar
** Version : 1.0
** Auteurs : julien SIEGA,emilien PERICO
** version : 2.0
** 
** Version Finale ! VAR Sovanramy & Dang Laurent !!
** Description : Fichier inclu charge de la gestion des fichiers par un enseignant
**	depot, suppression
*/
// on verifie toujours que cette page a ete appelee a partir de l'espace reserve
if (is_numeric(strpos($_SERVER['PHP_SELF'], "espacereserve.php")))
{
	/*
	// aucune action precisee : section principal
	if (!isset($_GET['a']))
	{
		print("<table border=\"1\">");
		print("<tr><td align='right'> <a href=\"espacereserve.php?p=connexion&w=enseignants&a=dep\"><u>D&eacute;poser des fichiers</u></a></td></tr>") ;
		print("<tr><td align='center'> <a href=\"espacereserve.php?p=connexion&w=enseignants&a=undep\">Supprimer des fichiers</a></td></tr>") ;
		print("<tr><td align='center'> <a href=\"espacereserve.php?p=connexion&w=enseignants&a=excel\">Gestion excel</a></td></tr>") ;
		print("<td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=visualisation\"><u>Visualisation</u></a></td>") ;
		print("<td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=note\"><u>saisie des notes</u></a></td>") ;
		print("</tr>\n") ;	
		print "</table>";
	}
	else*/
	if (isset($_GET['a']))
	{
		/****************************************************
		*     Partie modification (login ou mot de passe)    
		****************************************************/
		if($_GET['a']=='modif')
		{
			// on include le fichier modif
			require("modifier.php");
		}
		
		/************************************************************
		*     Affichage des options apres le choix de la matiere     
		************************************************************/
		if ($_GET['a'] == "acces")
		{
			$intituleMat = DB_Query('SELECT intitule FROM matiere WHERE `id-matiere` ="'.$_POST['matiereListe'].'"');
			$intituleFetch = mysql_fetch_array($intituleMat);
			$inti = $intituleFetch['intitule'];
			
			print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("<tr>\n") ;
			print("<td align=\"center\" width=\"800\"><br><b> Enseignement : ");
			echo  $inti;
			print("<br><br></b></td>") ;
			print("</tr>\n") ;
			print("</table>\n") ;
			
			print("<table cellspacing=\"1\" cellpadding=\"0\">\n");
			print("<tr><td align=\"center\"> <a href=\"espacereserve.php?p=connexion&w=enseignants&a=dep&mat=".$_POST['matiereListe']."\">D&eacute;poser des fichiers</a></td></tr>") ;
			print("<tr><td align=\"center\"> <a href=\"espacereserve.php?p=connexion&w=enseignants&a=undep&mat=".$_POST['matiereListe']."\">Supprimer des fichiers</a></td></tr>") ;
			print("<tr><td align=\"center\"> <a href=\"espacereserve.php?p=connexion&w=enseignants&a=excel&mat=".$_POST['matiereListe']."\">Gestion excel</a></td></tr>") ;
			print("<tr><td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=visualisation&mat=".$_POST['matiereListe']."\">Visualisation</a></td></tr>") ;
			print("<tr><td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=note&mat=".$_POST['matiereListe']."\">Saisie des notes</a></td></tr>") ;
			print("<tr><td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=mail&b=form&mat=".$_POST['matiereListe']."\">Envoyer un mail a tous les etudiants</a></td></tr>") ;
			print("</tr>\n") ;	
			print("</table>");
		}
		
		/****************************
		*     Depot d'un fichier     
		****************************/
		if ($_GET['a'] == "dep")
		{
			require ("deposer_doc.php");
		}
		
		/**********************************
		*     Suppression d'un fichier     
		**********************************/
		if ($_GET['a'] == "undep")
		{
			$id_diplome = DB_Query('SELECT `id-diplome` FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
			$dip_count = mysql_num_rows($id_diplome);
			$id_diplome = mysql_fetch_array($id_diplome);
			$id_diplome = $id_diplome['id-diplome'];
			$fichList = DB_Query('SELECT `id-fichier`, titre FROM fichier WHERE `id-diplome` = "'.$id_diplome.'" and `id-enseignant` ="'.$_SESSION['id-enseignant'].'" ORDER BY titre') ;
			$fichCount = mysql_num_rows($fichList) ;
			// aucun enseignant pour le moment
			if ($fichCount == 0)
			{
				print("<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("<tr>\n") ;
				print("<td width=\"600\" align=\"center\"> ");
				print "Aucun fichier d&eacute;pos&eacute; pour ".$_SESSION['diplome']." !" ;
				print("</td></tr>") ;
				print("</table>") ;
			}
			else
			{
				print("<center><form name=\"deleteForm\" action=\"espacereserve.php?w=enseignants&a=su\" method=\"post\" onSubmit=\"return checkItemsToDelete($fichCount)\">\n") ;
				print("<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				for ($i = 0 ; $i < $fichCount ; $i++)
				{
					$fFichList = mysql_fetch_array($fichList) ;
					print("<tr>\n") ;
					print("<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fFichList['id-fichier']}\"> {$fFichList['titre']} </td>\n") ;
					print("</tr>\n") ;
				}
				print("<tr>\n") ;
				print("<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"fileUndep\" value=\"Supprimer\"></td>\n") ;
				print("</tr>\n") ;
				print("</table>\n") ;
				print("</form></center>\n") ;
			}
		}
		
		/***********************************
		*     Importer un fichier excel     
		***********************************/
		/* choix fichier excel */
		if($_GET['a']== 'excel')
		{
			print("<center><form name=\"valideExcel\" action=\"espacereserve.php?w=enseignants&a=enregexcel\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
			print("<table>");
			print("<tr><td align=\"left\">Selection du fichier :</td><td width=\"700\" align=\"left\" colspan=\"3\"><input type=\"file\" name=\"fichierexcel\" class=\"defaultInput\" size=\"40\"></td></tr>") ;
			print("<tr><td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"fileexcel\" value=\"Valider\"></td>\n") ;
			print("</tr>\n");
			print("</table>");
			print("</form></center>\n") ;
		}
		
		/* traitement fichier excel */
		if($_GET['a']=='enregexcel')
		{
			require 'fonction_csv.inc.php';
			$file = $_FILES['fichierexcel']['name'];
			$file2 = $_FILES['fichierexcel']['tmp_name'];
			@ $success = move_uploaded_file($file2, "Tmp/".$file) ;
			$tab_fichier = traite_fichier("Tmp/".$file);
			//print_r($tab_fichier);
			$tableau = traite_tableau($tab_fichier);
			//print_r($tableau);
			insertion_base($tableau);   
			unlink("Tmp/".$file);
//			echo "<table align='center'><tr><td><h2> L'insertion s'est bien deroul&eacute;e ... Redirection ... </h2></td></tr></table>";
			print("<meta http-equiv=\"refresh\" content=\"2;url=espacereserve.php?p=connexion&w=enseignants\">\n") ;
		}
		
		/******************************************
		*    partie visualisation des fichiers     
		******************************************/
		/* visualiser */
		if($_GET['a'] == 'visualisation')
		{
			$id_diplome1 = DB_Query('SELECT `id-diplome` FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
			$id_diplome2 = mysql_fetch_array($id_diplome1);
			$id_diplome = $id_diplome2['id-diplome'];
			$fichList = DB_Query('SELECT `id-fichier`, titre, URL FROM fichier WHERE `id-diplome` = "'.$id_diplome.'" and `id-enseignant` ="'.$_SESSION['id-enseignant'].'"	ORDER BY titre') ;
			$fichCount = mysql_num_rows($fichList) ;
			// aucun enseignant pour le moment
			if ($fichCount == 0)
			{
				print("<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("<tr>\n") ;
				print("<td width=\"600\" align=\"center\"> ");
				print "Aucun fichier d&eacute;pos&eacute; pour ".$_SESSION['diplome']." !" ;
				print("</td></tr>\n") ;
				print("</table>") ;
			}
			else
			{	
				$dip = explode(" ", $_SESSION['diplome']);
				print "<table align='center'>";
				while($liste_fichier = mysql_fetch_array($fichList))
				{
					print "<tr><td>";
					print "titre : ".$liste_fichier['titre']."  </td><td> <a href='Data/Telechargement/".$dip[0].$dip[1]."/".$liste_fichier['URL']."'> visualisation du fichier</a>";
					print "</td></tr>";
				}
				print "</table>";
			}
		}
		
		/***********************************************
		*     partie qui permet de gerer les notes      
		***********************************************/
		/* note ? */
		if($_GET['a'] == 'note')
		{
			$id_diplome1 = DB_Query('SELECT `id-diplome` FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
			$id_diplome2 = mysql_fetch_array($id_diplome1);
			$id_diplome = $id_diplome2['id-diplome'];
			$requette_matiere = "select * from matiere where `id-module` in (select `id-module` from module where `id-responsable`='".$_SESSION['id-enseignant']."' and `id-diplome`='".$id_diplome."') ";
			//echo $requette_matiere;
			$resultat_matiere = DB_Query($requette_matiere);
			$nb_ligne = mysql_num_rows($resultat_matiere);
			if ($nb_ligne == 0)
			{
				print("<table><tr><td>") ;
				print("aucune matiere disponible, redirection ...") ;
				print("</td></tr>") ;
				print("<tr><td>") ;
				print("<meta http-equiv='refresh' content='2;url=espacereserve.php?p=connexion&w=enseignants'>");
				print("</td></tr></table>") ;
			}
			else
			{ 
				//debut de la creation de la liste deroulante qui va contenir les différentes matieres
				print("<center><form name=\"validenote\" action=\"espacereserve.php?w=enseignants&a=enregnote\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
				print "<table><tr>";
				print "<td>Liste des matieres :</td><td> <select name='liste'><option value=0> choisissez votre matiere </option>";
				while($ligne_matiere = mysql_fetch_array($resultat_matiere))
				{
					print "<option value='".$ligne_matiere['id-matiere']."'>".$ligne_matiere['intitule']."</option>";
				}
				print "</select></td></tr>";
				print("<tr><td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"note\" value=\"Valider\"></td>\n") ;
				print "</tr></table></form>";
				// fin de la liste
			}
		}
		
		/* enreg note ? */
		if($_GET['a'] == 'enregnote')
		{
			$id_diplome1 = DB_Query('SELECT `id-diplome` FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
			$id_diplome2 = mysql_fetch_array($id_diplome1);
			$id_diplome = $id_diplome2['id-diplome'];
			require 'fonction_csv.inc.php';
			if(isset($_GET['id']))
			{
				$id_matiere =$_GET['id'];
			}
			else
			{
				$id_matiere = $_POST['liste'];
			}
			$requette = "select * from matiere where `id-matiere`='".$id_matiere."'";
			$res = DB_Query($requette);
			$liste = mysql_fetch_array($res);
			$nomfic = $id_matiere.$liste['intitule'].".csv";
			if(!isset($_GET['b']))
			{
				if(file_exists("Evaluation\\".$nomfic))	// test l'existence du fichier
				{
					print "<table><tr><td>un fichier existe deja pour cette matiere que souhaitez vous faire : <a href='espacereserve.php?w=enseignants&a=enregnote&b=modif&id=".$_POST['liste']."'>Modifier</a>&nbsp;&nbsp;&nbsp;<a href='espacereserve.php?w=enseignants&a=enregnote&b=no&id=".$_POST['liste']."'>Nouveau</a></td></tr></table>";
				}
				else
				{
					$requette_selection = "select * from etudiant where `id-etudiant` in (select `id-etudiant` from inscrit where `id-diplome` = '".$id_diplome."' ) ";
					//  echo $requette_selection;
					$resu = DB_Query($requette_selection);
					print("<center><form name=\"enote\" action=\"espacereserve.php?w=enseignants&a=enote\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
					print "<table>";
					print "<tr><td>nom</td><td>prenom</td><td>note</td></tr>";
					while($liste = mysql_fetch_array($resu))
					{
						print "<tr><td>".$liste['nom']."</td><td>".$liste['prenom']."</td><td><input class=\"defaultInput\" name='".$liste['nom']."note' size=\"5\"></td></tr>";
					}
					print "<input type=\"hidden\" name=\"file\" value='".$nomfic."'>";
					print("<tr><td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"enote\" value=\"Valider\"></td>\n") ;
					print "</tr></table></form>";
				}
			}
			else
			{
				if($_GET['b'] == 'modif')
				{
					$tableau = traite_fichier("Evaluation\\".$nomfic);
					print("<center><form name=\"enote\" action=\"espacereserve.php?w=enseignants&a=enote&b=mo\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
					print "<table>";
					print "<tr><td>numero</td><td>nom</td><td>prenom</td><td>note</td></tr>";
					for($i=1;$i<count($tableau);$i++)
					{
						print "<tr><td>".$tableau[$i][0]."</td><td>".$tableau[$i][1]."</td><td>".$tableau[$i][2]."</td><td><input class=\"defaultInput\" name='".$i."note' size=\"5\" value='".$tableau[$i][3]."'></td></tr>";
						print "<input type=\"hidden\" name='".$i."num' value='".$tableau[$i][0]."'>";
						print "<input type=\"hidden\" name='".$i."nom' value='".$tableau[$i][1]."'>";
						print "<input type=\"hidden\" name='".$i."prenom' value='".$tableau[$i][2]."'>";
					}
					print "<input type=\"hidden\" name='tab' value='".$i."'>";
					print "<input type=\"hidden\" name=\"file\" value='".$nomfic."'>";
					print("<tr><td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"enote\" value=\"Valider\"></td>\n") ;
					print "</tr></table></form>";
				}
				else
				{
					$requette_selection = "select * from etudiant where `id-etudiant` in (select `id-etudiant` from inscrit where `id-diplome` = '".$id_diplome."' ) ";
					$resu = DB_Query($requette_selection);
					print("<center><form name=\"enote\" action=\"espacereserve.php?w=enseignants&a=enote\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
					print "<table>";
					print "<tr><td>nom</td><td>prenom</td><td>note</td></tr>";
					while($liste = mysql_fetch_array($resu))
					{
						print "<tr><td>".$liste['nom']."</td><td>".$liste['prenom']."</td><td><input class=\"defaultInput\" name='".$liste['nom']."note' size=\"5\"></td></tr>";
					}
					print "<input type=\"hidden\" name=\"file\" value='".$nomfic."'>";
					print("<tr><td width=\"200\" align=\"center\"><br><input class=\"defaultButton\" type=\"submit\" name=\"enote\" value=\"Valider\"></td>\n") ;
					print "</tr></table></form>";
				}
			}
		}
		
		/* eNote ? */
		if($_GET['a'] == 'enote')
		{
			if(isset($_GET['b']))
			{
				$fp = fopen("Evaluation\\".html_entity_decode($_POST['file']),"w+");
				$chaine = "Numero;Nom;Prenom;Note\n";
				fwrite($fp,$chaine);
				for($i=1;$i<$_POST['tab'];$i++)
				{
					$chaine = "";
					$chaine .=$_POST[$i.'num'].";".$_POST[$i.'nom'].";".$_POST[$i.'prenom'].";".$_POST[$i.'note']."\n";
					fwrite($fp, $chaine);
				}
				fclose($fp);
				print "<table><tr><td>enregistrement effectuer avec succes</td></tr></table>";
				print "<table align='center'><tr><td><a href='Evaluation/".$_POST['file']."'>copie du fichier</a></td></tr></table>";
			}
			else
			{
				$fp = fopen("Evaluation\\".html_entity_decode($_POST['file']),"w+");
				$chaine = "Numero;Nom;Prenom;Note\n";
				fwrite($fp,$chaine);
				$id_diplome1 = DB_Query('SELECT `id-diplome` FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
				$id_diplome2 = mysql_fetch_array($id_diplome1);
				$id_diplome = $id_diplome2['id-diplome'];
				$requette_selection = "select * from etudiant where `id-etudiant` in (select `id-etudiant` from inscrit where `id-diplome` = '".$id_diplome."' ) ";
				$resu = DB_Query($requette_selection);
				while($liste = mysql_fetch_array($resu))
				{
					$chaine = "";
					$chaine .=$liste['id-etudiant'].";".$liste['nom'].";".$liste['prenom'].";".$_POST[$liste['nom'].'note']."\n";
					fwrite($fp, $chaine);
				}
				fclose($fp);
				print "<table><tr><td>enregistrement effectuer avec succes</td></tr></table>";
				print "<table align='center'><tr><td><a href='Evaluation/".$_POST['file']."'>copie du fichier</a></td></tr></table>";
			}
		}		
		
		/*******************************************************************
		*     Envoi du mail a tous les etudiants qui suivent la matiere     
		*******************************************************************/
		if ($_GET['a'] == "mail")
		{
			/* formulaire */
			if($_GET['b'] == 'form')
			{
				print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("<tr>\n") ;
				print("<td align=\"center\" width=\"800\"><br><b> Envoie d'un mail <br><br></b></td>") ;
				print("</tr>\n") ;
				print("</table>\n") ;
				print("<center><form method=\"post\" action=\"espacereserve.php?p=connexion&w=enseignants&a=mail&b=envoie&mat=".$_GET['mat']."\" >\n") ;
				print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("<tr>\n") ;
				print("<td align=\"left\"><b> Sujet </b></td><td width=\"700\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"sujet\" size=\"40\"></td>\n") ;
				print("</tr>\n") ;
				print("<tr>\n") ;
				print("<td align=\"left\" colspan=\"3\"><b> Contenu</b></td>\n") ;
				print("</tr>\n") ;
				print("<tr>\n") ;
				print("<td width=\"800\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"50\" name=\"contenu\"></textarea><br><br></td>\n") ;
				print("</tr>\n") ;
				print("<tr>\n") ;
				print("<td width=\"800\" align=\"left\" colspan=\"3\"><br><input type=hidden name=subject value=formmail><input class=\"defaultButton\" type=\"submit\" value=\"Envoyer\"\"> - <input class=\"defaultButton\" type=\"reset\" value=\"Annuler\"></td>\n") ;
				print("</tr>\n") ;
				print("</table>\n") ;
				print("</form></center>\n") ;
			}
			
			/* envoi */
			if($_GET['b'] == 'envoie')
			{
				$requeteMail = DB_Query('SELECT etu.email FROM Etudiant etu, Inscrit ins, Module mo, Matiere mat 
						WHERE (etu.`id-etudiant` = ins.`id-etudiant`)
						and (ins.`id-diplome` = mo.`id-diplome`)
						and (mo.`id-module` = mat.`id-module`)
						and (mat.`id-matiere` = "'.$_GET['mat'].'")');
				$entete="FROM : ".$_SESSION['nom']." ".$_SESSION['prenom']." ";
				while ($tableau=mysql_fetch_array($requeteMail))
				{
					mail($tableau['email'],$_POST['sujet'],$_POST['contenu'],$entete);
				}
				print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
				print("<tr>\n") ;
				print("<td align=\"center\" width=\"800\"><br>Votre mail a ete envoy&eacute; &agrave; tous les &eacute;l&egrave;ves avec succes. Redirection...</td>") ;
				print("</tr>\n") ;
				print("</table>\n") ;
				print("<meta http-equiv=\"refresh\" content=\"3;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;
			}
		}
		
		/*********************
		*     Deconnexion     
		*********************/
		if ($_GET['a'] == "logout")
		{
			session_destroy() ;
			mysql_error();
			print("<table><td><tr>") ;
			print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion\">\n") ;
			print("</td></tr></table>") ;
		}
	}
	
	
	if($_GET['a']=='visu')
	{
		print("<table align='center'><tr><td><a href=\"{$_GET['fichier']}\">Visualiser</a></td></tr></table>");
		fopen(realpath($_GET['fichier']), 'r');
	}
	if (isset($_POST['fileUndep']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			print("<table><tr><td>") ;
			print "Aucun fichier selectionn&eacute;, redirection..." ;
			print("</td></tr></table>") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=espacereserve.php?p=connexion&w=enseignant&a=undep\">\n") ;
			return ;
		}
		foreach ($_POST['id'] as $idKey)
		{
			$requette = ("select * from fichier WHERE `id-fichier` = ".$idKey);
			$res = db_query($requette);
			$ligne = mysql_fetch_array($res);
			$chaine = explode(" ",$_SESSION['diplome']);
			$finalchaine = $chaine[0]."".$chaine[1];
			@unlink("Data/Telechargement/{$finalchaine}/".$ligne['URL']);
			$req = "delete from fichier where `id-fichier` ='".$idKey."'";
			$ress = DB_query($req);
		}
		print("<table><tr><td>") ;
		print "Fichiers(s) supprim&eacute;(s) avec succ&egrave;s, redirection" ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=espacereserve.php?p=connexion&w=enseignants\">\n") ;
		print("</td></tr></table>") ;
	} //fin du if suppression
	print("<center><table><tr><td><br><br><a href='espacereserve.php?p=connexion&w=enseignants'>retour</a></td></tr></table></center>");
}
else
{
	print("<table><tr><td>") ;
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
	print("</td></tr></table>") ;
}
/*
** EOF enseignants
*/
?>

