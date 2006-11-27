<?php
/*
** Fichier : enseignant
** Date de creation : 15/08/2004
** Auteurs : Avetisyan Gohar
** Version : 1.0
** Auteurs : julien SIEGA,emilien PERICO
** version : 2.0
** Description : Fichier inclu charge de la gestion des fichiers par un enseignant
**	depot, suppression
*/
// on verifie toujours que cette page a ete appelee a partir de index
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
	} // fin de if (!isset($_GET['a']))
	// une action est precisee
	else*/
	if (isset($_GET['a']))
	{
		// test
		if ($_GET['a'] == "acces")
		{
			print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("<tr>\n") ;
			print("<td align=\"center\" width=\"800\"><br><b> Coucou<br><br></b></td>") ;
			print("</tr>\n") ;
			print("</table>\n") ;
		}
		// ajout d'un element
		if ($_GET['a'] == "dep")
		{
			// dbConnect();
			//require("Functions/edition.inc.php") ;
			print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
			print("<tr>\n") ;
			print("<td align=\"center\" width=\"800\"><br><b> D&eacute;p&ocirc;t de fichiers<br><br></b></td>") ;
			print("</tr>\n") ;
			print("</table>\n") ;
			print("<center><form name=\"fichierForm\" action=\"espacereserve.php?p=connexion&w=enseignants&a=add\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
			print("<table width=\"800\" cellspacing=\"3\" cellpadding=\"0\">\n") ;
			//Pour afficher les matieres suivant le diplome choisi
			//on retrouve l'id de diplome correspondant
			$id_diplome = DB_Query('SELECT `id-diplome` FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
			$id_diplome = mysql_fetch_array($id_diplome);
			$id_diplome = $id_diplome['id-diplome'];
			$_SESSION['id-dip'] = $id_diplome;
			//on recherche les module de ce diplome
			$moduleList = DB_Query('SELECT `id-module` FROM module WHERE `id-diplome` = "'.$id_diplome.'"');
			$moduleCount = mysql_num_rows($moduleList);
			if ($moduleCount > 0 )
			{
				print("<tr>\n") ;
				print("<td align=\"left\"><b> Mati&egrave;re *</b></td><td width=\"700\" align=\"left\"><select class=\"defaultInput\" name=\"matiere\">") ;
				for($i = 0; $i<$moduleCount; $i++)
				{
					$fModuleList = mysql_fetch_array($moduleList);
					//on peut acceder au id-module un par un
					print("i : ".$fModuleList['id-module']."<br>");
					//pour chaque module on affiche les matiere de ce module
					$matiereList = DB_Query('SELECT `id-matiere`, intitule FROM matiere WHERE `id-module` = "'.$fModuleList['id-module'].'"	ORDER BY `id-module`');
					$matiereCount = mysql_num_rows($matiereList);
					if ($matiereCount > 0)
					{
						for ($j=0; $j<$matiereCount; $j++)
						{
							$fMatiereList = mysql_fetch_array($matiereList);
							//print("j : ".$fMatiereList['id-matiere']." matiere : ".$fMatiereList['intitule']."<br>");
							print("<option> {$fMatiereList['intitule']} </option>") ;
						}
					}
				}
				print("<br><br></select></td>\n</tr>\n") ;
			}
			print("<tr>\n") ;
			// Le champ titre doit etre renseigné (controle par script récupere sur internet)
			print("<td align=\"left\"><b> Titre </b></td><td width=\"700\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"titreDepot\" size=\"40\"></td>\n") ;
			print("</tr>\n") ;
			print("<tr>\n") ;
			print("<td align=\"left\"><b> Fichier </b></td>\n") ;
			//voir la possibilité d'uploader plusieurs fichiers
			//for ($i=0; $i<5; $i++)
			//{
			print("<td width=\"700\" align=\"left\" colspan=\"3\"><input type=\"file\" name=\"fichierDepot\" class=\"defaultInput\" size=\"40\"></td>\n") ;
			print("</tr>\n") ;
			//}
			print("<tr>\n") ;
			print("<td align=\"left\" colspan=\"3\"><b> Commentaire *</b></td>\n") ;
			print("</tr>\n") ;
			print("<tr>\n") ;
			print("<td width=\"800\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"50\" name=\"commentaireDepot\"></textarea><br><br></td>\n") ;
			print("</tr>\n") ;
			print("<tr>\n") ;
			print("<td width=\"800\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"fileDep\" value=\"true\"><input class=\"defaultButton\" type=\"submit\" name=\"addButton\" value=\"D&eacute;poser\" onClick=\"checFileDep('fichierForm')\"></td>\n") ;
			print("</tr>\n") ;
			print("</table>\n") ;
			print("</form></center>\n") ;
		}
		// suppression d'un element
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
				print("</td></tr>\n") ;
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
		} // end of if del
		if($_GET['a']== 'excel')
		{
			print("<center><form name=\"valideExcel\" action=\"espacereserve.php?w=enseignants&a=enregexcel\" method=\"post\" enctype=\"multipart/form-data\">\n") ;
			print ("<table>");
			print("<tr><td> selection du fichier :</td><td width=\"700\" align=\"left\" colspan=\"3\"><input type=\"file\" name=\"fichierexcel\" class=\"defaultInput\" size=\"40\"></td></tr>") ;
			print("<tr><td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"fileexcel\" value=\"Valider\"></td>\n") ;
			print("</tr>\n") ;
			print ("</table>");
			print("</form></center>\n") ;
		}
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
		/*-----------------------------------------------------------------------------------------
		partie visualisation des fichiers
		------------------------------------------------------------------------------------------*/
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
		//----------------------------- fin de la partie visualisation
		/*--------------------------------------------------------------------------------------------
		partie qui permet de gerer les notes 
		---------------------------------------------------------------------------------------------*/
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
				print("<tr><td>") ;
				print("aucune matiere disponible, redirection ...") ;
				print("</td></tr>") ;
				print("<tr><td>") ;
				print("<meta http-equiv='refresh' content='2;url=espacereserve.php?p=connexion&w=enseignants'>");
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
		// deconnexion
		if ($_GET['a'] == "logout")
		{
			// rien de critique
			session_destroy() ;mysql_error();
			//$SESSION['ensConnecte'] = false;
			//$SESSION['etuConnecte'] = false;
			// redirection
			//infoMessage(3, 3, "Fermeture de session...") ;
			print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion\">\n") ;
		} //end of if($_GET['a'] == "logout")
	}
	if (isset($_POST['fileDep']))
	{
		///print("on est dans database enseignant");
		// donnees correctes : traitement et ajout
		//	dbConnect() ;			
		//on ne trim pas le titre ni la matiere car il peut y avoir des espaces
		$fMatiere = addslashes($_POST['matiere']);
		$fileTitre = addslashes($_POST['requiredtitreDepot']) ;
		$fileCommentaire = addslashes($_POST['commentaireDepot']) ;
		$fileURL = $_FILES['fichierDepot']['name'];
		$fileURLT = $_FILES['fichierDepot']['tmp_name'];
		$requette = ('SELECT * FROM diplome WHERE intitule ="'.$_SESSION['diplome'].'"');
		$resultat = db_query($requette);
		$ligne = mysql_fetch_array($resultat);
		// extension du fichier
		$extension = explode("\\", $fileURL) ;
		$parts = count($extension) ;
		$finalExtension = $extension[$parts - 1] ;
		$string = explode(" ", $finalExtension);
		$finalstring = "";
		for($i=0;$i<count($string);$i++)
		{
			$finalstring .= $string[$i];
		}
		$chaine = explode(" ",$ligne['intitule']);
		$finalchaine = $chaine[0]."".$chaine[1];
		if (is_uploaded_file($fileURLT))
		{
			// teste de toutes les facons
			$success = move_uploaded_file($fileURLT, "Data/Telechargement/{$finalchaine}/".$finalstring) ;
			chmod("Data/Telechargement/{$finalchaine}/".$finalstring, 0755);
			//if ($success) { $finalCV = $fileId."_fich.".$finalExtension ; }
			$fMatiereId = DB_Query('SELECT `id-matiere` FROM matiere WHERE intitule = "'.$fMatiere.'"');
			$fMatiereId = mysql_fetch_array($fMatiereId);
			$fMatiereId = $fMatiereId['id-matiere'];
			DB_Query('INSERT INTO fichier	VALUES (NULL, "'.$fileTitre.'", "'.$ligne['id-diplome'].'", "'.$_SESSION['id-enseignant'].'", "'.$finalstring.'", "'.$fileCommentaire.'")') ;
			// felicitations et redirection
			print("<table><tr><td>Fichier d&eacute;pos&eacute; avec succ&egrave;s</td></tr></table>") ;
			$chaine = explode(" ",$ligne['intitule']);
			$finalchaine = $chaine[0]."".$chaine[1];
		}
		else
		{
			print "<table align=\"center\"><tr><td>";
			print("L'envoi du fichier a &eacute;chou&eacute;.");
			print "</td></tr></table>";
		}
		// dbClose() ;
		}//fin du if (isset($_POST['fileDep']))
		// if qui concerne la visualisation des fichiers deposés
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
	print("<table><tr><td><br><br><a href='espacereserve.php?p=connexion&w=enseignants'>retour</a></td></tr></table>");
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

